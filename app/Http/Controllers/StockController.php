<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\Product;
use App\Models\StockTransaction;
use Exception;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index()
    {
        try {
            $stocks = Stock::with(['product', 'branch', 'brand', 'category', 'subcategory'])->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Stocks retrieved successfully',
                'data' => $stocks
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve stocks',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            if(auth()->user()->role === 'admin' || auth()->user()->role === 'employee') {

                
                $data = $request->validate([
                    'product_id' => 'required|exists:products,product_id',
                    'branch_id' => 'required|exists:branches,branch_id',
                    'brand_id' => 'required|exists:brands,brand_id',
                    'category_id' => 'required|exists:categories,category_id',
                    'subcategory_id' => 'required|exists:sub_categories,subcategory_id',
                    'product_name' => 'required|string|max:255',
                    'quantity' => 'required|integer|min:0',
                    'minimum_stock_level' => 'nullable|integer|min:0',
                    'maximum_stock_level' => 'nullable|integer|min:0',
                    'last_restocked_date' => 'nullable|date',
                    'location_in_warehouse' => 'nullable|string|max:255',
                ]);

                // Check if stock already exists with same brand, subcategory, category, and product name
                $existingStock = Stock::where('brand_id', $data['brand_id'])
                                     ->where('subcategory_id', $data['subcategory_id'])
                                     ->where('category_id', $data['category_id'])
                                     ->where('product_name', $data['product_name'])
                                     ->where('branch_id', $data['branch_id'])
                                     ->first();

                if ($existingStock) {
                    // Auto increment quantity if same item exists
                    DB::beginTransaction();
                    
                    $quantityBefore = $existingStock->quantity;
                    $existingStock->quantity += $data['quantity'];
                    $existingStock->last_restocked_date = now();
                    $existingStock->save();

                    // Record transaction
                    StockTransaction::create([
                        'stock_id' => $existingStock->stock_id,
                        'product_id' => $existingStock->product_id,
                        'branch_id' => $existingStock->branch_id,
                        'brand_id' => $existingStock->brand_id,
                        'category_id' => $existingStock->category_id,
                        'subcategory_id' => $existingStock->subcategory_id,
                        'product_name' => $existingStock->product_name,
                        'transaction_type' => 'in',
                        'quantity_before' => $quantityBefore,
                        'quantity_changed' => $data['quantity'],
                        'quantity_after' => $existingStock->quantity,
                        'notes' => 'Auto increment on duplicate stock creation',
                        'created_by' => auth()->id(),
                    ]);
                    
                    DB::commit();

                    return response()->json([
                        'success' => true,
                        'message' => 'Stock quantity increased for existing item',
                        'data' => $existingStock,
                        'action' => 'incremented'
                    ], 200);
                }

                // Create new stock if doesn't exist
                $data['last_restocked_date'] = now();
                $stock = Stock::create($data);

                // Record initial stock transaction
                StockTransaction::create([
                    'stock_id' => $stock->stock_id,
                    'product_id' => $stock->product_id,
                    'branch_id' => $stock->branch_id,
                    'brand_id' => $stock->brand_id,
                    'category_id' => $stock->category_id,
                    'subcategory_id' => $stock->subcategory_id,
                    'product_name' => $stock->product_name,
                    'transaction_type' => 'initial',
                    'quantity_before' => 0,
                    'quantity_changed' => $data['quantity'],
                    'quantity_after' => $data['quantity'],
                    'notes' => 'Initial stock creation',
                    'created_by' => auth()->id(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Stock created successfully',
                    'data' => $stock,
                    'action' => 'created'
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'data' => null,
                ], 403);
            }
            
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create stock',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add items to stock (increment quantity)
     */
    public function addStock(Request $request, $id)
    {
        try {
            if(auth()->user()->role === 'admin' || auth()->user()->role === 'employee') {
                
                $data = $request->validate([
                    'quantity' => 'required|integer|min:1',
                ]);

                $stock = Stock::find($id);
                
                if (!$stock) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stock not found',
                        'data' => null
                    ], 404);
                }

                DB::beginTransaction();

                $quantityBefore = $stock->quantity;
                $stock->quantity += $data['quantity'];
                $stock->last_restocked_date = now();
                $stock->save();

                // Record transaction
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
                    'quantity_changed' => $data['quantity'],
                    'quantity_after' => $stock->quantity,
                    'notes' => 'Stock added via addStock method',
                    'created_by' => auth()->id(),
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Stock quantity increased successfully',
                    'data' => $stock,
                    'previous_quantity' => $quantityBefore,
                    'added_quantity' => $data['quantity'],
                    'new_quantity' => $stock->quantity
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'data' => null,
                ], 403);
            }
            
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to add stock',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove items from stock using product_id (decrement quantity)
     */
    public function removeStock(Request $request, $id)
    {
        try {
            

            if(auth()->user()->role === 'admin' || auth()->user()->role === 'employee') {
                
                $data = $request->validate([
                    'quantity' => 'required|integer|min:1',
                ]);

                // Find stock by product_id
                $stock = Stock::where('stock_id', $id)->first();

                
                if (!$stock) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stock not found for this product',
                        'data' => null
                    ], 404);
                }

                if ($stock->quantity < $data['quantity']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Insufficient stock quantity',
                        'data' => [
                            'available_quantity' => $stock->quantity,
                            'requested_quantity' => $data['quantity'],
                            'shortage' => $data['quantity'] - $stock->quantity
                        ]
                    ], 400);
                }

                DB::beginTransaction();

                $previousQuantity = $stock->quantity;
                $stock->quantity -= $data['quantity'];
                $stock->save();

                // Record transaction
                StockTransaction::create([
                    'stock_id' => $stock->stock_id,
                    'product_id' => $stock->product_id,
                    'branch_id' => $stock->branch_id,
                    'brand_id' => $stock->brand_id,
                    'category_id' => $stock->category_id,
                    'subcategory_id' => $stock->subcategory_id,
                    'product_name' => $stock->product_name,
                    'transaction_type' => 'out',
                    'quantity_before' => $previousQuantity,
                    'quantity_changed' => -$data['quantity'],
                    'quantity_after' => $stock->quantity,
                    'notes' => 'Stock removed via removeStock method',
                    'created_by' => auth()->id(),
                ]);

                DB::commit();

                // Check if stock is now low
                $isLowStock = $stock->isLowStock();

                return response()->json([
                    'success' => true,
                    'message' => 'Stock quantity decreased successfully',
                    'data' => $stock,
                    'previous_quantity' => $previousQuantity,
                    'removed_quantity' => $data['quantity'],
                    'new_quantity' => $stock->quantity,
                    'low_stock_alert' => $isLowStock
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'data' => null,
                ], 403);
            }
            
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove stock',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            if(auth()->user()->role === 'admin' || auth()->user()->role === 'employee') {
                $stock = Stock::find($id);
                
                if (!$stock) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stock not found',
                        'data' => null
                    ], 404);
                }

                $data = $request->validate([
                    'product_id' => 'nullable|exists:products,product_id',
                    'branch_id' => 'nullable|exists:branches,branch_id',
                    'brand_id' => 'nullable|exists:brands,brand_id',
                    'category_id' => 'nullable|exists:categories,category_id',
                    'subcategory_id' => 'nullable|exists:subcategories,subcategory_id',
                    'product_name' => 'nullable|string|max:255',
                    'quantity' => 'nullable|integer|min:0',
                    'minimum_stock_level' => 'nullable|integer|min:0',
                    'maximum_stock_level' => 'nullable|integer|min:0',
                    'last_restocked_date' => 'nullable|date',
                    'location_in_warehouse' => 'nullable|string|max:255',
                ]);

                $stock->update($data);

                return response()->json([
                    'success' => true,
                    'message' => 'Stock updated successfully',
                    'data' => $stock
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'data' => null,
                ], 403);
            }
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update stock',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            if(auth()->user()->role === 'admin' || auth()->user()->role === 'employee') {
                $stock = Stock::find($id);
                
                if (!$stock) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stock not found',
                        'data' => null
                    ], 404);
                }

                $stock->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Stock deleted successfully',
                    'data' => null
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'data' => null,
                ], 403);
            }
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete stock',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get low stock items
     */
    public function getLowStock()
    {
        try {
            $stocks = Stock::with(['product', 'branch', 'brand', 'category', 'subcategory'])
                          ->whereColumn('quantity', '<=', 'minimum_stock_level')
                          ->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Low stock items retrieved successfully',
                'count' => $stocks->count(),
                'data' => $stocks
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve low stock items',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get stock by branch
     */
    public function getStockByBranch($branch_id)
    {
        try {
            $stocks = Stock::with(['product', 'branch', 'brand', 'category', 'subcategory'])
                          ->where('branch_id', $branch_id)
                          ->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Branch stocks retrieved successfully',
                'data' => $stocks
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve branch stocks',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get stock by product
     */
    public function getStockByProduct($product_id)
    {
        try {
            $stocks = Stock::with(['product', 'branch', 'brand', 'category', 'subcategory'])
                          ->where('product_id', $product_id)
                          ->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Product stocks retrieved successfully',
                'data' => $stocks
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve product stocks',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ========== STOCK TRANSACTION HISTORY METHODS ==========

    /**
     * Get stock transaction history
     */
    public function getTransactionHistory($stock_id = null)
    {
        try {
            $query = StockTransaction::with(['stock', 'product', 'branch', 'brand', 'category', 'subcategory', 'creator'])
                                     ->orderBy('created_at', 'desc');
            
            if ($stock_id) {
                $query->where('stock_id', $stock_id);
            }
            
            $transactions = $query->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Transaction history retrieved successfully',
                'count' => $transactions->count(),
                'data' => $transactions
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve transaction history',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get transaction history by product
     */
    public function getTransactionsByProduct($product_id)
    {
        try {
            $transactions = StockTransaction::with(['stock', 'product', 'branch', 'brand', 'category', 'subcategory', 'creator'])
                                           ->where('product_id', $product_id)
                                           ->orderBy('created_at', 'desc')
                                           ->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Product transaction history retrieved successfully',
                'count' => $transactions->count(),
                'data' => $transactions
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve product transaction history',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get transaction history by branch
     */
    public function getTransactionsByBranch($branch_id)
    {
        try {
            $transactions = StockTransaction::with(['stock', 'product', 'branch', 'brand', 'category', 'subcategory', 'creator'])
                                           ->where('branch_id', $branch_id)
                                           ->orderBy('created_at', 'desc')
                                           ->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Branch transaction history retrieved successfully',
                'count' => $transactions->count(),
                'data' => $transactions
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve branch transaction history',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get transaction history by date range
     */
    public function getTransactionsByDateRange(Request $request)
    {
        try {
            $data = $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'transaction_type' => 'nullable|in:in,out,adjustment,initial',
            ]);

            $query = StockTransaction::with(['stock', 'product', 'branch', 'brand', 'category', 'subcategory', 'creator'])
                                     ->whereBetween('created_at', [$data['start_date'], $data['end_date']]);
            
            if (isset($data['transaction_type'])) {
                $query->where('transaction_type', $data['transaction_type']);
            }

            $transactions = $query->orderBy('created_at', 'desc')->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Transaction history retrieved successfully',
                'count' => $transactions->count(),
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'data' => $transactions
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve transaction history',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}