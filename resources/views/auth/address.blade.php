<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Address - UC Marketplace</title>
    @vite('resources/css/app.css')
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Add similar styling to registration form */
        body {
            font-family: 'Poppins', sans-serif;
            background: #f0e7d5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .address-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
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
<body>
    <div class="address-card animate__animated animate__fadeIn">
        <h2 class="text-2xl font-bold mb-6 text-center text-[#273157">Add Your Address</h2>
        
        <form method="POST" action="{{ route('register.address') }}">
            @csrf

            <div class="space-y-4">
                <!-- Street Address -->
                <div>
                    <label class="block text-sm font-medium mb-1">Street Address</label>
                    <input type="text" name="street" required
                           class="w-full p-2 border rounded-md focus:ring-2 focus:ring-blue-200">
                </div>

                <!-- City -->
                <div>
                    <label class="block text-sm font-medium mb-1">City</label>
                    <input type="text" name="city" required
                           class="w-full p-2 border rounded-md">
                </div>

                <!-- Province -->
                <div>
                    <label class="block text-sm font-medium mb-1">Province</label>
                    <input type="text" name="province" required
                           class="w-full p-2 border rounded-md">
                </div>

                <!-- Postal Code -->
                <div>
                    <label class="block text-sm font-medium mb-1">Postal Code</label>
                    <input type="text" name="postal_code" required
                           class="w-full p-2 border rounded-md">
                </div>

                <!-- Country -->
                <div>
                    <label class="block text-sm font-medium mb-1">Country</label>
                    <input type="text" name="country" required
                           class="w-full p-2 border rounded-md">
                </div>

                <!-- Primary Address -->
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_primary" id="is_primary" value="1">
                    <label for="is_primary" class="text-sm">Set as primary address</label>
                </div>

                <button type="submit" 
                        class="btn-primary w-full font-semibold mt-6">
                    Complete Registration
                </button>
            </div>
        </form>
    </div>
</body>
</html>