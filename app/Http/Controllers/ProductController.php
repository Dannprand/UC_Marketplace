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
        $categories = Category::all(); // Get all categories
        $categorySlug = $request->input('category'); // Get the selected category slug from the URL

        // Fetch products, with optional filtering based on category
        if ($categorySlug) {
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                // Fetch products that belong to the selected category
                $products = Product::where('category_id', $category->id)->get();
            } else {
                // If the category doesn't exist, return all products
                $products = Product::all();
            }
        } else {
            // If no category filter is applied, show all products
            $products = Product::all();
        }

        // Return the view with products and categories
        return view('user_view.home', compact('products', 'categories'));
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
