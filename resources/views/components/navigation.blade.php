<nav class="bg-white shadow-md px-12 py-6 flex justify-between items-center transition-all duration-500 ease-in-out">
    <!-- Logo -->
    <a href="{{ route('home') }}" 
       class="text-2xl font-bold text-[#96C2DB] hover:text-[#7fb1d1] transition-all duration-300 ease-in-out">
        UCMarketPlace
    </a>

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
            <a href="{{ route('profile') }}" 
               class="relative hover:text-[#96C2DB] transition-all duration-300 ease-in-out after:absolute after:left-0 after:bottom-0 after:w-0 after:h-[2px] after:bg-[#96C2DB] after:transition-all after:duration-300 hover:after:w-full">
               Profile
            </a>
        </li>
    </ul>
</nav>
