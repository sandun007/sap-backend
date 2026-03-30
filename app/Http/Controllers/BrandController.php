<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\Brand;
use Exception;

class BrandController extends Controller
{
    public function index()
    {
        try {
            if (auth()->user()->role === 'admin' || auth()->user()->role === 'brand') {
                $brands = Brand::all();

                return response()->json([
                    'success' => true,
                    'message' => 'Brands retrieved successfully',
                    'data' => $brands
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
                'message' => 'Failed to retrieve brands',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function store(Request $request)
    {
        try {
            $user = auth()->user();
            if ($user->role === 'admin') {
                $data = $request->validate([
                    'brand_code' => 'nullable|string|unique:brands,brand_code',
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
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Only admins can create brands.',
                    'data' => null,
                ], 403);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create brand',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = auth()->user();
            if ($user->role === 'admin') {
                $brand = Brand::find($id);

                if (!$brand) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Brand not found',
                        'data' => null
                    ], 404);
                }

                $data = $request->validate([
                    'brand_code' => 'nullable|string|unique:brands,brand_code,' . $id . ',brand_id',
                    'brand_name' => 'nullable|string|max:255',
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
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Only admins can update brands.',
                    'data' => null,
                ], 403);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update brand',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = auth()->user();
            if ($user->role === 'admin') {
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
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Only admins can delete brands.',
                    'data' => null,
                ], 403);
            }

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete brand',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function import(Request $request)
    {
        $brands = $request->input('brands', []);

        $imported = 0;
        $skipped = 0;

        foreach ($brands as $data) {

            $exists = Brand::where('brand_code', $data['brand_code'])
                ->orWhere('brand_name', $data['brand_name'])
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            Brand::create($data);
            $imported++;
        }

        return response()->json([
            'imported' => $imported,
            'skipped' => $skipped,
            'message' => "Imported $imported brand, skipped $skipped duplicates"
        ]);
    }
}

