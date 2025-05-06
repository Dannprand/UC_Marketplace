<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Session;

class CartController extends Controller
{   

    // Menampilkan halaman keranjang
    public function index()
{
    // Check if the user is authenticated
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login');  // Redirect to login if the user is not authenticated
    }

    // Get the cart with the related items and products
    $cart = Cart::with('items.product')->where('user_id', $user->id)->first();

    // If no cart exists, create a new one
    if (!$cart) {
        $cart = Cart::create(['user_id' => $user->id]);
    }

    return view('user_view.cart', compact('cart')); // Return the cart view
}


    // Menambahkan produk ke dalam keranjang
    public function addToCart(Request $request, $productId)
    {
        // Validate the quantity input
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        // Find the product by ID
        $product = Product::findOrFail($productId);

        // Get the quantity from the form
        $quantity = $request->input('quantity', 1);

        // Create an array for the cart item
        $cartItem = [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $quantity,
            'subtotal' => $product->price * $quantity,
        ];

        // Check if cart already exists in session
        $cart = session()->get('cart', []);

        // Check if product is already in the cart, then update quantity
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
            $cart[$productId]['subtotal'] = $cart[$productId]['price'] * $cart[$productId]['quantity'];
        } else {
            // Add the new product to the cart
            $cart[$productId] = $cartItem;
        }

        // Save the cart to session
        session()->put('cart', $cart);

        // Redirect to the cart page with a success message
        return redirect()->route('cart')->with('success', 'Product added to cart successfully!');
    }

}




