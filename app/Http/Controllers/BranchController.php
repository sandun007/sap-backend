<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;

class BranchController extends Controller
{
    //retrive all branches
    public function index()
    {
        if(auth()->user()->role === 'admin' || auth()->user()->role === 'employee') {
            $branches = Branch::all();
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'data' => null,
            ], 403);
        }
        $branches = Branch::all();

        return response()->json([
            'success' => true,
            'message' => 'Branches retrieved successfully',
            'data' => $branches,
        ], 200);
    }

    //store a new branch
    public function store(Request $request)
    {

        if(auth()->user()->role === 'admin') {
            if ($request->filled('branch_name') && Branch::where('branch_name', $request->input('branch_name'))->exists()) {
        return response()->json(['message' => 'branch already exists'], 409);
    }
    
    if ($request->filled('email') && Branch::where('email', $request->input('email'))->exists()) {
        return response()->json(['message' => 'branch email already exists'], 409);
    }
      

        $data = $request->validate([
            'branch_name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:branches,email',
            'manager_id' => 'nullable|exists:employees,employee_id',
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
        

        
    }

    //update branch
    public function update(Request $request, $id)
    {
         
        if(auth()->user()->role === 'admin') {
  
        $branch = Branch::where('branch_id', $id)->first();
        
        if (! $branch) {
            return response()->json([
                'success' => false,
                'message' => 'Branch not found',
                'data' => null,
            ], 404);
        }

        

        $data = $request->validate([
            'branch_name' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:branches,email,' . $id . ',branch_id',
            'manager_id' => 'nullable|exists:employees,employee_id',
            'opening_date' => 'nullable|date',
            'is_active' => 'boolean',
        ]);
        //  return response()->json([
        //     'data' => $data,
        // ], 200);

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

    }

    //delete branch
    public function destroy($id)
    {

        if(auth()->user()->role === 'admin') {
           $branch = Branch::find($id);

        if (! $branch) {
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
        
    }
}
