<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Order;
use App\Models\Stock;
use App\Models\StockTransaction;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function getStats()
    {
        try {
            $now = Carbon::now();

            // 1. Data Sales (Current Month vs Last Month)
            $currentMonthSales = Invoice::whereMonth('created_at', $now->month)
                ->whereYear('created_at', $now->year)
                ->sum('net_total');

            $lastMonth = Carbon::now()->subMonth();
            $lastMonthSales = Invoice::whereMonth('created_at', $lastMonth->month)
                ->whereYear('created_at', $lastMonth->year)
                ->sum('net_total');

            $salesPct = 0;
            if ($lastMonthSales > 0) {
                $salesPct = (($currentMonthSales - $lastMonthSales) / $lastMonthSales) * 100;
            } elseif ($currentMonthSales > 0) {
                $salesPct = 100;
            }

            // 2. Profit (Revenue - COGS)
            // Current Month
            $currentMonthInvoiceIds = Invoice::whereMonth('created_at', $now->month)
                ->whereYear('created_at', $now->year)
                ->pluck('invoice_id');
            $currentMonthRevenue = Invoice::whereIn('invoice_id', $currentMonthInvoiceIds)->sum('net_total');
            $currentMonthCOGS = InvoiceItem::whereIn('invoice_id', $currentMonthInvoiceIds)
                ->join('products', 'invoice_items.product_id', '=', 'products.product_id')
                ->sum(DB::raw('invoice_items.qty * products.cost_price'));
            $currentMonthProfit = $currentMonthRevenue - $currentMonthCOGS;

            // Last Month
            $lastMonthInvoiceIds = Invoice::whereMonth('created_at', $lastMonth->month)
                ->whereYear('created_at', $lastMonth->year)
                ->pluck('invoice_id');
            $lastMonthRevenue = Invoice::whereIn('invoice_id', $lastMonthInvoiceIds)->sum('net_total');
            $lastMonthCOGS = InvoiceItem::whereIn('invoice_id', $lastMonthInvoiceIds)
                ->join('products', 'invoice_items.product_id', '=', 'products.product_id')
                ->sum(DB::raw('invoice_items.qty * products.cost_price'));
            $lastMonthProfit = $lastMonthRevenue - $lastMonthCOGS;

            $profitPct = 0;
            if ($lastMonthProfit > 0) {
                $profitPct = (($currentMonthProfit - $lastMonthProfit) / $lastMonthProfit) * 100;
            } elseif ($currentMonthProfit > 0) {
                $profitPct = 100;
            }

            // 3. Pending Orders (Count & Weekly Trend)
            $pendingOrdersCount = Order::where('status', 'Pending')->count();

            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();
            $thisWeekPending = Order::where('status', 'Pending')
                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->count();

            $startOfLastWeek = Carbon::now()->subWeek()->startOfWeek();
            $endOfLastWeek = Carbon::now()->subWeek()->endOfWeek();
            $lastWeekPending = Order::where('status', 'Pending')
                ->whereBetween('created_at', [$startOfLastWeek, $endOfLastWeek])
                ->count();

            $pendingPct = 0;
            if ($lastWeekPending > 0) {
                $pendingPct = (($thisWeekPending - $lastWeekPending) / $lastWeekPending) * 100;
            } elseif ($thisWeekPending > 0) {
                $pendingPct = 100;
            }

            // 4. Low Stock count
            $lowStockCount = Stock::whereRaw('quantity <= minimum_stock_level')->count();

            return response()->json([
                'sales' => [
                    'value' => round($currentMonthSales, 2),
                    'percentage' => round($salesPct, 2),
                    'trend' => $salesPct >= 0 ? 'up' : 'down'
                ],
                'profit' => [
                    'value' => round($currentMonthProfit, 2),
                    'percentage' => round($profitPct, 2),
                    'trend' => $profitPct >= 0 ? 'up' : 'down'
                ],
                'pendingOrders' => [
                    'value' => $pendingOrdersCount,
                    'percentage' => round($pendingPct, 2),
                    'trend' => $pendingPct >= 0 ? 'up' : 'down'
                ],
                'lowStock' => [
                    'value' => $lowStockCount
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch dashboard stats', 'error' => $e->getMessage()], 500);
        }
    }

    public function getSalesData()
    {
        try {
            $salesData = [];
            $maxSales = 0;

            for ($i = 0; $i < 5; $i++) {
                $targetMonth = Carbon::now()->subMonths($i);
                $sales = Invoice::whereMonth('created_at', $targetMonth->month)
                    ->whereYear('created_at', $targetMonth->year)
                    ->sum('net_total');

                $maxSales = max($maxSales, $sales);

                $label = $i === 0 ? 'Current Month' : ($i === 1 ? 'Last Month' : $targetMonth->format('F Y') . ' (' . $i . ' Months Ago)');
                if ($i > 1) {
                    $label = $i . ' Months Ago';
                }

                $salesData[] = [
                    'label' => $label,
                    'value' => round($sales, 2)
                ];
            }

            // Calculate percentage relative to the max sales month
            foreach ($salesData as &$data) {
                $data['percentage'] = $maxSales > 0 ? round(($data['value'] / $maxSales) * 100) : 0;
            }

            return response()->json($salesData, 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch sales overview data', 'error' => $e->getMessage()], 500);
        }
    }

    public function getStockStatus()
    {
        try {
            $available = Stock::sum('quantity');
            $pendingOrdersCount = Order::where('status', 'Pending')->count();
            
            // Estimate reserved stock based on pending orders
            $reserved = $pendingOrdersCount * 15;
            $spot = Stock::whereRaw('quantity <= minimum_stock_level')->count();

            return response()->json([
                'available' => $available,
                'reserved' => $reserved,
                'spot' => $spot
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch stock status data', 'error' => $e->getMessage()], 500);
        }
    }

    public function getRecentActivity()
    {
        try {
            // Get recent invoices
            $invoices = Invoice::orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($inv) {
                    return [
                        'type' => 'invoice',
                        'text' => "New sale completed - Invoice #{$inv->invoice_number}",
                        'time' => $inv->created_at->diffForHumans(),
                        'created_at' => $inv->created_at
                    ];
                });

            // Get recent stock transactions
            $transactions = StockTransaction::orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($tx) {
                    $typeLabel = $tx->transaction_type === 'initial' ? 'Stock initialised' : 'Stock adjusted';
                    return [
                        'type' => 'stock',
                        'text' => "{$typeLabel} - Product {$tx->product_name} (Qty: {$tx->quantity_changed})",
                        'time' => $tx->created_at->diffForHumans(),
                        'created_at' => $tx->created_at
                    ];
                });

            // Get recent customers
            $customers = Customer::orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($cus) {
                    return [
                        'type' => 'customer',
                        'text' => "New customer registered - {$cus->first_name} {$cus->last_name}",
                        'time' => $cus->created_at->diffForHumans(),
                        'created_at' => $cus->created_at
                    ];
                });

            // Combine, sort, and take top 5
            $activities = collect()
                ->concat($invoices)
                ->concat($transactions)
                ->concat($customers)
                ->sortByDesc('created_at')
                ->take(5)
                ->values();

            return response()->json($activities, 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch recent activity', 'error' => $e->getMessage()], 500);
        }
    }
}
