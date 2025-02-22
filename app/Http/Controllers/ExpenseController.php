<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Branch;
use App\Models\ChickPurchase;
use App\Models\Feed;
use App\Models\Medicine;
use App\Models\Equipment;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with(['branch', 'chickPurchase', 'feed', 'medicine', 'equipment'])->get();
        $branches = Branch::all();
        $chickPurchases = ChickPurchase::all();
        $feeds = Feed::all();
        $medicines = Medicine::all();
        $equipments = Equipment::all();
        
        return view('expenses.index', compact('expenses', 'branches', 'chickPurchases', 'feeds', 'medicines', 'equipments'));
    }

    public function create()
    {
        $branches = Branch::all();
        return view('expenses.create', compact('branches'));
    }

    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'expense_date' => 'required|date',
            'expense_type' => 'required|in:RECURRING,TEMPORARY',
            'branch_id' => 'required|exists:branches,id',
            'chick_purchase_id' => 'nullable|exists:chick_purchases,id',
            'feed_id' => 'nullable|exists:feeds,id',
            'medicine_id' => 'nullable|exists:medicines,id',
            'equipment_id' => 'nullable|exists:equipments,id'
        ]);

        Expense::create($validated);
        return redirect()->route('expenses.index')->with('success', 'Expense created successfully.');
    }

    public function show(Expense $expense)
    {
        $expense->load(['branch', 'chickPurchase', 'feed', 'medicine', 'equipment']);
        return response()->json($expense);
    }

    public function edit(Expense $expense)
    {
        $expense->load(['branch', 'chickPurchase', 'feed', 'medicine', 'equipment']);
        return response()->json($expense);
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'expense_date' => 'required|date',
            'expense_type' => 'required|in:RECURRING,TEMPORARY',
            'branch_id' => 'required|exists:branches,id',
            'chick_purchase_id' => 'nullable|exists:chick_purchases,id',
            'feed_id' => 'nullable|exists:feeds,id',
            'medicine_id' => 'nullable|exists:medicines,id',
            'equipment_id' => 'nullable|exists:equipments,id'
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
