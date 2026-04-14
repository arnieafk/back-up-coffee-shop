<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    // Show the login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        // Validate input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt login
        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Login failed: Incorrect email or password.',
            ]);
        }

        // Regenerate session
        $request->session()->regenerate();

        // Get the authenticated user
        $user = Auth::user();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Login failed: User not found after authentication.',
            ]);
        }

        // Ensure the user has a role
        if (!$user->role) {
            Log::error('User logged in but role is missing', ['user_id' => $user->id]);
            Auth::logout();
            return back()->withErrors([
                'email' => 'Login failed: No role assigned to this account.',
            ]);
        }

        // Normalize role to lowercase
        $role = strtolower($user->role);

        // Redirect based on role
        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard');

            case 'staff':
            case 'barista':
                return redirect()->route('staff.dashboard');

            case 'customer':
                return redirect()->route('customer.dashboard');

            default:
                Log::error('User has unrecognized role', [
                    'user_id' => $user->id,
                    'role' => $user->role,
                ]);
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Login failed: Role not recognized.',
                ]);
        }
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
