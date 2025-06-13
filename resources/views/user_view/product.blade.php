<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>{{ $product->name }} - UCMarketPlace</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }

        body {
            background: #f9f9f9;
            color: #333;
            padding-top: 80px; /* Space for fixed navigation */
        }

        /* Fixed Navigation Styles */
        .navigation {
            background-color: #212842;
            color: white;
            padding: 15px 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            color: white;
        }

        .nav-links {
            display: flex;
            gap: 25px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }

        .nav-links a:hover {
            color: #aab7ff;
        }

        .nav-icons {
            display: flex;
            gap: 20px;
            font-size: 20px;
        }

        .nav-icons a {
            color: white;
            transition: all 0.3s;
        }

        .nav-icons a:hover {
            color: #aab7ff;
        }

        .product-container {
            display: flex;
            flex-wrap: wrap;
            gap: 32px;
            max-width: 1200px;
            margin: 0 auto 40px;
            padding: 20px;
        }

        .left-column {
            flex: 1;
            min-width: 380px;
            max-width: 100%;
        }

        .product-image {
            background-color: white;
            aspect-ratio: 1/1;
            border-radius: 12px;
            margin-bottom: 24px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #eee;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 15px;
            transition: transform 0.3s ease;
        }

        .product-image img:hover {
            transform: scale(1.05);
        }

        .seller-card {
            background-color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 24px;
            border: 1px solid #eee;
            transition: all 0.3s;
        }

        .seller-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .seller-card img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #f0f0f0;
        }

        .seller-info h3 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 4px;
            color: #212842;
        }

        .seller-info p {
            font-size: 14px;
            color: #636e72;
        }

        .center-column {
            flex: 2;
            min-width: 300px;
        }

        .right-column {
            flex: 1;
            min-width: 320px;
        }

        .product-detail {
            background-color: white;
            padding: 28px;
            border-radius: 12px;
            margin-bottom: 24px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border: 1px solid #eee;
        }

        .product-title {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 15px;
            color: #212842;
        }

        .price-container {
            display: flex;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 25px;
        }

        .original-price {
            text-decoration: line-through;
            color: #999;
            font-size: 20px;
        }

        .discounted-price {
            font-size: 28px;
            font-weight: 700;
            color: #e74c3c;
        }

        .discount-badge {
            background: #e74c3c;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 16px;
            font-weight: 600;
        }

        .product-detail h3 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #212842;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }

        .product-detail p {
            font-size: 16px;
            color: #555;
            line-height: 1.8;
            margin-bottom: 12px;
        }

        .order-card {
            background-color: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            position: sticky;
            top: 100px;
            border: 1px solid #eee;
        }

        .order-card h3 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
            color: #212842;
        }

        .pricing-info {
            margin-bottom: 20px;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 16px;
        }

        .price-label {
            color: #666;
        }

        .price-value {
            font-weight: 500;
        }

        .discount-row {
            display: flex;
            justify-content: space-between;
            background: #fef2f2;
            padding: 8px 12px;
            border-radius: 8px;
            margin: 15px 0;
        }

        .discount-label {
            color: #e74c3c;
        }

        .discount-value {
            color: #e74c3c;
            font-weight: 600;
        }

        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 25px 0;
        }

        .quantity-btn {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #dee2e6;
            background-color: #f8f9fa;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 18px;
            font-weight: 500;
        }

        .quantity-btn:hover {
            background-color: #e9ecef;
        }

        .quantity-selector input {
            width: 70px;
            text-align: center;
            padding: 10px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
        }

        .subtotal {
            font-size: 18px;
            margin: 25px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
        }

        .subtotal-label {
            font-weight: 500;
            color: #555;
        }

        .subtotal-value {
            font-weight: 700;
            color: #212842;
            font-size: 22px;
        }

        .buttons {
            display: flex;
            gap: 15px;
            margin-top: 25px;
        }

        .btn {
            padding: 16px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            flex: 1;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .add-to-cart {
            background-color: white;
            color: #212842;
            border: 2px solid #212842;
        }

        .add-to-cart:hover {
            background-color: #f0f4ff;
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(33, 40, 66, 0.15);
            border-color: #5363a0;
            color: #5363a0;
        }

        .buy-now {
            background-color: #212842;
            color: white;
        }

        .buy-now:hover {
            background-color: #3b4b91;
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(33, 40, 66, 0.25);
        }

        .review-section {
            background-color: white;
            padding: 28px;
            border-radius: 12px;
            margin-top: 24px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border: 1px solid #eee;
        }

        .review-section h2 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 24px;
            color: #212842;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .review-summary {
            display: flex;
            align-items: center;
            gap: 30px;
            margin-bottom: 30px;
            padding: 20px;
            background: #f9fafb;
            border-radius: 10px;
            border: 1px solid #eee;
        }

        .average-rating {
            font-size: 48px;
            font-weight: 700;
            color: #212842;
        }

        .rating-stars {
            display: flex;
            gap: 3px;
            margin-bottom: 8px;
        }

        .star {
            color: #ffc107;
            font-size: 24px;
        }

        .rating-count {
            font-size: 16px;
            color: #666;
        }

        .review-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            max-height: 400px;
            overflow-y: auto;
            padding-right: 10px;
        }

        /* Scrollbar styling */
        .review-grid::-webkit-scrollbar {
            width: 8px;
        }

        .review-grid::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .review-grid::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        .review-grid::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        .review-card {
            padding: 20px;
            border-radius: 10px;
            background: #f9fafb;
            border: 1px solid #eee;
            transition: all 0.3s;
        }

        .review-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .reviewer-name {
            font-weight: 600;
            color: #212842;
            font-size: 17px;
        }

        .review-date {
            font-size: 14px;
            color: #777;
        }

        .review-rating {
            margin-bottom: 12px;
        }

        .review-content {
            font-size: 16px;
            color: #555;
            line-height: 1.6;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 30px;
            gap: 8px;
        }

        .pagination a, .pagination span {
            display: inline-block;
            padding: 8px 16px;
            background: #f0f0f0;
            border-radius: 6px;
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: all 0.2s;
        }

        .pagination a:hover {
            background: #212842;
            color: white;
        }

        .pagination .active {
            background: #212842;
            color: white;
        }

        .review-form {
            margin-top: 30px;
            padding: 20px;
            background: #f9fafb;
            border-radius: 10px;
            border: 1px solid #eee;
        }

        .form-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #212842;
        }

        .rating-input {
            display: flex;
            gap: 5px;
            margin-bottom: 20px;
            flex-direction: row-reverse;
            justify-content: flex-end;
        }

        .rating-input input {
            display: none;
        }

        .rating-input label {
            font-size: 28px;
            color: #ddd;
            cursor: pointer;
            transition: color 0.2s;
        }

        .rating-input input:checked ~ label,
        .rating-input label:hover,
        .rating-input label:hover ~ label {
            color: #ffc107;
        }

        .rating-input input:checked + label {
            color: #ffc107;
        }

        textarea {
            width: 100%;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            min-height: 120px;
            margin-bottom: 15px;
            font-size: 15px;
            resize: vertical;
        }

        .submit-review {
            background: #212842;
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s;
            font-size: 16px;
        }

        .submit-review:hover {
            background: #3b4b91;
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            background: #4CAF50;
            color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 10px;
            transform: translateX(150%);
            transition: transform 0.3s ease;
        }

        .notification.show {
            transform: translateX(0);
        }

        @media (max-width: 992px) {
            .product-container {
                flex-direction: column;
            }
            
            .left-column, .center-column, .right-column {
                width: 100%;
                max-width: 100%;
            }
            
            .product-image {
                max-width: 500px;
                margin: 0 auto 24px;
            }
            
            .buttons {
                flex-direction: column;
            }
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
            
            .review-summary {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            
            .rating-stars {
                justify-content: center;
            }
            
            .product-title {
                font-size: 24px;
            }

            .nav-icons {
                gap: 15px;
            }
        }

        .animate-pop {
            animation: popIn 0.5s ease-out;
        }

        @keyframes popIn {
            0% { transform: scale(0.9); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
</head>
<body>
    <x-navigation></x-navigation>

    <div class="product-container">
        <!-- Left Column -->
        <div class="left-column">
            <div class="product-image animate__animated animate__fadeIn">
                <img id="mainImage" src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}">
            </div>
            <div class="seller-card">
                @if ($product->store && $product->store->logo)
                    <img src="{{ asset('storage/' . $product->store->logo) }}" alt="Store Logo">
                @else
                    <div style="width: 60px; height: 60px; border-radius: 50%; background-color: #dfe6e9; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-store text-gray-500"></i>
                    </div>
                @endif
                <div class="seller-info">
                    <h3>{{ $product->store ? $product->store->name : 'Store Not Available' }}</h3>
                    <p>Trusted Seller</p>
                </div>
            </div>
        </div>
    
        <!-- Center Column -->
        <div class="center-column">
            <div class="product-detail animate__animated animate__fadeInUp">
                <h1 class="product-title">{{ $product->name }}</h1>
                
                <div class="price-container">
                    @if($product->is_discounted)
                        <div class="original-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        <div class="discounted-price">Rp {{ number_format($product->price * (1 - $product->discount_percentage / 100), 0, ',', '.') }}</div>
                        <div class="discount-badge">{{ $product->discount_percentage }}% OFF</div>
                    @else
                        <div class="discounted-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                    @endif
                </div>
                
                <h3>Product Details</h3>
                <p>{{ $product->description }}</p>
            </div>
            
            <!-- Review Section -->
            <div class="review-section">
                <h2><i class="fas fa-star"></i> Customer Reviews</h2>
                
                <div class="review-summary">
                    <div class="average-rating">
                        {{ $product->average_rating }}
                    </div>
                    <div>
                        <div class="rating-stars">
                            @php
                                $fullStars = floor($product->average_rating);
                                $halfStar = ($product->average_rating - $fullStars) >= 0.5;
                                $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                            @endphp
                            
                            @for ($i = 0; $i < $fullStars; $i++)
                                <span class="star">★</span>
                            @endfor
                            
                            @if ($halfStar)
                                <span class="star">★½</span>
                            @endif
                            
                            @for ($i = 0; $i < $emptyStars; $i++)
                                <span class="star" style="color: #ddd;">★</span>
                            @endfor
                        </div>
                        <div class="rating-count">
                            Based on {{ $product->review_count }} reviews
                        </div>
                    </div>
                </div>
                
                @if($reviews->count() > 0)
                    <div class="review-grid">
                        @foreach ($reviews as $review)
                            <div class="review-card animate__animated animate__fadeIn">
                                <div class="review-header">
                                    <div class="reviewer-name">{{ $review->user->full_name }}</div>
                                    <div class="review-date">{{ $review->created_at->format('d M Y') }}</div>
                                </div>
                                
                                <div class="review-rating">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span class="star">{{ $i <= $review->rating ? '★' : '☆' }}</span>
                                    @endfor
                                </div>
                                
                                <div class="review-content">
                                    {{ $review->comment }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if ($reviews->hasPages())
                        <div class="pagination mt-6">
                            {{ $reviews->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-10">
                        <p class="text-gray-500">No reviews yet. Be the first to review this product!</p>
                    </div>
                @endif
                
                @if($canReview && !$hasReviewed)
                    <div class="review-form">
                        <div class="form-title">Write Your Review</div>
                        <form id="reviewForm" action="{{ route('reviews.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <div class="rating-input">
                                <input type="radio" id="star5" name="rating" value="5">
                                <label for="star5">★</label>
                                <input type="radio" id="star4" name="rating" value="4">
                                <label for="star4">★</label>
                                <input type="radio" id="star3" name="rating" value="3">
                                <label for="star3">★</label>
                                <input type="radio" id="star2" name="rating" value="2">
                                <label for="star2">★</label>
                                <input type="radio" id="star1" name="rating" value="1">
                                <label for="star1">★</label>
                            </div>
                            <textarea name="comment" placeholder="Share your experience using this product..." required></textarea>
                            <button type="submit" class="submit-review">Submit Review</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    
        <!-- Right Column -->
        <div class="right-column">
            <div class="order-card animate__animated animate__fadeInRight">
                <h3>Order Settings</h3>
                
                @if($product->is_discounted)
                    <div class="pricing-info">
                        <div class="price-row">
                            <span class="price-label">Original Price:</span>
                            <span class="price-value">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="discount-row">
                            <span class="discount-label">Discount:</span>
                            <span class="discount-value">{{ $product->discount_percentage }}% OFF</span>
                        </div>
                        
                        <div class="price-row">
                            <span class="price-label">Discounted Price:</span>
                            <span class="price-value font-bold text-xl text-[#e74c3c]">
                                Rp {{ number_format($product->price * (1 - $product->discount_percentage / 100), 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                @else
                    <div class="price-row">
                        <span class="price-label">Price:</span>
                        <span class="price-value font-bold text-xl text-[#5363a0]">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </span>
                    </div>
                @endif
                
                <div class="quantity-selector">
                    <button class="quantity-btn" onclick="updateQuantity(-1)">-</button>
                    <input type="number" id="quantityInput" value="1" min="1" max="10" onchange="updateSubtotal()">
                    <button class="quantity-btn" onclick="updateQuantity(1)">+</button>
                </div>
                
                <div class="subtotal">
                    <span class="subtotal-label">Subtotal:</span>
                    <span id="subtotalDisplay" class="subtotal-value">
                        @if($product->is_discounted)
                            Rp {{ number_format($product->price * (1 - $product->discount_percentage / 100), 0, ',', '.') }}
                        @else
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        @endif
                    </span>
                </div>
                
                <div class="buttons">
                    @auth
                        <form method="POST" action="{{ route('cart.add', $product->id) }}">
                            @csrf
                            <input type="hidden" id="formQuantity" name="quantity" value="1"> 
                            <button type="submit" class="btn add-to-cart">
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
                        </form>
                    
                        <form method="GET" action="{{ route('checkout.payment') }}">
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" id="formQuantityBuyNow" value="1">
                            <button type="submit" class="btn buy-now">
                                <i class="fas fa-bolt"></i> Buy Now
                            </button>
                        </form>
                    @endauth

                    @guest
                        <button class="btn add-to-cart" onclick="window.location.href='{{ route('login') }}'">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                        <button class="btn buy-now" onclick="window.location.href='{{ route('login') }}'">
                            <i class="fas fa-bolt"></i> Buy Now
                        </button>
                    @endguest
                </div>
            </div>
        </div>
    </div>

    <div id="notification" class="notification">
        <i class="fas fa-check-circle"></i>
        <span>Product added to cart successfully!</span>
    </div>

    <script>
        // Calculate unit price
        const unitPrice = {{ $product->is_discounted 
            ? $product->price * (1 - $product->discount_percentage / 100) 
            : $product->price }};
            
        const quantityInput = document.getElementById('quantityInput');
        const subtotalDisplay = document.getElementById('subtotalDisplay');
        const notification = document.getElementById('notification');

        function formatRupiah(amount) {
            return 'Rp ' + amount.toLocaleString('id-ID');
        }

        function updateQuantity(change) {
            let qty = parseInt(quantityInput.value);
            qty += change;
            if (qty < 1) qty = 1;
            if (qty > 10) qty = 10;
            quantityInput.value = qty;
            updateSubtotal();
            syncQuantityToForms();
            
            // Animation for change
            subtotalDisplay.classList.remove('animate-pop');
            void subtotalDisplay.offsetWidth;
            subtotalDisplay.classList.add('animate-pop');
        }

        function updateSubtotal() {
            let qty = parseInt(quantityInput.value);
            let subtotal = qty * unitPrice;
            subtotalDisplay.innerText = formatRupiah(subtotal);
        }

        function syncQuantityToForms() {
            document.getElementById('formQuantity').value = quantityInput.value;
            document.getElementById('formQuantityBuyNow').value = quantityInput.value;
        }

        function showNotification() {
            notification.classList.add('show');
            setTimeout(() => {
                notification.classList.remove('show');
            }, 3000);
        }

        // Initialize
        updateSubtotal();

        quantityInput.addEventListener('input', () => {
            let qty = parseInt(quantityInput.value);
            if (isNaN(qty)) qty = 1;
            if (qty < 1) quantityInput.value = 1;
            if (qty > 10) quantityInput.value = 10;
            updateSubtotal();
            syncQuantityToForms();
        });

        // Handle form submission for add to cart
        document.querySelector('form[action="{{ route('cart.add', $product->id) }}"]').addEventListener('submit', function(e) {
            e.preventDefault();
            // Submit form via AJAX or standard
            this.submit();
            showNotification();
        });
    </script>
</body>
</html>