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
        return redirect()->route('login');  // Redirect to login if the user is not authenticated
    }

    // Get the cart with the related items and products
    $cart = Cart::with('items.product')->where('user_id', $user->id)->first();

    if (!$cart) {
        $cart = Cart::create(['user_id' => $user->id]);
    }

    return view('user_view.cart', compact('cart')); 
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
}



