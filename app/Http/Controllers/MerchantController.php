<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\Store;
use App\Models\StoreAnalytic;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;


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

// public function updateStatus(Request $request, Order $order)
// {
//     $request->validate([
//         'status' => ['required', Rule::in(['pending', 'processing', 'shipped', 'delivered', 'cancelled'])],
//     ]);

//     $merchant = Auth::user()->merchant;
//     if (!$merchant || $order->store->merchant_id !== $merchant->id) {
//         abort(403, 'Unauthorized');
//     }

//     $oldStatus = $order->status;
//     $newStatus = $request->status;

//     $order->status = $newStatus;
//     $order->save();

//     // Update analytic hanya jika status berubah jadi 'shipped' dari status selain 'shipped'
//     if ($newStatus === 'shipped' && $oldStatus !== 'shipped') {
//         $storeAnalytic = $order->store->analytics;

//         if (!$storeAnalytic) {
//             $storeAnalytic = new StoreAnalytic();
//             $storeAnalytic->store_id = $order->store->id;
//             $storeAnalytic->total_views = 0;
//             $storeAnalytic->unique_visitors = 0;
//             $storeAnalytic->conversion_rate = 0;
//             $storeAnalytic->total_sales = 0;
//             $storeAnalytic->total_revenue = 0;
//             $storeAnalytic->average_order_value = 0;
//         }

//         // Tambahkan 1 pada total_sales
//         $storeAnalytic->total_sales += 1;

//         // Tambahkan total_amount order ke total_revenue
//         $storeAnalytic->total_revenue += $order->total_amount;

//         // Hitung average_order_value
//         if ($storeAnalytic->total_sales > 0) {
//             $storeAnalytic->average_order_value = $storeAnalytic->total_revenue / $storeAnalytic->total_sales;
//         }

//         $storeAnalytic->save();
//     }

//     return redirect()->back()->with('success', 'Order status updated.');
// }

// for debugging
public function updateStatus(Request $request, Order $order)
{
    $request->validate([
        'status' => ['required', Rule::in(['pending', 'processing', 'shipped', 'delivered', 'cancelled'])],
    ]);

    $merchant = Auth::user()->merchant;
    if (!$merchant || $order->store->merchant_id !== $merchant->id) {
        abort(403, 'Unauthorized');
    }

    $oldStatus = $order->status;
    $newStatus = $request->status;

    $order->status = $newStatus;
    $order->save();

    if ($newStatus === 'shipped' && $oldStatus !== 'shipped') {
        // Hitung total revenue dari order items
        $totalRevenue = $order->items()->sum('total_price');
        logger("Total revenue from order items: " . $totalRevenue);

        // Ambil atau buat StoreAnalytic
        $store = $order->store;
        $storeAnalytic = $store->analytics;

        if (!$storeAnalytic) {
            $storeAnalytic = new StoreAnalytic();
            $storeAnalytic->store_id = $store->id;
            $storeAnalytic->total_views = 0;
            $storeAnalytic->unique_visitors = 0;
            $storeAnalytic->conversion_rate = 0;
            $storeAnalytic->total_sales = 0;
            $storeAnalytic->total_revenue = 0;
            $storeAnalytic->average_order_value = 0;
            logger('StoreAnalytic not found, creating new one');
        } else {
            logger('StoreAnalytic found: ' . $storeAnalytic->id);
        }

        // Update data analitik
        $storeAnalytic->total_sales += 1;
        $storeAnalytic->total_revenue += $totalRevenue;

        if ($storeAnalytic->total_sales > 0) {
            $storeAnalytic->average_order_value = $storeAnalytic->total_revenue / $storeAnalytic->total_sales;
        }

        $storeAnalytic->save();

        logger('Updated StoreAnalytic:');
        logger([
            'total_sales' => $storeAnalytic->total_sales,
            'total_revenue' => $storeAnalytic->total_revenue,
            'average_order_value' => $storeAnalytic->average_order_value,
        ]);
    }

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


public function showDetail($id)
{
    $user = Auth::user();
    $merchant = $user->merchant;

    if (!$merchant) {
        return redirect()->route('merchant.dashboard')->with('error', 'Merchant tidak ditemukan.');
    }

    // Eager load analytics agar $store->analytics tersedia di Blade
    $store = $merchant->store()->with('analytics')->where('id', $id)->first();

    if (!$store) {
        return redirect()->route('merchant.dashboard')->with('error', 'Store tidak ditemukan.');
    }

    // Jika belum ada analytics, buatkan default-nya
    if (!$store->analytics) {
        $storeAnalytic = StoreAnalytic::create([
            'store_id' => $store->id,
            'total_views' => 0,
            'unique_visitors' => 0,
            'conversion_rate' => 0,
            'total_sales' => 0,
            'total_revenue' => 0,
            'average_order_value' => 0,
        ]);

        // Reload relasi agar $store->analytics tidak null di view
        $store->setRelation('analytics', $storeAnalytic);
    }

    return view('merchant_view.detailMerchant', compact('store'));
}

public function getIncomeData(Request $request)
{
    $view = $request->query('view', 'daily');
    $offset = (int) $request->query('offset', 0);

    $user = Auth::user();
    $store = optional($user->merchant)->store;

    if (!$store) {
        return response()->json(['labels' => [], 'data' => []]);
    }

    $startDate = now();
    $labels = [];
    $data = [];

    if ($view === 'daily') {
        // Mulai dari 6 hari lalu + offset mingguan (offset * 7)
        $startDate = now()->subDays(6 + ($offset * 7));

        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i)->format('Y-m-d');
            $labels[] = $date;

            $total = OrderItem::whereHas('product', fn($q) => $q->where('store_id', $store->id))
                ->whereHas('order', fn($q) => $q->whereDate('created_at', $date)
                    ->where('status', 'shipped'))
                ->select(DB::raw('SUM(quantity * unit_price) as total'))
                ->value('total') ?? 0;

            $data[] = (float) $total;
        }
    }

    if ($view === 'weekly') {
        // Mulai dari 4 minggu lalu + offset mingguan
        $startDate = now()->subWeeks(4 + $offset);

        for ($i = 0; $i < 5; $i++) {
            $weekStart = $startDate->copy()->addWeeks($i)->startOfWeek();
            $weekEnd = $weekStart->copy()->endOfWeek();

            $labels[] = "Minggu ke-" . ($i + 1);

            $total = OrderItem::whereHas('product', fn($q) => $q->where('store_id', $store->id))
                ->whereHas('order', fn($q) => $q->whereBetween('created_at', [$weekStart, $weekEnd])
                    ->where('status', 'shipped'))
                ->select(DB::raw('SUM(quantity * unit_price) as total'))
                ->value('total') ?? 0;

            $data[] = (float) $total;
        }
    }

    if ($view === 'monthly') {
        // Mulai dari 5 bulan lalu + offset bulanan
        $startDate = now()->subMonths(5 + $offset);

        for ($i = 0; $i < 6; $i++) {
            $month = $startDate->copy()->addMonths($i);
            $labels[] = $month->format('F Y');

            $total = OrderItem::whereHas('product', fn($q) => $q->where('store_id', $store->id))
                ->whereHas('order', fn($q) => $q->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->where('status', 'shipped'))
                ->select(DB::raw('SUM(quantity * unit_price) as total'))
                ->value('total') ?? 0;

            $data[] = (float) $total;
        }
    }

    return response()->json([
        'labels' => $labels,
        'data' => $data,
    ]);
}


}