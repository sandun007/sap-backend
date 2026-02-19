<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Exception;

class CategoryController extends Controller
{
    // List all categories
    public function index()
    {
        try {
            $categories = Category::all();

            return response()->json([
                'success' => true,
                'message' => 'Categories retrieved successfully',
                'data' => $categories
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Add new category
    public function store(Request $request)
    {
        try {

            if(auth()->user()->role === 'admin') {
           $data = $request->validate([
               'category_code' => 'nullable|string',
                'category_name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'created_date' => 'nullable|date',
                'is_active' => 'boolean',
            ]);

            $category = Category::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Category created successfully',
                'data' => $category
            ], 201);
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
                'message' => 'Failed to create category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Update category
    public function update(Request $request, $id)
    {
        try {


            if(auth()->user()->role === 'admin') {
             $category = Category::find($id);

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found',
                    'data' => null
                ], 404);
            }

            $data = $request->validate([
                'category_code' => 'nullable|string',
                'category_name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'created_date' => 'nullable|date',
                'is_active' => 'boolean',
            ]);

            $category->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully',
                'data' => $category
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
                'message' => 'Failed to update category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Delete category
    public function destroy($id)
    {
        try {

            if(auth()->user()->role === 'admin') {
            $category = Category::find($id);

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found',
                    'data' => null
                ], 404);
            }

            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully',
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
                'message' => 'Failed to delete category',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function import(Request $request)
    {
        $categories = $request->input('categories', []);

        $imported = 0;
        $skipped = 0;

        foreach ($categories as $data) {

            $exists = Category::where('category_code', $data['category_code'])
                ->orWhere('category_name', $data['category_name'])
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            Category::create($data);
            $imported++;
        }

        return response()->json([
            'imported' => $imported,
            'skipped' => $skipped,
            'message' => "Imported $imported categories, skipped $skipped duplicates"
        ]);
    }

}
