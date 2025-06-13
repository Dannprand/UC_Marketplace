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
    private function getProductPrice(Product $product)
    {
        return $product->discounted_price ?? $product->price;
    }

    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $cart = Cart::with(['items.product' => function($query) {
                $query->with('store');
            }])
            ->where('user_id', $user->id)
            ->first();

        if (!$cart) {
            $cart = Cart::create(['user_id' => $user->id]);
        }

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

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return redirect()->back()->with('success', 'Cart updated successfully.');
    }

    public function remove($id)
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

    public function addToCart(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $product = Product::findOrFail($productId);
        $quantity = $request->input('quantity', 1);
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }

        return redirect()->route('cart')->with('success', 'Product added to cart successfully!');
    }

    public function payment(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $cart = Cart::with(['items.product.store'])
            ->where('user_id', $user->id)
            ->first();

        $selectedItemIds = $request->query('selected_items', []);
        
        if (!is_array($selectedItemIds)) {
            $selectedItemIds = explode(',', $selectedItemIds);
        }
        
        $selectedItemIds = array_map('intval', $selectedItemIds);
        $selectedItemIds = array_filter($selectedItemIds);

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $items = $cart->items->filter(function ($item) use ($selectedItemIds) {
            return in_array($item->id, $selectedItemIds);
        });

        if ($items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'No valid items selected.');
        }

        $storeIds = $items->pluck('product.store_id')->unique();

        if ($storeIds->count() > 1) {
            return redirect()->route('cart')->with('error', 'You can only checkout items from one store at a time.');
        }

        $storeId = $storeIds->first();
        $store = Store::with('merchant')->find($storeId);

        if (!$store || !$store->merchant) {
            return redirect()->route('cart')->with('error', 'Store information is incomplete.');
        }

        $merchant = $store->merchant;
        $totalPrice = $items->sum(function($item) {
            $price = $this->getProductPrice($item->product);
            return $price * $item->quantity;
        });

        $qrCodeData = '';
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
        }

        $addresses = $user->addresses()->get();
        $paymentMethods = collect();
        
        return view('user_view.payment', [
            'cart' => $cart,
            'items' => $items,
            'totalPrice' => $totalPrice,
            'addresses' => $addresses,
            'paymentMethods' => $paymentMethods,
            'selectedItemIds' => $selectedItemIds,
            'merchant' => $merchant,
            'qrCodeData' => $qrCodeData,
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
            'selected_items.*' => 'integer|exists:cart_items,id',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        DB::beginTransaction();

        try {
            $cart = Cart::with('items.product')
                ->where('user_id', $user->id)
                ->first();
            
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
            $proofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
            
            $paymentMethod = PaymentMethod::create([
                'user_id' => $user->id,
                'type' => 'bank_transfer',
                'provider' => $merchant->bank_name,
                'account_name' => $merchant->merchant_name,
                'account_number' => $merchant->account_number,
            ]);

            $totalAmount = $selectedItems->sum(function ($item) {
                $price = $this->getProductPrice($item->product);
                return $price * $item->quantity;
            });

            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'INV-'.strtoupper(Str::random(8)),
                'status' => 'pending',
                'total_amount' => $totalAmount,
                'shipping_address_id' => $request->shipping_address_id,
                'payment_method_id' => $paymentMethod->id,
                'store_id' => $storeId,
                'payment_proof' => $proofPath,
            ]);

            foreach ($selectedItems as $cartItem) {
                $price = $this->getProductPrice($cartItem->product);
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $price,
                    'total_price' => $price * $cartItem->quantity,
                ]);

                $product = $cartItem->product;
                $product->sold_amount += $cartItem->quantity;
                $product->save();
            }

            CartItem::whereIn('id', $request->selected_items)->delete();

            DB::commit();

            return redirect()->route('orders.index')->with('success', 'Order created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout Error: '.$e->getMessage());
            return redirect()->back()->with('error', 'Checkout failed: '.$e->getMessage());
        }
    }
}