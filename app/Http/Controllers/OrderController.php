<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Handle new address submission during checkout
    public function storeAddress(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'is_primary' => 'nullable|boolean',
        ]);

        // Set existing primary to false if this new one is primary
        if ($request->has('is_primary')) {
            Address::where('user_id', $user->id)->update(['is_primary' => false]);
        }

        Address::create([
            'user_id' => $user->id,
            'street' => $validated['street'],
            'city' => $validated['city'],
            'province' => $validated['province'],
            'postal_code' => $validated['postal_code'],
            'country' => $validated['country'],
            'is_primary' => $request->has('is_primary'),
        ]);

        return redirect()->route('checkout.payment')->with('success', 'Address added successfully.');
    }

//     public function deleteAddress($id)
// {
//     $user = Auth::user();
//     $address = $user->addresses()->findOrFail($id);

//     // Cek apakah user punya lebih dari 1 alamat
//     if ($user->addresses()->count() <= 1) {
//         return back()->withErrors(['You must have at least one address.']);
//     }

//     $wasPrimary = $address->is_primary;

//     $address->delete();

//     // Kalau alamat yang dihapus adalah primary, set salah satu jadi primary
//     if ($wasPrimary) {
//         $nextAddress = $user->addresses()->first();
//         if ($nextAddress) {
//             $nextAddress->is_primary = true;
//             $nextAddress->save();
//         }
//     }

//     return back()->with('success', 'Address deleted successfully.');
// }

}
