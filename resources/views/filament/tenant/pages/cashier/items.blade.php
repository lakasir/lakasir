<div>
  @forelse($cartItems as $item)
    @php($sub_total += $item->price)
    <div class="flex justify-between bg-white dark:border-gray-900 dark:bg-gray-900">
      <div class="flex items-center space-x-3">
        <div class="space-y-3">
          <p class="font-semibold"> {{ $item->product->name }}</p>
          <div class="flex space-x-3 h-8">
            <p>{{ $item->product->selling_price_label }} x {{ $item->qty }}</p>
          </div>
        </div>
      </div>
      <div class="flex items-center">
        <p class="font-semibold text-lakasir-primary">{{ $item->price_format_money }}</p>
      </div>
    </div>
  @empty
    <div class="flex justify-center items-center h-40 border bg-white rounded-lg dark:border-gray-900 dark:bg-gray-900">
      <x-heroicon-o-x-mark class="text-gray-900 dark:text-white h-10 w-10"/>
        <p class="text-3xl text-gray-600 dark:text-white">{{ __('No item') }}</p>
    </div>
  @endforelse
</div>

