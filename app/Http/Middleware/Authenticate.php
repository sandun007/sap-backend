<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Handle unauthenticated requests.
     */
    protected function unauthenticated($request, array $guards)
    {
        abort(response()->json([
            'message' => 'Please login'
        ], 401));
    }

    protected function redirectTo(Request $request): ?string
    {
        return null; // No redirect needed
    }
}
