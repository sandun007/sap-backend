<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnItem;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockTransaction;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Exception;

class PurchaseReturnController extends Controller
{
    public function index()
    {
        try {
            $returns = PurchaseReturn::with(['supplier', 'branch', 'creator'])->orderBy('created_at', 'desc')->get();
            return response()->json([
                'success' => true,
                'data' => $returns
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch purchase returns',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $return = PurchaseReturn::with(['supplier', 'branch', 'creator', 'items'])->findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $return
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Purchase return not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function getSupplierProducts(Request $request, $supplier_id)
    {
        try {
            $branchId = $request->query('branch_id');
            if (!$branchId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Branch ID is required.'
                ], 400);
            }

            // Fetch products provided by supplier directly or via historical purchases
            $purchasedProductIds = DB::table('purchase_items')
                ->join('purchases', 'purchase_items.purchase_record_id', '=', 'purchases.id')
                ->where('purchases.supplier_id', $supplier_id)
                ->pluck('purchase_items.product_id')
                ->unique();

            $products = Product::where('supplier_id', $supplier_id)
                ->orWhereIn('product_id', $purchasedProductIds)
                ->get();

            $formattedProducts = $products->map(function ($p) use ($branchId) {
                // Fetch standard stock quantity
                $stock = Stock::where('product_id', $p->product_id)
                    ->where('branch_id', $branchId)
                    ->first();
                $stockQty = $stock ? $stock->quantity : 0;

                // Calculate net damaged quantity
                $salesDamaged = DB::table('sales_return_items')
                    ->where('product_id', $p->product_id)
                    ->where('condition', 'damaged')
                    ->sum('quantity');

                $supplierDamaged = DB::table('purchase_return_items')
                    ->join('purchase_returns', 'purchase_returns.id', '=', 'purchase_return_items.purchase_return_record_id')
                    ->where('purchase_return_items.product_id', $p->product_id)
                    ->where('purchase_return_items.return_type', 'damaged')
                    ->where('purchase_returns.branch_id', $branchId)
                    ->sum('purchase_return_items.quantity');

                $damagedQty = max(0, $salesDamaged - $supplierDamaged);

                return [
                    'product_id' => $p->product_id,
                    'product_name' => $p->product_name,
                    'product_code' => $p->product_code,
                    'cost_price' => (float)$p->cost_price,
                    'selling_price' => (float)$p->price,
                    'unit' => $p->unit,
                    'barcode' => $p->barcode,
                    'stock_qty' => $stockQty,
                    'damaged_qty' => $damagedQty
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedProducts
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch supplier products',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|string|exists:suppliers,supplier_id',
            'branch_id' => 'required|integer|exists:branches,branch_id',
            'return_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,product_id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.return_type' => 'required|string|in:damaged,expired',
        ]);

        DB::beginTransaction();

        try {
            // Generate purchase return ID (e.g. PR-20260619-0001)
            $datePrefix = now()->format('Ymd');
            $todayCount = PurchaseReturn::whereDate('created_at', today())->count() + 1;
            $purchaseReturnId = 'PR-' . $datePrefix . '-' . str_pad($todayCount, 4, '0', STR_PAD_LEFT);

            $supplier = Supplier::where('supplier_id', $request->supplier_id)->firstOrFail();

            $purchaseReturn = PurchaseReturn::create([
                'purchase_return_id' => $purchaseReturnId,
                'supplier_id' => $request->supplier_id,
                'branch_id' => $request->branch_id,
                'return_date' => $request->return_date,
                'total_amount' => 0, // calculated below
                'notes' => $request->notes,
                'created_by' => auth()->id() ?? 1,
            ]);

            $totalAmount = 0;

            foreach ($request->items as $itemData) {
                $product = Product::findOrFail($itemData['product_id']);
                $qty = $itemData['quantity'];
                $price = $itemData['price'];
                $type = $itemData['return_type'];
                $subtotal = $price * $qty;
                $totalAmount += $subtotal;

                // Create item record
                $purchaseReturn->items()->create([
                    'product_id' => $product->product_id,
                    'product_name' => $product->product_name,
                    'product_code' => $product->product_code,
                    'quantity' => $qty,
                    'price' => $price,
                    'total' => $subtotal,
                    'return_type' => $type,
                ]);

                // Find or create Stock
                $stock = Stock::where('product_id', $product->product_id)
                    ->where('branch_id', $request->branch_id)
                    ->first();

                if (!$stock) {
                    // Create basic stock record if not found (needed for transaction log)
                    $stock = Stock::create([
                        'product_id' => $product->product_id,
                        'branch_id' => $request->branch_id,
                        'brand_id' => $product->brand_id,
                        'category_id' => $product->subcategory->category_id ?? null,
                        'subcategory_id' => $product->subcategory_id,
                        'product_name' => $product->product_name,
                        'quantity' => 0,
                        'minimum_stock_level' => 10,
                        'last_restocked_date' => now(),
                    ]);
                }

                $qtyBefore = $stock->quantity;

                if ($type === 'expired') {
                    // Expired items are deducted from standard stock
                    if ($stock->quantity < $qty) {
                        throw new Exception("Insufficient standard stock at this branch for product [{$product->product_name}]. Available: {$stock->quantity}, trying to return: {$qty}.");
                    }
                    $stock->quantity -= $qty;
                    $stock->save();
                    $qtyAfter = $stock->quantity;
                    $notes = "Purchase Return (Expired) to Supplier [{$supplier->supplier_name}]";
                    $change = -$qty;
                } else {
                    // Damaged items do not sit in standard stock (they are in the damaged queue)
                    // We validate that we have enough damaged stock in store
                    $salesDamaged = DB::table('sales_return_items')
                        ->where('product_id', $product->product_id)
                        ->where('condition', 'damaged')
                        ->sum('quantity');

                    $supplierDamaged = DB::table('purchase_return_items')
                        ->join('purchase_returns', 'purchase_returns.id', '=', 'purchase_return_items.purchase_return_record_id')
                        ->where('purchase_return_items.product_id', $product->product_id)
                        ->where('purchase_return_items.return_type', 'damaged')
                        ->where('purchase_returns.branch_id', $request->branch_id)
                        ->where('purchase_returns.id', '!=', $purchaseReturn->id)
                        ->sum('purchase_return_items.quantity');

                    $netDamaged = max(0, $salesDamaged - $supplierDamaged);

                    if ($netDamaged < $qty) {
                        throw new Exception("Insufficient damaged stock currently recorded for product [{$product->product_name}]. Available: {$netDamaged}, trying to return: {$qty}.");
                    }

                    $qtyAfter = $qtyBefore; // standard stock remains unchanged
                    $notes = "Purchase Return (Damaged) to Supplier [{$supplier->supplier_name}]";
                    $change = -$qty;
                }

                // Log stock transaction
                StockTransaction::create([
                    'stock_id' => $stock->stock_id,
                    'product_id' => $stock->product_id,
                    'branch_id' => $stock->branch_id,
                    'brand_id' => $stock->brand_id,
                    'category_id' => $stock->category_id,
                    'subcategory_id' => $stock->subcategory_id,
                    'product_name' => $stock->product_name,
                    'transaction_type' => 'out',
                    'quantity_before' => $qtyBefore,
                    'quantity_changed' => $change,
                    'quantity_after' => $qtyAfter,
                    'reference_number' => $purchaseReturnId,
                    'notes' => $notes,
                    'created_by' => auth()->id() ?? 1,
                ]);
            }

            // Update total amount on purchase return
            $purchaseReturn->update([
                'total_amount' => $totalAmount
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Purchase return submitted successfully.',
                'data' => $purchaseReturn->load(['items', 'supplier', 'branch'])
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to process purchase return: ' . $e->getMessage()
            ], 500);
        }
    }
}
