<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    // List all customers
    public function index()
    {
        $customers = Customer::all();
        return response()->json($customers);
    }


// Create new customer
public function store(Request $request)
{
    
    // Custom check for already-existing email: if the request supplied an email and it exists, return 409
    if ($request->filled('email') && User::where('email', $request->input('email'))->exists()) {
        return response()->json(['message' => 'Email already exists'], 409);
    }

    if ($request->filled('email') && Customer::where('email', $request->input('email'))->exists()) {
        return response()->json(['message' => 'Email already exists'], 409);
    }

    $messages = [
        'email.unique' => 'This email is already registered. Please use another email.'
    ];

    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'company_name' => 'nullable|string|max:255',
        'email' => 'required|email|unique:users,email',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string',
        'customer_type' => 'nullable|string|max:50',
        'registration_date' => 'nullable|date',
        'credit_limit' => 'nullable|numeric',
        'password' => 'required|string|min:6', // password required
    ], $messages);

    // Create USER record
    $user = User::create([
        'name'  => $validated['first_name'] . ' ' . $validated['last_name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => 'customer',
    ]);

    // Remove password before creating customer record
    unset($validated['password']);

    // Create CUSTOMER record
    $customer = Customer::create($validated);

    

    return response()->json([
        'message' => 'Customer created successfully',
        'user_id' => $user->id,
        'data' => $customer
    ], 201);
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
