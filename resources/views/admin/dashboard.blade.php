<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>Admin Dashboard - UCMarketPlace</title>
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
    </style>
</head>
<body>
    <div class="pt-12 pb-8">
        <div class="admin-container animate__animated animate__fadeIn">
            <div class="admin-header">
                <h1 class="text-2xl font-bold text-[#2d3748]">Admin Dashboard</h1>
                <p class="text-[#718096]">Welcome back, Administrator</p>
            </div>
            
            <div class="admin-nav">
                <a href="{{ route('admin.dashboard') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5z"/>
                        <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6z"/>
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.users') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816zM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275z"/>
                    </svg>
                    User Management
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-white text-red-500 rounded-lg shadow hover:bg-red-50 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
                            <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
            
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-title">Total Users</div>
                    <div class="stat-value">{{ $userCount }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">New Users Today</div>
                    <div class="stat-value">{{ $newUsersToday }}</div>
                </div>
            </div>

            <!-- Search Form -->
            <div class="search-container">
                <form method="GET" action="{{ route('admin.dashboard') }}" class="flex w-full">
                    <input type="text" 
                           name="search" 
                           class="search-input" 
                           placeholder="Search by name, email or phone..." 
                           value="{{ request('search') }}"
                           aria-label="Search users">
                    <button type="submit" class="search-btn">
                        Search
                    </button>
                </form>
            </div>
            
            <h2 class="text-xl font-bold text-[#2d3748] mb-4">Recent Users</h2>
            <div class="overflow-x-auto">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentUsers as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->full_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone_number }}</td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="flex gap-2">
                                <a href="#" class="action-btn view-btn">View</a>
                                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn delete-btn">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        // Simple animation for stat cards
        document.querySelectorAll('.stat-card').forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
            card.classList.add('animate__animated', 'animate__fadeInUp');
        });
    </script>
</body>
</html>