<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open Your Merchant - UC Marketplace</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f0e7d5;
            min-height: 100vh;
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
        
        .pfp-upload {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .pfp-preview {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #e0f3fe;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin-bottom: 1rem;
            border: 3px solid #a1d4f6;
        }
        
        .pfp-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .file-input-label {
            background: #e0f3fe;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .file-input-label:hover {
            background: #a1d4f6;
        }
    </style>
</head>
<body class="flex items-center justify-center p-4">
    <div class="merchant-card w-full max-w-md p-8 animate__animated animate__fadeIn">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-[#2d3748] mb-2">Open Your Merchant</h2>
            <p class="text-[#718096]">Fill the form below to get started</p>
        </div>

        <form method="POST" action="{{ route('merchant.open') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            
            <div class="pfp-upload">
                <div class="pfp-preview" id="pfpPreview">
                    <span class="text-gray-500">No image</span>
                </div>
                <label class="file-input-label">
                    Upload Merchant Logo
                    <input type="file" name="pfp" id="pfpInput" class="hidden" accept="image/*">
                </label>
            </div>

            <div>
                <label for="merchant_name" class="block text-sm font-medium text-[#4a5568] mb-2">Merchant Name</label>
                <input id="merchant_name" type="text" name="merchant_name" class="form-input w-full p-3" 
                       value="{{ old('merchant_name') }}" required placeholder="Enter your merchant name">
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-[#4a5568] mb-2">Description</label>
                <textarea id="description" name="description" class="form-input w-full p-3" rows="4" 
                          required placeholder="Describe your business">{{ old('description') }}</textarea>
            </div>

            <div>
                <label for="merchant_password" class="block text-sm font-medium text-[#4a5568] mb-2">Merchant Password</label>
                <input id="merchant_password" type="password" name="merchant_password" class="form-input w-full p-3" 
                       required placeholder="Create a merchant password" minlength="8">
            </div>

            <div>
                <label for="merchant_password_confirmation" class="block text-sm font-medium text-[#4a5568] mb-2">Confirm Password</label>
                <input id="merchant_password_confirmation" type="password" name="merchant_password_confirmation" 
                       class="form-input w-full p-3" required placeholder="Confirm your merchant password">
            </div>

            <button type="submit" class="btn-primary w-full font-semibold mt-4">
                Open Merchant
            </button>
        </form>
    </div>

    <script>
        // Preview uploaded profile picture
        document.getElementById('pfpInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('pfpPreview');
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>