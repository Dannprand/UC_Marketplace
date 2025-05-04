<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>Landing Page - UCMarketPlace</title>
    <style>
        /* Font Family*/
        body {
            font-family: 'Poppins', sans-serif;
        } 

        /* Color Variables */
        :root {
            --primary: #96C2DB;
            --primary-dark: #7fb1d1;
            --secondary: #E5EDF1;
            --text-dark: #2d3748;
            --text-medium: #4a5568;
            --text-light: #718096;
        }

        /* Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        @keyframes widen {
            from { width: 0; opacity: 0; }
            to { width: 80px; opacity: 1; }
        }

        /* Hero Section */
        .hero-bg {
            background-color: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 2rem;
        }
        .hero-content {
            max-width: 800px;
        }
        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }
        .title-underline {
            height: 4px;
            width: 80px;
            background: var(--primary);
            margin: 0 auto 2rem;
            animation: widen 1s ease-out forwards;
        }
        .hero-subtitle {
            font-size: 1.8rem;
            font-weight: 500;
            color: var(--text-medium);
            margin-bottom: 3rem;
            animation: float 3s infinite;
        }
        .btn-primary {
            background-color: var(--primary);
            color: white;
            font-weight: 600;
            padding: 1rem 2.5rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(150, 194, 219, 0.3);
        }

        /* Content Sections */
        .section {
            padding: 5rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        .section-title {
            font-size: 2.5rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 3rem;
            text-align: center;
        }
        .section-subtitle {
            font-size: 1.2rem;
            color: var(--text-medium);
            max-width: 700px;
            margin: 0 auto 3rem;
            text-align: center;
            line-height: 1.6;
        }
        .feature-card {
            background: white;
            border-radius: 12px;
            padding: 2.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }
        .feature-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }
        .feature-desc {
            color: var(--text-light);
            line-height: 1.7;
        }

        /* Animation Classes */
        .animate-float {
            animation: float 3s infinite;
        }
        .animate-delay-1 {
            animation-delay: 0.5s;
        }
        .animate-delay-2 {
            animation-delay: 1s;
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-bg animate__animated animate__fadeIn">
        <div class="hero-content">
            <h1 class="hero-title animate__animated animate__fadeInDown">UC MarketPlace</h1>
            <div class="title-underline"></div>
            <h2 class="hero-subtitle animate__animated animate__fadeIn animate-delay-1">For You By You</h2>
            <a href="{{ route('home') }}" class="btn-primary animate__animated animate__pulse animate__infinite animate__slower">
                Get Started
            </a>
        </div>
    </section>

    <!-- About Section -->
    <section class="section">
        <h2 class="section-title animate__animated animate__fadeIn">About UC MarketPlace</h2>
        <p class="section-subtitle animate__animated animate__fadeIn animate-delay-1">
            The official marketplace platform for Ciputra University students to buy and sell product to campus community.
        </p>
        
        <div class="feature-card animate__animated animate__fadeInUp animate-delay-1">
            <h3 class="feature-title">Campus Exclusive</h3>
            <p class="feature-desc">
                Connect only with verified university students. Our platform is exclusively for campus community members, ensuring safe and trustworthy transactions.
            </p>
        </div>
        
        <div class="feature-card animate__animated animate__fadeInUp animate-delay-2">
            <h3 class="feature-title">Fundraising</h3>
            <p class="feature-desc">
                Platform that have core reason for helping you ensure funds for comitte work program.
            </p>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="section rounded-2xl" style="background-color: #f8fafc;">
        <h2 class="section-title animate__animated animate__fadeIn">How It Works</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Step 1 -->
            <div class="feature-card animate__animated animate__fadeIn">
                <h3 class="feature-title">1. Create Account</h3>
                <p class="feature-desc">
                    Sign up with your email.
                </p>
            </div>
            
            <!-- Step 2 -->
            <div class="feature-card animate__animated animate__fadeIn animate-delay-1">
                <h3 class="feature-title">2. List or Browse</h3>
                <p class="feature-desc">
                    Post items for sale or browse listings from other students in your campus community.
                </p>
            </div>
            
            <!-- Step 3 -->
            <div class="feature-card animate__animated animate__fadeIn animate-delay-2">
                <h3 class="feature-title">3. Buy and Sell</h3>
                <p class="feature-desc">
                   Buy product you want or post product available to sell.
                </p>
            </div>
        </div>
    </section>

    <script>
        // Add intersection observer for scroll animations
        document.addEventListener('DOMContentLoaded', function() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate__fadeInUp');
                    }
                });
            }, { threshold: 0.1 });

            document.querySelectorAll('.feature-card, .section-title, .section-subtitle').forEach(el => {
                observer.observe(el);
            });
        });
    </script>
</body>
</html>