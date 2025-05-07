<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;  



class OrderController extends Controller{

    public function index()
{
    $user = Auth::user();
    $orders = $user->orders()->with(['items.product.merchant'])->latest()->get();

    return view('order', compact('orders'));
}

public function history()
{
    $user = Auth::user();

    $orders = $user->orders()
        ->with(['items.product', 'merchant'])
        ->latest()
        ->get();

    $address = $user->addresses()->where('is_primary', true)->first();
    $paymentMethod = $user->paymentMethods()->where('is_default', true)->first();

    return view('orders.history', compact('orders', 'address', 'paymentMethod'));
}

}

