<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetUserType
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            if (!session('user_type') && !session('user_role')) {
                $userType = $user->role ?? 'client';
                session(['user_type' => $userType]);
                session(['user_role' => $userType]);
            }
        }
        
        return $next($request);
    }
}