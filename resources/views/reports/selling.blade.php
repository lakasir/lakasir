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
        <h1>{{ __('Selling report') }}</h1>
        <h3>{{ $header['shop_name'] }}</h3>
    </div>
    <p>{{ __('Period') }}: <b>{{ $header['start_date'] }} - {{ $header['end_date'] }}</b></p>
    <table>
        <thead>
            <th>#</th>
            <th>SKU</th>
            <th>{{ __('Product name') }}</th>
            <th style="width: 100px;" class="number">{{ __('Selling price') }}</th>
            <th>{{ __('Qty') }}</th>
            <th style="width: 100px;" class="number">{{ __('Selling') }}</th>
            <th style="width: 100px;" class="number">{{ __('Discount price') }}</th>
            <th style="width: 100px;" class="number">{{ __('Cost') }}</th>
        </thead>
      @foreach($reports as $key => $report)
        <tbody>
            <tr>
                <td>{{ $report['code'] }}</td>
                <td>{{ $report['sku'] }}</td>
                <td>{{ $report['name'] }}</td>
                <td class="number">{{ $report['selling_price'] }}</td>
                <td>{{ $report['qty'] }}</td>
                <td class="number">{{ $report['selling'] }}</td>
                <td class="number">{{ $report['discount_price'] }}</td>
                <td class="number">{{ $report['initial_price'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <table>
      <tr>
        <td colspan="5">Grand Total</td>
        <td class="number"><b>{{ $footer['total_cost'] }}</b></td>
        <td class="number"><b>{{ $footer['total_gross_profit'] }}</b></td>
        <td class="number"><b>{{ $footer['total_net_profit'] }}</b></td>
      </tr>
    </table>
</body>
</html>


