<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    @vite('resources/css/app.css')
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(180deg, #e0f3fe 70%, #a1d4f6 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1rem;
        }
        .verification-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            padding: 2rem;
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
        .btn-verify {
            background: #2b6cb0;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            display: inline-block;
            margin-top: 1rem;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="verification-card">
        <h1 class="text-2xl font-bold mb-4">Verify Your Email Address</h1>
        
        @if (session('message'))
            <div class="mb-4 text-green-600">
                {{ session('message') }}
            </div>
        @endif

        <p class="mb-4">Before proceeding, please check your email for a verification link.</p>
        <p class="mb-4">If you did not receive the email, click below to request another.</p>

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn-verify">Resend Verification Email</button>
        </form>
    </div>
</body>
</html>