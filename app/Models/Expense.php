<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Branch;
use App\Models\ChickPurchase; // Added ChickPurchase model
use App\Models\Feed; // Added Feed model
use App\Models\Medicine; // Added Medicine model
use App\Models\Equipment; // Added Equipment model

class ExpenseController extends Controller
{
    public function index()
    {
        // Load all related models (ChickPurchase, Feed, Medicine, Equipment, Branch) for efficient fetching
        $expenses = Expense::with(['branch', 'chickPurchase', 'feed', 'medicine', 'equipment'])->get();
        return view('expenses.index', compact('expenses'));
    }

    public function create()
    {
        // Get all branches, chick purchases, feeds, medicines, and equipment to populate select options in the form
        $branches = Branch::all();
        $chickPurchases = ChickPurchase::all();
        $feeds = Feed::all();
        $medicines = Medicine::all();
        $equipments = Equipment::all();
        return view('expenses.create', compact('branches', 'chickPurchases', 'feeds', 'medicines', 'equipments'));
    }

    public function store(Request $request)
    {
        // Validate incoming request data, ensuring that all fields are properly validated
        $validated = $request->validate([
            'category'      => 'required|string',
            'amount'        => 'required|numeric',
            'expense_date'  => 'required|date',
            'expense_type'  => 'required|string',
            'branch_id'     => 'required|exists:branches,id',
            'chick_purchase_id' => 'nullable|exists:chick_purchases,id',  // Ensure chick purchase ID is optional
            'feed_id'       => 'nullable|exists:feeds,id',
            'medicine_id'   => 'nullable|exists:medicines,id',
            'equipment_id'  => 'nullable|exists:equipment,id',
        ]);

        // Create new Expense record with validated data
        Expense::create($validated);
        return redirect()->route('expenses.index')->with('success', 'Expense created successfully.');
    }

    public function show(Expense $expense)
    {
        // Eager load the related models to show full data
        $expense->load(['branch', 'chickPurchase', 'feed', 'medicine', 'equipment']);
        return view('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        // Get all necessary related data for editing an expense
        $branches = Branch::all();
        $chickPurchases = ChickPurchase::all();
        $feeds = Feed::all();
        $medicines = Medicine::all();
        $equipments = Equipment::all();
        return view('expenses.edit', compact('expense', 'branches', 'chickPurchases', 'feeds', 'medicines', 'equipments'));
    }

    public function update(Request $request, Expense $expense)
    {
        // Validate incoming request data for updating an expense
        $validated = $request->validate([
            'category'      => 'required|string',
            'amount'        => 'required|numeric',
            'expense_date'  => 'required|date',
            'expense_type'  => 'required|string',
            'branch_id'     => 'required|exists:branches,id',
            'chick_purchase_id' => 'nullable|exists:chick_purchases,id',
            'feed_id'       => 'nullable|exists:feeds,id',
            'medicine_id'   => 'nullable|exists:medicines,id',
            'equipment_id'  => 'nullable|exists:equipment,id',
        ]);

        // Update the expense with the validated data
        $expense->update($validated);
        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        // Delete the expense and redirect with a success message
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }
}
