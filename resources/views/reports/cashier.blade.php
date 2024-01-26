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
    @foreach($reports as $key => $report)
    <table>
        <thead>
            <th>Kasir</th>
            <th>Nomor Transaksi</th>
            <th>Tanggal dan jam</th>
            <th>Items</th>
            <th style="width: 100px;" class="number">Harga</th>
            <th style="width: 100px;" class="number">Modal</th>
            <th style="width: 100px;" class="number">Keuntungan Kotor</th>
            <th style="width: 100px;" class="number">Keuntungan Bersih</th>
        </thead>
        @foreach($report['transaction']['items'] as $item)
        <tbody>
            <tr>
                <td>{{ $loop->first ? $report['transaction']['user'] : '' }}</td>
                <td>{{ $loop->first ? $report['transaction']['number'] : '' }}</td>
                <td>{{ $loop->first ? $report['transaction']['created_at'] : '' }}</td>
                <td>{{ $item['product'] }}</td>
                <td class="number">{{ $item['product_price'] }} x {{ $item['quantity'] }}</td>
                <td class="number">{{ $item['cost'] }}</td>
                <td class="number">{{ $item['price'] }}</td>
                <td class="number">{{ $item['net_profit'] }}</td>
            </tr>
        @endforeach
        <tr>
            <td><b>Total</b></td>
            <td colspan="4"></td>
            <td class="number"><b>{{ $report['total']['cost'] }}</b></td>
            <td class="number"><b>{{ $report['total']['gross_profit'] }}</b></td>
            <td class="number"><b>{{ $report['total']['net_profit'] }}</b></td>
        </tr>
        </tbody>
    </table>
    @endforeach
    <div class="total-section">
        <p>Total pendapatan kotor: <b>{{ $footer['total_gross_profit'] }}</b></p>
        <p>Total biaya: <b>{{ $footer['total_cost'] }}</b></p>
        <p>Total pendapatan bersih: <b>{{ $footer['total_net_profit'] }}</b></p>
    </div>
</body>
</html>

