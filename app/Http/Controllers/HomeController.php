<?php
// app/Http/Controllers/HomeController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Use Auth facade consistently

class HomeController extends Controller
{
    public function index()
    {
        // Check if user is logged in via session or Auth (using Auth facade)
        if (!session()->has('user_id') && !Auth::check()) {
            return redirect()->route('login.form');
        }
        
        // Try to get authenticated user
        $user = $this->getAuthenticatedUser();
        
        if (!$user) {
            session()->flush();
            return redirect()->route('login.form')->with('error', 'Invalid session. Please login again.');
        }
        
        // Get user role
        $userRole = $this->getUserRole($user);
        
        if (!$userRole) {
            session()->flush();
            return redirect()->route('login.form')->with('error', 'User role not found. Please login again.');
        }
        
        // Redirect based on role
        if ($userRole === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        
        return redirect()->route('client.home');
    }
    
    /**
     * Get authenticated user from Auth or session
     */
    private function getAuthenticatedUser()
    {
        // Check Auth first (using Auth facade)
        if (Auth::check()) {
            return Auth::user();
        }
        
        // Check session
        if (session()->has('user_id')) {
            $user = User::find(session('user_id'));
            if ($user) {
                // Log the user into Auth
                Auth::login($user);
                return $user;
            }
        }
        
        return null;
    }
    
    /**
     * Get user role from user object or session
     */
    private function getUserRole($user)
    {
        // Try to get role from user object first
        if ($user && isset($user->role)) {
            return $user->role;
        }
        
        // Fallback to session
        if (session()->has('user_role')) {
            return session('user_role');
        }
        
        return null;
    }
}