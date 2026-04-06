<?php
// app/Http/Middleware/ClientMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ClientMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // First check if authenticated via CheckAuth middleware
        if (!session()->has('user_id') && !Auth::check()) {
            return redirect()->route('login.form');
        }

        // Check if user is client
        $userRole = session('user_role') ?? (Auth::user() ? Auth::user()->role : null);
        
        if ($userRole !== 'client') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access.'
                ], 403);
            }
            
            return redirect()->route('admin.dashboard')->with('error', 'Unauthorized access.');
        }

        return $next($request);
    }
}