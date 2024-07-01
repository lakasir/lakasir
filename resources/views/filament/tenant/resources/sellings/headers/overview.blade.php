@php
  use App\Features\TotalRevenueInSellingTable;
  use App\Models\Tenants\Setting;
@endphp
@feature(TotalRevenueInSellingTable::class)
<div class="p-3">
  <p class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('Total revenue')</p>
  <p class="text-xl font-semibold">{{ Number::currency(10000, Setting::get('currency', 'IDR')) }}</p>
</div>
@endfeature
