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
        /* Previous styles remain the same */
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

        .payment-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }

        .payment-header {
            padding: 20px;
            font-size: 24px;
            color: #333;
            margin-bottom: 18px;
            font-weight: 600;
            border-bottom: 2px solid black;
            position: relative;
            padding-bottom: 15px;
        }

        /* Form sections */
        .form-section {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        /* Dropdown styles */
        .select-dropdown {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 16px;
            margin-top: 10px;
            background-color: #f8fafc;
            transition: all 0.3s ease;
        }

        .select-dropdown:hover {
            border-color: #96C2DB;
        }

        .select-dropdown:focus {
            outline: none;
            border-color: #4299e1;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.2);
        }

        /* Add new button */
        .add-new-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 15px;
            background-color: #4299e1;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .add-new-btn:hover {
            background-color: #3182ce;
        }

        /* Selected info box */
        .selected-info {
            background-color: #f0f9ff;
            border: 1px solid #bee3f8;
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .payment-container {
                padding: 0 10px;
            }
            
            .form-section {
                padding: 15px;
            }
        }

        /* Rest of your existing styles... */
    </style>
</head>

<body>
    <x-navigation></x-navigation>

    <div class="pt-16">
        <div class="payment-header">Checkout Process</div>
        <div class="payment-container">
            <form id="checkout-form" action="{{ route('checkout.process') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Shipping Address Section -->
                <div class="form-section">
                    <h2 class="text-lg font-semibold mb-4">Shipping Address</h2>
                    
                    @if($user->addresses->count() > 0)
                        <label for="address-select" class="block text-sm font-medium text-gray-700">Select Address</label>
                        <select id="address-select" name="address_id" class="select-dropdown">
                            @foreach($user->addresses as $addr)
                                <option value="{{ $addr->id }}" {{ $addr->is_primary ? 'selected' : '' }}>
                                    {{ $addr->street }}, {{ $addr->city }}, {{ $addr->province }} - {{ $addr->postal_code }}
                                </option>
                            @endforeach
                        </select>
                        
                        <div class="selected-info mt-3">
                            <h4 class="font-medium">Selected Address:</h4>
                            <p id="selected-address-display">
                                {{ $address->street }}, {{ $address->city }}, {{ $address->province }} - 
                                {{ $address->postal_code }}, {{ $address->country }}
                            </p>
                        </div>
                        
                        <a href="{{ route('profile') }}" class="add-new-btn">+ Add New Address</a>
                    @else
                        <div class="alert alert-danger">
                            No shipping address found. 
                            <a href="{{ route('profile') }}" class="text-blue-600 hover:underline">Please add an address first.</a>
                        </div>
                    @endif
                </div>

                <!-- Payment Method Section -->
                <div class="form-section">
                    <h2 class="text-lg font-semibold mb-4">Payment Method</h2>
                    
                    @if($user->paymentMethods->count() > 0)
                        <label for="payment-select" class="block text-sm font-medium text-gray-700">Select Payment Method</label>
                        <select id="payment-select" name="payment_method_id" class="select-dropdown">
                            @foreach($user->paymentMethods as $method)
                                <option value="{{ $method->id }}" {{ $method->is_default ? 'selected' : '' }}>
                                    {{ $method->type }} - {{ $method->provider }} ({{ $method->account_number }})
                                </option>
                            @endforeach
                        </select>
                        
                        <div class="selected-info mt-3">
                            <h4 class="font-medium">Selected Payment:</h4>
                            <p id="selected-payment-display">
                                {{ $paymentMethod->type }} - {{ $paymentMethod->provider }} 
                                ({{ $paymentMethod->account_number }})
                            </p>
                        </div>
                        
                        <a href="{{ route('profile') }}" class="add-new-btn">+ Add New Payment Method</a>
                    @else
                        <div class="alert alert-danger">
                            No payment method found. 
                            <a href="{{ route('profile') }}" class="text-blue-600 hover:underline">Please add a payment method first.</a>
                        </div>
                    @endif
                </div>

                <!-- Order Summary Section -->
                <div class="form-section">
                    <h2 class="text-lg font-semibold mb-4">Order Summary</h2>
                    <div class="order-items space-y-2">
                        @foreach($cart->items as $item)
                            <div class="order-item flex justify-between">
                                <span class="item-name">{{ $item->product->name }} x{{ $item->quantity }}</span>
                                <span class="item-price">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="order-total mt-4 flex justify-between font-bold text-lg">
                        <span class="total-label">Total Amount</span>
                        <span class="total-value">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>

                    @if($user->addresses->count() > 0 && $user->paymentMethods->count() > 0)
                        <button type="submit" class="mt-4 w-full bg-green-500 hover:bg-green-600 text-white py-3 px-4 rounded-lg font-medium text-lg transition-colors">
                            Confirm Payment
                        </button>
                    @else
                        <button type="button" class="mt-4 w-full bg-gray-400 text-white py-3 px-4 rounded-lg font-medium text-lg cursor-not-allowed" disabled>
                            Complete your details to proceed
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Popup remains the same -->
    <div class="popup-overlay" id="popup">
        <div class="popup-content animate__animated animate__zoomIn">
            <h2>Payment Successful!</h2>
            <p>Thank you for your order. We will process it shortly.</p>
            <button id="popup-ok-btn">OK</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Update displayed address when selection changes
            const addressSelect = document.getElementById('address-select');
            const paymentSelect = document.getElementById('payment-select');
            const addressDisplay = document.getElementById('selected-address-display');
            const paymentDisplay = document.getElementById('selected-payment-display');
            
            if (addressSelect) {
                addressSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    addressDisplay.textContent = selectedOption.text;
                });
            }
            
            if (paymentSelect) {
                paymentSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    paymentDisplay.textContent = selectedOption.text;
                });
            }

            // Payment Success Popup
            const form = document.getElementById('checkout-form');
            const popup = document.getElementById('popup');
            const okBtn = document.getElementById('popup-ok-btn');

            if (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault(); // prevent real submission (for demo)
                    popup.style.display = 'flex';
                });
            }

            if (okBtn) {
                okBtn.addEventListener('click', function () {
                    popup.style.display = 'none';
                    window.location.href = '/orders'; // redirect to orders page
                });
            }
        });
    </script>
</body>
</html>