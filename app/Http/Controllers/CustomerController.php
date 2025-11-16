<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    // List all customers
    public function index()
    {
        $customers = Customer::all();
        return response()->json($customers);
    }

    // Add new customer
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:customers,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'customer_type' => 'nullable|string|max:50',
            'registration_date' => 'nullable|date',
            'credit_limit' => 'nullable|numeric',
        ]);

        $customer = Customer::create($request->all());
        return response()->json($customer, 201);
    }



    // Update customer
    public function update(Request $request, $id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:customers,email,' . $id . ',customer_id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'customer_type' => 'nullable|string|max:50',
            'registration_date' => 'nullable|date',
            'credit_limit' => 'nullable|numeric',
        ]);

        $customer->update($request->all());
        return response()->json($customer);
    }

    // Delete customer
    public function destroy($id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $customer->delete();
        return response()->json(['message' => 'Customer deleted successfully']);
    }
}
