@php
use function Filament\Support\format_money;

@endphp
<div class="space-y-3">
  <div class="flex justify-between">
    <p>{{ __('Sub total') }}</p>
    <p class="font-bold text-[#ff6600]">{{ format_money($sub_total, $currency) }}</p>
  </div>
  <div class="flex justify-between">
    <p>{{ __('Tax') }}</p>
    <p class="font-bold text-[#ff6600]">{{ $tax }}%</p>
  </div>
  <div class="flex justify-between">
    <p>{{ __('Discount Price') }}</p>
    <p class="font-bold text-[#ff6600]">({{ format_money($this->discount_price, $currency) }})</p>
  </div>
  <hr/>
  <div class="flex justify-between">
    <p class="font-bold">{{ __('Total') }}</p>
    <p class="font-bold text-[#ff6600]" x-ref="total">{{ format_money($total_price, $currency) }}</p>
  </div>
  <div class="flex justify-between">
    <p class="font-bold">{{ __('Changes') }}</p>
    <p class="font-bold text-[#ff6600]" x-ref="moneyChanges"></p>
  </div>
</div>

