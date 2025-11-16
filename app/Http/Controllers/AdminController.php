<?php

namespace App\Http\Controllers;

use App\Models\InvPara;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{


public function createAdmin(Request $request)
{
    $data = $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
    ]);

    $admin = User::create([
        'name'  => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
        'role' => 'admin',
    ]);

    return response()->json([
        'message' => 'Admin created successfully',
        'data' => $admin
    ], 201);
}




    public function login()
    {
        
    }
    public function dashboard()
    {
        
    }
}
