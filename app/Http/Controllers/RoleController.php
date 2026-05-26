<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Menu;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->paginate(10);

        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        // Only show menus that have a permission key defined
        $menus = Menu::whereNotNull('permission')
            ->where('permission', '!=', '')
            ->orderBy('order_no')
            ->get();

        return view('roles.create', compact('menus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name'
        ]);

        $role = Role::create(['name' => $request->name]);

        $role->permissions()->sync(
            $this->resolvePermissionIds($request->permissions ?? [])
        );

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        $menus = Menu::whereNotNull('permission')
            ->where('permission', '!=', '')
            ->orderBy('order_no')
            ->get();

        return view('roles.create', compact('role', 'menus'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id
        ]);

        $role->update(['name' => $request->name]);

        $role->permissions()->sync(
            $this->resolvePermissionIds($request->permissions ?? [])
        );

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    /**
     * Given an array of permission name strings, find or create each
     * Permission record and return their IDs ready for sync().
     */
    private function resolvePermissionIds(array $names): array
    {
        // Strip blanks/nulls that can come from unchecked checkboxes
        $names = array_filter($names, fn($n) => !empty(trim((string) $n)));

        $ids = [];
        foreach ($names as $name) {
            $name = trim($name);
            $permission = Permission::firstOrCreate(['name' => $name]);
            $ids[] = $permission->id;
        }

        return $ids;
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return back()->with('success','Role deleted');
    }
}