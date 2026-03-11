@php
    $dashboardUrl = Route::has('dashboard') ? route('dashboard') : url('/');
@endphp

<header class="py-5">
    <div class="mx-auto flex w-full max-w-6xl items-center justify-between px-4 md:px-6">
        <a href="{{ $dashboardUrl }}" class="inline-flex items-center" aria-label="{{ config('app.name') }}">
            <img src="{{ asset('assets/logo/image.png') }}" alt="{{ config('app.name') }}" class="h-6 w-auto md:h-7">
        </a>

        @auth
            <form method="POST" action="{{ url('/logout') }}">
                @csrf
                <button
                    type="submit"
                    class="inline-flex items-center gap-2 text-sm text-[#888888] transition hover:text-[#555555] focus:outline-none focus-visible:ring-2 focus-visible:ring-lakasir-primary focus-visible:ring-offset-2 focus-visible:ring-offset-[#f2f2f2]"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>{{ __('menu.logout') }}</span>
                </button>
            </form>
        @endauth
    </div>
</header>