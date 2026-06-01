@extends('layouts.app')

@section('title', 'Stock In - Persipura ERP')
@section('header_title', 'Stock In')
@section('header_breadcrumb', '/ Purchase Orders')

@section('content')
  <!-- Toolbar -->
  <div class="toolbar">
    <div class="search-box">
      <i class="ti ti-search" style="color:var(--gray-400);font-size:14px"></i>
      <input type="text" placeholder="Search purchase orders...">
    </div>
    <button class="filter-btn">
      <i class="ti ti-calendar"></i> Date Range
    </button>
    <div style="flex:1"></div>
    <button class="btn btn-primary" onclick="openModal('newStockInModal')">
      <i class="ti ti-plus"></i> New Stock In
    </button>
  </div>

  <!-- Table -->
  <div class="table-card">
    <div class="table-header">
      <div>
        <div class="section-title">Purchase Orders</div>
        <div class="section-subtitle">Track incoming inventory</div>
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
        <tr>
          <td><code style="font-size:11px;background:var(--gray-100);padding:2px 6px;border-radius:4px">PO-2026-001</code></td>
          <td><strong>PT Garuda Sportindo</strong></td>
          <td>May 28, 2026</td>
          <td>5 items</td>
          <td><strong>Rp 45,000,000</strong></td>
          <td><span class="badge badge-success">Received</span></td>
          <td>
            <button class="btn-ghost"><i class="ti ti-eye"></i></button>
            <button class="btn-ghost"><i class="ti ti-printer"></i></button>
          </td>
        </tr>
        <tr>
          <td><code style="font-size:11px;background:var(--gray-100);padding:2px 6px;border-radius:4px">PO-2026-002</code></td>
          <td><strong>CV Merchandise Papua</strong></td>
          <td>May 30, 2026</td>
          <td>3 items</td>
          <td><strong>Rp 12,500,000</strong></td>
          <td><span class="badge badge-warning">Pending</span></td>
          <td>
            <button class="btn-ghost"><i class="ti ti-eye"></i></button>
            <button class="btn-ghost"><i class="ti ti-printer"></i></button>
          </td>
        </tr>
      </tbody>
    </table>
    <div class="pagination">
      <button class="page-btn"><i class="ti ti-chevron-left"></i></button>
      <button class="page-btn active">1</button>
      <button class="page-btn">2</button>
      <button class="page-btn"><i class="ti ti-chevron-right"></i></button>
    </div>
  </div>
@endsection

@push('modals')
<div class="modal-overlay" id="newStockInModal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">New Stock In</div>
      <button class="modal-close" onclick="closeModal('newStockInModal')"><i class="ti ti-x"></i></button>
    </div>
    <div class="modal-body">
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Supplier</label>
          <select class="form-select">
            <option>PT Garuda Sportindo</option>
            <option>CV Merchandise Papua</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Purchase Date</label>
          <input type="date" class="form-input">
        </div>
        <div class="form-group">
          <label class="form-label">Notes</label>
          <input type="text" class="form-input" placeholder="Optional notes">
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('newStockInModal')">Cancel</button>
      <button class="btn btn-primary">Create Purchase Order</button>
    </div>
  </div>
</div>
@endpush
