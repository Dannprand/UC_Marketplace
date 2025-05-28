<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\Store;
use App\Models\StoreAnalytic;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class MerchantController extends Controller
{
    // Show merchant registration form
    public function showOpenForm()
    {
        // Check if user already has merchant account
        if (Auth::user()->merchant) {
            return redirect()->route('merchant.manage');
        }
        
        return view('openMerchant');
    }

    // Process merchant registration
    public function openMerchant(Request $request)
    {
        $request->validate([
            'merchant_name' => 'required|string|max:255',
            'description' => 'required|string',
            'merchant_password' => 'required|string|min:8|confirmed',
            'pfp' => 'nullable|image|max:2048',
        ]);

        // Handle file upload
        $pfpPath = null;
        if ($request->hasFile('pfp')) {
            $pfpPath = $request->file('pfp')->store('merchant_pfps', 'public');
        }

        // Create merchant
        $merchant = Merchant::create([
            'user_id' => Auth::id(),
            'merchant_name' => $request->merchant_name,
            'merchant_description' => $request->description,
            'merchant_pfp' => $pfpPath,
            'merchant_password' => Hash::make($request->merchant_password),
            'status' => 'active',
        ]);

        // Update user's merchant status
        // Auth::user()->update(['is_merchant' => true]);
        $user = Auth::user();
        $user->is_merchant = true;
        $user->save();

        return redirect()->route('store.create');
    }

    // Show merchant management login
    public function showManageForm()
    {
        if (!Auth::user()->merchant) {
            return redirect()->route('merchant.open');
        }
        
        return view('manageMerchant');
    }

    // Process merchant management login
    public function manageMerchant(Request $request)
    {
        $request->validate([
            'merchant_password' => 'required|string',
        ]);

        if (Hash::check($request->merchant_password, Auth::user()->merchant->merchant_password)) {
            return redirect()->route('merchant.dashboard');
        }

        return back()->withErrors(['merchant_password' => 'Incorrect merchant password']);
    }

    // Show merchant dashboard
    public function dashboard()
    {
        $merchant = Auth::user()->merchant;
        $store = $merchant->store;
        
        if (!$store) {
            return redirect()->route('store.create');
        }
        
        $products = $store->products()->with('category')->get();
        
        // return view('merchant', compact('merchant', 'store', 'products'));
        return view('merchant_view.merchant', compact('merchant', 'store', 'products'));
    }

    public function transactions()
{
    $merchant = Auth::user()->merchant;

    if (!$merchant) {
        abort(404, 'Merchant not found for this user.');
    }

    $store = $merchant->store;

    if (!$store) {
        abort(404, 'Store not found for this merchant.');
    }

    // Ambil semua order yang punya item dari produk milik store ini
    $orders = Order::whereHas('items.product', function ($query) use ($store) {
        $query->where('store_id', $store->id);
    })
    ->with(['user', 'items' => function ($query) use ($store) {
        // Hanya ambil item dari store ini
        $query->whereHas('product', function ($q) use ($store) {
            $q->where('store_id', $store->id);
        })->with('product');
    }])
    ->orderByDesc('created_at')
    ->get();

    // dd($orders);

    return view('merchant_view.transactions', compact('orders'));
}

public function updateStatus(Request $request, Order $order)
{
    $request->validate([
        'status' => ['required', Rule::in(['pending', 'processing', 'shipped', 'delivered', 'cancelled'])],
    ]);

    $merchant = Auth::user()->merchant;
    if (!$merchant || $order->store->merchant_id !== $merchant->id) {
        abort(403, 'Unauthorized');
    }

    $order->status = $request->status;
    $order->save();

    return redirect()->back()->with('success', 'Order status updated.');
}

        public function index()
    {
        $user = Auth::user();
        $merchant = $user->merchant; // relasi User -> Merchant
        $store = $merchant?->store; // relasi Merchant -> Store

        if (!$store) {
            return redirect()->route('some.route')->with('error', 'Store tidak ditemukan.');
        }
        $products = $store->products()->select('id', 'name', 'quantity', 'price', 'images')->get();

        return view('merchant_view.merchant', compact('products','store','merchant'));
    }

    
//     public function showDetail()
// {
//     $user = Auth::user();
//     $merchant = $user->merchant;

//     if (!$merchant) {
//         return redirect()->route('merchant.dashboard')->with('error', 'Merchant tidak ditemukan.');
//     }

//     $store = $merchant->store;

//     if (!$store) {
//         return redirect()->route('merchant.dashboard')->with('error', 'Store tidak ditemukan.');
//     }

//     // Ambil data store analytic terkait store ini
//     $storeAnalytic = $store->storeAnalytic; // Asumsi relasi sudah ada di model Store

//     return view('merchant_view.detailMerchant', compact('store', 'storeAnalytic'));
// }



    // // Opsional: API untuk data income harian/mingguan/bulanan
    // public function getIncomeData(Request $request)
    // {
    //     $merchant = Auth::user();
    //     $store = $merchant->store;

    //     $view = $request->get('view', 'daily'); // daily, weekly, monthly
    //     $offset = intval($request->get('offset', 0));

    //     $labels = [];
    //     $data = [];

    //     if ($view === 'daily') {
    //         for ($i = -2; $i <= 0; $i++) {
    //             $date = Carbon::today()->addDays($offset + $i);
    //             $labels[] = $date->format('M d');
    //             $data[] = rand(1000000, 4000000); // Ganti dengan data asli nanti
    //         }
    //     } elseif ($view === 'weekly') {
    //         $start = Carbon::now()->startOfWeek()->addWeeks($offset);
    //         for ($i = 0; $i < 7; $i++) {
    //             $day = $start->copy()->addDays($i);
    //             $labels[] = $day->format('D');
    //             $data[] = rand(1000000, 4000000);
    //         }
    //     } elseif ($view === 'monthly') {
    //         $monthStart = Carbon::now()->startOfMonth()->addMonths($offset);
    //         $daysInMonth = $monthStart->daysInMonth;

    //         for ($i = 1; $i <= $daysInMonth; $i++) {
    //             $labels[] = "Day $i";
    //             $data[] = rand(1000000, 4000000);
    //         }
    //     }

    //     return response()->json([
    //         'labels' => $labels,
    //         'data' => $data
    //     ]);
    // }
}