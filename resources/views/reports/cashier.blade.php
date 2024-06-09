<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            text-align: center;
        }

        h3 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tfoot {
            font-weight: bold;
        }

        p {
            text-align: right;
        }

        p b, td.number, th.number {
            text-align: right; /* Align numbers to the right */
        }

        div.total-section {
            margin-top: 20px;
            text-align: right;
        }
    </style>
</head>

<body>
    <div>
        <h1>Laporan kasir</h1>
        <h3>{{ $header['shop_name'] }}</h3>
    </div>
    <p>{{ __('Period') }}: <b>{{ $header['start_date'] }} - {{ $header['end_date'] }}</b></p>
    @foreach($reports as $key => $report)
    <table>
        <thead>
          <tr>
            <th colspan="3">{{ __('Cashier') }} : {{ $report['user'] }}</th>
            <th colspan="2"># {{ $report['number'] }}</th>
            <th colspan="2">{{ __('Date') }} : {{ $report['created_at'] }}</th>
          </tr>
          <tr>
            <th>{{ __('Items') }}</th>
            <th style="width: 100px;" class="number">{{ __('Price') }} </th>
            <th style="width: 100px;" class="number">{{ __('Cost') }}</th>
            <th style="width: 100px;" class="number">{{ __('Discount') }}</th>
            <th style="width: 100px;" class="number">{{ __('Total Harga') }}</th>
            <th style="width: 100px;" class="number">{{ __('Total Cost') }}</th>
            <th style="width: 100px;" class="number">{{ __('Total Sesudah Discount') }}</th>
            <!-- <th style="width: 100px;" class="number">{{ __('Keuntungan Kotor') }}</th>
            <th style="width: 100px;" class="number">{{ __('Keuntungan Bersih') }}</th> -->
          </tr>
        </thead>
        @foreach($report['transaction']['items'] as $item)
        <tbody>
            <tr>
                <td>{{ $item['product'] }}</td>
                <td class="number">{{ $item['product_price'] }} x {{ $item['quantity'] }}</td>
                <td class="number">{{ $item['cost'] }} x {{ $item['quantity'] }}</td>
                <td class="number">{{ $item['discount_price'] }}</td>
                <td class="number">{{ $item['price'] }}</td>
                <td class="number">{{ $item['cost'] }}</td>
                <td class="number">{{ $item['total_after_discount'] }}</td>
                <!-- <td class="number">{{ $item['gross_profit'] }}</td>
                <td class="number">{{ $item['net_profit'] }}</td> -->
            </tr>
        @endforeach
        <tr>
            <td colspan="3"><b>{{ __('Sub Total') }}</b></td>
            <td class="number"><b>{{ $report['total']['discount'] }}</b></td>
            <td class="number"><b>{{ $report['total']['gross_selling'] }}</b></td>
            <td class="number"><b>{{ $report['total']['cost'] }}</b></td>
            <td class="number"><b>{{ $report['total']['net_selling'] }}</b></td>
        </tr>
        <tr>
            <td colspan="6"><b>{{ __('Discount Penjualan') }}</b></td>
            <td class="number"><b>- {{ $report['total']['discount_selling'] }}</b></td>
        </tr>
        <tr>
            <td colspan="6"><b>Total</b></td>
            <td class="number"><b>{{ $report['total']['grand_total'] }}</b></td>
        </tr>
        </tbody>
    </table>
    @endforeach
    <table>
      <thead>
        <tr>
          <th colspan="8" style="text-align: center;">{{ __('Grand Total') }}</th>
        </tr>
        <tr>
          <th>{{ __('Cost') }}</th>
          <th>{{ __('Penjualan') }}</th>
          <th>{{ __('Discount per Penjualan') }}</th>
          <th>{{ __('Discount per Item') }}</th>
          <th>{{ __('Penjualan Setelah Discount') }}</th>
          <th>{{ __('Keuntungan Kotor') }}</th>
          <th>{{ __('Keuntungan Bersih Sebelum Diskon Penjualan') }}</th>
          <th>{{ __('Keuntungan Bersih Setelah Diskon Penjualan') }}</th>
        </tr>
      </thead>
      <tbody>
        <td class="number"><b>{{ $footer['total_cost'] }}</b></td>
        <td class="number"><b>{{ $footer['total_gross'] }}</b></td>
        <td class="number"><b>{{ $footer['total_discount'] }}</b></td>
        <td class="number"><b>{{ $footer['total_discount_per_item'] }}</b></td>
        <td class="number"><b>{{ $footer['total_net'] }}</b></td>
        <td class="number"><b>{{ $footer['total_gross_profit'] }}</b></td>
        <td class="number"><b>{{ $footer['total_net_profit_before_discount_selling'] }}</b></td>
        <td class="number"><b>{{ $footer['total_net_profit_after_discount_selling'] }}</b></td>
      </tbody>
    </table>
</body>
</html>

