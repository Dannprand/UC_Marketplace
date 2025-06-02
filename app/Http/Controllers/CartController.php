<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Merchant;
use App\Models\PaymentMethod;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // Menampilkan halaman keranjang
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Get the cart with related items and products
        $cart = Cart::with('items.product')->where('user_id', $user->id)->first();

        if (!$cart) {
            $cart = Cart::create(['user_id' => $user->id]);
        }

        // Hitung total harga dan jumlah item
        $totalPrice = 0;
        $totalItems = 0;

        foreach ($cart->items as $item) {
            $totalPrice += $item->product->price * $item->quantity;
            $totalItems += $item->quantity;
        }

        return view('user_view.cart', compact('cart', 'totalPrice', 'totalItems'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $user = Auth::user();

        // Ambil cart milik user
        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart) {
            return redirect()->back()->with('error', 'Cart not found.');
        }

        // Cari CartItem yang punya cart_id sesuai dan id item yang diminta
        $cartItem = CartItem::where('id', $id)
            ->where('cart_id', $cart->id)
            ->first();

        if (!$cartItem) {
            return redirect()->back()->with('error', 'Cart item not found.');
        }

        // Update quantity
        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return redirect()->back()->with('success', 'Cart updated successfully.');
    }

    // Hapus item dari cart
    public function remove(Request $request, $id)
    {
        $user = Auth::user();

        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart) {
            return redirect()->back()->with('error', 'Cart not found.');
        }

        $cartItem = CartItem::where('id', $id)
            ->where('cart_id', $cart->id)
            ->first();

        if (!$cartItem) {
            return redirect()->back()->with('error', 'Cart item not found.');
        }

        $cartItem->delete();

        return redirect()->back()->with('success', 'Item removed from cart.');
    }

    // Menambahkan produk ke dalam keranjang
    public function addToCart(Request $request, $productId)
    {
        // Validate the quantity input
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Find the product by ID
        $product = Product::findOrFail($productId);

        // Get the quantity from the form
        $quantity = $request->input('quantity', 1);

        // Get or create cart for user
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        // Check if this product already exists in user's cart
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            // Product already in cart → update quantity
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            // New product → add to cart items table
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }

        return redirect()->route('cart')->with('success', 'Product added to cart successfully!');
    }

    public function buyNow(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $quantity = (int) $request->quantity;

        return view('user_view.payment', compact('product', 'quantity'));
    }

    // Persiapan ke payment
    public function payment(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $cart = Cart::with(['items.product.store.merchant'])->where('user_id', $user->id)->first();
        if (!$cart) {
            return redirect()->route('cart')->with('error', 'Keranjang belanja kosong.');
        }

        // Ambil item terpilih dari session (hasil dari processCheckout)
        $selectedItemIds = $request->input('selected_items', []);
        $selectedItems = collect();

        if (!empty($selectedItemIds)) {
            $selectedItems = $cart->items->whereIn('id', $selectedItemIds);
        }

        $totalPrice = $selectedItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        $merchant = null;
        if ($selectedItems->isNotEmpty()) {
            $merchant = $selectedItems->first()->product->store->merchant ?? null;
        }

        // Alamat pengiriman
        $shippingAddresses = $user->addresses;
        $defaultAddress = $shippingAddresses->where('is_primary', true)->first();

        // QR dan data konfirmasi dari session
        $qrCodeData = session('qr_code'); // base64 string
        $orderSuccess = session('order_success', false);
        $orderNumber = session('order_number');

        // Debug output, hentikan eksekusi dan tampilkan data
        // dd([
        //     'user' => $user,
        //     'cart' => $cart,
        //     'selectedItemIds' => $selectedItemIds,
        //     'selectedItems' => $selectedItems,
        //     'totalPrice' => $totalPrice,
        //     'merchant' => $merchant,
        //     'shippingAddresses' => $shippingAddresses,
        //     'defaultAddress' => $defaultAddress,
        //     'qrCodeData' => $qrCodeData,
        //     'orderSuccess' => $orderSuccess,
        //     'orderNumber' => $orderNumber,
        // ]);

        return view('user_view.payment', [
            'cart' => $cart,
            'selectedItems' => $selectedItems,
            'shippingAddresses' => $shippingAddresses,
            'defaultAddress' => $defaultAddress,
            'qrCodeData' => $qrCodeData,
            'orderSuccess' => $orderSuccess,
            'orderNumber' => $orderNumber,
            'merchant' => $merchant,
            'totalPrice' => $totalPrice,
            'items' => $selectedItems,
            'selectedItemIds' => $selectedItemIds,
        ]);
    }

    public function processCheckout(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $request->validate([
            'shipping_address_id' => 'required|exists:addresses,id',
            'selected_items' => 'required|array',
            'selected_items.*' => 'integer|exists:cart_items,id'
        ]);

        DB::beginTransaction();

        try {
            $cart = Cart::with('items.product')->where('user_id', $user->id)->first();
            if (!$cart) throw new \Exception('Cart not found');

            $selectedItems = CartItem::with('product')
                ->where('cart_id', $cart->id)
                ->whereIn('id', $request->selected_items)
                ->get();
            if ($selectedItems->isEmpty()) throw new \Exception('No items selected for checkout');

            $storeIds = $selectedItems->pluck('product.store_id')->unique();
            if ($storeIds->count() > 1) throw new \Exception('Only one store allowed per checkout');

            $storeId = $storeIds->first();
            $store = Store::with('merchant')->find($storeId);
            if (!$store || !$store->merchant) throw new \Exception('Merchant data not found');

            $merchant = $store->merchant;

            $paymentMethod = PaymentMethod::create([
                'user_id' => $user->id,
                'type' => 'bank_transfer',
                'provider' => $merchant->bank_name,
                'account_name' => $merchant->merchant_name,
                'account_number' => $merchant->account_number,
                'is_default' => false,
            ]);

            $totalAmount = $selectedItems->sum(fn($item) => $item->product->price * $item->quantity);

            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'INV-' . strtoupper(Str::random(8)),
                'status' => 'pending',
                'total_amount' => $totalAmount,
                'shipping_address_id' => $request->shipping_address_id,
                'payment_method_id' => $paymentMethod->id,
                'store_id' => $storeId,
            ]);
 Log::info('Order created:', $order->toArray());

            foreach ($selectedItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->product->price,
                    'total_price' => $cartItem->product->price * $cartItem->quantity,
                ]);
                Log::info('OrderItem created:', $orderItem->toArray());

                $product = $cartItem->product;
                $product->sold_amount += $cartItem->quantity;
                $product->save();
            }

            CartItem::whereIn('id', $request->selected_items)->delete();

            if (!$merchant->bank_name || !$merchant->account_number || !$merchant->merchant_name) {
                // Bisa lakukan log error atau set default pesan
                Log::warning('Merchant bank info incomplete for QR Code generation.');
                $qrCodeData = null; // Atau set ke gambar placeholder, atau langsung lewati generate qr
            } else {
                $qrContent = json_encode([
                    'bank' => $merchant->bank_name,
                    'account_number' => $merchant->account_number,
                    'account_name' => $merchant->merchant_name,
                    'amount' => number_format($totalAmount, 0, '', '')
                ]);

                $qrCodeData = base64_encode(QrCode::format('png')->size(220)->generate($qrContent));
                session()->put('qr_code', $qrCodeData);
            }

            // Generate QR Code
            $qrContent = json_encode([
                'bank' => $merchant->bank_name,
                'account_number' => $merchant->account_number,
                'account_name' => $merchant->merchant_name,
                'amount' => number_format($totalAmount, 0, '', '')
            ]);

            try {
                $qrCodeData = base64_encode(QrCode::format('png')->size(220)->generate($qrContent));
                session()->put('qr_code', $qrCodeData);
                // session()->forget('qr_code');
            } catch (\Exception $e) {
                Log::error("QR Code Error: " . $e->getMessage());
            }

            session()->put('order_success', true);
            session()->put('order_number', $order->order_number);
            session()->put('selected_item_ids', $request->selected_items);

            DB::commit();

            return redirect()->route('payment');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
