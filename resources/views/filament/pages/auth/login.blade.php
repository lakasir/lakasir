<div class="min-h-screen w-full relative flex items-center justify-center p-4 sm:p-6 overflow-x-hidden overflow-y-auto bg-[#F2F4F7] dark:bg-[#0F1117]" style="font-family: 'Inter', system-ui, sans-serif;">

    <!-- Soft background orbs — no gradient, just blurred circles -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden z-0" aria-hidden="true">
        <!-- Orange top-left orb -->
        <div class="absolute -top-20 -left-20 w-[360px] h-[360px] bg-[#FF6600] rounded-full filter blur-[100px] opacity-[0.12] dark:opacity-[0.22] animate-orb-1"></div>
        <!-- Orange bottom-right orb -->
        <div class="absolute -bottom-20 -right-20 w-[300px] h-[300px] bg-[#FF8C00] rounded-full filter blur-[90px] opacity-[0.10] dark:opacity-[0.18] animate-orb-2"></div>
    </div>

    <!-- Login Card -->
    <div class="relative z-10 w-full max-w-sm sm:max-w-md">
        <div class="bg-white dark:bg-[#161821] border border-black/[0.07] dark:border-white/[0.07] rounded-2xl shadow-xl dark:shadow-2xl overflow-hidden">

            <!-- Card Header -->
            <div class="px-8 pt-8 pb-6 border-b border-black/[0.06] dark:border-white/[0.06] flex flex-col items-center">
                <img src="{{ asset('assets/logo/image.png') }}" alt="Lakasir Logo" class="h-9 sm:h-11 w-auto object-contain mb-4">
                <h1 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">Selamat Datang</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Masuk ke akun Anda</p>
            </div>

            <!-- Card Body — Form -->
            <div class="px-8 py-7">
                <form wire:submit.prevent="authenticate" class="space-y-5">

                    {{ $this->form }}

                    <!-- Submit Button — solid orange, no gradient -->
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="w-full py-2.5 px-6 rounded-lg font-semibold text-white text-sm bg-[#FF6600] hover:bg-[#e55d00] active:bg-[#cc5200] focus:outline-none focus:ring-2 focus:ring-[#FF6600]/40 transition-colors duration-150 cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="authenticate">Masuk</span>
                        <span wire:loading wire:target="authenticate" class="flex items-center justify-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Memproses...
                        </span>
                    </button>
                </form>
            </div>

        </div>

        <!-- Footer note -->
        <p class="text-center text-xs text-gray-400 dark:text-gray-600 mt-5">
            &copy; {{ date('Y') }} Lakasir. All rights reserved.
        </p>
    </div>
</div>

