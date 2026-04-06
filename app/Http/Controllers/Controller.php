<?php

namespace App\Http\Controllers;

abstract class Controller
{
    /**
     * Get the currently logged in user from session
     */
    protected function getLoggedInUser()
    {
        return session()->get('user');
    }

    /**
     * Get the currently logged in user ID from session
     */
    protected function getLoggedInUserId()
    {
        return session()->get('user_id');
    }

    /**
     * Get the currently logged in user role from session
     */
    protected function getLoggedInUserRole()
    {
        return session()->get('user_role');
    }

    /**
     * Get the currently logged in user name from session
     */
    protected function getLoggedInUserName()
    {
        return session()->get('user_name');
    }

    /**
     * Update user session data
     */
    protected function updateUserSession($user)
    {
        session()->put('user', $user);
        session()->put('user_id', $user->id);
        session()->put('user_role', $user->role);
        session()->put('user_name', $user->name);
    }

    /**
     * Check if current user is admin
     */
    protected function isAdmin()
    {
        return session()->get('user_role') === 'admin';
    }

    /**
     * Check if current user is client
     */
    protected function isClient()
    {
        return session()->get('user_role') === 'client';
    }

    /**
     * Check if user is logged in
     */
    protected function isLoggedIn()
    {
        return session()->has('user_id');
    }

    /**
     * Redirect with success message
     */
    protected function redirectWithSuccess($route, $message)
    {
        return redirect()->route($route)->with('success', $message);
    }

    /**
     * Redirect back with error message
     */
    protected function backWithError($message)
    {
        return back()->with('error', $message);
    }

    /**
     * Redirect back with success message
     */
    protected function backWithSuccess($message)
    {
        return back()->with('success', $message);
    }
}