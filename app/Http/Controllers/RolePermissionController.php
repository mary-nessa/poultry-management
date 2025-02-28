<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    // Display roles and permissions dashboard
    public function index()
    {
        //get roles except admin
        $roles = Role::where('name', '!=', 'admin')->get();
        $permissions = Permission::all();
        return view('roles.index', compact('roles', 'permissions'));
    }

    public function splitView(Request $request)
    {
        $roles = Role::where('name', '!=', 'admin')->get();
        $permissions = Permission::all();
        $selectedRole = $request->has('role_id')
            ? Role::find($request->role_id)
            : null;

        return view('roles.split', compact('roles', 'permissions', 'selectedRole'));
    }


    // Create a new role
    public function store(Request $request)
    {
        //first convert role to lowercase
        $request['name'] = strtolower($request->name);
        
        $request->validate([
            'name' => 'required|unique:roles,name'
        ]);
        
        Role::create(['name' => $request->name]);

        return back()->with('success', 'Role created successfully.');
    }

    // Delete an existing role
    public function destroy(Role $role)
    {
        $role->delete();
        return back()->with('success', 'Role deleted successfully.');
    }

    // Assign or update permissions for a role
    public function assignPermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'nullable|array'
        ]);

        $role->syncPermissions($request->permissions ?? []);

        return back()->with('success', 'Permissions updated successfully.');
    }
}
