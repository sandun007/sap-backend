<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Stock;
use App\Models\StockTransaction;
use App\Models\Bank;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class PurchaseController extends Controller
{
    public function index()
    {
        try {
            $purchases = Purchase::with(['supplier', 'branch'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($purchase) {
                    $purchase->supplier_name = $purchase->supplier->supplier_name ?? 'N/A';
                    $purchase->branch_name = $purchase->branch->branch_name ?? 'N/A';
                    return $purchase;
                });

            return response()->json([
                'success' => true,
                'data' => $purchases
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // 1. Create Purchase Record
            $purchase = Purchase::create([
                'purchase_id' => $request->purchase_id,
                'supplier_id' => $request->supplier_id,
                'branch_id' => $request->branch_id,
                'purchase_date' => $request->purchase_date,
                'reference_no' => $request->reference_no,
                'subtotal' => $request->subtotal,
                'tax_amount' => $request->tax_amount,
                'discount' => $request->discount,
                'grand_total' => $request->grand_total,
                'payment_method' => $request->payment_method,
                'amount_paid' => $request->amount_paid,
                'balance_due' => $request->balance_due,
                'payment_notes' => $request->payment_notes,
                'notes' => $request->notes,
                'bank_name' => $request->bank_details['bank_name'] ?? null,
                'bank_reference' => $request->bank_details['reference_no'] ?? null,
                'card_type' => $request->card_details['card_type'] ?? null,
                'card_last_four' => $request->card_details['last_four'] ?? null,
                'loan_interest_rate' => $request->loan_details['interest_rate'] ?? null,
                'loan_term_months' => $request->loan_details['term_months'] ?? null,
            ]);

            // 2. Create Purchase Items and Update Stock
            foreach ($request->items as $itemData) {
                $item = new PurchaseItem([
                    'purchase_record_id' => $purchase->id,
                    'purchase_id' => $purchase->purchase_id,
                    'product_id' => $itemData['product_id'],
                    'product_name' => $itemData['product_name'],
                    'product_code' => $itemData['product_code'],
                    'quantity' => $itemData['quantity'],
                    'stock_price' => $itemData['stock_price'],
                    'selling_price' => $itemData['selling_price'],
                    'discount' => $itemData['discount'] ?? 0,
                    'subtotal' => $itemData['subtotal'],
                ]);
                $item->save();

                // Update Stock
                $this->updateStock($purchase, $itemData);
            }

            // 3. Update Bank Balance if Payment Method is Bank
            if ($request->payment_method === 'Bank' && $request->amount_paid > 0) {
                $bankName = $request->bank_details['bank_name'] ?? null;
                if ($bankName) {
                    $bank = Bank::where('bank_name', $bankName)->first();
                    if ($bank) {
                        $bank->amount -= $request->amount_paid;
                        $bank->save();
                    } else {
                        throw new Exception("Selected bank not found in records.");
                    }
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Purchase recorded successfully and stock updated.',
                'data' => $purchase
            ], 201);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to record purchase: ' . $e->getMessage()
            ], 500);
        }
    }

    private function updateStock($purchase, $itemData)
    {
        $product = Product::with('subcategory')->find($itemData['product_id']);
        if (!$product) return;

        // Update product prices if they changed
        // Use 'price' for selling price as per database schema
        if (isset($itemData['stock_price'])) {
            $product->cost_price = $itemData['stock_price'];
        }
        if (isset($itemData['selling_price'])) {
            $product->price = $itemData['selling_price'];
        }
        $product->save();

        // Update or Create Stock in the specific branch
        $stock = Stock::where('product_id', $itemData['product_id'])
                      ->where('branch_id', $purchase->branch_id)
                      ->first();

        if ($stock) {
            $quantityBefore = $stock->quantity;
            $stock->quantity += $itemData['quantity'];
            $stock->last_restocked_date = now();
            // Ensure category and other IDs are consistent even if they were missing
            $stock->brand_id = $product->brand_id;
            $stock->category_id = $product->subcategory->category_id ?? null;
            $stock->subcategory_id = $product->subcategory_id;
            $stock->save();
        } else {
            $quantityBefore = 0;
            $stock = Stock::create([
                'product_id' => $itemData['product_id'],
                'branch_id' => $purchase->branch_id,
                'brand_id' => $product->brand_id,
                'category_id' => $product->subcategory->category_id ?? null,
                'subcategory_id' => $product->subcategory_id,
                'product_name' => $product->product_name,
                'quantity' => $itemData['quantity'],
                'minimum_stock_level' => 10, // default
                'last_restocked_date' => now(),
            ]);
        }

        $supplier = \App\Models\Supplier::find($purchase->supplier_id);
        // Record Stock Transaction
        StockTransaction::create([
            'stock_id' => $stock->stock_id,
            'product_id' => $stock->product_id,
            'branch_id' => $stock->branch_id,
            'brand_id' => $stock->brand_id,
            'category_id' => $stock->category_id,
            'subcategory_id' => $stock->subcategory_id,
            'product_name' => $stock->product_name,
            'transaction_type' => 'in',
            'quantity_before' => $quantityBefore,
            'quantity_changed' => $itemData['quantity'],
            'quantity_after' => $stock->quantity,
            'reference_number' => $purchase->purchase_id,
            'notes' => "Purchased via GRN: {$purchase->purchase_id} from Supplier [{$supplier->supplier_name}]",
            'created_by' => auth()->id(),
        ]);
    }

    public function show($id)
    {
        try {
            $purchase = Purchase::with(['supplier', 'branch', 'items'])->where('purchase_id', $id)->firstOrFail();
            
            // Map flat names for frontend convenience
            $purchase->supplier_name = $purchase->supplier->supplier_name ?? 'N/A';
            $purchase->branch_name = $purchase->branch->branch_name ?? 'N/A';

            return response()->json([
                'success' => true,
                'data' => $purchase
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Purchase not found'
            ], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $purchase = Purchase::where('purchase_id', $id)->firstOrFail();
            $purchase->delete();
            return response()->json([
                'success' => true,
                'message' => 'Purchase record deleted'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete record'
            ], 500);
        }
    }

    public function getProductHistory($product_id)
    {
        try {
            $history = PurchaseItem::with(['purchase.supplier'])
                ->where('product_id', $product_id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get()
                ->map(function ($item) {
                    return [
                        'purchase_id' => $item->purchase_id,
                        'purchase_date' => $item->purchase->purchase_date ?? 'N/A',
                        'supplier_name' => $item->purchase->supplier->supplier_name ?? 'N/A',
                        'quantity' => $item->quantity,
                        'stock_price' => $item->stock_price,
                        'selling_price' => $item->selling_price,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $history
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
