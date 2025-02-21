<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        // Eager-load branch and dailyActivities relationships
        $users = User::with(['branch', 'roles'])->get();
        $roles = Role::all();
        $branches = Branch::all();
        return view('users.index', compact('users', 'branches', 'roles'));
    }

    public function create()
    {
        if (request()->ajax()) {
            $branches = Branch::all();
            return response()->json([
                'branches' => $branches
            ]);
        }
        return abort(404);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|string|min:6|confirmed',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        // Hash the password
        $validated['password'] = Hash::make($validated['password']);

        // Create the user
        $user = User::create($validated);

        // If the user is a manager, create a Manager record
        if ($user->role === 'MANAGER' && $validated['branch_id']) {
            Manager::create([
                'user_id'   => $user->id,
                'branch_id' => $validated['branch_id'],
            ]);
        }

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }
    public function assignRoles(Request $request)
    {

        $user = User::findOrFail($request->user_id);
        $roles = $request->roles;


        DB::transaction(function () use ($user, $roles) {
            $user->syncRoles($roles);
        });


        return response()->json(['status' => 'success']);
    }

    public function show(User $user)
    {
        return response()->json($user->load(['branch', 'roles']));

    }

    public function edit(User $user)
    {
        return response()->json($user->load(['branch', 'roles']));
    }

    public function update(Request $request, User $user)
    {

        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,'.$user->id,
            'role'      => 'required',
            'old_role'  => 'required',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        // check if the user role has changed
        if ($validated['role'] !== $validated['old_role']) {
            //if the old role was not null remove the old role
//            if ($validated['old_role'] !== null) {
//                $user->removeRole($validated['old_role']);
//            }
            // If the user is a manager, create a Manager record
            if ($validated['role'] === 'manager' && $validated['branch_id']) {
                // assign the manager role to the user
                $user->syncRoles('manager');
                // change the manager_id in the branch table
                Branch::where('id', $validated['branch_id'])->update(['manager_id' => $user->id]);
            } else if($validated['old_role'] === 'manager' && $validated['role'] !== 'manager') {
                //assign the new role to the user
                $user->syncRoles($validated['role']);
                // change the manager_id in the branch table
                Branch::where('manager_id', $user->id)->update(['manager_id' => null]);
            }
        }
        //check if the other user details have changed and make adjustments
        if ($validated['name'] !== $user->name || $validated['email'] !== $user->email) {
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->branch_id = $validated['branch_id'];
            $user->save();
        }



        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        // Delete associated manager record if exists
        Manager::where('user_id', $user->id)->delete();

        // Delete the user
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
