<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem; // Added missing import
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class PaymentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Load cart with items and product relationships
        $cart = $user->cart()->with('items.product')->first();
        
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty');
        }

        $totalPrice = $cart->items->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        $address = $user->addresses()->where('is_primary', true)->first();
        $paymentMethod = $user->paymentMethods()->where('is_default', true)->first();

        return view('user_view.payment', [
            'cart' => $cart,
            'totalPrice' => $totalPrice,
            'address' => $address,
            'paymentMethod' => $paymentMethod
        ]);
    }

    public function show(Order $order)
    {
        return view('payment', [
            'order' => $order->load('items.product', 'paymentMethod')
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:credit_card,bank_transfer,e-wallet',
            'provider' => 'required|string|max:50',
            'account_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:30',
            'expiry_date' => 'nullable|date',
        ]);

        PaymentMethod::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'provider' => $request->provider,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
            'expiry_date' => $request->expiry_date,
            'is_default' => false,
        ]);

        return redirect()->back()->with('success', 'Payment method saved successfully!');
    }

    public function confirmPayment(Request $request)
    {
        $user = Auth::user();
        $cart = session()->get('cart', []);
        $groupedByMerchant = collect($cart)->groupBy('merchant_id');
        
        foreach ($groupedByMerchant as $merchantId => $items) {
            $order = Order::create([
                'user_id' => $user->id,
                'merchant_id' => $merchantId,
                'payment_method_id' => $user->paymentMethods()->where('is_default', true)->value('id'),
                'total' => collect($items)->sum(fn($item) => $item['price'] * $item['quantity']),
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }
        }

        session()->forget('cart');

        return redirect()->route('orders.history')->with('success', 'Order placed successfully!');
    }
}