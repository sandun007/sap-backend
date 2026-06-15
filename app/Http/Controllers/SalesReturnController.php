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

    public function verifyCreditNote(Request $request)
    {
        $code = $request->query('code');
        if (!$code) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher code is required'
            ], 400);
        }

        $code = trim($code);
        if (preg_match('/(RTN-\d{8}-\d{4})/i', $code, $matches)) {
            $code = strtoupper($matches[1]);
        }

        $salesReturn = SalesReturn::where('return_number', $code)->first();

        if (!$salesReturn) {
            return response()->json([
                'success' => false,
                'message' => 'Credit note voucher not found'
            ], 404);
        }

        if ($salesReturn->refund_method !== 'store_credit') {
            return response()->json([
                'success' => false,
                'message' => 'Voucher code is not a store credit note'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'remaining_credit' => floatval($salesReturn->remaining_credit),
            'refund_amount' => floatval($salesReturn->refund_amount),
            'return_number' => $salesReturn->return_number
        ]);
    }
}
