@props([
    'current' => 'general',
])

@php
    $items = [
        [
            'section' => 'general',
            'key' => 'general',
            'label' => __('settings.nav.general'),
            'route' => 'settings.general',
            'icon' => 'general',
            'accent' => '#973131',
        ],
        [
            'section' => 'general',
            'key' => 'users',
            'label' => __('settings.nav.users'),
            'route' => 'settings.users',
            'icon' => 'users',
            'accent' => '#8c3061',
        ],
        [
            'section' => 'general',
            'key' => 'roles',
            'label' => __('settings.nav.roles'),
            'route' => 'settings.roles',
            'icon' => 'roles',
            'accent' => '#522258',
        ],
        [
            'section' => 'system',
            'key' => 'printer',
            'label' => __('settings.nav.printer'),
            'route' => 'settings.printer',
            'icon' => 'printer',
            'accent' => '#d74b76',
        ],
        [
            'section' => 'system',
            'key' => 'profile',
            'label' => __('settings.nav.profile'),
            'route' => 'settings.profile',
            'icon' => 'profile',
            'accent' => '#c63c51',
        ],
        [
            'section' => 'system',
            'key' => 'about',
            'label' => __('settings.nav.about'),
            'route' => 'settings.about',
            'icon' => 'about',
            'accent' => '#d95f59',
        ],
    ];

    $groups = collect($items)->groupBy('section');

    $sectionLabels = [
        'general' => __('settings.groups.general'),
        'system' => __('settings.groups.system'),
    ];
@endphp

<div class="rounded-lg bg-[#f2f2f2]">
    @foreach($groups as $section => $sectionItems)
        <div class="mb-4 last:mb-0">
            <p class="px-1 text-sm text-[#888888]">{{ $sectionLabels[$section] }}</p>

            <div class="mt-2 space-y-2">
                @foreach($sectionItems as $item)
                    @php
                        $isActive = $item['key'] === $current;
                        $canNavigate = Route::has($item['route']);

                        $itemClasses = implode(' ', [
                            'flex items-center gap-3 rounded-lg bg-[#f5f5f5] px-4 py-4 transition',
                            $isActive ? 'ring-1 ring-[#e9d9d3]' : 'hover:bg-[#eeeeee]',
                            $canNavigate ? '' : 'cursor-not-allowed opacity-50',
                        ]);
                    @endphp

                    <a
                        href="{{ $canNavigate ? route($item['route']) : '#' }}"
                        class="{{ $itemClasses }}"
                        @if($isActive)
                            aria-current="page"
                        @endif
                    >
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full" style="background-color: {{ $item['accent'] }};">
                            @switch($item['icon'])
                                @case('users')
                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-1a4 4 0 00-5-3.87M9 20H2v-1a4 4 0 015-3.87M16 3.13a4 4 0 010 7.75M8 3.13a4 4 0 000 7.75M12 11a4 4 0 100-8 4 4 0 000 8z" />
                                    </svg>
                                    @break

                                @case('roles')
                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3l8 4v6c0 4.418-3.582 8-8 8s-8-3.582-8-8V7l8-4z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.5 12.5l1.7 1.7L14.5 11" />
                                    </svg>
                                    @break

                                @case('printer')
                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8V4h10v4M6 17H5a2 2 0 01-2-2v-3a3 3 0 013-3h12a3 3 0 013 3v3a2 2 0 01-2 2h-1" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 14h10v6H7z" />
                                    </svg>
                                    @break

                                @case('profile')
                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12a4 4 0 100-8 4 4 0 000 8zm0 2c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z" />
                                    </svg>
                                    @break

                                @case('about')
                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9 9 0 100-18 9 9 0 000 18zm0-12v6m0-10h.01" />
                                    </svg>
                                    @break

                                @default
                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3h14a2 2 0 012 2v14l-4-2-4 2-4-2-4 2V5a2 2 0 012-2z" />
                                    </svg>
                            @endswitch
                        </span>

                        <span class="text-base font-semibold" style="color: {{ $item['accent'] }};">
                            {{ $item['label'] }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
