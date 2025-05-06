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
        $product = Product::findOrFail($id);
        return view('user_view.product', compact('product'));
    }


}
