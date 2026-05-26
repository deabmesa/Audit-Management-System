<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * ✅ FIX: Show registration form
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * ✅ FIX: Handle user registration with proper validation and error handling
     */
    public function register(Request $request)
    {
        try {
            // ✅ Validate input
            $data = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'unique:users,email'],
                'password' => [
                    'required',
                    'confirmed',
                    Password::min(8)
                        ->mixedCase()
                        ->numbers()
                        ->symbols(),
                ],
            ], [
                'password.confirmed' => 'Password confirmation does not match.',
                'password.mixed_case' => 'Password must contain both uppercase and lowercase letters.',
                'password.numbers' => 'Password must contain at least one number.',
                'password.symbols' => 'Password must contain at least one special character.',
            ]);

            // ✅ Create user with default role
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'user',  // ✅ FIX: Set default role
                'is_active' => true,
            ]);

            // ✅ Log activity
            UserActivityLog::create([
                'user_id' => $user->id,
                'activity' => 'User self-registered',
                'ip_address' => $request->ip(),
            ]);

            // ✅ Log in user
            auth()->login($user);

            // ✅ Redirect to dashboard
            return redirect()->route('dashboard')->with('success', 'Welcome! Your account has been created.');

        } catch (\Illuminate\Database\QueryException $e) {
            // ✅ Handle database errors
            return back()
                ->withInput($request->except('password'))
                ->withErrors(['email' => 'This email is already registered.']);

        } catch (\Exception $e) {
            // ✅ Handle general errors
            \Log::error('Registration error: ' . $e->getMessage());
            return back()
                ->withInput($request->except('password'))
                ->withErrors(['general' => 'An error occurred during registration. Please try again.']);
        }
    }
}
