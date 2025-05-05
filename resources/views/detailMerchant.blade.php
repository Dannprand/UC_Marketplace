<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Merchant Details - UCMarketPlace</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(180deg, #e0f3fe 70%, #a1d4f6 100%);
            min-height: 100vh;
            padding: 2rem;
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: 75% 25%;
            gap: 2rem;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .full-width {
            padding: 0 2rem 2rem 2rem;
        }
        .metric-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(4px);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(143, 211, 248, 0.15);
        }
        .progress-bar {
            height: 8px;
            background: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background: #2ecc71;
            transition: width 0.3s ease;
        }
        .scroll-container {
            overflow-x: hidden; /* Scroll hidden */
            border: 2px solid #ccc;
            border-radius: 8px;
            padding: 1rem;
            background: white;
        }
        canvas {
            width: 100% !important;
            height: auto !important;
        }
    </style>
</head>
<body>
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-[#2d3436]">Merchant Dashboard</h1>
            <a href="{{ route('merchant') }}" class="text-[#2d3436] hover:text-[#535757] font-bold flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back to Merchant
            </a>
        </div>

        <!-- Dashboard Grid -->
        <div class="dashboard-grid">
            <!-- Income Section -->
            <div class="metric-card">
                <h2 class="text-xl font-semibold mb-4">Today Profit: <span class="text-[#2ecc71]">Rp 3,000,000</span></h2>
                
                <!-- Dropdown -->
                <div class="mb-4">
                    <label for="timeSelect" class="mr-2 font-medium">View:</label>
                    <select id="timeSelect" class="p-2 border rounded-lg">
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                </div>

                <!-- Chart -->
                <div class="scroll-container">
                    <canvas id="incomeChart" class="mt-4 animate__animated"></canvas>
                </div>
            </div>

            <!-- Fundraising Target -->
            <div class="metric-card">
                <h2 class="text-xl font-semibold mb-4">Fundraising Target</h2>
                <div class="mb-4">
                    <div class="flex justify-between mb-2">
                        <span>Current Progress</span>
                        <span>65%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 65%"></div>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <input type="number" placeholder="Target Amount" class="w-full p-2 border rounded-lg">
                    <input type="number" placeholder="Days Remaining" class="w-full p-2 border rounded-lg">
                    <button class="w-full bg-[#2ecc71] text-white py-2 rounded-lg hover:bg-[#27ae60]">
                        Set Target
                    </button>
                </div>
            </div>
        </div>

        <!-- Full width stock -->
        <div class="full-width">
            <div class="metric-card">
                <h2 class="text-xl font-semibold mb-4">Current Stock</h2>
                <div class="space-y-4">
                    <div class="stock-item">
                        <div class="flex justify-between mb-2">
                            <span>Rawon Daging</span>
                            <span class="text-[#2ecc71]">45 left</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 75%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('incomeChart').getContext('2d');

        const dataSets = {
            daily: {
                labels: ['Mon', 'Tue', 'Wed'],
                data: [1200000, 1900000, 3000000]
            },
            weekly: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                data: [1200000, 1900000, 3000000, 2500000, 2200000, 3500000, 4000000]
            },
            monthly: {
                labels: Array.from({ length: 30 }, (_, i) => `Day ${i + 1}`),
                data: Array.from({ length: 30 }, () => Math.floor(Math.random() * 4000000) + 1000000)
            }
        };

        let incomeChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: dataSets.daily.labels,
                datasets: [{
                    label: 'Income',
                    data: dataSets.daily.data,
                    borderColor: '#2ecc71',
                    backgroundColor: 'rgba(46, 204, 113, 0.2)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: value => 'Rp' + value.toLocaleString()
                        }
                    },
                    x: {
                        ticks: { autoSkip: false }
                    }
                }
            }
        });

        document.getElementById('timeSelect').addEventListener('change', (e) => {
            const selected = e.target.value;

            // Animate chart fade in
            const chartCanvas = document.getElementById('incomeChart');
            chartCanvas.classList.remove('animate__fadeIn');
            void chartCanvas.offsetWidth; // force reflow
            chartCanvas.classList.add('animate__fadeIn');

            // Update chart
            incomeChart.data.labels = dataSets[selected].labels;
            incomeChart.data.datasets[0].data = dataSets[selected].data;
            incomeChart.update();
        });
    </script>
</body>
</html>
