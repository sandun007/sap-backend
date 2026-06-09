<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Stock;
use App\Models\StockTransaction;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Get consolidated dashboard statistics.
     */
    public function getStats()
    {
        // 1. Define Date Ranges
        $startOfThisMonth = Carbon::now()->startOfMonth();
        $endOfThisMonth = Carbon::now()->endOfMonth();
        
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        // 2. Calculate Sales & Growth
        $salesThisMonth = (float) Invoice::whereBetween('created_at', [$startOfThisMonth, $endOfThisMonth])
            ->where('status', 'paid')
            ->sum('net_total');

        $salesLastMonth = (float) Invoice::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->where('status', 'paid')
            ->sum('net_total');

        $salesGrowth = 0.0;
        if ($salesLastMonth > 0) {
            $salesGrowth = (($salesThisMonth - $salesLastMonth) / $salesLastMonth) * 100;
        } elseif ($salesThisMonth > 0) {
            $salesGrowth = 100.0;
        }

        // 3. Calculate Profit & Growth
        // Profit = Quantity * (Price - Cost Price)
        $profitThisMonth = (float) InvoiceItem::whereHas('invoice', function ($query) use ($startOfThisMonth, $endOfThisMonth) {
            $query->whereBetween('created_at', [$startOfThisMonth, $endOfThisMonth])
                  ->where('status', 'paid');
        })
        ->join('products', 'invoice_items.product_id', '=', 'products.product_id')
        ->sum(DB::raw('invoice_items.qty * (invoice_items.price - COALESCE(products.cost_price, 0))'));

        $profitLastMonth = (float) InvoiceItem::whereHas('invoice', function ($query) use ($startOfLastMonth, $endOfLastMonth) {
            $query->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
                  ->where('status', 'paid');
        })
        ->join('products', 'invoice_items.product_id', '=', 'products.product_id')
        ->sum(DB::raw('invoice_items.qty * (invoice_items.price - COALESCE(products.cost_price, 0))'));

        $profitGrowth = 0.0;
        if ($profitLastMonth > 0) {
            $profitGrowth = (($profitThisMonth - $profitLastMonth) / $profitLastMonth) * 100;
        } elseif ($profitThisMonth > 0) {
            $profitGrowth = 100.0;
        }

        // 4. Pending Orders
        $pendingOrdersCount = Order::where('status', 'pending')->count();
        
        // Orders growth (this week vs last week)
        $startOfThisWeek = Carbon::now()->startOfWeek();
        $endOfThisWeek = Carbon::now()->endOfWeek();
        $startOfLastWeek = Carbon::now()->subWeek()->startOfWeek();
        $endOfLastWeek = Carbon::now()->subWeek()->endOfWeek();

        $ordersThisWeek = Order::whereBetween('created_at', [$startOfThisWeek, $endOfThisWeek])->count();
        $ordersLastWeek = Order::whereBetween('created_at', [$startOfLastWeek, $endOfLastWeek])->count();

        $ordersGrowth = 0.0;
        if ($ordersLastWeek > 0) {
            $ordersGrowth = (($ordersThisWeek - $ordersLastWeek) / $ordersLastWeek) * 100;
        } elseif ($ordersThisWeek > 0) {
            $ordersGrowth = 100.0;
        }

        // 5. Low Stock Count
        $lowStockCount = Stock::whereColumn('quantity', '<=', 'minimum_stock_level')->count();

        // 6. Sales Overview (Last 5 Months)
        $salesOverview = [];
        for ($i = 4; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            
            $monthlySales = Invoice::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->where('status', 'paid')
                ->sum('net_total');
                
            $salesOverview[] = [
                'label' => $date->format('F'),
                'sales' => (float) $monthlySales,
            ];
        }

        // 7. Stock Status
        $availableQty = (int) Stock::sum('quantity');
        $reservedQty = (int) Order::where('status', 'pending')->count();
        $spotQty = (int) Stock::whereColumn('quantity', '<=', 'minimum_stock_level')->count();
        $totalStockStatusQty = $availableQty + $reservedQty + $spotQty;

        $availablePct = 0.0;
        $reservedPct = 0.0;
        $spotPct = 0.0;

        if ($totalStockStatusQty > 0) {
            $availablePct = round(($availableQty / $totalStockStatusQty) * 100, 1);
            $reservedPct = round(($reservedQty / $totalStockStatusQty) * 100, 1);
            $spotPct = round(($spotQty / $totalStockStatusQty) * 100, 1);
        }

        // 8. Recent Activity
        $activities = collect();

        // Fetch latest invoices
        $latestInvoices = Invoice::latest()->take(5)->get();
        foreach ($latestInvoices as $invoice) {
            $activities->push([
                'id' => 'invoice_' . $invoice->invoice_id,
                'type' => 'invoice',
                'title' => "New sale completed - Invoice #{$invoice->invoice_number}",
                'description' => "Amount: Rs. " . number_format($invoice->net_total, 2),
                'icon' => 'plus',
                'color' => 'green',
                'time' => $invoice->created_at->toIso8601String(),
                'timestamp' => $invoice->created_at->timestamp,
            ]);
        }

        // Fetch latest stock transactions
        $latestTransactions = StockTransaction::latest()->take(5)->get();
        foreach ($latestTransactions as $tx) {
            $typeLabel = $tx->transaction_type === 'add' ? 'added to' : 'removed from';
            $activities->push([
                'id' => 'tx_' . $tx->transaction_id,
                'type' => 'stock_transaction',
                'title' => "Stock update - " . ucfirst($tx->transaction_type),
                'description' => "{$tx->quantity_changed} units {$typeLabel} {$tx->product_name}",
                'icon' => $tx->transaction_type === 'add' ? 'plus' : 'minus',
                'color' => $tx->transaction_type === 'add' ? 'blue' : 'yellow',
                'time' => $tx->created_at->toIso8601String(),
                'timestamp' => $tx->created_at->timestamp,
            ]);
        }

        // Fetch latest critical stock items
        $latestLowStocks = Stock::whereColumn('quantity', '<=', 'minimum_stock_level')
            ->latest('updated_at')
            ->take(5)
            ->get();
        foreach ($latestLowStocks as $stock) {
            $activities->push([
                'id' => 'low_' . $stock->stock_id,
                'type' => 'low_stock',
                'title' => "Low stock alert - {$stock->product_name}",
                'description' => "Only {$stock->quantity} items remaining (min level: {$stock->minimum_stock_level})",
                'icon' => 'alert',
                'color' => 'red',
                'time' => $stock->updated_at->toIso8601String(),
                'timestamp' => $stock->updated_at->timestamp,
            ]);
        }

        // Sort by timestamp desc and take 5
        $recentActivity = $activities->sortByDesc('timestamp')->take(5)->values()->all();

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => [
                    'total_sales' => $salesThisMonth,
                    'sales_growth' => $salesGrowth,
                    'total_profit' => $profitThisMonth,
                    'profit_growth' => $profitGrowth,
                    'pending_orders' => $pendingOrdersCount,
                    'orders_growth' => $ordersGrowth,
                    'low_stock' => $lowStockCount,
                ],
                'sales_overview' => $salesOverview,
                'stock_status' => [
                    'available' => $availableQty,
                    'available_pct' => $availablePct,
                    'reserved' => $reservedQty,
                    'reserved_pct' => $reservedPct,
                    'spot' => $spotQty,
                    'spot_pct' => $spotPct,
                ],
                'recent_activity' => $recentActivity,
            ]
        ]);
    }
}
