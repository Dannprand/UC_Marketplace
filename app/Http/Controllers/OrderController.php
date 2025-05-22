<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Handle new address submission during checkout
    public function storeAddress(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'is_primary' => 'nullable|boolean',
        ]);

        // Set existing primary to false if this new one is primary
        if ($request->has('is_primary')) {
            Address::where('user_id', $user->id)->update(['is_primary' => false]);
        }

        Address::create([
            'user_id' => $user->id,
            'street' => $validated['street'],
            'city' => $validated['city'],
            'province' => $validated['province'],
            'postal_code' => $validated['postal_code'],
            'country' => $validated['country'],
            'is_primary' => $request->has('is_primary'),
        ]);

        return redirect()->route('checkout.payment')->with('success', 'Address added successfully.');
    }

    // Tampilkan daftar order user
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Ambil semua order user beserta relasi item, product, dan merchant/store
        $orders = $user->orders()
            ->with('items.product.store.merchant')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user_view.order', compact('orders'));
    }


}
