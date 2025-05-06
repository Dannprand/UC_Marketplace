<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Store;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StoreController extends Controller
{
    // Show store creation form
    public function create()
    {
        if (Auth::user()->merchant->store) {
            return redirect()->route('merchant.dashboard');
        }
        
        $categories = Category::all();
        return view('store.create', compact('categories'));
    }

    // Store new store
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'logo' => 'required|image|max:2048',
            'banner' => 'nullable|image|max:4096',
        ]);

        // Handle file uploads
        $logoPath = $request->file('logo')->store('store_logos', 'public');
        $bannerPath = $request->hasFile('banner') 
            ? $request->file('banner')->store('store_banners', 'public') 
            : null;

        // Create store
        $store = Store::create([
            'merchant_id' => Auth::user()->merchant->id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'logo' => $logoPath,
            'banner' => $bannerPath,
            'is_published' => true,
        ]);

        return redirect()->route('merchant.dashboard');
    }
}