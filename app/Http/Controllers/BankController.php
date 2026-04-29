<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => Bank::all()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'bank_code'=>'required|unique:banks',
            'bank_name'=>'required',
            'branch_name'=>'required',
            'account_number'=>'required',
            'account_holder_name'=>'required',
        ]);

        $bank = Bank::create($request->all());

        return response()->json([
            'message'=>'Bank created successfully',
            'data'=>$bank
        ],201);
    }

    public function show($id)
    {
        return response()->json(
            Bank::findOrFail($id)
        );
    }

    public function update(Request $request,$id)
    {
        $bank = Bank::findOrFail($id);

        $bank->update($request->all());

        return response()->json([
            'message'=>'Updated successfully',
            'data'=>$bank
        ]);
    }

    public function destroy($id)
    {
        Bank::findOrFail($id)->delete();

        return response()->json([
            'message'=>'Deleted successfully'
        ]);
    }
}