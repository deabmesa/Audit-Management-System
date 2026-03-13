<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required|in:Admin,Auditor,Reviewer',
        ]);
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        $this->logActivity($request, "Created user {$user->email}");

        return redirect()->route('users.index')->with('success', 'User created.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'role' => 'required|in:Admin,Auditor,Reviewer',
            'password' => 'nullable|min:8',
        ]);
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $user->update($data);

        $this->logActivity($request, "Updated user {$user->email}");

        return redirect()->route('users.index')->with('success', 'User updated.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->withErrors(['error' => 'You cannot delete your own account.']);
        }

        $email = $user->email;
        $user->delete();

        $this->logActivity($request, "Deleted user {$email}");

        return redirect()->route('users.index')->with('success', 'User deleted.');
    }

    public function logs()
    {
        $logs = UserActivityLog::with('user')->latest()->paginate(20);
        return view('users.logs', compact('logs'));
    }

    private function logActivity(Request $request, string $activity): void
    {
        UserActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => $activity,
            'ip_address' => $request->ip(),
        ]);
    }
}
