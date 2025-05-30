<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\Store;
use App\Models\StoreAnalytic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


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

// persiapan ke payment
    public function payment(Request $request)
{
    $user = Auth::user();
    if (!$user) {
        return redirect()->route('login');
    }

    // Ambil order success dari session biasa, bukan flash
    $orderSuccess = session('order_success', false);
    $orderNumber = session('order_number');

    // Hapus agar tidak muncul terus-menerus
    session()->forget('order_success');
    session()->forget('order_number');

    $cart = Cart::with('items.product.store')->where('user_id', $user->id)->first();

    $selectedItemIds = $request->query('selected_items', []);

    if (!is_array($selectedItemIds)) {
        $selectedItemIds = [];
    }
    $selectedItemIds = array_map('intval', $selectedItemIds);

    if (!$orderSuccess && (!$cart || $cart->items->isEmpty())) {
        return redirect()->route('cart')->with('error', 'Keranjang belanja kosong!');
    }

    if (empty($selectedItemIds)) {
        return redirect()->route('cart')->with('error', 'Silakan pilih item yang ingin dibeli.');
    }

    $items = $cart->items->filter(function ($item) use ($selectedItemIds) {
        return in_array($item->id, $selectedItemIds);
    });

    if ($items->isEmpty()) {
        return redirect()->route('cart')->with('error', 'Item yang dipilih tidak valid.');
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

    $addresses = $user->addresses ?? collect();
    $paymentMethods = $user->paymentMethods ?? collect();
    $totalPrice = $items->sum(fn($item) => $item->product->price * $item->quantity);

    return view('user_view.payment', [
        'cart' => $cart,
        'items' => $items,
        'totalPrice' => $totalPrice,
        'addresses' => $addresses,
        'paymentMethods' => $paymentMethods,
        'orderSuccess' => $orderSuccess,
        'orderNumber' => $orderNumber,
        'selectedItemIds' => $selectedItemIds
    ]);
}

// buat proses checkout produk
   public function processCheckout(Request $request)
{
    $user = Auth::user();
    
    // Validasi input
    $request->validate([
        'shipping_address_id' => 'required|exists:addresses,id',
        'type' => 'required|string|in:bank_transfer,e-wallet',
        'provider' => 'required|string',
        'selected_items' => 'required|array', // Tambahkan validasi ini
        'selected_items.*' => 'integer|exists:cart_items,id'
    ]);

    $cart = Cart::with('items.product')->where('user_id', $user->id)->first();
    
    // Ambil hanya item yang dipilih
    $selectedItems = $cart->items->whereIn('id', $request->selected_items);

    // Validasi item
    if($selectedItems->isEmpty()) {
        return back()->withErrors('Tidak ada item yang dipilih!');
    }

    // Validasi toko
    $storeIds = $selectedItems->pluck('product.store_id')->unique();
    if($storeIds->count() > 1) {
        return back()->withErrors('Hanya boleh checkout dari 1 toko!');
    }

    // Simpan metode pembayaran
    $paymentMethod = PaymentMethod::create([
        'user_id' => $user->id,
        'type' => $request->type,
        'provider' => $request->provider,
        'account_name' => $user->email,
        'account_number' => $user->phone_number, 
    ]);

    // Hitung total dari item yang dipilih
    $totalAmount = $selectedItems->sum(function($item) {
        return $item->product->price * $item->quantity;
    });

    // Buat order baru
    $order = Order::create([
        'user_id' => $user->id,
        'order_number' => 'INV-' . strtoupper(Str::random(8)),
        'status' => 'pending',
        'total_amount' => $totalAmount,
        'shipping_address_id' => $request->shipping_address_id,
        'payment_method_id' => $paymentMethod->id,
        'store_id' => $storeIds->first(),
    ]);

    // Tambahkan item yang dipilih ke order
    foreach($selectedItems as $cartItem) {
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $cartItem->product_id,
            'quantity' => $cartItem->quantity,
            'unit_price' => $cartItem->product->price,
            'total_price' => $cartItem->product->price * $cartItem->quantity,
        ]);

        // Tambahkan update sold_amount
        $product = $cartItem->product;
        $product->sold_amount += $cartItem->quantity;
        $product->save();
    }

    // Hapus hanya item yang dipilih dari cart
    CartItem::whereIn('id', $request->selected_items)->delete();

    return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibuat!');
}

}