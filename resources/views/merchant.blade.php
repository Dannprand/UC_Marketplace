<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Merchant</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background: linear-gradient(180deg, #e0f3fe 70%, #a1d4f6 100%);
            min-height: 100vh;
        }
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1.25rem;
        }
        .merchant-profile {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(4px);
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(143, 211, 248, 0.15);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .merchant-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(150, 194, 219, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #2d3436;
            font-weight: 700;
        }
        .merchant-info {
            flex-grow: 1;
        }
        .merchant-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2d3436;
            margin: 0;
        }
        .merchant-description {
            color: #636e72;
            margin-top: 0.25rem;
            font-size: 0.9rem;
        }
        .products-section {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(4px);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(143, 211, 248, 0.15);
            position: relative;
        }
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 1.2rem;
        }
        .product-card {
            background: rgba(255, 255, 255, 0.95);
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
            background-color: #e9ecef;
        }
        .product-details {
            padding: 0.75rem;
        }
        .product-name {
            font-weight: 600;
            font-size: 1rem;
            color: #2d3436;
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
            background: #2ecc71;
            color: white;
            border: none;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            font-size: 2rem;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transition: all 0.2s ease;
        }
        .add-product-btn:hover {
            background: #27ae60;
            transform: scale(1.05);
        }
        @media (min-width: 768px) {
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            }
            .product-image {
                height: 150px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Merchant Profile -->
        <div class="merchant-profile">
            <div class="merchant-avatar">M</div>
            <div class="merchant-info">
                <h2 class="merchant-name">Toko Maju Mundur</h2>
                <p class="merchant-description">Specializing in authentic Indonesian cuisine since 2020</p>
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
                        <p class="product-name">Rawon Daging</p>
                        <p class="product-price">Rp 40.000</p>
                    </div>
                </div>

                <!-- Product Card 2 -->
                <div class="product-card">
                    <img src="https://via.placeholder.com/200x120" alt="Product" class="product-image">
                    <div class="product-details">
                        <p class="product-name">Nasi Goreng</p>
                        <p class="product-price">Rp 25.000</p>
                    </div>
                </div>

                <!-- Product Card 3 -->
                <div class="product-card">
                    <img src="https://via.placeholder.com/200x120" alt="Product" class="product-image">
                    <div class="product-details">
                        <p class="product-name">Soto Ayam</p>
                        <p class="product-price">Rp 30.000</p>
                    </div>
                </div>

                <!-- More product cards -->
            </div>
        </div>
    </div>
</body>
</html>