<div class="px-6 py-4 border-t border-gray-200 dark:border-white/10 flex flex-col gap-y-2 fi-sidebar-custom-footer">
    {{-- User Profile Link --}}
    @if(filament()->auth()->check())
    <a href="{{ filament()->getProfileUrl() ?? '#' }}" class="flex items-center gap-x-3 px-2 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-white/5 transition">
        <x-filament-panels::avatar.user :user="filament()->auth()->user()" />
        <div class="flex-1 min-w-0" x-show="$store.sidebar.isOpen" x-transition.opacity>
            <p class="text-sm font-medium text-gray-950 dark:text-white truncate">
                {{ filament()->auth()->user()?->name }}
            </p>
            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                Profile
            </p>
        </div>
    </a>
    @endif
    
    {{-- Sign Out Button --}}
    <form action="{{ filament()->getLogoutUrl() }}" method="post" class="w-full">
        @csrf
        <button type="submit" class="w-full flex items-center gap-x-3 px-2 py-2 rounded-lg text-danger-600 hover:bg-danger-50 dark:hover:bg-danger-400/10 transition">
            <x-filament::icon icon="heroicon-o-arrow-left-on-rectangle" class="h-6 w-6 text-danger-500" />
            <span class="text-sm font-medium truncate" x-show="$store.sidebar.isOpen" x-transition.opacity>Sign Out</span>
        </button>
    </form>
</div>
