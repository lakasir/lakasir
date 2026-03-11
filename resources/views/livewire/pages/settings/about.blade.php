<?php

use App\Models\Tenants\About;
use App\Models\Tenants\Setting;
use function Livewire\Volt\layout;
use function Livewire\Volt\mount;
use function Livewire\Volt\state;

layout('components.layouts.app');

state([
    'shop_name' => '',
    'shop_location' => '',
    'version' => '-',
    'license' => 'MIT',
    'support_center_url' => 'https://lakasir.com',
]);

mount(function (): void {
    $about = About::query()->first();

    $this->shop_name = (string) ($about?->shop_name ?? '');
    $this->shop_location = (string) ($about?->shop_location ?? '');

    $version = trim((string) file_get_contents(base_path('version.txt')));
    if ($version !== '') {
        $this->version = $version;
    }

    if (is_file(base_path('LICENSE'))) {
        $licenseContents = trim((string) file_get_contents(base_path('LICENSE')));
        if ($licenseContents !== '') {
            $this->license = (string) strtok($licenseContents, "\n");
        }
    }

    try {
        $configuredSupportCenterUrl = Setting::get('support_center_url');
        if (is_string($configuredSupportCenterUrl) && filled($configuredSupportCenterUrl)) {
            $this->support_center_url = $configuredSupportCenterUrl;
        }
    } catch (\Throwable $exception) {
        // Keep fallback support center URL.
    }
});

?>

<div class="grid gap-6 lg:grid-cols-[280px_minmax(0,1fr)]">
    <aside>
        <x-settings.sidebar current="about" />
    </aside>

    <section>
        <x-ui.card :title="__('settings.about.title')" :subtitle="__('settings.about.subtitle')">
            <dl class="grid gap-4 sm:grid-cols-2">
                <div class="rounded-lg bg-[#f8f8f8] px-4 py-3">
                    <dt class="text-sm text-[#888888]">{{ __('settings.about.fields.application_name') }}</dt>
                    <dd class="mt-1 text-base font-semibold text-[#222222]">{{ config('app.name') }}</dd>
                </div>

                <div class="rounded-lg bg-[#f8f8f8] px-4 py-3">
                    <dt class="text-sm text-[#888888]">{{ __('settings.about.fields.version') }}</dt>
                    <dd class="mt-1 text-base font-semibold text-[#222222]">{{ $version }}</dd>
                </div>

                <div class="rounded-lg bg-[#f8f8f8] px-4 py-3">
                    <dt class="text-sm text-[#888888]">{{ __('settings.about.fields.license') }}</dt>
                    <dd class="mt-1 text-base font-semibold text-[#222222]">{{ $license }}</dd>
                </div>

                <div class="rounded-lg bg-[#f8f8f8] px-4 py-3">
                    <dt class="text-sm text-[#888888]">{{ __('settings.about.fields.store_name') }}</dt>
                    <dd class="mt-1 text-base font-semibold text-[#222222]">{{ $shop_name ?: '—' }}</dd>
                </div>

                <div class="rounded-lg bg-[#f8f8f8] px-4 py-3 sm:col-span-2">
                    <dt class="text-sm text-[#888888]">{{ __('settings.about.fields.store_address') }}</dt>
                    <dd class="mt-1 text-base font-semibold text-[#222222]">{{ $shop_location ?: '—' }}</dd>
                </div>
            </dl>

            <div class="mt-6 rounded-lg border border-[#ececec] bg-white p-4">
                <h3 class="text-base font-semibold text-[#222222]">{{ __('settings.about.support_title') }}</h3>
                <p class="mt-1 text-sm text-[#666666]">{{ __('settings.about.support_description') }}</p>
                <a href="{{ $support_center_url }}" target="_blank" rel="noopener noreferrer" class="mt-3 inline-flex text-sm font-semibold text-[#f60] underline">
                    {{ __('menu.support_center') }}
                </a>
            </div>
        </x-ui.card>
    </section>
</div>
