<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>Payment - UCMarketPlace</title>
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
        }

        .payment-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 60% 40%;
            gap: 30px;
        }

        .payment-header {
            grid-column: 1 / -1;
            padding: 20px;
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
            font-weight: 600;
            border-bottom: 2px solid black;
            position: relative;
            padding-bottom: 15px;
        }

        .payment-methods {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .payment-methods h2 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #2d3748;
        }

        .payment-option {
            display: flex;
            align-items: center;
            padding: 15px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .payment-option:hover {
            border-color: #96C2DB;
            background-color: #f8fafc;
        }

        .payment-option.active {
            border-color: #96C2DB;
            background-color: #f0f9ff;
        }

        .payment-option input {
            margin-right: 15px;
        }

        .payment-icon {
            width: 40px;
            height: 40px;
            margin-right: 15px;
            object-fit: contain;
        }

        .payment-label {
            flex-grow: 1;
        }

        .payment-label h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 3px;
            color: #2d3748;
        }

        .payment-label p {
            font-size: 14px;
            color: #718096;
        }

        .order-summary {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            position: sticky;
            top: 20px;
        }

        .order-summary h2 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #2d3748;
        }

        .order-items {
            margin-bottom: 20px;
            max-height: 300px;
            overflow-y: auto;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #edf2f7;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .item-name {
            font-size: 14px;
            color: #4a5568;
        }

        .item-price {
            font-size: 14px;
            font-weight: 600;
            color: #2d3748;
        }

        .order-total {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .total-label {
            font-size: 16px;
            color: #4a5568;
        }

        .total-value {
            font-size: 16px;
            font-weight: 600;
            color: #2d3748;
        }

        .grand-total {
            font-size: 18px;
            color: #e74c3c;
            font-weight: 700;
        }

        .pay-button {
            width: 100%;
            padding: 15px;
            background-color: #2ecc71;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s;
        }

        .pay-button:hover {
            background-color: #27ae60;
        }

        .pay-button:disabled {
            background-color: #cbd5e0;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <x-navigation></x-navigation>

    <div class="payment-container">
        <div class="payment-header">Payment Method</div>
        
        <!-- Payment Methods Section -->
        <div class="payment-methods">
            <h2>Select Payment Method</h2>
            
            <div class="payment-option active">
                <input type="radio" name="paymentMethod" id="ucWallet" checked>
                <img src="https://via.placeholder.com/40x40?text=UC" alt="UC Wallet" class="payment-icon">
                <div class="payment-label">
                    <h3>UC Wallet</h3>
                    <p>Pay using your UC Wallet balance</p>
                </div>
            </div>
            
            <div class="payment-option">
                <input type="radio" name="paymentMethod" id="bankTransfer">
                <img src="https://via.placeholder.com/40x40?text=Bank" alt="Bank Transfer" class="payment-icon">
                <div class="payment-label">
                    <h3>Bank Transfer</h3>
                    <p>Transfer directly from your bank account</p>
                </div>
            </div>
            
            <div class="payment-option">
                <input type="radio" name="paymentMethod" id="gopay">
                <img src="https://via.placeholder.com/40x40?text=Gopay" alt="Gopay" class="payment-icon">
                <div class="payment-label">
                    <h3>Gopay</h3>
                    <p>Pay using your Gopay account</p>
                </div>
            </div>
            
            <div class="payment-option">
                <input type="radio" name="paymentMethod" id="ovo">
                <img src="https://via.placeholder.com/40x40?text=OVO" alt="OVO" class="payment-icon">
                <div class="payment-label">
                    <h3>OVO</h3>
                    <p>Pay using your OVO account</p>
                </div>
            </div>
        </div>
        
        <!-- Order Summary Section -->
        <div class="order-summary">
            <h2>Order Summary</h2>
            
            <div class="order-items">
                <div class="order-item">
                    <span class="item-name">Beef Rawon Soup</span>
                    <span class="item-price">Rp 40,000</span>
                </div>
                <div class="order-item">
                    <span class="item-name">Nasi Goreng</span>
                    <span class="item-price">Rp 15,000</span>
                </div>
                <div class="order-item">
                    <span class="item-name">Es Teh</span>
                    <span class="item-price">Rp 5,000</span>
                </div>
            </div>
            
            <div class="order-total">
                <div class="total-row">
                    <span class="total-label">Subtotal</span>
                    <span class="total-value">Rp 60,000</span>
                </div>
                <div class="total-row">
                    <span class="total-label">Service Fee</span>
                    <span class="total-value">Rp 2,000</span>
                </div>
                <div class="total-row grand-total">
                    <span class="total-label">Total</span>
                    <span class="total-value">Rp 62,000</span>
                </div>
            </div>
            
            <button class="pay-button">Pay Now</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle payment method selection
            const paymentOptions = document.querySelectorAll('.payment-option');
            
            paymentOptions.forEach(option => {
                option.addEventListener('click', function() {
                    // Remove active class from all options
                    paymentOptions.forEach(opt => opt.classList.remove('active'));
                    
                    // Add active class to clicked option
                    this.classList.add('active');
                    
                    // Check the radio input
                    const radio = this.querySelector('input[type="radio"]');
                    if (radio) {
                        radio.checked = true;
                    }
                });
            });
        });
    </script>
</body>
</html>