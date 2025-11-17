<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use Exception;

class StockController extends Controller
{
    public function index()
    {
        try {
            $stocks = Stock::with(['product', 'branch'])->get();
            
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
            $data = $request->validate([
                'product_id' => 'required|exists:products,product_id',
                'branch_id' => 'required|exists:branches,branch_id',
                'quantity' => 'required|integer|min:0',
                'minimum_stock_level' => 'nullable|integer|min:0',
                'maximum_stock_level' => 'nullable|integer|min:0',
                'last_restocked_date' => 'nullable|date',
                'location_in_warehouse' => 'nullable|string|max:255',
            ]);

            $stock = Stock::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Stock created successfully',
                'data' => $stock
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create stock',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $stock = Stock::find($id);
            
            if (!$stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stock not found',
                    'data' => null
                ], 404);
            }

            $data = $request->validate([
                'product_id' => 'required|exists:products,product_id',
                'branch_id' => 'required|exists:branches,branch_id',
                'quantity' => 'required|integer|min:0',
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
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete stock',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}