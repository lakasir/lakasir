@php
use function Filament\Support\format_money;

@endphp
<div class="space-y-3">
  <div class="flex justify-between">
    <p>{{ __('Sub total') }}</p>
    <p class="font-bold text-primary">{{ format_money($sub_total, $currency) }}</p>
  </div>
  <div class="flex justify-between">
    <p>{{ __('Tax') }}</p>
    <p class="font-bold text-primary">{{ $tax }}%</p>
  </div>
  <div class="flex justify-between">
    <p>{{ __('Discount price') }}</p>
    <p class="font-bold text-primary">({{ format_money($this->discount_price, $currency) }})</p>
  </div>
  <hr/>
  <div class="flex justify-between">
    <p class="font-bold">{{ __('Total') }}</p>
    <p class="font-bold text-primary" x-ref="total" data-value="{{ $total_price }}">{{ format_money($total_price, $currency) }}</p>
  </div>
  <div class="flex justify-between">
    <p class="font-bold">{{ __('Money changes') }}</p>
    <p class="font-bold text-primary" x-ref="moneyChanges"></p>
  </div>
</div>

