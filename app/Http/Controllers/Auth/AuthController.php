<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
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

    // Handle login request
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/home');
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
}