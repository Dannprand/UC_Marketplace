{{-- <nav class="fixed top-0 left-0 w-full z-50 bg-white shadow-md px-12 py-6 flex justify-between items-center transition-all duration-500 ease-in-out"> --}}
<nav class="fixed top-0 left-0 w-full z-[1000] bg-white shadow-md px-12 py-6 flex justify-between items-center transition-all duration-500 ease-in-out">
    <!-- Logo -->
    <a href="{{ route('home') }}" 
       class="text-2xl font-bold text-[#96C2DB] hover:text-[#7fb1d1] transition-all duration-300 ease-in-out">
        UCMarketPlace
    </a>

    {{-- <!-- Search Bar -->
    <div class="flex-1 max-w-xl mx-8 relative">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" 
                id="liveSearchInput"
                class="w-full pl-10 pr-4 py-2 rounded-lg border-0 bg-gray-100 focus:ring-2 focus:ring-[#96C2DB] focus:bg-white transition-all duration-300" 
                placeholder="Search products...">
        </div>

        <!-- Suggestions Dropdown -->
        <div id="suggestionsBox" class="absolute bg-white border mt-2 w-full rounded-lg shadow-lg hidden z-50"></div>
    </div> --}}

    {{-- <!-- Search Bar -->
    <div class="flex-1 max-w-xl mx-8 relative">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none h-full">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text" 
                id="liveSearchInput"
                class="w-full pl-10 pr-4 py-2 rounded-lg border-0 bg-gray-100 focus:ring-2 focus:ring-[#96C2DB] focus:bg-white transition-all duration-300 h-full" 
                placeholder="Search products...">
        </div>
        <!-- Suggestions Dropdown -->
        <div id="suggestionsBox" class="absolute bg-white border mt-2 w-full rounded-lg shadow-lg hidden z-50"></div>
    </div> --}}

    <!-- Search Bar -->
    <div class="flex justify-center items-center flex-1 mx-8 relative h-full max-w-xl">
        <div class="relative w-full">
            <!-- Search Icon -->
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>

            <!-- Input -->
            <input type="text" 
                id="liveSearchInput"
                class="w-full pl-10 pr-4 py-2 rounded-lg border-0 bg-gray-100 focus:ring-2 focus:ring-[#96C2DB] focus:bg-white transition-all duration-300" 
                placeholder="Search products...">
        </div>

        <!-- Suggestions Dropdown -->
        <div id="suggestionsBox" class="absolute top-full mt-2 w-full bg-white border rounded-lg shadow-lg hidden z-50"></div>
    </div>

    <!-- Navigation Links -->
    <ul class="flex space-x-12 text-[#333] font-medium">
        <li>
            <a href="{{ route('cart') }}" 
               class="relative hover:text-[#96C2DB] transition-all duration-300 ease-in-out after:absolute after:left-0 after:bottom-0 after:w-0 after:h-[2px] after:bg-[#96C2DB] after:transition-all after:duration-300 hover:after:w-full">
               Cart
            </a>
        </li>
        <li>
            <a href="{{ route('balance') }}" 
               class="relative hover:text-[#96C2DB] transition-all duration-300 ease-in-out after:absolute after:left-0 after:bottom-0 after:w-0 after:h-[2px] after:bg-[#96C2DB] after:transition-all after:duration-300 hover:after:w-full">
               Balance
            </a>
        </li>
        <li>
            <li>
                @if(Auth::check())
                    <a href="{{ route('profile') }}" 
                       class="relative hover:text-[#96C2DB] transition-all duration-300 ease-in-out after:absolute after:left-0 after:bottom-0 after:w-0 after:h-[2px] after:bg-[#96C2DB] after:transition-all after:duration-300 hover:after:w-full">
                       {{ Auth::user()->full_name }}
                    </a>
                @else
                    <a href="{{ route('profile') }}" 
                       class="relative hover:text-[#96C2DB] transition-all duration-300 ease-in-out after:absolute after:left-0 after:bottom-0 after:w-0 after:h-[2px] after:bg-[#96C2DB] after:transition-all after:duration-300 hover:after:w-full">
                       Login
                    </a>
                @endif
            </li>
            
        </li>
    </ul>
</nav>

<!-- JS for Live Search -->
<script>
    const searchInput = document.getElementById('liveSearchInput');
    const suggestionsBox = document.getElementById('suggestionsBox');

    searchInput.addEventListener('input', function () {
        const query = this.value;

        if (query.length < 2) { 
            suggestionsBox.classList.add('hidden');
            return;
        }

        fetch(`/user/live-search?query=${encodeURIComponent(query)}`) // ✅ Correct URL
            .then(response => response.json())
            .then(data => {
                suggestionsBox.innerHTML = '';

                if (data.length === 0) {
                    suggestionsBox.classList.add('hidden');
                    return;
                }

                data.forEach(item => {
                    const div = document.createElement('div');
                    div.classList.add('px-4', 'py-2', 'hover:bg-gray-100', 'cursor-pointer');
                    div.textContent = item.name;

                    // ✅ Correct product URL
                    div.addEventListener('click', () => {
                        window.location.href = `/user/product/${item.id}`;
                    });

                    suggestionsBox.appendChild(div);
                });

                suggestionsBox.classList.remove('hidden');
            });
    });

    // Hide dropdown when clicking outside
    document.addEventListener('click', function (event) {
        if (!event.target.closest('#liveSearchInput')) {
            suggestionsBox.classList.add('hidden');
        }
    });
</script>
