<?php

use App\Models\Tenants\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use function Livewire\Volt\layout;
use function Livewire\Volt\mount;
use function Livewire\Volt\state;

layout('components.layouts.app');

state([
    'name' => '',
    'email' => '',
    'phone' => '',
    'address' => '',
    'locale' => 'en',
    'timezone' => 'Asia/Jakarta',
    'password' => '',
    'password_confirmation' => '',
]);

mount(function (): void {
    /** @var User|null $user */
    $user = auth()->user();

    if (! $user) {
        return;
    }

    $this->name = (string) $user->name;
    $this->email = (string) $user->email;
    $this->phone = (string) ($user->profile?->phone ?? '');
    $this->address = (string) ($user->profile?->address ?? '');
    $this->locale = (string) ($user->profile?->locale ?? app()->getLocale());
    $this->timezone = (string) ($user->profile?->timezone ?? 'Asia/Jakarta');
});

$save = function (): void {
    /** @var User|null $user */
    $user = auth()->user();

    if (! $user) {
        return;
    }

    $this->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => [
            'required',
            'email',
            'max:255',
            Rule::unique(User::class, 'email')->ignore($user->id),
        ],
        'phone' => ['nullable', 'string', 'max:30'],
        'address' => ['nullable', 'string', 'max:500'],
        'locale' => ['required', 'in:id,en,es'],
        'timezone' => ['required', 'string', 'max:100'],
        'password' => ['nullable', 'confirmed', 'min:8'],
    ]);

    $user->update([
        'name' => $this->name,
        'email' => $this->email,
    ]);

    $profilePayload = [
        'phone' => $this->phone,
        'address' => $this->address,
        'locale' => $this->locale,
        'timezone' => $this->timezone,
    ];

    if ($user->profile) {
        $user->profile->update($profilePayload);
    } else {
        $user->profile()->create($profilePayload);
    }

    if (filled($this->password)) {
        $user->update([
            'password' => Hash::make($this->password),
        ]);

        $this->password = '';
        $this->password_confirmation = '';
    }

    session()->flash('status', __('settings.messages.saved'));
};

?>

<div class="grid gap-6 lg:grid-cols-[280px_minmax(0,1fr)]">
    <aside>
        <x-settings.sidebar current="profile" />
    </aside>

    <section class="space-y-4">
        @if (session('status'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('status') }}
            </div>
        @endif

        <x-ui.card :title="__('settings.profile.title')" :subtitle="__('settings.profile.subtitle')">
            <form wire:submit="save" class="space-y-8">
                <x-form.section :title="__('settings.profile.sections.account')">
                    <div class="grid gap-4 md:grid-cols-2">
                        <x-ui.input wire:model="name" name="name" :label="__('settings.profile.fields.name')" :error="$errors->first('name')" />
                        <x-ui.input wire:model="email" type="email" name="email" :label="__('settings.profile.fields.email')" :error="$errors->first('email')" />
                    </div>
                </x-form.section>

                <x-form.section :title="__('settings.profile.sections.contact')">
                    <div class="grid gap-4 md:grid-cols-2">
                        <x-ui.input wire:model="phone" name="phone" :label="__('settings.profile.fields.phone')" :error="$errors->first('phone')" />
                        <x-ui.input wire:model="address" name="address" :label="__('settings.profile.fields.address')" :error="$errors->first('address')" />
                    </div>
                </x-form.section>

                <x-form.section :title="__('settings.profile.sections.localization')">
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="locale" class="mb-1 block text-sm font-medium text-gray-700">{{ __('settings.profile.fields.language') }}</label>
                            <select id="locale" wire:model="locale" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-transparent focus:outline-none focus:ring-2 focus:ring-lakasir-primary">
                                <option value="id">Bahasa Indonesia</option>
                                <option value="en">English</option>
                                <option value="es">Español</option>
                            </select>
                        </div>

                        <div>
                            <label for="timezone" class="mb-1 block text-sm font-medium text-gray-700">{{ __('settings.profile.fields.timezone') }}</label>
                            <select id="timezone" wire:model="timezone" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-transparent focus:outline-none focus:ring-2 focus:ring-lakasir-primary">
                                <option value="Asia/Jakarta">Asia/Jakarta</option>
                                <option value="Asia/Makassar">Asia/Makassar</option>
                                <option value="Asia/Jayapura">Asia/Jayapura</option>
                                <option value="Asia/Singapore">Asia/Singapore</option>
                                <option value="Asia/Bangkok">Asia/Bangkok</option>
                                <option value="UTC">UTC</option>
                            </select>
                        </div>
                    </div>
                </x-form.section>

                <x-form.section :title="__('settings.profile.sections.security')">
                    <div class="grid gap-4 md:grid-cols-2">
                        <x-ui.input wire:model="password" type="password" name="password" :label="__('settings.profile.fields.new_password')" :error="$errors->first('password')" />
                        <x-ui.input wire:model="password_confirmation" type="password" name="password_confirmation" :label="__('settings.profile.fields.confirm_password')" />
                    </div>
                </x-form.section>

                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center justify-center rounded-full border border-[#f60] bg-[#f60] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-orange-600">
                        {{ __('settings.save') }}
                    </button>
                </div>
            </form>
        </x-ui.card>
    </section>
</div>
