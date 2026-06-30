<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    // List all products
    public function index(Request $request)
    {
        try {
            $query = Product::with(['brand', 'subcategory.category','user', 'stocks'])
                ->withSum(['returnItems as sales_damaged_qty' => function($q) {
                    $q->where('condition', 'damaged');
                }], 'quantity')
                ->withSum(['purchaseReturnItems as supplier_damaged_qty' => function($q) {
                    $q->where('return_type', 'damaged');
                }], 'quantity');

            if ($request->filled('has_transactions')) {
                // Match any product that has EITHER a transaction record OR a stock record
                // for the given branch. This ensures all 5 products visible on the Live Stock
                // page also appear in the Bin Card product dropdown.
                $branchId = $request->input('branch_id');

                $query->where(function ($orQuery) use ($request, $branchId) {
                    // Condition 1: Has at least one stock_transaction for this branch
                    $orQuery->whereExists(function ($subQuery) use ($branchId) {
                        $subQuery->select(DB::raw(1))
                            ->from('stock_transactions')
                            ->whereColumn('stock_transactions.product_id', 'products.product_id');

                        if ($branchId) {
                            $subQuery->where('stock_transactions.branch_id', $branchId);
                        }
                    });

                    // Condition 2: Has a stock record (row in stocks table) for this branch
                    if ($branchId) {
                        $orQuery->orWhereExists(function ($subQuery) use ($branchId) {
                            $subQuery->select(DB::raw(1))
                                ->from('stocks')
                                ->whereColumn('stocks.product_id', 'products.product_id')
                                ->where('stocks.branch_id', $branchId);
                        });
                    }
                });
            }

            $products = $query->get()->map(function ($p) {
                $salesDamaged = (int)$p->sales_damaged_qty;
                $supplierDamaged = (int)$p->supplier_damaged_qty;
                $p->damaged_quantity = max(0, $salesDamaged - $supplierDamaged);
                return $p;
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Products retrieved successfully',
                'data' => $products
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve products',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Add new product
    public function store(Request $request)
{
    try {
        $userid = auth()->user()->id;
        
        $data = $request->validate([
            'product_name' => 'required|string|max:255',
            'product_code' => 'required|string|max:255|unique:products,product_code',
            'brand_id' => 'required|exists:brands,brand_id',
            'subcategory_id' => 'required|exists:sub_categories,subcategory_id',
            'price' => 'required|numeric',
            'unit' => 'required|string|max:50',
            'status' => 'nullable|string',
            'description' => 'nullable|string',
            'barcode' => 'nullable|string|max:255',
            'cost_price' => 'nullable|numeric',
            'weight' => 'nullable|string|max:255',
            'dimensions' => 'nullable|string|max:255',
            'supplier_id' => 'nullable|exists:suppliers,supplier_id',
            'minimum_stock_level' => 'nullable|integer|min:0',
        ]);

        $data['user_id'] = $userid;

        $product = Product::create($data);

        // Log initial price history
        \App\Models\ProductPriceHistory::create([
            'product_id' => $product->product_id,
            'old_cost_price' => 0.00,
            'new_cost_price' => (float)($product->cost_price ?? 0),
            'old_selling_price' => 0.00,
            'new_selling_price' => (float)$product->price,
            'user_id' => $userid
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'data' => $product
        ], 201);

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to create product',
            'error' => $e->getMessage()
        ], 500);
    }
}


    // Show single product
    public function show($id)
    {
        try {
            $product = Product::with(['brand', 'subcategory'])->find($id);
            
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found',
                    'data' => null
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Product retrieved successfully',
                'data' => $product
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Update product
    public function update(Request $request, $id)
    {
        try {
            $product = Product::find($id);
            
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found',
                    'data' => null
                ], 404);
            }

            $data = $request->validate([
                'product_name' => 'nullable|string|max:255',
                'product_code' => 'nullable|string|max:255|unique:products,product_code,' . $product->product_id . ',product_id',
                'brand_id' => 'nullable|exists:brands,brand_id',
                'subcategory_id' => 'nullable|exists:sub_categories,subcategory_id',
                'price' => 'nullable|numeric',
                'unit' => 'nullable|string|max:50',
                'status' => 'nullable|string',
                'description' => 'nullable|string',
                'barcode' => 'nullable|string|max:255',
                'cost_price' => 'nullable|numeric',
                'weight' => 'nullable|string|max:255',
                'dimensions' => 'nullable|string|max:255',
                'supplier_id' => 'nullable|exists:suppliers,supplier_id',
                'minimum_stock_level' => 'nullable|integer|min:0',
            ]);

            $oldCost = (float)$product->cost_price;
            $oldSelling = (float)$product->price;

            $product->update($data);

            $newCost = (float)$product->cost_price;
            $newSelling = (float)$product->price;

            // Log if prices changed
            if ($oldCost !== $newCost || $oldSelling !== $newSelling) {
                \App\Models\ProductPriceHistory::create([
                    'product_id' => $product->product_id,
                    'old_cost_price' => $oldCost,
                    'new_cost_price' => $newCost,
                    'old_selling_price' => $oldSelling,
                    'new_selling_price' => $newSelling,
                    'user_id' => auth()->user()->id
                ]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
                'data' => $product
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Delete product
    public function destroy($id)
    {
        try {
            $product = Product::find($id);
            
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found',
                    'data' => null
                ], 404);
            }

            $product->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully',
                'data' => null
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function getDropdownData()
    {
        try {
            return response()->json([
                'success' => true,
                'categories' => \App\Models\Category::all(),
                'subcategories' => \App\Models\SubCategory::all(),
                'brands' => \App\Models\Brand::all(),
                'suppliers' => \App\Models\Supplier::all(),
                'branches' => \App\Models\Branch::all(),
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve dropdown data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}