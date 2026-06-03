@extends('layouts.app')

@section('title', 'Dashboard - Cendrawasih Karsa Store')
@section('header_title', 'Dashboard')
@section('header_breadcrumb', '')

@section('content')
<div class="db">

    {{-- Toast Notification --}}
    @if(session('success'))
    <div class="db-toast" role="alert">
        <i class="ti ti-circle-check"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    {{-- Welcome + Quick Access Card --}}
    <section class="db-card db-card--welcome">
        <div class="db-welcome">
            <p class="db-welcome__greet">Selamat datang,</p>
            <h1 class="db-welcome__name">{{ Auth::user()->nama }}</h1>
            <p class="db-welcome__meta">{{ now()->format('d F Y') }} · {{ Auth::user()->role->nama_role ?? 'Pengguna' }}</p>
        </div>
        <div class="db-quick-wrap">
            <span class="db-quick-wrap__label">Akses Cepat</span>
            <nav class="db-quick" aria-label="Akses cepat">
                <a href="{{ route('inventory.stockin') }}" class="db-quick__btn db-quick__btn--primary">
                    <i class="ti ti-package-import"></i> Stok Masuk
                </a>
                <a href="{{ route('inventory.stockout') }}" class="db-quick__btn">
                    <i class="ti ti-package-export"></i> Stok Keluar
                </a>
                <a href="{{ route('inventory.index') }}" class="db-quick__btn">
                    <i class="ti ti-package"></i> Inventaris
                </a>
                <a href="{{ route('kategoris.index') }}" class="db-quick__btn">
                    <i class="ti ti-category"></i> Kategori
                </a>
                <a href="{{ route('intelligence.forecast') }}" class="db-quick__btn">
                    <i class="ti ti-chart-line"></i> Prediksi
                </a>
                <a href="{{ route('intelligence.reports') }}" class="db-quick__btn">
                    <i class="ti ti-report"></i> Laporan
                </a>
            </nav>
        </div>
    </section>

    {{-- KPI Cards Grid --}}
    <div class="db-kpis">
        <article class="db-stat">
            <div class="db-stat__icon db-stat__icon--red"><i class="ti ti-package"></i></div>
            <div class="db-stat__body">
                <span class="db-stat__label">Total SKU</span>
                <span class="db-stat__value">{{ number_format($totalSku) }}</span>
                <span class="db-stat__hint db-stat__hint--up">Produk terdaftar</span>
            </div>
        </article>

        <article class="db-stat">
            <div class="db-stat__icon db-stat__icon--green"><i class="ti ti-shopping-cart"></i></div>
            <div class="db-stat__body">
                <span class="db-stat__label">Penjualan Bulan Ini</span>
                <span class="db-stat__value">Rp {{ number_format($penjualanBulanIni, 0, ',', '.') }}</span>
                <span class="db-stat__hint db-stat__hint--up">Bulan berjalan</span>
            </div>
        </article>

        <article class="db-stat">
            <div class="db-stat__icon db-stat__icon--amber"><i class="ti ti-alert-triangle"></i></div>
            <div class="db-stat__body">
                <span class="db-stat__label">Stok Rendah</span>
                <span class="db-stat__value">{{ $stokRendahCount }}</span>
                <span class="db-stat__hint">Perlu restock</span>
            </div>
        </article>

        <article class="db-stat">
            <div class="db-stat__icon db-stat__icon--slate"><i class="ti ti-building-warehouse"></i></div>
            <div class="db-stat__body">
                <span class="db-stat__label">Total Stok</span>
                <span class="db-stat__value">{{ number_format($totalStok) }} Unit</span>
                <div class="db-stat__bar"><i style="width: 100%"></i></div>
            </div>
        </article>
    </div>

    {{-- Main Section Grid --}}
    <div class="db-grid db-grid--main">
        
        {{-- Left Content: Chart + Operational Summary (Stacked) --}}
        <div class="db-main-left">
            {{-- Chart Card --}}
            <section class="db-card db-card--chart">
                <header class="db-card__head db-card__head--border">
                    <div>
                        <h2 class="db-card__title">Tren Penjualan</h2>
                        <p class="db-card__sub">Nilai penjualan · 6 bulan terakhir</p>
                    </div>
                    <select class="db-select" aria-label="Tahun">
                        <option selected>2026</option>
                        <option>2025</option>
                    </select>
                </header>
                <div class="db-chart-wrap">
                    <div class="db-chart" role="img" aria-label="Grafik tren penjualan Des–Mei 2026">
                        <svg viewBox="0 0 400 130" preserveAspectRatio="xMidYMid meet" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <linearGradient id="dbG" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#C1121F" stop-opacity=".15"/>
                                    <stop offset="100%" stop-color="#C1121F" stop-opacity="0"/>
                                </linearGradient>
                            </defs>
                            
                            @foreach([20,40,60,80,100] as $y)
                            <line x1="45" y1="{{ $y }}" x2="385" y2="{{ $y }}" stroke="#F1F5F9" stroke-width="1"/>
                            @endforeach
                            
                            <text x="38" y="24" text-anchor="end" class="db-chart__axis">{{ number_format($maxSales, 0, ',', '.') }}</text>
                            <text x="38" y="44" text-anchor="end" class="db-chart__axis">{{ number_format($maxSales * 0.75, 0, ',', '.') }}</text>
                            <text x="38" y="64" text-anchor="end" class="db-chart__axis">{{ number_format($maxSales * 0.5, 0, ',', '.') }}</text>
                            <text x="38" y="84" text-anchor="end" class="db-chart__axis">{{ number_format($maxSales * 0.25, 0, ',', '.') }}</text>
                            <text x="38" y="104" text-anchor="end" class="db-chart__axis">0</text>
                            
                            <path d="{{ $svgPath }}L385,100 L55,100 Z" fill="url(#dbG)"/>
                            <polyline points="{{ str_replace('M', '', $svgPath) }}" fill="none" stroke="#C1121F" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                            
                            @foreach($chartData as $index => $point)
                            <circle cx="{{ $point['x'] }}" cy="{{ $point['y'] }}" r="{{ $index == 5 ? 4 : 3 }}" fill="#C1121F" @if($index == 5) stroke="#fff" stroke-width="2" @endif/>
                            <text x="{{ $point['x'] }}" y="120" text-anchor="middle" class="db-chart__axis">{{ $point['month'] }}</text>
                            @endforeach
                        </svg>
                    </div>
                    <ul class="db-chart-legend">
                        <li><span class="db-chart-legend__dot"></span> Penjualan</li>
                        <li class="db-chart-legend__hi"><strong>{{ $highestMonth }}</strong> tertinggi</li>
                    </ul>
                </div>
            </section>
        </div>

        {{-- Right Content: Sidebar Stack (Hanya berisi 2 kartu, tinggi seimbang) --}}
        <div class="db-stack">
            {{-- AI Forecast Card --}}
            <section class="db-card db-card--forecast">
                <header class="db-forecast__head">
                    <span class="db-forecast__tag"><i class="ti ti-sparkles"></i> AI Forecast</span>
                    <h2 class="db-forecast__title">{{ $forecastMonth }}</h2>
                </header>
                <ul class="db-forecast__list">
                    <li><span>Prediksi Omzet</span><strong>Rp {{ number_format($totalPrediksi, 0, ',', '.') }}</strong></li>
                    <li><span>Top produk</span><strong>{{ $topProduk }}</strong></li>
                    <li><span>Restock</span><strong class="warn">{{ $kritisRestock }} SKU kritis</strong></li>
                </ul>
                <a href="{{ route('intelligence.forecast') }}" class="db-forecast__link">
                    Lihat prediksi lengkap <i class="ti ti-arrow-right"></i>
                </a>
            </section> {{-- Diperbaiki di sini: Menutup tag section dengan benar --}}
        </div>
    </div>

    {{-- Tables Grid --}}
    <div class="db-grid db-grid--tables">
        {{-- Low Stock Table --}}
        <section class="db-card db-card--table">
            <header class="db-card__head">
                <div>
                    <h2 class="db-card__title">Stok Rendah</h2>
                    <p class="db-card__sub">Produk di bawah ambang minimum</p>
                </div>
                <a href="{{ route('inventory.index') }}" class="db-link">Lihat semua</a>
            </header>
            <div class="db-table-wrap">
                <table class="db-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Stok</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stokRendahs as $produk)
                        <tr>
                            <td>{{ $produk->nama_produk }}</td>
                            <td class="qty qty--c">{{ $produk->stok }}</td>
                            <td><span class="db-badge db-badge--danger">Kritis</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">Semua stok aman.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Recent Activity Table --}}
        <section class="db-card db-card--table">
            <header class="db-card__head">
                <div>
                    <h2 class="db-card__title">Transaksi Terbaru</h2>
                    <p class="db-card__sub">Aktivitas stok terakhir</p>
                </div>
                <a href="{{ route('inventory.stockin') }}" class="db-link">Lihat semua</a>
            </header>
            <div class="db-table-wrap">
                <table class="db-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tipe</th>
                            <th>Jumlah</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentActivities as $activity)
                        <tr>
                            <td class="code">{{ $activity->id }}</td>
                            <td>
                                @if($activity->type == 'Masuk')
                                <span class="db-badge db-badge--success">Masuk</span>
                                @else
                                <span class="db-badge db-badge--danger">Keluar</span>
                                @endif
                            </td>
                            <td>{{ $activity->qty }}</td>
                            <td class="muted">{{ $activity->date }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada aktivitas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

</div>
@endsection



@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toast = document.querySelector('.db-toast');
        if (toast) {
            setTimeout(() => {
                toast.style.transition = 'opacity .4s ease, transform .4s ease';
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-8px)';
                setTimeout(() => toast.remove(), 400);
            }, 5000);
        }
    });
</script>
@endpush