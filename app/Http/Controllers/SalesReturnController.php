<?php

namespace App\Http\Controllers;

use App\Models\SalesReturn;
use Illuminate\Http\Request;

class SalesReturnController extends Controller
{
    public function index()
    {
        $returns = SalesReturn::with(['invoice', 'items', 'creator'])
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data'    => $returns
        ]);
    }

    public function show($id)
    {
        $return = SalesReturn::with(['invoice', 'items', 'creator'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $return
        ]);
    }
}
