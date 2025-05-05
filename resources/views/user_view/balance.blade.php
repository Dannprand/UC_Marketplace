<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balance - UCMarketPlace</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Maintain color palette consistency */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(180deg, #e0f3fe 70%, #a1d4f6 100%);
            min-height: 100vh;
        }

        .balance-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            transition: all 0.2s ease;
        }

        .payment-method-card {
            border: 2px solid #E5EDF1;
            border-radius: 12px;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .payment-method-card:hover {
            border-color: #a1d4f6;
            transform: translateY(-2px);
        }

        .payment-method-card.active {
            border-color: #2b6cb0;
            background: #EBF8FF;
        }

        .topup-btn {
            background: #2b6cb0;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .topup-btn:hover {
            background: #2c5282;
            transform: translateY(-1px);
        }

        .amount-option {
            border: 2px solid #E5EDF1;
            border-radius: 12px;
            transition: all 0.2s ease;
            cursor: pointer;
            min-height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .amount-option:hover {
            border-color: #a1d4f6;
            background: #f8fcff;
        }

        .amount-option.active {
            border-color: #2b6cb0;
            background: #EBF8FF;
        }

        .amount-option input {
            border: none;
            width: 100%;
            max-width: 120px;
            font-size: 1rem;
        }

        .amount-option input:focus {
            outline: none;
            box-shadow: none;
        }

        .amount-option input::placeholder {
            color: #718096;
            opacity: 0.8;
        }
    </style>
</head>

<body class="flex items-center justify-center p-4">
    <div class="balance-card w-full max-w-2xl p-8 animate__animated animate__fadeIn">
        <!-- Balance Overview -->
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-[#718096] mb-2">Current Balance</h2>
            <div class="text-4xl font-bold text-[#2d3748]">
                Rp <span id="balanceAmount">1,250,000</span>
            </div>
        </div>

        <!-- Top-up Amount Selection -->
        <div class="mb-8">
            <h3 class="text-xl font-semibold text-[#2d3748] mb-4">Select Amount</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                <!-- Preset Amounts -->
                <div class="amount-option p-4 text-center" data-amount="100000">
                    <p class="font-medium text-[#2d3748]">Rp 100,000</p>
                </div>
                <div class="amount-option p-4 text-center" data-amount="250000">
                    <p class="font-medium text-[#2d3748]">Rp 250,000</p>
                </div>
                <div class="amount-option p-4 text-center" data-amount="500000">
                    <p class="font-medium text-[#2d3748]">Rp 500,000</p>
                </div>
                <div class="amount-option p-4 text-center" data-amount="750000">
                    <p class="font-medium text-[#2d3748]">Rp 750,000</p>
                </div>
                <div class="amount-option p-4 text-center" data-amount="1000000">
                    <p class="font-medium text-[#2d3748]">Rp 1,000,000</p>
                </div>

                <!-- Custom Amount -->
                <div class="amount-option p-4 text-center custom-amount-container">
                    <input type="number"
                        class="custom-amount-input w-full bg-transparent font-medium text-[#2d3748] focus:outline-none placeholder-[#718096]"
                        placeholder="Other amount">
                </div>
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="mb-8">
            <h3 class="text-xl font-semibold text-[#2d3748] mb-4">Payment Methods</h3>
            <div class="space-y-4">
                <div class="payment-method-card p-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-[#E5EDF1] rounded-lg flex items-center justify-center">
                            <i class="fab fa-cc-visa text-2xl text-[#2d3748]"></i>
                        </div>
                        <div>
                            <p class="font-medium text-[#2d3748]">Credit/Debit Card</p>
                            <p class="text-sm text-[#718096]">Visa, Mastercard, JCB</p>
                        </div>
                    </div>
                </div>

                <div class="payment-method-card p-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-[#E5EDF1] rounded-lg flex items-center justify-center">
                            <i class="fab fa-paypal text-2xl text-[#2d3748]"></i>
                        </div>
                        <div>
                            <p class="font-medium text-[#2d3748]">PayPal</p>
                            <p class="text-sm text-[#718096]">Secure online payments</p>
                        </div>
                    </div>
                </div>

                <div class="payment-method-card p-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-[#E5EDF1] rounded-lg flex items-center justify-center">
                            <i class="fas fa-wallet text-2xl text-[#2d3748]"></i>
                        </div>
                        <div>
                            <p class="font-medium text-[#2d3748]">E-Wallet</p>
                            <p class="text-sm text-[#718096]">Gopay, OVO, Dana</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Button -->
        <button class="topup-btn w-full font-semibold">
            <i class="fas fa-coins mr-2"></i>Top Up Now
        </button>
    </div>

    <script>
        // Payment Method Selection
        const paymentCards = document.querySelectorAll('.payment-method-card');
        paymentCards.forEach(card => {
            card.addEventListener('click', () => {
                paymentCards.forEach(c => c.classList.remove('active'));
                card.classList.add('active');
            });
        });

        // Amount Selection
        const amountOptions = document.querySelectorAll('.amount-option');
        amountOptions.forEach(option => {
            option.addEventListener('click', () => {
                amountOptions.forEach(opt => opt.classList.remove('active'));
                option.classList.add('active');
            });
        });

        // Custom Amount Input
        const customAmount = document.querySelector('input[type="number"]');
        customAmount.addEventListener('focus', () => {
            amountOptions.forEach(opt => opt.classList.remove('active'));
        });
    </script>
</body>

</html>