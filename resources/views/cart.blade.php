<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>Cart - UCMarketPlace</title>
    <style>
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
            overflow: hidden
        }

        .main-container {
            display: grid;
            grid-template-columns: 75% 25%;
            grid-template-rows: auto 1fr;
            height: calc(100vh - 80px); /* Adjust based on navigation height */
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            padding: 20px;
            gap: 20px;
        }

        .cart-header {
            grid-column: 1 / -1;
            padding: 20px;
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
            font-weight: 600;
            border-bottom: 2px solid black; 
            position: relative;
            padding-bottom: 15px;
        }

        /* Items Section (75%) */
        .items-section {
            overflow-y: auto;
            padding-right: 15px;
            height: 100%; 
        }

        .cart-item {
            background: white;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .cart-item h3 {
            font-size: 16px;
            color: #333;
            margin-bottom: 5px;
            font-weight: 600; 
        }

        .cart-item p {
            font-size: 16px;
            color: #e74c3c;
            margin-bottom: 5px; 
        }

        /* Total Section (25%) */
        .total-section {
            position: sticky;
            top: 20px;
            height: fit-content; 
            background: white;
            padding: 10%;
            border-radius: 8px;
        }

        .total-price {
            font-size: 20px;
            font-weight: bold;
            color: #e74c3c;
            margin-bottom: 20px;
        }

        .buy-button {
            background: #2ecc71;
            color: white;
            border: none;
            width: 100%;
            padding: 15px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }

        .buy-button:hover {
            background: #27ae60;
        }

    </style>
</head>
<body>
    <x-navigation></x-navigation>

    <div class="main-container">
        <div class="cart-header">Cart</div>
        
        <!-- Scrollable Items (75%) -->
        <div class="items-section">
            <div class="cart-item">
                <h3>Rawon Daging</h3>
                <p>Rp 40.000</p>
            </div>
            <!-- Repeat items as needed -->
            <div class="cart-item">
                <h3>Rawon Daging</h3>
                <p>Rp 40.000</p>
            </div>
            <div class="cart-item">
                <h3>Rawon Daging</h3>
                <p>Rp 40.000</p>
            </div>
            <div class="cart-item">
                <h3>Rawon Daging</h3>
                <p>Rp 40.000</p>
            </div>
            <!-- Repeat items as needed -->
            <div class="cart-item">
                <h3>Rawon Daging</h3>
                <p>Rp 40.000</p>
            </div>
            <div class="cart-item">
                <h3>Rawon Daging</h3>
                <p>Rp 40.000</p>
            </div>
            <div class="cart-item">
                <h3>Rawon Daging</h3>
                <p>Rp 40.000</p>
            </div>
            <!-- Repeat items as needed -->
            <div class="cart-item">
                <h3>Rawon Daging</h3>
                <p>Rp 40.000</p>
            </div>
            <div class="cart-item">
                <h3>Rawon Daging</h3>
                <p>Rp 40.000</p>
            </div>
            <!-- Add more items here -->
        </div>

        <!-- Fixed Total Section (25%) -->
        <div class="total-section">
            <div class="total-price">Total: Rp 160.000</div>
            <button class="buy-button">Buy Now</button>
        </div>
    </div>
</body>
</html>