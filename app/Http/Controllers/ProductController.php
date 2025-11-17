<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    // List all products
    public function index()
    {
        $products = Product::with(['brand', 'subcategory'])->get();
        return response()->json($products);
    }

        // Add new product
        public function store(Request $request)
        {
            $userid = auth()->user()->id;
            $request->validate([
                'product_name' => 'required|string|max:255',
                'brand_id' => 'required|exists:brands,brand_id',
                'subcategory_id' => 'required|exists:sub_categories,subcategory_id',
                'price' => 'required|numeric',
                'unit' => 'required|string|max:50',
                'status' => 'nullable|string',
                'description' => 'nullable|string',
                'user_id' => 'required|string',
            ]);

            $request->merge(['user_id' => $userid]);

        $product = Product::create($request->all());
        return response()->json($product, 201);
    }

    // Show single product
    public function show($id)
    {
        $product = Product::with(['brand', 'subcategory'])->find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json($product);
    }

    // Update product
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $request->validate([
            'product_name' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,brand_id',
            'subcategory_id' => 'required|exists:sub_categories,subcategory_id',
            'price' => 'required|numeric',
            'unit' => 'required|string|max:50',
            'status' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $product->update($request->all());
        return response()->json($product);
    }

    // Delete product
    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
