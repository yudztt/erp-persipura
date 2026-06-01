<!DOCTYPE html>
<html>
<head>
    <title>Export PDF - Laporan</title>
    <style>
        @page { margin: 40px 30px; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12px; color: #333; }
        
        .header-table { width: 100%; border-bottom: 2px solid #2c3e50; padding-bottom: 10px; margin-bottom: 25px; }
        .header-table td { border: none; padding: 0; }
        .logo { height: 60px; width: auto; }
        .company-name { font-size: 24px; font-weight: bold; color: #2c3e50; margin: 0; letter-spacing: 0.5px; }
        .company-address { font-size: 11px; color: #7f8c8d; margin: 5px 0 0 0; }
        
        .report-title { font-size: 20px; text-align: center; font-weight: bold; margin-bottom: 5px; color: #34495e; text-transform: uppercase; letter-spacing: 1px; }
        .report-period { text-align: center; font-size: 12px; color: #7f8c8d; margin-bottom: 25px; font-style: italic; }
        
        .summary-container { width: 100%; margin-bottom: 25px; }
        .summary-box { 
            width: 23%; 
            display: inline-block; 
            background-color: #f8f9fa; 
            border: 1px solid #e9ecef;
            border-left: 4px solid #3498db;
            padding: 12px; 
            box-sizing: border-box; 
            vertical-align: top;
            margin-right: 1.5%;
        }
        .summary-box.last { margin-right: 0; }
        .summary-box.margin-box { border-left-color: #2ecc71; }
        .summary-box.cogs-box { border-left-color: #e74c3c; }
        .summary-box.unit-box { border-left-color: #f39c12; }
        
        .summary-label { font-size: 10px; color: #7f8c8d; text-transform: uppercase; font-weight: bold; margin-bottom: 8px; display: block; letter-spacing: 0.5px; }
        .summary-value { font-size: 16px; font-weight: bold; color: #2c3e50; }

        .data-table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 11px; }
        .data-table th, .data-table td { border: 1px solid #ecf0f1; padding: 10px 8px; text-align: left; }
        .data-table th { background-color: #34495e; color: #ffffff; font-weight: bold; text-transform: uppercase; font-size: 10px; letter-spacing: 0.5px; border-color: #34495e; }
        .data-table tr:nth-child(even) { background-color: #fbfbfc; }
        
        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        
        .footer { width: 100%; text-align: center; font-size: 10px; color: #95a5a6; position: fixed; bottom: -20px; padding-top: 10px; border-top: 1px solid #ecf0f1; }
        .page-number:before { content: counter(page); }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td width="12%" style="text-align: center;">
                <?php
                    $logo_path = public_path('img/logo-2.png');
                    $logo_src = '';
                    if (file_exists($logo_path)) {
                        $logo_data = base64_encode(file_get_contents($logo_path));
                        $logo_src = 'data:image/png;base64,' . $logo_data;
                    }
                ?>
                @if($logo_src)
                    <img src="{{ $logo_src }}" class="logo" alt="Logo">
                @endif
            </td>
            <td width="88%" style="vertical-align: middle; padding-left: 15px;">
                <h1 class="company-name">Cendrawasih Karsa Store</h1>
                <p class="company-address">Sistem Informasi Manajemen Laporan &bull; Divisi Penjualan &amp; Inventaris</p>
            </td>
        </tr>
    </table>

    <div class="report-title">{{ $reportTitle }}</div>
    <div class="report-period">
        Periode: {{ \Carbon\Carbon::parse($start_date)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($end_date)->translatedFormat('d F Y') }}
    </div>

    <div class="summary-container">
        <div class="summary-box">
            <span class="summary-label">{{ $type === 'inventory' ? 'Total Nilai Potensial' : 'Total Pendapatan' }}</span>
            <span class="summary-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
        </div>
        <div class="summary-box unit-box">
            <span class="summary-label">{{ $type === 'inventory' ? 'Total Stok (Unit)' : 'Unit Terjual' }}</span>
            <span class="summary-value">{{ number_format($unitsSold ?: $totalStockIn, 0, ',', '.') }}</span>
        </div>
        <div class="summary-box cogs-box">
            <span class="summary-label">{{ $type === 'inventory' ? 'Total Nilai Aset (HPP)' : 'HPP (COGS)' }}</span>
            <span class="summary-value">Rp {{ number_format($cogs, 0, ',', '.') }}</span>
        </div>
        <div class="summary-box margin-box last">
            <span class="summary-label">Margin Keseluruhan</span>
            <span class="summary-value">{{ number_format($margin, 1, ',', '.') }}%</span>
        </div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="30%">Produk</th>
                <th width="20%">Kategori</th>
                <th width="10%" class="text-center">Unit Terjual</th>
                <th width="15%" class="text-right">Pendapatan (Rp)</th>
                <th width="15%" class="text-right">Rata-Rata Harga (Rp)</th>
                <th width="10%" class="text-center">Sisa Stok</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData as $row)
            <tr>
                <td>{{ $row['produk'] }}</td>
                <td>{{ $row['kategori'] }}</td>
                <td class="text-center">{{ $row['unit_terjual'] }}</td>
                <td class="text-right">{{ number_format($row['pendapatan'], 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($row['rata_rata_harga'], 0, ',', '.') }}</td>
                <td class="text-center">{{ $row['sisa_stok'] }}</td>
            </tr>
            @endforeach
            @if(empty($reportData))
            <tr>
                <td colspan="6" class="text-center" style="padding: 20px; font-style: italic; color: #7f8c8d;">Tidak ada data untuk periode ini</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i:s') }} &nbsp; | &nbsp; Cendrawasih Karsa Store &copy; {{ date('Y') }} &nbsp; | &nbsp; Halaman <span class="page-number"></span>
    </div>

</body>
</html>
