<?php
// app/Http/Controllers/InvoiceController.php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    // GET /api/invoices
    public function index()
    {
        $invoices = Invoice::with('items')
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data'    => $invoices
        ]);
    }

    // GET /api/invoices/{id}
    public function show($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $invoice
        ]);
    }

    // POST /api/invoices
    public function store(Request $request)
    {
        $request->validate([
            'items'                => 'required|array|min:1',
            'items.*.product_id'   => 'required|exists:products,product_id',
            'items.*.product_name' => 'required|string',
            'items.*.product_code' => 'required|string',
            'items.*.price'        => 'required|numeric|min:0',
            'items.*.qty'          => 'required|integer|min:1',
            'net_total'            => 'required|numeric|min:0',
            'payment_method'       => 'required|in:cash,card',
            'cash_given'           => 'nullable|numeric|min:0',
            'balance_returned'     => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Generate invoice number e.g. INV-20260506-0001
            $count = Invoice::whereDate('created_at', today())->count() + 1;
            $invoiceNumber = 'INV-' . now()->format('Ymd') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

            $invoice = Invoice::create([
                'invoice_number'   => $invoiceNumber,
                'net_total'        => $request->net_total,
                'payment_method'   => $request->payment_method,
                'cash_given'       => $request->cash_given,
                'balance_returned' => $request->balance_returned,
                'status'           => 'paid',
            ]);

            foreach ($request->items as $item) {
                $invoice->items()->create([
                    'product_id'   => $item['product_id'],
                    'product_name' => $item['product_name'],
                    'product_code' => $item['product_code'],
                    'price'        => $item['price'],
                    'qty'          => $item['qty'],
                    'total'        => $item['price'] * $item['qty'],
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Invoice created successfully',
                'data'    => $invoice->load('items')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create invoice',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // PUT /api/invoices/{id}
    public function update(Request $request, $id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);

        $request->validate([
            'items'                => 'required|array|min:1',
            'items.*.product_id'   => 'required|exists:products,product_id',
            'items.*.product_name' => 'required|string',
            'items.*.product_code' => 'required|string',
            'items.*.price'        => 'required|numeric|min:0',
            'items.*.qty'          => 'required|integer|min:1',
            'net_total'            => 'required|numeric|min:0',
            'payment_method'       => 'required|in:cash,card',
            'cash_given'           => 'nullable|numeric|min:0',
            'balance_returned'     => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $invoice->update([
                'net_total'        => $request->net_total,
                'payment_method'   => $request->payment_method,
                'cash_given'       => $request->cash_given,
                'balance_returned' => $request->balance_returned,
            ]);

            // Delete old items and re-insert
            $invoice->items()->delete();

            foreach ($request->items as $item) {
                $invoice->items()->create([
                    'product_id'   => $item['product_id'],
                    'product_name' => $item['product_name'],
                    'product_code' => $item['product_code'],
                    'price'        => $item['price'],
                    'qty'          => $item['qty'],
                    'total'        => $item['price'] * $item['qty'],
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Invoice updated successfully',
                'data'    => $invoice->load('items')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update invoice',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // DELETE /api/invoices/{id}
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $invoice = Invoice::findOrFail($id);
            $invoice->items()->delete();
            $invoice->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Invoice deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete invoice',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // GET /api/invoices/search?number=INV-xxxx
    public function search(Request $request)
    {
        $number = $request->query('number');
        if (!$number) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice number is required'
            ], 400);
        }

        $number = trim($number);
        if (preg_match('/(INV-\d{8}-\d{4})/i', $number, $matches)) {
            $number = strtoupper($matches[1]);
        }

        $invoice = Invoice::with('items')
            ->where('invoice_number', $number)
            ->first();

        if (!$invoice) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice not found'
            ], 404);
        }

        // Calculate returned quantities for each item on this invoice
        $returnedQuantities = DB::table('sales_returns')
            ->join('sales_return_items', 'sales_returns.return_id', '=', 'sales_return_items.return_id')
            ->where('sales_returns.invoice_id', $invoice->invoice_id)
            ->select('sales_return_items.product_id', DB::raw('SUM(sales_return_items.quantity) as total_returned'))
            ->groupBy('sales_return_items.product_id')
            ->pluck('total_returned', 'product_id');

        // Append returned_qty to each item
        foreach ($invoice->items as $item) {
            $productId = $item->product_id;
            $item->returned_qty = isset($returnedQuantities[$productId]) ? (int) $returnedQuantities[$productId] : 0;
        }

        return response()->json([
            'success' => true,
            'data'    => $invoice
        ]);
    }

    // POST /api/invoices/return
    public function processReturn(Request $request)
    {
        $request->validate([
            'invoice_number' => 'required|string|exists:invoices,invoice_number',
            'refund_method'  => 'required|string|in:cash,card,store_credit,no_refund',
            'reason'         => 'nullable|string|max:255',
            'items'          => 'required|array|min:1',
            'items.*.product_id'    => 'required|exists:products,product_id',
            'items.*.qty_to_return' => 'required|integer|min:1',
            'items.*.condition'     => 'required|string|in:sellable,damaged',
        ]);

        DB::beginTransaction();

        try {
            $invoice = Invoice::with('items')->where('invoice_number', $request->invoice_number)->firstOrFail();

            // Fetch already returned quantities for validation
            $returnedQuantities = DB::table('sales_returns')
                ->join('sales_return_items', 'sales_returns.return_id', '=', 'sales_return_items.return_id')
                ->where('sales_returns.invoice_id', $invoice->invoice_id)
                ->select('sales_return_items.product_id', DB::raw('SUM(sales_return_items.quantity) as total_returned'))
                ->groupBy('sales_return_items.product_id')
                ->pluck('total_returned', 'product_id');

            // Generate return number e.g. RTN-20260615-0001
            $count = \App\Models\SalesReturn::whereDate('created_at', today())->count() + 1;
            $returnNumber = 'RTN-' . now()->format('Ymd') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

            // Create Sales Return
            $salesReturn = \App\Models\SalesReturn::create([
                'return_number' => $returnNumber,
                'invoice_id'    => $invoice->invoice_id,
                'refund_amount' => 0, // Computed below
                'refund_method' => $request->refund_method,
                'reason'        => $request->reason,
                'created_by'    => auth()->id() ?? 1,
            ]);

            $refundAmount = 0;

            foreach ($request->items as $returnItem) {
                $productId   = $returnItem['product_id'];
                $qtyToReturn = $returnItem['qty_to_return'];
                $condition   = $returnItem['condition'];

                // Find the corresponding item in the invoice
                $invoiceItem = $invoice->items->firstWhere('product_id', $productId);
                if (!$invoiceItem) {
                    return response()->json([
                        'success' => false,
                        'message' => "Product ID {$productId} was not found in this invoice."
                    ], 400);
                }

                $alreadyReturned = isset($returnedQuantities[$productId]) ? (int) $returnedQuantities[$productId] : 0;
                $maxReturnable   = $invoiceItem->qty - $alreadyReturned;

                if ($qtyToReturn > $maxReturnable) {
                    return response()->json([
                        'success' => false,
                        'message' => "Cannot return {$qtyToReturn} units of {$invoiceItem->product_name}. Only {$maxReturnable} units are available to return."
                    ], 400);
                }

                // Calculate refund for this item
                $itemRefund = ($request->refund_method === 'no_refund') ? 0 : ($invoiceItem->price * $qtyToReturn);
                $refundAmount += $itemRefund;

                // Create Sales Return Item record
                $salesReturn->items()->create([
                    'product_id'   => $productId,
                    'product_name' => $invoiceItem->product_name,
                    'product_code' => $invoiceItem->product_code,
                    'quantity'     => $qtyToReturn,
                    'price'        => $invoiceItem->price,
                    'total'        => $itemRefund,
                    'condition'    => $condition,
                ]);

                // Update stock conditionally
                $stock = \App\Models\Stock::where('product_id', $productId)->first();
                if ($stock) {
                    $qtyBefore = $stock->quantity;

                    if ($condition === 'sellable') {
                        $stock->quantity += $qtyToReturn;
                        $stock->save();
                        $qtyAfter = $stock->quantity;
                        $notes    = "Returned from invoice {$invoice->invoice_number} (Sellable)";
                    } else {
                        $qtyAfter = $qtyBefore;
                        $notes    = "Returned from invoice {$invoice->invoice_number} (Damaged - Written Off)";
                    }

                    // Create stock transaction record
                    \App\Models\StockTransaction::create([
                        'stock_id'         => $stock->stock_id,
                        'product_id'       => $stock->product_id,
                        'branch_id'        => $stock->branch_id,
                        'brand_id'         => $stock->brand_id,
                        'category_id'      => $stock->category_id,
                        'subcategory_id'   => $stock->subcategory_id,
                        'product_name'     => $stock->product_name,
                        'transaction_type' => $condition === 'sellable' ? 'in' : 'out',
                        'quantity_before'  => $qtyBefore,
                        'quantity_changed' => $qtyToReturn,
                        'quantity_after'   => $qtyAfter,
                        'notes'            => $notes,
                        'created_by'       => auth()->id() ?? 1,
                    ]);
                }
            }

            // Update final refund amount
            $salesReturn->update([
                'refund_amount' => $refundAmount
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Invoice return processed successfully',
                'refund_amount' => $refundAmount,
                'data' => $salesReturn->load(['items', 'invoice'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to process return',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}