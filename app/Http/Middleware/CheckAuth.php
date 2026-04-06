<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated via session
        if (!session()->has('user_id')) {
            // If it's an AJAX request, return JSON response
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please login to continue.'
                ], 401);
            }
            
            return redirect()->route('login.form')->with('error', 'Please login to continue.');
        }

        // If user is in session but not logged into Auth, log them in
        if (!Auth::check()) {
            $user = \App\Models\User::find(session('user_id'));
            if ($user) {
                Auth::login($user);
                Log::info('Auto-logged in user from session: ' . $user->id);
            } else {
                // User not found in database, clear session
                session()->flush();
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'User not found. Please login again.'
                    ], 401);
                }
                
                return redirect()->route('login.form')->with('error', 'User not found. Please login again.');
            }
        }

        return $next($request);
    }
}