<?php

use Illuminate\Support\Facades\Auth;
use function Livewire\Volt\layout;
use function Livewire\Volt\state;
use function Livewire\Volt\rules;

layout('components.layouts.auth');

state([
    'email' => '',
    'password' => '',
    'remember' => false,
    'showPassword' => false,
]);

rules([
    'email' => ['required', 'string', 'email'],
    'password' => ['required', 'string'],
]);

$login = function() {
    $this->validate();
    
    $credentials = [
        'email' => $this->email,
        'password' => $this->password,
    ];
    
    if (!Auth::guard('web')->attempt($credentials, $this->remember)) {
        $this->addError('email', __('auth.failed'));
        return;
    }
    
    session()->regenerate();
    
    return redirect()->intended(route('dashboard', absolute: false));
};

?>

<div>
    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Welcome Back</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-2">Sign in to your account to continue</p>
    </div>

    <form wire:submit="login" class="space-y-5">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Email Address
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                    </svg>
                </div>
                <input
                    type="email"
                    wire:model="email"
                    placeholder="you@example.com"
                    class="@error('email') border-red-500 focus:ring-red-500 @else border-gray-300 dark:border-gray-600 @enderror w-full rounded-lg bg-white dark:bg-gray-900 px-3 py-2 pl-10 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-lakasir-primary focus:border-transparent"
                >
            </div>
            @error('email')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Password
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <input
                    type="{{ $showPassword ? 'text' : 'password' }}"
                    wire:model="password"
                    placeholder="Enter your password"
                    class="@error('password') border-red-500 focus:ring-red-500 @else border-gray-300 dark:border-gray-600 @enderror w-full rounded-lg bg-white dark:bg-gray-900 px-3 py-2 pl-10 pr-10 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-lakasir-primary focus:border-transparent"
                >
                <button
                    type="button"
                    @click="$wire.showPassword = !$wire.showPassword"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                >
                    @if($showPassword)
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    @else
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                    @endif
                </button>
            </div>
            @error('password')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
                <input
                    type="checkbox"
                    wire:model="remember"
                    class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 text-lakasir-primary focus:ring-lakasir-primary"
                >
                <span class="text-sm text-gray-600 dark:text-gray-400">Remember me</span>
            </label>

            @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-lakasir-primary hover:underline">
                    Forgot password?
                </a>
            @endif
        </div>

        <button
            type="submit"
            wire:loading.attr="disabled"
            class="w-full inline-flex items-center justify-center font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed bg-lakasir-primary text-white hover:bg-orange-600 focus:ring-lakasir-primary px-6 py-3 text-base gap-2"
        >
            <svg wire:loading class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Sign In</span>
        </button>
    </form>

    @if(Route::has('auth.register'))
        <p class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
            Don't have an account?
            <a href="{{ route('auth.register') }}" class="text-lakasir-primary hover:underline font-medium">
                Sign up
            </a>
        </p>
    @endif
</div>