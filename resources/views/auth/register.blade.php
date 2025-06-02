<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - UC Marketplace</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f0e7d5;
        }
        
        .register-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            transition: all 0.2s ease;
        }
        
        .register-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(0,0,0,0.12);
        }
        
        .form-input {
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
    </style>
</head>
<body class="flex items-center justify-center p-4">
    <div class="register-card w-full max-w-md p-8 animate__animated animate__fadeIn">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-[#273157] mb-2">Create Account</h2>
            <p class="text-[#718096]">Join UC Marketplace today</p>
        </div>

        @if($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf
            
            <div>
                <label for="full_name" class="block text-sm font-medium text-[#4a5568] mb-2">Full Name</label>
                <input id="full_name" type="text" name="full_name" class="form-input w-full p-3" 
                       value="{{ old('full_name') }}" required autocomplete="name" autofocus placeholder="John Doe">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-[#4a5568] mb-2">Email Address</label>
                <input id="email" type="email" name="email" class="form-input w-full p-3" 
                       value="{{ old('email') }}" required autocomplete="email" placeholder="john@student.uc.ac.id">
            </div>

            <div>
                <label for="phone_number" class="block text-sm font-medium text-[#4a5568] mb-2">Phone Number</label>
                <input id="phone_number" type="tel" name="phone_number" class="form-input w-full p-3" 
                       value="{{ old('phone_number') }}" required placeholder="081234567890"
                       pattern="[0-9]{10,15}" title="Enter a valid phone number (10-15 digits)">
                <p class="text-xs text-gray-500 mt-1">Format: 10-15 digits (no spaces or special characters)</p>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-[#4a5568] mb-2">Password</label>
                <input id="password" type="password" name="password" class="form-input w-full p-3" 
                       required autocomplete="new-password" placeholder="••••••••" minlength="8">
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-[#4a5568] mb-2">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" class="form-input w-full p-3" 
                       required autocomplete="new-password" placeholder="••••••••">
            </div>

            <button type="submit" class="btn-primary w-full font-semibold mt-6">
                Register Now
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-[#718096]">
            Already have an account? 
            <a href="{{ route('login') }}" class="text-[#273157] hover:text-[#5363a0] font-medium">Sign in here</a>
        </div>
    </div>
</body>
</html>