<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Merchant Details - UCMarketPlace</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
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
            overflow-x: hidden;
            /* Scroll hidden */
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
                    <path fill-rule="evenodd"
                        d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                        clip-rule="evenodd" />
                </svg>
                Back to Merchant
            </a>
        </div>

        <!-- Dashboard Grid -->
        <div class="dashboard-grid">
            <!-- Income Section -->
            <div class="metric-card">
                <h2 class="text-xl font-semibold mb-4">Today Profit: <span class="text-[#2ecc71]">Rp 3,000,000</span>
                </h2>

                <!-- Dropdown -->
                <div class="mb-4">
                    <label for="timeSelect" class="mr-2 font-medium">View:</label>
                    <select id="timeSelect" class="p-2 border rounded-lg">
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                </div>

                <div class="flex items-center justify-between mb-2">
                    <button id="prevBtn" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">←
                        Previous</button>
                    <span id="dateLabel" class="font-semibold"></span>
                    <button id="nextBtn" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">Next
                        →</button>
                </div>



                <!-- Chart -->
                <div class="scroll-container">
                    <canvas id="incomeChart" class="mt-4 animate__animated"></canvas>
                </div>
            </div>

            <!-- Fundraising Target -->
            <div class="metric-card">
                <h2 class="text-xl font-semibold mb-4">Fundraising Target</h2>
                <div class="space-y-4">
                    <div>
                        <label for="targetAmount" class="block font-medium mb-1">Target Amount (Rp)</label>
                        <input id="targetAmount" type="number" placeholder="e.g. 5000000"
                            class="w-full p-2 border rounded-lg">
                    </div>

                    <div>
                        <label for="currentAmount" class="block font-medium mb-1">Current Amount (Rp)</label>
                        <input id="currentAmount" type="number" placeholder="e.g. 1500000"
                            class="w-full p-2 border rounded-lg">
                    </div>

                    <div>
                        <label for="itemPrice" class="block font-medium mb-1">Price per Item (Rp)</label>
                        <input id="itemPrice" type="number" placeholder="e.g. 20000"
                            class="w-full p-2 border rounded-lg">
                    </div>

                    <div class="flex justify-between mb-2">
                        <span>Current Progress</span>
                        <span id="progressText">0%</span>
                    </div>

                    <div class="progress-bar">
                        <div id="progressFill" class="progress-fill" style="width: 0%"></div>
                    </div>

                    <div id="itemsNeededInfo" class="text-sm text-gray-700 font-medium mt-2">
                        <!-- jumlah barang yang perlu dijual akan muncul di sini -->
                    </div>

                    <button id="calculateBtn" class="w-full bg-[#2ecc71] text-white py-2 rounded-lg hover:bg-[#27ae60]">
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

        let offset = 0; // untuk sliding waktu
        let currentView = 'daily'; // default

        const generateDataSets = (view, offset) => {
            const today = new Date();
            const data = [];
            const labels = [];

            if (view === 'daily') {
                const base = new Date();
                base.setDate(base.getDate() + offset);

                for (let i = 0; i < 3; i++) {
                    const d = new Date(base);
                    d.setDate(d.getDate() - 2 + i);
                    labels.push(d.toDateString().slice(4, 10));
                    data.push(Math.floor(Math.random() * 4000000) + 1000000);
                }

            } else if (view === 'weekly') {
                const base = new Date();
                base.setDate(base.getDate() + (offset * 7));
                labels.push(...['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']);
                for (let i = 0; i < 7; i++) {
                    data.push(Math.floor(Math.random() * 4000000) + 1000000);
                }

            } else if (view === 'monthly') {
                const base = new Date();
                base.setMonth(base.getMonth() + offset);
                const daysInMonth = new Date(base.getFullYear(), base.getMonth() + 1, 0).getDate();
                for (let i = 0; i < daysInMonth; i++) {
                    labels.push(`Day ${i + 1}`);
                    data.push(Math.floor(Math.random() * 4000000) + 1000000);
                }
            }

            return { labels, data };
        };

        const updateDateLabel = () => {
            const now = new Date();
            let label = '';

            if (currentView === 'daily') {
                const target = new Date();
                target.setDate(target.getDate() + offset);
                label = target.toDateString();
            } else if (currentView === 'weekly') {
                const start = new Date();
                start.setDate(start.getDate() + offset * 7);
                const end = new Date(start);
                end.setDate(start.getDate() + 6);
                label = `${start.toDateString().slice(4, 10)} - ${end.toDateString().slice(4, 10)}`;
            } else if (currentView === 'monthly') {
                const target = new Date();
                target.setMonth(target.getMonth() + offset);
                label = target.toLocaleString('default', { month: 'long', year: 'numeric' });
            }

            document.getElementById('dateLabel').textContent = label;
        };

        const renderChart = () => {
            const dataset = generateDataSets(currentView, offset);
            incomeChart.data.labels = dataset.labels;
            incomeChart.data.datasets[0].data = dataset.data;
            incomeChart.update();
            updateDateLabel();
        };

        const initialDataset = generateDataSets('daily', 0);

        let incomeChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: initialDataset.labels,
                datasets: [{
                    label: 'Income',
                    data: initialDataset.data,
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
            currentView = e.target.value;
            offset = 0;
            renderChart();
        });

        document.getElementById('prevBtn').addEventListener('click', () => {
            offset--;
            renderChart();
        });

        document.getElementById('nextBtn').addEventListener('click', () => {
            offset++;
            renderChart();
        });

        updateDateLabel();

        document.getElementById('calculateBtn').addEventListener('click', () => {
            const target = parseFloat(document.getElementById('targetAmount').value);
            const current = parseFloat(document.getElementById('currentAmount').value);
            const pricePerItem = parseFloat(document.getElementById('itemPrice').value);
            const progressText = document.getElementById('progressText');
            const progressFill = document.getElementById('progressFill');
            const itemsNeededInfo = document.getElementById('itemsNeededInfo');

            if (isNaN(target) || isNaN(current) || isNaN(pricePerItem) || target <= 0 || pricePerItem <= 0) {
                alert('Please enter valid numbers for all fields.');
                return;
            }

            const progressPercent = Math.min((current / target) * 100, 100).toFixed(1);
            progressText.textContent = `${progressPercent}%`;
            progressFill.style.width = `${progressPercent}%`;

            const remaining = Math.max(target - current, 0);
            const itemsNeeded = Math.ceil(remaining / pricePerItem);

            if (progressPercent >= 100) {
                itemsNeededInfo.textContent = `🎉 Target achieved!`;
            } else {
                itemsNeededInfo.textContent = `You need to sell approximately ${itemsNeeded} more item(s) to reach your target.`;
            }
        });


    </script>

</body>

</html>