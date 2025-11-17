<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use Exception;

class BrandController extends Controller
{
    public function index()
    {
        try {
            $brands = Brand::all();
            
            return response()->json([
                'success' => true,
                'message' => 'Brands retrieved successfully',
                'data' => $brands
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve brands',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'brand_name' => 'required|string|max:255',
                'contact_person' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
                'email' => 'nullable|email|unique:brands,email',
                'website' => 'nullable|string|max:255',
                'created_date' => 'nullable|date',
            ]);

            $brand = Brand::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Brand created successfully',
                'data' => $brand
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create brand',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $brand = Brand::find($id);
            
            if (!$brand) {
                return response()->json([
                    'success' => false,
                    'message' => 'Brand not found',
                    'data' => null
                ], 404);
            }

            $data = $request->validate([
                'brand_name' => 'required|string|max:255',
                'contact_person' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
                'email' => 'nullable|email|unique:brands,email,' . $id . ',brand_id',
                'website' => 'nullable|string|max:255',
                'created_date' => 'nullable|date',
            ]);

            $brand->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Brand updated successfully',
                'data' => $brand
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update brand',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $brand = Brand::find($id);
            
            if (!$brand) {
                return response()->json([
                    'success' => false,
                    'message' => 'Brand not found',
                    'data' => null
                ], 404);
            }

            $brand->delete();

            return response()->json([
                'success' => true,
                'message' => 'Brand deleted successfully',
                'data' => null
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete brand',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}