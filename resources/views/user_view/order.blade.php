<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Order - UCMarketPlace</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
       body {
            font-family: 'Poppins', sans-serif;
             background: #e0f3fe;
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
            color: #2b6cb0;
        }

        .order-date {
            color: #4a5568;
            font-size: 0.9rem;
        }

        .order-status {
            display: inline-block;
            background-color: #bee3f8;
            color: #2b6cb0;
            font-weight: 600;
            font-size: 0.75rem;
            padding: 0.2rem 0.7rem;
            border-radius: 12px;
            text-transform: uppercase;
            margin-bottom: 1rem;
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
            background: #faf089;
            color: #975a16;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<bod>

    <x-navigation></x-navigation>

    <section class="order-section">

        <h1 class="text-2xl font-bold text-gray-800 mb-8">Order History</h1>

        @if($orders->count() > 0)
            @foreach($orders as $order)
                <article class="order-card" data-order-id="{{ $order->id }}">
                    <header class="order-header">
                        <div class="order-id">Order #{{ $order->id }}</div>
                        <div class="order-date">{{ $order->created_at->format('d M Y, H:i') }}</div>
                    </header>

                    <div class="order-status">
                        {{ ucfirst($order->status) }}
                    </div>

                    <div class="order-items">
                        @foreach($order->items as $item)
                            <div class="order-item">
                                <div>
                                    <p class="product-name">{{ $item->product->name }} x{{ $item->quantity }}</p>
                                    @if($item->product->store && $item->product->store->merchant)
                                        <p class="product-store">From: {{ $item->product->store->merchant->merchant_name }}</p>
                                    @endif
                                </div>

                                {{-- Tampilkan total pembayaran user dari order --}}
                                <div class="payment-amount">
                                    Rp {{ number_format($order->total_payment ?? 0, 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
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
                printWindow.document.write('<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">');
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
