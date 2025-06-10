<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Order - UCMarketPlace</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        body {
            background: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .order-section {
            max-width: 1200px;
            margin: 8rem auto;
            padding: 0 1.25rem;
        }

        .order-card {
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
            background: white;
        }

        .order-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.18);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .order-id {
            font-weight: 700;
            font-size: 1.25rem;
            color: #680303;
        }

        .order-date {
            color: #4a5568;
            font-size: 0.9rem;
        }

        .order-status {
            display: inline-block;
            font-weight: 600;
            font-size: 0.75rem;
            padding: 0.3rem 0.75rem;
            border-radius: 9999px;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        .status-pending {
            background-color: #fff5f5;
            color: #e53e3e;
        }

        .status-processing {
            background-color: #fefcbf;
            color: #b7791f;
        }
        
        .status-pending_verification {
            background-color: #fff5f5;
            color: #e53e3e;
            border: 1px dashed #e53e3e;
        }

        .status-shipped {
            background-color: #bee3f8;
            color: #3182ce;
        }

        .status-delivered {
            background-color: #c6f6d5;
            color: #2f855a;
        }

        .status-cancelled {
            background-color: #e2e8f0;
            color: #4a5568;
        }

        .order-items {
            border-top: 1px solid #cbd5e0;
            border-bottom: 1px solid #cbd5e0;
            margin-bottom: 1rem;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            font-size: 0.95rem;
            color: #2d3748;
            border-bottom: 1px solid #e2e8f0;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .order-total {
            color: #5363a0;
        }

        .product-name {
            font-weight: 600;
        }

        .product-store {
            font-size: 0.8rem;
            color: #718096;
            margin-top: 0.15rem;
        }

        .payment-amount {
            font-weight: 700;
            color: #2b6cb0;
            white-space: nowrap;
        }

        .no-orders {
            max-width: 600px;
            margin: 3rem auto;
            background: white;
            color: #975a16;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        /* Payment Proof Styles */
        .payment-proof-section {
            margin-top: 1.5rem;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        
        .payment-proof-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #212842;
        }
        
        .payment-proof-image {
            max-width: 300px;
            max-height: 300px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-top: 0.5rem;
        }
        
        .payment-actions {
            margin-top: 1rem;
            display: flex;
            gap: 0.5rem;
        }
        
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .btn-primary {
            background-color: #212842;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #5363a0;
        }
        
        .btn-secondary {
            background-color: #e2e8f0;
            color: #4a5568;
        }
        
        .btn-secondary:hover {
            background-color: #cbd5e0;
        }

        .review-form {
            margin-top: 1rem;
            padding: 1rem;
            background-color: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .review-form h4 {
            font-weight: 600;
            margin-bottom: 1rem;
            color: #212842;
        }

        /* Rating Stars */
        .rating input:checked ~ label {
            color: #ccc;
        }
        .rating label {
            color: #ffc107;
            cursor: pointer;
        }
        .rating input:checked + label {
            color: #ffc107;
        }
    </style>
</head>

<body>
    <x-navigation></x-navigation>

    <section class="order-section">
        @if ($orders->count() > 0)
            <h1 class="text-2xl font-bold text-[#212842] mb-8">Order History</h1>
            @foreach ($orders as $order)
                @php
                    $statusClass = match ($order->status) {
                        'pending' => 'status-pending',
                        'pending_verification' => 'status-pending_verification',
                        'processing' => 'status-processing',
                        'shipped' => 'status-shipped',
                        'delivered' => 'status-delivered',
                        'cancelled' => 'status-cancelled',
                        default => '',
                    };
                @endphp

                <article class="order-card" data-order-id="{{ $order->id }}">
                    <header class="order-header">
                        <div class="order-id">Order #{{ $order-> order_number }}</div>
                        <div class="order-date">{{ $order->created_at->format('d M Y, H:i') }}</div>
                    </header>

                    <div class="order-status {{ $statusClass }}">
                        {{ str_replace('_', ' ', ucfirst($order->status)) }}
                    </div>

                    <div class="order-items">
                        @foreach ($order->items as $item)
                            <div class="order-item">
                                <div>
                                    <p class="product-name">{{ $item->product->name }} x{{ $item->quantity }}</p>
                                    @if ($item->product->store && $item->product->store->merchant)
                                        <p class="product-store">From:
                                            {{ $item->product->store->merchant->merchant_name }}</p>
                                    @endif
                                    <p class="product-price">
                                        Price: Rp {{ number_format($item->unit_price, 0, ',', '.') }} <br>
                                        Total Price: Rp {{ number_format($item->total_price, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                            @if ($order->status === 'delivered')
        @php
            $hasReviewed = $item->product->reviews->contains('order_id', $order->id);
        @endphp
        
        @if (!$hasReviewed)
            <div class="review-form mt-4 p-4 bg-gray-50 rounded-lg">
                <h4 class="font-medium mb-3">Review Product: {{ $item->product->name }}</h4>
                <form action="{{ route('reviews.store', $item->product) }}" method="POST">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    
                    <!-- Rating -->
                    <div class="mb-3">
                        <label class="block text-sm font-medium mb-1">Rating</label>
                        <div class="flex space-x-1">
                            @for ($i = 1; $i <= 5; $i++)
                                <input type="radio" id="star{{ $i }}_{{ $item->id }}" name="rating" value="{{ $i }}" 
                                    class="hidden peer" {{ $i == 5 ? 'checked' : '' }}>
                                <label for="star{{ $i }}_{{ $item->id }}" 
                                    class="text-2xl cursor-pointer text-gray-300 peer-checked:text-yellow-500">★</label>
                            @endfor
                        </div>
                    </div>
                    
                    <!-- Comment -->
                    <div class="mb-3">
                        <label class="block text-sm font-medium mb-1">Review</label>
                        <textarea name="comment" rows="3" required
                            class="w-full border rounded p-2"
                            placeholder="Share your experience with this product"></textarea>
                    </div>
                    
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Submit Review
                    </button>
                </form>
            </div>
        @else
            <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                <h4 class="font-medium text-green-800">You've reviewed this product</h4>
                <div class="flex mt-1">
                    @php
                        $review = $item->product->reviews->where('order_id', $order->id)->first();
                    @endphp
                    @for ($i = 1; $i <= 5; $i++)
                        <span class="{{ $i <= $review->rating ? 'text-yellow-500' : 'text-gray-300' }}">★</span>
                    @endfor
                </div>
                <p class="mt-2 text-gray-700">{{ $review->comment }}</p>
            </div>
        @endif
    @endif
@endforeach

                        {{-- Total Pembayaran --}}
                        <div class="order-total mt-4 font-bold text-lg text-right">
                            Total Payment: Rp {{ number_format($order->total_amount ?? 0, 0, ',', '.') }}
                        </div>
                        
                        {{-- Bukti Pembayaran --}}
                        @if($order->payment_proof)
                        <div class="payment-proof-section">
                            <h3 class="payment-proof-title">Payment Proof</h3>
                            <img 
                                src="{{ asset('storage/' . $order->payment_proof) }}" 
                                alt="Payment Proof" 
                                class="payment-proof-image"
                            >
                            <p class="text-sm mt-2 text-gray-600">
                                Uploaded at {{ $order->updated_at->format('d M Y, H:i') }}
                            </p>
                            
                            @if($order->status === 'pending_verification')
                            <div class="payment-actions">
                                <button 
                                    class="btn btn-primary"
                                    onclick="verifyPayment('{{ $order->id }}')">
                                    Verify Payment
                                </button>
                                <button 
                                    class="btn btn-secondary"
                                    onclick="rejectPayment('{{ $order->id }}')">
                                    Reject Payment
                                </button>
                            </div>
                            @endif
                        </div>
                        @endif

                        @if (!in_array($order->status, ['pending', 'pending_verification', 'processing', 'cancelled']))
                            <div class="mt-4 p-4 bg-[#fffcea] border border-gray-200 rounded-lg text-sm text-gray-800">
                                <h3 class="font-semibold text-base text-[#212842] mb-2">Shipping Information</h3>
                                <p class="mb-1"><span class="font-medium">Shipping Provider:</span>
                                    {{ $order->shipping_provider ?? '-' }}</p>
                                <p class="mb-1"><span class="font-medium">Estimated Delivery:</span>
                                    {{ $order->estimated_delivery ? \Carbon\Carbon::parse($order->estimated_delivery)->format('d M Y') : '-' }}
                                </p>

                                @if ($order->notes)
                                    <p class="mb-1"><span class="font-medium">Note:</span> {{ $order->notes }}</p>
                                @endif

                                @if ($order->status === 'delivered' && $order->delivered_at)
                                    <p class="mt-2 text-green-700"><span class="font-medium">Delivered At:</span>
                                        {{ \Carbon\Carbon::parse($order->delivered_at)->format('d M Y, H:i') }}</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </article>
            @endforeach
        @else
            <div class="no-orders">
                You haven't made any orders yet. Go shopping now!
            </div>
        @endif
    </section>

    <script>
        function printOrder(orderId) {
            const content = document.querySelector(`[data-order-id="${orderId}"]`);
            if (content) {
                const printWindow = window.open('', '', 'width=900,height=600');
                printWindow.document.write('<html><head><title>Order Print</title>');
                printWindow.document.write(
                    '<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">'
                    );
                printWindow.document.write('</head><body class="p-6">');
                printWindow.document.write(content.innerHTML);
                printWindow.document.write('</body></html>');
                printWindow.document.close();
                printWindow.print();
            }
        }
        
        function verifyPayment(orderId) {
            fetch(`/orders/${orderId}/verify`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ action: 'verify' })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred');
            });
        }
        
        function rejectPayment(orderId) {
            if (confirm('Are you sure you want to reject this payment?')) {
                fetch(`/orders/${orderId}/verify`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ action: 'reject' })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred');
                });
            }
        }
    </script>

</body>
</html>