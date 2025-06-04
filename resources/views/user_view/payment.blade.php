<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <title>Payment - UCMarketPlace</title>
    <style>

        .popup-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        
        .popup-content {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            max-width: 90%;
            max-height: 90vh;
            width: 500px;
            transform: scale(0.9);
            opacity: 0;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        
        .popup-content.active {
            transform: scale(1);
            opacity: 1;
        }
        
        .popup-scrollable-content {
            overflow-y: auto;
            max-height: 60vh;
            padding-right: 10px;
        }
        
        .popup-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: auto;
        }
        
        #qr-code-container {
            min-height: 250px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 10px;
            background: white;
        }
        
        #qrcode {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }
        
        #qrcode img {
            max-width: 100%;
            height: auto;
            display: block;
        }
        /* Base styles */
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f0e7d5;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }

        /* Main container layout */
        .payment-wrapper {
            padding: 30px 0;
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        .payment-container {
            width: 100%;
            max-width: 1200px;
            padding: 0 40px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        .payment-header {
            grid-column: 1 / -1;
            font-size: 28px;
            color: #212842;
            /* Dark blue-ish color */
            font-weight: 700;
            margin-bottom: 25px;
            text-align: center;
            position: relative;
        }

        .payment-header::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: 0;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background-color: #5363a0;
            /* Blueish underline */
            border-radius: 2px;
        }

        /* Column styling */
        .payment-column {
            background: white;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            height: fit-content;
            border: 1px solid #ddd;
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
            top: 30px;
            z-index: 10;
            margin-top: 20px;
        }

        @media (max-width: 1024px) {
            .payment-container {
                grid-template-columns: 1fr;
                padding: 0 20px;
                gap: 1.5rem;
            }

            .payment-header {
                margin: 0 20px 25px;
            }

            .address-section,
            .payment-section,
            .order-section {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 768px) {
            .payment-wrapper {
                padding: 20px 0;
            }

            .payment-container {
                padding: 0 15px;
                gap: 1rem;
            }

            .payment-header {
                font-size: 22px;
                margin-bottom: 20px;
            }

            .payment-column {
                padding: 1.5rem;
            }
        }

        .payment-methods h2,
        .order-summary h2 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 25px;
            color: #212842;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        .payment-option {
            display: flex;
            align-items: center;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 12px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #212842;
        }

        .payment-option:hover {
            border-color: #5363a0;
            background-color: #f0f0f0;
        }

        .payment-option.active {
            border-color: #5363a0;
            background-color: #d9e0f0;
        }

        .order-items {
            margin-bottom: 20px;
            max-height: 350px;
            overflow-y: auto;
            padding-right: 10px;
        }

        /* Custom scrollbar for order-items */
        .order-items::-webkit-scrollbar {
            width: 8px;
        }

        .order-items::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .order-items::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        .order-items::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px dashed #ddd;
            font-size: 15px;
            color: #212842;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .item-name {
            color: #333;
            font-weight: 500;
        }

        .item-price {
            color: #333;
            /* red accent from second CSS */
            font-weight: 400;
        }

        .order-total {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
        }

        .total-label {
            color: #212842;
        }

        .total-value {
            color: #5363a0;
            font-size: 22px;
        }

        .pay-button {
            width: 100%;
            padding: 18px;
            background-color: #212842;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 30px;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 4px 10px rgba(33, 40, 66, 0.3);
        }

        .pay-button:hover {
            background-color: #5363a0;
            transform: translateY(-2px);
        }

        .pay-button:active {
            transform: translateY(0);
        }

        /* Alert styling */
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-danger {
            background-color: #fcecea;
            color: #e74c3c;
            border: 1px solid #e74c3c;
        }

        /* Popup Styling */
        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(33, 40, 66, 0.7);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            animation: fadeIn 0.3s ease-out;
        }

        .popup-content {
            background: white;
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            max-width: 450px;
            width: 90%;
            box-shadow: 0 8px 30px rgba(33, 40, 66, 0.2);
            transform: scale(0.8);
            opacity: 0;
            transition: transform 0.3s ease-out, opacity 0.3s ease-out;
        }

        .popup-content.active {
            transform: scale(1);
            opacity: 1;
        }

        .popup-content h2 {
            color: #5363a0;
            margin-bottom: 20px;
            font-size: 28px;
            font-weight: 700;
        }

        .popup-content p {
            margin-bottom: 8px;
            color: #444;
            font-size: 15px;
            line-height: 1.5;
        }

        .popup-content p strong {
            color: #212842;
        }

        /* Style for the QR Code container */
        #qr-code-container {
            max-width: 220px;
            margin: 30px auto 0;
            border: 5px solid #f2f2f2;
            padding: 5px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .popup-content button {
            padding: 12px 28px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            margin-top: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        #close-popup-button {
            background-color: #95a5a6;
        }

        #close-popup-button:hover {
            background-color: #7f8c8d;
        }

        #proceed-to-payment-processing {
            background-color: #5363a0;
            margin-left: 15px;
        }

        #proceed-to-payment-processing:hover {
            background-color: #414e7a;
        }

        #qrcode {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            padding: 10px;
        }
        
        #qrcode canvas {
            max-width: 100%;
            height: auto;
            display: block;
        }
        
        .qr-placeholder {
            text-align: center;
            padding: 20px;
            color: #777;
            font-style: italic;
        }

        /* Animation Keyframes */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <x-navigation />

    <div class="pt-24 payment-wrapper">
        <div class="payment-container">
            <div class="payment-header">Checkout Process</div>

            @if ($errors->any())
                <div class="alert alert-danger col-span-full">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger col-span-full">
                    {{ session('error') }}
                </div>
            @endif

            <div id="new-address-form" class="payment-column my-4 hidden col-span-full">
                <h3 class="font-semibold mb-4 text-lg">Add a New Address</h3>
                <form action="{{ route('address.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="from_checkout" value="1">

                    <input type="text" name="street" placeholder="Street / House No." required
                        class="w-full border px-4 py-3 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-transparent transition-all">
                    <input type="text" name="city" placeholder="City" required
                        class="w-full border px-4 py-3 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-transparent transition-all">
                    <input type="text" name="province" placeholder="Province" required
                        class="w-full border px-4 py-3 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-transparent transition-all">
                    <input type="text" name="postal_code" placeholder="Postal Code" required
                        class="w-full border px-4 py-3 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-transparent transition-all">
                    <input type="text" name="country" placeholder="Country" required
                        class="w-full border px-4 py-3 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-transparent transition-all">

                    <label class="flex items-center text-gray-700">
                        <input type="checkbox" name="is_primary" value="1" class="mr-2 h-4 w-4 text-blue-600 rounded">
                        Set as primary address
                    </label>

                    <button type="submit"
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-3 rounded-lg font-semibold transition-colors">Save
                        Address</button>
                </form>
            </div>

            <form id="checkout-form" action="{{ route('checkout.process') }}" method="POST"
                class="space-y-4 grid grid-cols-1 lg:grid-cols-2 gap-4 col-span-full">
                @csrf
                @foreach ($selectedItemIds as $itemId)
                    <input type="hidden" name="selected_items[]" value="{{ $itemId }}">
                @endforeach

                <div class="payment-column address-section">
                    <h2 class="text-lg font-semibold mb-4">Shipping Address</h2>
                    <div class="mb-4">
                        <label for="shipping_address_id" class="block mb-2 font-medium text-gray-700">Select an
                            address</label>
                        <select name="shipping_address_id" id="shipping_address_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-400 focus:border-transparent transition-all"
                            required>
                            @forelse ($addresses as $address)
                                <option value="{{ $address->id }}" {{ $address->is_primary ? 'selected' : '' }}>
                                    {{ $address->street }}, {{ $address->city }}, {{ $address->province }} -
                                    {{ $address->postal_code }}
                                </option>
                            @empty
                                <option value="" disabled selected>No addresses found. Please add one.</option>
                            @endforelse
                        </select>
                    </div>
                    <a href="#" id="toggle-address-form"
                        class="text-blue-600 underline text-sm hover:text-blue-800 transition-colors">
                        + Add New Address
                    </a>
                </div>

                <div class="payment-column payment-section">
                    <h2 class="text-lg font-semibold mb-4">Payment Details (Merchant)</h2>
                    @if ($merchant)
                        <div class="space-y-3 text-gray-700">
                            <p><strong>Nama Pemilik:</strong> {{ $merchant->merchant_name }}</p>
                            <p><strong>Nomor Rekening:</strong> {{ $merchant->account_number }}</p>
                            <p><strong>Bank:</strong> {{ $merchant->bank_name }}</p>
                        </div>
                    @else
                        <p class="text-red-500 text-sm">Informasi pembayaran merchant tidak tersedia. Harap hubungi
                            penjual.</p>
                    @endif
                </div>

                <div class="payment-column order-section col-span-full">
                    <h2 class="text-lg font-semibold mb-4">Order Summary</h2>
                    <div class="order-items">
                        @forelse ($items as $item)
                            <div class="order-item">
                                <span class="item-name">{{ $item->product->name }} (x{{ $item->quantity }})</span>
                                <span class="item-price">Rp.
                                    {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</span>
                            </div>
                        @empty
                            <p class="text-gray-600">No items selected for checkout.</p>
                        @endforelse
                    </div>
                    <div class="order-total flex justify-between items-center font-bold text-xl">
                        <span class="total-label">Total:</span>
                        <span class="total-value">Rp. {{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>

                    <button type="button" id="open-payment-popup" class="pay-button">
                        Complete Payment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="popup-overlay" id="payment-popup-overlay">
        <div class="popup-content">
            <h2>Confirm Payment</h2>
            <p>Please transfer the total amount to the following merchant account:</p>
            
            <div class="popup-scrollable-content">
                @if ($merchant)
                    <p><strong>Nama Pemilik:</strong> <span id="popup-account-name">{{ $merchant->merchant_name }}</span></p>
                    <p><strong>Nomor Rekening:</strong> <span id="popup-account-number">{{ $merchant->account_number }}</span></p>
                    <p><strong>Bank:</strong> <span id="popup-bank-name">{{ $merchant->bank_name }}</span></p>
                @else
                    <p class="text-red-500">Merchant details are not available.</p>
                @endif
                <p><strong>Total Amount:</strong> <span id="popup-total-amount">Rp. {{ number_format($totalPrice, 0, ',', '.') }}</span></p>

                <!-- QR Code Container -->
                <div id="qr-code-container" class="my-4">
                    <div id="qrcode" class="flex justify-center">
                        <div class="qr-placeholder">QR Code will be generated</div>
                    </div>
                </div>

                <p class="mt-4 text-sm text-gray-600">Scan the QR code or use the bank details above to complete your payment.</p>
            </div>

            <div class="popup-buttons mt-4">
                <button type="button" id="close-popup-button" class="btn bg-gray-500 text-white">Close</button>
                <button type="button" id="proceed-to-payment-processing" class="btn bg-blue-500 text-white">I have paid</button>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleAddressFormButton = document.getElementById('toggle-address-form');
            const newAddressForm = document.getElementById('new-address-form');
            const shippingAddressSelect = document.getElementById('shipping_address_id');
            const openPaymentPopupButton = document.getElementById('open-payment-popup');
            const paymentPopupOverlay = document.getElementById('payment-popup-overlay');
            const popupContent = paymentPopupOverlay.querySelector('.popup-content');
            const closePopupButton = document.getElementById('close-popup-button');
            const proceedButton = document.getElementById('proceed-to-payment-processing');
            const checkoutForm = document.getElementById('checkout-form');

            // Toggle new address form visibility
            toggleAddressFormButton.addEventListener('click', function (e) {
                e.preventDefault();
                newAddressForm.classList.toggle('hidden');
                shippingAddressSelect.required = newAddressForm.classList.contains('hidden');
            });

             function generateQRCode(content) {
                const qrContainer = document.getElementById('qrcode');
                qrContainer.innerHTML = ''; // Clear any existing content
                
                try {
                    // Create QR code with qrcode.js
                    new QRCode(qrContainer, {
                        text: content,
                        width: 200,
                        height: 200,
                        colorDark: "#000000",
                        colorLight: "#ffffff",
                        correctLevel: QRCode.CorrectLevel.H
                    });
                } catch (error) {
                    console.error('Error generating QR code:', error);
                    qrContainer.innerHTML = '<div class="text-red-500 p-4">Failed to generate QR code</div>';
                }
            }

            // Validate form before showing popup
            openPaymentPopupButton.addEventListener('click', function() {
                if (checkoutForm.checkValidity()) {
                    @if($merchant)
                        // Prepare QR code content
                        const qrContent = `BANK: {{ $merchant->bank_name }}\n` +
                                        `ACCOUNT NO: {{ $merchant->account_number }}\n` +
                                        `ACCOUNT NAME: {{ $merchant->merchant_name }}\n` +
                                        `AMOUNT: Rp {{ number_format($totalPrice, 0, ',', '.') }}`;
                        
                        // Generate QR code
                        generateQRCode(qrContent);
                    @else
                        const qrContainer = document.getElementById('qrcode');
                        qrContainer.innerHTML = '<div class="text-center p-4 bg-gray-100 rounded shadow">Merchant information not available</div>';
                    @endif
                    
                    // Tampilkan popup
                    paymentPopupOverlay.style.display = 'flex';
                    setTimeout(() => {
                        popupContent.classList.add('active');
                    }, 10);
                } else {
                    checkoutForm.reportValidity();
                }
            });

            // Close popup
            closePopupButton.addEventListener('click', () => {
                popupContent.classList.remove('active');
                setTimeout(() => {
                    paymentPopupOverlay.style.display = 'none';
                }, 300);
            });

            // Close popup if clicked outside popup content
            paymentPopupOverlay.addEventListener('click', (e) => {
                if (e.target === paymentPopupOverlay) {
                    closePopupButton.click();
                }
            });

            // Submit form on confirm payment
            proceedButton.addEventListener('click', () => {
                // Optionally disable button to prevent double submit
                proceedButton.disabled = true;
                proceedButton.textContent = 'Processing...';
                checkoutForm.submit();
            });
        });

    </script>
</body>

</html>