<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Exception;

class ProductController extends Controller
{
    // List all products
    public function index()
    {
        try {
            $products = Product::with(['brand', 'subcategory.category','user', 'stocks'])->get();
            
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
        ]);

        $data['user_id'] = $userid;

        $product = Product::create($data);

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
            ]);

            $product->update($data);
            
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