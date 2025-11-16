<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;

class BrandController extends Controller
{
    public function index()
    {
        return Brand::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'brand_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:brands,email',
            'website' => 'nullable|string|max:255',
            'created_date' => 'nullable|date',
        ]);

        return Brand::create($data);
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $data = $request->validate([
            'brand_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:brands,email,' . $id . ',brand_id',
            'website' => 'nullable|string|max:255',
            'created_date' => 'nullable|date',
        ]);

        $brand->update($data);

        return $brand;
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();

        return ['message' => 'Brand deleted'];
    }
}
