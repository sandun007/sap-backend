<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\SalesReturn;
use App\Models\SalesReturnItem;
use App\Models\PurchaseReturn;
use App\Models\StockTransaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProfitReportController extends Controller
{
    public function getProfitReport(Request $request)
    {
        try {
            $startDate = $request->query('start_date');
            $endDate = $request->query('end_date');
            $groupBy = $request->query('group_by', 'date'); // date, product

            if (!$startDate) {
                $startDate = Carbon::now()->startOfMonth()->toDateString();
            }
            if (!$endDate) {
                $endDate = Carbon::now()->endOfMonth()->toDateString();
            }

            // --- 1. Total Metrics Calculations ---
            
            // Total Sales Revenue (Gross Sales)
            $totalSales = (float)Invoice::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->sum('net_total');

            // Total Cost of Goods Sold (COGS)
            $totalCOGS = (float)InvoiceItem::join('invoices', 'invoice_items.invoice_id', '=', 'invoices.invoice_id')
                ->join('products', 'invoice_items.product_id', '=', 'products.product_id')
                ->whereBetween('invoices.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->sum(DB::raw('invoice_items.qty * products.cost_price'));

            // Gross Profit = Gross Sales - COGS
            $grossProfit = $totalSales - $totalCOGS;

            // Customer Refunds (Revenue Deductions)
            $totalRefunds = (float)SalesReturn::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->sum('refund_amount');

            // Inventory Damaged Write-offs (from sales returns)
            $damagedReturnsLoss = (float)SalesReturnItem::join('sales_returns', 'sales_return_items.return_id', '=', 'sales_returns.return_id')
                ->join('products', 'sales_return_items.product_id', '=', 'products.product_id')
                ->where('sales_return_items.condition', 'damaged')
                ->whereBetween('sales_returns.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->sum(DB::raw('sales_return_items.quantity * products.cost_price'));

            // Inventory Adjustment Write-offs (negative discrepancy stock adjustments)
            $adjustmentLoss = (float)StockTransaction::join('products', 'stock_transactions.product_id', '=', 'products.product_id')
                ->where('stock_transactions.transaction_type', 'adjustment')
                ->where('stock_transactions.quantity_changed', '<', 0)
                ->whereBetween('stock_transactions.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->sum(DB::raw('ABS(stock_transactions.quantity_changed) * products.cost_price'));

            // Supplier Claims / Recovery (from purchase returns)
            $supplierClaims = (float)PurchaseReturn::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->sum('total_amount');

            // Total Losses & Expenses = Customer Refunds + Damaged Returns Loss + Stock Adjustment Loss
            $totalLosses = $totalRefunds + $damagedReturnsLoss + $adjustmentLoss;

            // Net Profit = Gross Profit - Total Losses + Supplier Claims
            $netProfit = $grossProfit - $totalLosses + $supplierClaims;

            // --- 2. Grouped Details Calculations ---
            $reportData = [];

            if ($groupBy === 'date') {
                // Generate all dates in the range
                $period = new \DatePeriod(
                    new \DateTime($startDate),
                    new \DateInterval('P1D'),
                    (new \DateTime($endDate))->modify('+1 day')
                );

                $datesList = [];
                foreach ($period as $date) {
                    $datesList[] = $date->format('Y-m-d');
                }

                // Sales by date
                $salesByDate = DB::table('invoices')
                    ->select(DB::raw('DATE(created_at) as date_group'), DB::raw('SUM(net_total) as sales'))
                    ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->groupBy(DB::raw('DATE(created_at)'))
                    ->get()
                    ->pluck('sales', 'date_group');

                // COGS by date
                $cogsByDate = DB::table('invoice_items')
                    ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.invoice_id')
                    ->join('products', 'invoice_items.product_id', '=', 'products.product_id')
                    ->select(DB::raw('DATE(invoices.created_at) as date_group'), DB::raw('SUM(invoice_items.qty * products.cost_price) as cogs'))
                    ->whereBetween('invoices.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->groupBy(DB::raw('DATE(invoices.created_at)'))
                    ->get()
                    ->pluck('cogs', 'date_group');

                // Refunds by date
                $refundsByDate = DB::table('sales_returns')
                    ->select(DB::raw('DATE(created_at) as date_group'), DB::raw('SUM(refund_amount) as refunds'))
                    ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->groupBy(DB::raw('DATE(created_at)'))
                    ->get()
                    ->pluck('refunds', 'date_group');

                // Damaged Returns Loss by date
                $damagedLossByDate = DB::table('sales_return_items')
                    ->join('sales_returns', 'sales_return_items.return_id', '=', 'sales_returns.return_id')
                    ->join('products', 'sales_return_items.product_id', '=', 'products.product_id')
                    ->select(DB::raw('DATE(sales_returns.created_at) as date_group'), DB::raw('SUM(sales_return_items.quantity * products.cost_price) as loss'))
                    ->where('sales_return_items.condition', 'damaged')
                    ->whereBetween('sales_returns.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->groupBy(DB::raw('DATE(sales_returns.created_at)'))
                    ->get()
                    ->pluck('loss', 'date_group');

                // Stock Adjustment Loss by date
                $adjLossByDate = DB::table('stock_transactions')
                    ->join('products', 'stock_transactions.product_id', '=', 'products.product_id')
                    ->select(DB::raw('DATE(stock_transactions.created_at) as date_group'), DB::raw('SUM(ABS(stock_transactions.quantity_changed) * products.cost_price) as loss'))
                    ->where('stock_transactions.transaction_type', 'adjustment')
                    ->where('stock_transactions.quantity_changed', '<', 0)
                    ->whereBetween('stock_transactions.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->groupBy(DB::raw('DATE(stock_transactions.created_at)'))
                    ->get()
                    ->pluck('loss', 'date_group');

                // Supplier Claims by date
                $claimsByDate = DB::table('purchase_returns')
                    ->select(DB::raw('DATE(created_at) as date_group'), DB::raw('SUM(total_amount) as claims'))
                    ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->groupBy(DB::raw('DATE(created_at)'))
                    ->get()
                    ->pluck('claims', 'date_group');

                foreach ($datesList as $dateStr) {
                    $sales = (float)($salesByDate[$dateStr] ?? 0);
                    $cogs = (float)($cogsByDate[$dateStr] ?? 0);
                    $refunds = (float)($refundsByDate[$dateStr] ?? 0);
                    $damagedLoss = (float)($damagedLossByDate[$dateStr] ?? 0);
                    $adjLoss = (float)($adjLossByDate[$dateStr] ?? 0);
                    $claims = (float)($claimsByDate[$dateStr] ?? 0);

                    $gross = $sales - $cogs;
                    $losses = $refunds + $damagedLoss + $adjLoss;
                    $net = $gross - $losses + $claims;

                    $margin = $sales > 0 ? ($net / $sales) * 100 : 0;

                    if ($sales > 0 || $refunds > 0 || $damagedLoss > 0 || $adjLoss > 0 || $claims > 0) {
                        $reportData[] = [
                            'date' => $dateStr,
                            'sales' => $sales,
                            'cogs' => $cogs,
                            'gross_profit' => $gross,
                            'refunds' => $refunds,
                            'write_offs' => $damagedLoss + $adjLoss,
                            'supplier_claims' => $claims,
                            'net_profit' => $net,
                            'margin' => round($margin, 2),
                        ];
                    }
                }

                // Sort descending by date
                usort($reportData, function($a, $b) {
                    return strcmp($b['date'], $a['date']);
                });

            } elseif ($groupBy === 'product') {
                // Fetch product wise sales
                $productSales = DB::table('invoice_items')
                    ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.invoice_id')
                    ->join('products', 'invoice_items.product_id', '=', 'products.product_id')
                    ->select(
                        'invoice_items.product_id',
                        'products.product_code',
                        'products.product_name',
                        DB::raw('SUM(invoice_items.qty) as qty_sold'),
                        DB::raw('SUM(invoice_items.total) as sales_val'),
                        DB::raw('SUM(invoice_items.qty * products.cost_price) as cogs_val')
                    )
                    ->whereBetween('invoices.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->groupBy('invoice_items.product_id', 'products.product_code', 'products.product_name')
                    ->orderBy('sales_val', 'desc')
                    ->get();

                // Damaged sales returns by product
                $productDamagedLosses = DB::table('sales_return_items')
                    ->join('sales_returns', 'sales_return_items.return_id', '=', 'sales_returns.return_id')
                    ->join('products', 'sales_return_items.product_id', '=', 'products.product_id')
                    ->select(
                        'sales_return_items.product_id',
                        DB::raw('SUM(sales_return_items.quantity * products.cost_price) as damaged_loss')
                    )
                    ->where('sales_return_items.condition', 'damaged')
                    ->whereBetween('sales_returns.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->groupBy('sales_return_items.product_id')
                    ->get()
                    ->pluck('damaged_loss', 'product_id');

                // Stock adjustment losses by product
                $productAdjLosses = DB::table('stock_transactions')
                    ->join('products', 'stock_transactions.product_id', '=', 'products.product_id')
                    ->select(
                        'stock_transactions.product_id',
                        DB::raw('SUM(ABS(stock_transactions.quantity_changed) * products.cost_price) as adj_loss')
                    )
                    ->where('stock_transactions.transaction_type', 'adjustment')
                    ->where('stock_transactions.quantity_changed', '<', 0)
                    ->whereBetween('stock_transactions.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->groupBy('stock_transactions.product_id')
                    ->get()
                    ->pluck('adj_loss', 'product_id');

                foreach ($productSales as $row) {
                    $pId = $row->product_id;
                    $sales = (float)$row->sales_val;
                    $cogs = (float)$row->cogs_val;
                    $damagedLoss = (float)($productDamagedLosses[$pId] ?? 0);
                    $adjLoss = (float)($productAdjLosses[$pId] ?? 0);

                    $gross = $sales - $cogs;
                    $net = $gross - ($damagedLoss + $adjLoss);
                    $margin = $sales > 0 ? ($net / $sales) * 100 : 0;

                    $reportData[] = [
                        'product_code' => $row->product_code,
                        'product_name' => $row->product_name,
                        'qty_sold' => (int)$row->qty_sold,
                        'sales' => $sales,
                        'cogs' => $cogs,
                        'gross_profit' => $gross,
                        'write_offs' => $damagedLoss + $adjLoss,
                        'net_profit' => $net,
                        'margin' => round($margin, 2),
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'summary' => [
                    'total_sales' => $totalSales,
                    'total_cogs' => $totalCOGS,
                    'gross_profit' => $grossProfit,
                    'total_refunds' => $totalRefunds,
                    'damaged_returns_loss' => $damagedReturnsLoss,
                    'adjustment_loss' => $adjustmentLoss,
                    'supplier_claims' => $supplierClaims,
                    'total_losses' => $totalLosses,
                    'net_profit' => $netProfit,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ],
                'data' => $reportData
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate profit report',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
