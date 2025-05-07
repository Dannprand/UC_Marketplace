<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Your Platform</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(180deg,
                    #e0f3fe 70%,
                    #a1d4f6 100%);
            min-height: 100vh;
        }

        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            transition: all 0.2s ease;
        }

        .login-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12);
        }

        /* Reuse the same form styles from register */
        .form-input,
        .btn-primary {
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
    <div class="login-card w-full max-w-md p-8 animate__animated animate__fadeIn">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-[#2d3748] mb-2">Welcome Back</h2>
            <p class="text-[#718096]">Login to continue</p>
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

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-[#4a5568] mb-2">Email Address</label>
                <input id="email" type="email" name="email" class="form-input w-full p-3" value="{{ old('email') }}"
                    required autocomplete="email" autofocus>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-[#4a5568] mb-2">Password</label>
                <input id="password" type="password" name="password" class="form-input w-full p-3" required
                    autocomplete="current-password">
            </div>

            <button type="submit" class="btn-primary w-full font-semibold">
                Login
            </button>
            {{-- <a href="{{ route('home') }}" class="btn-primary w-full font-semibold">Login</a> --}}
        </form>

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif


        <div class="mt-6 text-center text-sm text-[#718096]">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-[#2b6cb0] hover:text-[#2c5282] font-medium">Create account</a>
        </div>
    </div>
</body>

</html>