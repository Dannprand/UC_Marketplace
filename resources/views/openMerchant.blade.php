<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open Your Merchant - UC Marketplace</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f0e7d5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #2d3748;
        }

        .container {
            display: flex;
            max-width: 1000px;
            width: 100%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
            overflow: hidden;
        }

        .illustration {
            flex: 1;
            background: #273157;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .illustration::before {
            content: "";
            position: absolute;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 70%);
            top: -50%;
            left: -50%;
        }

        .illustration img {
            max-width: 100%;
            margin-bottom: 30px;
            filter: drop-shadow(0 10px 15px rgba(0, 0, 0, 0.15));
        }

        .illustration h3 {
            font-size: 1.8rem;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .illustration p {
            font-size: 1rem;
            opacity: 0.9;
            max-width: 300px;
        }

        .merchant-form {
            flex: 1;
            padding: 50px 40px;
            position: relative;
        }

        .merchant-form h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 8px;
            color: #2a4365;
            text-align: center;
        }

        .merchant-form p.subtitle {
            text-align: center;
            color: #718096;
            margin-bottom: 40px;
        }

        .form-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group label {
            font-weight: 500;
            color: #4a5568;
            font-size: 0.9rem;
        }

        .form-input {
            padding: 14px 16px;
            border: 2px solid #E5EDF1;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f9fbfd;
        }

        .form-input:focus {
            border-color: #4299e1;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.2);
            outline: none;
            background: white;
        }

        textarea.form-input {
            min-height: 120px;
            resize: vertical;
        }

        .btn-primary {
            background: #273157;
            color: white;
            padding: 16px;
            border: none;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 1.05rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-primary:hover {
            background: #5363a0;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(50, 115, 220, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .pfp-upload {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 10px;
            color: white;
        }

        .pfp-preview {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #e0f3fe 0%, #a1d4f6 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin-bottom: 15px;
            border: 4px solid #a1d4f6;
            position: relative;
        }

        .pfp-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .pfp-preview i {
            font-size: 2.5rem;
            color: #2b6cb0;
            opacity: 0.7;
        }

        .file-input-label {
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            background: #5363a0;
            display: flex;
            align-items: center;
            gap: 8px;
            border: 2px dashed #a1d4f6;
        }

        .file-input-label:hover {
            background: #94a4e4;
            transform: translateY(-2px);
        }

        .bank-selector {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .bank-icon {
            width: 40px;
            height: 40px;
            background: #edf2f7;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2b6cb0;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .account-input {
            flex: 1;
        }

        .form-footer {
            text-align: center;
            margin-top: 20px;
            color: #718096;
            font-size: 0.9rem;
        }

        .form-footer a {
            color: #273157;
            text-decoration: none;
            font-weight: 500;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        .success-message {
            display: none;
            position: fixed;
            top: 20px;
            right: 20px;
            background: #48bb78;
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
            z-index: 100;
            animation: slideIn 0.5s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .illustration {
                padding: 30px 20px;
            }

            .merchant-form {
                padding: 40px 25px;
            }
        }
    </style>
</head>

<body>
    <div class="container animate__animated animate__fadeInUp">
        <div class="illustration">
            <div style="width: 100%; max-width: 300px;">
                <svg viewBox="0 0 400 300" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#ffffff"
                        d="M75,150 C75,99.08 116.08,58 167,58 C217.92,58 259,99.08 259,150 C259,200.92 217.92,242 167,242 C116.08,242 75,200.92 75,150 Z"
                        opacity="0.1" />
                    <path fill="#ffffff"
                        d="M167,75 C123.86,75 89,109.86 89,153 C89,196.14 123.86,231 167,231 C210.14,231 245,196.14 245,153 C245,109.86 210.14,75 167,75 Z"
                        opacity="0.3" />
                    <circle fill="#ffffff" cx="167" cy="153" r="70" opacity="0.5" />
                    <rect fill="#ffffff" x="147" y="133" width="40" height="40" rx="8" opacity="0.8" />
                    <path fill="#ffffff"
                        d="M167,110 C167,104.48 171.48,100 177,100 C182.52,100 187,104.48 187,110 C187,115.52 182.52,120 177,120 C171.48,120 167,115.52 167,110 Z"
                        opacity="0.8" />
                    <path fill="#ffffff"
                        d="M300,100 L300,220 C300,227.18 294.18,233 287,233 L247,233 C239.82,233 234,227.18 234,220 L234,100 C234,92.82 239.82,87 247,87 L287,87 C294.18,87 300,92.82 300,100 Z"
                        opacity="0.3" />
                    <rect fill="#ffffff" x="247" y="95" width="53" height="15" rx="3" opacity="0.8" />
                    <rect fill="#ffffff" x="247" y="120" width="53" height="10" rx="2" opacity="0.6" />
                    <rect fill="#ffffff" x="247" y="140" width="53" height="10" rx="2" opacity="0.6" />
                    <rect fill="#ffffff" x="247" y="160" width="40" height="10" rx="2" opacity="0.6" />
                    <rect fill="#ffffff" x="247" y="190" width="53" height="10" rx="2" opacity="0.6" />
                    <rect fill="#ffffff" x="247" y="210" width="53" height="10" rx="2" opacity="0.6" />
                </svg>
            </div>
            <h3>Grow Your Business with Us</h3>
            <p>Join thousands of successful merchants on our platform</p>
        </div>

        <div class="merchant-form">
            <h2>Open Your Merchant</h2>
            <p class="subtitle">Fill the form below to get started</p>

            <form method="POST" action="{{ route('merchant.open') }}" enctype="multipart/form-data"
                class="form-container">
                @csrf

                <div class="pfp-upload">
                    <div class="pfp-preview" id="pfpPreview">
                        <i class="fas fa-store"></i>
                    </div>
                    <label class="file-input-label">
                        <i class="fas fa-upload"></i> Upload Merchant Logo
                        <input type="file" name="pfp" id="pfpInput" class="hidden" accept="image/*">
                    </label>
                </div>

                <div class="form-group">
                    <label for="merchant_name">Merchant Name</label>
                    <input type="text" id="merchant_name" name="merchant_name" class="form-input"
                        value="{{ old('merchant_name') }}" required placeholder="Enter your merchant name">
                </div>

                <!-- Tambahkan ini di bawah subtitle -->
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Perbaikan form group rekening bank -->
                <div class="form-group">
                    <label>Account Number</label>
                    <div class="bank-selector">
                        <div class="bank-icon">
                            <i class="fas fa-university"></i>
                        </div>
                        <div class="account-input">
                            <input type="text" id="account_number" name="account_number" class="form-input"
                                placeholder="Enter bank account number" required value="{{ old('account_number') }}">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="bank_name">Bank Name</label>
                    <select id="bank_name" name="bank_name" class="form-input" required>
                        <option value="" disabled selected>Select your bank</option>
                        <option value="bca" {{ old('bank_name') == 'bca' ? 'selected' : '' }}>BCA - Bank Central Asia
                        </option>
                        <option value="bni" {{ old('bank_name') == 'bni' ? 'selected' : '' }}>BNI - Bank Negara Indonesia
                        </option>
                        <option value="bri" {{ old('bank_name') == 'bri' ? 'selected' : '' }}>BRI - Bank Rakyat Indonesia
                        </option>
                        <option value="mandiri" {{ old('bank_name') == 'mandiri' ? 'selected' : '' }}>Bank Mandiri
                        </option>
                        <option value="cimb" {{ old('bank_name') == 'cimb' ? 'selected' : '' }}>CIMB Niaga</option>
                        <option value="other" {{ old('bank_name') == 'other' ? 'selected' : '' }}>Other Bank</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-input" rows="4" required
                        placeholder="Describe your business">{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="merchant_password">Merchant Password</label>
                    <input type="password" id="merchant_password" name="merchant_password" class="form-input" required
                        placeholder="Create a merchant password" minlength="8">
                </div>

                <div class="form-group">
                    <label for="merchant_password_confirmation">Confirm Password</label>
                    <input type="password" id="merchant_password_confirmation" name="merchant_password_confirmation"
                        class="form-input" required placeholder="Confirm your merchant password">
                </div>

                <button type="submit" class="btn-primary">
                    Open Merchant
                </button>

                <div class="form-footer">
                    By registering, you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
                </div>
            </form>
        </div>
    </div>

    <div class="success-message" id="successMessage">
        <i class="fas fa-check-circle"></i> Merchant account created successfully!
    </div>

    <script>
        // Preview uploaded profile picture
        document.getElementById('pfpInput').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const preview = document.getElementById('pfpPreview');
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                }
                reader.readAsDataURL(file);
            }
        });

        // Handle form submission
        document.querySelector('form').addEventListener('submit', function (e) {
            e.preventDefault();

            // Show success message
            const successMessage = document.getElementById('successMessage');
            successMessage.style.display = 'flex';
            successMessage.style.alignItems = 'center';
            successMessage.style.gap = '10px';

            // Simulate form submission to backend
            setTimeout(() => {
                // Submit the form programmatically
                this.submit();
            }, 500);
        });
    </script>
</body>

</html>