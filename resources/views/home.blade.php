<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        .scroll-section {
            width: 100%;
            padding: 1rem 0 2rem;
            overflow: hidden; /* Hide scrollbar */
        }
        .scroll-container {
            display: flex;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            scroll-padding: 0 1.5rem;
            padding: 0 1.5rem;
            gap: 1.5rem;
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
        .scroll-container::-webkit-scrollbar {
            display: none; 
        }
        .scroll-item {
            flex: 0 0 calc(100% - 3rem);
            scroll-snap-align: start;
            height: 400px;
            background: #E5EDF1;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: bold;
            color: #2d3748;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: transform 0.2s ease;

        }
        .scroll-item:hover {
            transform: translateY(-3px);
        }
        .section-title {
            font-size: 1.8rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 1.5rem;
            padding: 0 1.5rem;
            color: #2d3748;
        }
    </style>
</head>
<body class="bg-white">
    <x-navigation />

    <div class="animate__animated animate__fadeIn">
        <div class="scroll-section">
            <h2 class="section-title">Hot Deals</h2>
            <div class="scroll-container">
                <div class="scroll-item">Item 1</div>
                <div class="scroll-item">Item 2</div>
                <div class="scroll-item">Item 3</div>
                <div class="scroll-item">Item 4</div>
                <div class="scroll-item">Item 5</div>
            </div>
        </div>
    </div>
</body>
</html>
