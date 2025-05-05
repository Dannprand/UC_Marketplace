<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Merchant</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background-color: #f5f9fa;
        }
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1.25rem;
            
        }
        .merchant-profile {
            background: #ffffff;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .merchant-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: #e5edf1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #2d3748;
            font-weight: 700;
        }
        .merchant-info {
            flex-grow: 1;
        }
        .merchant-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2d3748;
            margin: 0;
        }
        .merchant-description {
            color: #718096;
            margin-top: 0.25rem;
            font-size: 0.9rem;
        }
        .products-section {
            background: #ffffff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            position: relative;
        }
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 1.2rem;
        }
        .product-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            overflow: hidden;
            transition: all 0.2s ease;
            display: flex;
            flex-direction: column;
        }
        .product-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        }
        .product-image {
            width: 100%;
            height: 120px;
            object-fit: cover;
            background-color: #e5edf1;
        }
        .product-details {
            padding: 0.75rem;
        }
        .product-name {
            font-weight: 600;
            font-size: 1rem;
            color: #2d3748;
            margin: 0 0 0.25rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .product-price {
            font-weight: 700;
            font-size: 1rem;
            color: #e74c3c;
            margin: 0;
        }
        .add-product-btn {
            position: absolute;
            top: -1.5rem;
            right: -1.5rem;
            background-color: #2b6cb0;
            color: white;
            border: none;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            font-size: 2rem;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transition: background 0.2s ease;
        }
        .add-product-btn:hover {
            background-color: #245a94;
        }
    </style>
</head>
<body>
    <div class="container">

        <!-- Merchant Profile -->
        <div class="merchant-profile">
            <div class="merchant-avatar">M</div>
            <div class="merchant-info">
                <h2 class="merchant-name">My Awesome Store</h2>
                <p class="merchant-description">Selling the best products just for you!</p>
            </div>
        </div>

        <!-- Products Section -->
        <div class="products-section">
            <button class="add-product-btn">+</button>
            <div class="products-grid">
                <!-- Product Card 1 -->
                <div class="product-card">
                    <img src="https://via.placeholder.com/200x120" alt="Product" class="product-image">
                    <div class="product-details">
                        <p class="product-name">Product 1</p>
                        <p class="product-price">$19.99</p>
                    </div>
                </div>

                <!-- Product Card 2 -->
                <div class="product-card">
                    <img src="https://via.placeholder.com/200x120" alt="Product" class="product-image">
                    <div class="product-details">
                        <p class="product-name">Product 2</p>
                        <p class="product-price">$29.99</p>
                    </div>
                </div>

                <!-- Product Card 3 -->
                <div class="product-card">
                    <img src="https://via.placeholder.com/200x120" alt="Product" class="product-image">
                    <div class="product-details">
                        <p class="product-name">Product 3</p>
                        <p class="product-price">$9.99</p>
                    </div>
                </div>

                <!-- More product cards -->
            </div>
        </div>

    </div>
</body>
</html>
