<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>Product Detail - UCMarketPlace</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(
                180deg,
                #e0f3fe 70%,
                #a1d4f6 100%
            );
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
            color: #e74c3c;
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
            color: #2ecc71;
            border: 2px solid #2ecc71;
        }

        .add-to-cart:hover {
            background-color: #e8f8f0;
            color: #27ae60;
            border-color: #27ae60;
        }

        .buy-now {
            background-color: #2ecc71;
            color: white;
        }

        .buy-now:hover {
            background-color: #27ae60;
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
    </style>
</head>
<body>
    <x-navigation></x-navigation> 

<div class="pt-24">
    <div class="product-container">
        <!-- Left Column -->
        <div class="left-column">
            <div class="product-image"></div>
            <div class="seller-card">
                <h3>Moving Forward Store</h3>
                <p>Seller Rating: ★★★★☆ (4.2/5)</p>
            </div>
        </div>

        <!-- Center Column -->
        <div class="center-column">
            <h1 class="product-title">Beef Rawon Soup</h1>
            <div class="product-price">Rp 40,000</div>
            
            <div class="product-detail">
                <h3>Product Details</h3>
                <p>Authentic East Javanese beef rawon soup made with premium quality beef and traditional spices. Slow-cooked for 4 hours to enhance flavor.</p>
                <p>Ingredients: Beef, keluak nuts, garlic, shallots, ginger, turmeric, coriander, and other natural spices.</p>
                <p>Net weight: 500g</p>
                <p>⚠️ Contains nuts ⚠️</p>
            </div>
        </div>

        <!-- Right Column -->
        <div class="right-column">
            <div class="order-card">
                <h3 style="font-size: 16px; margin-bottom: 24px;">Order Settings</h3>
                <div class="quantity-selector">
                    <button class="quantity-btn">-</button>
                    <input type="number" value="1" min="1" max="10">
                    <button class="quantity-btn">+</button>
                </div>
                <div class="subtotal">
                    <span>Subtotal:</span>
                    <div>Rp 40,000</div>
                </div>
                <div class="buttons">
                    <button class="btn add-to-cart">Add to Cart</button>
                    <button class="btn buy-now" onclick="window.location.href='{{ route('payment') }}'">Buy Now</button>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>