<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>User Orders - UCMarketPlace</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(180deg, #e0f3fe 70%, #a1d4f6 100%);
            min-height: 100vh;
        }
        
        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .admin-header {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        
        .admin-nav {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .admin-nav a {
            padding: 10px 20px;
            background: white;
            color: #2b6cb0;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .admin-nav a:hover {
            background: #2b6cb0;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }
        
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: all 0.2s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        }
        
        .stat-title {
            font-size: 1rem;
            color: #718096;
            margin-bottom: 10px;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #2b6cb0;
        }
        
        .users-table {
            width: 100%;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        
        .users-table th {
            background: #2b6cb0;
            color: white;
            padding: 15px;
            text-align: left;
        }
        
        .users-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #edf2f7;
        }
        
        .users-table tr:last-child td {
            border-bottom: none;
        }
        
        .users-table tr:hover {
            background: #f8fafc;
        }
        
        .action-btn {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }
        
        .delete-btn {
            background: #fed7d7;
            color: #e53e3e;
        }
        
        .delete-btn:hover {
            background: #e53e3e;
            color: white;
        }
        
        .view-btn {
            background: #ebf8ff;
            color: #3182ce;
        }
        
        .view-btn:hover {
            background: #3182ce;
            color: white;
        }
        
        @media (max-width: 768px) {
            .admin-nav {
                flex-direction: column;
            }
            
            .stats-container {
                grid-template-columns: 1fr;
            }
            
            .users-table {
                display: block;
                overflow-x: auto;
            }
        }
         /* Add new styles */
        .search-container {
            display: flex;
            margin-bottom: 20px;
            gap: 10px;
        }
        
        .search-input {
            flex-grow: 1;
            padding: 10px 12px;
            border: 2px solid #259cd8;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            transition: all 0.2s ease;
        }
        
        .search-input:focus {
            border-color: #a1d4f6;
            box-shadow: 0 0 0 3px rgba(161, 212, 246, 0.2);
            outline: none;
        }
        
        .search-btn {
            background: #2b6cb0;
            color: white;
            border: none;
            padding: 0 20px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .search-btn:hover {
            background: #2c5282;
            transform: translateY(-1px);
        }
        
        .users-table-container {
            max-height: 500px;
            overflow-y: auto;
            margin-bottom: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        
        /* Custom scrollbar */
        .users-table-container::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        .users-table-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .users-table-container::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }
        
        .users-table-container::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }
        
        .pagination a, .pagination span {
            padding: 8px 16px;
            background: white;
            border-radius: 6px;
            text-decoration: none;
            color: #2b6cb0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.2s ease;
        }
        
        .pagination a:hover {
            background: #2b6cb0;
            color: white;
        }
        
        .pagination .active {
            background: #2b6cb0;
            color: white;
        }
        
        .no-results {
            text-align: center;
            padding: 20px;
            color: #718096;
        }
        .order-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .order-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        }

        .order-status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .status-pending { background: #fef3c7; color: #d97706; }
        .status-completed { background: #dcfce7; color: #16a34a; }
        .status-cancelled { background: #fee2e2; color: #dc2626; }
    </style>
</head>
<body>
    <div class="pt-12 pb-8">
        <div class="admin-container animate__animated animate__fadeIn">
            <div class="admin-header">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold text-[#2d3748]">Order History</h1>
                        <p class="text-[#718096] mt-2">
                            User: {{ $user->full_name }}<br>
                            Email: {{ $user->email }}<br>
                            Phone: {{ $user->phone_number }}
                        </p>
                    </div>
                    <a href="{{ route('admin.dashboard') }}" class="text-blue-500 hover:text-blue-700 transition-colors">
                        ← Back to Dashboard
                    </a>
                </div>
            </div>

            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-title">Total Orders</div>
                    <div class="stat-value">{{ $orders->count() }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">Total Spent</div>
                    <div class="stat-value">Rp {{ number_format($orders->sum('total_amount'), 0, ',', '.') }}</div>
                </div>
            </div>

            <div class="space-y-4">
                @forelse($orders as $order)
                <div class="order-card">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-[#2d3748]">
                                Order #{{ $order->order_number }}
                            </h3>
                            <p class="text-sm text-[#718096]">
                                {{ $order->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                        <span class="text-xl font-bold text-[#2b6cb0]">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="mb-4">
                        <span class="order-status status-{{ $order->status }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>

                    <div class="space-y-3">
                        @foreach($order->items as $item)
                        <div class="flex justify-between items-start py-2 border-b border-[#edf2f7] last:border-0">
                            <div>
                                <p class="font-medium text-[#2d3748]">{{ $item->product->name }}</p>
                                <p class="text-sm text-[#718096]">
                                    {{ $item->product->store->name ?? 'No Store' }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-[#2d3748]">x{{ $item->quantity }}</p>
                                <p class="text-sm text-[#718096]">
                                    Rp {{ number_format($item->unit_price, 0, ',', '.') }} each
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @empty
                <div class="stat-card text-center text-[#718096]">
                    No orders found for this user
                </div>
                @endforelse
            </div>
        </div>
    </div>
</body>
</html>