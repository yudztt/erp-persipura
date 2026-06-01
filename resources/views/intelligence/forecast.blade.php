@extends('layouts.app')

@section('title', 'Prakiraan Penjualan - Persipura ERP')
@section('header_title', 'Prakiraan Penjualan')
@section('header_breadcrumb', '/ Prediksi AI')

@section('content')
  <div class="ai-card">
    <div class="ai-tag"><i class="ti ti-brain" style="font-size:12px"></i> Prakiraan Berbasis AI</div>
    <div class="ai-title">Prediksi Penjualan Juni 2026</div>
    <div class="ai-sub">Model machine learning dilatih menggunakan data historis 24 bulan, pola tren musiman, dan jadwal pertandingan</div>
    <div class="forecast-row">
      <div class="forecast-item">
        <div class="fi-label">Prediksi Pendapatan</div>
        <div class="fi-val">Rp 1.1M</div>
        <div class="fi-change">↑ +30.6% proyeksi pertumbuhan</div>
      </div>
      <div class="forecast-item">
        <div class="fi-label">Estimasi Pesanan</div>
        <div class="fi-val">2.450</div>
        <div class="fi-change">↑ +18% vs bulan lalu</div>
      </div>
      <div class="forecast-item">
        <div class="fi-label">Produk Terlaris</div>
        <div class="fi-val" style="font-size:14px;line-height:1.3">Jersey Kandang<br>Persipura 2026</div>
        <div class="fi-change">Sinyal permintaan tinggi</div>
      </div>
    </div>
  </div>

  <div class="charts-row">
    <div class="chart-card">
      <div class="section-title">Prakiraan Pendapatan (6 Bulan)</div>
      <div class="section-subtitle">Prediksi vs Aktual</div>
      <div class="chart-svg-container" style="display:flex;align-items:center;justify-content:center;color:var(--gray-400)">
        <div style="text-align:center">
          <i class="ti ti-chart-line" style="font-size:48px;opacity:0.3"></i>
          <div style="font-size:13px;margin-top:8px">Visualisasi grafik</div>
        </div>
      </div>
    </div>
    <div class="chart-card">
      <div class="section-title">Prakiraan Permintaan Produk</div>
      <div class="section-subtitle">Top 5 Produk</div>
      <div class="chart-svg-container" style="display:flex;align-items:center;justify-content:center;color:var(--gray-400)">
        <div style="text-align:center">
          <i class="ti ti-chart-bar" style="font-size:48px;opacity:0.3"></i>
          <div style="font-size:13px;margin-top:8px">Visualisasi grafik</div>
        </div>
      </div>
    </div>
  </div>

  <div class="table-card">
    <div class="table-header">
      <div>
        <div class="section-title">Prakiraan Tingkat Produk</div>
        <div class="section-subtitle">Prediksi untuk 30 hari ke depan</div>
      </div>
      <button class="btn btn-secondary">
        <i class="ti ti-download"></i> Ekspor Laporan
      </button>
    </div>
    <table>
      <thead>
        <tr>
          <th>Produk</th>
          <th>Stok Saat Ini</th>
          <th>Prediksi Permintaan</th>
          <th>Tingkat Akurasi</th>
          <th>Rekomendasi</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><strong>Jersey Kandang Persipura 2026</strong></td>
          <td>245 unit</td>
          <td><strong>380 unit</strong></td>
          <td><span class="badge badge-success">Tinggi (92%)</span></td>
          <td><span class="badge badge-warning">Stok Ulang +150</span></td>
        </tr>
        <tr>
          <td><strong>Jersey Tandang Persipura 2026</strong></td>
          <td>189 unit</td>
          <td><strong>220 unit</strong></td>
          <td><span class="badge badge-success">Tinggi (88%)</span></td>
          <td><span class="badge badge-warning">Stok Ulang +50</span></td>
        </tr>
        <tr>
          <td><strong>Syal Resmi Persipura</strong></td>
          <td>8 unit</td>
          <td><strong>95 unit</strong></td>
          <td><span class="badge badge-success">Sedang (75%)</span></td>
          <td><span class="badge badge-danger">Segera Stok Ulang</span></td>
        </tr>
      </tbody>
    </table>
  </div>
@endsection