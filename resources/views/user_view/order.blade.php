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
            font-family: 'Poppins', sans-serif;
            background: #f0e7d5;
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
                        'processing' => 'status-processing',
                        'shipped' => 'status-shipped',
                        'delivered' => 'status-delivered',
                        'cancelled' => 'status-cancelled',
                        default => '',
                    };
                @endphp

                <article class="order-card" data-order-id="{{ $order->id }}">
                    <header class="order-header">
                        <div class="order-id">Order #{{ $order->id }}</div>
                        <div class="order-date">{{ $order->created_at->format('d M Y, H:i') }}</div>
                    </header>

                    <div class="order-status {{ $statusClass }}">
                        {{ ucfirst($order->status) }}
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
                        @endforeach

                        {{-- Total Pembayaran Ditampilkan Sekali Saja --}}
                        <div class="order-total mt-4 font-bold text-lg text-right">
                            Total Payment: Rp {{ number_format($order->total_amount ?? 0, 0, ',', '.') }}
                        </div>

                        @if (!in_array($order->status, ['pending', 'processing', 'cancelled']))
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
    </script>

</body>

</html>
