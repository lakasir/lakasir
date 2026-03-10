<?php

use App\Models\Tenants\Printer;
use App\Models\Tenants\Setting;
use Illuminate\Support\Facades\Route;
use function Livewire\Volt\layout;
use function Livewire\Volt\mount;
use function Livewire\Volt\rules;
use function Livewire\Volt\state;

layout('components.layouts.app');

state([
    'name' => '',
    'driver' => 'usb',
    'port' => '',
    'ip_address' => '',
    'paper_size' => '80mm',
]);

mount(function (): void {
    try {
        $printer = Printer::query()->first();

        if ($printer) {
            $this->name = (string) $printer->name;
            $this->driver = (string) $printer->driver;
            $this->port = (string) ($printer->port ?? '');
            $this->ip_address = (string) ($printer->ip_address ?? '');
        }

        $this->paper_size = (string) Setting::get('receipt_paper_size', '80mm');
    } catch (\Throwable $exception) {
        // Keep defaults when printer table is not available.
    }
});

rules([
    'name' => ['required', 'string', 'max:255'],
    'driver' => ['required', 'in:usb,network'],
    'port' => ['nullable', 'string', 'max:255'],
    'ip_address' => ['nullable', 'ip'],
    'paper_size' => ['required', 'in:58mm,80mm'],
]);

$save = function (): void {
    $this->validate();

    try {
        $printer = Printer::query()->first();

        if ($printer) {
            $printer->update([
                'name' => $this->name,
                'driver' => $this->driver,
                'port' => $this->port,
                'ip_address' => $this->ip_address,
            ]);
        } else {
            Printer::query()->create([
                'name' => $this->name,
                'driver' => $this->driver,
                'port' => $this->port,
                'ip_address' => $this->ip_address,
            ]);
        }

        Setting::set('receipt_paper_size', $this->paper_size);

        session()->flash('status', __('settings.messages.saved'));
    } catch (\Throwable $exception) {
        $this->addError('save', __('settings.messages.save_failed'));
    }
};

?>

<div class="grid gap-6 lg:grid-cols-[280px_minmax(0,1fr)]">
    <aside>
        <x-settings.sidebar current="printer" />
    </aside>

    <section class="space-y-4">
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

        <x-ui.card :title="__('settings.printer.title')" :subtitle="__('settings.printer.subtitle')">
            <form wire:submit="save" class="space-y-6">
                <div class="grid gap-4 md:grid-cols-2">
                    <x-ui.input wire:model="name" name="name" :label="__('settings.printer.fields.name')" :error="$errors->first('name')" />

                    <div>
                        <label for="driver" class="mb-1 block text-sm font-medium text-gray-700">{{ __('settings.printer.fields.driver') }}</label>
                        <select id="driver" wire:model="driver" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-transparent focus:outline-none focus:ring-2 focus:ring-lakasir-primary">
                            <option value="usb">USB</option>
                            <option value="network">Network</option>
                        </select>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <x-ui.input wire:model="port" name="port" :label="__('settings.printer.fields.port')" :error="$errors->first('port')" />
                    <x-ui.input wire:model="ip_address" name="ip_address" :label="__('settings.printer.fields.ip_address')" :error="$errors->first('ip_address')" />
                </div>

                <div class="max-w-sm">
                    <label for="paper_size" class="mb-1 block text-sm font-medium text-gray-700">{{ __('settings.printer.fields.paper_size') }}</label>
                    <select id="paper_size" wire:model="paper_size" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-transparent focus:outline-none focus:ring-2 focus:ring-lakasir-primary">
                        <option value="58mm">58mm</option>
                        <option value="80mm">80mm</option>
                    </select>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                    @if(Route::has('filament.tenant.pages.printer'))
                        <a href="{{ route('filament.tenant.pages.printer') }}" class="inline-flex items-center justify-center rounded-full border border-[#f60] px-5 py-2.5 text-sm font-semibold text-[#f60] transition hover:bg-orange-50">
                            {{ __('settings.printer.open_advanced') }}
                        </a>
                    @endif

                    <button type="submit" class="inline-flex items-center justify-center rounded-full border border-[#f60] bg-[#f60] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-orange-600">
                        {{ __('settings.save') }}
                    </button>
                </div>
            </form>
        </x-ui.card>
    </section>
</div>
