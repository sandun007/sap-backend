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
}