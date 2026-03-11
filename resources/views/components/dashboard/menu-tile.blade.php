@props([
    'title',
    'href' => '#',
    'icon' => 'transaction',
    'accent' => '#d74b76',
    'disabled' => false,
])

@php
    $tileClasses = implode(' ', [
        'group flex h-[170px] flex-col items-center justify-center gap-3 rounded-lg bg-[#f5f5f5] px-4 text-center md:h-[200px]',
        $disabled
            ? 'cursor-not-allowed opacity-45'
            : 'transition duration-200 hover:-translate-y-0.5 hover:shadow-[0_10px_30px_rgba(17,17,17,0.08)] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-lakasir-primary focus-visible:ring-offset-2 focus-visible:ring-offset-[#f2f2f2]',
    ]);
@endphp

@if($disabled)
    <div class="{{ $tileClasses }}" aria-disabled="true">
@else
    <a href="{{ $href }}" class="{{ $tileClasses }}">
@endif
        <span class="inline-flex h-[82px] w-[82px] items-center justify-center rounded-full" style="background-color: {{ $accent }};">
            @switch($icon)
                @case('product')
                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
                    </svg>
                    @break

                @case('member')
                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11c1.657 0 3-1.567 3-3.5S17.657 4 16 4s-3 1.567-3 3.5 1.343 3.5 3 3.5zM8 11c1.657 0 3-1.567 3-3.5S9.657 4 8 4 5 5.567 5 7.5 6.343 11 8 11zm8 2c-2.761 0-5 1.79-5 4v1h10v-1c0-2.21-2.239-4-5-4zM8 13c-2.761 0-5 1.79-5 4v1h6v-1c0-1.525.735-2.89 1.886-3.782A6.384 6.384 0 008 13z" />
                    </svg>
                    @break

                @case('profile')
                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 12a4 4 0 100-8 4 4 0 000 8zm0 2c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z" />
                    </svg>
                    @break

                @case('about')
                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 21a9 9 0 100-18 9 9 0 000 18zm0-12v6m0-10h.01" />
                    </svg>
                    @break

                @case('setting')
                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11.983 6.009a1.2 1.2 0 012.034 0l.243.421a1.2 1.2 0 001.446.545l.462-.149a1.2 1.2 0 011.44 1.44l-.149.462a1.2 1.2 0 00.545 1.446l.421.243a1.2 1.2 0 010 2.034l-.421.243a1.2 1.2 0 00-.545 1.446l.149.462a1.2 1.2 0 01-1.44 1.44l-.462-.149a1.2 1.2 0 00-1.446.545l-.243.421a1.2 1.2 0 01-2.034 0l-.243-.421a1.2 1.2 0 00-1.446-.545l-.462.149a1.2 1.2 0 01-1.44-1.44l.149-.462a1.2 1.2 0 00-.545-1.446l-.421-.243a1.2 1.2 0 010-2.034l.421-.243a1.2 1.2 0 00.545-1.446l-.149-.462a1.2 1.2 0 011.44-1.44l.462.149a1.2 1.2 0 001.446-.545l.243-.421z" />
                        <circle cx="12" cy="12" r="2.5" stroke-width="1.8" />
                    </svg>
                    @break

                @default
                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 4h12a2 2 0 012 2v12l-2-1.5L16 18l-2-1.5L12 18l-2-1.5L8 18l-2-1.5L4 18V6a2 2 0 012-2z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 9h8M8 13h5" />
                    </svg>
            @endswitch
        </span>

        <span class="text-[16px] font-semibold leading-none" style="color: {{ $accent }};">
            {{ $title }}
        </span>
@if($disabled)
    </div>
@else
    </a>
@endif
