@extends('layouts.app')

@section('title', 'Pengguna - Cendrawasih Karsa Store')
@section('header_title', 'Manajemen Pengguna')

@section('content')



  {{-- Toolbar --}}
  <div class="toolbar">
    <div class="search-box">
      <i class="ti ti-search" style="color:var(--gray-400);font-size:14px"></i>
      <input type="text" placeholder="Cari pengguna..." id="searchInput">
    </div>
    <div style="flex:1"></div>
    <button class="btn btn-primary" onclick="openModal('addUserModal')">
      <i class="ti ti-plus"></i> Tambah Pengguna
    </button>
  </div>

  {{-- Users Table --}}
  <div class="table-card">
    <div class="table-header">
      <div>
        <div class="section-title">Daftar Pengguna</div>
        <div class="section-subtitle">Kelola akun dan hak akses pengguna sistem</div>
      </div>
      <div style="font-size:13px;color:var(--gray-500)">
        <strong style="color:var(--gray-900)">{{ $users->count() }}</strong> pengguna terdaftar
      </div>
    </div>
    <table>
      <thead>
        <tr>
          <th>Pengguna</th>
          <th>Email</th>
          <th>Role</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody id="usersTable">
        @forelse($users as $user)
        <tr>
          <td>
            <div style="display:flex;align-items:center;gap:10px">
              <div class="user-avatar" style="width:34px;height:34px;font-size:12px;flex-shrink:0;
                background:{{ ['#C1121F','#059669','#D97706','#6366F1','#0891B2','#7C3AED'][($user->id_user - 1) % 6] }}">
                {{ strtoupper(substr($user->nama, 0, 2)) }}
              </div>
              <div>
                <div style="font-weight:600;font-size:13px">{{ $user->nama }}</div>
                @if($user->id_user === auth()->id())
                  <div style="font-size:11px;color:var(--gray-400)">(Akun Anda)</div>
                @endif
              </div>
            </div>
          </td>
          <td style="color:var(--gray-600)">{{ $user->email }}</td>
          <td>
            <span class="badge badge-gray">{{ $user->role->nama_role ?? '-' }}</span>
          </td>
          <td>
            <button class="btn-ghost" title="Edit"
              onclick="openEditUser({{ $user->id_user }}, '{{ addslashes($user->nama) }}', '{{ $user->email }}', {{ $user->id_role }})">
              <i class="ti ti-edit"></i>
            </button>
            @if($user->id_user !== auth()->id())
            <button class="btn-ghost" title="Hapus"
              onclick="confirmDeleteUser({{ $user->id_user }}, '{{ addslashes($user->nama) }}')">
              <i class="ti ti-trash" style="color:var(--red)"></i>
            </button>
            @endif
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="4" style="text-align:center;padding:48px;color:var(--gray-400)">
            <i class="ti ti-users-off" style="font-size:48px;opacity:0.3;display:block;margin-bottom:12px"></i>
            Belum ada pengguna terdaftar.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
    {{ $users->links() }}
  </div>

@endsection

@push('modals')

{{-- Add User Modal --}}
<div class="modal-overlay" id="addUserModal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Tambah Pengguna</div>
      <button class="modal-close" onclick="closeModal('addUserModal')"><i class="ti ti-x"></i></button>
    </div>
    <form action="{{ route('users.store') }}" method="POST">
      @csrf
      <div class="modal-body">
        <div class="form-grid">
          <div class="form-group">
            <label class="form-label">Nama Lengkap <span style="color:var(--red)">*</span></label>
            <input type="text" name="nama" class="form-input" placeholder="Nama pengguna" required value="{{ old('nama') }}">
          </div>
          <div class="form-group">
            <label class="form-label">Email <span style="color:var(--red)">*</span></label>
            <input type="email" name="email" class="form-input" placeholder="email@domain.com" required value="{{ old('email') }}">
          </div>
          <div class="form-group">
            <label class="form-label">Role <span style="color:var(--red)">*</span></label>
            <select name="id_role" class="form-select" required>
              <option value="">-- Pilih Role --</option>
              @foreach($roles as $role)
                <option value="{{ $role->id_role }}" {{ old('id_role') == $role->id_role ? 'selected' : '' }}>
                  {{ $role->nama_role }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="form-grid form-grid-2">
            <div class="form-group">
              <label class="form-label">Password <span style="color:var(--red)">*</span></label>
              <input type="password" name="password" class="form-input" placeholder="Min. 8 karakter" required>
            </div>
            <div class="form-group">
              <label class="form-label">Konfirmasi Password <span style="color:var(--red)">*</span></label>
              <input type="password" name="password_confirmation" class="form-input" placeholder="Ulangi password" required>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('addUserModal')">Batal</button>
        <button type="submit" class="btn btn-primary"><i class="ti ti-check"></i> Simpan</button>
      </div>
    </form>
  </div>
</div>

{{-- Edit User Modal --}}
<div class="modal-overlay" id="editUserModal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Edit Pengguna</div>
      <button class="modal-close" onclick="closeModal('editUserModal')"><i class="ti ti-x"></i></button>
    </div>
    <form id="editUserForm" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-body">
        <div class="form-grid">
          <div class="form-group">
            <label class="form-label">Nama Lengkap <span style="color:var(--red)">*</span></label>
            <input type="text" name="nama" id="edit_nama" class="form-input" required>
          </div>
          <div class="form-group">
            <label class="form-label">Email <span style="color:var(--red)">*</span></label>
            <input type="email" name="email" id="edit_email" class="form-input" required>
          </div>
          <div class="form-group">
            <label class="form-label">Role <span style="color:var(--red)">*</span></label>
            <select name="id_role" id="edit_id_role" class="form-select" required>
              <option value="">-- Pilih Role --</option>
              @foreach($roles as $role)
                <option value="{{ $role->id_role }}">{{ $role->nama_role }}</option>
              @endforeach
            </select>
          </div>
          <div style="background:var(--gray-50);border:1px solid var(--gray-200);border-radius:8px;padding:12px">
            <p style="font-size:12px;color:var(--gray-500);margin-bottom:10px">
              <i class="ti ti-lock"></i> Kosongkan jika tidak ingin mengubah password
            </p>
            <div class="form-grid form-grid-2">
              <div class="form-group">
                <label class="form-label">Password Baru</label>
                <input type="password" name="password" class="form-input" placeholder="Min. 8 karakter">
              </div>
              <div class="form-group">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-input" placeholder="Ulangi password">
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('editUserModal')">Batal</button>
        <button type="submit" class="btn btn-primary"><i class="ti ti-check"></i> Perbarui</button>
      </div>
    </form>
  </div>
</div>

@endpush

@push('scripts')
<script>
  // Search
  document.getElementById('searchInput').addEventListener('keyup', function () {
    const val = this.value.toLowerCase();
    document.querySelectorAll('#usersTable tr').forEach(row => {
      row.style.display = row.textContent.toLowerCase().includes(val) ? '' : 'none';
    });
  });

  // Open edit modal
  function openEditUser(id, nama, email, roleId) {
    document.getElementById('editUserForm').action = `/users/${id}`;
    document.getElementById('edit_nama').value  = nama;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_id_role').value = roleId;
    openModal('editUserModal');
  }

  // Confirm delete with SweetAlert2
  function confirmDeleteUser(id, nama) {
    Swal.fire({
      title: 'Hapus Pengguna?',
      text: `Apakah Anda yakin ingin menghapus pengguna "${nama}"? Tindakan ini tidak dapat dibatalkan.`,
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
        form.action = `/users/${id}`;
        
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

  // Re-open add modal on validation error
  @if($errors->any() && old('nama'))
    openModal('addUserModal');
  @endif
</script>
@endpush
