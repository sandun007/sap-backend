<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use Exception;

class BranchController extends Controller
{
    //retrieve all branches
    public function index()
    {
        try {
            if(auth()->user()->role === 'admin' || auth()->user()->role === 'employee') {
                $branches = Branch::all();

                return response()->json([
                    'success' => true,
                    'message' => 'Branches retrieved successfully',
                    'data' => $branches,
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
                'message' => 'Failed to retrieve branches',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    //store a new branch
    public function store(Request $request)
    {
        try {
            if(auth()->user()->role === 'admin') {
                // Check for existing branch name
                if ($request->filled('branch_name') && Branch::where('branch_name', $request->input('branch_name'))->exists()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Branch name already exists',
                        'data' => null
                    ], 409);
                }

                // Check for existing email
                if ($request->filled('email') && Branch::where('email', $request->input('email'))->exists()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Branch email already exists',
                        'data' => null
                    ], 409);
                }

                $data = $request->validate([
                    'branch_code' => 'nullable|string',
                    'branch_name' => 'required|string|max:255',
                    'address' => 'nullable|string',
                    'phone_num1' => 'nullable|string|max:20',
                    'phone_num2' => 'nullable|string|max:20',
                    'email' => 'nullable|email|unique:branches,email',
                    'manager_id' => 'nullable|string',
                    'opening_date' => 'nullable|date',
                    'is_active' => 'boolean',
                ]);

                $branch = Branch::create($data);

                return response()->json([
                    'success' => true,
                    'message' => 'Branch created successfully',
                    'data' => $branch,
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
                'message' => 'Failed to create branch',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    //update branch
    public function update(Request $request, $id)
    {
        try {
            if(auth()->user()->role === 'admin') {
                $branch = Branch::where('branch_id', $id)->first();

                if (!$branch) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Branch not found',
                        'data' => null,
                    ], 404);
                }

                $data = $request->validate([
                    'branch_code' => 'nullable|string',
                    'branch_name' => 'nullable|string|max:255',
                    'address' => 'nullable|string',
                    'phone_num1' => 'nullable|string|max:20',
                    'phone_num2' => 'nullable|string|max:20',
                    'email' => 'nullable|email|unique:branches,email,' . $id . ',branch_id',
                    'manager_id' => 'nullable|exists:employees,employee_id',
                    'opening_date' => 'nullable|date',
                    'is_active' => 'boolean',
                ]);

                $branch->update($data);

                return response()->json([
                    'success' => true,
                    'message' => 'Branch updated successfully',
                    'data' => $branch,
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
                'message' => 'Failed to update branch',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    //delete branch
    public function destroy($id)
    {
        try {
            if(auth()->user()->role === 'admin') {
                $branch = Branch::find($id);

                if (!$branch) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Branch not found',
                        'data' => null,
                    ], 404);
                }

                $branch->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Branch deleted successfully',
                    'data' => null,
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
                'message' => 'Failed to delete branch',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function import(Request $request)
    {
        $branches = $request->input('branches', []);

        $imported = 0;
        $skipped = 0;

        foreach ($branches as $data) {

            $exists = Branch::where('branch_name', $data['branch_name'])
                ->orWhere('email', $data['email'])
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            Branch::create($data);
            $imported++;
        }

        return response()->json([
            'imported' => $imported,
            'skipped' => $skipped,
            'message' => "Imported $imported branch, skipped $skipped duplicates"
        ]);
    }

}

