@extends('layouts.app')

@section('title', 'Laporan - Persipura ERP')
@section('header_title', 'Laporan')
@section('header_breadcrumb', '/ Analisis & Wawasan')

@section('content')
  <!-- Filter Atas & Aksi -->
  <form method="GET" action="{{ route('intelligence.reports') }}" style="display:flex; flex-wrap:wrap; gap:12px; align-items:center; margin-bottom:24px; background:#fff; padding:16px; border-radius:8px; border:1px solid var(--gray-200)">
    <div>
      <select name="type" class="form-input" style="min-width:180px; height:38px; padding:0 12px">
        <option value="sales" {{ $type === 'sales' ? 'selected' : '' }}>Laporan Penjualan</option>
        <option value="inventory" {{ $type === 'inventory' ? 'selected' : '' }}>Laporan Inventaris</option>
      </select>
    </div>
    
    <div style="display:flex; align-items:center; gap:8px">
      <input type="date" name="start_date" class="form-input" value="{{ $start_date }}" style="height:38px">
      <span style="color:var(--gray-400)">—</span>
      <input type="date" name="end_date" class="form-input" value="{{ $end_date }}" style="height:38px">
    </div>

    <button type="submit" class="btn" style="background:#C2185B; color:#fff; height:38px; border:none; padding:0 16px; font-weight:600; border-radius:4px">
      Buat Laporan
    </button>
    
    <button type="submit" name="export" value="pdf" class="btn btn-secondary" style="height:38px; margin-left: auto;">
      Ekspor PDF
    </button>
  </form>

  <!-- Ringkasan Statistik Laporan -->
  <div class="kpi-grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px; margin-bottom:24px">
    <!-- Total Pendapatan -->
    <div class="kpi-card" style="border-right: 4px solid #C2185B; padding:20px; background:#fff; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.05)">
      <div style="font-size:12px; font-weight:600; color:var(--gray-500); text-transform:uppercase; letter-spacing:0.5px">
        {{ $type === 'inventory' ? 'TOTAL NILAI POTENSIAL' : 'TOTAL PENDAPATAN' }}
      </div>
      <div style="font-size:28px; font-weight:700; color:#1E293B; margin:8px 0 4px 0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
    </div>

    <!-- Unit Terjual / Total Stok -->
    <div class="kpi-card" style="border-right: 4px solid #059669; padding:20px; background:#fff; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.05)">
      <div style="font-size:12px; font-weight:600; color:var(--gray-500); text-transform:uppercase; letter-spacing:0.5px">
        {{ $type === 'inventory' ? 'TOTAL STOK (UNIT)' : 'UNIT TERJUAL' }}
      </div>
      <div style="font-size:28px; font-weight:700; color:#1E293B; margin:8px 0 4px 0">
        {{ $type === 'inventory' ? number_format($totalStockIn, 0, ',', '.') : number_format($unitsSold, 0, ',', '.') }}
      </div>
    </div>

    <!-- HPP / COGS / Nilai Aset -->
    <div class="kpi-card" style="border-right: 4px solid #6366F1; padding:20px; background:#fff; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.05)">
      <div style="font-size:12px; font-weight:600; color:var(--gray-500); text-transform:uppercase; letter-spacing:0.5px">
        {{ $type === 'inventory' ? 'TOTAL NILAI ASET (HPP)' : 'HPP (COGS)' }}
      </div>
      <div style="font-size:28px; font-weight:700; color:#1E293B; margin:8px 0 4px 0">Rp {{ number_format($cogs, 0, ',', '.') }}</div>
      <div style="font-size:12px; font-weight:600; color:#059669">Margin {{ number_format($margin, 1, ',', '.') }}%</div>
    </div>
  </div>
  
  <!-- AI Analisis & Rekomendasi Laporan Penjualan -->
  @if($type === 'sales' && !empty($aiAnalysis))
  <div style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%); color: white; padding: 20px; border-radius: 12px; margin-bottom: 24px; border-left: 5px solid #e21c2c; box-shadow: 0 4px 20px rgba(0,0,0,0.15);">
      <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 10px;">
          <i class="ti ti-sparkles" style="color: #fca5a5; font-size: 20px;"></i>
          <span style="font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">AI Analisis Performa &amp; Rekomendasi</span>
      </div>
      <p style="font-size: 13px; line-height: 1.6; color: #cbd5e1; margin: 0; font-weight: 500;">{{ $aiAnalysis }}</p>
  </div>
  @endif

  <!-- Tabel Ringkasan Penjualan / Inventaris -->
  <div class="table-card" style="background:#fff; border-radius:8px; border:1px solid var(--gray-200); overflow:hidden">
    <div class="table-header" style="padding:16px; border-bottom:1px solid var(--gray-200)">
      <div style="font-size:16px; font-weight:700; color:#1E293B">{{ $reportTitle }}</div>
    </div>
    <div style="overflow-x:auto;">
      <table style="width:100%; border-collapse:collapse; text-align:left">
        <thead>
          <tr style="background:#F8FAFC; border-bottom:1px solid var(--gray-200)">
            <th style="padding:12px 16px; font-size:11px; font-weight:600; color:var(--gray-500); text-transform:uppercase">PRODUK</th>
            <th style="padding:12px 16px; font-size:11px; font-weight:600; color:var(--gray-500); text-transform:uppercase">KATEGORI</th>
            <th style="padding:12px 16px; font-size:11px; font-weight:600; color:var(--gray-500); text-transform:uppercase">UNIT TERJUAL</th>
            <th style="padding:12px 16px; font-size:11px; font-weight:600; color:var(--gray-500); text-transform:uppercase">PENDAPATAN (Rp)</th>
            <th style="padding:12px 16px; font-size:11px; font-weight:600; color:var(--gray-500); text-transform:uppercase">RATA-RATA HARGA (Rp)</th>
            @if($type === 'inventory')
              <th style="padding:12px 16px; font-size:11px; font-weight:600; color:var(--gray-500); text-transform:uppercase">SISA STOK</th>
            @endif
          </tr>
        </thead>
        <tbody>
          @forelse($reportData as $row)
          <tr style="border-bottom:1px solid var(--gray-100)">
            <td style="padding:14px 16px; font-size:14px; font-weight:600; color:#1E293B">{{ $row['produk'] }}</td>
            <td style="padding:14px 16px; font-size:14px; color:var(--gray-600)">{{ $row['kategori'] }}</td>
            <td style="padding:14px 16px; font-size:14px; color:var(--gray-600)">{{ $row['unit_terjual'] }}</td>
            <td style="padding:14px 16px; font-size:14px; font-weight:600; color:#1E293B">{{ number_format($row['pendapatan'], 0, ',', '.') }}</td>
            <td style="padding:14px 16px; font-size:14px; color:var(--gray-600)">{{ number_format($row['rata_rata_harga'], 0, ',', '.') }}</td>
            @if($type === 'inventory')
              <td style="padding:14px 16px; font-size:14px; color:var(--gray-600)">{{ $row['sisa_stok'] }}</td>
            @endif
          </tr>
          @empty
          <tr>
            <td colspan="{{ $type === 'inventory' ? 6 : 5 }}" style="padding:14px 16px; font-size:14px; color:var(--gray-600); text-align:center;">Tidak ada data pada periode ini.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection