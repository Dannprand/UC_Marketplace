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
            height: calc(100vh - 80px);
            /* Adjust based on navigation height */
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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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

        .product-image-container {
            width: 100px;
            height: 100px;
            flex-shrink: 0;
            margin-left: 5%
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

        /* Update quantity controls */
        .quantity-control {
            display: flex;
            align-items: center;
            margin-left: auto;
        }

        /* Product info styling */
        .product-info {
            display: flex;
            align-items: center;
            flex-grow: 1;
        }

        .product-image-container {
            width: 80px;
            height: 80px;
            margin-right: 15px;
        }
    </style>
</head>

<body>
    <x-navigation></x-navigation>

    <div class="pt-18">
        <div class="main-container">
            <div class="cart-header">Cart</div>

            <!-- Scrollable Items (75%) -->
            <div class="items-section">
                @php $total = 0; @endphp
                @forelse($cart->items as $item)
                    <div class="cart-item-container" id="item-{{ $item->id }}">
                        <div class="cart-item-content">
                            <input type="checkbox" class="item-checkbox" data-id="{{ $item->id }}"
                                data-price="{{ $item->product->price }}" data-quantity="{{ $item->quantity }}" checked>
<div class="cart-item-container" id="item-{{ $item->id }}">
    <div class="cart-item-content">
        <input type="checkbox" class="item-checkbox" data-id="{{ $item->id }}" 
               data-price="{{ $item->product->price }}" data-quantity="{{ $item->quantity }}" checked>
                            <div class="product-info">
                                <div class="product-image-container">
                                    <img src="{{ asset('storage/' . $item->product->images[0]) }}" 
                                        alt="{{ $item->product->name }}" class="rounded object-cover w-20 h-20">
                                </div>
                                <div>
                                    <h3>{{ $item->product->name }}</h3>
                                    <p class="seller-name">{{ $item->product->store->name }}</p>
                                    <p>Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>
                                </div>
                            </div>

                             <div class="quantity-control">
            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="update-form">
    @csrf
    @method('PATCH')
    <button type="button" class="quantity-btn minus" 
            onclick="updateQuantity(this, -1)">-</button>
    <input type="number" name="quantity" 
           value="{{ $item->quantity }}"
           min="1" max="10" 
           class="quantity-input"
           onchange="submitForm(this)">
    <button type="button" class="quantity-btn plus" 
            onclick="updateQuantity(this, 1)">+</button>
</form>
        </div>
                        </div>

                        <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="delete-action">
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
                <div class="total-price">Total: Rp {{ number_format($totalPrice, 0, ',', '.') }}</div>
                <div style="margin-bottom: 15px;">
                    Items: <span id="item-count">{{ $totalItems }}</span>
                </div>
                
                @if($totalItems > 0)
                    <form id="checkout-form" action="{{ route('payment') }}" method="GET">
                        @csrf
                        {{-- Hidden inputs untuk selected_items nanti diisi JS --}}
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

    {{-- Quantity JS (optional: dynamic only if you later make update route) --}}
    <script>
        // Global variable untuk menyimpan data cart
        let cartItems = {!! json_encode($cart->items->map(function($item) {
            return [
                'id' => $item->id,
                'price' => $item->product->price,
                'quantity' => $item->quantity
            ];
        })) !!};
    
        // Format angka ke rupiah
        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }
    
        // Fungsi hitung total berdasarkan item yang dichecklist
        function calculateTotal() {
        let total = 0;
        let itemCount = 0;
        
        document.querySelectorAll('.item-checkbox:checked').forEach(checkbox => {
            const price = parseFloat(checkbox.getAttribute('data-price'));
            const quantity = parseInt(checkbox.getAttribute('data-quantity'));
            total += price * quantity;
            itemCount++;
        });
        
        document.querySelector('.total-price').textContent = 'Total: Rp ' + formatRupiah(total);
        document.querySelector('#item-count').textContent = itemCount;
    }
    
        // Fungsi update quantity
function updateQuantity(button, change) {
    const form = button.closest('.update-form');
    const input = form.querySelector('.quantity-input');
    let newValue = parseInt(input.value) + change;
    
    newValue = Math.max(1, Math.min(newValue, 10));
    input.value = newValue;
    
            const itemId = parseInt(form.action.split('/').pop());
            const itemIndex = cartItems.findIndex(item => item.id === itemId);
            if (itemIndex !== -1) {
                cartItems[itemIndex].quantity = newValue;
            }
    
            // Update quantity di checkbox juga
            const checkbox = document.querySelector(.item-checkbox[data-id="${itemId}"]);
            if (checkbox) {
                checkbox.setAttribute('data-quantity', newValue);
            }
    // Update data di checkbox
    const itemId = parseInt(form.action.split('/').pop());
    const checkbox = document.querySelector(`.item-checkbox[data-id="${itemId}"]`);
    if(checkbox) {
        checkbox.setAttribute('data-quantity', newValue);
    }
    
            calculateTotal();
            form.submit();
        }
    
        // Update quantity saat input langsung diubah
        function submitForm(input) {
            const form = input.closest('form');
            const itemId = parseInt(form.action.split('/').pop());
            const quantity = parseInt(input.value);
    
            const itemIndex = cartItems.findIndex(item => item.id === itemId);
            if (itemIndex !== -1) {
                cartItems[itemIndex].quantity = quantity;
            }
    
            const checkbox = document.querySelector(.item-checkbox[data-id="${itemId}"]);
            if (checkbox) {
                checkbox.setAttribute('data-quantity', quantity);
            }
    
            calculateTotal();
            form.submit();
        }
    calculateTotal();
    form.submit();
}

// Fungsi submit form
function submitForm(input) {
    const form = input.closest('form');
    const itemId = parseInt(form.action.split('/').pop());
    const quantity = parseInt(input.value);

    // Update data di checkbox
    const checkbox = document.querySelector(`.item-checkbox[data-id="${itemId}"]`);
    if(checkbox) {
        checkbox.setAttribute('data-quantity', quantity);
    }

    calculateTotal();
    form.submit();
}
    
        // Hapus konfirmasi sebelum delete
        document.querySelectorAll('.delete-action button').forEach(btn => {
            btn.addEventListener('click', function(e) {
                if (!confirm('Are you sure you want to remove this item?')) {
                    e.preventDefault();
                    e.stopPropagation();
                }
            });
        });
    
        // Swipe gesture (mobile & desktop)
        document.querySelectorAll('.cart-item-container').forEach(container => {
            let startX, currentX;
    
            container.addEventListener('touchstart', e => {
                startX = e.touches[0].clientX;
            }, {passive: true});
    
            container.addEventListener('touchmove', e => {
                currentX = e.touches[0].clientX;
                const diff = startX - currentX;
    
                if (diff > 30) container.classList.add('swiped');
                else if (diff < -30) container.classList.remove('swiped');
            }, {passive: true});
    
            container.addEventListener('mousedown', e => {
                if (e.button !== 0) return;
                startX = e.clientX;
                document.addEventListener('mousemove', handleMouseMove);
                document.addEventListener('mouseup', () => {
                    document.removeEventListener('mousemove', handleMouseMove);
                }, {once: true});
            });
    
            function handleMouseMove(e) {
                currentX = e.clientX;
                const diff = startX - currentX;
                if (diff > 30) container.classList.add('swiped');
                else if (diff < -30) container.classList.remove('swiped');
            }
        });
    
        // Saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            calculateTotal();
    
            // Dengarkan perubahan checkbox
            document.querySelectorAll('.item-checkbox').forEach(cb => {
                cb.addEventListener('change', calculateTotal);
            });
    
            // Dengarkan input langsung di quantity
            document.querySelectorAll('.quantity-input').forEach(input => {
                input.addEventListener('change', function() {
                    submitForm(this);
                });
            });
        });

        // cart store
        document.addEventListener('DOMContentLoaded', function() {
        calculateTotal();

        // Dengarkan perubahan checkbox
        document.querySelectorAll('.item-checkbox').forEach(cb => {
            cb.addEventListener('change', calculateTotal);
        });

        // Dengarkan input langsung di quantity
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', function() {
                submitForm(this);
            });
        });

        // Tangani submit form checkout
        const checkoutForm = document.getElementById('checkout-form');
        checkoutForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Ambil semua checkbox yang dicek
            const checkedBoxes = Array.from(document.querySelectorAll('.item-checkbox:checked'));
            if (checkedBoxes.length === 0) {
                alert('Silakan pilih minimal satu item untuk checkout.');
                return;
            }

            // Cek semua item berasal dari toko yang sama
            const selectedIds = checkedBoxes.map(cb => parseInt(cb.getAttribute('data-id')));
            const selectedStores = new Set();

            checkedBoxes.forEach(cb => {
                // cari store name dari seller-name element di cart item container
                const itemContainer = cb.closest('.cart-item-container');
                const storeName = itemContainer.querySelector('.seller-name').textContent.trim();
                selectedStores.add(storeName);
            });

            if (selectedStores.size > 1) {
                alert('Anda hanya dapat melakukan checkout untuk satu toko saja.');
                return;
            }

            // Isi hidden inputs selected_items[] di form
            const inputsContainer = document.getElementById('selected-items-inputs');
            inputsContainer.innerHTML = ''; // kosongkan dulu

            selectedIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_items[]';
                input.value = id;
                inputsContainer.appendChild(input);
            });

            // Submit form jika validasi lolos
            this.submit();
        });
    });

    </script>
    
</body>

</html>