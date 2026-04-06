<?php

namespace App\Http\Controllers\Client;

use App\Models\User;
use App\Models\Order;
use App\Models\CartItem;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ClientProfileController extends Controller
{

   

    public function profile()
    {
        // No need to check session here because middleware already did
        $user = session()->get('user'); // Get the logged-in user
        return view('client.client-profile.my-profile', compact('user'));
    }


    public function edit()
    {
        // Get the logged-in user from session
        $user = session()->get('user');
        return view('client.client-profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $userId = session()->get('user_id');
        $user = User::find($userId);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $userId,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address
        ]);

        // Update session data
        session()->put('user', $user);
        session()->put('user_name', $user->name);

        return redirect()->route('client.profile')->with('success', 'Profile updated successfully!');
    }

    public function changePassword()
    {
        return view('client.client-profile.change-password');
    }

    public function updatePassword(Request $request)
    {
        $userId = session()->get('user_id');
        $user = User::find($userId);

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Current password is incorrect.');
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->route('client.profile')->with('success', 'Password changed successfully!');
    }


    /**
     * Delete user account with email reuse capability
     */
    public function deleteAccount(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login.form')->with('error', 'User not found.');
        }
        
        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Invalid password.');
        }
        
        DB::beginTransaction();
        
        try {
            $userId = $user->id;
            $originalEmail = $user->email;
            
            // 1. Archive original email (for audit trail)
            DB::table('archived_emails')->insert([
                'user_id' => $userId,
                'original_email' => $originalEmail,
                'deleted_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // 2. Anonymize user data
            $user->update([
                'name' => 'Deleted User',
                'email' => 'deleted_' . $userId . '_' . time() . '@deleted.com',
                'phone' => null,
                'address' => null,
                'email_verified_at' => null,
                'remember_token' => null,
            ]);
            
            // 3. Soft delete the user
            $user->delete();
            
            // 4. Logout
            Auth::logout();
            session()->flush();
            
            DB::commit();
            
            return redirect()->route('login.form')->with('success', 
                'Your account has been deleted. Your transaction history is preserved. You can now create a new account with the same email if you wish.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Account deletion failed for user ' . $user->id . ': ' . $e->getMessage());
            return back()->with('error', 'Failed to delete account. Please try again or contact support.');
        }
    }
    
    /**
     * Verify user password for AJAX
     */
    public function verifyPassword(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['valid' => false, 'message' => 'User not authenticated'], 401);
            }
            
            $valid = Hash::check($request->password, $user->password);
            
            return response()->json(['valid' => $valid]);
            
        } catch (\Exception $e) {
            Log::error('Password verification error: ' . $e->getMessage());
            return response()->json(['valid' => false, 'error' => 'Verification failed'], 500);
        }
    }

}