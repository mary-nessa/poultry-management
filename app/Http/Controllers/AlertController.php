<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alert;
use App\Models\Branch;
use App\Models\User;

class AlertController extends Controller
{
    public function index()
    {
        $alerts = Alert::with(['branch', 'user'])->get();
        return view('alerts.index', compact('alerts'));
    }

    public function create()
    {
        $branches = Branch::all();
        $users    = User::all();
        return view('alerts.create', compact('branches', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type'      => 'required|string',
            'message'   => 'required|string',
            'branch_id' => 'nullable|exists:branches,id',
            'user_id'   => 'nullable|exists:users,id',
            'resolved'  => 'nullable|boolean',
        ]);

        Alert::create($validated);
        return redirect()->route('alerts.index')->with('success', 'Alert created successfully.');
    }

    public function show(Alert $alert)
    {
        $alert->load(['branch', 'user']);
        return view('alerts.show', compact('alert'));
    }

    public function edit(Alert $alert)
    {
        $branches = Branch::all();
        $users    = User::all();
        return view('alerts.edit', compact('alert', 'branches', 'users'));
    }

    public function update(Request $request, Alert $alert)
    {
        $validated = $request->validate([
            'type'      => 'required|string',
            'message'   => 'required|string',
            'branch_id' => 'nullable|exists:branches,id',
            'user_id'   => 'nullable|exists:users,id',
            'resolved'  => 'nullable|boolean',
        ]);

        $alert->update($validated);
        return redirect()->route('alerts.index')->with('success', 'Alert updated successfully.');
    }

    public function destroy(Alert $alert)
    {
        $alert->delete();
        return redirect()->route('alerts.index')->with('success', 'Alert deleted successfully.');
    }
}
