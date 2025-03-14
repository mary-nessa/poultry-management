<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return redirect('/');
    }

    public function login(Request $request)
    {
        
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        //more meaningful error message for username
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->filled('remember'))) {
            return back()->withErrors(['email' => 'Invalid email or password.']);
        }

        //check the user role and redirect to the appropriate dashboard
        if (Auth::user()->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }elseif (Auth::user()->hasRole('manager')) {
            //check if the user has a branch assigned
            if (Auth::user()->branch_id == null) {
                Auth::logout();
                return back()->withErrors(['error' => 'You do not have permission to access this page.']);
            }
            return redirect()->route('manager.dashboard');
        }elseif (Auth::user()->hasRole('salesmanager')) {
            //check if the user has a branch assigned
            if (Auth::user()->branch_id == null) {
                Auth::logout();
                return back()->withErrors(['error' => 'You do not have permission to access this page.']);
            }
            return redirect()->route('salesmanager.dashboard');

        }elseif (Auth::user()->hasRole('worker')) {
            //check if the user has a branch assigned
            if (Auth::user()->branch_id == null) {
                Auth::logout();
                return back()->withErrors(['error' => 'You do not have permission to access this page.']);
            }
            return redirect()->route('worker.dashboard');
        }else{
            Auth::logout();
            return redirect('/')->with('error', 'You do not have permission to access this page');
        }
    
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
