<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Branch;
use App\Models\Buyer;
use App\Models\Product;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with(['branch', 'buyer', 'items.product'])->get();
        $branches = Branch::all();
        $buyers = Buyer::all();
        $products = Product::all();
        return view('sales.index', compact('sales', 'branches', 'buyers', 'products'));
    }

    public function create()
    {
        $branches  = Branch::all();
        $buyers    = Buyer::all();
        $products  = Product::all();
        return view('sales.create', compact('branches', 'buyers', 'products'));
    }

    public function store(Request $request)
    {
        
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'branch_id' => 'required|exists:branches,id',
                'buyer_id' => 'nullable|exists:buyers,id',
                'payment_method' => 'required|in:CASH,CARD,MOBILE,CREDIT',
                'balance' => 'nullable|numeric|min:0',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.unit_price' => 'required|numeric|min:0'
            ]);

            if($request->is_paid == 'on'){
                $validated['is_paid'] = true;
            }else{
                $validated['is_paid'] = false;
            }
        

            // Create the sale
            $sale = Sale::create([
                'sale_date' => now(),
                'branch_id' => $validated['branch_id'],
                'buyer_id' => $validated['buyer_id'],
                'payment_method' => $validated['payment_method'],
                'is_paid' => $validated['is_paid'],
                'balance' => $validated['balance']
            ]);

            // Create sale items
            foreach ($validated['items'] as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_amount' => $item['quantity'] * $item['unit_price']
                ]);
            }

            DB::commit();
            return redirect()->route('sales.index')->with('success', 'Sale created successfully.');
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            DB::rollBack();
            return back()->with('error', 'Error creating sale: ' . $e->getMessage());
        }
    }

    public function show(Sale $sale)
    {
        $sale->load(['branch', 'buyer', 'items.product']);
        return response()->json($sale);
    }

    public function edit(Sale $sale)
    {
        $sale->load(['branch', 'buyer', 'items.product']);
        return response()->json($sale);
    }

    public function update(Request $request, Sale $sale)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'branch_id' => 'required|exists:branches,id',
                'buyer_id' => 'nullable|exists:buyers,id',
                'payment_method' => 'required|in:CASH,CARD,MOBILE,CREDIT',
                'is_paid' => 'required|boolean',
                'balance' => 'nullable|numeric|min:0',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.unit_price' => 'required|numeric|min:0'
            ]);

            // Update the sale
            $sale->update([
                'branch_id' => $validated['branch_id'],
                'buyer_id' => $validated['buyer_id'],
                'payment_method' => $validated['payment_method'],
                'is_paid' => $validated['is_paid'],
                'balance' => $validated['balance']
            ]);

            // Delete existing items
            $sale->items()->delete();

            // Create new sale items
            foreach ($validated['items'] as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_amount' => $item['quantity'] * $item['unit_price']
                ]);
            }

            DB::commit();
            return redirect()->route('sales.index')->with('success', 'Sale updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating sale: ' . $e->getMessage());
        }
    }

    public function destroy(Sale $sale)
    {
        try {
            DB::beginTransaction();
            
            // Delete all related items first
            $sale->items()->delete();
            
            // Delete the sale
            $sale->delete();
            
            DB::commit();
            return redirect()->route('sales.index')->with('success', 'Sale deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting sale: ' . $e->getMessage());
        }
    }
}
