<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyActivity;
use App\Models\User;
use App\Models\Branch;
use App\Models\EggTray;

class DailyActivityController extends Controller
{
    public function index()
    {
        $activities = DailyActivity::with(['worker', 'branch', 'eggTray'])->get();
        return view('daily_activities.index', compact('activities'));
    }

    public function create()
    {
        $users    = User::all();
        $branches = Branch::all();
        $eggTrays = EggTray::all();
        return view('daily_activities.create', compact('users', 'branches', 'eggTrays'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'worker_id'           => 'required|exists:users,id',
            'branch_id'           => 'required|exists:branches,id',
            'activity_date'       => 'required|date',
            'feeding_notes'       => 'nullable|string',
            'health_notes'        => 'nullable|string',
            'egg_collection_count'=> 'nullable|integer',
            'egg_tray_id'         => 'nullable|exists:egg_trays,id',
            // Additional fields if necessary
        ]);

        DailyActivity::create($validated);
        return redirect()->route('daily_activities.index')->with('success', 'Daily Activity recorded successfully.');
    }

    public function show(DailyActivity $dailyActivity)
    {
        $dailyActivity->load(['worker', 'branch', 'eggTray']);
        return view('daily_activities.show', compact('dailyActivity'));
    }

    public function edit(DailyActivity $dailyActivity)
    {
        $users    = User::all();
        $branches = Branch::all();
        $eggTrays = EggTray::all();
        return view('daily_activities.edit', compact('dailyActivity', 'users', 'branches', 'eggTrays'));
    }

    public function update(Request $request, DailyActivity $dailyActivity)
    {
        $validated = $request->validate([
            'worker_id'           => 'required|exists:users,id',
            'branch_id'           => 'required|exists:branches,id',
            'activity_date'       => 'required|date',
            'feeding_notes'       => 'nullable|string',
            'health_notes'        => 'nullable|string',
            'egg_collection_count'=> 'nullable|integer',
            'egg_tray_id'         => 'nullable|exists:egg_trays,id',
        ]);

        $dailyActivity->update($validated);
        return redirect()->route('daily_activities.index')->with('success', 'Daily Activity updated successfully.');
    }

    public function destroy(DailyActivity $dailyActivity)
    {
        $dailyActivity->delete();
        return redirect()->route('daily_activities.index')->with('success', 'Daily Activity deleted successfully.');
    }
}
