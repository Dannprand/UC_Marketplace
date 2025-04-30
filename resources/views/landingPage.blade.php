<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .hero-bg {
            background-color: #E5EDF1;
            background-size: 20px 20px;
        }
        .btn-primary {
            background-color: #96C2DB;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #7fb1d1;
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 4px 15px rgba(150, 194, 219, 0.4);
        }
        .title-underline {
            height: 3px;
            width: 80px;
            background: #96C2DB;
            margin: 0 auto;
            animation: widen 1s ease-out forwards;
        }
        @keyframes widen {
            from { width: 0; opacity: 0; }
            to { width: 80px; opacity: 1; }
        }
    </style>
</head>
<body class="font-sans">
    <!-- Hero Section -->
    <div class="hero-bg min-h-screen flex items-center justify-center px-4">
        <div class="text-center max-w-md animate__animated animate__fadeIn animate__slow">
            <!-- Animated Title -->
            <h1 class="text-5xl font-bold text-gray-800 mb-3 animate__animated animate__fadeInDown">UC MarketPlace</h1>
            
            <!-- Animated Underline -->
            <div class="title-underline mb-8"></div>
            
            <!-- Floating Subtitle -->
            <h2 class="text-3xl font-medium text-gray-700 my-8 animate__animated fadeIn" style="animation-name: float; animation-duration: 3s; ">
                For You By You
            </h2>
            
            <!-- Pulsing Button -->
            <a href="{{ route('home') }}" class="btn-primary text-white font-bold py-3 px-10 rounded-lg text-lg animate__animated animate__pulse animate__infinite animate__slower inline-block">
                Get Started
            </a>            
        </div>
    </div>

    <script>
        document.getElementById('getStartedBtn').addEventListener('click', function () {
            const hero = document.querySelector('.hero-bg');
            hero.classList.add('animate__animated', 'animate__fadeOut');
    
            // Delay before redirect
            setTimeout(() => {
                window.location.href = "{{ route('home') }}";
            }, 800); // matches animation duration
        });
    </script>
</body>
</html>