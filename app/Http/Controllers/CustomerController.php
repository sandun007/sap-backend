<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Exception;

class CustomerController extends Controller
{
    // List all customers
    public function index()
    {
        try {
            $user = auth()->user();
            if($user && $user->role === 'admin') {
                $customers = Customer::all();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Customers retrieved successfully',
                    'data' => $customers
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'data' => null
                ], 403);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve customers',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Create new customer
    public function store(Request $request)
    {
        try {
            // Custom check for already-existing email: if the request supplied an email and it exists, return 409
            if ($request->filled('email') && User::where('email', $request->input('email'))->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email already exists in users',
                    'data' => null
                ], 409);
            }

            if ($request->filled('email') && Customer::where('email', $request->input('email'))->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email already exists in customers',
                    'data' => null
                ], 409);
            }

            $messages = [
                'email.unique' => 'This email is already registered. Please use another email.'
            ];

            $validated = $request->validate([
                'customer_code' => 'nullable|string|max:50',
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
                'success' => true,
                'message' => 'Customer created successfully',
                'user_id' => $user->id,
                'data' => $customer
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create customer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Update customer
    public function update(Request $request, $id)
    {
        try {
            $customer = Customer::find($id);
            
            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found',
                    'data' => null
                ], 404);
            }

            if (auth()->user()->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'data' => null
                ], 403);
            }

            $data = $request->validate([
                'customer_code' => 'nullable|string|max:50',
                'first_name' => 'nullable|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'company_name' => 'nullable|string|max:255',
                'email' => 'nullable|email|unique:customers,email,' . $id . ',customer_id',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string',
                'customer_type' => 'nullable|string|max:50',
                'registration_date' => 'nullable|date',
                'credit_limit' => 'nullable|numeric',
            ]);

            $customer->update($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Customer updated successfully',
                'data' => $customer
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update customer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Delete customer
    public function destroy($id)
    {
        try {
            $customer = Customer::find($id);
            
            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found',
                    'data' => null
                ], 404);
            }

            $customer->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Customer deleted successfully',
                'data' => null
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete customer',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}