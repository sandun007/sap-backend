<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function create()
    {
        return view('product.product_create');
    }

    //    =============================== Brand ===========================================

    public function brand_create()
    {
        return view('product.brand');
    }


//    =================================== Category ================================================

    public function category_create()
    {
        return view('product.category');
    }
}
