<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    // Get all orders
    public function index()
    {
        return response()->json(Order::all());
    }

    // Add new order
    public function store(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string|unique:orders,order_number',
            'order_date' => 'nullable|date',
            'customer_id' => 'required|integer',
            'branch_id' => 'required|integer',
            'employee_id' => 'required|integer',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'nullable|string',
            'payment_status' => 'nullable|string',
            'shipping_address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $order = Order::create($request->all());
        return response()->json($order, 201);
    }


    // Update order
    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $request->validate([
            'order_number' => 'required|string|unique:orders,order_number,' . $id . ',order_id',
            'order_date' => 'nullable|date',
            'customer_id' => 'required|integer',
            'branch_id' => 'required|integer',
            'employee_id' => 'required|integer',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'nullable|string',
            'payment_status' => 'nullable|string',
            'shipping_address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $order->update($request->all());
        return response()->json($order);
    }

    // Delete order
    public function destroy($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->delete();
        return response()->json(['message' => 'Order deleted successfully']);
    }
}
