<?php
// app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('payment.index', compact('cart', 'total'));
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
}
