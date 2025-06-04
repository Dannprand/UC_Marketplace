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
}