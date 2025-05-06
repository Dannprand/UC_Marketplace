<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['store', 'category'])->get();

        return view('user_view.home', compact('products'));
    }

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
