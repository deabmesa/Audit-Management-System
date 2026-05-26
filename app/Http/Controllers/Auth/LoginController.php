<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show login page
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        // ? Validate input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // ? Attempt login
        if (Auth::attempt($credentials, $request->boolean('remember'))) {

            // Prevent session fixation
            $request->session()->regenerate();

            // Log activity
            UserActivityLog::create([
                'user_id'   => Auth::id(),
                'activity'  => 'User logged in',
                'ip_address'=> $request->ip(),
            ]);

            // Clear wrong redirect
            $request->session()->forget('url.intended');

            // ?? Optional: Role-based redirect
            $user = Auth::user();

            if ($user->role === 'Admin') {
                return redirect()->route('dashboard');
            }

            if ($user->role === 'Auditor') {
                return redirect()->route('dashboard');
            }

            return redirect()->route('dashboard');
        }

        // ? LOGIN FAILED (FIX HERE)
        return back()
            ->withErrors([
                'login' => 'Invalid username or password'
            ])
            ->onlyInput('email');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        // Log logout activity
        if (Auth::check()) {
            UserActivityLog::create([
                'user_id'   => Auth::id(),
                'activity'  => 'User logged out',
                'ip_address'=> $request->ip(),
            ]);
        }

        // Logout
        Auth::logout();

        // Destroy session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}