<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Merchant Transactions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f0e7d5;
            margin: 0;
            padding: 0;
        }

        .transactions-section {
            max-width: 1100px;
            margin: 8rem auto;
            padding: 0 1rem;
        }

        .transaction-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            margin-bottom: 1.5rem;
            transition: 0.3s;
        }

        .transaction-card:hover {
            border-color: #cbd5e0;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
        }

        .transaction-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .order-info {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .order-id {
            font-weight: 700;
            font-size: 1.25rem;
            color: #680303;
        }

        .order-date {
            font-size: 0.85rem;
            color: #718096;
        }

        .status-dropdown {
            padding: 0.3rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 9999px;
            text-transform: uppercase;
            border: none;
            cursor: pointer;
        }

        .status-dropdown.pending {
            background-color: #fff5f5;
            color: #e53e3e;
        }

        .status-dropdown.processing {
            background-color: #fefcbf;
            color: #b7791f;
        }

        .status-dropdown.shipped {
            background-color: #bee3f8;
            color: #3182ce;
        }

        .status-dropdown.delivered {
            background-color: #c6f6d5;
            color: #2f855a;
        }

        .status-dropdown.cancelled {
            background-color: #e2e8f0;
            color: #4a5568;
        }

        .buyer-info {
            font-size: 1rem;
            color: #212842;
            margin-bottom: 0.75rem;
        }

        .items-list {
            border-top: 1px dashed #cbd5e0;
            padding-top: 1rem;
        }

        .item {
            display: flex;
            justify-content: space-between;
            padding: 0.6rem 0;
            border-bottom: 1px dashed #edf2f7;
        }

        .item:last-child {
            border-bottom: none;
        }

        .item-name {
            font-weight: 600;
        }

        .item-details {
            font-size: 0.85rem;
            color: #718096;
        }

        .item-price {
            font-weight: 700;
            color: #212842;
            white-space: nowrap;
        }

        .total-section {
            text-align: right;
            margin-top: 1rem;
            font-weight: 700;
            color: #5363a0;
            font-size: 1.05rem;
        }

        .no-transactions {
            max-width: 600px;
            margin: 5rem auto;
            background: #fffbea;
            border: 1px solid #f6e05e;
            color: #744210;
            padding: 1rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            text-align: center;
        }
    </style>
</head>
<body>
    <x-navigation />

    <section class="transactions-section">
        <a href="{{ route('merchant.dashboard') }}" class="text-black font-medium hover:font-semibold">&larr; Back to Merchant</a>
        @if ($orders->count() > 0)
            <h1 class="text-2xl font-bold text-[#212842] mb-6">Store Transactions</h1>
            @foreach ($orders as $order)
                <div class="transaction-card">
                    <div class="transaction-top">
                        <div class="order-info">
                            <div class="order-id">Order #{{ $order->id }}</div>
                            <div class="order-date">{{ $order->created_at->format('d M Y, H:i') }}</div>
                        </div>

                        <div>
                            <form action="{{ route('merchant.orders.updateStatus', $order->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <select name="status" onchange="this.form.submit()" class="status-dropdown {{ $order->status }}">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </form>
                        </div>
                    </div>

                    <div class="buyer-info">
                        <strong>{{ $order->user->full_name }}</strong>
                    </div>

                    <div class="items-list">
                        @foreach ($order->items as $item)
                            <div class="item">
                                <div>
                                    <div class="item-name">{{ $item->product->name }}</div>
                                    <div class="item-details">Quantity: {{ $item->quantity }} × Rp {{ number_format($item->unit_price, 0, ',', '.') }}</div>
                                </div>
                                <div class="item-price">Rp {{ number_format($item->total_price, 0, ',', '.') }}</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="total-section">
                        Total: Rp {{ number_format($order->total_amount ?? 0, 0, ',', '.') }}
                    </div>
                    {{-- Shipping Info --}}
                    @if ($order->shipping_provider && $order->tracking_number)
                        <div class="mt-4 p-4 bg-[#fffcea] border border-gray-200 rounded-lg text-sm text-gray-800">
                                <h3 class="font-semibold text-base text-[#212842] mb-2">Shipping Information</h3>
                                <p class="mb-1"><span class="font-semibold">Shipping Provider:</span>
                                    {{ $order->shipping_provider ?? '-' }}</p>
                                <p class="mb-1"><span class="font-semibold">Estimated Delivery:</span>
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
                    @else
                        <div class="mt-4">
                            <a href="{{ route('merchant.orders.shipping', $order->id) }}"
                            class="inline-block bg-[#212842] text-white px-4 py-2 rounded-md text-sm font-semibold hover:bg-[#3b4b91]">
                                Input Shipping Info
                            </a>
                        </div>
                    @endif

                </div>
            @endforeach
        @else
            <div class="no-transactions">
                You currently have no transactions for your store.
            </div>
        @endif
    </section>
</body>
</html>
