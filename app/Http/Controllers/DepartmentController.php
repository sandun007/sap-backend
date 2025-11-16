<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function index()
    {
        return Department::with(['manager', 'branch'])->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'department_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'manager_id' => 'nullable|exists:employees,employee_id',
            'branch_id' => 'required|exists:branches,branch_id',
            'created_date' => 'nullable|date',
        ]);

        return Department::create($data);
    }

    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        $data = $request->validate([
            'department_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'manager_id' => 'nullable|exists:employees,employee_id',
            'branch_id' => 'required|exists:branches,branch_id',
            'created_date' => 'nullable|date',
        ]);

        $department->update($data);

        return $department;
    }

    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();

        return ['message' => 'Department deleted'];
    }
}
