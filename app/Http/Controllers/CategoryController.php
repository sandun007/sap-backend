<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    // List all categories
    public function index()
    {
        return Category::all();
    }

    // Add new category
    public function store(Request $request)
    {
        $data = $request->validate([
            'category_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'created_date' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        return Category::create($data);
    }

    // Update category
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $data = $request->validate([
            'category_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'created_date' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $category->update($data);

        return $category;
    }

    // Delete category
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return ['message' => 'Category deleted'];
    }
}
