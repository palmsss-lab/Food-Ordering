<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserRegistered;
use App\Hub\SystemHub;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthenticationController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Show signup form
    public function showSignupForm()
    {
        return view('auth.signup');
    }

    // Handle signup
    public function signup(Request $request)
    {
        try {
            $validator = validator($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'username' => 'required|string|min:3|unique:users,username',
                'password' => 'required|string|min:6|confirmed',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                // Return ALL validation errors at once
                return response()->json([
                    'success' => false,
                    'message' => 'Please fix the following errors:',
                    'errors' => $validator->errors()->all() // Returns array of all errors
                ], 422);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'address' => $request->address,
                'role' => 'client'
            ]);

            Auth::login($user);
            $this->setUserSession($user);

            // Route registration event through hub to AccountSpoke
            app(SystemHub::class)->dispatch(new UserRegistered($user));

            Log::info('New user registered and logged in', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->role
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Account created successfully!',
                'redirect' => $user->isAdmin() ? route('admin.dashboard') : route('client.home'),
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Signup error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.'
            ], 500);
        }
    }

    // Handle login
    public function login(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string'
            ]);

            // Find user by username or email
            $user = User::where('username', $request->username)
                        ->orWhere('email', $request->username)
                        ->first();

            // Check credentials
            if (!$user || !Hash::check($request->password, $user->password)) {
                Log::warning('Failed login attempt', [
                    'username' => $request->username,
                    'ip' => $request->ip()
                ]);
                
                // Check if it's an AJAX request
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid email/username or password. Please try again.',
                        'errors' => [
                            'username' => 'Invalid credentials',
                            'password' => 'Invalid credentials'
                        ]
                    ], 401);
                }
                
                return back()->with('error', 'Invalid email/username or password.')->withInput($request->except('password'));
            }

            // Check if user is active/banned (if you have such fields)
            if (isset($user->is_active) && !$user->is_active) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Your account has been deactivated. Please contact support.'
                    ], 403);
                }
                return back()->with('error', 'Your account has been deactivated.');
            }

            // Log the user in
            Auth::login($user, $request->has('remember'));
            $this->setUserSession($user);
            
            // Regenerate session for security
            $request->session()->regenerate();

            Log::info('User logged in successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
                'ip' => $request->ip()
            ]);

            // Check if it's an AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Welcome back, ' . $user->name . '!',
                    'redirect' => $user->isAdmin() ? route('admin.dashboard') : route('client.home'),
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role
                    ]
                ]);
            }

            // Regular form submission
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')->with('success', 'Welcome back, Admin!');
            }
            return redirect()->route('client.home')->with('success', 'Welcome back, ' . $user->name . '!');

        } catch (ValidationException $e) {
            // Handle validation errors
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please fill in all required fields.',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong. Please try again.'
                ], 500);
            }
            
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    // Handle logout
    public function logout(Request $request)
    {
        $userName = Auth::user() ? Auth::user()->name : 'Guest';
        
        // Log the user out
        Auth::logout();
        
        // Clear custom session data
        session()->flush();
        
        // Invalidate the session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info('User logged out', ['user' => $userName]);

        // Check if it's an AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully.',
                'redirect' => route('login.form')
            ]);
        }

        return redirect()->route('login.form')->with('success', 'Logged out successfully.');
    }

    // ==================== HELPER METHODS ====================

    /**
     * Set user session data
     */
    private function setUserSession($user)
    {
        session()->put('user', $user);
        session()->put('user_id', $user->id);
        session()->put('user_role', $user->role);
        session()->put('user_type', $user->role); // ADD THIS LINE - sets user_type
        session()->put('user_name', $user->name);
    }

    /**
     * Generate a unique username from the user's name
     */
    protected function generateUsername($name)
    {
        $username = strtolower(str_replace(' ', '', $name));
        $original = $username;
        $counter = 1;
        
        while (User::where('username', $username)->exists()) {
            $username = $original . $counter;
            $counter++;
        }
        
        return $username;
    }
}