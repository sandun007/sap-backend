<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubCategory;
use Exception;

class SubCategoryController extends Controller
{
    public function index()
    {
        try {
            $subcategories = SubCategory::with('category')->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Subcategories retrieved successfully',
                'data' => $subcategories
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve subcategories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'subcategory_name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category_id' => 'required|exists:categories,category_id',
                'created_date' => 'nullable|date',
            ]);

            $subcategory = SubCategory::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Subcategory created successfully',
                'data' => $subcategory
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create subcategory',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $subcategory = SubCategory::find($id);
            
            if (!$subcategory) {
                return response()->json([
                    'success' => false,
                    'message' => 'Subcategory not found',
                    'data' => null
                ], 404);
            }

            $data = $request->validate([
                'subcategory_name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category_id' => 'required|exists:categories,category_id',
                'created_date' => 'nullable|date',
            ]);

            $subcategory->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Subcategory updated successfully',
                'data' => $subcategory
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update subcategory',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $subcategory = SubCategory::find($id);
            
            if (!$subcategory) {
                return response()->json([
                    'success' => false,
                    'message' => 'Subcategory not found',
                    'data' => null
                ], 404);
            }

            $subcategory->delete();

            return response()->json([
                'success' => true,
                'message' => 'Subcategory deleted successfully',
                'data' => null
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete subcategory',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}