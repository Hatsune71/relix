<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersAuthentication
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('api')->check()) {
            return $next($request);
        } else {
            $data = "Token Salah";
            return response()->json([
                'error'=>true,
                'data' =>$data
            ], 401);
        }
    }
}