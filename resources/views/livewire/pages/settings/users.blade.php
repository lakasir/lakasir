<?php

use function Livewire\Volt\layout;

layout('components.layouts.app');

?>

@php
    $users = \App\Models\Tenants\User::query()
        ->with('roles')
        ->latest()
        ->limit(30)
        ->get();
@endphp

<div class="grid gap-6 lg:grid-cols-[280px_minmax(0,1fr)]">
    <aside>
        <x-settings.sidebar current="users" />
    </aside>

    <section>
        <x-ui.card :title="__('settings.users.title')" :subtitle="__('settings.users.subtitle')">
            <div class="mb-4 flex flex-wrap items-center justify-end gap-2">
                @if(Route::has('filament.tenant.resources.users.create'))
                    <a href="{{ route('filament.tenant.resources.users.create') }}" class="inline-flex items-center justify-center rounded-full border border-[#f60] bg-[#f60] px-5 py-2 text-sm font-semibold text-white transition hover:bg-orange-600">
                        {{ __('settings.users.add_user') }}
                    </a>
                @endif

                @if(Route::has('filament.tenant.resources.users.index'))
                    <a href="{{ route('filament.tenant.resources.users.index') }}" class="inline-flex items-center justify-center rounded-full border border-[#f60] px-5 py-2 text-sm font-semibold text-[#f60] transition hover:bg-orange-50">
                        {{ __('settings.users.manage_all') }}
                    </a>
                @endif
            </div>

            @if($users->isEmpty())
                <x-ui.empty-state
                    :title="__('settings.users.empty_title')"
                    :description="__('settings.users.empty_description')"
                />
            @else
                <x-ui.table :columns="[
                    __('settings.users.table.name'),
                    __('settings.users.table.email'),
                    __('settings.users.table.roles'),
                    __('settings.users.table.status'),
                ]">
                    @foreach($users as $user)
                        <tr class="hover:bg-[#fafafa]">
                            <td class="px-4 py-3 text-sm font-semibold text-[#333333]">{{ $user->name }}</td>
                            <td class="px-4 py-3 text-sm text-[#555555]">{{ $user->email }}</td>
                            <td class="px-4 py-3 text-sm text-[#555555]">
                                {{ $user->roles->pluck('name')->join(', ') ?: '—' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if($user->deleted_at)
                                    <span class="inline-flex rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-700">{{ __('settings.users.inactive') }}</span>
                                @else
                                    <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-700">{{ __('settings.users.active') }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </x-ui.table>
            @endif
        </x-ui.card>
    </section>
</div>
