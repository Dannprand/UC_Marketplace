<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;


class ProductController extends Controller
{
    // Show all products with categories filtering
    public function index(Request $request)
    {
        $categoryFilter = $request->input('category'); // 'category' from URL query

        $categories = Category::all();

        // Filter logic
        if ($categoryFilter && $categoryFilter !== 'all') {
            $products = Product::where('category_id', $categoryFilter)->get();
        } else {
            // Show ALL products when "No Filter" (which sends 'all' or is null)
            $products = Product::all();
        }

        return view('user_view.home', [
            'categories' => $categories,
            'products' => $products,
        ]);
    }

    // Show a single product
    public function show($id)
    {
        $product = Product::with('store')->findOrFail($id);
        return view('user_view.product', compact('product'));
    }

    public function liveSearch(Request $request)
    {
        $query = $request->input('query');
    
        $products = Product::with(['store.merchant']) // Eager load store and merchant
                    ->where('name', 'like', '%' . $query . '%')
                    ->orWhereHas('store', function ($q) use ($query) {
                        $q->where('name', 'like', '%' . $query . '%');
                    })
                    ->orWhereHas('store.merchant', function ($q) use ($query) {
                        $q->where('merchant_name', 'like', '%' . $query . '%');
                    })
                    ->select('id', 'name')
                    ->limit(5)
                    ->get();
    
        return response()->json($products);
    }    
}
