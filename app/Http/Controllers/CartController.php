<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

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
    
}



