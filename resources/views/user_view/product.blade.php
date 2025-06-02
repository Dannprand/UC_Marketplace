<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>{{ $product->name }} - UCMarketPlace</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f0e7d5;
        }

        .product-container {
            display: flex;
            gap: 32px;
            max-width: 1200px;
            margin: 24px auto;
            padding: 0 20px;
        }

        .left-column {
            flex: 1;
            min-width: 380px;
        }

        .product-image {
            background-color: white;
            aspect-ratio: 1/1;
            border-radius: 12px;
            margin-bottom: 24px;
            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 12px;
        }

        .center-column {
            flex: 2;
            padding-top: 8px;
        }

        .right-column {
            flex: 1;
            min-width: 320px;
        }

        .product-title {
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 12px;
            color: #2d3436;
        }

        .product-price {
            font-size: 24px;
            font-weight: 700;
            color: #5363a0;
            margin-bottom: 24px;
        }

        .product-detail {
            background-color: white;
            padding: 24px;
            border-radius: 12px;
            margin-bottom: 24px;
        }

        .product-detail h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 16px;
            color: #2d3436;
        }

        .product-detail p {
            font-size: 14px;
            color: #636e72;
            line-height: 1.6;
            margin-bottom: 12px;
        }

        .seller-card {
            background-color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .seller-card h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .seller-card p {
            font-size: 14px;
            color: #636e72;
        }

        .seller-card img {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
        }

        .order-card {
            background-color: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }

        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 20px 0;
        }

        .quantity-btn {
            padding: 8px 16px;
            border: 1px solid #dee2e6;
            background-color: #f8f9fa;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .quantity-btn:hover {
            background-color: #e9ecef;
        }

        .quantity-selector input {
            width: 60px;
            text-align: center;
            padding: 8px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            font-size: 14px;
        }

        .buttons {
            display: flex;
            gap: 12px;
            margin-top: 24px;
        }

        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            flex: 1;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .add-to-cart {
            background-color: white;
            color: #212842;
            border: 2px solid #212842;
        }

        .add-to-cart:hover {
            background-color: #eaeeff;
            color: #212842;
            border-color: #212842;
        }

        .buy-now {
            background-color: #212842;
            color: white;
        }

        .buy-now:hover {
            background-color: #3b4b91;
        }

        .subtotal {
            font-size: 16px;
            margin: 20px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .subtotal div {
            font-weight: 600;
            color: #2d3436;
        }

        .cart-animation {
            animation: popIn 1s ease-out;
        }

        @keyframes popIn {
            0% { transform: scale(0.8); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
</head>
<body>
    <x-navigation></x-navigation>

<div class="pt-24">
    <div class="product-container">
        <!-- Left Column -->
        <div class="left-column">
            <div class="product-image">
                <img id="mainImage" src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}">
            </div>
            <div class="seller-card flex items-center gap-4">
                @if ($product->store && $product->store->logo)
                    <img src="{{ asset('storage/' . $product->store->logo) }}" alt="Store Logo" style="width: 48px; height: 48px; object-fit: cover; border-radius: 9999px;">
                @else
                    <div style="width: 48px; height: 48px; border-radius: 9999px; background-color: #dfe6e9;"></div>
                @endif

                <div>
                    <h3>{{ $product->store ? $product->store->name : 'No Merchant Name Available' }}</h3>
                    {{-- Bisa ditambah detail lainnya seperti rating store --}}
                </div>
            </div>

        </div>
    
        <!-- Center Column -->
        <div class="center-column">
            <div class="product-detail">
                <h1 class="product-title">{{ $product->name }}</h1>
                <div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                <h3>Product Details</h3>
                <p>{{ $product->description }}</p>
            </div>
        </div>
    
        <!-- Right Column -->
        <div class="right-column">
            <div class="order-card">
                <h3 style="font-size: 16px; margin-bottom: 24px;">Order Settings</h3>
                <div class="quantity-selector">
                    <button class="quantity-btn" onclick="updateQuantity(-1)">-</button>
                    <input type="number" id="quantityInput" value="1" min="1" max="10" onchange="updateSubtotal()">
                    <button class="quantity-btn" onclick="updateQuantity(1)">+</button>
                </div>
                <div class="subtotal">
                    <span>Subtotal:</span>
                    <div id="subtotalDisplay" class="text-[#5363a0]">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                </div>
                <div class="buttons">
                    @auth
                        <form method="POST" action="{{ route('cart.add', $product->id) }}">
                            @csrf
                            <input type="hidden" id="formQuantity" name="quantity" value="1"> 
                            <button type="submit" class="btn add-to-cart">Add to Cart</button>
                        </form>
                    
                        <form method="GET" action="{{ route('buy.now') }}">
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" id="formQuantityBuyNow" value="1">
                            <button type="submit" class="btn buy-now">Buy Now</button>
                        </form>
                    @endauth

                    @guest
                        <button class="btn add-to-cart" onclick="window.location.href='{{ route('login') }}'">Add to Cart</button>
                        <button class="btn buy-now" onclick="window.location.href='{{ route('login') }}'">Buy Now</button>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const unitPrice = {{ $product->price }};
    const quantityInput = document.getElementById('quantityInput');
    const subtotalDisplay = document.getElementById('subtotalDisplay');

    function updateQuantity(change) {
        let qty = parseInt(quantityInput.value);
        qty += change;
        if (qty < 1) qty = 1;
        if (qty > 10) qty = 10;
        quantityInput.value = qty;
        updateSubtotal();
        syncQuantityToForms();
    }

    function updateSubtotal() {
        let qty = parseInt(quantityInput.value);
        let subtotal = qty * unitPrice;
        subtotalDisplay.innerText = "Rp " + subtotal.toLocaleString('id-ID');
    }

    function syncQuantityToForms() {
        document.getElementById('formQuantity').value = quantityInput.value;
        document.getElementById('formQuantityBuyNow').value = quantityInput.value;
    }

    quantityInput.addEventListener('input', () => {
        updateSubtotal();
        syncQuantityToForms();
    });
</script>


</body>
</html>
