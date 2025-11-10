<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;

class BranchController extends Controller
{
    public function index()
    {
        return Branch::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'branch_name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:branches,email',
            'manager_id' => 'nullable|exists:employees,employee_id',
            'opening_date' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        return Branch::create($data);
    }

    public function update(Request $request, $id)
    {
        $branch = Branch::findOrFail($id);

        $data = $request->validate([
            'branch_name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:branches,email,' . $id . ',branch_id',
            'manager_id' => 'nullable|exists:employees,employee_id',
            'opening_date' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $branch->update($data);

        return $branch;
    }

    public function destroy($id)
    {
        $branch = Branch::findOrFail($id);
        $branch->delete();

        return ['message' => 'Branch deleted'];
    }
}
