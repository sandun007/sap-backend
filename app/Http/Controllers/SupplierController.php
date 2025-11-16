<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function index()
    {
        return Supplier::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'supplier_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:suppliers,email',
            'address' => 'nullable|string',
            'tax_id' => 'nullable|string|max:50',
            'payment_terms' => 'nullable|string|max:255',
            'created_date' => 'nullable|date',
        ]);

        return Supplier::create($data);
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $data = $request->validate([
            'supplier_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:suppliers,email,' . $id . ',supplier_id',
            'address' => 'nullable|string',
            'tax_id' => 'nullable|string|max:50',
            'payment_terms' => 'nullable|string|max:255',
            'created_date' => 'nullable|date',
        ]);

        $supplier->update($data);

        return $supplier;
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return ['message' => 'Supplier deleted'];
    }
}
