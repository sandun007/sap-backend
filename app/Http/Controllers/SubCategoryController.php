<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubCategory;

class SubCategoryController extends Controller
{
    public function index()
    {
        return SubCategory::with('category')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subcategory_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,category_id',
            'created_date' => 'nullable|date',
        ]);

        return SubCategory::create($data);
    }


    public function update(Request $request, $id)
    {
        $subcategory = SubCategory::findOrFail($id);

        $data = $request->validate([
            'subcategory_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,category_id',
            'created_date' => 'nullable|date',
        ]);

        $subcategory->update($data);

        return $subcategory;
    }

    public function destroy($id)
    {
        $subcategory = SubCategory::findOrFail($id);
        $subcategory->delete();

        return ['message' => 'SubCategory deleted'];
    }
}
