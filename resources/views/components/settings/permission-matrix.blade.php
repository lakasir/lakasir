@props([
    'selected' => [],
])

@php
    $permissionGroups = [
        __('settings.roles.groups.transactions') => ['read selling', 'create selling', 'update selling', 'delete selling'],
        __('settings.roles.groups.products') => ['read product', 'create product', 'update product', 'delete product'],
        __('settings.roles.groups.members') => ['read member', 'create member', 'update member', 'delete member'],
        __('settings.roles.groups.reports') => ['generate selling report', 'generate cashier report', 'generate product report'],
        __('settings.roles.groups.settings') => ['read setting', 'update setting'],
        __('settings.roles.groups.users') => ['read user', 'create user', 'update user', 'delete user'],
    ];
@endphp

<div class="overflow-hidden rounded-lg border border-[#e7e7e7]">
    <table class="min-w-full divide-y divide-[#e7e7e7] bg-white">
        <thead class="bg-[#f9f9f9]">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#888888]">
                    {{ __('settings.roles.permission_group') }}
                </th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#888888]">
                    {{ __('settings.roles.permissions') }}
                </th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[#efefef] bg-white">
            @foreach($permissionGroups as $groupName => $permissions)
                <tr>
                    <td class="px-4 py-4 align-top text-sm font-semibold text-[#333333]">
                        {{ $groupName }}
                    </td>
                    <td class="px-4 py-4">
                        <div class="grid gap-2 sm:grid-cols-2">
                            @foreach($permissions as $permission)
                                <label class="inline-flex items-center gap-2 text-sm text-[#555555]">
                                    <input
                                        type="checkbox"
                                        class="h-4 w-4 rounded border-[#cccccc] text-lakasir-primary focus:ring-lakasir-primary"
                                        @if(in_array($permission, $selected, true)) checked @endif
                                        disabled
                                    >
                                    <span>{{ $permission }}</span>
                                </label>
                            @endforeach
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
