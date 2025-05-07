<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // Show all products with categories filtering (user view)
    public function index(Request $request)
    {
        $categoryFilter = $request->input('category');

        $categories = Category::all();

        if ($categoryFilter && $categoryFilter !== 'all') {
            $products = Product::where('category_id', $categoryFilter)->get();
        } else {
            $products = Product::all();
        }

        return view('user_view.home', [
            'categories' => $categories,
            'products' => $products,
        ]);
    }

    // Show a single product (user view)
    public function show($id)
    {
        $product = Product::with('store')->findOrFail($id);
        return view('user_view.product', compact('product'));
    }

    // Live search (user view)
    public function liveSearch(Request $request)
    {
        $query = $request->input('query');
    
        $products = Product::with(['store.merchant'])
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

    // Show product creation form (merchant view)
    public function create()
    {
        $store = Auth::user()->merchant->store;
        $categories = Category::all();
        
        if (!$store) {
            return redirect()->route('store.create')->with('error', 'You need to create a store first');
        }

        // return view('products.create', compact('store', 'categories'));
        return view('merchant_view.products.create', compact('store', 'categories'));
    }

    // Store new product (merchant view)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'images' => 'required|array|min:1',
            'images.*' => 'image|max:2048',
        ]);

        $store = Auth::user()->merchant->store;

        $imagePaths = [];
        foreach ($request->file('images') as $image) {
            $imagePaths[] = $image->store('product_images', 'public');
        }

        $productData = [
            'store_id' => $store->id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'images' => $imagePaths,
            'sold_amount' => 0,
            'is_discounted' => $request->boolean('is_discounted'),
            'discount_percentage' => $request->is_discounted ? $request->discount_percentage : null,
            'is_featured' => $request->boolean('is_featured'),
            'rating' => 0,
            'review_count' => 0,
        ];

        $product = Product::create($productData);

        return redirect()->route('merchant.dashboard')->with('success', 'Product created successfully');
    }

    // Show product edit form (merchant view)
    public function edit($id)
    {
        $product = Product::whereHas('store', function($query) {
            $query->where('merchant_id', Auth::user()->merchant->id);
        })->findOrFail($id);

        $categories = Category::all();

        return view('products.edit', compact('product', 'categories'));
    }

    // Update product (merchant view)
    public function update(Request $request, $id)
    {
        $product = Product::whereHas('store', function($query) {
            $query->where('merchant_id', Auth::user()->merchant->id);
        })->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'images' => 'sometimes|array',
            'images.*' => 'image|max:2048',
        ]);

        $data = $request->only(['name', 'description', 'price', 'quantity', 'category_id']);

        if ($request->hasFile('images')) {
            // Delete old images
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image);
            }
            
            // Store new images
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('product_images', 'public');
            }
            $data['images'] = $imagePaths;
        }

        $product->update($data);

        return redirect()->route('merchant.dashboard')->with('success', 'Product updated successfully');
    }

    // Delete product (merchant view)
    public function destroy($id)
    {
        $product = Product::whereHas('store', function($query) {
            $query->where('merchant_id', Auth::user()->merchant->id);
        })->findOrFail($id);

        // Delete associated images
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image);
        }

        $product->delete();

        return redirect()->route('merchant.dashboard')->with('success', 'Product deleted successfully');
    }
}