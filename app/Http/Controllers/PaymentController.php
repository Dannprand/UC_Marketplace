<?php
// app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

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

}
