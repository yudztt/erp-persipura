<!DOCTYPE html>
<html>
<head>
    <title>Export PDF - Laporan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; }
        .summary { margin-bottom: 20px; }
    </style>
</head>
<body>

    <h2>{{ $reportTitle }}</h2>
    <p>Periode: {{ $start_date }} s/d {{ $end_date }}</p>

    <div class="summary">
        <ul>
            <li>{{ $type === 'inventory' ? 'Total Nilai Potensial' : 'Total Pendapatan' }}: Rp {{ number_format($totalRevenue, 0, ',', '.') }}</li>
            <li>{{ $type === 'inventory' ? 'Total Stok (Unit)' : 'Unit Terjual' }}: {{ number_format($unitsSold ?: $totalStockIn, 0, ',', '.') }}</li>
            <li>{{ $type === 'inventory' ? 'Total Nilai Aset (HPP)' : 'HPP (COGS)' }}: Rp {{ number_format($cogs, 0, ',', '.') }}</li>
            <li>Margin: {{ number_format($margin, 1, ',', '.') }}%</li>
        </ul>
    </div>

    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Kategori</th>
                <th>Unit Terjual</th>
                <th>Pendapatan (Rp)</th>
                <th>Rata-Rata Harga (Rp)</th>
                <th>Sisa Stok</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData as $row)
            <tr>
                <td>{{ $row['produk'] }}</td>
                <td>{{ $row['kategori'] }}</td>
                <td>{{ $row['unit_terjual'] }}</td>
                <td>{{ number_format($row['pendapatan'], 0, ',', '.') }}</td>
                <td>{{ number_format($row['rata_rata_harga'], 0, ',', '.') }}</td>
                <td>{{ $row['sisa_stok'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
