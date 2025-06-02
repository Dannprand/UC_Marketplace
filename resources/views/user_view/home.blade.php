<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>Home - UCMarketPlace</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f0e7d5;
        }
        
        .scroll-section {
            width: 100%;
            padding: 0.5rem 0 1.5rem;
            overflow: hidden;
            margin: 0 auto;
            max-width: 1200px;
            position: relative;
        }
        
        .scroll-container {
            display: flex;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            scroll-padding: 0 1.25rem;
            padding: 0 1.25rem;
            gap: 1rem;
            -ms-overflow-style: none;
            scrollbar-width: none;
            scroll-behavior: smooth;
        }
        
        .scroll-container::-webkit-scrollbar {
            display: none;
        }
        
        .scroll-item {
            flex: 0 0 calc(90% - 1rem);
            scroll-snap-align: start;
            height: 280px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            font-weight: 600;
            color: #e0f3fe;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.2s ease;
            margin: 0 0.5rem;
            background: #273157;
        }
        
        .scroll-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }
        
        .section-title {
            font-size: 1.8rem;
            font-weight: 800;
            text-align: center;
            margin-bottom: 1rem;
            padding: 0 1.25rem;
            color: #273157;
        }
        
        @media (min-width: 768px) {
            .scroll-item {
                flex: 0 0 calc(45% - 1rem);
                height: 200px;
            }
        }

        .best-seller-section {
        background: #212842;
        padding: 2rem 0;
        margin-top: 2rem;
    }
        
        .best-seller-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.25rem;
        }
        
        .best-seller-header {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .best-seller-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            padding-left: 0.5rem;
        }
        
        .best-seller-items {
            display: flex;
            gap: 1.2rem;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            padding: 0.5rem 0 1rem;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        .best-seller-items::-webkit-scrollbar {
            display: none;
        }
        
        .best-seller-item {
            flex: 0 0 160px;
            height: 220px; 
            background: white;
            border-radius: 15px;
            scroll-snap-align: start;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.2s ease;
        }
        
        .best-seller-item:hover{
            transform: translateY(-2px);
            box-shadow: 0 6px 6px rgba(255, 251, 251, 0.386);
        }

        .product-image {
            width: 100%;
            height: 100px;
            object-fit: cover;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }
        
        .product-details {
            padding: 0.75rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        
        .product-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: #2d3748;
            margin-bottom: 0.25rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .product-price {
            font-weight: 700;
            font-size: 1rem;
            color: #5363a0;
            margin-bottom: 0.5rem;
        }
        
        .seller-info {
            display: flex;
            align-items: center;
            margin-top: auto;
            padding-top: 0.5rem;
            border-top: 1px solid #edf2f7;
        }
        
        .seller-avatar {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background-color: #E5EDF1;
            margin-right: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.6rem;
            color: #4a5568;
        }
        
        .seller-name {
            font-size: 0.75rem;
            color: #718096;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            flex-grow: 1;
        }
        
        @media (min-width: 768px) {
            .best-seller-item {
                flex: 0 0 180px;
                height: 260px; /* Increased height for desktop */
            }
            
            .product-image {
                height: 120px;
            }
            
            .product-name {
                font-size: 1rem;
            }
            
            .product-price {
                font-size: 1.1rem;
            }
        }
        .hidden{
            display: none;
        }

        .filter-container {
            display: flex;
            justify-content: flex-end;
            margin: 1rem 1.25rem 1.5rem;
            position: relative;
        }
        
        .filter-btn {
            background-color: white;
            color:  #273157;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }
        
        .filter-btn:hover {
            background-color: whitesmoke;
            transform: translateY(-1px);
        }
        
        .filter-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 8px;
            padding: 0;
            min-width: 200px;
            z-index: 10;
            display: none;
        }
        
        .filter-dropdown.show {
            display: block;
        }
        
        .filter-option {
            cursor: pointer;
        }
        
        .filter-option:hover {
            color: #273157;
        }
        
        /* Products Section */
        .products-section {
            background: #273157;
            padding: 1rem 0 2rem;
            margin: 2rem;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 1.2rem;
            padding: 0 1.25rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .product-card {
            height: 220px;
            background: white;
            border-radius: 15px;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.2s ease;
        }
        
        .product-card:hover {
            transform: translateY(-2px);
           box-shadow: 0 6px 6px rgba(255, 251, 251, 0.386);
        }
        
        .seller-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            overflow: hidden;
            background-color: #E5EDF1;
            margin-right: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media (min-width: 768px) {
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            }
            
            .product-card {
                height: 260px;
            }
        }

    </style>
</head>
<body>
    <x-navigation />
    <div class="pt-12">

    <div class="animate__animated animate__fadeIn">
        <div class="scroll-section">
            <h2 class="section-title mt-16">Hot Deals</h2>
            <div class="scroll-container mt-12" id="autoScrollContainer">
                <div class="scroll-item">Item 1</div>
                <div class="scroll-item">Item 2</div>
                <div class="scroll-item">Item 3</div>
                <div class="scroll-item">Item 4</div>
                <div class="scroll-item">Item 5</div>
            </div>
        </div>
    

    <section class="best-seller-section rounded-2xl mx-8">
        <div class="best-seller-container">
            <div class="best-seller-header">
                <h2 class="best-seller-title">Best Seller</h2>
                <div class="best-seller-scroll">
                   <div class="best-seller-items">
                        @foreach($bestSellers as $product)
                            @php
                                $sellerName = $product->store->merchant->merchant_name ?? 'Unknown';
                                $logo = $product->store->logo ?? 'https://via.placeholder.com/40x40?text=Logo';
                                $image = $product->images[0] ?? 'https://via.placeholder.com/180x120';
                            @endphp

                            <div class="best-seller-item">
                                <img src="{{ $image }}" alt="{{ $product->name }}" class="product-image" />
                                <div class="product-details">
                                    <div class="product-name">{{ $product->name }}</div>
                                    <div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                    <div class="seller-info">
                                        <div class="seller-avatar">
                                            <img src="{{ $logo }}" alt="Logo {{ $sellerName }}" class="w-full h-full object-cover rounded-full" />
                                        </div>
                                        <div class="seller-name">{{ $sellerName }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

   <!-- All Products Section with Filter -->
<section class="products-section rounded-2xl">
    <div class="filter-container relative inline-block">
        <!-- Filter Button -->
        <button class="filter-btn" id="filterBtn" aria-haspopup="true" aria-expanded="false" aria-controls="filterDropdown">
            Filter
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z"/>
            </svg>
        </button>
        
        <!-- Filter Dropdown Menu -->
        <div class="filter-dropdown absolute bg-white border rounded shadow-lg mt-2 hidden z-50" id="filterDropdown" role="menu" aria-labelledby="filterBtn">
            <!-- No Filter option -->
            <ul class="filter-options-list">
            <li><a href="{{ route('home', ['category' => 'all']) }}" class="filter-option px-4 py-2 hover:bg-gray-100 cursor-pointer" data-filter="all" role="menuitem" aria-label="No Filter">No Filter</a></li>
             @foreach($categories as $category)
                    <li>
                        <a href="{{ route('home', ['category' => $category->slug]) }}" class="filter-option px-4 py-2 hover:bg-gray-100 cursor-pointer" role="menuitem" aria-label="{{ $category->name }}">
                            {{ $category->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="products-grid mt-6" id="productsGrid">
        @foreach($products as $product)
            @php
                $category = strtolower($product->category->name ?? 'all');
                $image = $product->images[0] ?? 'https://via.placeholder.com/180x120';
                $sellerName = $product->store->merchant->merchant_name ?? 'Unknown';
                $logo = $product->store->logo ?? 'https://via.placeholder.com/40x40?text=Logo'; // fallback logo
            @endphp

            <a href="{{ route('product.show', $product->id) }}" class="product-card" data-category="{{ $category }}" aria-label="View product details for {{ $product->name }}">
                <img src="{{ $image }}" class="product-image" />
                <div class="product-details">
                    <div class="product-name">{{ $product->name }}</div>
                    <div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                    <div class="seller-info">
                        <div class="seller-avatar">
                            <img src="{{ $logo }}" alt="Logo {{ $sellerName }}" class="w-full h-full object-cover rounded-full">
                        </div>
                        <div class="seller-name">{{ $sellerName }}</div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</section>
    

</div>
    <x-footer />
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('autoScrollContainer');
            const items = container.querySelectorAll('.scroll-item');
            let currentIndex = 0;
    
            // Handle scroll events to track current index
            container.addEventListener('scroll', () => {
                const containerRect = container.getBoundingClientRect();
                const containerCenter = containerRect.left + containerRect.width / 2;
    
                items.forEach((item, index) => {
                    const itemRect = item.getBoundingClientRect();
                    if (itemRect.left <= containerCenter && itemRect.right >= containerCenter) {
                        currentIndex = index;
                    }
                });
            });
        });
    
        // Filter Button and Dropdown
        const filterBtn = document.querySelector('.filter-btn');
        const filterDropdown = document.querySelector('.filter-dropdown');
        
        // Toggle filter dropdown visibility
        filterBtn.addEventListener('click', () => {
            filterDropdown.classList.toggle('show');
        });
    
        // Close the filter dropdown if clicked outside
        document.addEventListener('click', (e) => {
            if (!filterBtn.contains(e.target) && !filterDropdown.contains(e.target)) {
                filterDropdown.classList.remove('show');
            }
        });
    
        // Filter options functionality
        const filterOptions = document.querySelectorAll('.filter-option');
        filterOptions.forEach(option => {
            option.addEventListener('click', (e) => {
                e.preventDefault();  // Prevent default anchor behavior
                
                const selectedFilter = option.getAttribute('data-filter').toLowerCase();
    
                if (selectedFilter === "all") {
                    console.log("Resetting filters to show all products.");
                    showAllProducts();
                } else {
                    console.log(`Applying filter: ${selectedFilter}`);
                    applyFilter(selectedFilter);
                }
    
                // Close dropdown after selecting
                filterDropdown.classList.remove('show');
            });
        });
    
        // Show all products
        function showAllProducts() {
            const productCards = document.querySelectorAll('.product-card');
            productCards.forEach(card => {
                card.style.display = "block";  // Show all products
            });
        }
    
        // Apply filter by category
        function applyFilter(category) {
            const productCards = document.querySelectorAll('.product-card');
            productCards.forEach(card => {
                const cardCategory = card.getAttribute('data-category').toLowerCase();
                if (cardCategory === category) {
                    card.style.display = "block";  // Show the filtered category
                } else {
                    card.style.display = "none";   // Hide non-matching categories
                }
            });
        }
    </script>
       
</body>
</html>