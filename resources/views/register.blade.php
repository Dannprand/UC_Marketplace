<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Your Platform</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(
                180deg,
                #e0f3fe 70%,
                #a1d4f6 100%
            );
            min-height: 100vh;
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
            border-color: #a1d4f6;
            box-shadow: 0 0 0 3px rgba(161, 212, 246, 0.2);
        }
        
        .btn-primary {
            background: #2b6cb0;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        
        .btn-primary:hover {
            background: #2c5282;
            transform: translateY(-1px);
        }
    </style>
</head>
<body class="flex items-center justify-center p-4">
    <div class="register-card w-full max-w-md p-8 animate__animated animate__fadeIn">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-[#2d3748] mb-2">Create Account</h2>
            <p class="text-[#718096]">Join UCMarketPlace today</p>
        </div>

        <form class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-[#4a5568] mb-2">Full Name</label>
                <input type="text" class="form-input w-full p-3" placeholder="Kevin Artan">
            </div>

            <div>
                <label class="block text-sm font-medium text-[#4a5568] mb-2">Email Address</label>
                <input type="email" class="form-input w-full p-3" placeholder="kevin@example.com">
            </div>

            <div>
                <label class="block text-sm font-medium text-[#4a5568] mb-2">Password</label>
                <input type="password" class="form-input w-full p-3" placeholder="••••••••">
            </div>

            <button type="submit" class="btn-primary w-full font-semibold">
                Register Now
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-[#718096]">
            Already have an account? 
            <a href="#" class="text-[#2b6cb0] hover:text-[#2c5282] font-medium">Sign in here</a>
        </div>
    </div>
</body>
</html>