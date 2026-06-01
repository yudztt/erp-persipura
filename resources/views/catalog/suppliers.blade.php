@extends('layouts.app')

@section('title', 'Supplier - Cendrawasih Karsa Store')
@section('header_title', 'Manajemen Supplier')

@section('content')

  {{-- Toolbar --}}
  <div class="toolbar">
    <div class="search-box">
      <i class="ti ti-search" style="color:var(--gray-400);font-size:14px"></i>
      <input type="text" placeholder="Cari supplier..." id="searchInput">
    </div>
    <div style="flex:1"></div>
    <button class="btn btn-primary" onclick="openModal('addSupplierModal')">
      <i class="ti ti-plus"></i> Tambah Supplier
    </button>
  </div>

  {{-- Suppliers Table --}}
  <div class="table-card">
    <div class="table-header">
      <div>
        <div class="section-title">Daftar Supplier</div>
        <div class="section-subtitle">Kelola data pemasok produk Anda</div>
      </div>
    </div>
    <table>
      <thead>
        <tr>
          <th style="text-align:center;width:60px">NO</th>
          <th>Nama Supplier</th>
          <th>Telepon</th>
          <th>Alamat</th>
          <th>Produk</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody id="supplierTable">
        @forelse($suppliers as $i => $supplier)
        <tr>
          <td style="color:var(--gray-400);font-size:12px;text-align:center">{{ $i + 1 }}</td>
          <td>
            <div style="display:flex;align-items:center;gap:10px">
              <div style="width:34px;height:34px;border-radius:8px;background:#FEE2E2;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i class="ti ti-truck" style="color:var(--red);font-size:15px"></i>
              </div>
              <strong>{{ $supplier->nama_supplier }}</strong>
            </div>
          </td>
          <td style="color:var(--gray-600)">
            {{ $supplier->telepon ?: '-' }}
          </td>
          <td style="color:var(--gray-600);max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
            {{ $supplier->alamat ?: '-' }}
          </td>
          <td>
            <span style="font-weight:600">{{ $supplier->produks_count }}</span>
            <span style="color:var(--gray-400);font-size:12px"> produk</span>
          </td>
          <td>
            <button class="btn-ghost" title="Edit"
              onclick="openEditSupplier(
                {{ $supplier->id_supplier }},
                '{{ addslashes($supplier->nama_supplier) }}',
                '{{ addslashes($supplier->telepon ?? '') }}',
                '{{ addslashes($supplier->alamat ?? '') }}'
              )">
              <i class="ti ti-edit"></i>
            </button>
            <button class="btn-ghost" title="Hapus"
              onclick="confirmDeleteSupplier({{ $supplier->id_supplier }}, '{{ addslashes($supplier->nama_supplier) }}')">
              <i class="ti ti-trash" style="color:var(--red)"></i>
            </button>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" style="text-align:center;padding:48px;color:var(--gray-400)">
            <i class="ti ti-truck-off" style="font-size:48px;opacity:0.3;display:block;margin-bottom:12px"></i>
            Belum ada supplier. Tambahkan supplier pertama Anda!
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
    {{ $suppliers->links() }}
  </div>

@endsection

@push('modals')

{{-- Add Supplier Modal --}}
<div class="modal-overlay" id="addSupplierModal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Tambah Supplier</div>
      <button class="modal-close" onclick="closeModal('addSupplierModal')"><i class="ti ti-x"></i></button>
    </div>
    <form action="{{ route('suppliers.store') }}" method="POST">
      @csrf
      <div class="modal-body">
        <div class="form-grid">
          <div class="form-group">
            <label class="form-label">Nama Supplier <span style="color:var(--red)">*</span></label>
            <input type="text" name="nama_supplier" class="form-input"
              placeholder="Nama perusahaan/perorangan" required
              value="{{ old('nama_supplier') }}">
          </div>
          <div class="form-group">
            <label class="form-label">Nomor Telepon</label>
            <input type="text" name="telepon" class="form-input"
              placeholder="Contoh: 0812-3456-7890"
              value="{{ old('telepon') }}">
          </div>
          <div class="form-group">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" class="form-input" rows="3"
              placeholder="Alamat lengkap supplier" style="resize:vertical">{{ old('alamat') }}</textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('addSupplierModal')">Batal</button>
        <button type="submit" class="btn btn-primary"><i class="ti ti-check"></i> Simpan</button>
      </div>
    </form>
  </div>
</div>

{{-- Edit Supplier Modal --}}
<div class="modal-overlay" id="editSupplierModal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Edit Supplier</div>
      <button class="modal-close" onclick="closeModal('editSupplierModal')"><i class="ti ti-x"></i></button>
    </div>
    <form id="editSupplierForm" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-body">
        <div class="form-grid">
          <div class="form-group">
            <label class="form-label">Nama Supplier <span style="color:var(--red)">*</span></label>
            <input type="text" name="nama_supplier" id="edit_nama_supplier" class="form-input" required>
          </div>
          <div class="form-group">
            <label class="form-label">Nomor Telepon</label>
            <input type="text" name="telepon" id="edit_telepon" class="form-input" placeholder="Contoh: 0812-3456-7890">
          </div>
          <div class="form-group">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" id="edit_alamat" class="form-input" rows="3" style="resize:vertical"></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('editSupplierModal')">Batal</button>
        <button type="submit" class="btn btn-primary"><i class="ti ti-check"></i> Perbarui</button>
      </div>
    </form>
  </div>
</div>

@endpush

@push('styles')
<style>
  textarea.form-input { padding: 9px 12px; }
</style>
@endpush

@push('scripts')
<script>
  // Search
  document.getElementById('searchInput').addEventListener('keyup', function () {
    const val = this.value.toLowerCase();
    document.querySelectorAll('#supplierTable tr').forEach(row => {
      row.style.display = row.textContent.toLowerCase().includes(val) ? '' : 'none';
    });
  });

  // Open edit modal
  function openEditSupplier(id, nama, telepon, alamat) {
    document.getElementById('editSupplierForm').action = `/suppliers/${id}`;
    document.getElementById('edit_nama_supplier').value = nama;
    document.getElementById('edit_telepon').value       = telepon;
    document.getElementById('edit_alamat').value        = alamat;
    openModal('editSupplierModal');
  }

  // Confirm delete with SweetAlert2
  function confirmDeleteSupplier(id, nama) {
    Swal.fire({
      title: 'Hapus Supplier?',
      text: `Apakah Anda yakin ingin menghapus supplier "${nama}"? Tindakan ini tidak dapat dibatalkan.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#C1121F',
      cancelButtonColor: '#6B7280',
      confirmButtonText: '<i class="ti ti-trash"></i> Ya, Hapus!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/suppliers/${id}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
      }
    });
  }

  // Re-open modal on validation error
  @if($errors->any() && old('nama_supplier'))
    openModal('addSupplierModal');
  @endif
</script>
@endpush



dsjnanfjdansa,sd ask dk