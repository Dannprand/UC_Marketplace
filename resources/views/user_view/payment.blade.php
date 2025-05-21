<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>Payment - UCMarketPlace</title>
    <style>
        /* Base styles remain the same */
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #e0f3fe;
            background: -webkit-linear-gradient(180deg, #e0f3fe 70%, #a1d4f6 100%);
            background: linear-gradient(180deg, #e0f3fe 70%, #a1d4f6 100%);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }

        /* Revised grid layout */
        .payment-container {
            margin: 30px auto;
            padding: 0 100px;
            grid-template-columns: 1fr 1fr;
            align-items: start;
        }

        .payment-header {
            grid-column: 1 / -1;
            padding: 20px;
            margin: 0 50px;
            font-size: 24px;
            color: #333;
            margin-bottom: 18px;
            font-weight: 600;
            border-bottom: 2px solid black;
            position: relative;
            padding-bottom: 15px;
        }

        /* Column styling */
        .payment-column {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            height: fit-content;
            border-left: 1px solid #e2e8f0;
            border-radius: 12px;
        }

        .address-section {
            grid-column: 1;
        }

        .payment-section {
            grid-column: 2;
        }

        .order-section {
            grid-column: 1 / -1;
            position: sticky;
            top: 20px;
        }

        @media (max-width: 768px) {
            .payment-container {
                grid-template-columns: 1fr;
            }

            .address-section,
            .payment-section,
            .order-section {
                grid-column: 1 / -1;
            }
        }

        .payment-methods h2,
        .order-summary h2 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #2d3748;
        }

        .payment-option {
            display: flex;
            align-items: center;
            padding: 15px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 12px;
        }

        .payment-option:hover {
            border-color: #96C2DB;
            background-color: #f8fafc;
        }

        .payment-option.active {
            border-color: #96C2DB;
            background-color: #f0f9ff;
        }

        .order-items {
            margin-bottom: 20px;
            max-height: 300px;
            overflow-y: auto;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            border-radius: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #edf2f7;
        }

        .pay-button {
            width: 100%;
            padding: 15px;
            background-color: #2ecc71;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s;
        }

        /* Alert styling */
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-danger {
            background-color: #fee2e2;
            color: #dc2626;
            border: 1px solid #fca5a5;
        }

        /* Popup Styling */
        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .popup-content {
            background: white;
            padding: 40px;
            border-radius: 12px;
            text-align: center;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .popup-content h2 {
            color: #2ecc71;
            margin-bottom: 15px;
        }

        .popup-content p {
            margin-bottom: 25px;
            color: #555;
        }

        .popup-content button {
            padding: 12px 25px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .popup-content button:hover {
            background-color: #2980b9;
        }

    </style>
</head>

<body>
   <x-navigation />

<div class="pt-24">
    <div class="payment-header">Checkout Process</div>
    <div class="payment-container">
        <!-- New Address Form -->
        <div id="new-address-form" class="my-4 bg-white p-4 rounded-xl hidden">
        <h3 class="font-semibold mb-2">Add a New Address</h3>
            <form action="{{ route('address.store') }}" method="POST" class="space-y-2">
                 @csrf
                <input type="hidden" name="from_checkout" value="1">

                <input type="text" name="street" placeholder="Street / House No." required class="w-full border px-3 py-2 rounded mb-2">
                <input type="text" name="city" placeholder="City" required class="w-full border px-3 py-2 rounded mb-2">
                <input type="text" name="province" placeholder="Province" required class="w-full border px-3 py-2 rounded mb-2">
                <input type="text" name="postal_code" placeholder="Postal Code" required class="w-full border px-3 py-2 rounded mb-2">
                <input type="text" name="country" placeholder="Country" required class="w-full border px-3 py-2 rounded mb-2">

                <label class="flex items-center mb-2">
                    <input type="checkbox" name="is_primary" value="1" class="mr-2">
                        Set as primary address
                </label>

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save Address</button>
            </form>
        </div>

        <form id="checkout-form" action="{{ route('checkout.process') }}" method="POST" class="space-y-4">
            @csrf
            <!-- Top Section: Address and Payment Side by Side -->
            <div class="flex flex-col lg:flex-row gap-4">
                <!-- Shipping Address (50%) -->
                <div class="w-full lg:w-1/2 bg-white p-4 rounded-xl shadow">
                    <div class="mb-4">
                    <label for="shipping_address_id" class="block mb-1 font-medium">Shipping Address</label>
                        <select name="shipping_address_id" id="shipping_address_id" class="w-full border rounded px-3 py-2" required>
                            @foreach($addresses as $address)
                                <option value="{{ $address->id }}" {{ $address->is_primary ? 'selected' : '' }}>
                                    {{ $address->street }}, {{ $address->city }}, {{ $address->province }} - {{ $address->postal_code }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <a href="#" id="toggle-address-form" class="text-blue-600 underline text-sm">+ Add New Address</a>

                    
                </div>

                <!-- Payment Method (Input Section) -->
                <div class="w-full lg:w-1/2 bg-white p-4 rounded-xl shadow">
                    <h2 class="text-lg font-semibold mb-2">Add Payment Method</h2>

                    <!-- Select Type -->
                    <div class="mb-3">
                        <label for="payment-type" class="block mb-1">Payment Type</label>
                        <select name="type" id="payment-type" class="w-full border rounded px-3 py-2" required>
                        <option value="" disabled selected>-- Select Payment Type --</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="e-wallet">E-Wallet</option>
                        </select>
                    </div>

                    <!-- Select Provider -->
                    <div class="mb-3">
                        <label for="payment-provider" class="block mb-1">Provider</label>
                        <select name="provider" id="payment-provider" class="w-full border rounded px-3 py-2" required>
                            <option value="" disabled selected>-- Select Provider --</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Bottom Section: Order Summary (100%) -->
            <div class="bg-white p-4 rounded-xl shadow">
                <h2 class="text-lg font-semibold mb-2">Order Summary</h2>
                <div class="order-items space-y-2">
                    @foreach($cart->items as $item)
                        <div class="order-item flex justify-between">
                            <span class="item-name">
                                {{ $item->product->name }} x{{ $item->quantity }}
                            </span>
                            <span class="item-price">
                                Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                </div>

                <div class="order-total mt-4 flex justify-between font-bold text-lg">
                    <span class="total-label">Total Amount</span>
                    <span class="total-value">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                </div>

                <button type="submit"
                        class="mt-4 w-full bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded">
                    Confirm Payment
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Optional Payment Success Popup -->
<div class="popup-overlay" id="popup" style="display:none;">
    <div class="popup-content animate__animated animate__zoomIn">
        <h2>Payment Successful!</h2>
        <p>Thank you for your order. We will process it shortly.</p>
        <button id="popup-ok-btn">OK</button>
    </div>
</div>

@php
    $orderSuccess = session('order_success');
@endphp

<script>
    // Toggle form tambah alamat baru
    document.getElementById('toggle-address-form').addEventListener('click', function (e) {
        e.preventDefault();
        const form = document.getElementById('new-address-form');
        form.classList.toggle('hidden');
    });

    // Payment type & provider logic
    const providerSelect = document.getElementById('payment-provider');
    const typeSelect = document.getElementById('payment-type');

    const providers = {
        'bank_transfer': ['BCA'],
        'e-wallet': ['Gopay', 'UC Coin']
    };

    typeSelect.addEventListener('change', function () {
        const selectedType = this.value;
        const options = providers[selectedType] || [];

        // Reset & populate provider dropdown
        providerSelect.innerHTML = '<option value="" disabled selected>-- Select Provider --</option>';
        options.forEach(provider => {
            const option = document.createElement('option');
            option.value = provider;
            option.textContent = provider;
            providerSelect.appendChild(option);
        });
    });

    // Payment success popup logic
    const orderSuccess = @json(session('order_success'));
    console.log("Order Success from session:", orderSuccess);

    if (orderSuccess) {
        window.addEventListener('DOMContentLoaded', () => {
            const popup = document.getElementById('popup');
            popup.style.display = 'flex';

            document.getElementById('popup-ok-btn').addEventListener('click', () => {
                popup.style.display = 'none';
                window.location.href = '/home';
            });
        });
    }
</script>
