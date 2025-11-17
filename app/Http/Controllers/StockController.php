<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\Product;
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
                    
                    $existingStock->quantity += $data['quantity'];
                    $existingStock->last_restocked_date = now();
                    $existingStock->save();
                    
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

                $stock->quantity += $data['quantity'];
                $stock->last_restocked_date = now();
                $stock->save();

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Stock quantity increased successfully',
                    'data' => $stock,
                    'previous_quantity' => $stock->quantity - $data['quantity'],
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
}