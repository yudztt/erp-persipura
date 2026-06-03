@extends('layouts.app')

@section('title', 'Stock In - Cendrawasih Karsa Store')
@section('header_title', 'Stok Masuk')
@section('header_breadcrumb', '/ Purchase Orders')

@section('content')
  <!-- Toolbar -->
  <form action="{{ route('inventory.stockin') }}" method="GET" class="toolbar" id="searchForm">
    <div class="search-box">
      <i class="ti ti-search" style="color:var(--gray-400);font-size:14px"></i>
      <input type="text" name="search" placeholder="Search purchase orders..." id="searchInput" value="{{ request('search') }}">
    </div>
    <div style="flex:1"></div>
    <button type="button" class="btn btn-primary" onclick="openModal('newStockInModal')">
      <i class="ti ti-plus"></i> New Stock In
    </button>
  </form>

  <!-- Table -->
  <div class="table-card">
    <div class="table-header">
      <div>
        <div class="section-title">Purchase Orders</div>
        <div class="section-subtitle">Track incoming inventory and supplier shipments</div>
      </div>
    </div>
    <table>
      <thead>
        <tr>
          <th>PO Number</th>
          <th>Supplier</th>
          <th>Date</th>
          <th>Items</th>
          <th>Total</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($pembelians as $pembelian)
        <tr>
          <td><code style="font-size:11px;background:var(--gray-100);padding:2px 6px;border-radius:4px">PO-{{ str_pad($pembelian->id_pembelian, 5, '0', STR_PAD_LEFT) }}</code></td>
          <td><strong>{{ $pembelian->supplier->nama_supplier ?? '-' }}</strong></td>
          <td>{{ $pembelian->tanggal->format('d M Y') }}</td>
          <td>{{ $pembelian->detailPembelians->count() }} item(s)</td>
          <td><strong>Rp {{ number_format($pembelian->total, 0, ',', '.') }}</strong></td>
          <td><span class="badge badge-success">Diterima</span></td>
          <td>
            <button class="btn-ghost" title="Detail Items" onclick="viewItems('PO-{{ str_pad($pembelian->id_pembelian, 5, '0', STR_PAD_LEFT) }}', '{{ $pembelian->supplier->nama_supplier ?? '-' }}', '{{ $pembelian->tanggal->format('d M Y') }}', {{ json_encode($pembelian->detailPembelians->map(function($d) { return ['nama_produk' => $d->produk->nama_produk, 'qty' => $d->qty, 'harga' => $d->harga, 'subtotal' => $d->subtotal]; })) }})"><i class="ti ti-eye" style="font-size:16px;"></i></button>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" style="text-align:center;padding:40px;color:var(--gray-400)">
            <i class="ti ti-package" style="font-size:48px;opacity:0.3"></i>
            <div style="margin-top:12px">Belum ada transaksi stok masuk yang terdaftar.</div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
    <div style="padding: 16px 20px; border-top: 1px solid var(--gray-100); display: flex; justify-content: flex-end;">
      {{ $pembelians->links() }}
    </div>
  </div>
@endsection

@push('modals')
<!-- New Stock In Modal -->
<div class="modal-overlay" id="newStockInModal">
  <div class="modal" style="width: 650px; max-width: 95%;">
    <div class="modal-header">
      <div class="modal-title">New Purchase Order (Stock In)</div>
      <button class="modal-close" onclick="closeModal('newStockInModal')"><i class="ti ti-x"></i></button>
    </div>
    <form action="{{ route('inventory.stockin.store') }}" method="POST">
      @csrf
      <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
        <div class="form-grid">
          <div class="form-grid form-grid-2">
            <div class="form-group">
              <label class="form-label">Supplier *</label>
              <select name="id_supplier" class="form-select" required>
                <option value="">Select Supplier</option>
                @foreach(\App\Models\Supplier::all() as $supplier)
                  <option value="{{ $supplier->id_supplier }}">{{ $supplier->nama_supplier }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Purchase Date *</label>
              <input type="date" name="tanggal" class="form-input" value="{{ date('Y-m-d') }}" required>
            </div>
          </div>
          
          <hr style="border-color: var(--gray-100); margin: 8px 0;">
          <div style="font-weight:600; font-size:13px; color:var(--gray-800); display: flex; justify-content: space-between; align-items: center;">
            <span>Daftar Item Barang</span>
            <button type="button" class="btn btn-secondary btn-sm" onclick="addPembelianItemRow()" style="padding: 4px 8px; font-size: 11px;">
              <i class="ti ti-plus"></i> Tambah Item
            </button>
          </div>
          
          <div id="pembelian-items-container" style="display:flex; flex-direction:column; gap:8px;">
            <!-- Item Row template -->
            <div class="form-grid" style="grid-template-columns: 2fr 1fr 1.2fr auto; gap: 8px; align-items: flex-end;">
              <div class="form-group" style="gap:4px">
                <label class="form-label" style="font-size:11px">Produk *</label>
                <select name="details[0][id_produk]" class="form-select" required>
                  <option value="">Select Product</option>
                  @foreach(\App\Models\Produk::all() as $prod)
                    <option value="{{ $prod->id_produk }}">{{ $prod->nama_produk }} (Stok: {{ $prod->stok }})</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group" style="gap:4px">
                <label class="form-label" style="font-size:11px">Qty *</label>
                <input type="number" name="details[0][qty]" class="form-input" min="1" required placeholder="0">
              </div>
              <div class="form-group" style="gap:4px">
                <label class="form-label" style="font-size:11px">Harga Beli (Rp) *</label>
                <input type="number" name="details[0][harga]" class="form-input" min="0" required placeholder="0">
              </div>
              <div style="padding-bottom: 4px;">
                <button type="button" class="btn-ghost" style="color:var(--gray-400); cursor:not-allowed;" disabled><i class="ti ti-trash"></i></button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('newStockInModal')">Cancel</button>
        <button type="submit" class="btn btn-primary">Create Purchase Order</button>
      </div>
    </form>
  </div>
</div>

<!-- View Detail Modal -->
<div class="modal-overlay" id="viewDetailModal">
  <div class="modal" style="width: 600px; max-width: 95%;">
    <div class="modal-header">
      <div class="modal-title">Detail Transaksi</div>
      <button class="modal-close" onclick="closeModal('viewDetailModal')"><i class="ti ti-x"></i></button>
    </div>
    <div class="modal-body">
      <div style="margin-bottom:16px; font-size:13px; color:var(--gray-600); display:grid; grid-template-columns:1fr 1fr; gap:12px; border-bottom: 1px solid var(--gray-100); padding-bottom: 12px;">
        <div><strong>No. Transaksi:</strong> <span id="detailNo"></span></div>
        <div><strong>Supplier:</strong> <span id="detailParty"></span></div>
        <div><strong>Tanggal:</strong> <span id="detailDate"></span></div>
        <div><strong>Status:</strong> <span class="badge badge-success">Diterima</span></div>
      </div>
      <div class="table-card" style="margin-bottom: 0; box-shadow: none; border-radius: 8px; border: 1px solid var(--gray-200);">
        <table style="width: 100%; border-collapse: collapse;">
          <thead>
            <tr style="background: var(--gray-50);">
              <th style="padding: 8px 12px; font-size: 11px; text-transform: uppercase;">Nama Produk</th>
              <th style="padding: 8px 12px; font-size: 11px; text-transform: uppercase; text-align: center;">Jumlah</th>
              <th style="padding: 8px 12px; font-size: 11px; text-transform: uppercase; text-align: right;">Harga Satuan</th>
              <th style="padding: 8px 12px; font-size: 11px; text-transform: uppercase; text-align: right;">Subtotal</th>
            </tr>
          </thead>
          <tbody id="detailTableBody">
            <!-- Dynamic rows will be inserted here -->
          </tbody>
        </table>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('viewDetailModal')">Tutup</button>
    </div>
  </div>
</div>
@endpush

@push('scripts')
<script>
  let itemIndex = 1;
  function addPembelianItemRow() {
    const container = document.getElementById('pembelian-items-container');
    const newRow = document.createElement('div');
    newRow.className = 'form-grid';
    newRow.style = 'grid-template-columns: 2fr 1fr 1.2fr auto; gap: 8px; align-items: flex-end; margin-top: 8px;';
    newRow.innerHTML = `
      <div class="form-group" style="gap:4px">
        <select name="details[${itemIndex}][id_produk]" class="form-select" required>
          <option value="">Select Product</option>
          @foreach(\App\Models\Produk::all() as $prod)
            <option value="{{ $prod->id_produk }}">{{ $prod->nama_produk }} (Stok: {{ $prod->stok }})</option>
          @endforeach
        </select>
      </div>
      <div class="form-group" style="gap:4px">
        <input type="number" name="details[${itemIndex}][qty]" class="form-input" min="1" required placeholder="0">
      </div>
      <div class="form-group" style="gap:4px">
        <input type="number" name="details[${itemIndex}][harga]" class="form-input" min="0" required placeholder="0">
      </div>
      <div style="padding-bottom: 4px;">
        <button type="button" class="btn-ghost" style="color:var(--red);" onclick="this.closest('.form-grid').remove()"><i class="ti ti-trash"></i></button>
      </div>
    `;
    container.appendChild(newRow);
    itemIndex++;
  }

  function viewItems(code, party, date, details) {
    document.getElementById('detailNo').innerText = code;
    document.getElementById('detailParty').innerText = party;
    document.getElementById('detailDate').innerText = date;
    
    const tbody = document.getElementById('detailTableBody');
    tbody.innerHTML = '';
    
    let grandTotal = 0;
    details.forEach(item => {
      grandTotal += parseFloat(item.subtotal);
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td style="padding: 8px 12px; border-bottom: 1px solid var(--gray-100);"><strong>${item.nama_produk}</strong></td>
        <td style="padding: 8px 12px; border-bottom: 1px solid var(--gray-100); text-align: center;">${item.qty} unit</td>
        <td style="padding: 8px 12px; border-bottom: 1px solid var(--gray-100); text-align: right;">Rp ${new Intl.NumberFormat('id-ID').format(item.harga)}</td>
        <td style="padding: 8px 12px; border-bottom: 1px solid var(--gray-100); text-align: right; font-weight: 700;">Rp ${new Intl.NumberFormat('id-ID').format(item.subtotal)}</td>
      `;
      tbody.appendChild(tr);
    });
    
    // Add a grand total row
    const totalTr = document.createElement('tr');
    totalTr.style.background = 'var(--gray-50)';
    totalTr.innerHTML = `
      <td colspan="3" style="padding: 10px 12px; text-align: right; font-weight: 700;">TOTAL AKHIR:</td>
      <td style="padding: 10px 12px; text-align: right; font-weight: 800; color: var(--red); font-size: 14px;">Rp ${new Intl.NumberFormat('id-ID').format(grandTotal)}</td>
    `;
    tbody.appendChild(totalTr);
    
    openModal('viewDetailModal');
  }
</script>
@endpush
