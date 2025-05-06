<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - UCMarketPlace</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Maintain original styles */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(180deg, #e0f3fe 70%, #a1d4f6 100%);
            min-height: 100vh;
        }
        
        .profile-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            transition: all 0.2s ease;
        }
        
        .info-item {
            border-bottom: 2px solid #E5EDF1;
            padding: 1.25rem 0;
        }
        
        /* Improved edit button */
        .edit-btn {
            background: #2b6cb0;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .edit-btn:hover {
            background: #2c5282;
            transform: translateY(-1px);
        }
        
        /* New merchant button style */
        .merchant-btn {
            background: #4299e1;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
        }
        
        .merchant-btn:hover {
            background: #3182ce;
            transform: translateY(-1px);
        }
        
        /* Improved avatar section */
        .avatar-wrapper {
            position: relative;
            display: inline-block;
            margin-bottom: 1.5rem;
        }
        
        .avatar-edit {
            position: absolute;
            bottom: -8px;
            right: -8px;
            background: white;
            border: 2px solid #E5EDF1;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .avatar-edit:hover {
            background: #EBF8FF;
            transform: scale(1.05);
        }
    </style>
</head>
<body class="flex items-center justify-center p-4">
    <div class="profile-card w-full max-w-2xl p-8 animate__animated animate__fadeIn">
        <!-- Profile Header -->
        <div class="text-center mb-8">
            <div class="avatar-wrapper">
                <div class="w-24 h-24 rounded-full bg-[#E5EDF1] flex items-center justify-center mx-auto">
                    <i class="fas fa-user text-3xl text-[#96C2DB]"></i>
                </div>
                <div class="avatar-edit">
                    <i class="fas fa-pencil-alt text-sm text-[#2b6cb0]"></i>
                </div>
            </div>
            <h2 class="text-2xl font-bold text-[#2d3748] mb-1">{{ auth()->user()->full_name }}</h2>
            <p class="text-[#718096]">User</p>
        </div>

        <!-- Profile Info -->
        <div class="space-y-4">
            <div class="info-item">
                <p class="text-sm text-[#718096] mb-1">Email</p>
            </div>
            
            <div class="info-item">
                <p class="text-sm text-[#718096] mb-1">Phone</p>
            </div>
            
            <div class="info-item">
                <p class="text-sm text-[#718096] mb-1">Location</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex justify-between items-center">
            <a href="/openMerchant" class="merchant-btn inline-flex items-center space-x-2">
                <i class="fas fa-store"></i>
                <span>Open Merchant</span>
            </a>
            <button class="edit-btn">
                <i class="fas fa-pencil-alt"></i>
                Edit Profile
            </button>
        </div>
    </div>

    <script>
        // Enhanced edit functionality
        const editBtn = document.querySelector('.edit-btn');
        const fields = document.querySelectorAll('.info-item p:last-child');
        let originalValues = [];
        
        editBtn.addEventListener('click', () => {
            const isEditing = editBtn.classList.toggle('editing-mode');
            
            fields.forEach((field, index) => {
                if(isEditing) {
                    originalValues[index] = field.textContent;
                    const input = document.createElement('input');
                    input.className = 'w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a1d4f6]';
                    input.value = originalValues[index];
                    field.replaceWith(input);
                } else {
                    const inputs = document.querySelectorAll('.info-item input');
                    const newValue = inputs[index].value;
                    const newPara = document.createElement('p');
                    newPara.className = 'text-[#2d3748] font-medium';
                    newPara.textContent = newValue;
                    inputs[index].replaceWith(newPara);
                }
            });
            
            editBtn.innerHTML = isEditing 
                ? '<i class="fas fa-save"></i> Save Edit' 
                : '<i class="fas fa-pencil-alt"></i> Edit Profile';
        });
    </script>
</body>
</html>