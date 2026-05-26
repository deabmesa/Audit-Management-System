<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LIST USERS
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $query = User::with('roles');

        // ?? FILTER
        if ($request->role) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // ?? SEARCH
        if ($request->q) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->q}%")
                  ->orWhere('email', 'like', "%{$request->q}%");
            });
        }

        $users = $query->latest()->paginate(10);

        $roles = Role::pluck('name');

        return view('users.index', compact('users','roles'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE FORM
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE USER
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role'     => 'required|string|exists:roles,name',
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'is_active' => true,
        ]);

        // Assign role — form sends a single role name
        $role = Role::where('name', $request->role)->first();
        if ($role) {
            $user->roles()->sync([$role->id]);
        }

        $this->logActivity($request, "Created user {$user->email}");

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT FORM
    |--------------------------------------------------------------------------
    */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user','roles'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE USER
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|min:8',
            'roles'    => 'required|array'
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        // ? SYNC ROLES
        $user->roles()->sync($request->roles);

        $this->logActivity($request, "Updated user {$user->email}");

        return redirect()->route('users.index')->with('success', 'User updated.');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE USER
    |--------------------------------------------------------------------------
    */
    public function toggleStatus(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'You cannot change your own status.']);
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';
        $this->logActivity($request, "User {$user->email} {$status}");

        return back()->with('success', "User {$user->name} has been {$status}.");
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->withErrors(['error' => 'You cannot delete your own account.']);
        }

        $email = $user->email;

        $user->roles()->detach(); // ? CLEAN RELATION
        $user->delete();

        $this->logActivity($request, "Deleted user {$email}");

        return redirect()->route('users.index')->with('success', 'User deleted.');
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE ROLE (AJAX)
    |--------------------------------------------------------------------------
    */
    public function updateRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role'    => 'required|string'
        ]);

        $user = User::findOrFail($request->user_id);

        $role = Role::where('name', $request->role)->first();

        if (!$role) {
            return response()->json(['success'=>false,'message'=>'Role not found']);
        }

        $user->roles()->sync([$role->id]);

        $this->logActivity($request, "Changed role of {$user->email} to {$role->name}");

        return response()->json(['success'=>true]);
    }

    /*
    |--------------------------------------------------------------------------
    | ACTIVITY LOG
    |--------------------------------------------------------------------------
    */
    public function logs()
    {
        $logs = UserActivityLog::with('user')->latest()->paginate(20);
        return view('users.logs', compact('logs'));
    }

    /*
    |--------------------------------------------------------------------------
    | LOG HELPER
    |--------------------------------------------------------------------------
    */
    private function logActivity(Request $request, string $activity): void
    {
        UserActivityLog::create([
            'user_id'   => auth()->id(),
            'activity'  => $activity,
            'ip_address'=> $request->ip(),
        ]);
    }
}