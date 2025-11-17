<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use Exception;

class DepartmentController extends Controller
{
    public function index()
    {
        try {
            $departments = Department::with(['manager', 'branch'])->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Departments retrieved successfully',
                'data' => $departments
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve departments',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            if(auth()->user()->role === 'admin') {
            $data = $request->validate([
                'department_name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'manager_id' => 'nullable|exists:employees,employee_id',
                'branch_id' => 'required|exists:branches,branch_id',
                'created_date' => 'nullable|date',
            ]);

            $department = Department::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Department created successfully',
                'data' => $department
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'data' => null,
            ], 403);
        }
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create department',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            if(auth()->user()->role === 'admin') {
            $department = Department::find($id);
            
            if (!$department) {
                return response()->json([
                    'success' => false,
                    'message' => 'Department not found',
                    'data' => null
                ], 404);
            }

            $data = $request->validate([
                'department_name' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'manager_id' => 'nullable|exists:employees,employee_id',
                'branch_id' => 'nullable|exists:branches,branch_id',
                'created_date' => 'nullable|date',
            ]);

            $department->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Department updated successfully',
                'data' => $department
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'data' => null,
            ], 403);
        }
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update department',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            if(auth()->user()->role === 'admin') {
            $department = Department::find($id);
            
            if (!$department) {
                return response()->json([
                    'success' => false,
                    'message' => 'Department not found',
                    'data' => null
                ], 404);
            }

            $department->delete();

            return response()->json([
                'success' => true,
                'message' => 'Department deleted successfully',
                'data' => null
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'data' => null,
            ], 403);
        }
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete department',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}