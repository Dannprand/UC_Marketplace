<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - UCMarketPlace</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Same styles as edit page */
    </style>
</head>
<body class="flex items-center justify-center p-4">
    <div class="product-card w-full max-w-md p-8 animate__animated animate__fadeIn">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-[#2d3748] mb-2">Add New Product</h2>
            <p class="text-[#718096]">Fill in your product details</p>
        </div>

        <form method="POST" action="{{ route('product.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            
            <!-- Same form fields as edit page, without existing images -->
            
            <div>
                <label class="block text-sm font-medium text-[#4a5568] mb-2">Product Images</label>
                <label class="file-input-label">
                    Select Images
                    <input type="file" name="images[]" id="imagesInput" class="hidden" accept="image/*" multiple required>
                </label>
                <div id="imagesPreview" class="image-preview-container"></div>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="btn-primary flex-1 font-semibold">
                    Add Product
                </button>
                <a href="{{ route('merchant.dashboard') }}" class="btn-primary flex-1 font-semibold text-center" style="background: #718096">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <script>
        // Same image preview script as edit page
    </script>
</body>
</html>