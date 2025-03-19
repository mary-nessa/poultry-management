<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Branch;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{public function index()
    {
        $user = Auth::user();

        // Check if the user is an admin (can see all branches)
        if ($user->hasRole('admin')) {
            $products = Product::with('branch')->paginate(5);
            $branches = Branch::all();
        } else {
            // Normal user sees only products from their assigned branch
            $products = Product::where('branch_id', $user->branch_id)->with('branch')->paginate(5);
            $branches = Branch::where('id', $user->branch_id)->get();
        }

        return view('products.index', compact('products', 'branches'));
    }

    public function create()
    {
        $branches = Branch::all();
        return view('products.create', compact('branches'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_type' => 'required|string|unique:products',
                'breed' => 'required|string',
                'unit_measure' => 'required|string',
                'default_price' => 'required|numeric|min:0',
                'branch_id' => 'required|exists:branches,id'
                // Removed 'quantity' field as it's not in your form
            ]);

            Product::create($validated);
            return redirect()->route('products.index')->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create product: ' . $e->getMessage()]);
        }
    }

    public function show(Product $product)
    {
        $product->load('branch');
        return response()->json($product);
    }

    public function edit(Product $product)
    {
        return response()->json($product);
    }

    public function update(Request $request, Product $product)
    {
        try {
            $validated = $request->validate([
                'product_type' => 'required|string|unique:products,product_type,' . $product->id,
                'breed' => 'required|string',
                'unit_measure' => 'required|string',
                'default_price' => 'required|numeric|min:0',
                'branch_id' => 'required|exists:branches,id'
                // Removed 'quantity' field as it's not in your form
            ]);

            $product->update($validated);
            return redirect()->route('products.index')->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update product: ' . $e->getMessage()]);
        }
    }

    public function destroy(Product $product)
    {
        try {
            // Check if the product has any associated sale items
            if ($product->saleItems()->exists()) {
                return back()->with('error', 'Cannot delete product with associated sales.');
            }

            $product->delete();
            return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete product: ' . $e->getMessage());
        }
    }
}