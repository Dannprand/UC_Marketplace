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
        body {
            font-family: 'Poppins', sans-serif;
            background: #f0e7d5;
            min-height: 100vh;
        }
        
        .product-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            transition: all 0.2s ease;
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
        
        .image-preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .image-preview {
            width: 100px;
            height: 100px;
            border-radius: 8px;
            background-color: #e0f3fe;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
            border: 2px solid #a1d4f6;
        }
        
        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .remove-image {
            position: absolute;
            top: 0;
            right: 0;
            background: rgba(255,0,0,0.7);
            color: white;
            border: none;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        
        .file-input-label {
            background: #e0f3fe;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-block;
            margin-bottom: 1rem;
        }
        
        .file-input-label:hover {
            background: #a1d4f6;
        }
        
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }
        
        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }
        
        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        
        input:checked + .slider {
            background-color: #273157;
        }
        
        input:checked + .slider:before {
            transform: translateX(26px);
        }
        
        .discount-fields {
            display: none;
        }
        
        .show-discount .discount-fields {
            display: block;
        }
    </style>
</head>
<body class="flex items-center justify-center p-4">
    <div class="product-card w-full max-w-2xl p-8 animate__animated animate__fadeIn">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-[#2d3748] mb-2">Add New Product</h2>
            <p class="text-[#718096]">Fill in your product details</p>
        </div>

        <form method="POST" action="{{ route('product.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-[#4a5568] mb-2">Product Name</label>
                        <input type="text" id="name" name="name" class="form-input w-full p-3" required>
                    </div>
                    
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-[#4a5568] mb-2">Category</label>
                        <select id="category_id" name="category_id" class="form-input w-full p-3" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="price" class="block text-sm font-medium text-[#4a5568] mb-2">Price (Rp)</label>
                        <input type="number" id="price" name="price" min="0" step="0.01" class="form-input w-full p-3" required>
                    </div>
                    
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-[#4a5568] mb-2">Quantity</label>
                        <input type="number" id="quantity" name="quantity" min="0" class="form-input w-full p-3" required>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <label class="block text-sm font-medium text-[#4a5568]">Is Featured?</label>
                        <label class="toggle-switch">
                            <input type="checkbox" name="is_featured" value="1">
                            <span class="slider"></span>
                        </label>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <label class="block text-sm font-medium text-[#4a5568]">Is Discounted?</label>
                        <label class="toggle-switch">
                            <input type="checkbox" id="is_discounted" name="is_discounted" value="1">
                            <span class="slider"></span>
                        </label>
                    </div>
                    
                    <div id="discountFields" class="discount-fields">
                        <label for="discount_percentage" class="block text-sm font-medium text-[#4a5568] mb-2">Discount Percentage</label>
                        <input type="number" id="discount_percentage" name="discount_percentage" min="0" max="100" class="form-input w-full p-3">
                    </div>
                </div>
                
                <!-- Additional Information -->
                <div class="space-y-4">
                    <div>
                        <label for="description" class="block text-sm font-medium text-[#4a5568] mb-2">Description</label>
                        <textarea id="description" name="description" rows="5" class="form-input w-full p-3" required></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-[#4a5568] mb-2">Product Images</label>
                        <label class="file-input-label">
                            Select Images (Max 5)
                            <input type="file" name="images[]" id="imagesInput" class="hidden" accept="image/*" multiple required>
                        </label>
                        <p class="text-xs text-gray-500">First image will be used as main product image</p>
                        <div id="imagesPreview" class="image-preview-container"></div>
                    </div>
                </div>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="submit" class="btn-primary flex-1 font-semibold">
                    Add Product
                </button>
                <a href="{{ route('merchant.dashboard') }}" class="btn-primary flex-1 font-semibold text-center" style="background: #901c04">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <script>
        // Image preview functionality
        document.getElementById('imagesInput').addEventListener('change', function(e) {
            const previewContainer = document.getElementById('imagesPreview');
            previewContainer.innerHTML = '';
            
            const files = Array.from(e.target.files).slice(0, 5); // Limit to 5 images
            
            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.createElement('div');
                    preview.className = 'image-preview';
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                    previewContainer.appendChild(preview);
                }
                reader.readAsDataURL(file);
            });
        });
        
        // Toggle discount fields
        const discountToggle = document.getElementById('is_discounted');
        const discountFields = document.getElementById('discountFields');
        
        discountToggle.addEventListener('change', function() {
            if (this.checked) {
                discountFields.style.display = 'block';
                document.getElementById('discount_percentage').setAttribute('required', 'required');
            } else {
                discountFields.style.display = 'none';
                document.getElementById('discount_percentage').removeAttribute('required');
            }
        });
    </script>
</body>
</html>