<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExpenseLimit;
use App\Models\Branch;

class ExpenseLimitController extends Controller
{
    public function index()
    {
        $limits = ExpenseLimit::with('branch')->get();
        return view('expense_limits.index', compact('limits'));
    }

    public function create()
    {
        $branches = Branch::all();
        return view('expense_limits.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id'    => 'required|exists:branches,id',
            'category'     => 'required|string',
            'limit_amount' => 'required|numeric',
        ]);

        ExpenseLimit::create($validated);
        return redirect()->route('expense_limits.index')->with('success', 'Expense Limit set successfully.');
    }

    public function show(ExpenseLimit $expenseLimit)
    {
        $expenseLimit->load('branch');
        return view('expense_limits.show', compact('expenseLimit'));
    }

    public function edit(ExpenseLimit $expenseLimit)
    {
        $branches = Branch::all();
        return view('expense_limits.edit', compact('expenseLimit', 'branches'));
    }

    public function update(Request $request, ExpenseLimit $expenseLimit)
    {
        $validated = $request->validate([
            'branch_id'    => 'required|exists:branches,id',
            'category'     => 'required|string',
            'limit_amount' => 'required|numeric',
        ]);

        $expenseLimit->update($validated);
        return redirect()->route('expense_limits.index')->with('success', 'Expense Limit updated successfully.');
    }

    public function destroy(ExpenseLimit $expenseLimit)
    {
        $expenseLimit->delete();
        return redirect()->route('expense_limits.index')->with('success', 'Expense Limit deleted successfully.');
    }
}
