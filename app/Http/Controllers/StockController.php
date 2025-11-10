<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;

class StockController extends Controller
{
    public function index()
    {
        return Stock::with(['product', 'branch'])->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'branch_id' => 'required|exists:branches,branch_id',
            'quantity' => 'required|integer|min:0',
            'minimum_stock_level' => 'nullable|integer|min:0',
            'maximum_stock_level' => 'nullable|integer|min:0',
            'last_restocked_date' => 'nullable|date',
            'location_in_warehouse' => 'nullable|string|max:255',
        ]);

        return Stock::create($data);
    }

    public function update(Request $request, $id)
    {
        $stock = Stock::findOrFail($id);

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

        return $stock;
    }

    public function destroy($id)
    {
        $stock = Stock::findOrFail($id);
        $stock->delete();

        return ['message' => 'Stock record deleted'];
    }
}
