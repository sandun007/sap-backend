<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesReportController extends Controller
{
    public function getSalesReport(Request $request)
    {
        try {
            $startDate = $request->query('start_date');
            $endDate = $request->query('end_date');
            $groupBy = $request->query('group_by', 'date'); // date, invoice, item
            $productId = $request->query('product_id');

            // Default to current month if no date range is provided
            if (!$startDate) {
                $startDate = Carbon::now()->startOfMonth()->toDateString();
            }
            if (!$endDate) {
                $endDate = Carbon::now()->endOfMonth()->toDateString();
            }

            // 1. Calculate Summary Stats for the date range
            // Total Revenue
            $totalSales = Invoice::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->sum('net_total');

            // Total Invoice Count
            $totalInvoices = Invoice::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->count();

            // Total Profit = Sales Revenue - Cost Price of sold items
            $totalProfit = InvoiceItem::join('invoices', 'invoice_items.invoice_id', '=', 'invoices.invoice_id')
                ->join('products', 'invoice_items.product_id', '=', 'products.product_id')
                ->whereBetween('invoices.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->sum(DB::raw('invoice_items.qty * (invoice_items.price - products.cost_price)'));

            // 2. Fetch Grouped Data
            $reportData = [];

            if ($groupBy === 'date') {
                // Group sales and invoice count by date
                $salesByDate = DB::table('invoices')
                    ->select(
                        DB::raw('DATE(created_at) as date_group'),
                        DB::raw('SUM(net_total) as total_sales'),
                        DB::raw('COUNT(invoice_id) as total_invoices')
                    )
                    ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->groupBy(DB::raw('DATE(created_at)'))
                    ->orderBy('date_group', 'desc')
                    ->get();

                // Group profit by date
                $profitByDate = DB::table('invoice_items')
                    ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.invoice_id')
                    ->join('products', 'invoice_items.product_id', '=', 'products.product_id')
                    ->select(
                        DB::raw('DATE(invoices.created_at) as date_group'),
                        DB::raw('SUM(invoice_items.qty * (invoice_items.price - products.cost_price)) as profit')
                    )
                    ->whereBetween('invoices.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->groupBy(DB::raw('DATE(invoices.created_at)'))
                    ->get()
                    ->pluck('profit', 'date_group');

                $reportData = $salesByDate->map(function ($row) use ($profitByDate) {
                    $dateStr = $row->date_group;
                    return [
                        'date' => $dateStr,
                        'sales' => (float)$row->total_sales,
                        'invoices_count' => (int)$row->total_invoices,
                        'profit' => (float)($profitByDate[$dateStr] ?? 0),
                    ];
                });

            } elseif ($groupBy === 'invoice') {
                // Fetch invoices in date range
                $invoices = Invoice::with(['items.product'])
                    ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->orderBy('created_at', 'desc')
                    ->get();

                $reportData = $invoices->map(function ($inv) {
                    $profit = 0;
                    foreach ($inv->items as $item) {
                        $cost = $item->product->cost_price ?? 0;
                        $profit += $item->qty * ($item->price - $cost);
                    }
                    return [
                        'invoice_id' => $inv->invoice_id,
                        'invoice_number' => $inv->invoice_number,
                        'date' => $inv->created_at->format('Y-m-d H:i:s'),
                        'net_total' => (float)$inv->net_total,
                        'profit' => (float)$profit,
                    ];
                });

            } elseif ($groupBy === 'item') {
                if (!$productId) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Product ID is required for item-wise report.'
                    ], 400);
                }

                $product = Product::findOrFail($productId);
                $costPrice = (float)($product->cost_price ?? 0);

                // Fetch invoice items for this product
                $items = InvoiceItem::with(['invoice'])
                    ->where('product_id', $productId)
                    ->whereHas('invoice', function ($q) use ($startDate, $endDate) {
                        $q->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                    })
                    ->get();

                // Sort by invoice created_at desc
                $items = $items->sortByDesc(function ($item) {
                    return $item->invoice->created_at;
                });

                $reportData = $items->map(function ($item) use ($costPrice) {
                    $profit = $item->qty * ($item->price - $costPrice);
                    return [
                        'date' => $item->invoice->created_at->format('Y-m-d H:i:s'),
                        'invoice_number' => $item->invoice->invoice_number,
                        'qty' => $item->qty,
                        'price' => (float)$item->price,
                        'total' => (float)$item->total,
                        'profit' => (float)$profit,
                    ];
                })->values();
            }

            return response()->json([
                'success' => true,
                'summary' => [
                    'total_sales' => (float)$totalSales,
                    'total_profit' => (float)$totalProfit,
                    'total_invoices' => (int)$totalInvoices,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ],
                'data' => $reportData
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate sales report',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
