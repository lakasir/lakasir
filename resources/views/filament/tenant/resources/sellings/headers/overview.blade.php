@php
  use App\Features\TotalRevenueInSellingTable;
  use App\Models\Tenants\Setting;
@endphp
@feature(TotalRevenueInSellingTable::class)
<div class="p-3">
  <p class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('Total revenue')</p>
  <p class="text-xl font-semibold">{{ price_format(10000) }}</p>
</div>
@endfeature
