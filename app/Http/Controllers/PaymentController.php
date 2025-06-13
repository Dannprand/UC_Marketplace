<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\CartItem;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Store;
use App\Models\Merchant;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function showPayment(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $selectedItemIds = $request->input('selected_items', []);
        $cart = Cart::with('items.product.store.merchant')
            ->where('user_id', $user->id)
            ->first();

        // Filter item yang dipilih
        $items = $cart ? $cart->items->filter(function ($item) use ($selectedItemIds) {
            return in_array($item->id, $selectedItemIds);
        }) : collect();

        if ($items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Item yang dipilih tidak valid atau keranjang kosong.');
        }

        $storeIds = $items->pluck('product.store_id')->unique();
        if ($storeIds->count() > 1) {
            return redirect()->route('cart')->with('error', 'Anda hanya dapat melakukan checkout untuk satu toko saja.');
        }

        // Cek jika user adalah merchant dan mencoba membeli dari toko sendiri
        if ($user->merchant && $user->merchant->store) {
            $ownStoreId = $user->merchant->store->id;
            if ($storeIds->contains($ownStoreId)) {
                return redirect()->route('cart')->with('error', 'Anda tidak dapat membeli produk dari toko milik Anda sendiri.');
            }
        }

        $merchant = null;
        $totalPrice = $items->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        if ($storeIds->isNotEmpty()) {
            $storeId = $storeIds->first();
            $store = Store::with('merchant')->find($storeId);

            if ($store && $store->merchant) {
                $merchant = $store->merchant;
            }
        }

        $addresses = $user->addresses()->get();
        
        return view('user_view.payment', [
            'cart' => $cart,
            'items' => $items,
            'totalPrice' => $totalPrice,
            'addresses' => $addresses,
            'selectedItemIds' => $selectedItemIds,
            'merchant' => $merchant,
        ]);
    }

    public function processCheckout(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'shipping_address_id' => 'required|exists:addresses,id',
            'selected_items' => 'required|array',
            'selected_items.*' => 'exists:cart_items,id',
        ]);

        $selectedItemIds = $validated['selected_items'];
        $cart = Cart::with('items.product')
            ->where('user_id', $user->id)
            ->first();

        if (!$cart) {
            return redirect()->route('cart')->with('error', 'Keranjang tidak ditemukan.');
        }

        $items = $cart->items->filter(function ($item) use ($selectedItemIds) {
            return in_array($item->id, $selectedItemIds);
        });

        if ($items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Tidak ada item yang dipilih untuk checkout.');
        }

        $totalPrice = $items->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        $storeIds = $items->pluck('product.store_id')->unique();
        if ($storeIds->count() > 1) {
            return redirect()->route('cart')->with('error', 'Anda hanya dapat melakukan checkout untuk satu toko saja.');
        }

        $storeId = $storeIds->first();
        $store = Store::find($storeId);
        if (!$store) {
            return redirect()->route('cart')->with('error', 'Toko tidak ditemukan.');
        }

        DB::beginTransaction();
        try {
            // Buat order
            $order = new Order();
            $order->user_id = $user->id;
            $order->store_id = $storeId;
            $order->shipping_address_id = $validated['shipping_address_id'];
            $order->total_amount = $totalPrice;
            $order->order_number = 'ORD' . time() . Str::random(5);
            
            // Handle payment proof upload
            if ($request->hasFile('payment_proof')) {
                $path = $request->file('payment_proof')->store('payment_proofs', 'public');
                $order->payment_proof = $path;
                $order->status = 'pending_verification';
            } else {
                $order->status = 'payment_pending';
                $order->expired_at = now()->addHours(24); // Timer 24 jam
            }
            
            $order->save();

            // Buat order items
            foreach ($items as $cartItem) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $cartItem->product_id;
                $orderItem->quantity = $cartItem->quantity;
                $orderItem->unit_price = $cartItem->product->price;
                $orderItem->total_price = $cartItem->product->price * $cartItem->quantity;
                $orderItem->save();

                // Kurangi stok produk
                $product = Product::find($cartItem->product_id);
                $product->stock -= $cartItem->quantity;
                $product->save();
            }

            // Hapus item dari cart
            CartItem::whereIn('id', $selectedItemIds)->delete();

            DB::commit();

            return redirect()->route('orders.index')->with('success', 'Order berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout error: ' . $e->getMessage());
            return back()->with('error', 'Gagal memproses checkout: ' . $e->getMessage());
        }
    }
}