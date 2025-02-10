<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Branch;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with(['branch', 'bird', 'feed', 'medicine', 'equipment'])->get();
        return view('expenses.index', compact('expenses'));
    }

    public function create()
    {
        $branches = Branch::all();
        return view('expenses.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category'      => 'required|string',
            'amount'        => 'required|numeric',
            'expense_date'  => 'required|date',
            'expense_type'  => 'required|string',
            'branch_id'     => 'required|exists:branches,id',
            'bird_id'       => 'nullable|exists:birds,id',
            'feed_id'       => 'nullable|exists:feeds,id',
            'medicine_id'   => 'nullable|exists:medicines,id',
            'equipment_id'  => 'nullable|exists:equipment,id',
        ]);

        Expense::create($validated);
        return redirect()->route('expenses.index')->with('success', 'Expense created successfully.');
    }

    public function show(Expense $expense)
    {
        $expense->load(['branch', 'bird', 'feed', 'medicine', 'equipment']);
        return view('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        $branches = Branch::all();
        return view('expenses.edit', compact('expense', 'branches'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'category'      => 'required|string',
            'amount'        => 'required|numeric',
            'expense_date'  => 'required|date',
            'expense_type'  => 'required|string',
            'branch_id'     => 'required|exists:branches,id',
            'bird_id'       => 'nullable|exists:birds,id',
            'feed_id'       => 'nullable|exists:feeds,id',
            'medicine_id'   => 'nullable|exists:medicines,id',
            'equipment_id'  => 'nullable|exists:equipment,id',
        ]);

        $expense->update($validated);
        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }
}
