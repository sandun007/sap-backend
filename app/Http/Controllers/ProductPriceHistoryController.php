<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductPriceHistory;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductPriceHistoryController extends Controller
{
    // Update product price & log it
    public function updatePrice(Request $request)
    {
        try {
            $data = $request->validate([
                'product_id' => 'required|exists:products,product_id',
                'cost_price' => 'required|numeric|min:0',
                'selling_price' => 'required|numeric|min:0',
            ]);

            $userId = auth()->user()->id;

            $updated = DB::transaction(function () use ($data, $userId) {
                $product = Product::lockForUpdate()->find($data['product_id']);

                $oldCost = (float)$product->cost_price;
                $newCost = (float)$data['cost_price'];
                $oldSelling = (float)$product->price;
                $newSelling = (float)$data['selling_price'];

                // Check if there is any price difference
                if ($oldCost !== $newCost || $oldSelling !== $newSelling) {
                    ProductPriceHistory::create([
                        'product_id' => $product->product_id,
                        'old_cost_price' => $oldCost,
                        'new_cost_price' => $newCost,
                        'old_selling_price' => $oldSelling,
                        'new_selling_price' => $newSelling,
                        'user_id' => $userId
                    ]);

                    $product->update([
                        'cost_price' => $newCost,
                        'price' => $newSelling
                    ]);
                }

                return $product;
            });

            return response()->json([
                'success' => true,
                'message' => 'Product prices updated and logged successfully',
                'data' => $updated
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product prices',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Retrieve price histories
    public function getHistory(Request $request)
    {
        try {
            $query = ProductPriceHistory::with(['product.brand', 'user']);

            if ($request->filled('product_id')) {
                $query->where('product_id', $request->input('product_id'));
            }

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('created_at', [
                    $request->input('start_date') . ' 00:00:00',
                    $request->input('end_date') . ' 23:59:59'
                ]);
            }

            $history = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'message' => 'Price history retrieved successfully',
                'data' => $history
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve price histories',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
