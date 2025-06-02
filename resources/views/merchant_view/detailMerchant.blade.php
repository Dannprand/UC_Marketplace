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

<body class="bg-gray-50 text-gray-800">

    
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Detail Merchant - {{ $store->name }}</h1>
            <a href="{{ route('merchant.dashboard') }}" class="text-black font-medium hover:font-semibold">&larr; Back to Merchant</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white shadow-md rounded-2xl p-4">
                <h2 class="text-lg font-semibold mb-2">Total Revenue</h2>
                <p class="text-2xl font-bold text-green-700">
                    Rp {{ number_format(optional($store->analytics)->total_revenue ?? 0, 0, ',', '.') }}
                </p>
            </div>
            <div class="bg-white shadow-md rounded-2xl p-4">
                <h2 class="text-lg font-semibold mb-2">Total Sales</h2>
                <p class="text-2xl font-bold text-blue-400">
                    {{ optional($store->analytics)->total_sales ?? 0 }}
                </p>
            </div>
            <div class="bg-white shadow-md rounded-2xl p-4">
                <h2 class="text-lg font-semibold mb-2">Average Order</h2>
                <p class="text-2xl font-bold text-blue-400">
                    {{ optional($store->analytics)->average_order_value ?? 0 }}
                </p>
            </div>
        </div>



        <div class="bg-white shadow-md rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold">Income Overview</h2>
                <div class="space-x-2">
                    <button onclick="setView('daily')" class="btn-view text-sm py-1 px-3 rounded-full border text-gray-700" id="btn-daily">Daily</button>
                    <button onclick="setView('weekly')" class="btn-view text-sm py-1 px-3 rounded-full border text-gray-700" id="btn-weekly">Weekly</button>
                    <button onclick="setView('monthly')" class="btn-view text-sm py-1 px-3 rounded-full border text-gray-700" id="btn-monthly">Monthly</button>
                </div>
            </div>
            <div class="flex justify-between items-center mb-4">
                <button onclick="adjustOffset(-1)" class="text-orange-500 hover:font-semibold">&larr; Prev</button>
                <span id="date-label" class="text-gray-600"></span>
                <button onclick="adjustOffset(1)" class="text-orange-500 hover:font-semibold">Next &rarr;</button>
            </div>
            <canvas id="incomeChart" class="w-full h-64"></canvas>
            
        </div>
    </div>

   <script>
    let currentView = 'daily';
    let offset = 0;
    const ctx = document.getElementById('incomeChart').getContext('2d');

    const incomeChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Income',
                data: [],
                borderColor: 'rgb(34, 197, 94)', // green-500
                backgroundColor: 'rgba(34, 197, 94, 0.1)', // green-500 with opacity
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    ticks: {
                        callback: value => 'Rp ' + value.toLocaleString()
                    }
                }
            }
        }
    });

    async function fetchChartData(view, offset = 0) {
        const res = await fetch(`/merchant/income-data?view=${view}&offset=${offset}`);
        if (!res.ok) {
            console.error('Failed to fetch income data');
            return { labels: [], data: [] };
        }
        return res.json();
    }


    async function renderChart() {
        const { labels, data } = await fetchChartData(currentView, offset);
        incomeChart.data.labels = labels;
        incomeChart.data.datasets[0].data = data;
        incomeChart.update();
        updateDateLabel();
    }

    function setView(view) {
        currentView = view;
        offset = 0;
        renderChart();
        document.querySelectorAll('.btn-view').forEach(btn => btn.classList.remove('bg-orange-500', 'text-white'));
        const activeBtn = document.getElementById(`btn-${view}`);
        if (activeBtn) activeBtn.classList.add('bg-orange-500', 'text-white');
    }

    function adjustOffset(value) {
        offset += value;
        renderChart();
    }

    function updateDateLabel() {
        const label = document.getElementById('date-label');
        if (!label) return;

        if (currentView === 'daily') {
            const today = new Date();
            today.setDate(today.getDate() - offset);
            label.textContent = today.toDateString();
        } else if (currentView === 'weekly') {
            const base = new Date();
            base.setDate(base.getDate() - offset * 7);
            const start = new Date(base);
            start.setDate(start.getDate() - start.getDay()); // minggu mulai dari minggu lalu
            const end = new Date(start);
            end.setDate(end.getDate() + 6);
            label.textContent = `${start.toDateString()} - ${end.toDateString()}`;
        } else if (currentView === 'monthly') {
            const now = new Date();
            now.setMonth(now.getMonth() - offset);
            label.textContent = now.toLocaleString('default', { month: 'long', year: 'numeric' });
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        setView('daily');
    });
</script>

</body>
</html>