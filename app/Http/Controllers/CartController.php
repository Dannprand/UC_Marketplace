<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;

class CartController extends Controller
{
    // Menampilkan halaman keranjang
    public function index()
{
    $user = auth()->user();

    // Ambil cart dengan relasi ke cart items dan produk
    $cart = Cart::with('items.product')->where('user_id', $user->id)->first();

    // Jika cart belum ada, buat baru
    if (!$cart) {
        $cart = Cart::create(['user_id' => $user->id]);
    }

    return view('user_view.cart', compact('cart'));
}

    // Menambahkan produk ke dalam keranjang
    public function add(Request $request, $productId)
    {
        // Ambil user yang sedang login
        $user = auth()->user();

        // Cek apakah user sudah memiliki keranjang
        $cart = $user->cart;

        if (!$cart) {
            // Jika belum ada keranjang, buat baru
            $cart = Cart::create(['user_id' => $user->id]);
        }

        // Ambil produk yang ingin ditambahkan ke keranjang
        $product = Product::findOrFail($productId);

        // Menambahkan produk ke keranjang (menambahkan ke CartItem atau pivot table)
        $cart->items()->create([
            'product_id' => $product->id,
            'quantity' => $request->quantity ?? 1, // Default quantity adalah 1
        ]);

        // Redirect kembali ke halaman keranjang
        return redirect()->route('cart.index');
    }
}




