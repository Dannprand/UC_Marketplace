<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $orders = $user->orders()->with(['items.product.merchant'])->latest()->get();
        return view('order', compact('orders'));
    }

    public function history(): View
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