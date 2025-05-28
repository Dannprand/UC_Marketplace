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
 
//     public function showDetail($id)
// {
//     $user = Auth::user();
//     $merchant = $user->merchant;

//     if (!$merchant) {
//         return redirect()->route('merchant.dashboard')->with('error', 'Merchant tidak ditemukan.');
//     }

//     // Pastikan hanya mengambil store milik merchant yang sesuai dengan ID
//     $store = $merchant->store()->where('id', $id)->first();

//     if (!$store) {
//         return redirect()->route('merchant.dashboard')->with('error', 'Store tidak ditemukan.');
//     }

//     // Ambil data store analytic terkait store ini
//     $storeAnalytic = $store->storeAnalytic; // Asumsi relasi sudah ada di model Store

//     return view('merchant_view.detailMerchant', compact('store', 'storeAnalytic'));
// }

public function showDetail($id)
{
    $user = Auth::user();
    $merchant = $user->merchant;

    if (!$merchant) {
        return redirect()->route('merchant.dashboard')->with('error', 'Merchant tidak ditemukan.');
    }

    // Ambil store (boleh dummy saja kalau mau)
    $store = $merchant->store()->where('id', $id)->first();

    if (!$store) {
        return redirect()->route('merchant.dashboard')->with('error', 'Store tidak ditemukan.');
    }

    // Buat data dummy storeAnalytic kalau belum ada data asli
    $storeAnalytic = $store->storeAnalytic;
    if (!$storeAnalytic) {
        // Contoh buat stdClass dengan properti yang diperlukan
        $storeAnalytic = new \stdClass();
        $storeAnalytic->total_income = 15000000;  // 15 juta
        $storeAnalytic->total_products = 120;
        $storeAnalytic->total_orders = 80;

        // Bisa juga buat properti data grafik jika diperlukan
        $storeAnalytic->daily_sales = [
            ['date' => '2025-05-20', 'total' => 1000000],
            ['date' => '2025-05-21', 'total' => 1200000],
            ['date' => '2025-05-22', 'total' => 900000],
            ['date' => '2025-05-23', 'total' => 1500000],
            ['date' => '2025-05-24', 'total' => 1100000],
            ['date' => '2025-05-25', 'total' => 1300000],
            ['date' => '2025-05-26', 'total' => 1700000],
        ];
    }

    return view('merchant_view.detailMerchant', compact('store', 'storeAnalytic'));
}


    // Opsional: API untuk data income harian/mingguan/bulanan
    public function getIncomeData(Request $request)
{
    $view = $request->input('view', 'daily');
    $offset = (int) $request->input('offset', 0);

    // Simulasi respons
    $labels = [];
    $data = [];

    if ($view === 'daily') {
        for ($i = 0; $i < 3; $i++) {
            $labels[] = now()->subDays(2 - $i)->format('d M');
            $data[] = rand(1000000, 3000000);
        }
    } elseif ($view === 'weekly') {
        $labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $data = array_map(fn() => rand(1000000, 3000000), $labels);
    } elseif ($view === 'monthly') {
        $days = now()->daysInMonth;
        for ($i = 1; $i <= $days; $i++) {
            $labels[] = "Day $i";
            $data[] = rand(1000000, 3000000);
        }
    }

    return response()->json(compact('labels', 'data'));
}


}