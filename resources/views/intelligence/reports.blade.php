@extends('layouts.app')

@section('title', 'Laporan - Persipura ERP')
@section('header_title', 'Laporan')
@section('header_breadcrumb', '/ Analisis & Wawasan')

@section('content')
  <!-- Filter Atas & Aksi (Berdasarkan Gambar) -->
  <div style="display:flex; flex-wrap:wrap; gap:12px; align-items:center; margin-bottom:24px; background:#fff; padding:16px; border-radius:8px; border:1px solid var(--gray-200)">
    <div>
      <select class="form-input" style="min-width:180px; height:38px; padding:0 12px">
        <option value="sales">Laporan Penjualan</option>
        <option value="inventory">Laporan Inventaris</option>
        <option value="movement">Pergerakan Stok</option>
        <option value="supplier">Laporan Pemasok</option>
      </select>
    </div>
    
    <div style="display:flex; align-items:center; gap:8px">
      <input type="date" class="form-input" value="2025-05-01" style="height:38px">
      <span style="color:var(--gray-400)">—</span>
      <input type="date" class="form-input" value="2025-05-31" style="height:38px">
    </div>

    <button class="btn" style="background:#C2185B; color:#fff; height:38px; border:none; padding:0 16px; font-weight:600; border-radius:4px">
      Buat Laporan
    </button>
    <button class="btn btn-secondary" style="height:38px">
      Ekspor PDF
    </button>
    <button class="btn btn-secondary" style="height:38px">
      Ekspor Excel
    </button>
  </div>

  <!-- Ringkasan Statistik Laporan (KPI Grid Modifikasi Sesuai Gambar) -->
  <div class="kpi-grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px; margin-bottom:24px">
    <!-- Total Pendapatan -->
    <div class="kpi-card" style="border-right: 4px solid #C2185B; padding:20px; background:#fff; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.05)">
      <div style="font-size:12px; font-weight:600; color:var(--gray-500); text-transform:uppercase; letter-spacing:0.5px">TOTAL PENDAPATAN</div>
      <div style="font-size:28px; font-weight:700; color:#1E293B; margin:8px 0 4px 0">Rp 842M</div>
      <div style="font-size:12px; font-weight:600; color:#059669">+18.4% MoM</div>
    </div>

    <!-- Unit Terjual -->
    <div class="kpi-card" style="border-right: 4px solid #059669; padding:20px; background:#fff; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.05)">
      <div style="font-size:12px; font-weight:600; color:var(--gray-500); text-transform:uppercase; letter-spacing:0.5px">UNIT TERJUAL</div>
      <div style="font-size:28px; font-weight:700; color:#1E293B; margin:8px 0 4px 0">2.998</div>
      <div style="font-size:12px; font-weight:600; color:#059669">+12.1% MoM</div>
    </div>

    <!-- Total Stok Masuk -->
    <div class="kpi-card" style="border-right: 4px solid #D97706; padding:20px; background:#fff; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.05)">
      <div style="font-size:12px; font-weight:600; color:var(--gray-500); text-transform:uppercase; letter-spacing:0.5px">TOTAL STOK MASUK</div>
      <div style="font-size:28px; font-weight:700; color:#1E293B; margin:8px 0 4px 0">1.840</div>
      <div style="font-size:12px; color:var(--gray-400)">Periode ini</div>
    </div>

    <!-- HPP / COGS -->
    <div class="kpi-card" style="border-right: 4px solid #6366F1; padding:20px; background:#fff; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.05)">
      <div style="font-size:12px; font-weight:600; color:var(--gray-500); text-transform:uppercase; letter-spacing:0.5px">HPP (COGS)</div>
      <div style="font-size:28px; font-weight:700; color:#1E293B; margin:8px 0 4px 0">Rp 548M</div>
      <div style="font-size:12px; font-weight:600; color:#059669">Margin 34.9%</div>
    </div>
  </div>

  <!-- Tabel Ringkasan Penjualan -->
  <div class="table-card" style="background:#fff; border-radius:8px; border:1px solid var(--gray-200); overflow:hidden">
    <div class="table-header" style="padding:16px; border-bottom:1px solid var(--gray-200)">
      <div style="font-size:16px; font-weight:700; color:#1E293B">Ringkasan Penjualan — Mei 2025</div>
    </div>
    <table style="width:100%; border-collapse:collapse; text-align:left">
      <thead>
        <tr style="background:#F8FAFC; border-bottom:1px solid var(--gray-200)">
          <th style="padding:12px 16px; font-size:11px; font-weight:600; color:var(--gray-500); text-transform:uppercase">PRODUK</th>
          <th style="padding:12px 16px; font-size:11px; font-weight:600; color:var(--gray-500); text-transform:uppercase">KATEGORI</th>
          <th style="padding:12px 16px; font-size:11px; font-weight:600; color:var(--gray-500); text-transform:uppercase">UNIT TERJUAL</th>
          <th style="padding:12px 16px; font-size:11px; font-weight:600; color:var(--gray-500); text-transform:uppercase">PENDAPATAN</th>
          <th style="padding:12px 16px; font-size:11px; font-weight:600; color:var(--gray-500); text-transform:uppercase">RATA-RATA HARGA</th>
          <th style="padding:12px 16px; font-size:11px; font-weight:600; color:var(--gray-500); text-transform:uppercase">SISA STOK</th>
          <th style="padding:12px 16px; font-size:11px; font-weight:600; color:var(--gray-500); text-transform:uppercase">TREN</th>
        </tr>
      </thead>
      <tbody>
        <tr style="border-bottom:1px solid var(--gray-100)">
          <td style="padding:14px 16px; font-size:14px; font-weight:600; color:#1E293B">Jersey Kandang 2025</td>
          <td style="padding:14px 16px; font-size:14px; color:var(--gray-600)">Jersey</td>
          <td style="padding:14px 16px; font-size:14px; color:var(--gray-600)">842</td>
          <td style="padding:14px 16px; font-size:14px; font-weight:600; color:#1E293B">Rp 239.9M</td>
          <td style="padding:14px 16px; font-size:14px; color:var(--gray-600)">Rp 285.000</td>
          <td style="padding:14px 16px; font-size:14px; color:var(--gray-600)">342</td>
          <td style="padding:14px 16px;">
            <span class="badge" style="background:#D1FAE5; color:#065F46; padding:4px 8px; border-radius:12px; font-size:12px; font-weight:600">↑ Tinggi</span>
          </td>
        </tr>
        <tr style="border-bottom:1px solid var(--gray-100)">
          <td style="padding:14px 16px; font-size:14px; font-weight:600; color:#1E293B">Jersey Tandang 2025</td>
          <td style="padding:14px 16px; font-size:14px; color:var(--gray-600)">Jersey</td>
          <td style="padding:14px 16px; font-size:14px; color:var(--gray-600)">523</td>
          <td style="padding:14px 16px; font-size:14px; font-weight:600; color:#1E293B">Rp 149.1M</td>
          <td style="padding:14px 16px; font-size:14px; color:var(--gray-600)">Rp 285.000</td>
          <td style="padding:14px 16px; font-size:14px; color:var(--gray-600)">12</td>
          <td style="padding:14px 16px;">
            <span class="badge" style="background:#FEF3C7; color:#92400E; padding:4px 8px; border-radius:12px; font-size:12px; font-weight:600">↑ Meningkat</span>
          </td>
        </tr>
        <tr style="border-bottom:1px solid var(--gray-100)">
          <td style="padding:14px 16px; font-size:14px; font-weight:600; color:#1E293B">Topi Persipura</td>
          <td style="padding:14px 16px; font-size:14px; color:var(--gray-600)">Aksesoris</td>
          <td style="padding:14px 16px; font-size:14px; color:var(--gray-600)">401</td>
          <td style="padding:14px 16px; font-size:14px; font-weight:600; color:#1E293B">Rp 38.1M</td>
          <td style="padding:14px 16px; font-size:14px; color:var(--gray-600)">Rp 95.000</td>
          <td style="padding:14px 16px; font-size:14px; color:var(--gray-600)">188</td>
          <td style="padding:14px 16px;">
            <span class="badge" style="background:#E0F2FE; color:#0369A1; padding:4px 8px; border-radius:12px; font-size:12px; font-weight:600">→ Stabil</span>
          </td>
        </tr>
        <tr>
          <td style="padding:14px 16px; font-size:14px; font-weight:600; color:#1E293B">Syal Persipura</td>
          <td style="padding:14px 16px; font-size:14px; color:var(--gray-600)">Merchandise</td>
          <td style="padding:14px 16px; font-size:14px; color:var(--gray-600)">380</td>
          <td style="padding:14px 16px; font-size:14px; font-weight:600; color:#1E293B">Rp 28.5M</td>
          <td style="padding:14px 16px; font-size:14px; color:var(--gray-600)">Rp 75.000</td>
          <td style="padding:14px 16px; font-size:14px; color:var(--gray-600)">15</td>
          <td style="padding:14px 16px;">
            <span class="badge" style="background:#FEE2E2; color:#991B1B; padding:4px 8px; border-radius:12px; font-size:12px; font-weight:600">↓ Rendah</span>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
@endsection