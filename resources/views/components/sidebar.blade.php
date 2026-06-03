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