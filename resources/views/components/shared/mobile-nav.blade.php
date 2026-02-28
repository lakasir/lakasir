<nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 z-40">
    <div class="grid grid-cols-4 gap-1 px-2 py-1">
        <a 
            href="{{ Route::has('dashboard') ? route('dashboard') : '#' }}" 
            class="flex flex-col items-center justify-center py-2 text-gray-500 dark:text-gray-400 hover:text-lakasir-primary dark:hover:text-lakasir-primary transition-colors"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="text-xs mt-1">Home</span>
        </a>
        
        <a 
            href="{{ Route::has('pos.cashier') ? route('pos.cashier') : '#' }}" 
            class="flex flex-col items-center justify-center py-2 text-gray-500 dark:text-gray-400 hover:text-lakasir-primary dark:hover:text-lakasir-primary transition-colors"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <span class="text-xs mt-1">POS</span>
        </a>
        
        <a 
            href="{{ Route::has('pos.history') ? route('pos.history') : '#' }}" 
            class="flex flex-col items-center justify-center py-2 text-gray-500 dark:text-gray-400 hover:text-lakasir-primary dark:hover:text-lakasir-primary transition-colors"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-xs mt-1">History</span>
        </a>
        
        <a 
            href="{{ Route::has('settings.profile') ? route('settings.profile') : '#' }}" 
            class="flex flex-col items-center justify-center py-2 text-gray-500 dark:text-gray-400 hover:text-lakasir-primary dark:hover:text-lakasir-primary transition-colors"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span class="text-xs mt-1">Profile</span>
        </a>
    </div>
</nav>