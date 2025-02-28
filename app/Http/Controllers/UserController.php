<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Exception;

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
            'role'      => 'nullable',
        ]);

        // Hash the password
        $validated['password'] = Hash::make($validated['password']);

        // Create the user
        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => $validated['password'],
            'branch_id' => $validated['branch_id'],
        ]);

        if ($validated['role']) {
            $user->syncRoles($validated['role']);
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


        return redirect()->back();
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
        
        try{

            \Log::info($request->all());
            
            $validated = $request->validate([
                'name'      => 'required|string|max:255',
                'email'     => 'required|email|unique:users,email,'.$user->id,
                'role'      => 'nullable',
                'old_role'  => 'nullable',
                'branch_id' => 'nullable|exists:branches,id',
            ]);
    
            
            // check if the user role has changed
            if ($validated['role'] !== $validated['old_role']) {
                
                // If the user is a manager, create a Manager record
                if ($validated['role'] === 'manager' && $validated['branch_id']) {
                    // assign the manager role to the user
                    $user->syncRoles('manager');
                    // change the manager_id in the branch table
                    Branch::where('id', $validated['branch_id'])->update(['manager_id' => $user->id]);
                } else if($validated['old_role'] === 'manager' && $validated['role'] !== 'manager' && $validated['role'] !== NULL) {
                    //assign the new role to the user
                    $user->syncRoles($validated['role']);
                    // change the manager_id in the branch table
                    Branch::where('manager_id', $user->id)->update(['manager_id' => null]);
                }elseif($validated['role'] == NULL && $validated['old_role'] != NULL){
                    //assign the old role to the user
                    $user->syncRoles($validated['old_role']);
                }else{
                    //assign the new role to the user
                    $user->syncRoles($validated['role']);
                }
            }
    
                $user->name = $validated['name'];
                $user->email = $validated['email'];
                $user->branch_id = $validated['branch_id'];
                $user->save();
            
    
    
    
            return redirect()->route('users.index')->with('success', 'User updated successfully.');
        }catch(Exception $e){
            \Log::info($e->getMessage());
            return redirect()->route('users.index')->with('error', 'Error updating');
        }
        
    }

    public function destroy(User $user)
    {

        // Delete the user
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
