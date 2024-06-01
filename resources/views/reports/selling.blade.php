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
    <p>Periode: <b>{{ $header['start_date'] }} - {{ $header['end_date'] }}</b></p>
    <table>
        <thead>
            <th>SKU</th>
            <th>Nama Barang</th>
            <th style="width: 100px;" class="number">Harga Jual</th>
            <th>QTY Terjual</th>
            <th style="width: 100px;" class="number">Rupiah Penjualan</th>
            <th style="width: 100px;" class="number">Potongan Harga</th>
            <th style="width: 100px;" class="number">Net Penjualan</th>
        </thead>
      @foreach($reports as $key => $report)
        <tbody>
            <tr>
                <td>{{ $report['sku'] }}</td>
                <td>{{ $report['name'] }}</td>
                <td>{{ $report['selling_price'] }}</td>
                <td>{{ $report['qty'] }}</td>
                <td class="number">{{ $report['initial_price'] }}</td>
                <td class="number">{{ $report['initial_price'] }}</td>
                <td class="number">{{ $report['selling_price'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <table>
      <tr>
        <td colspan="4">Grand Total</td>
        <td class="number"><b>{{ $footer['total_cost'] }}</b></td>
        <td class="number"><b>{{ $footer['total_gross_profit'] }}</b></td>
        <td class="number"><b>{{ $footer['total_net_profit'] }}</b></td>
      </tr>
    </table>
</body>
</html>


