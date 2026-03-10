<?php

use Illuminate\Support\Facades\Auth;
use function Livewire\Volt\layout;
use function Livewire\Volt\state;
use function Livewire\Volt\rules;

layout('components.layouts.auth');

state([
    'email' => '',
    'password' => '',
    'remember' => true,
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
        return null;
    }

    session()->regenerate();

    return redirect()->intended(route('dashboard', absolute: false));
};

?>

<div class="auth-login">
    <div class="auth-login__logo-wrap">
        <img src="{{ asset('assets/logo/image.png') }}" alt="Lakasir" class="auth-login__logo">
    </div>

    <div class="auth-form__header">
        <h1 class="auth-form__title">{{ __('auth.sign_in') }} Lakasir</h1>
        <p class="auth-form__subtitle">{{ __('auth.enter_your_details') }}</p>
    </div>

    <form wire:submit="login" class="auth-form__body">
        <div class="auth-form__fields">
            <div class="auth-field">
                <label for="login-email" class="sr-only">
                    {{ __('auth.email_address') }}
                </label>
                <input
                    id="login-email"
                    type="email"
                    wire:model="email"
                    autocomplete="email"
                    placeholder="{{ __('auth.email_or_phone_placeholder') }}"
                    class="auth-input @error('email') auth-input--error @enderror"
                >
                @error('email')
                    <p class="auth-field__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="auth-field">
                <label for="login-password" class="sr-only">
                    {{ __('auth.password_label') }}
                </label>
                <input
                    id="login-password"
                    type="password"
                    wire:model="password"
                    autocomplete="current-password"
                    placeholder="{{ __('auth.password_placeholder') }}"
                    class="auth-input @error('password') auth-input--error @enderror"
                >
                @error('password')
                    <p class="auth-field__error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="auth-form__meta">
            <label class="auth-remember">
                <input
                    type="checkbox"
                    wire:model="remember"
                    class="auth-remember__checkbox"
                >
                <span>{{ __('auth.remember_me') }}</span>
            </label>

            @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="auth-form__link">
                    {{ __('auth.forgot_password') }}
                </a>
            @endif
        </div>

        <button
            type="submit"
            wire:loading.attr="disabled"
            class="auth-submit"
        >
            <svg wire:loading wire:target="login" class="auth-submit__spinner animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>{{ __('auth.sign_in') }}</span>
        </button>
    </form>

    @if(Route::has('auth.register'))
        <p class="auth-form__footer">
            {{ __('auth.dont_have_account') }}
            <a href="{{ route('auth.register') }}">
                {{ __('auth.sign_up') }}
            </a>
        </p>
    @endif
</div>
