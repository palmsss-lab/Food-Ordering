<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminProfileController extends Controller
{
    /**
     * Show the profile view page.
     */
    public function show()
    {
        // Get the logged-in admin from session
        $admin = session()->get('user');
        
        // If not in session, fetch from database
        if (!$admin) {
            $adminId = session()->get('user_id');
            $admin = User::find($adminId);
            session()->put('user', $admin);
        }
        
        return view('admin.profile.show', compact('admin'));
    }

    /**
     * Show the profile edit form.
     */
    public function edit()
    {
        // Get the logged-in admin from session
        $admin = session()->get('user');
        
        // If not in session, fetch from database
        if (!$admin) {
            $adminId = session()->get('user_id');
            $admin = User::find($adminId);
            session()->put('user', $admin);
        }
        
        return view('admin.profile.edit', compact('admin'));
    }

    /**
     * Update profile information.
     */
    public function update(Request $request)
    {
        $adminId = session()->get('user_id');
        $admin = User::find($adminId);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $adminId,
        ]);

        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update session data
        session()->put('user', $admin);
        session()->put('user_name', $admin->name);

        return redirect()->route('admin.profile.show')->with('success', 'Profile updated successfully!');
    }

    /**
     * Show the change password form.
     */
    public function changePassword()
    {
        $admin = session()->get('user');
        return view('admin.profile.change-password', compact('admin'));
    }

    /**
     * Update password.
     */
    public function updatePassword(Request $request)
    {
        $adminId = session()->get('user_id');
        $admin = User::find($adminId);

        $request->validate([
            'current_password' => 'required',
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ]);

        // Check current password
        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->with('error', 'Current password is incorrect.');
        }

        // Update password
        $admin->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->route('admin.profile.show')->with('success', 'Password changed successfully!');
    }
}