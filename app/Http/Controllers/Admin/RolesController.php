<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users')->with('permissions')->get();
        $permissions = Permission::all()->groupBy('module');
        $users = User::with('roles')->latest()->paginate(15);

        return view('admin.roles.index', compact('roles', 'permissions', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:roles,name',
            'display_name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create([
            'name' => strtolower(trim($validated['name'])),
            'display_name' => $validated['display_name'],
            'description' => $validated['description'],
        ]);

        if (!empty($validated['permissions'])) {
            $role->permissions()->sync($validated['permissions']);
        }

        return back()->with('success', "Rol '{$role->display_name}' creado correctamente.");
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $validated = $request->validate([
            'display_name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update([
            'display_name' => $validated['display_name'],
            'description' => $validated['description'],
        ]);

        $role->permissions()->sync($validated['permissions'] ?? []);

        return back()->with('success', "Rol '{$role->display_name}' actualizado.");
    }

    public function assignUserRole(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $user = User::findOrFail($validated['user_id']);
        $user->roles()->sync($validated['roles']);

        return back()->with('success', "Roles del usuario {$user->name} actualizados.");
    }
}
