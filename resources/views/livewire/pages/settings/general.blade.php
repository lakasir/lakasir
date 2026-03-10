<?php

use App\Models\Tenants\Setting;
use App\Models\Tenants\User;
use Illuminate\Support\Facades\Route;
use function Livewire\Volt\layout;
use function Livewire\Volt\mount;
use function Livewire\Volt\state;

layout('components.layouts.app');

state([
    'active_sheet' => null,
    'currency' => 'IDR',
    'draft_currency' => 'IDR',
    'language' => 'en',
    'draft_language' => 'en',
    'display' => '80mm',
    'draft_display' => '80mm',
]);

mount(function (): void {
    /** @var User|null $user */
    $user = auth()->user();

    $locale = strtolower((string) ($user?->profile?->locale ?? app()->getLocale()));
    if (! in_array($locale, ['id', 'en', 'es'], true)) {
        $locale = 'en';
    }

    $this->language = $locale;
    $this->draft_language = $locale;

    try {
        $savedCurrency = strtoupper((string) Setting::get('currency_code', ''));
        if (! in_array($savedCurrency, ['IDR', 'USD'], true)) {
            $currencySymbol = (string) Setting::get('currency_symbol', 'Rp');
            $savedCurrency = $currencySymbol === '$' ? 'USD' : 'IDR';
        }

        $this->currency = $savedCurrency;
        $this->draft_currency = $savedCurrency;

        $savedPaperSize = (string) Setting::get('receipt_paper_size', '80mm');
        if (! in_array($savedPaperSize, ['58mm', '80mm'], true)) {
            $savedPaperSize = '80mm';
        }

        $this->display = $savedPaperSize;
        $this->draft_display = $savedPaperSize;
    } catch (\Throwable $exception) {
        // Keep defaults when tenant data is not available yet.
    }
});

$openSheet = function (string $sheet): void {
    if (! in_array($sheet, ['currency', 'language', 'display'], true)) {
        return;
    }

    $this->active_sheet = $sheet;

    if ($sheet === 'currency') {
        $this->draft_currency = $this->currency;
    }

    if ($sheet === 'language') {
        $this->draft_language = $this->language;
    }

    if ($sheet === 'display') {
        $this->draft_display = $this->display;
    }
};

$closeSheet = function (): void {
    $this->active_sheet = null;
};

$applySheet = function (): void {
    /** @var User|null $user */
    $user = auth()->user();

    try {
        if ($this->active_sheet === 'currency') {
            $value = in_array($this->draft_currency, ['IDR', 'USD'], true) ? $this->draft_currency : 'IDR';

            $this->currency = $value;
            $this->draft_currency = $value;

            if ($value === 'USD') {
                Setting::set('currency_symbol', '$');
                Setting::set('currency_decimal_places', 2);
            } else {
                Setting::set('currency_symbol', 'Rp');
                Setting::set('currency_decimal_places', 0);
            }

            Setting::set('currency_code', $value);
            Setting::set('currency_position', 'before');
        }

        if ($this->active_sheet === 'language') {
            $value = in_array($this->draft_language, ['id', 'en', 'es'], true) ? $this->draft_language : 'en';

            $this->language = $value;
            $this->draft_language = $value;

            if ($user) {
                if ($user->profile) {
                    $user->profile->update(['locale' => $value]);
                } else {
                    $user->profile()->create(['locale' => $value]);
                }
            }

            app()->setLocale($value);
        }

        if ($this->active_sheet === 'display') {
            $value = in_array($this->draft_display, ['58mm', '80mm'], true) ? $this->draft_display : '80mm';

            $this->display = $value;
            $this->draft_display = $value;

            Setting::set('receipt_paper_size', $value);
        }

        session()->flash('status', __('settings.messages.saved'));
        $this->active_sheet = null;
    } catch (\Throwable $exception) {
        $this->addError('save', __('settings.messages.save_failed'));
    }
};

?>

@php
    $dashboardUrl = Route::has('dashboard') ? route('dashboard') : url('/');
    $categoryRoute = 'settings.category';
    $categoryEnabled = Route::has($categoryRoute);
    $categoryUrl = $categoryEnabled ? route($categoryRoute) : '#';

    $languageLabels = [
        'id' => __('settings.landing.languages.id'),
        'en' => __('settings.landing.languages.en'),
        'es' => __('settings.landing.languages.es'),
    ];

    $displayLabels = [
        '58mm' => __('settings.landing.displays.58mm'),
        '80mm' => __('settings.landing.displays.80mm'),
    ];
@endphp

<div class="mx-auto w-full max-w-[820px] space-y-4 pb-10 pt-2">
    @if (session('status'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('status') }}
        </div>
    @endif

    @error('save')
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ $message }}
        </div>
    @enderror

    <header class="flex items-center gap-4 px-1 pt-1">
        <a href="{{ $dashboardUrl }}" class="inline-flex h-9 w-9 items-center justify-center rounded-full text-[#111111] transition hover:bg-[#eaeaea]" aria-label="Back">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>

        <h1 class="text-[20px] font-semibold text-[#111111]">{{ __('settings.landing.title') }}</h1>
    </header>

    <section class="space-y-2">
        <p class="px-1 text-sm text-[#888888]">{{ __('settings.landing.sections.general') }}</p>

        @if($categoryEnabled)
            <a href="{{ $categoryUrl }}" class="flex items-center gap-3 rounded-lg bg-[#f5f5f5] px-6 py-6 transition hover:bg-[#eeeeee] md:px-8">
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-[#973131]">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 5h5v5H5V5zm9 0h5v5h-5V5zM5 14h5v5H5v-5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16.5 13v6m-3-3h6" />
                    </svg>
                </span>

                <span class="text-base font-semibold text-[#973131]">{{ __('settings.landing.items.category') }}</span>
            </a>
        @else
            <div class="flex cursor-not-allowed items-center gap-3 rounded-lg bg-[#f5f5f5] px-6 py-6 opacity-50 md:px-8">
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-[#973131]">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 5h5v5H5V5zm9 0h5v5h-5V5zM5 14h5v5H5v-5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16.5 13v6m-3-3h6" />
                    </svg>
                </span>

                <span class="text-base font-semibold text-[#973131]">{{ __('settings.landing.items.category') }}</span>
            </div>
        @endif

        <button type="button" wire:click="openSheet('currency')" class="flex w-full items-center gap-3 rounded-lg bg-[#f5f5f5] px-6 py-6 text-left transition hover:bg-[#eeeeee] md:px-8">
            <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-[#8c3061]">
                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3a9 9 0 100 18 9 9 0 000-18z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.5 9.5c.5-1 1.5-1.5 2.5-1.5 1.2 0 2.5.7 2.5 2s-1.2 1.7-2.4 2c-1.2.3-2.4.8-2.4 2 0 1.3 1.2 2 2.4 2 1.1 0 2.1-.5 2.6-1.5" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 7v10" />
                </svg>
            </span>

            <span class="text-base font-semibold text-[#8c3061]">{{ __('settings.landing.items.currency') }}</span>
        </button>
    </section>

    <section class="space-y-2">
        <p class="px-1 text-sm text-[#888888]">{{ __('settings.landing.sections.system') }}</p>

        <button type="button" wire:click="openSheet('language')" class="flex w-full items-center gap-3 rounded-lg bg-[#f5f5f5] px-6 py-6 text-left transition hover:bg-[#eeeeee] md:px-8">
            <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-[#d74b76]">
                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3a9 9 0 100 18 9 9 0 000-18z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12h18M12 3c2.2 2.3 3.3 5.3 3.3 9S14.2 18.7 12 21M12 3C9.8 5.3 8.7 8.3 8.7 12S9.8 18.7 12 21" />
                </svg>
            </span>

            <div class="min-w-0 flex-1">
                <p class="text-base font-semibold text-[#d74b76]">{{ __('settings.landing.items.language') }}</p>
                <p class="text-sm text-[#777777]">{{ $languageLabels[$language] ?? strtoupper($language) }}</p>
            </div>
        </button>

        <button type="button" wire:click="openSheet('display')" class="flex w-full items-center gap-3 rounded-lg bg-[#f5f5f5] px-6 py-6 text-left transition hover:bg-[#eeeeee] md:px-8">
            <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-[#522258]">
                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7a2 2 0 012-2h12a2 2 0 012 2v8a2 2 0 01-2 2H6a2 2 0 01-2-2V7z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 18h8" />
                </svg>
            </span>

            <div class="min-w-0 flex-1">
                <p class="text-base font-semibold text-[#522258]">{{ __('settings.landing.items.display') }}</p>
                <p class="text-sm text-[#777777]">{{ $displayLabels[$display] ?? $display }}</p>
            </div>
        </button>
    </section>

    @if($active_sheet)
        @php
            $sheetTitle = match ($active_sheet) {
                'currency' => __('settings.landing.items.currency'),
                'language' => __('settings.landing.items.language'),
                'display' => __('settings.landing.items.display'),
                default => __('settings.landing.title'),
            };

            $currencyOptions = [
                ['value' => 'IDR', 'label' => __('settings.landing.currencies.idr'), 'symbol' => 'Rp'],
                ['value' => 'USD', 'label' => __('settings.landing.currencies.usd'), 'symbol' => '$'],
            ];

            $languageOptions = [
                ['value' => 'id', 'label' => __('settings.landing.languages.id')],
                ['value' => 'en', 'label' => __('settings.landing.languages.en')],
            ];

            $displayOptions = [
                ['value' => '58mm', 'label' => __('settings.landing.displays.58mm')],
                ['value' => '80mm', 'label' => __('settings.landing.displays.80mm')],
            ];
        @endphp

        <div class="fixed inset-0 z-40">
            <button type="button" wire:click="closeSheet" class="absolute inset-0 bg-[#0a0a0a]/80" aria-label="Close"></button>

            <div class="absolute bottom-0 left-1/2 w-full max-w-[460px] -translate-x-1/2 rounded-t-[12px] bg-white p-4 shadow-2xl md:bottom-auto md:top-1/2 md:w-[342px] md:-translate-y-1/2 md:rounded-[12px]">
                <div class="mb-5 flex items-center justify-between px-1">
                    <h2 class="text-[20px] font-semibold text-[#111111]">{{ $sheetTitle }}</h2>
                    <button type="button" wire:click="closeSheet" class="inline-flex h-8 w-8 items-center justify-center rounded-full text-[#555555] transition hover:bg-[#f2f2f2]" aria-label="Close">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-2">
                    @if($active_sheet === 'currency')
                        @foreach($currencyOptions as $option)
                            @php
                                $isSelected = $draft_currency === $option['value'];
                            @endphp

                            <button type="button" wire:click="$set('draft_currency', '{{ $option['value'] }}')" class="flex w-full items-center gap-3 rounded-lg border px-4 py-5 text-left transition {{ $isSelected ? 'border-[#f60] bg-[#f5f5f5]' : 'border-transparent bg-[#f5f5f5] hover:border-[#f2b08a]' }}">
                                <span class="inline-flex h-6 w-6 items-center justify-center rounded-full border text-xs font-semibold {{ $isSelected ? 'border-[#f60] text-[#f60]' : 'border-[#666666] text-[#666666]' }}">{{ $option['symbol'] }}</span>
                                <span class="text-base {{ $isSelected ? 'text-[#f60]' : 'text-[#555555]' }}">{{ $option['label'] }}</span>
                            </button>
                        @endforeach
                    @endif

                    @if($active_sheet === 'language')
                        @foreach($languageOptions as $option)
                            @php
                                $isSelected = $draft_language === $option['value'];
                            @endphp

                            <button type="button" wire:click="$set('draft_language', '{{ $option['value'] }}')" class="flex w-full items-center justify-between rounded-lg border px-4 py-5 text-left transition {{ $isSelected ? 'border-[#f60] bg-[#f5f5f5] text-[#f60]' : 'border-transparent bg-[#f5f5f5] text-[#555555] hover:border-[#f2b08a]' }}">
                                <span class="text-base">{{ $option['label'] }}</span>
                                @if($isSelected)
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                @endif
                            </button>
                        @endforeach
                    @endif

                    @if($active_sheet === 'display')
                        @foreach($displayOptions as $option)
                            @php
                                $isSelected = $draft_display === $option['value'];
                            @endphp

                            <button type="button" wire:click="$set('draft_display', '{{ $option['value'] }}')" class="flex w-full items-center justify-between rounded-lg border px-4 py-5 text-left transition {{ $isSelected ? 'border-[#f60] bg-[#f5f5f5] text-[#f60]' : 'border-transparent bg-[#f5f5f5] text-[#555555] hover:border-[#f2b08a]' }}">
                                <span class="text-base">{{ $option['label'] }}</span>
                                @if($isSelected)
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                @endif
                            </button>
                        @endforeach
                    @endif
                </div>

                <div class="mt-6 grid grid-cols-2 gap-3">
                    <button type="button" wire:click="closeSheet" class="inline-flex items-center justify-center rounded-full border border-[#f60] bg-[#f9f9f9] px-4 py-3 text-sm font-semibold text-[#f60] transition hover:bg-[#fff2eb]">
                        {{ __('settings.landing.actions.cancel') }}
                    </button>

                    <button type="button" wire:click="applySheet" class="inline-flex items-center justify-center rounded-full border border-[#f60] bg-[#f60] px-4 py-3 text-sm font-semibold text-[#f9f9f9] transition hover:bg-[#e65f00]">
                        {{ __('settings.landing.actions.set') }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
