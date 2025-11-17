<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if($user && $user->role === 'admin') {
            return Employee::all();
        } else {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    }


public function store(Request $request)
{
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
        'message' => 'Employee created successfully',
        'user_id' => $user->id,
        'data' => $employee
    ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'data' => null,
            ], 403);
        }


    
}


    public function update(Request $request, $id)
    {
       
        
        $employee = Employee::find($id);

        if (!$employee || auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Employee not found'], 404);
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
        'message' => 'Employee update successfully',
        'data' => $employee
    ], 201);
    }

    public function destroy($id)
    {
        $employee = Employee::find($id);

        if (!$employee || auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        $employee->delete();

        return response()->json([
        'message' => 'Employee delete successfully',
    ], 200);
    }
}
