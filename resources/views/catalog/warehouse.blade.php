@extends('layouts.app')

@section('title', 'Gudang - Cendrawasih Karsa Store')
@section('header_title', 'Gudang')
@section('header_breadcrumb', '/ Manajemen Penyimpanan')

@section('content')
  @php
    // Fetch all categories with their product stock aggregates dynamically from database
    $kategoris = \App\Models\Kategori::with('produks')->orderBy('nama_kategori')->get();
    
    $gudangData = $kategoris->map(function($kat, $index) {
        $stokSaatIni = $kat->produks->sum('stok');
        // Generate realistic Sector layouts based on Category Index
        $lokasi = 'Sektor ' . chr(65 + ($index % 26)) . '-' . (($index % 5) + 1);
        // Dynamic capacity based on cumulative minimum stock
        $kapasitas = max(1000, $kat->produks->sum('stok_minimum') * 3);
        
        return (object)[
            'id' => $kat->id_kategori,
            'nama' => 'Penyimpanan ' . $kat->nama_kategori,
            'nama_kategori' => $kat->nama_kategori,
            'lokasi' => $lokasi,
            'kapasitas' => $kapasitas,
            'stok_saat_ini' => $stokSaatIni,
            'products' => $kat->produks->map(function($p) {
                return [
                    'sku' => $p->kode_produk,
                    'nama_produk' => $p->nama_produk,
                    'stok' => $p->stok,
                    'stok_minimum' => $p->stok_minimum
                ];
            })
        ];
    });
  @endphp

  <div class="table-card">
    <div class="table-header">
      <div>
        <div class="section-title">Lokasi Gudang</div>
        <div class="section-subtitle">Kelola fasilitas penyimpanan dan kapasitas sekat Anda</div>
      </div>
      <button class="btn btn-secondary" onclick="window.location='{{ route('kategoris.index') }}'">
        <i class="ti ti-category"></i> Kelola Kategori Penyimpanan
      </button>
    </div>
    <table>
      <thead>
        <tr>
          <th>Gudang / Area</th>
          <th>Lokasi</th>
          <th>Kapasitas</th>
          <th>Stok Saat Ini</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($gudangData as $gudang)
        <tr>
          <td><strong>{{ $gudang->nama }}</strong></td>
          <td>{{ $gudang->lokasi }}</td>
          <td>{{ number_format($gudang->kapasitas, 0, ',', '.') }} unit</td>
          <td><strong>{{ number_format($gudang->stok_saat_ini, 0, ',', '.') }}</strong> unit</td>
          <td>
            <button class="btn-ghost" title="Lihat Detail Produk" onclick="viewStorageItems('{{ $gudang->nama }}', '{{ $gudang->lokasi }}', {{ json_encode($gudang->products) }})">
              <i class="ti ti-eye" style="font-size:16px;"></i>
            </button>
            <button class="btn-ghost" title="Ubah Nama Area" onclick="openEditStorageModal({{ $gudang->id }}, '{{ $gudang->nama_kategori }}')">
              <i class="ti ti-edit" style="font-size:16px;"></i>
            </button>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" style="text-align:center;padding:40px;color:var(--gray-400)">
            <i class="ti ti-package" style="font-size:48px;opacity:0.3"></i>
            <div style="margin-top:12px">Belum ada kategori area penyimpanan terdaftar.</div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
@endsection

@push('modals')
<!-- View Products Modal -->
<div class="modal-overlay" id="viewStorageItemsModal">
  <div class="modal" style="width: 650px; max-width: 95%;">
    <div class="modal-header">
      <div class="modal-title" id="viewModalTitle">Detail Barang di Gudang</div>
      <button class="modal-close" onclick="closeModal('viewStorageItemsModal')"><i class="ti ti-x"></i></button>
    </div>
    <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
      <div style="margin-bottom: 16px; font-size: 13px; color: var(--gray-600); border-bottom: 1px solid var(--gray-100); padding-bottom: 12px; display: flex; justify-content: space-between;">
        <div><strong>Nama Area:</strong> <span id="storageAreaName" style="color: var(--gray-800); font-weight: 600;"></span></div>
        <div><strong>Lokasi:</strong> <span id="storageLocationName" style="color: var(--gray-800); font-weight: 600;"></span></div>
      </div>
      <div class="table-card" style="box-shadow: none; border: 1px solid var(--gray-200); margin-bottom: 0;">
        <table style="width: 100%; border-collapse: collapse;">
          <thead>
            <tr style="background: var(--gray-50);">
              <th style="padding: 10px 12px; font-size: 11px;">SKU</th>
              <th style="padding: 10px 12px; font-size: 11px;">Nama Produk</th>
              <th style="padding: 10px 12px; font-size: 11px; text-align: right;">Stok</th>
              <th style="padding: 10px 12px; font-size: 11px; text-align: right;">Batas Min</th>
            </tr>
          </thead>
          <tbody id="storageProductsTableBody">
            <!-- Products list will be inserted here dynamically -->
          </tbody>
        </table>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('viewStorageItemsModal')">Tutup</button>
    </div>
  </div>
</div>

<!-- Edit Storage Area Modal (Category Rename) -->
<div class="modal-overlay" id="editStorageModal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Ubah Nama Area Penyimpanan</div>
      <button class="modal-close" onclick="closeModal('editStorageModal')"><i class="ti ti-x"></i></button>
    </div>
    <form id="editStorageForm" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-body">
        <div class="form-grid">
          <div class="form-group">
            <label class="form-label">Nama Kategori Produk (Area) *</label>
            <input type="text" name="nama_kategori" id="editStorageNameInput" class="form-input" required placeholder="Masukkan nama kategori">
            <small style="color:var(--gray-400); font-size:11px; margin-top:4px;">Mengubah nama kategori produk ini akan secara otomatis mengubah nama area penyimpanan terkait di Gudang.</small>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('editStorageModal')">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>
@endpush

@push('scripts')
<script>
  function viewStorageItems(areaName, location, products) {
    document.getElementById('storageAreaName').innerText = areaName;
    document.getElementById('storageLocationName').innerText = location;
    
    const tbody = document.getElementById('storageProductsTableBody');
    tbody.innerHTML = '';
    
    if (products.length === 0) {
      tbody.innerHTML = `
        <tr>
          <td colspan="4" style="text-align: center; padding: 24px; color: var(--gray-400); font-style: italic;">
            Belum ada produk yang disimpan di dalam area ini.
          </td>
        </tr>
      `;
    } else {
      products.forEach(prod => {
        const tr = document.createElement('tr');
        const minClass = prod.stok <= prod.stok_minimum ? 'color: var(--red); font-weight: 700;' : 'color: var(--gray-700);';
        
        tr.innerHTML = `
          <td style="padding: 10px 12px; border-bottom: 1px solid var(--gray-100);"><code style="font-size: 11px; background: var(--gray-100); padding: 2px 4px; border-radius: 4px;">${prod.sku}</code></td>
          <td style="padding: 10px 12px; border-bottom: 1px solid var(--gray-100);"><strong>${prod.nama_produk}</strong></td>
          <td style="padding: 10px 12px; border-bottom: 1px solid var(--gray-100); text-align: right; ${minClass}">${new Intl.NumberFormat('id-ID').format(prod.stok)} unit</td>
          <td style="padding: 10px 12px; border-bottom: 1px solid var(--gray-100); text-align: right;">${new Intl.NumberFormat('id-ID').format(prod.stok_minimum)} unit</td>
        `;
        tbody.appendChild(tr);
      });
    }
    
    openModal('viewStorageItemsModal');
  }

  function openEditStorageModal(id, currentName) {
    document.getElementById('editStorageForm').action = '/kategoris/' + id;
    document.getElementById('editStorageNameInput').value = currentName;
    openModal('editStorageModal');
  }
</script>
@endpush