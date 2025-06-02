<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Shipping Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f0e7d5;
            margin: 0;
            padding: 0;
        }

        .shipping-section {
            max-width: 700px;
            margin: 8rem auto;
            background: #ffffff;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            font-weight: 600;
            display: block;
            margin-bottom: 0.5rem;
            color: #212842;
        }

        input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #cbd5e0;
            border-radius: 8px;
            font-size: 1rem;
        }

        button {
            background-color: #212842;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
        }

        button:hover {
            background-color: #3b4b91;
        }

        .success-message {
            background-color: #c6f6d5;
            color: #22543d;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
    </style>
</head>

<body>
    <x-navigation />
    <div class="mt-12">
        <div class="shipping-section">
            <a href="{{ route('merchant.transactions') }}" class="text-sm text-black font-medium hover:underline">&larr;
                Back to Transactions</a>

            <h1 class="text-2xl font-bold text-[#212842] mt-4 mb-6">Shipping Order #{{ $order->id }}</h1>

            @if (session('success'))
                <div class="success-message">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('merchant.orders.shipping.store', $order->id) }}">
                @csrf
                <div class="form-group">
                    <label for="shipping_provider">Shipping Provider</label>
                    <select name="shipping_provider" id="shipping_provider" required>
                        @php
                            $providers = ['JNE Express', 'J&T Express', 'SiCepat', 'Go-Send'];
                            $selected = old('shipping_provider', $order->shipping_provider);
                        @endphp
                        @foreach ($providers as $provider)
                            <option value="{{ $provider }}" {{ $selected === $provider ? 'selected' : '' }}>
                                {{ $provider }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="tracking_number">Tracking Number</label>
                    <input type="text" name="tracking_number" id="tracking_number"
                        value="{{ old('tracking_number', $order->tracking_number) }}" required>
                </div>

                <div class="form-group">
                    <label for="estimated_delivery">Estimated Delivery Date</label>
                    <input type="date" name="estimated_delivery" id="estimated_delivery"
                        value="{{ old('estimated_delivery') }}" min="{{ date('Y-m-d') }}" required>
                </div>

                <div class="form-group">
                    <label for="note" class="block font-medium">Notes</label>
                    <textarea name="note" id="note" rows="3"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">{{ old('note') }}</textarea>
                </div>


                <button type="submit">Set Shipping</button>
            </form>
        </div>

    </div>

</body>

</html>
