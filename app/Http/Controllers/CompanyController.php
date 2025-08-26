<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function create_branch()
    {
        return view('company.branch_create');
    }
}
