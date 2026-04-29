<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;

class StoreController extends Controller
{
    public function index()
{
    return response()->json([
        'data' => Store::select('id', 'store_code', 'store_name')->get()
    ]);
}

    public function store(Request $request)
    {
        $request->validate([
            'store_code'=>'required|unique:stores',
            'store_name'=>'required'
        ]);

        $store = Store::create([
            'store_code'=>$request->store_code,
            'store_name'=>$request->store_name
        ]);

        return response()->json([
            'message'=>'Store created successfully',
            'data'=>$store
        ],201);
    }

    public function update(Request $request,$id)
    {
        $store=Store::findOrFail($id);

        $store->update([
            'store_code'=>$request->store_code,
            'store_name'=>$request->store_name
        ]);

        return response()->json([
            'message'=>'Updated successfully',
            'data'=>$store
        ]);
    }

    public function destroy($id)
    {
        Store::findOrFail($id)->delete();

        return response()->json([
            'message'=>'Deleted successfully'
        ]);
    }
}