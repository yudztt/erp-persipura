@extends('layouts.app')

@section('title', 'Kategori - Cendrawasih Karsa Store')
@section('header_title', 'Kategori Produk')

@section('content')

  {{-- Toolbar --}}
  <div class="toolbar">
    <div class="search-box">
      <i class="ti ti-search" style="color:var(--gray-400);font-size:14px"></i>
      <input type="text" placeholder="Cari kategori..." id="searchInput">
    </div>
    <div style="flex:1"></div>
    <button class="btn btn-primary" onclick="openModal('addCategoryModal')">
      <i class="ti ti-plus"></i> Tambah Kategori
    </button>
  </div>

  {{-- Categories Table --}}
  <div class="table-card">
    <div class="table-header">
      <div>
        <div class="section-title">Daftar Kategori</div>
        <div class="section-subtitle">Kelola kategori produk Anda</div>
      </div>
    </div>
    <table>
      <thead>
        <tr>
          <th style="text-align:center;width:60px">NO</th>
          <th>Nama Kategori</th>
          <th>Jumlah Produk</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody id="categoryTable">
        @forelse($kategoris as $i => $kategori)
        <tr>
          <td style="color:var(--gray-400);font-size:12px;text-align:center">{{ $i + 1 }}</td>
          <td>
            <div style="display:flex;align-items:center;gap:10px">
              <div style="width:32px;height:32px;border-radius:8px;background:#EEF2FF;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i class="ti ti-tag" style="color:#6366F1;font-size:15px"></i>
              </div>
              <strong>{{ $kategori->nama_kategori }}</strong>
            </div>
          </td>
          <td>
            <span style="font-weight:600">{{ number_format($kategori->produks_count) }}</span>
            <span style="color:var(--gray-400);font-size:12px"> produk</span>
          </td>
          <td>
            <button class="btn-ghost" title="Edit"
              onclick="openEditModal({{ $kategori->id_kategori }}, '{{ addslashes($kategori->nama_kategori) }}')">
              <i class="ti ti-edit"></i>
            </button>
            <button class="btn-ghost" title="Hapus"
              onclick="confirmDelete({{ $kategori->id_kategori }}, '{{ addslashes($kategori->nama_kategori) }}')">
              <i class="ti ti-trash" style="color:var(--red)"></i>
            </button>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="4" style="text-align:center;padding:48px;color:var(--gray-400)">
            <i class="ti ti-category-off" style="font-size:48px;opacity:0.3;display:block;margin-bottom:12px"></i>
            Belum ada kategori. Tambahkan kategori pertama Anda!
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

@endsection

@push('modals')

{{-- Add Category Modal --}}
<div class="modal-overlay" id="addCategoryModal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Tambah Kategori</div>
      <button class="modal-close" onclick="closeModal('addCategoryModal')"><i class="ti ti-x"></i></button>
    </div>
    <form action="{{ route('kategoris.store') }}" method="POST">
      @csrf
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">Nama Kategori <span style="color:var(--red)">*</span></label>
          <input type="text" name="nama_kategori" class="form-input" placeholder="Contoh: Jersey, Aksesori..." required autofocus>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('addCategoryModal')">Batal</button>
        <button type="submit" class="btn btn-primary">
          <i class="ti ti-check"></i> Simpan
        </button>
      </div>
    </form>
  </div>
</div>

{{-- Edit Category Modal --}}
<div class="modal-overlay" id="editCategoryModal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Edit Kategori</div>
      <button class="modal-close" onclick="closeModal('editCategoryModal')"><i class="ti ti-x"></i></button>
    </div>
    <form id="editCategoryForm" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">Nama Kategori <span style="color:var(--red)">*</span></label>
          <input type="text" name="nama_kategori" id="edit_nama_kategori" class="form-input" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('editCategoryModal')">Batal</button>
        <button type="submit" class="btn btn-primary">
          <i class="ti ti-check"></i> Perbarui
        </button>
      </div>
    </form>
  </div>
</div>

@endpush

@push('scripts')
<script>
  // Search functionality
  document.getElementById('searchInput').addEventListener('keyup', function () {
    const val = this.value.toLowerCase();
    document.querySelectorAll('#categoryTable tr').forEach(row => {
      row.style.display = row.textContent.toLowerCase().includes(val) ? '' : 'none';
    });
  });

  // Open edit modal
  function openEditModal(id, nama) {
    document.getElementById('editCategoryForm').action = `/kategoris/${id}`;
    document.getElementById('edit_nama_kategori').value = nama;
    openModal('editCategoryModal');
  }

  // Confirm delete with SweetAlert2
  function confirmDelete(id, nama) {
    Swal.fire({
      title: 'Hapus Kategori?',
      text: `Apakah Anda yakin ingin menghapus kategori "${nama}"? Tindakan ini tidak dapat dibatalkan.`,
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
        form.action = `/kategoris/${id}`;
        
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
  @if($errors->any() && old('nama_kategori'))
    openModal('addCategoryModal');
  @endif
</script>
@endpush