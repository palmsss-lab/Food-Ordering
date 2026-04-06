<?php
// app/Http/Middleware/AdminMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // First check if authenticated via CheckAuth middleware
        if (!session()->has('user_id') && !Auth::check()) {
            return redirect()->route('login.form');
        }

        // Check if user is admin
        $userRole = session('user_role') ?? (Auth::user() ? Auth::user()->role : null);
        
        if ($userRole !== 'admin') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access.'
                ], 403);
            }
            
            return redirect()->route('client.home')->with('error', 'Unauthorized access.');
        }

        return $next($request);
    }
}