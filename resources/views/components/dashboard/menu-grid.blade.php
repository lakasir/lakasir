@props([
    'items' => [],
])

<div class="grid grid-cols-2 gap-[8px] md:grid-cols-3 md:gap-[6px]">
    @foreach($items as $item)
        <x-dashboard.menu-tile
            :title="$item['label']"
            :href="$item['href']"
            :icon="$item['icon']"
            :accent="$item['accent']"
            :disabled="$item['disabled']"
        />
    @endforeach
</div>
