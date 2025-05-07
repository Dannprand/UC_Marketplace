<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>Order - UCMarketPlace</title>
</head>

<body>
    <x-navigation></x-navigation>
    @extends('layouts.app')

@section('content')
<div class="pt-16 px-4 md:px-10 min-h-screen bg-gradient-to-b from-blue-50 via-blue-100 to-blue-200">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">🧾 Order History</h1>

    @if($orders->count() > 0)
        <div class="grid gap-6">
            @foreach($orders as $order)
                <div class="bg-white p-6 rounded-2xl shadow-lg transition-transform transform hover:scale-[1.01]">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-3">
                        <h2 class="text-xl font-semibold text-blue-800">Order #{{ $order->id }}</h2>
                        <span class="text-sm text-gray-600 mt-2 md:mt-0">
                            {{ $order->created_at->format('d M Y, H:i') }}
                        </span>
                    </div>

                    <div class="text-sm mb-4">
                        <span class="font-medium text-gray-700">Status:</span>
                        <span class="inline-block px-2 py-1 rounded bg-blue-100 text-blue-700 text-xs uppercase font-semibold">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>

                    <div class="divide-y divide-gray-200 mb-4">
                        @foreach($order->items as $item)
                            <div class="py-2 flex justify-between text-sm">
                                <div>
                                    <p class="font-medium text-gray-800">{{ $item->product->name }} x{{ $item->quantity }}</p>
                                    @if($item->product->merchant)
                                        <p class="text-xs text-gray-500">From: {{ $item->product->merchant->store_name }}</p>
                                    @endif
                                </div>
                                <div class="text-gray-700">
                                    Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex justify-between items-center">
                        <div class="text-lg font-bold text-green-600">
                            Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('orders.invoice', $order->id) }}" 
                               class="bg-blue-600 text-white text-sm px-3 py-1.5 rounded hover:bg-blue-700 transition">
                                Download Invoice
                            </a>
                            <button onclick="printOrder({{ $order->id }})"
                               class="bg-gray-700 text-white text-sm px-3 py-1.5 rounded hover:bg-gray-800 transition">
                                Print
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-yellow-100 text-yellow-800 px-4 py-3 rounded shadow text-center">
            You haven't made any orders yet. Go shopping now!
        </div>
    @endif
</div>

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
@endsection

    
</body>

</html>