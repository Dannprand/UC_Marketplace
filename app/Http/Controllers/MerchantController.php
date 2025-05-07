<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class MerchantController extends Controller
{
    // Show merchant registration form
    public function showOpenForm()
    {
        // Check if user already has merchant account
        if (Auth::user()->merchant) {
            return redirect()->route('merchant.manage');
        }
        
        return view('openMerchant');
    }

    // Process merchant registration
    public function openMerchant(Request $request)
    {
        $request->validate([
            'merchant_name' => 'required|string|max:255',
            'description' => 'required|string',
            'merchant_password' => 'required|string|min:8|confirmed',
            'pfp' => 'nullable|image|max:2048',
        ]);

        // Handle file upload
        $pfpPath = null;
        if ($request->hasFile('pfp')) {
            $pfpPath = $request->file('pfp')->store('merchant_pfps', 'public');
        }

        // Create merchant
        $merchant = Merchant::create([
            'user_id' => Auth::id(),
            'merchant_name' => $request->merchant_name,
            'merchant_description' => $request->description,
            'merchant_pfp' => $pfpPath,
            'merchant_password' => Hash::make($request->merchant_password),
            'status' => 'active',
        ]);

        // Update user's merchant status
        // Auth::user()->update(['is_merchant' => true]);
        $user = Auth::user();
        $user->is_merchant = true;
        $user->save();

        return redirect()->route('store.create');
    }

    // Show merchant management login
    public function showManageForm()
    {
        if (!Auth::user()->merchant) {
            return redirect()->route('merchant.open');
        }
        
        return view('manageMerchant');
    }

    // Process merchant management login
    public function manageMerchant(Request $request)
    {
        $request->validate([
            'merchant_password' => 'required|string',
        ]);

        if (Hash::check($request->merchant_password, Auth::user()->merchant->merchant_password)) {
            return redirect()->route('merchant.dashboard');
        }

        return back()->withErrors(['merchant_password' => 'Incorrect merchant password']);
    }

    // Show merchant dashboard
    public function dashboard()
    {
        $merchant = Auth::user()->merchant;
        $store = $merchant->store;
        
        if (!$store) {
            return redirect()->route('store.create');
        }
        
        $products = $store->products()->with('category')->get();
        
        // return view('merchant', compact('merchant', 'store', 'products'));
        return view('merchant_view.merchant', compact('merchant', 'store', 'products'));
    }
}