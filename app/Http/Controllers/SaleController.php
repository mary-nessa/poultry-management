<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Branch;
use App\Models\Buyer;
use App\Models\EggTray;
use App\Models\Product;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with(['branch', 'buyer', 'eggTray', 'product'])->get();
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $branches  = Branch::all();
        $buyers    = Buyer::all();
        $eggTrays  = EggTray::all();
        $products  = Product::all();
        return view('sales.create', compact('branches', 'buyers', 'eggTrays', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_type'    => 'required|string',
            'quantity'        => 'required|integer',
            'price_per_unit'  => 'required|numeric',
            'total_amount'    => 'required|numeric',
            'sale_date'       => 'required|date',
            'branch_id'       => 'required|exists:branches,id',
            'buyer_id'        => 'nullable|exists:buyers,id',
            'egg_tray_id'     => 'nullable|exists:egg_trays,id',
            'product_id'      => 'nullable|exists:products,id',
            'payment_method'  => 'required|string',
            'is_paid'         => 'required|boolean',
            'balance'         => 'nullable|numeric',
        ]);

        Sale::create($validated);
        return redirect()->route('sales.index')->with('success', 'Sale created successfully.');
    }

    public function show(Sale $sale)
    {
        $sale->load(['branch', 'buyer', 'eggTray', 'product']);
        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        $branches  = Branch::all();
        $buyers    = Buyer::all();
        $eggTrays  = EggTray::all();
        $products  = Product::all();
        return view('sales.edit', compact('sale', 'branches', 'buyers', 'eggTrays', 'products'));
    }

    public function update(Request $request, Sale $sale)
    {
        $validated = $request->validate([
            'product_type'    => 'required|string',
            'quantity'        => 'required|integer',
            'price_per_unit'  => 'required|numeric',
            'total_amount'    => 'required|numeric',
            'sale_date'       => 'required|date',
            'branch_id'       => 'required|exists:branches,id',
            'buyer_id'        => 'nullable|exists:buyers,id',
            'egg_tray_id'     => 'nullable|exists:egg_trays,id',
            'product_id'      => 'nullable|exists:products,id',
            'payment_method'  => 'required|string',
            'is_paid'         => 'required|boolean',
            'balance'         => 'nullable|numeric',
        ]);

        $sale->update($validated);
        return redirect()->route('sales.index')->with('success', 'Sale updated successfully.');
    }

    public function destroy(Sale $sale)
    {
        $sale->delete();
        return redirect()->route('sales.index')->with('success', 'Sale deleted successfully.');
    }
}
