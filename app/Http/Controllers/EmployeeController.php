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
        return Employee::with(['department', 'manager'])->get();
    }


public function store(Request $request)
{
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
        'manager_id' => 'nullable|exists:employees,employee_id',
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
}


    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:employees,email,' . $id . ',employee_id',
            'phone' => 'nullable|string|max:20',
            'hire_date' => 'nullable|date',
            'position' => 'nullable|string|max:255',
            'salary' => 'nullable|numeric',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'department_id' => 'required|exists:departments,department_id',
            'manager_id' => 'nullable|exists:employees,employee_id',
            'is_active' => 'boolean',
        ]);

        $employee->update($data);

        return $employee;
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return ['message' => 'Employee deleted'];
    }
}
