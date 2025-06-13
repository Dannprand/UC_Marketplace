<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Merchant - UC Marketplace</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
        }
        
        .merchant-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            transition: all 0.2s ease;
        }
        
        .merchant-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(0,0,0,0.12);
        }
        
        .form-input, .btn-primary {
            border: 2px solid #E5EDF1;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        
        .form-input:focus {
            border-color: #111157;
            box-shadow: 0 0 0 3px rgba(161, 212, 246, 0.2);
        }
        
        .btn-primary {
            background: #273157;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        
        .btn-primary:hover {
            background: #5363a0;
            transform: translateY(-1px);
        }
        
        .merchant-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .merchant-pfp {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #a1d4f6;
        }
    </style>
</head>
<body class="flex items-center justify-center p-4">
    <div class="merchant-card w-full max-w-md p-8 animate__animated animate__fadeIn">
        <div class="text-center mb-6">
            <h2 class="text-3xl font-bold text-[#2d3748] mb-2">Manage Merchant</h2>
            <p class="text-[#718096]">Enter your merchant password to continue</p>
        </div>

        <div class="merchant-info">
            <img src="{{ asset('storage/' . Auth::user()->merchant->merchant_pfp) }}" alt="Merchant Logo" class="merchant-pfp">
            <div>
                <h3 class="font-semibold">{{ Auth::user()->merchant->merchant_name }}</h3>
                <p class="text-sm text-gray-600">{{ Auth::user()->merchant->merchant_description }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('merchant.manage') }}" class="space-y-4">
            @csrf
            
            <div>
                <label for="merchant_password" class="block text-sm font-medium text-[#4a5568] mb-2">Merchant Password</label>
                <input id="merchant_password" type="password" name="merchant_password" class="form-input w-full p-3" 
                       required placeholder="Enter your merchant password">
                @error('merchant_password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-primary w-full font-semibold mt-4">
                Continue to Dashboard
            </button>
        </form>
    </div>
</body>
</html>