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
        <h1>{{ __('Product Report') }}</h1>
        <h3>{{ $header['shop_name'] }}</h3>
    </div>
    <p>{{ __('Period') }}: <b>{{ $header['start_date'] }} - {{ $header['end_date'] }}</b></p>
    <table>
        <thead>
            <!-- <th>#</th> -->
            <th>SKU</th>
            <th>{{ __('Product Name') }}</th>
            <!-- <th style="width: 100px;" class="number">{{ __('Cost') }}</th> -->
            <th style="width: 100px;" class="number">{{ __('Price') }}</th>
            <th>{{ __('Qty') }}</th>
            <th style="width: 100px;" class="number">{{ __('Selling') }}</th>
            <th style="width: 100px;" class="number">{{ __('Discount') }}</th>
            <!-- <th style="width: 100px;" class="number">{{ __('Total Cost') }}</th> -->
            <th style="width: 100px;" class="number">{{ __('Net Selling') }}</th>
            <th style="width: 100px;" class="number">{{ __('Gross Profit') }}</th>
            <th style="width: 100px;" class="number">{{ __('Net Profit') }}</th>
        </thead>
      @foreach($reports as $key => $report)
        <tbody>
            <tr>
                <!-- <td>{{ $report['code'] }}</td> -->
                <td>{{ $report['sku'] }}</td>
                <td>{{ $report['name'] }}</td>
                <!-- <td class="number">{{ $report['initial_price'] }}</td> -->
                <td class="number">{{ $report['selling_price'] }}</td>
                <td>{{ $report['qty'] }}</td>
                <td class="number">{{ $report['selling'] }}</td>
                <td class="number">{{ $report['discount_price'] }}</td>
                <!-- <td class="number">{{ $report['cost'] }}</td> -->
                <td class="number">{{ $report['total_after_discount'] }}</td>
                <td class="number">{{ $report['gross_profit'] }}</td>
                <td class="number">{{ $report['net_profit'] }}</td>
            </tr>
        @endforeach
            <tr>
              <td colspan="3">{{ __('Total') }}</td>
              <td class="number">{{ $footer['total_qty'] }}</td>
              <td class="number">{{ $footer['total_gross'] }}</td>
              <td class="number">{{ $footer['total_discount_per_item'] }}</td>
              <td class="number">{{ $footer['total_net'] }}</td>
              <td class="number">{{ $footer['total_gross_profit'] }}</td>
              <td class="number">{{ $footer['total_net_profit_before_discount_selling'] }}</td>
            </tr>
        </tbody>
    </table>

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



