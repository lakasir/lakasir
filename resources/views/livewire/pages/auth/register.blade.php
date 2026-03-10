<?php

use App\Rules\Domain;
use App\Services\RegisterTenant;
use Illuminate\Validation\Rules\Password;
use function Livewire\Volt\layout;
use function Livewire\Volt\state;

layout('components.layouts.auth');

state([
    'shopName' => '',
    'domain' => '',
    'email' => '',
    'password' => '',
    'passwordConfirmation' => '',
    'agreeTerms' => true,
]);

$create = function (RegisterTenant $registerTenant) {
    $validated = $this->validate(
        [
            'shopName' => ['required', 'string', 'max:255'],
            'domain' => ['required', 'string', 'max:63', 'unique:tenants,id', new Domain],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:tenant_users,email'],
            'password' => ['required', Password::defaults(), 'same:passwordConfirmation'],
            'passwordConfirmation' => ['required'],
            'agreeTerms' => ['accepted'],
        ],
        [
            'agreeTerms.accepted' => __('validation.accepted', ['attribute' => __('auth.terms_and_conditions')]),
        ],
        [
            'shopName' => __('auth.shop_name'),
            'domain' => __('auth.domain_name'),
            'email' => __('auth.email_address'),
            'password' => __('auth.password_label'),
            'passwordConfirmation' => __('auth.confirmation_password'),
            'agreeTerms' => __('auth.terms_and_conditions'),
        ],
    );

    $domainName = strtolower($validated['domain']);

    $data = [
        'name' => $domainName,
        'domain' => $domainName.'.'.config('tenancy.central_domains')[0],
        'email' => $validated['email'],
        'password' => $validated['password'],
        'shop_name' => $validated['shopName'],
        'business_type' => 'other',
        'other_business_type' => null,
        'full_name' => $validated['shopName'],
    ];

    $tenant = $registerTenant->create($data);

    $securedDomain = 'https://'.$tenant->domains->first()->domain;

    return redirect()->to($securedDomain, secure: true);
};

?>

<div class="auth-register">
  <div class="auth-login__logo-wrap">
    <img src="{{ asset('assets/logo/image.png') }}" alt="Lakasir" class="auth-login__logo">
  </div>

  <div class="auth-form__header">
    <h1 class="auth-form__title">{{ __('auth.sign_up') }} Lakasir</h1>
    <p class="auth-form__subtitle">{{ __('auth.enter_your_details') }}</p>
  </div>

  <form wire:submit="create" class="auth-form__body auth-form__body--register">
    <div class="auth-register__fields">
      <div class="auth-field">
        <label for="register-shop-name" class="sr-only">{{ __('auth.shop_name') }}</label>
        <input
          id="register-shop-name"
          type="text"
          wire:model="shopName"
          placeholder="{{ __('auth.shop_name_placeholder') }}"
          class="auth-input @error('shopName') auth-input--error @enderror"
        >
        @error('shopName')
          <p class="auth-field__error">{{ $message }}</p>
        @enderror
      </div>

      <div class="auth-field">
        <label for="register-domain" class="sr-only">{{ __('auth.domain_name') }}</label>
        <div class="auth-domain-wrap @error('domain') auth-domain-wrap--error @enderror">
          <input
            id="register-domain"
            type="text"
            wire:model="domain"
            placeholder="{{ __('auth.domain_name_placeholder') }}"
            class="auth-domain-input"
          >
          <span class="auth-domain-suffix">.{{ config('tenancy.central_domains')[0] }}</span>
        </div>
        @error('domain')
          <p class="auth-field__error">{{ $message }}</p>
        @enderror
      </div>

      <div class="auth-field">
        <label for="register-email" class="sr-only">{{ __('auth.email_address') }}</label>
        <input
          id="register-email"
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
        <label for="register-password" class="sr-only">{{ __('auth.password_label') }}</label>
        <input
          id="register-password"
          type="password"
          wire:model="password"
          autocomplete="new-password"
          placeholder="{{ __('auth.password_placeholder') }}"
          class="auth-input @error('password') auth-input--error @enderror"
        >
        @error('password')
          <p class="auth-field__error">{{ $message }}</p>
        @enderror
      </div>

      <div class="auth-field">
        <label for="register-password-confirmation" class="sr-only">{{ __('auth.confirmation_password') }}</label>
        <input
          id="register-password-confirmation"
          type="password"
          wire:model="passwordConfirmation"
          autocomplete="new-password"
          placeholder="{{ __('auth.confirmation_password_placeholder') }}"
          class="auth-input"
        >
      </div>
    </div>

    <div class="auth-terms">
      <label class="auth-terms__label">
        <input
          type="checkbox"
          wire:model="agreeTerms"
          class="auth-remember__checkbox"
        >
        <span class="auth-terms__text">
          <span class="auth-terms__copy">{{ __('auth.terms_prefix') }}</span>
          <span class="auth-terms__link">{{ __('auth.terms_and_conditions') }}</span>
        </span>
      </label>
      @error('agreeTerms')
        <p class="auth-field__error">{{ $message }}</p>
      @enderror
    </div>

    <button
      type="submit"
      wire:loading.attr="disabled"
      class="auth-submit"
    >
      <svg wire:loading wire:target="create" class="auth-submit__spinner animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
      <span>{{ __('auth.create_shop') }}</span>
    </button>
  </form>
</div>
