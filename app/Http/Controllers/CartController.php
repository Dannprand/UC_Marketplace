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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // Helper function untuk mendapatkan harga produk yang benar (termasuk diskon)
    private function getProductPrice(Product $product)
    {
        return $product->discounted_price ?? $product->price;
    }

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
            $price = $this->getProductPrice($item->product);
            $totalPrice += $price * $item->quantity;
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

    // Persiapan ke payment
    public function payment(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $orderSuccess = session('order_success', false);
        $orderNumber = session('order_number');

        session()->forget(['order_success', 'order_number']);

        $cart = Cart::with('items.product.store')->where('user_id', $user->id)->first();
        $selectedItemIds = $request->query('selected_items', []);

        // Konversi ke array integer
        if (!is_array($selectedItemIds)) {
            $selectedItemIds = explode(',', $selectedItemIds);
        }
        $selectedItemIds = array_map('intval', $selectedItemIds);
        $selectedItemIds = array_filter($selectedItemIds);

        // Validasi jika cart kosong
        if (!$orderSuccess && (!$cart || $cart->items->isEmpty())) {
            return redirect()->route('cart')->with('error', 'Keranjang belanja kosong!');
        }

        // Filter item yang dipilih
        $items = collect();
        if ($cart) {
            $items = $cart->items->filter(function ($item) use ($selectedItemIds) {
                return in_array($item->id, $selectedItemIds);
            });
        }

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
        $qrCodeData = '';
        
        $totalPrice = $items->sum(function($item) {
            $price = $this->getProductPrice($item->product);
            return $price * $item->quantity;
        });

        // Pastikan storeId ada sebelum diproses
        if ($storeIds->isNotEmpty()) {
            $storeId = $storeIds->first();
            $store = Store::with('merchant')->find($storeId);

            if ($store && $store->merchant) {
                $merchant = $store->merchant;

                // Format data untuk QR code
                $qrCodeContent = json_encode([
                    'bank' => $merchant->bank_name,
                    'account_number' => $merchant->account_number,
                    'account_name' => $merchant->merchant_name,
                    'amount' => number_format($totalPrice, 0, '', '')
                ]);

                try {
                    $qrCodeData = base64_encode(QrCode::format('png')->size(220)->generate($qrCodeContent));
                } catch (\Exception $e) {
                    Log::error("QR Code Generation Error: " . $e->getMessage());
                    $qrCodeData = '';
                }
            }
        }

        $addresses = $user->addresses()->get();
        $paymentMethods = collect();
        
        return view('user_view.payment', [
            'cart' => $cart,
            'items' => $items,
            'totalPrice' => $totalPrice,
            'addresses' => $addresses,
            'paymentMethods' => $paymentMethods,
            'orderSuccess' => $orderSuccess,
            'orderNumber' => $orderNumber,
            'selectedItemIds' => $selectedItemIds,
            'merchant' => $merchant,
            'qrCodeData' => $qrCodeData,
        ]);
    }

    // Proses checkout produk
    public function processCheckout(Request $request)
{
    $user = Auth::user();
    if (!$user) {
        return redirect()->route('login');
    }

    // Validasi
    $request->validate([
        'shipping_address_id' => 'required|exists:addresses,id',
        'selected_items' => 'required|array',
        'selected_items.*' => 'integer|exists:cart_items,id',
        'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:5120', // Max 5MB
    ]);

    DB::beginTransaction();

    try {
        $cart = Cart::with('items.product')->where('user_id', $user->id)->first();
        
        if (!$cart) {
            throw new \Exception('Cart not found');
        }

        $selectedItems = $cart->items->whereIn('id', $request->selected_items);

        if ($selectedItems->isEmpty()) {
            throw new \Exception('No items selected for checkout');
        }

        $storeIds = $selectedItems->pluck('product.store_id')->unique();
        if ($storeIds->count() > 1) {
            throw new \Exception('Only one store allowed per checkout');
        }

        $storeId = $storeIds->first();
        $store = Store::with('merchant')->find($storeId);

        if (!$store || !$store->merchant) {
            throw new \Exception('Merchant data not found');
        }

        $merchant = $store->merchant;
        $userId = auth()->id();
        
        // Simpan file bukti pembayaran
        $proofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
        
        $paymentMethod = PaymentMethod::create([
            'user_id' => $userId,
            'type' => 'bank_transfer',
            'provider' => $merchant->bank_name,
            'account_name' => $merchant->merchant_name,
            'account_number' => $merchant->account_number,
            'is_default' => false,
        ]);

        $totalAmount = $selectedItems->sum(function ($item) {
            $price = $this->getProductPrice($item->product);
            return $price * $item->quantity;
        });

        // Buat order baru
        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => 'INV-'.strtoupper(Str::random(8)),
            'status' => 'pending',
            'total_amount' => $totalAmount,
            'shipping_address_id' => $request->shipping_address_id,
            'payment_method_id' => $paymentMethod->id,
            'store_id' => $storeId,
            'payment_proof' => $proofPath, // Simpan path file
        ]);

        // Tambahkan item ke order
        foreach ($selectedItems as $cartItem) {
            $price = $this->getProductPrice($cartItem->product);
            
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'unit_price' => $price,
                'total_price' => $price * $cartItem->quantity,
            ]);

            // Update sold_amount produk
            $product = $cartItem->product;
            $product->sold_amount += $cartItem->quantity;
            $product->save();
        }

        // Hapus item dari cart
        CartItem::whereIn('id', $request->selected_items)->delete();

        DB::commit();

        return response()->json([
            'success' => true,
            'redirect' => route('orders.index'),
            'message' => 'Order created successfully'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Checkout Error: '.$e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Checkout failed: '.$e->getMessage()
        ], 500);
    }
}
}