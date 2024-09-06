<div class="max-w-full">
  <div class="text-center space-y-2">
    <h1 class="text-3xl font-semibold">{{ __('Purchasing Report') }}</h1>
    <h3 class="text-xl">{{ $header['shop_name'] }}</h3>
  </div>
  <p class="mb-4">{{ __('Period') }}: <b>{{ $header['start_date'] }} - {{ $header['end_date'] }}</b></p>
  <div class="space-y-4">
    @foreach($reports as $key => $report)
      <x-table class="w-full table-fixed">
        <x-table-header>
          <x-table-row>
            <x-table-header-cell colspan="6"> {{ __('Supplier') }}:</x-table-header-cell>
            <x-table-header-cell> {{ $report['supplier'] }}</x-table-header-cell>
          </x-table-row>
          <x-table-row>
            <x-table-header-cell colspan="6"> {{ __('Date') }}:</x-table-header-cell>
            <x-table-header-cell> {{ $report['date'] }}</x-table-header-cell>
          </x-table-row>
        </x-table-header>
        <x-table-header>
          <x-table-header-cell>{{ __('Product name') }}</x-table-header-cell>
          <x-table-header-cell>{{ __('Unit') }}</x-table-header-cell>
          <x-table-header-cell>{{ __('Stock amount') }}</x-table-header-cell>
          <x-table-header-cell>{{ __('Cost per stock') }}</x-table-header-cell>
          <x-table-header-cell>{{ __('Total cost') }}</x-table-header-cell>
          <x-table-header-cell>{{ __('Price per stock') }}</x-table-header-cell>
          <x-table-header-cell>{{ __('Total price') }}</x-table-header-cell>
        </x-table-header>
        <tbody>
          @foreach ($report['stocks'] as $key => $stock)
            <x-table-row>
              <x-table-cell>{{ $stock['product_name'] }}</x-table-cell>
              <x-table-cell>{{ $stock['product_unit'] }}</x-table-cell>
              <x-table-cell>{{ $stock['init_stock'] }}</x-table-cell>
              <x-table-cell>{{ $stock['initial_price'] }}</x-table-cell>
              <x-table-cell>{{ $stock['total_initial_price'] }}</x-table-cell>
              <x-table-cell>{{ $stock['selling_price'] }}</x-table-cell>
              <x-table-cell>{{ $stock['total_selling_price'] }}</x-table-cell>
            </x-table-row>
          @endforeach
          <x-table-row class="border-t border-gray-200 dark:border-gray-600">
            <x-table-cell colspan="4">{{ __('Subtotal') }}</x-table-cell>
            <x-table-cell colspan="2">{{ $report['subtotal_total_initial_price'] }}</x-table-cell>
            <x-table-cell>{{ $report['subtotal_total_selling_price'] }}</x-table-cell>
          </x-table-row>
        </tbody>
      </x-table>
      @endforeach
  </div>
  <x-table class="w-full table-fixed mt-4">
    <x-table-header>
      <x-table-row>
        <x-table-header-cell colspan="2" style="text-align: center;">{{ __('Grand Total') }}</x-table-header-cell>
      </x-table-row>
      <x-table-row>
        <x-table-header-cell>{{ __('Cost') }}</x-table-header-cell>
        <x-table-header-cell class="number" style="text-align: right;" class="text-right"><b>{{ $footer['grand_total_initial_price'] }}</b></x-table-header-cell>
      </x-table-row>
      <x-table-row>
        <x-table-header-cell>{{ __('Selling price') }}</x-table-header-cell>
        <x-table-header-cell class="number" style="text-align: right;" class="text-right"><b>{{ $footer['grand_total_selling_price'] }}</b></x-table-header-cell>
      </x-table-row>
    </x-table-header>
  </x-table>
</div>

