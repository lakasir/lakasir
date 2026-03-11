<?php

use function Livewire\Volt\layout;

layout('components.layouts.app');

?>

@php
    $roles = \Spatie\Permission\Models\Role::query()
        ->with('permissions')
        ->withCount('permissions')
        ->orderBy('name')
        ->get();

    $selectedRole = $roles->first();
    $selectedPermissions = $selectedRole?->permissions?->pluck('name')->all() ?? [];
@endphp

<div class="grid gap-6 lg:grid-cols-[280px_minmax(0,1fr)]">
    <aside>
        <x-settings.sidebar current="roles" />
    </aside>

    <section class="space-y-4">
        <x-ui.card :title="__('settings.roles.title')" :subtitle="__('settings.roles.subtitle')">
            <div class="mb-4 flex justify-end">
                @if(Route::has('filament.tenant.resources.roles.index'))
                    <a href="{{ route('filament.tenant.resources.roles.index') }}" class="inline-flex items-center justify-center rounded-full border border-[#f60] px-5 py-2 text-sm font-semibold text-[#f60] transition hover:bg-orange-50">
                        {{ __('settings.roles.manage_all') }}
                    </a>
                @endif
            </div>

            @if($roles->isEmpty())
                <x-ui.empty-state
                    :title="__('settings.roles.empty_title')"
                    :description="__('settings.roles.empty_description')"
                />
            @else
                <x-ui.table :columns="[
                    __('settings.roles.table.role'),
                    __('settings.roles.table.permissions_count'),
                ]">
                    @foreach($roles as $role)
                        <tr class="hover:bg-[#fafafa]">
                            <td class="px-4 py-3 text-sm font-semibold text-[#333333]">{{ $role->name }}</td>
                            <td class="px-4 py-3 text-sm text-[#555555]">{{ $role->permissions_count }}</td>
                        </tr>
                    @endforeach
                </x-ui.table>
            @endif
        </x-ui.card>

        <x-ui.card :title="__('settings.roles.matrix_title')" :subtitle="__('settings.roles.matrix_subtitle')">
            @if($selectedRole)
                <p class="mb-3 text-sm text-[#555555]">
                    {{ __('settings.roles.preview_for', ['role' => $selectedRole->name]) }}
                </p>
            @endif

            <x-settings.permission-matrix :selected="$selectedPermissions" />
        </x-ui.card>
    </section>
</div>
