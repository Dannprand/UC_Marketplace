<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
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
    ]);
}

// buat proses checkout produk
   public function processCheckout(Request $request)
{
    $user = Auth::user();

    // Validasi form input
    $request->validate([
        'shipping_address_id' => 'required|exists:addresses,id',
        'type' => 'required|string|in:bank_transfer,e-wallet',
        'provider' => 'required|string',
    ]);

   
    $cart = Cart::with('items.product')->where('user_id', $user->id)->first();

    if (!$cart || $cart->items->isEmpty()) {
        return back()->withErrors('Your cart is empty.');
    }

    $firstStoreId = $cart->items->first()->product->store_id;

    // Simpan metode pembayaran baru
    $paymentMethod = PaymentMethod::create([
        'user_id' => $user->id,
        'type' => $request->type,
        'provider' => $request->provider,
        'account_name' => $user->email,
        'account_number' => $user->phone_number, 
    ]);


    // Buat order baru
    $order = Order::create([
        'user_id' => $user->id,
        'order_number' => 'INV-' . strtoupper(Str::random(8)),
        'status' => 'pending',
        'total_amount' => $cart->items->sum(fn($item) => $item->product->price * $item->quantity),
        'shipping_address_id' => $request->shipping_address_id,
        'payment_method_id' => $paymentMethod->id,
        'store_id' => $firstStoreId,
    ]);

    // Tambahkan item ke order
    foreach ($cart->items as $item) {
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
            'unit_price' => $item->product->price,
            'total_price' => $item->product->price * $item->quantity,
        ]);
    }

    // Hapus item dari cart
    $cart->items()->delete();

    return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibuat!');

}

}



