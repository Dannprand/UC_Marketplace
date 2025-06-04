<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Store - UC Marketplace</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Similar styles to openMerchant.blade.php */
        body {
            font-family: 'Poppins', sans-serif;
           background: #f0e7d5;
            /* background: linear-gradient(180deg, #e0f3fe 70%, #a1d4f6 100%); */
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
            border-color: #a1d4f6;
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
            <h2 class="text-3xl font-bold text-[#2d3748] mb-2">Create Your Store</h2>
            <p class="text-[#718096]">Set up your store to start selling</p>
        </div>

        <form method="POST" action="{{ route('store.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            
            <div>
                <label for="name" class="block text-sm font-medium text-[#4a5568] mb-2">Store Name</label>
                <input id="name" type="text" name="name" class="form-input w-full p-3" 
                       value="{{ old('name') }}" required placeholder="Enter your store name">
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-[#4a5568] mb-2">Store Description</label>
                <textarea id="description" name="description" class="form-input w-full p-3" rows="4" 
                          required placeholder="Describe your store">{{ old('description') }}</textarea>
            </div>

            <div>
                <label for="category_id" class="block text-sm font-medium text-[#4a5568] mb-2">Category</label>
                <select id="category_id" name="category_id" class="form-input w-full p-3" required>
                    <option value="">Select a category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-[#4a5568] mb-2">Store Logo (Required)</label>
                <input type="file" name="logo" class="form-input w-full p-3" required accept="image/*">
            </div>

            <div>
                <label class="block text-sm font-medium text-[#4a5568] mb-2">Store Banner (Optional)</label>
                <input type="file" name="banner" class="form-input w-full p-3" accept="image/*">
            </div>

            <button type="submit" class="btn-primary w-full font-semibold mt-4">
                Create Store
            </button>
        </form>
    </div>
</body>
</html>