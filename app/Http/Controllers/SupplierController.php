<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Supplier;
use Exception;

class SupplierController extends Controller
{
    public function index()
    {
        try {
           if(auth()->user()->role === 'admin' || auth()->user()->role === 'employee') {
            $suppliers = Supplier::all();

            return response()->json([
                'success' => true,
                'message' => 'Suppliers retrieved successfully',
                'data' => $suppliers
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
                'message' => 'Failed to retrieve suppliers',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            if(auth()->user()->role === 'admin' || auth()->user()->role === 'employee') {
            $data = $request->validate([
                'supplier_id'=> 'required|string|max:32|unique:suppliers,supplier_id',
                'supplier_name' => 'required|string|max:255',
                'contact_person' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
                'email' => 'nullable|email|unique:suppliers,email',
                'address' => 'nullable|string',
                'tax_id' => 'nullable|string|max:50',
                'payment_terms' => 'nullable|string|max:255',
                'created_date' => 'nullable|date',
            ]);

            $supplier = Supplier::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Supplier created successfully',
                'data' => $supplier
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
                'message' => 'Failed to create supplier',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            if(auth()->user()->role === 'admin') {
            $supplier = Supplier::find($id);

            if (!$supplier) {
                return response()->json([
                    'success' => false,
                    'message' => 'Supplier not found',
                    'data' => null
                ], 404);
            }

            $data = $request->validate([
                'supplier_name' => 'nullable|string|max:255',
                'contact_person' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
                'email' => 'nullable|email|unique:suppliers,email,' . $id . ',supplier_id',
                'address' => 'nullable|string',
                'tax_id' => 'nullable|string|max:50',
                'payment_terms' => 'nullable|string|max:255',
                'created_date' => 'nullable|date',
            ]);

            $supplier->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Supplier updated successfully',
                'data' => $supplier
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
                'message' => 'Failed to update supplier',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            if(auth()->user()->role === 'admin') {
            $supplier = Supplier::find($id);

            if (!$supplier) {
                return response()->json([
                    'success' => false,
                    'message' => 'Supplier not found',
                    'data' => null
                ], 404);
            }

            $supplier->delete();

            return response()->json([
                'success' => true,
                'message' => 'Supplier deleted successfully',
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
                'message' => 'Failed to delete supplier',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function import(Request $request)
    {
        $suppliers = $request->input('suppliers', []);

        $imported = 0;
        $skipped = 0;

        foreach ($suppliers as $data) {

            $exists = Supplier::where('supplier_name', $data['supplier_name'])
                ->orWhere('email', $data['email'])
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
            'message' => "Imported $imported Suppliers, skipped $skipped duplicates"
        ]);
    }

}
