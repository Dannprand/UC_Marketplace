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
            display: flex;
            align-items: center;
            gap: 1.5rem;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(143, 211, 248, 0.15);
            position: relative;
            margin-bottom: 3.5rem
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
        .details-btn {
        margin-left: auto;
        background: #2ecc71;
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .details-btn:hover {
        background: #27ae60;
        transform: translateY(-1px);
    }

    .details-btn svg {
        transition: transform 0.2s ease;
    }

    .details-btn:hover svg {
        transform: translateX(2px);
    }

    @media (max-width: 640px) {
        .merchant-profile {
            flex-wrap: wrap;
        }
        .details-btn {
            width: 100%;
            justify-content: center;
            margin-left: 0;
            margin-top: 1rem;
        }
    }
    </style>
</head>
<body>
   <x-navigation> </x-navigation>
    
<div class="pt-20">
    <div class="container">
        <!-- Merchant Profile -->
        <div class="merchant-profile">
            <div class="merchant-avatar">M</div>
            <div class="merchant-info">
                <h2 class="merchant-name">Toko Maju Mundur</h2>
                <p class="merchant-description">Specializing in authentic Indonesian cuisine since 2020</p>
            </div>
            <a href="{{ route('detailMerchant') }}" class="details-btn">
                Details
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </a>
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

</div>

</body>
</html>