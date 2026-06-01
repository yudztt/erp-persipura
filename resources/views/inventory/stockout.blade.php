@extends('layouts.app')

@section('title', 'Stock Out - Persipura ERP')
@section('header_title', 'Stock Out')
@section('header_breadcrumb', '/ Sales Orders')

@section('content')
  <!-- Toolbar -->
  <div class="toolbar">
    <div class="search-box">
      <i class="ti ti-search" style="color:var(--gray-400);font-size:14px"></i>
      <input type="text" placeholder="Search sales orders...">
    </div>
    <button class="filter-btn">
      <i class="ti ti-calendar"></i> Date Range
    </button>
    <div style="flex:1"></div>
    <button class="btn btn-primary" onclick="openModal('newStockOutModal')">
      <i class="ti ti-plus"></i> New Stock Out
    </button>
  </div>

  <!-- Table -->
  <div class="table-card">
    <div class="table-header">
      <div>
        <div class="section-title">Sales Orders</div>
        <div class="section-subtitle">Track outgoing inventory</div>
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
        <tr>
          <td><code style="font-size:11px;background:var(--gray-100);padding:2px 6px;border-radius:4px">SO-2026-089</code></td>
          <td><strong>Toko Mutiara Jayapura</strong></td>
          <td>May 31, 2026</td>
          <td>12 items</td>
          <td><strong>Rp 8,400,000</strong></td>
          <td><span class="badge badge-success">Completed</span></td>
          <td>
            <button class="btn-ghost"><i class="ti ti-eye"></i></button>
            <button class="btn-ghost"><i class="ti ti-printer"></i></button>
          </td>
        </tr>
        <tr>
          <td><code style="font-size:11px;background:var(--gray-100);padding:2px 6px;border-radius:4px">SO-2026-090</code></td>
          <td><strong>Online Store</strong></td>
          <td>Jun 1, 2026</td>
          <td>3 items</td>
          <td><strong>Rp 1,050,000</strong></td>
          <td><span class="badge badge-warning">Processing</span></td>
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
<div class="modal-overlay" id="newStockOutModal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">New Stock Out</div>
      <button class="modal-close" onclick="closeModal('newStockOutModal')"><i class="ti ti-x"></i></button>
    </div>
    <div class="modal-body">
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Customer</label>
          <select class="form-select">
            <option>Toko Mutiara Jayapura</option>
            <option>Online Store</option>
            <option>Walk-in Customer</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Sales Date</label>
          <input type="date" class="form-input">
        </div>
        <div class="form-group">
          <label class="form-label">Notes</label>
          <input type="text" class="form-input" placeholder="Optional notes">
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('newStockOutModal')">Cancel</button>
      <button class="btn btn-primary">Create Sales Order</button>
    </div>
  </div>
</div>
@endpush
