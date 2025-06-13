<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>Cart - UCMarketPlace</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .main-container {
            display: grid;
            grid-template-columns: 75% 25%;
            grid-template-rows: auto 1fr;
            height: calc(100vh - 80px);
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

        .items-section {
            overflow-y: auto;
            padding-right: 15px;
            height: 100%;
        }

        .cart-item-container {
            position: relative;
            overflow: hidden;
            margin-bottom: 15px;
            border-radius: 10px;
        }

        .cart-item-content {
            display: flex;
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            position: relative;
            z-index: 2;
            align-items: center;
        }

        .delete-action {
            position: absolute;
            right: -100px;
            top: 0;
            bottom: 0;
            width: 100px;
            background: #e74c3c;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            cursor: pointer;
            transition: right 0.3s ease;
            z-index: 1;
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        .cart-item-container.swiped .delete-action {
            right: 0;
        }

        .cart-item-container.swiped .cart-item-content {
            transform: translateX(-100px);
        }

        .product-info {
            display: flex;
            align-items: center;
            flex-grow: 1;
        }

        .product-image-container {
            width: 80px;
            height: 80px;
            margin-right: 15px;
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

        .cart-item h3 {
            font-size: 20px;
            color: #333;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .cart-item p {
            font-size: 16px;
            color: #e74c3c;
            margin-bottom: 5px;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: auto;
        }

        .quantity-btn {
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 6px;
            background: #212842;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .quantity-btn:hover {
            background: #5363a0;
        }

        .quantity-input {
            width: 50px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 5px;
            font-size: 14px;
        }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }

        .total-section {
            position: sticky;
            top: 20px;
            height: fit-content;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .total-price {
            font-size: 20px;
            font-weight: bold;
            color: #5363a0;
            margin-bottom: 20px;
        }

        .buy-button {
            background: #212842;
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
            background: #5363a0;
        }

        .buy-button.disabled {
            background: #cccccc;
            cursor: not-allowed;
        }
    </style>
</head>

<body>
    <x-navigation></x-navigation>

    <div class="pt-18">
        <div class="main-container">
            <div class="cart-header">Cart</div>

            <!-- Items Section -->
            <div class="items-section">
                @forelse($cart->items as $item)
                    <div class="cart-item-container" id="item-{{ $item->id }}">
                        <div class="cart-item-content">
                            <input type="checkbox" class="item-checkbox" data-id="{{ $item->id }}"
                                data-price="{{ $item->product->is_discounted ? $item->product->discounted_price : $item->product->price }}"
                                data-store="{{ $item->product->store_id }}"
                                data-quantity="{{ $item->quantity }}"
                                checked>
                            
                            <div class="product-info">
                                <div class="product-image-container">
                                    <img src="{{ asset('storage/' . $item->product->images[0]) }}"
                                        alt="{{ $item->product->name }}" class="product-image">
                                </div>
                                <div class="item-details">
                                    <h3>{{ $item->product->name }}</h3>
                                    <p class="seller-name">{{ $item->product->store->name }}</p>
                                    
                                    @if($item->product->is_discounted)
                                        <p>
                                            <span style="text-decoration: line-through; color: #999;">
                                                Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                            </span>
                                            <span style="color: #e74c3c;">
                                                Rp {{ number_format($item->product->discounted_price, 0, ',', '.') }}
                                            </span>
                                            <span style="color: green; font-weight: bold;">
                                                ({{ $item->product->discount_percentage }}% OFF)
                                            </span>
                                        </p>
                                    @else
                                        <p>Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="quantity-control">
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="update-form">
                                    @csrf
                                    @method('PATCH')
                                    <button type="button" class="quantity-btn minus"
                                        onclick="updateQuantity(this, -1)">-</button>
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="10"
                                        class="quantity-input" onchange="submitForm(this)">
                                    <button type="button" class="quantity-btn plus"
                                        onclick="updateQuantity(this, 1)">+</button>
                                </form>
                            </div>
                        </div>

                        <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="delete-action" 
                              onsubmit="return confirm('Are you sure you want to remove this item?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    </div>
                @empty
                    <p>Your cart is empty.</p>
                @endforelse
            </div>

            <!-- Total Section -->
            <div class="total-section">
                <div class="total-price">Total: Rp <span id="total-amount">{{ number_format($totalPrice, 0, ',', '.') }}</span></div>
                <div style="margin-bottom: 15px;">
                    Items: <span id="item-count">{{ $totalItems }}</span>
                </div>

                @if($totalItems > 0)
                    <form id="checkout-form" action="{{ route('checkout.payment') }}" method="GET">
                        <div id="selected-items-inputs"></div>
                        <button type="submit" class="buy-button">
                            Buy Now
                        </button>
                    </form>
                @else
                    <button class="buy-button disabled" disabled>
                        Cart is Empty
                    </button>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Format angka ke rupiah
        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }

        // Fungsi hitung total berdasarkan item yang dichecklist
        function calculateTotal() {
            let total = 0;
            let itemCount = 0;

            document.querySelectorAll('.item-checkbox:checked').forEach(checkbox => {
                const price = parseFloat(checkbox.dataset.price);
                const quantity = parseInt(checkbox.dataset.quantity);
                
                total += price * quantity;
                itemCount++;
            });

            document.getElementById('total-amount').textContent = formatRupiah(total);
            document.getElementById('item-count').textContent = itemCount;
        }

        // Fungsi update quantity
        function updateQuantity(button, change) {
            const form = button.closest('.update-form');
            const input = form.querySelector('.quantity-input');
            let newValue = parseInt(input.value) + change;

            newValue = Math.max(1, Math.min(newValue, 10));
            input.value = newValue;

            // Update data di checkbox
            const itemId = form.action.split('/').pop();
            const checkbox = document.querySelector(`.item-checkbox[data-id="${itemId}"]`);
            if (checkbox) {
                checkbox.dataset.quantity = newValue;
            }

            calculateTotal();
            form.submit();
        }

        // Fungsi submit form
        function submitForm(input) {
            const form = input.closest('form');
            const itemId = form.action.split('/').pop();
            const quantity = parseInt(input.value);

            // Update data di checkbox
            const checkbox = document.querySelector(`.item-checkbox[data-id="${itemId}"]`);
            if (checkbox) {
                checkbox.dataset.quantity = quantity;
            }

            calculateTotal();
            form.submit();
        }

        // Saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function () {
            calculateTotal();

            // Dengarkan perubahan checkbox
            document.querySelectorAll('.item-checkbox').forEach(cb => {
                cb.addEventListener('change', calculateTotal);
            });

            // Tangani submit form checkout
            const checkoutForm = document.getElementById('checkout-form');
            if (checkoutForm) {
                checkoutForm.addEventListener('submit', function (e) {
                    e.preventDefault();

                    // Ambil semua checkbox yang dicek
                    const checkedBoxes = Array.from(document.querySelectorAll('.item-checkbox:checked'));
                    if (checkedBoxes.length === 0) {
                        alert('Please select at least one item to checkout.');
                        return;
                    }

                    // Cek semua item berasal dari toko yang sama
                    const selectedStores = new Set();
                    const selectedIds = [];

                    checkedBoxes.forEach(cb => {
                        selectedStores.add(cb.dataset.store);
                        selectedIds.push(cb.dataset.id);
                    });

                    if (selectedStores.size > 1) {
                        alert('You can only checkout items from one store at a time.');
                        return;
                    }

                    // Isi hidden inputs selected_items[] di form
                    const inputsContainer = document.getElementById('selected-items-inputs');
                    inputsContainer.innerHTML = '';

                    selectedIds.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'selected_items[]';
                        input.value = id;
                        inputsContainer.appendChild(input);
                    });

                    this.submit();
                });
            }

            // Swipe gesture untuk delete
            document.querySelectorAll('.cart-item-container').forEach(container => {
                let startX, currentX;

                container.addEventListener('touchstart', e => {
                    startX = e.touches[0].clientX;
                }, { passive: true });

                container.addEventListener('touchmove', e => {
                    currentX = e.touches[0].clientX;
                    const diff = startX - currentX;

                    if (diff > 30) container.classList.add('swiped');
                    else if (diff < -30) container.classList.remove('swiped');
                }, { passive: true });

                container.addEventListener('mousedown', e => {
                    if (e.button !== 0) return;
                    startX = e.clientX;
                    document.addEventListener('mousemove', handleMouseMove);
                    document.addEventListener('mouseup', () => {
                        document.removeEventListener('mousemove', handleMouseMove);
                    }, { once: true });
                });

                function handleMouseMove(e) {
                    currentX = e.clientX;
                    const diff = startX - currentX;
                    if (diff > 30) container.classList.add('swiped');
                    else if (diff < -30) container.classList.remove('swiped');
                }
            });
        });
    </script>
</body>

</html>