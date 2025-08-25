<?php

namespace App\Http\Controllers;

use App\Models\InvPara;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function login()
    {
        $inv_data = InvPara::query()->first();
        return view('login',compact('inv_data'));
    }
    public function dashboard()
    {
        $inv_data = InvPara::query()->first();
        return view('dashboard',compact('inv_data'));
    }
}
