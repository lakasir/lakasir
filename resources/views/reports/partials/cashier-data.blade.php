<div class="max-w-full">
  <div class="text-center space-y-2">
    <h1 class="text-3xl font-semibold">Laporan kasir</h1>
    <h3 class="text-xl">{{ $header['shop_name'] }}</h3>
  </div>
  <p class="mb-4">{{ __('Period') }}: <b>{{ $header['start_date'] }} - {{ $header['end_date'] }}</b></p>
  <div class="space-y-4">
    @foreach($reports as $key => $report)
      <x-table class="w-full table-fixed">
        <x-table-header>
          <x-table-row>
            <x-table-header-cell colspan="3">{{ __('Cashier') }} : {{ $report['user'] }}</x-table-header-cell>
            <x-table-header-cell colspan="2"># {{ $report['number'] }}</x-table-header-cell>
            <x-table-header-cell colspan="2">{{ __('Date') }} : {{ $report['created_at'] }}</x-table-header-cell>
          </x-table-row>
          <x-table-row>
            <x-table-header-cell>{{ __('Items') }}</x-table-header-cell>
            <x-table-header-cell style="width: 100px;" class="number">{{ __('Price') }} </x-table-header-cell>
            <x-table-header-cell style="width: 100px;" class="number">{{ __('Cost') }}</x-table-header-cell>
            <x-table-header-cell style="width: 100px;" class="number">{{ __('Discount') }}</x-table-header-cell>
            <x-table-header-cell style="width: 100px;" class="number">{{ __('Total Harga') }}</x-table-header-cell>
            <x-table-header-cell style="width: 100px;" class="number">{{ __('Total Cost') }}</x-table-header-cell>
            <x-table-header-cell style="width: 100px;" class="number">{{ __('Total Sesudah Discount') }}</x-table-header-cell>
            <!-- <th style="width: 100px;" class="number">{{ __('Keuntungan Kotor') }}</th>
              <th style="width: 100px;" class="number">{{ __('Keuntungan Bersih') }}</th> -->
          </x-table-row>
        </x-table-header>
        <tbody>
          @foreach($report['transaction']['items'] as $item)
            <x-table-row>
              <x-table-cell>{{ $item['product'] }}</x-table-cell>
              <x-table-cell class="number">{{ $item['product_price'] }} x {{ $item['quantity'] }}</x-table-cell>
              <x-table-cell class="number">{{ $item['product_cost'] }} x {{ $item['quantity'] }}</x-table-cell>
              <x-table-cell class="number">({{ $item['discount_price'] }})</x-table-cell>
              <x-table-cell class="number">{{ $item['price'] }}</x-table-cell>
              <x-table-cell class="number">{{ $item['cost'] }}</x-table-cell>
              <x-table-cell class="number">{{ $item['total_after_discount'] }}</x-table-cell>
              <!-- <td class="number">{{ $item['gross_profit'] }}</td>
                <td class="number">{{ $item['net_profit'] }}</td> -->
            </x-table-row>
          @endforeach
          <x-table-row>
            <x-table-cell colspan="3"><b>{{ __('Sub Total') }}</b></x-table-cell>
            <x-table-cell class="number"><b>({{ $report['total']['discount'] }})</b></x-table-cell>
            <x-table-cell class="number"><b>{{ $report['total']['gross_selling'] }}</b></x-table-cell>
            <x-table-cell class="number"><b>{{ $report['total']['cost'] }}</b></x-table-cell>
            <x-table-cell class="number"><b>{{ $report['total']['net_selling'] }}</b></x-table-cell>
          </x-table-row>
          <x-table-row>
            <x-table-cell colspan="6"><b>{{ __('Discount Penjualan') }}</b></x-table-cell>
            <x-table-cell class="number"><b>({{ $report['total']['discount_selling'] }})</b></x-table-cell>
          </x-table-row>
          <x-table-row>
            <x-table-cell colspan="6"><b>Total</b></x-table-cell>
            <x-table-cell class="number"><b>{{ $report['total']['grand_total'] }}</b></x-table-cell>
          </x-table-row>
        </tbody>
      </x-table>
    @endforeach
  </div>
  <x-table class="mt-4">
    <x-table-header>
      <x-table-row>
        <x-table-header-cell colspan="8" style="text-align: center;">{{ __('Grand Total') }}</x-table-header-cell>
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
      <x-table-cell class="number"><b>{{ $footer['total_cost'] }}</b></x-table-cell>
      <x-table-cell class="number"><b>{{ $footer['total_gross'] }}</b></x-table-cell>
      <x-table-cell class="number"><b>{{ $footer['total_discount'] }}</b></x-table-cell>
      <x-table-cell class="number"><b>{{ $footer['total_discount_per_item'] }}</b></x-table-cell>
      <x-table-cell class="number"><b>{{ $footer['total_net'] }}</b></x-table-cell>
      <x-table-cell class="number"><b>{{ $footer['total_gross_profit'] }}</b></x-table-cell>
      <x-table-cell class="number"><b>{{ $footer['total_net_profit_before_discount_selling'] }}</b></x-table-cell>
      <x-table-cell class="number"><b>{{ $footer['total_net_profit_after_discount_selling'] }}</b></x-table-cell>
    </tbody>
  </x-table>

</div>
