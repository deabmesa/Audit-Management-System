<?php

namespace App\Http\Controllers;

use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(private readonly AuditLogger $auditLogger)
    {
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Invalid credentials'])->onlyInput('email');
        }

        $request->session()->regenerate();

        $this->auditLogger->log('login', [
            'user_id' => Auth::id(),
            'ip' => $request->ip(),
        ]);

        return redirect()->route('checkin.form');
    }

    public function logout(Request $request)
    {
        if (session('checked_in')) {
            return redirect()->route('checkin.form')->withErrors([
                'checkout' => 'You must check-out before logout.',
            ]);
        }

        $this->auditLogger->log('logout', [
            'user_id' => Auth::id(),
            'ip' => $request->ip(),
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.form');
    }
}
