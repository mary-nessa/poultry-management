<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Branch;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('branch')->paginate(5);
        $branches = Branch::all();
        return view('products.index', compact('products', 'branches'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_type' => 'required|string|unique:products',
            'breed' => 'required|string',
            'unit_measure' => 'required|string',
            'default_price' => 'required|numeric|min:0',
            'branch_id' => 'required|exists:branches,id'
        ]);

        Product::create($validated);
        return redirect()->route('products.index')->with('success', 'Product created successfully.');
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
        $validated = $request->validate([
            'product_type' => 'required|string|unique:products,product_type,'.$product->id,
            'breed' => 'required|string',
            'unit_measure' => 'required|string',
            'default_price' => 'required|numeric|min:0',
            'branch_id' => 'required|exists:branches,id'
        ]);

        $product->update($validated);
        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        // Check if the product has any associated sale items
        if ($product->saleItems()->exists()) {
            return back()->with('error', 'Cannot delete product with associated sales.');
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
