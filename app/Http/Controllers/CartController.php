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
    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $cartItem = CartItem::findOrFail($itemId);
        $cartItem->update(['quantity' => $request->quantity]);

        return redirect()->route('cart')->with('success', 'Cart updated!');
    }

    public function remove($id) {
        $cartItem = CartItem::findOrFail($id);
        $cartItem->delete();
        return redirect()->back()->with('success', 'Item removed from cart.');
    }
    
    public function payment()
{
    $user = Auth::user();
    if (!$user) {
        return redirect()->route('login');
    }

    // Ambil cart user beserta item dan produk
    $cart = Cart::with('items.product')->where('user_id', $user->id)->first();

    // Jika cart kosong, arahkan kembali
    if (!$cart || $cart->items->isEmpty()) {
        return redirect()->route('cart')->with('error', 'Keranjang belanja kosong!');
    }

    // Ambil alamat dan metode pembayaran user
    $addresses = $user->addresses ?? collect();
    $paymentMethods = $user->paymentMethods ?? collect();

    // Hitung total harga
    $totalPrice = $cart->items->sum(function ($item) {
        return $item->product->price * $item->quantity;
    });

    $orderSuccess = session('order_success', false);

    return view('user_view.payment', [
        'cart' => $cart,
        'totalPrice' => $totalPrice,
        'addresses' => $addresses,
        'paymentMethods' => $paymentMethods,
        'orderSuccess' => $orderSuccess,  // Kirim ke blade
    ]);
}

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

     return redirect()->route('payment')
        ->with('order_success', true)
        ->with('order_number', $order->order_number);
}

    // Add this method for order confirmation
    public function orderConfirmation(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['items.product', 'shippingAddress', 'paymentMethod']);
        return view('user_view.order_confirmation', compact('order'));
    }
}



