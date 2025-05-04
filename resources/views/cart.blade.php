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
            display: flex;
            background: white;
            padding: 20px;
            margin-bottom: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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

        .product-image-container {
            width: 100px;
            height: 100px;
            flex-shrink: 0;
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 6px;
        }

        .item-details {
            flex-grow: 1;
        }

        .seller-name {
            font-size: 14px;
            color: #666;
            margin-top: 8px;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .quantity-btn {
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 6px;
            background: #2ecc71;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .quantity-btn:hover {
            background: #27ae60;
        }

        .quantity-input {
            width: 50px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 5px;
            font-size: 14px;
        }

         /* Remove number input arrows */
         input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
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
            <!-- Example Cart Item -->
            <div class="cart-item">
                <div class="product-image-container">
                    <img src="https://via.placeholder.com/100x100" alt="Product" class="product-image">
                </div>
               <div class="item-details">
                    <h3>Rawon Daging</h3>
                    <p>Rp 40.000</p>
                    <div class="seller-name">Toko Maju Mundur</div>
                </div>
                <div class="quantity-control">
                    <button class="quantity-btn minus">-</button>
                    <input type="number" class="quantity-input" value="1" min="1">
                    <button class="quantity-btn plus">+</button>
                </div>
            </div>

            <!-- Repeat other items with same structure -->
            <div class="cart-item">
                <div class="product-image-container">
                    <img src="https://via.placeholder.com/100x100" alt="Product" class="product-image">
                </div>
                <div class="item-details">
                    <h3>Rawon Daging</h3>
                    <p>Rp 40.000</p>
                    <div class="seller-name">Toko Maju Mundur</div>
                </div>
                <div class="quantity-control">
                    <button class="quantity-btn minus">-</button>
                    <input type="number" class="quantity-input" value="1" min="1">
                    <button class="quantity-btn plus">+</button>
                </div>
            </div>

            <!-- Add more items following the same pattern -->
        </div>


        <!-- Fixed Total Section (25%) -->
        <div class="total-section">
            <div class="total-price">Total: Rp 160.000</div>
            <button class="buy-button" onclick="window.location.href='{{ route('payment') }}'">Buy Now</button>
        </div>
    </div>

    <script>
        // Quantity control functionality
        document.querySelectorAll('.quantity-btn').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentNode.querySelector('.quantity-input');
                let value = parseInt(input.value);

                if (this.classList.contains('minus')) {
                    value = Math.max(1, value - 1);
                } else if (this.classList.contains('plus')) {
                    value++;
                }

                input.value = value;
            });
        });
    </script>
    
</body>
</html>