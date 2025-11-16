<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

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
            'email' => 'nullable|email|unique:employees,email',
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

        return Employee::create($data);
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
