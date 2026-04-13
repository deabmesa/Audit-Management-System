<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        return view('roles.index', [
            'roles' => Role::with('permissions')->get(),
            'permissions' => Permission::all(),
            'users' => User::all(),
        ]);
    }

    public function create()
    {
        return view('roles.create', ['permissions' => Permission::all()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|unique:roles,name']);
        $role = Role::create($data);
        $role->syncPermissions($request->input('permissions', []));
        return redirect()->route('roles.index');
    }

    public function edit(Role $role)
    {
        return view('roles.edit', ['role' => $role, 'permissions' => Permission::all()]);
    }

    public function update(Request $request, Role $role)
    {
        $request->validate(['name' => 'required|string']);
        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->input('permissions', []));
        if ($request->filled('user_id')) {
            User::findOrFail($request->user_id)->syncRoles([$role->name]);
        }
        return redirect()->route('roles.index');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return back();
    }
}
