@extends('layouts.app')

@section('title', 'Gudang - Persipura ERP')
@section('header_title', 'Gudang')
@section('header_breadcrumb', '/ Manajemen Penyimpanan')

@section('content')

  <div class="table-card">
    <div class="table-header">
      <div>
        <div class="section-title">Lokasi Gudang</div>
        <div class="section-subtitle">Kelola fasilitas penyimpanan Anda</div>
      </div>
      <button class="btn btn-primary" onclick="openModal('addWarehouseModal')">
        <i class="ti ti-plus"></i> Tambah Gudang
      </button>
    </div>
    <table>
      <thead>
        <tr>
          <th>Gudang</th>
          <th>Lokasi</th>
          <th>Kapasitas</th>
          <th>Stok Saat Ini</th>
          <th>Utilisasi</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><strong>Gudang A - Utama</strong></td>
          <td>Jayapura, Papua</td>
          <td>8.000 unit</td>
          <td>6.200 unit</td>
          <td>
            <div style="display:flex;align-items:center;gap:8px">
              <div style="flex:1;height:6px;background:var(--gray-200);border-radius:3px;overflow:hidden">
                <div style="width:77.5%;height:100%;background:#059669"></div>
              </div>
              <span style="font-size:12px;font-weight:600">77.5%</span>
            </div>
          </td>
          <td>
            <button class="btn-ghost" title="Ubah"><i class="ti ti-edit"></i></button>
            <button class="btn-ghost" title="Lihat"><i class="ti ti-eye"></i></button>
          </td>
        </tr>
        <tr>
          <td><strong>Gudang B - Sekunder</strong></td>
          <td>Sentani, Papua</td>
          <td>5.000 unit</td>
          <td>3.100 unit</td>
          <td>
            <div style="display:flex;align-items:center;gap:8px">
              <div style="flex:1;height:6px;background:var(--gray-200);border-radius:3px;overflow:hidden">
                <div style="width:62%;height:100%;background:#059669"></div>
              </div>
              <span style="font-size:12px;font-weight:600">62%</span>
            </div>
          </td>
          <td>
            <button class="btn-ghost" title="Ubah"><i class="ti ti-edit"></i></button>
            <button class="btn-ghost" title="Lihat"><i class="ti ti-eye"></i></button>
          </td>
        </tr>
        <tr>
          <td><strong>Gudang C - Distribusi</strong></td>
          <td>Abepura, Papua</td>
          <td>2.000 unit</td>
          <td>1.500 unit</td>
          <td>
            <div style="display:flex;align-items:center;gap:8px">
              <div style="flex:1;height:6px;background:var(--gray-200);border-radius:3px;overflow:hidden">
                <div style="width:75%;height:100%;background:#059669"></div>
              </div>
              <span style="font-size:12px;font-weight:600">75%</span>
            </div>
          </td>
          <td>
            <button class="btn-ghost" title="Ubah"><i class="ti ti-edit"></i></button>
            <button class="btn-ghost" title="Lihat"><i class="ti ti-eye"></i></button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
@endsection

@push('modals')
<div class="modal-overlay" id="addWarehouseModal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Tambah Gudang Baru</div>
      <button class="modal-close" onclick="closeModal('addWarehouseModal')"><i class="ti ti-x"></i></button>
    </div>
    <div class="modal-body">
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Nama Gudang</label>
          <input type="text" class="form-input" placeholder="Masukkan nama gudang">
        </div>
        <div class="form-group">
          <label class="form-label">Lokasi</label>
          <input type="text" class="form-input" placeholder="Kota, Provinsi">
        </div>
        <div class="form-group">
          <label class="form-label">Kapasitas (unit)</label>
          <input type="number" class="form-input" placeholder="0">
        </div>
        <div class="form-group">
          <label class="form-label">Alamat</label>
          <input type="text" class="form-input" placeholder="Alamat lengkap">
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('addWarehouseModal')">Batal</button>
      <button class="btn btn-primary">Simpan Gudang</button>
    </div>
  </div>
</div>
@endpush