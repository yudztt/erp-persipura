<style>
    /* =========================
       SIDEBAR BASE
    ========================= */
    .sidebar {
        background-color: #8b0707;
        height: 100vh;
        color: #ffffff;
        overflow-y: auto;
        /* Tambahan agar scrollbar lebih rapi */
        scrollbar-width: thin;
        scrollbar-color: rgba(255,255,255,0.3) transparent;
    }

    /* Custom Scrollbar untuk Chrome/Safari/Edge */
    .sidebar::-webkit-scrollbar {
        width: 6px;
    }
    .sidebar::-webkit-scrollbar-thumb {
        background-color: rgba(255,255,255,0.3);
        border-radius: 10px;
    }

    /* =========================
       BRAND / LOGO
    ========================= */
    .sidebar-brand {
        background-color: #8b0707; /* Diubah mengikuti warna sidebar */
        padding: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1); /* Diubah menjadi putih transparan */
        position: sticky; /* Logo tetap di atas saat di-scroll */
        top: 0;
        z-index: 10;
    }

    .sidebar-logo {
        width: 100%;
        max-width: 160px; /* Lebar optimal untuk sidebar */
        height: auto;
        display: block;
        object-fit: contain;
    }

    .sidebar-nav {
        display: flex;
        flex-direction: column;
        padding-top: 16px;
        padding-bottom: 24px;
    }

    /* =========================
       SECTION TITLE
    ========================= */
    .sidebar-nav .nav-section {
        color: rgba(255, 255, 255, 0.5);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1px;
        margin-top: 24px;
        margin-bottom: 8px;
        padding: 0 24px;
        text-transform: uppercase;
    }

    .sidebar-nav .nav-section:first-child {
        margin-top: 0;
    }

    /* =========================
       MENU ITEM
    ========================= */
    .sidebar-nav .nav-item {
        color: #e2e8f0;
        font-size: 15px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 18px;
        margin: 4px 16px;
        text-decoration: none;
        border-radius: 10px;
        transition: all 0.2s ease;
    }

    .sidebar-nav .nav-item:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #ffffff;
        transform: translateX(4px);
    }

    /* =========================
       ACTIVE MENU
    ========================= */
    .sidebar-nav .nav-item.active {
        background: #ffffff;
        color: #8b0707; /* Teks merah mengikuti warna tema */
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .sidebar-nav .nav-item.active i {
        color: #8b0707; /* Icon merah mengikuti warna tema */
    }

    /* =========================
       ICON
    ========================= */
    .sidebar-nav .nav-item i {
        font-size: 22px;
        min-width: 24px;
    }
</style>

<aside class="sidebar" id="sidebar">

    <div class="sidebar-brand">
        <img src="{{ asset('img/logo-3.png') }}" alt="Cendrawasih Karsa Store" class="sidebar-logo">
    </div>

    <nav class="sidebar-nav">

        <div class="nav-section">UTAMA</div>

        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->is('dashboard*') ? 'active' : '' }}">
            <i class="ti ti-layout-grid"></i>
            Dashboard
        </a>

        <a href="{{ route('inventory.index') }}" class="nav-item {{ request()->is('inventory*') && !request()->is('stock-*') ? 'active' : '' }}">
            <i class="ti ti-box"></i>
            Inventaris
        </a>

        <div class="nav-section">OPERASIONAL</div>

        <a href="{{ route('inventory.stockin') }}" class="nav-item {{ request()->is('stock-in*') ? 'active' : '' }}">
            <i class="ti ti-package-import"></i>
            Stok Masuk
        </a>

        <a href="{{ route('inventory.stockout') }}" class="nav-item {{ request()->is('stock-out*') ? 'active' : '' }}">
            <i class="ti ti-package-export"></i>
            Stok Keluar
        </a>

        <div class="nav-section">KATALOG</div>

        <a href="{{ route('kategoris.index') }}" class="nav-item {{ request()->is('kategoris*') ? 'active' : '' }}">
            <i class="ti ti-apps"></i>
            Kategori
        </a>

        <a href="{{ route('suppliers.index') }}" class="nav-item {{ request()->is('suppliers*') ? 'active' : '' }}">
            <i class="ti ti-truck"></i>
            Supplier
        </a>

        <a href="{{ route('catalog.warehouse') }}" class="nav-item {{ request()->is('warehouse*') ? 'active' : '' }}">
            <i class="ti ti-building-warehouse"></i>
            Gudang
        </a>

        <div class="nav-section">AI</div>

        <a href="{{ route('intelligence.forecast') }}" class="nav-item {{ request()->is('forecast*') ? 'active' : '' }}">
            <i class="ti ti-trending-up"></i>
            Prediksi Penjualan
        </a>

        <a href="{{ route('intelligence.reports') }}" class="nav-item {{ request()->is('reports*') ? 'active' : '' }}">
            <i class="ti ti-report-analytics"></i>
            Laporan
        </a>

        <div class="nav-section">SISTEM</div>

        <a href="{{ route('users.index') }}" class="nav-item {{ request()->is('users*') ? 'active' : '' }}">
            <i class="ti ti-users"></i>
            Pengguna
        </a>

    </nav>

</aside>