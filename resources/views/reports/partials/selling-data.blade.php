<div class="max-w-full">
  <div class="text-center space-y-2">
    <h1 class="text-3xl font-semibold">{{ __('Selling Report') }}</h1>
    <h3 class="text-xl">{{ $header['shop_name'] }}</h3>
  </div>
  <p class="mb-4">{{ __('Period') }}: <b>{{ $header['start_date'] }} - {{ $header['end_date'] }}</b></p>

  <x-table class="w-full table-fixed">
    <x-table-header>
      <x-table-header-cell>@lang('SKU')</x-table-header-cell>
      <x-table-header-cell>@lang('Product Name')</x-table-header-cell>
      <x-table-header-cell>@lang('Price')</x-table-header-cell>
      <x-table-header-cell>@lang('Qty')</x-table-header-cell>
      <x-table-header-cell>@lang('Selling')</x-table-header-cell>
      <x-table-header-cell>@lang('Discount')</x-table-header-cell>
      <x-table-header-cell>@lang('Net Selling')</x-table-header-cell>
      <x-table-header-cell>@lang('Gross Profit')</x-table-header-cell>
      <x-table-header-cell>@lang('Net Profit')</x-table-header-cell>
    </x-table-header>

    <tbody>
      @foreach($reports as $key => $report)
        <x-table-row>
          <x-table-cell>{{ $report['sku'] }}</x-table-cell>
          <x-table-cell>{{ $report['name'] }}</x-table-cell>
          <x-table-cell class="number">{{ $report['selling_price'] }}</x-table-cell>
          <x-table-cell>{{ $report['qty'] }}</x-table-cell>
          <x-table-cell class="number">{{ $report['selling'] }}</x-table-cell>
          <x-table-cell class="number">{{ $report['discount_price'] }}</x-table-cell>
          <x-table-cell class="number">{{ $report['total_after_discount'] }}</x-table-cell>
          <x-table-cell class="number">{{ $report['gross_profit'] }}</x-table-cell>
          <x-table-cell class="number">{{ $report['net_profit'] }}</x-table-cell>
        </x-table-row>
      @endforeach
      <x-table-row>
        <x-table-cell colspan="3">{{ __('Total') }}</x-table-cell>
        <x-table-cell class="number">{{ $footer['total_qty'] }}</x-table-cell>
        <x-table-cell class="number">{{ $footer['total_gross'] }}</x-table-cell>
        <x-table-cell class="number">{{ $footer['total_discount_per_item'] }}</x-table-cell>
        <x-table-cell class="number">{{ $footer['total_net_price_after_discount_per_item'] }}</x-table-cell>
        <x-table-cell class="number">{{ $footer['total_gross_profit'] }}</x-table-cell>
        <x-table-cell class="number">{{ $footer['total_net_profit_before_discount_selling'] }}</x-table-cell>
      </x-table-row>
    </tbody>
  </x-table>

  <x-table class="w-full table-fixed mt-4">
    <x-table-header>
      <x-table-row>
        <x-table-header-cell colspan="8" class="text-center" style="text-align: center;">{{ __('Grand Total') }}</x-table-header-cell>
      </x-table-row>
      <x-table-row>
        <x-table-header-cell>{{ __('Cost') }}</x-table-header-cell>
        <x-table-header-cell>{{ __('Penjualan') }}</x-table-header-cell>
        <x-table-header-cell>{{ __('Discount per Penjualan') }}</x-table-header-cell>
        <x-table-header-cell>{{ __('Discount per Item') }}</x-table-header-cell>
        <x-table-header-cell>{{ __('Penjualan Setelah Discount') }}</x-table-header-cell>
        <x-table-header-cell>{{ __('Keuntungan Kotor') }}</x-table-header-cell>
        <x-table-header-cell>{{ __('Keuntungan Bersih Sebelum Diskon Penjualan') }}</x-table-header-cell>
        <x-table-header-cell>{{ __('Keuntungan Bersih Setelah Diskon Penjualan') }}</x-table-header-cell>
      </x-table-row>
    </x-table-header>
    <tbody>
      <x-table-row>
        <x-table-cell class="number"><b>{{ $footer['total_cost'] }}</b></x-table-cell>
        <x-table-cell class="number"><b>{{ $footer['total_gross'] }}</b></x-table-cell>
        <x-table-cell class="number"><b>{{ $footer['total_discount'] }}</b></x-table-cell>
        <x-table-cell class="number"><b>{{ $footer['total_discount_per_item'] }}</b></x-table-cell>
        <x-table-cell class="number"><b>{{ $footer['total_net_price_after_discount_per_item'] }}</b></x-table-cell>
        <x-table-cell class="number"><b>{{ $footer['total_gross_profit'] }}</b></x-table-cell>
        <x-table-cell class="number"><b>{{ $footer['total_net_profit_before_discount_selling'] }}</b></x-table-cell>
        <x-table-cell class="number"><b>{{ $footer['total_net_profit_after_discount_selling'] }}</b></x-table-cell>
      </x-table-row>
    </tbody>
  </x-table>
</div>

