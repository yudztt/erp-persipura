@extends('layouts.app')

@section('title', 'Inventory - Cendrawasih Karsa Store')
@section('header_title', 'Inventory')
@section('header_breadcrumb', '/ Product List')

@section('content')


  <!-- Toolbar -->
  <div class="toolbar">
    <div class="search-box">
      <i class="ti ti-search" style="color:var(--gray-400);font-size:14px"></i>
      <input type="text" placeholder="Search products..." id="searchInput">
    </div>
    <button class="filter-btn">
      <i class="ti ti-filter"></i> Filter
    </button>
    <button class="filter-btn">
      <i class="ti ti-download"></i> Export
    </button>
    <div style="flex:1"></div>
    <button class="btn btn-primary" onclick="openModal('addProductModal')">
      <i class="ti ti-plus"></i> Add Product
    </button>
  </div>

  <!-- Table -->
  <div class="table-card">
    <div class="table-header">
      <div>
        <div class="section-title">Product Inventory</div>
        <div class="section-subtitle">Manage all products and stock levels</div>
      </div>
    </div>
    <table>
      <thead>
        <tr>
          <th>SKU</th>
          <th>Product Name</th>
          <th>Category</th>
          <th>Supplier</th>
          <th>Stock</th>
          <th>Status</th>
          <th>Price</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="productTable">
        @forelse($produks as $produk)
        <tr>
          <td><code style="font-size:11px;background:var(--gray-100);padding:2px 6px;border-radius:4px">{{ $produk->kode_produk }}</code></td>
          <td><strong>{{ $produk->nama_produk }}</strong></td>
          <td>{{ $produk->kategori->nama_kategori ?? '-' }}</td>
          <td>{{ $produk->supplier->nama_supplier ?? '-' }}</td>
          <td><strong>{{ number_format($produk->stok) }}</strong> units</td>
          <td>
            @if($produk->stok <= $produk->stok_minimum)
              <span class="badge badge-danger">Low Stock</span>
            @elseif($produk->stok <= $produk->stok_minimum * 2)
              <span class="badge badge-warning">Medium</span>
            @else
              <span class="badge badge-success">In Stock</span>
            @endif
          </td>
          <td>Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</td>
          <td>
            <button class="btn-ghost" onclick="openEditModal({{ $produk->id_produk }}, '{{ $produk->kode_produk }}', '{{ $produk->nama_produk }}', {{ $produk->id_kategori }}, {{ $produk->id_supplier }}, {{ $produk->harga_beli }}, {{ $produk->harga_jual }}, {{ $produk->stok }}, {{ $produk->stok_minimum }})"><i class="ti ti-edit"></i></button>
            <button class="btn-ghost" onclick="deleteProduct({{ $produk->id_produk }}, '{{ $produk->nama_produk }}')"><i class="ti ti-trash"></i></button>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8" style="text-align:center;padding:40px;color:var(--gray-400)">
            <i class="ti ti-package" style="font-size:48px;opacity:0.3"></i>
            <div style="margin-top:12px">No products found. Add your first product!</div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
@endsection

@push('modals')
<!-- Add Product Modal -->
<div class="modal-overlay" id="addProductModal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Add New Product</div>
      <button class="modal-close" onclick="closeModal('addProductModal')"><i class="ti ti-x"></i></button>
    </div>
    <form action="{{ route('inventory.store') }}" method="POST">
      @csrf
      <div class="modal-body">
        <div class="form-grid">
          <div class="form-group">
            <label class="form-label">Product Name *</label>
            <input type="text" name="nama_produk" class="form-input" placeholder="Enter product name" required>
          </div>
          <div class="form-grid form-grid-2">
            <div class="form-group">
              <label class="form-label">SKU *</label>
              <input type="text" name="kode_produk" class="form-input" placeholder="SKU-XXX" required>
            </div>
            <div class="form-group">
              <label class="form-label">Category *</label>
              <select name="id_kategori" class="form-select" required>
                <option value="">Select Category</option>
                @foreach(\App\Models\Kategori::all() as $kategori)
                  <option value="{{ $kategori->id_kategori }}">{{ $kategori->nama_kategori }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Supplier *</label>
            <select name="id_supplier" class="form-select" required>
              <option value="">Select Supplier</option>
              @foreach(\App\Models\Supplier::all() as $supplier)
                <option value="{{ $supplier->id_supplier }}">{{ $supplier->nama_supplier }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-grid form-grid-2">
            <div class="form-group">
              <label class="form-label">Purchase Price *</label>
              <input type="number" name="harga_beli" class="form-input" placeholder="0" min="0" required>
            </div>
            <div class="form-group">
              <label class="form-label">Selling Price *</label>
              <input type="number" name="harga_jual" class="form-input" placeholder="0" min="0" required>
            </div>
          </div>
          <div class="form-grid form-grid-2">
            <div class="form-group">
              <label class="form-label">Stock Quantity *</label>
              <input type="number" name="stok" class="form-input" placeholder="0" min="0" required>
            </div>
            <div class="form-group">
              <label class="form-label">Minimum Stock *</label>
              <input type="number" name="stok_minimum" class="form-input" placeholder="0" min="0" required>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('addProductModal')">Cancel</button>
        <button type="submit" class="btn btn-primary">Save Product</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Product Modal -->
<div class="modal-overlay" id="editProductModal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Edit Product</div>
      <button class="modal-close" onclick="closeModal('editProductModal')"><i class="ti ti-x"></i></button>
    </div>
    <form id="editProductForm" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-body">
        <div class="form-grid">
          <div class="form-group">
            <label class="form-label">Product Name *</label>
            <input type="text" name="nama_produk" id="edit_nama_produk" class="form-input" required>
          </div>
          <div class="form-grid form-grid-2">
            <div class="form-group">
              <label class="form-label">SKU *</label>
              <input type="text" name="kode_produk" id="edit_kode_produk" class="form-input" required>
            </div>
            <div class="form-group">
              <label class="form-label">Category *</label>
              <select name="id_kategori" id="edit_id_kategori" class="form-select" required>
                <option value="">Select Category</option>
                @foreach(\App\Models\Kategori::all() as $kategori)
                  <option value="{{ $kategori->id_kategori }}">{{ $kategori->nama_kategori }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Supplier *</label>
            <select name="id_supplier" id="edit_id_supplier" class="form-select" required>
              <option value="">Select Supplier</option>
              @foreach(\App\Models\Supplier::all() as $supplier)
                <option value="{{ $supplier->id_supplier }}">{{ $supplier->nama_supplier }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-grid form-grid-2">
            <div class="form-group">
              <label class="form-label">Purchase Price *</label>
              <input type="number" name="harga_beli" id="edit_harga_beli" class="form-input" min="0" required>
            </div>
            <div class="form-group">
              <label class="form-label">Selling Price *</label>
              <input type="number" name="harga_jual" id="edit_harga_jual" class="form-input" min="0" required>
            </div>
          </div>
          <div class="form-grid form-grid-2">
            <div class="form-group">
              <label class="form-label">Stock Quantity *</label>
              <input type="number" name="stok" id="edit_stok" class="form-input" min="0" required>
            </div>
            <div class="form-group">
              <label class="form-label">Minimum Stock *</label>
              <input type="number" name="stok_minimum" id="edit_stok_minimum" class="form-input" min="0" required>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('editProductModal')">Cancel</button>
        <button type="submit" class="btn btn-primary">Update Product</button>
      </div>
    </form>
  </div>
</div>

<!-- Delete Form (Hidden) -->
<form id="deleteProductForm" method="POST" style="display:none">
  @csrf
  @method('DELETE')
</form>
@endpush

@push('scripts')
<script>
  // Search functionality
  document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('#productTable tr');
    
    rows.forEach(row => {
      const text = row.textContent.toLowerCase();
      row.style.display = text.includes(searchValue) ? '' : 'none';
    });
  });

  // Open edit modal with product data
  function openEditModal(id, kode, nama, kategori, supplier, hargaBeli, hargaJual, stok, stokMin) {
    // Set form action
    document.getElementById('editProductForm').action = `/inventory/${id}`;
    
    // Fill form fields
    document.getElementById('edit_kode_produk').value = kode;
    document.getElementById('edit_nama_produk').value = nama;
    document.getElementById('edit_id_kategori').value = kategori;
    document.getElementById('edit_id_supplier').value = supplier;
    document.getElementById('edit_harga_beli').value = hargaBeli;
    document.getElementById('edit_harga_jual').value = hargaJual;
    document.getElementById('edit_stok').value = stok;
    document.getElementById('edit_stok_minimum').value = stokMin;
    
    // Open modal
    openModal('editProductModal');
  }

  // Delete product with SweetAlert2
  function deleteProduct(id, nama) {
    Swal.fire({
      title: 'Hapus Produk?',
      text: `Apakah Anda yakin ingin menghapus produk "${nama}"? Tindakan ini tidak dapat dibatalkan.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#C1121F',
      cancelButtonColor: '#6B7280',
      confirmButtonText: '<i class="ti ti-trash"></i> Ya, Hapus!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        const form = document.getElementById('deleteProductForm');
        form.action = `/inventory/${id}`;
        form.submit();
      }
    });
  }
</script>
@endpush
