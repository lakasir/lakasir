<?php

use function Livewire\Volt\layout;

layout('components.layouts.app');

?>

@php
    $menuItems = [
        [
            'key' => 'transaction',
            'label' => __('menu.transaction'),
            'route' => 'filament.tenant.pages.cashier',
            'icon' => 'transaction',
            'accent' => '#d74b76',
            'permission' => 'read selling',
        ],
        [
            'key' => 'product',
            'label' => __('menu.product'),
            'route' => 'filament.tenant.resources.products.index',
            'icon' => 'product',
            'accent' => '#522258',
            'permission' => 'read product',
        ],
        [
            'key' => 'member',
            'label' => __('menu.member'),
            'route' => 'filament.tenant.resources.members.index',
            'icon' => 'member',
            'accent' => '#8c3061',
            'permission' => 'read member',
        ],
        [
            'key' => 'profile',
            'label' => __('menu.profile'),
            'route' => 'settings.profile',
            'icon' => 'profile',
            'accent' => '#c63c51',
            'permission' => null,
        ],
        [
            'key' => 'about',
            'label' => __('menu.about'),
            'route' => 'settings.about',
            'icon' => 'about',
            'accent' => '#d95f59',
            'permission' => null,
        ],
        [
            'key' => 'setting',
            'label' => __('menu.setting'),
            'route' => 'settings.general',
            'icon' => 'setting',
            'accent' => '#973131',
            'permission' => null,
        ],
    ];

    $menuItems = array_map(function (array $item): array {
        $hasRoute = \Illuminate\Support\Facades\Route::has($item['route']);

        $hasPermission = true;
        if (! empty($item['permission']) && auth()->check()) {
            $hasPermission = (bool) auth()->user()?->can($item['permission']);
        }

        $item['disabled'] = ! $hasRoute || ! $hasPermission;
        $item['href'] = $item['disabled'] ? '#' : route($item['route']);

        return $item;
    }, $menuItems);

    $supportCenterUrl = 'https://lakasir.com';
    try {
        $configuredSupportCenterUrl = \App\Models\Tenants\Setting::get('support_center_url');
        if (is_string($configuredSupportCenterUrl) && filled($configuredSupportCenterUrl)) {
            $supportCenterUrl = $configuredSupportCenterUrl;
        }
    } catch (\Throwable $exception) {
        // Keep default support center URL when tenant settings are not available.
    }
@endphp

<div class="flex min-h-[calc(100vh-150px)] flex-col justify-between pb-4">
    <section class="mx-auto w-full max-w-[770px] pt-4 md:pt-8">
        <x-dashboard.menu-grid :items="$menuItems" />
    </section>

    <div class="pb-2 pt-10 text-center">
        <a
            href="{{ $supportCenterUrl }}"
            class="text-sm text-[#888888] underline transition hover:text-[#555555]"
            target="_blank"
            rel="noopener noreferrer"
        >
            {{ __('menu.support_center') }}
        </a>
    </div>
</div>