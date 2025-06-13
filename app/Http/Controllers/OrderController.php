<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
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

    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Perbaikan query untuk mengambil order dengan item yang relevan
        $orders = Order::with(['items.product.store.merchant', 'shippingAddress', 'paymentMethod', 'items.product.reviews'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user_view.order', compact('orders'));
    }
    
    public function completePayment(Order $order, Request $request)
    {
        $request->validate([
            'payment_proof' => 'required|image|max:5120' // 5MB
        ]);
        
        if ($order->status !== 'payment_pending') {
            return response()->json([
                'success' => false,
                'message' => 'Order is not in payment pending status.'
            ], 400);
        }
        
        // Simpan bukti pembayaran
        $path = $request->file('payment_proof')->store('payment_proofs', 'public');
        $order->payment_proof = $path;
        $order->status = 'pending_verification';
        $order->expired_at = null; // Hapus timer
        $order->save();
        
        return response()->json(['success' => true]);
    }
    
    public function verifyPayment(Request $request, Order $order)
    {
        $request->validate([
            'action' => 'required|in:verify,reject'
        ]);
        
        if ($order->status !== 'pending_verification') {
            return response()->json([
                'success' => false,
                'message' => 'Order is not in pending verification status.'
            ], 400);
        }
        
        if ($request->action === 'verify') {
            $order->status = 'processing';
            $order->save();
        } else {
            $order->status = 'cancelled';
            $order->save();
            
            // Kembalikan stok produk
            foreach ($order->items as $item) {
                $product = $item->product;
                $product->stock += $item->quantity;
                $product->save();
            }
        }
        
        return response()->json(['success' => true]);
    }
}