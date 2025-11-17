<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Exception;

class EmployeeController extends Controller
{
    public function index()
    {
        try {
            $user = auth()->user();
            if($user && $user->role === 'admin') {
                $employees = Employee::all();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Employees retrieved successfully',
                    'data' => $employees
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
                'message' => 'Failed to retrieve employees',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            if(auth()->user()->role === 'admin') {
                $data = $request->validate([
                    'first_name' => 'required|string|max:255',
                    'last_name' => 'required|string|max:255',
                    'email' => 'required|email|unique:users,email',
                    'phone' => 'nullable|string|max:20',
                    'hire_date' => 'nullable|date',
                    'position' => 'nullable|string|max:255',
                    'salary' => 'nullable|numeric',
                    'address' => 'nullable|string',
                    'date_of_birth' => 'nullable|date',
                    'department_id' => 'required|exists:departments,department_id',
                    'is_active' => 'boolean',
                    'password' => 'required|string|min:6',
                ]);

                $user = User::create([
                    'name'  => $data['first_name'] . ' ' . $data['last_name'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'role' => 'employee',
                ]);

                unset($data['password']);

                $employee = Employee::create($data);

                return response()->json([
                    'success' => true,
                    'message' => 'Employee created successfully',
                    'user_id' => $user->id,
                    'data' => $employee
                ], 201);
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
                'message' => 'Failed to create employee',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $employee = Employee::find($id);

            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found',
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
                'first_name' => 'nullable|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
                'hire_date' => 'nullable|date',
                'position' => 'nullable|string|max:255',
                'salary' => 'nullable|numeric',
                'address' => 'nullable|string',
                'date_of_birth' => 'nullable|date',
                'department_id' => 'nullable|exists:departments,department_id',
                'manager_id' => 'nullable|exists:employees,employee_id',
                'is_active' => 'boolean',
            ]);

            $employee->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Employee updated successfully',
                'data' => $employee
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update employee',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $employee = Employee::find($id);

            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found',
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

            $employee->delete();

            return response()->json([
                'success' => true,
                'message' => 'Employee deleted successfully',
                'data' => null
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete employee',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}