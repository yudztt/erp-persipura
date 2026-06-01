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

@push('styles')
<style>
/* Base Theme & Layout Overrides */
.content:has(.db){padding:24px;background:#F8FAFC}
.db{display:flex;flex-direction:column;gap:24px;max-width:1280px;margin:0 auto;width:100%}

/* Alert Toast */
.db-toast{
    display:flex;align-items:center;gap:10px;padding:14px 18px;
    font-size:13px;font-weight:500;color:#065F46;
    background:#ECFDF5;border:1px solid #A7F3D0;border-radius:12px;
    box-shadow:0 2px 4px rgba(16,24,40,.02);
}
.db-toast i{font-size:18px}

/* Card Base Styles */
.db-card{
    background:#fff;border:1px solid #E2E8F0;
    border-radius:14px;
    box-shadow:0 1px 3px rgba(0,0,0,.02), 0 4px 12px rgba(0,0,0,.01);
}
.db-card__head{
    display:flex;align-items:center;justify-content:space-between;gap:12px;
    padding:20px;
}
.db-card__head--border{border-bottom:1px solid #F1F5F9;padding-bottom:16px}
.db-card__head--compact{padding:16px 20px;border-bottom:1px solid #F1F5F9}
.db-card__title{font-size:16px;font-weight:600;color:#0F172A;margin:0}
.db-card__sub{font-size:12px;color:#64748B;margin-top:4px}
.db-card__badge{font-size:11px;font-weight:600;color:#475569;background:#F1F5F9;padding:4px 10px;border-radius:6px}

/* Custom Controls */
.db-select{
    padding:6px 32px 6px 12px;border:1px solid #CBD5E1;border-radius:8px;
    font-size:13px;font-weight:500;color:#334155;background:#fff;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748B' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat:no-repeat;background-position:right 10px center;
    font-family:inherit;cursor:pointer;outline:none;
}
.db-select:focus{border-color:#C1121F}
.db-link{font-size:13px;font-weight:600;color:#C1121F;text-decoration:none;transition:color .15s}
.db-link:hover{color:#9E0E18;text-decoration:underline}

/* Welcome Hero Card */
.db-card--welcome{
    display:flex;align-items:center;justify-content:space-between;gap:24px;
    padding:24px;background:linear-gradient(to right, #ffffff, #FAF9F9);
}
.db-welcome{min-width:0}
.db-welcome__greet{font-size:13px;font-weight:500;color:#64748B;margin:0 0 4px}
.db-welcome__name{font-size:22px;font-weight:700;color:#0F172A;margin:0 0 6px;letter-spacing:-.02em}
.db-welcome__meta{font-size:13px;color:#64748B;margin:0}
.db-quick-wrap{text-align:right}
.db-quick-wrap__label{
    display:block;font-size:11px;font-weight:700;text-transform:uppercase;
    letter-spacing:.05em;color:#94A3B8;margin-bottom:12px;
}
.db-quick{display:flex;flex-wrap:wrap;justify-content:flex-end;gap:10px}
.db-quick__btn{
    display:inline-flex;align-items:center;gap:8px;
    padding:9px 16px;font-size:13px;font-weight:500;
    color:#334155;background:#fff;
    border:1px solid #E2E8F0;border-radius:8px;
    text-decoration:none;transition:all .15s ease;
}
.db-quick__btn i{font-size:16px;color:#64748B}
.db-quick__btn:hover{background:#F8FAFC;border-color:#CBD5E1;box-shadow:0 2px 4px rgba(0,0,0,.04)}
.db-quick__btn--primary{background:#C1121F;border-color:#C1121F;color:#fff}
.db-quick__btn--primary i{color:#fff}
.db-quick__btn--primary:hover{background:#9E0E18;border-color:#9E0E18;color:#fff;box-shadow:0 4px 8px rgba(193,18,31,.15)}

/* Statistics Grid (KPIs) */
.db-kpis{display:grid;grid-template-columns:repeat(4,1fr);gap:20px}
.db-stat{
    display:flex;align-items:center;gap:16px;
    padding:20px;background:#fff;
    border:1px solid #E2E8F0;border-radius:14px;
    box-shadow:0 1px 3px rgba(0,0,0,.01);
    transition:all .2s ease;
}
.db-stat:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(0,0,0,.04)}
.db-stat__icon{
    width:48px;height:48px;border-radius:12px;
    display:flex;align-items:center;justify-content:center;
    font-size:22px;flex-shrink:0;
}
.db-stat__icon--red{background:#FEE2E2;color:#C1121F}
.db-stat__icon--green{background:#D1FAE5;color:#059669}
.db-stat__icon--amber{background:#FEF3C7;color:#D97706}
.db-stat__icon--slate{background:#EEF2FF;color:#6366F1}
.db-stat__body{flex:1;min-width:0}
.db-stat__label{display:block;font-size:11px;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.05em;margin-bottom:6px}
.db-stat__value{display:block;font-size:24px;font-weight:700;color:#0F172A;line-height:1.2;letter-spacing:-.02em}
.db-stat__hint{display:block;font-size:12px;color:#64748B;margin-top:6px}
.db-stat__hint--up{color:#059669;font-weight:600}
.db-stat__bar{height:6px;background:#F1F5F9;border-radius:99px;margin-top:10px;overflow:hidden}
.db-stat__bar i{display:block;height:100%;background:linear-gradient(90deg,#6366F1,#818CF8);border-radius:99px}

/* Layout Structural Columns */
.db-grid{display:grid;gap:20px}
.db-grid--main{grid-template-columns:1fr 340px;align-items:stretch}
.db-grid--tables{grid-template-columns:1fr 1fr}
.db-main-left{display:flex;flex-direction:column;gap:20px}
.db-stack{display:flex;flex-direction:column;gap:20px;height:100%}

/* Interactive Analytics Chart (Optimized Height) */
.db-card--chart{display:flex;flex-direction:column}
.db-chart-wrap{padding:20px}
.db-chart {
    width: 100%;
    line-height: 0;
}
.db-chart svg {
    width: 100%;
    height: auto;
    display: block;
    max-height: 150px; /* Diperketat agar grafik tidak memanjang vertikal */
}
.db-chart__axis{font-size:10px;fill:#94A3B8;font-family:Inter,system-ui,sans-serif;font-weight:500}
.db-chart-legend{
    display:flex;align-items:center;justify-content:space-between;gap:12px;
    margin:12px 0 0;padding:12px 4px 0;
    border-top:1px solid #F1F5F9;
    list-style:none;font-size:13px;color:#64748B;
}
.db-chart-legend__dot{
    display:inline-block;width:10px;height:10px;border-radius:50%;
    background:#C1121F;margin-right:6px;vertical-align:middle;
}
.db-chart-legend__hi strong{color:#1E293B;font-weight:600}

/* New Horizontal Operational Summary Layout */
.db-card--summary-horizontal {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    padding: 20px 24px;
}
.db-summary-head { min-width: 180px; }
.db-summary--horizontal {
    display: flex;
    flex: 1;
    gap: 16px;
    list-style: none;
    margin: 0;
    padding: 0;
}
.db-summary--horizontal li {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    background: #F8FAFC;
    border-radius: 10px;
    font-size: 13px;
    color: #475569;
    font-weight: 500;
    flex: 1;
}
.db-summary--horizontal i { font-size: 18px; color: #94A3B8; }
.db-summary--horizontal span { flex: 1; }
.db-summary--horizontal b { font-size: 16px; font-weight: 700; color: #0F172A; }

/* Smart AI Forecast Panel */
.db-card--forecast{
    background:linear-gradient(145deg, #0F172A 0%, #1E1114 100%);
    border-color:#334155;color:#fff;padding:22px 20px;
}
.db-forecast__head{margin-bottom:16px}
.db-forecast__tag{
    display:inline-flex;align-items:center;gap:6px;
    font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;
    color:#FCA5A5;background:rgba(193,18,31,.25);
    padding:4px 10px;border-radius:6px;margin-bottom:10px;
}
.db-forecast__title{font-size:18px;font-weight:700;margin:0;letter-spacing:-.01em}
.db-forecast__list{list-style:none;margin:0 0 16px;padding:0;display:grid;gap:10px}
.db-forecast__list li{
    display:flex;justify-content:space-between;align-items:center;gap:12px;
    padding:12px;background:rgba(255,255,255,.05);border-radius:10px;
    font-size:13px;color:#94A3B8;border:1px solid rgba(255,255,255,.03);
}
.db-forecast__list strong{font-size:14px;font-weight:600;color:#fff}
.db-forecast__list em{font-style:normal;font-size:12px;color:#34D399;font-weight:600}
.db-forecast__list .warn{color:#FBBF24}
.db-forecast__link{
    display:inline-flex;align-items:center;gap:6px;
    font-size:13px;font-weight:600;color:#FCA5A5;text-decoration:none;
}
.db-forecast__link:hover{color:#fff}

/* Segment Distribution Bars */
.db-bars{list-style:none;margin:0;padding:16px 20px 20px;display:grid;gap:14px}
.db-bars li{display:grid;grid-template-columns:85px 1fr 40px;align-items:center;gap:12px;font-size:13px;color:#475569;font-weight:500}
.db-bars__track{height:7px;background:#F1F5F9;border-radius:99px;overflow:hidden}
.db-bars__track i{display:block;height:100%;border-radius:99px}
.db-bars em{font-style:normal;font-weight:600;font-size:13px;color:#1E293B;text-align:right}

/* Data Tables */
.db-card--table{overflow:hidden}
.db-table-wrap{overflow-x:auto}
.db-table{width:100%;border-collapse:collapse}
.db-table th{
    font-size:11px;font-weight:700;color:#64748B;
    text-transform:uppercase;letter-spacing:.05em;
    padding:12px 20px;text-align:left;
    background:#F8FAFC;border-bottom:1px solid #E2E8F0;
}
.db-table td{
    padding:14px 20px;border-bottom:1px solid #F1F5F9;
    font-size:13px;color:#334155;white-space:nowrap;
}
.db-table tbody tr:last-child td{border-bottom:none}
.db-table tbody tr:hover td{background:#F8FAFC}
.db-table .code{font-weight:600;color:#C1121F;font-size:12px}
.db-table .qty{font-weight:700;font-size:14px}
.db-table .qty--c{color:#C1121F}
.db-table .qty--w{color:#D97706}
.db-table .muted{color:#94A3B8;font-size:12px}

/* Badges Component */
.db-badge{display:inline-block;font-size:11px;font-weight:700;padding:4px 10px;border-radius:6px;text-align:center}
.db-badge--danger{background:#FEE2E2;color:#991B1B}
.db-badge--warn{background:#FEF3C7;color:#92400E}
.db-badge--success{background:#D1FAE5;color:#065F46}

/* Responsive Grid Adjustments */
@media(max-width:1150px){
    .db-grid--main, .db-grid--tables{grid-template-columns:1fr}
    .db-kpis{grid-template-columns:repeat(2,1fr);gap:16px}
    .db-card--summary-horizontal { flex-direction: column; align-items: flex-start; gap: 14px; }
    .db-summary--horizontal { width: 100%; flex-wrap: wrap; }
}
@media(max-width:768px){
    .db-card--welcome{flex-direction:column;align-items:flex-start;gap:20px;padding:20px}
    .db-quick-wrap{text-align:left;width:100%}
    .db-quick{justify-content:flex-start}
    .db-kpis{grid-template-columns:1fr;gap:14px}
    .db-summary--horizontal { flex-direction: column; }
    .content:has(.db){padding:16px}
}
@media(max-width:480px){
    .db-quick__btn{width:100%;justify-content:center;padding:11px}
}
</style>
@endpush

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