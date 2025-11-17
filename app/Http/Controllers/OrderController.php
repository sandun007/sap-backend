<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Exception;

class OrderController extends Controller
{
    // Get all orders
    public function index()
    {
        try {
            $orders = Order::all();
            
            return response()->json([
                'success' => true,
                'message' => 'Orders retrieved successfully',
                'data' => $orders
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve orders',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Add new order
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
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

            $order = Order::create($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => $order
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Update order
    public function update(Request $request, $id)
    {
        try {
            $order = Order::find($id);
            
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found',
                    'data' => null
                ], 404);
            }

            $data = $request->validate([
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

            $order->update($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Order updated successfully',
                'data' => $order
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Delete order
    public function destroy($id)
    {
        try {
            $order = Order::find($id);
            
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found',
                    'data' => null
                ], 404);
            }

            $order->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Order deleted successfully',
                'data' => null
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete order',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}