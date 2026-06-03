@extends('layouts.app')

@section('title', 'Stock Out - Cendrawasih Karsa Store')
@section('header_title', 'Stok Keluar')
@section('header_breadcrumb', '/ Sales Orders')

@section('content')
  <!-- Toolbar -->
  <form action="{{ route('inventory.stockout') }}" method="GET" class="toolbar" id="searchForm">
    <div class="search-box">
      <i class="ti ti-search" style="color:var(--gray-400);font-size:14px"></i>
      <input type="text" name="search" placeholder="Search sales orders..." id="searchInput" value="{{ request('search') }}">
    </div>
    <div style="flex:1"></div>
    <button type="button" class="btn btn-primary" onclick="openModal('newStockOutModal')">
      <i class="ti ti-plus"></i> New Stock Out
    </button>
  </form>

  <!-- Table -->
  <div class="table-card">
    <div class="table-header">
      <div>
        <div class="section-title">Sales Orders</div>
        <div class="section-subtitle">Track outgoing inventory and customer shipments</div>
      </div>
    </div>
    <table>
      <thead>
        <tr>
          <th>SO Number</th>
          <th>Customer</th>
          <th>Date</th>
          <th>Items</th>
          <th>Total</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($penjualans as $penjualan)
        <tr>
          <td><code style="font-size:11px;background:var(--gray-100);padding:2px 6px;border-radius:4px">SO-{{ str_pad($penjualan->id_penjualan, 5, '0', STR_PAD_LEFT) }}</code></td>
          <td><strong>{{ $penjualan->customer->nama_customer ?? '-' }}</strong></td>
          <td>{{ $penjualan->tanggal->format('d M Y') }}</td>
          <td>{{ $penjualan->detailPenjualans->count() }} item(s)</td>
          <td><strong>Rp {{ number_format($penjualan->total, 0, ',', '.') }}</strong></td>
          <td><span class="badge badge-success">Selesai</span></td>
          <td>
            <button class="btn-ghost" title="Detail Items" onclick="viewItems('SO-{{ str_pad($penjualan->id_penjualan, 5, '0', STR_PAD_LEFT) }}', '{{ $penjualan->customer->nama_customer ?? '-' }}', '{{ $penjualan->tanggal->format('d M Y') }}', {{ json_encode($penjualan->detailPenjualans->map(function($d) { return ['nama_produk' => $d->produk->nama_produk, 'qty' => $d->qty, 'harga' => $d->harga, 'subtotal' => $d->subtotal]; })) }})"><i class="ti ti-eye" style="font-size:16px;"></i></button>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" style="text-align:center;padding:40px;color:var(--gray-400)">
            <i class="ti ti-package" style="font-size:48px;opacity:0.3"></i>
            <div style="margin-top:12px">Belum ada transaksi stok keluar yang terdaftar.</div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
    <div style="padding: 16px 20px; border-top: 1px solid var(--gray-100); display: flex; justify-content: flex-end;">
      {{ $penjualans->links() }}
    </div>
  </div>
@endsection

@push('modals')
<!-- New Stock Out Modal -->
<div class="modal-overlay" id="newStockOutModal">
  <div class="modal" style="width: 650px; max-width: 95%;">
    <div class="modal-header">
      <div class="modal-title">New Sales Order (Stock Out)</div>
      <button class="modal-close" onclick="closeModal('newStockOutModal')"><i class="ti ti-x"></i></button>
    </div>
    <form action="{{ route('inventory.stockout.store') }}" method="POST">
      @csrf
      <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
        <div class="form-grid">
          <div class="form-grid form-grid-2">
            <div class="form-group">
              <label class="form-label">Customer *</label>
              <select name="id_customer" class="form-select" required>
                <option value="">Select Customer</option>
                @foreach(\App\Models\Customer::all() as $customer)
                  <option value="{{ $customer->id_customer }}">{{ $customer->nama_customer }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Sales Date *</label>
              <input type="date" name="tanggal" class="form-input" value="{{ date('Y-m-d') }}" required>
            </div>
          </div>
          
          <hr style="border-color: var(--gray-100); margin: 8px 0;">
          <div style="font-weight:600; font-size:13px; color:var(--gray-800); display: flex; justify-content: space-between; align-items: center;">
            <span>Daftar Item Barang</span>
            <button type="button" class="btn btn-secondary btn-sm" onclick="addPenjualanItemRow()" style="padding: 4px 8px; font-size: 11px;">
              <i class="ti ti-plus"></i> Tambah Item
            </button>
          </div>
          
          <div id="penjualan-items-container" style="display:flex; flex-direction:column; gap:8px;">
            <!-- Item Row template -->
            <div class="form-grid" style="grid-template-columns: 2fr 1fr 1.2fr auto; gap: 8px; align-items: flex-end;">
              <div class="form-group" style="gap:4px">
                <label class="form-label" style="font-size:11px">Produk *</label>
                <select name="details[0][id_produk]" class="form-select" onchange="updatePrice(this, 0)" required>
                  <option value="">Select Product</option>
                  @foreach(\App\Models\Produk::all() as $prod)
                    <option value="{{ $prod->id_produk }}" data-price="{{ $prod->harga_jual }}">{{ $prod->nama_produk }} (Stok: {{ $prod->stok }})</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group" style="gap:4px">
                <label class="form-label" style="font-size:11px">Qty *</label>
                <input type="number" name="details[0][qty]" class="form-input" min="1" required placeholder="0">
              </div>
              <div class="form-group" style="gap:4px">
                <label class="form-label" style="font-size:11px">Harga Jual (Rp) *</label>
                <input type="number" name="details[0][harga]" id="price-0" class="form-input" min="0" required placeholder="0">
              </div>
              <div style="padding-bottom: 4px;">
                <button type="button" class="btn-ghost" style="color:var(--gray-400); cursor:not-allowed;" disabled><i class="ti ti-trash"></i></button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('newStockOutModal')">Cancel</button>
        <button type="submit" class="btn btn-primary">Create Sales Order</button>
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
        <div><strong>Customer:</strong> <span id="detailParty"></span></div>
        <div><strong>Tanggal:</strong> <span id="detailDate"></span></div>
        <div><strong>Status:</strong> <span class="badge badge-success">Selesai</span></div>
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
  function addPenjualanItemRow() {
    const container = document.getElementById('penjualan-items-container');
    const newRow = document.createElement('div');
    newRow.className = 'form-grid';
    newRow.style = 'grid-template-columns: 2fr 1fr 1.2fr auto; gap: 8px; align-items: flex-end; margin-top: 8px;';
    
    // Build options with their prices
    let options = '<option value="">Select Product</option>';
    @foreach(\App\Models\Produk::all() as $prod)
      options += `<option value="{{ $prod->id_produk }}" data-price="{{ $prod->harga_jual }}">{{ $prod->nama_produk }} (Stok: {{ $prod->stok }})</option>`;
    @endforeach

    newRow.innerHTML = `
      <div class="form-group" style="gap:4px">
        <select name="details[${itemIndex}][id_produk]" class="form-select" onchange="updatePrice(this, ${itemIndex})" required>
          ${options}
        </select>
      </div>
      <div class="form-group" style="gap:4px">
        <input type="number" name="details[${itemIndex}][qty]" class="form-input" min="1" required placeholder="0">
      </div>
      <div class="form-group" style="gap:4px">
        <input type="number" name="details[${itemIndex}][harga]" id="price-${itemIndex}" class="form-input" min="0" required placeholder="0">
      </div>
      <div style="padding-bottom: 4px;">
        <button type="button" class="btn-ghost" style="color:var(--red);" onclick="this.closest('.form-grid').remove()"><i class="ti ti-trash"></i></button>
      </div>
    `;
    container.appendChild(newRow);
    itemIndex++;
  }

  function updatePrice(selectElement, index) {
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const price = selectedOption.getAttribute('data-price') || 0;
    document.getElementById('price-' + index).value = price;
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
