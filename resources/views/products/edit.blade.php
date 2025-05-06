<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - UCMarketPlace</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(180deg, #e0f3fe 70%, #a1d4f6 100%);
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
    </style>
</head>
<body class="flex items-center justify-center p-4">
    <div class="product-card w-full max-w-md p-8 animate__animated animate__fadeIn">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-[#2d3748] mb-2">Edit Product</h2>
            <p class="text-[#718096]">Update your product details</p>
        </div>

        <form method="POST" action="{{ route('product.update', $product->id) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label for="name" class="block text-sm font-medium text-[#4a5568] mb-2">Product Name</label>
                <input id="name" type="text" name="name" class="form-input w-full p-3" 
                       value="{{ old('name', $product->name) }}" required placeholder="Product name">
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-[#4a5568] mb-2">Description</label>
                <textarea id="description" name="description" class="form-input w-full p-3" rows="4" 
                          required placeholder="Product description">{{ old('description', $product->description) }}</textarea>
            </div>

            <div>
                <label for="price" class="block text-sm font-medium text-[#4a5568] mb-2">Price</label>
                <input id="price" type="number" name="price" class="form-input w-full p-3" 
                       value="{{ old('price', $product->price) }}" required placeholder="Product price">
            </div>

            <div>
                <label for="quantity" class="block text-sm font-medium text-[#4a5568] mb-2">Quantity</label>
                <input id="quantity" type="number" name="quantity" class="form-input w-full p-3" 
                       value="{{ old('quantity', $product->quantity) }}" required placeholder="Available quantity">
            </div>

            <div>
                <label for="category_id" class="block text-sm font-medium text-[#4a5568] mb-2">Category</label>
                <select id="category_id" name="category_id" class="form-input w-full p-3" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-[#4a5568] mb-2">Current Images</label>
                <div class="image-preview-container">
                    @foreach($product->images as $image)
                        <div class="image-preview">
                            <img src="{{ asset('storage/' . $image) }}" alt="Product image">
                        </div>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-[#4a5568] mb-2">Add More Images (Optional)</label>
                <label class="file-input-label">
                    Select Images
                    <input type="file" name="images[]" id="imagesInput" class="hidden" accept="image/*" multiple>
                </label>
                <div id="newImagesPreview" class="image-preview-container"></div>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="btn-primary flex-1 font-semibold">
                    Update Product
                </button>
                <a href="{{ route('merchant.dashboard') }}" class="btn-primary flex-1 font-semibold text-center" style="background: #718096">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <script>
        // Preview new images before upload
        document.getElementById('imagesInput').addEventListener('change', function(e) {
            const previewContainer = document.getElementById('newImagesPreview');
            previewContainer.innerHTML = '';
            
            Array.from(e.target.files).forEach(file => {
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
    </script>
</body>
</html>