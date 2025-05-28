<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller 
{
    // Show login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

 //Check if user is admin
 
    private function isAdminUser($email, $password)
    {
        return $email === 'admin@gmail.com' && $password === '123';
    }

    // Handle login request
    public function login(Request $request)
{
    // Admin login check (add this at the start)
    if ($this->isAdminUser($request->email, $request->password)) {
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'full_name' => 'Admin',
                'phone_number' => '0000000000',
                'password' => Hash::make('123'),
                'email_verified_at' => now(),
            ]
        );

        Auth::login($admin);
        $request->session()->regenerate();
        return redirect()->route('admin.dashboard');
    }

    // Rest of your existing login method remains the same...
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect('/home');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
}

    // Show registration form
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Handle registration request
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|unique:users|regex:/^[0-9]{10,15}$/',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(), // Auto-verify for now
        ]);

        // Future email verification (commented out)
        // event(new Registered($user));
        // Auth::login($user);

        // Current behavior - login immediately
        session(['registering_user' => $user->id]);

        return redirect()->route('register.address');

    }

    // Handle logout request
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function showAddressForm()
{
    if (!session()->has('registering_user')) {
        return redirect()->route('register');
    }

    return view('auth.address');
}

public function processAddress(Request $request)
{
    $userId = session()->get('registering_user');
    if (!$userId) {
        return redirect()->route('register');
    }

    $validated = $request->validate([
        'street' => 'required|string|max:255',
        'city' => 'required|string|max:255',
        'province' => 'required|string|max:255',
        'postal_code' => 'required|string|max:10',
        'country' => 'required|string|max:255',
        'is_primary' => 'sometimes|boolean',
    ]);

    // Create address
    Address::create([
        'user_id' => $userId,
        'street' => $validated['street'],
        'city' => $validated['city'],
        'province' => $validated['province'],
        'postal_code' => $validated['postal_code'],
        'country' => $validated['country'],
        'is_primary' => $request->has('is_primary'),
    ]);

    // Login the user
    Auth::loginUsingId($userId);

    // Clear session
    session()->forget('registering_user');

    return redirect()->route('home')->with('success', 'Registration complete!');
}
/**
 * Admin dashboard
 */
public function adminDashboard(Request $request)
{
    if (!Auth::check() || Auth::user()->email !== 'admin@gmail.com') {
        abort(403, 'Unauthorized access');
    }

    $search = $request->input('search');
    
    $query = User::where('email', '!=', 'admin@gmail.com')
                ->orderBy('created_at', 'desc');

    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('full_name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone_number', 'like', "%{$search}%");
        });
    }

    $userCount = User::where('email', '!=', 'admin@gmail.com')->count();
    $newUsersToday = User::whereDate('created_at', today())->count();
    $recentUsers = $query->paginate(15);

    return view('admin.dashboard', [
        'userCount' => $userCount,
        'newUsersToday' => $newUsersToday,
        'recentUsers' => $recentUsers,
        'search' => $search
    ]);
}

/**
 * Show all users for admin
 */
public function adminUsers()
{
    // Validasi admin menggunakan session auth
    if (!Auth::check() || Auth::user()->email !== 'admin@gmail.com') {
        abort(403, 'Unauthorized access');
    }

    $users = User::where('email', '!=', 'admin@gmail.com')
                ->with('address') // Jika ingin menampilkan data alamat juga
                ->orderBy('created_at', 'desc')
                ->get();

    return view('admin.users', compact('users'));
}

public function userOrders(User $user)
{
    // Eager load orders dengan relasi yang diperlukan
    $orders = Order::with(['items.product.store.merchant', 'shippingAddress', 'paymentMethod'])
        ->where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->get();

    return view('admin.user_orders', [
        'user' => $user,
        'orders' => $orders
    ]);
}

/**
 * Delete user by admin
 */
public function adminDeleteUser(Request $request, $id)
{
    // Validasi admin menggunakan session auth
    if (!Auth::check() || Auth::user()->email !== 'admin@gmail.com') {
        abort(403, 'Unauthorized access');
    }

    $user = User::findOrFail($id);

    // Prevent deleting admin
    if ($user->email === 'admin@gmail.com') {
        return back()->with('error', 'Cannot delete admin user.');
    }

    // Hapus alamat terkait jika ada
    if ($user->address) {
        $user->address->delete();
    }

    $user->delete();
    return back()->with('success', 'User deleted successfully.');
}

}