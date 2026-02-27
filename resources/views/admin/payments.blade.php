@extends('layouts.admin')

@section('title', 'Payments Management - StayEase Admin')

@section('page_header', 'Payments Management')

@section('page_description', 'Track and manage all rental payments, collections, and financial transactions')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Payments</li>
@endsection

@section('header_actions')
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#recordPaymentModal">
        <i class="bi bi-cash-stack me-2"></i>Record Payment
    </button>
    <div class="btn-group ms-2">
        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
            <i class="bi bi-download me-2"></i>Export
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="#" onclick="exportPayments('pdf')"><i class="bi bi-file-pdf me-2"></i>PDF</a></li>
            <li><a class="dropdown-item" href="#" onclick="exportPayments('excel')"><i class="bi bi-file-excel me-2"></i>Excel</a></li>
            <li><a class="dropdown-item" href="#" onclick="exportPayments('csv')"><i class="bi bi-file-text me-2"></i>CSV</a></li>
        </ul>
    </div>
    <button class="btn btn-outline-primary ms-2" onclick="generateFinancialReport()">
        <i class="bi bi-file-bar-graph me-2"></i>Financial Report
    </button>
@endsection

@section('styles')
<style>
    /* Payment Stats Cards */
    .payment-stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.03);
        border: 1px solid rgba(0,0,0,0.03);
        height: 100%;
        transition: all 0.3s;
    }
    
    .payment-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.1);
        border-color: rgba(102, 126, 234, 0.15);
    }
    
    .payment-stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }
    
    .payment-stat-icon.primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .payment-stat-icon.success {
        background: linear-gradient(135deg, #34b1aa 0%, #2c9a94 100%);
    }
    
    .payment-stat-icon.warning {
        background: linear-gradient(135deg, #f6b23e 0%, #f4a51e 100%);
    }
    
    .payment-stat-icon.info {
        background: linear-gradient(135deg, #3b7cff 0%, #2b6ef0 100%);
    }
    
    .payment-stat-value {
        font-size: 1.6rem;
        font-weight: 700;
        color: #2d3748;
        line-height: 1.2;
    }
    
    .payment-stat-label {
        color: #718096;
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    .payment-stat-change {
        font-size: 0.75rem;
        padding: 0.2rem 0.4rem;
        border-radius: 16px;
        background: #f0fff4;
        color: #2ecc71;
    }
    
    .payment-stat-change.negative {
        background: #fff5f5;
        color: #e74c3c;
    }
    
    /* Payment Table Styles */
    .payment-info h6 {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.2rem;
        font-size: 0.95rem;
    }
    
    .payment-info small {
        color: #718096;
        font-size: 0.75rem;
    }
    
    .tenant-avatar {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .property-badge {
        background: #e3f2fd;
        color: #1976d2;
        padding: 0.2rem 0.6rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
        display: inline-block;
    }
    
    .room-badge {
        background: #f3e8ff;
        color: #9c27b0;
        padding: 0.2rem 0.6rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
    }
    
    .badge-payment {
        padding: 0.25rem 0.8rem;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: capitalize;
        white-space: nowrap;
    }
    
    .badge-payment.paid {
        background: #e6f7e6;
        color: #27ae60;
    }
    
    .badge-payment.pending {
        background: #fff3e0;
        color: #f39c12;
    }
    
    .badge-payment.overdue {
        background: #fee9e9;
        color: #e74c3c;
    }
    
    .badge-payment.partial {
        background: #e3f2fd;
        color: #3498db;
    }
    
    .badge-payment.refunded {
        background: #2d3748;
        color: white;
    }
    
    .action-buttons .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
        margin: 0 2px;
    }
    
    /* Filter Section */
    .filter-section {
        background: white;
        border-radius: 16px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(0,0,0,0.03);
    }
    
    .filter-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #718096;
        margin-bottom: 0.3rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    /* Quick Stats */
    .quick-stats-row {
        background: white;
        border-radius: 16px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(0,0,0,0.03);
    }
    
    .quick-stat-item {
        text-align: center;
        border-right: 1px solid #edf2f7;
    }
    
    .quick-stat-item:last-child {
        border-right: none;
    }
    
    .quick-stat-number {
        font-size: 1.4rem;
        font-weight: 700;
        color: #2d3748;
        line-height: 1.2;
    }
    
    .quick-stat-label {
        font-size: 0.75rem;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    /* Summary Cards */
    .summary-card {
        background: white;
        border-radius: 16px;
        padding: 1rem;
        border: 1px solid rgba(0,0,0,0.03);
        height: 100%;
    }
    
    .summary-title {
        font-size: 0.8rem;
        color: #718096;
        margin-bottom: 0.5rem;
    }
    
    .summary-amount {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2d3748;
    }
    
    .summary-sub {
        font-size: 0.7rem;
        color: #718096;
        margin-top: 0.25rem;
    }
    
    .summary-change {
        font-size: 0.7rem;
        padding: 0.15rem 0.3rem;
        border-radius: 12px;
        background: #f0fff4;
        color: #27ae60;
    }
    
    .summary-change.negative {
        background: #fff5f5;
        color: #e74c3c;
    }
    
    /* Payment Method Icons */
    .method-icon {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }
    
    .method-icon.cash {
        background: #e6f7e6;
        color: #27ae60;
    }
    
    .method-icon.bank {
        background: #e3f2fd;
        color: #1976d2;
    }
    
    .method-icon.gcash {
        background: #e3f2fd;
        color: #0066b3;
    }
    
    .method-icon.paymaya {
        background: #f3e8ff;
        color: #9c27b0;
    }
    
    /* Receipt Modal */
    .receipt-container {
        background: #f8fafc;
        border-radius: 16px;
        padding: 2rem;
        max-width: 500px;
        margin: 0 auto;
    }
    
    .receipt-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .receipt-logo {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.8rem;
        margin: 0 auto 1rem;
    }
    
    .receipt-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 0.25rem;
    }
    
    .receipt-id {
        color: #718096;
        font-size: 0.9rem;
    }
    
    .receipt-details {
        margin-bottom: 2rem;
    }
    
    .receipt-row {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px dashed #edf2f7;
    }
    
    .receipt-label {
        color: #718096;
        font-size: 0.9rem;
    }
    
    .receipt-value {
        font-weight: 600;
        color: #2d3748;
    }
    
    .receipt-total {
        background: white;
        border-radius: 12px;
        padding: 1rem;
        margin-top: 1rem;
    }
    
    .receipt-footer {
        text-align: center;
        margin-top: 2rem;
        padding-top: 1rem;
        border-top: 1px solid #edf2f7;
        color: #718096;
        font-size: 0.8rem;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .payment-stat-value {
            font-size: 1.3rem;
        }
        
        .payment-stat-icon {
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
        }
        
        .filter-section {
            padding: 1rem;
        }
        
        .quick-stat-item {
            border-right: none;
            border-bottom: 1px solid #edf2f7;
            padding: 0.5rem 0;
        }
        
        .quick-stat-item:last-child {
            border-bottom: none;
        }
        
        .summary-amount {
            font-size: 1.2rem;
        }
        
        .receipt-container {
            padding: 1rem;
        }
    }
    
    /* Modal Styles */
    .payment-form-section {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .payment-form-section h6 {
        font-size: 0.9rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .payment-form-section h6 i {
        color: #667eea;
    }
</style>
@endsection

@section('content')
<div class="fade-in">
    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="payment-stat-card">
                <div class="d-flex align-items-center">
                    <div class="payment-stat-icon primary me-3">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="payment-stat-value">₱1,245,000</div>
                        <div class="payment-stat-label">Total Collected</div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">This month</span>
                        <span class="payment-stat-change">
                            <i class="bi bi-arrow-up"></i> +12.5%
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="payment-stat-card">
                <div class="d-flex align-items-center">
                    <div class="payment-stat-icon success me-3">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="payment-stat-value">284</div>
                        <div class="payment-stat-label">Successful Payments</div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">This month</span>
                        <span class="payment-stat-change">
                            <i class="bi bi-arrow-up"></i> +23
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="payment-stat-card">
                <div class="d-flex align-items-center">
                    <div class="payment-stat-icon warning me-3">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="payment-stat-value">₱187,500</div>
                        <div class="payment-stat-label">Pending Payments</div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">42 pending</span>
                        <span class="payment-stat-change negative">
                            <i class="bi bi-arrow-up"></i> +8
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="payment-stat-card">
                <div class="d-flex align-items-center">
                    <div class="payment-stat-icon info me-3">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="payment-stat-value">₱89,200</div>
                        <div class="payment-stat-label">Overdue</div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">18 accounts</span>
                        <span class="payment-stat-change negative">
                            <i class="bi bi-arrow-up"></i> +5
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Row -->
    <div class="quick-stats-row">
        <div class="row g-0">
            <div class="col-2">
                <div class="quick-stat-item">
                    <div class="quick-stat-number">156</div>
                    <div class="quick-stat-label">Cash</div>
                </div>
            </div>
            <div class="col-2">
                <div class="quick-stat-item">
                    <div class="quick-stat-number">89</div>
                    <div class="quick-stat-label">Bank Transfer</div>
                </div>
            </div>
            <div class="col-2">
                <div class="quick-stat-item">
                    <div class="quick-stat-number">34</div>
                    <div class="quick-stat-label">GCash</div>
                </div>
            </div>
            <div class="col-2">
                <div class="quick-stat-item">
                    <div class="quick-stat-number">12</div>
                    <div class="quick-stat-label">PayMaya</div>
                </div>
            </div>
            <div class="col-2">
                <div class="quick-stat-item">
                    <div class="quick-stat-number">5</div>
                    <div class="quick-stat-label">Check</div>
                </div>
            </div>
            <div class="col-2">
                <div class="quick-stat-item">
                    <div class="quick-stat-number">8</div>
                    <div class="quick-stat-label">Refunds</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="summary-card">
                <div class="summary-title">Today's Collection</div>
                <div class="summary-amount">₱45,600</div>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <span class="summary-sub">12 transactions</span>
                    <span class="summary-change"><i class="bi bi-arrow-up"></i> +8%</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card">
                <div class="summary-title">This Week</div>
                <div class="summary-amount">₱287,500</div>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <span class="summary-sub">84 transactions</span>
                    <span class="summary-change"><i class="bi bi-arrow-up"></i> +15%</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card">
                <div class="summary-title">This Month</div>
                <div class="summary-amount">₱1,245,000</div>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <span class="summary-sub">284 transactions</span>
                    <span class="summary-change"><i class="bi bi-arrow-up"></i> +12.5%</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card">
                <div class="summary-title">Collection Rate</div>
                <div class="summary-amount">87%</div>
                <div class="progress mt-2" style="height: 6px;">
                    <div class="progress-bar bg-success" style="width: 87%"></div>
                </div>
                <div class="d-flex justify-content-between mt-1">
                    <span class="summary-sub">Target: 95%</span>
                    <span class="summary-change negative">-8%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="row g-3">
            <div class="col-lg-2 col-md-4">
                <div class="filter-label">Search</div>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-0 bg-light" id="searchPayment" placeholder="Tenant, ref #...">
                </div>
            </div>
            <div class="col-lg-2 col-md-4">
                <div class="filter-label">Property</div>
                <select class="form-select bg-light border-0" id="filterProperty">
                    <option value="">All Properties</option>
                    <option value="sunset">Sunset Residences</option>
                    <option value="green">Green Heights</option>
                    <option value="bayview">Bayview Tower</option>
                    <option value="city">City Lights</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-4">
                <div class="filter-label">Payment Status</div>
                <select class="form-select bg-light border-0" id="filterStatus">
                    <option value="">All Status</option>
                    <option value="paid">Paid</option>
                    <option value="pending">Pending</option>
                    <option value="overdue">Overdue</option>
                    <option value="partial">Partial</option>
                    <option value="refunded">Refunded</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-4">
                <div class="filter-label">Payment Method</div>
                <select class="form-select bg-light border-0" id="filterMethod">
                    <option value="">All Methods</option>
                    <option value="cash">Cash</option>
                    <option value="bank">Bank Transfer</option>
                    <option value="gcash">GCash</option>
                    <option value="paymaya">PayMaya</option>
                    <option value="check">Check</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-4">
                <div class="filter-label">Date Range</div>
                <input type="text" class="form-control bg-light border-0" id="dateRange" placeholder="Select range">
            </div>
            <div class="col-lg-2 col-md-4">
                <div class="filter-label">Amount Range</div>
                <select class="form-select bg-light border-0" id="filterAmount">
                    <option value="">All Amounts</option>
                    <option value="0-1000">Below ₱1,000</option>
                    <option value="1000-3000">₱1,000 - ₱3,000</option>
                    <option value="3000-5000">₱3,000 - ₱5,000</option>
                    <option value="5000-10000">₱5,000 - ₱10,000</option>
                    <option value="10000+">Above ₱10,000</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="table-container">
        <div class="table-header">
            <h5 class="table-title">Payment Transactions</h5>
            <div class="d-flex align-items-center gap-3">
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-gear"></i> Bulk Actions
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="bulkAction('send-receipts')"><i class="bi bi-envelope me-2"></i>Send Receipts</a></li>
                        <li><a class="dropdown-item" href="#" onclick="bulkAction('send-reminders')"><i class="bi bi-bell me-2"></i>Send Reminders</a></li>
                        <li><a class="dropdown-item" href="#" onclick="bulkAction('export-selected')"><i class="bi bi-download me-2"></i>Export Selected</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#" onclick="bulkAction('mark-overdue')"><i class="bi bi-exclamation-triangle me-2"></i>Mark as Overdue</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover" id="paymentsTable">
                <thead>
                    <tr>
                        <th width="40">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAll">
                            </div>
                        </th>
                        <th>Transaction ID</th>
                        <th>Date & Time</th>
                        <th>Tenant</th>
                        <th>Property & Room</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Reference</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input row-checkbox" type="checkbox">
                            </div>
                        </td>
                        <td>
                            <div class="payment-info">
                                <h6>#PAY-2024-001</h6>
                                <small class="text-muted">Receipt: RCP-001</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div>Mar 1, 2026</div>
                                <small class="text-muted">10:30 AM</small>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="tenant-avatar">JD</div>
                                <div>
                                    <div class="fw-semibold">John Doe</div>
                                    <small class="text-muted">ID: OCP-2024-001</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <span class="property-badge">Sunset Residences</span>
                                <span class="room-badge ms-1">Rm 204</span>
                            </div>
                        </td>
                        <td>
                            <div>March 2026 Rent</div>
                            <small class="text-muted">Monthly payment</small>
                        </td>
                        <td>
                            <div class="fw-bold">₱4,500</div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="method-icon cash">
                                    <i class="bi bi-cash"></i>
                                </div>
                                <span>Cash</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge-payment paid">Paid</span>
                        </td>
                        <td>
                            <small class="text-muted">Cash payment</small>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-outline-primary" title="View Receipt" onclick="viewReceipt(1)">
                                    <i class="bi bi-receipt"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-secondary" title="Edit" onclick="editPayment(1)">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-info" title="Send Receipt" onclick="sendReceipt(1)">
                                    <i class="bi bi-envelope"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-success" title="Print" onclick="printReceipt(1)">
                                    <i class="bi bi-printer"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-warning" title="Void" onclick="voidPayment(1)">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input row-checkbox" type="checkbox">
                            </div>
                        </td>
                        <td>
                            <div class="payment-info">
                                <h6>#PAY-2024-002</h6>
                                <small>Receipt: RCP-002</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div>Feb 28, 2026</div>
                                <small class="text-muted">2:15 PM</small>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="tenant-avatar">MS</div>
                                <div>
                                    <div class="fw-semibold">Maria Santos</div>
                                    <small class="text-muted">ID: OCP-2024-002</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <span class="property-badge">Green Heights</span>
                                <span class="room-badge ms-1">Rm 305</span>
                            </div>
                        </td>
                        <td>
                            <div>February 2026 Rent</div>
                            <small class="text-muted">Monthly payment</small>
                        </td>
                        <td>
                            <div class="fw-bold">₱5,000</div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="method-icon bank">
                                    <i class="bi bi-bank"></i>
                                </div>
                                <span>Bank Transfer</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge-payment pending">Pending</span>
                        </td>
                        <td>
                            <small class="text-muted">Ref: BPI-02282026-001</small>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-receipt"></i></button>
                                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-sm btn-outline-info"><i class="bi bi-envelope"></i></button>
                                <button class="btn btn-sm btn-outline-success"><i class="bi bi-printer"></i></button>
                                <button class="btn btn-sm btn-outline-warning"><i class="bi bi-x-circle"></i></button>
                            </div>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input row-checkbox" type="checkbox">
                            </div>
                        </td>
                        <td>
                            <div class="payment-info">
                                <h6>#PAY-2024-003</h6>
                                <small>Receipt: RCP-003</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div>Feb 25, 2026</div>
                                <small class="text-muted">9:45 AM</small>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="tenant-avatar">AR</div>
                                <div>
                                    <div class="fw-semibold">Alex Reyes</div>
                                    <small class="text-muted">ID: OCP-2024-003</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <span class="property-badge">Bayview Tower</span>
                                <span class="room-badge ms-1">Rm 101</span>
                            </div>
                        </td>
                        <td>
                            <div>February 2026 Rent</div>
                            <small class="text-muted">Monthly payment</small>
                        </td>
                        <td>
                            <div class="fw-bold">₱8,500</div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="method-icon gcash">
                                    <i class="bi bi-phone"></i>
                                </div>
                                <span>GCash</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge-payment paid">Paid</span>
                        </td>
                        <td>
                            <small class="text-muted">Ref: GCASH-250226-001</small>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-receipt"></i></button>
                                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-sm btn-outline-info"><i class="bi bi-envelope"></i></button>
                                <button class="btn btn-sm btn-outline-success"><i class="bi bi-printer"></i></button>
                                <button class="btn btn-sm btn-outline-warning"><i class="bi bi-x-circle"></i></button>
                            </div>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input row-checkbox" type="checkbox">
                            </div>
                        </td>
                        <td>
                            <div class="payment-info">
                                <h6>#PAY-2024-004</h6>
                                <small>Receipt: RCP-004</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div>Feb 20, 2026</div>
                                <small class="text-muted">11:20 AM</small>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="tenant-avatar">JL</div>
                                <div>
                                    <div class="fw-semibold">Jane Lim</div>
                                    <small class="text-muted">ID: OCP-2024-004</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <span class="property-badge">City Lights</span>
                                <span class="room-badge ms-1">Rm 412</span>
                            </div>
                        </td>
                        <td>
                            <div>February 2026 Rent</div>
                            <small class="text-muted">Partial payment</small>
                        </td>
                        <td>
                            <div class="fw-bold">₱2,000</div>
                            <small class="text-muted">of ₱4,000</small>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="method-icon paymaya">
                                    <i class="bi bi-credit-card"></i>
                                </div>
                                <span>PayMaya</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge-payment partial">Partial</span>
                        </td>
                        <td>
                            <small class="text-muted">Ref: MAYA-2026-002</small>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-receipt"></i></button>
                                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-sm btn-outline-info"><i class="bi bi-envelope"></i></button>
                                <button class="btn btn-sm btn-outline-success"><i class="bi bi-printer"></i></button>
                                <button class="btn btn-sm btn-outline-warning"><i class="bi bi-x-circle"></i></button>
                            </div>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input row-checkbox" type="checkbox">
                            </div>
                        </td>
                        <td>
                            <div class="payment-info">
                                <h6>#PAY-2024-005</h6>
                                <small>Receipt: RCP-005</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div>Feb 15, 2026</div>
                                <small class="text-muted">3:30 PM</small>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="tenant-avatar">CV</div>
                                <div>
                                    <div class="fw-semibold">Carlos Villanueva</div>
                                    <small class="text-muted">ID: OCP-2024-005</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <span class="property-badge">Sunset Residences</span>
                                <span class="room-badge ms-1">Rm 310</span>
                            </div>
                        </td>
                        <td>
                            <div>February 2026 Rent</div>
                            <small class="text-muted">Monthly payment</small>
                        </td>
                        <td>
                            <div class="fw-bold">₱3,800</div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="method-icon check">
                                    <i class="bi bi-file-text"></i>
                                </div>
                                <span>Check</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge-payment overdue">Overdue</span>
                        </td>
                        <td>
                            <small class="text-muted">Check #123456</small>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-receipt"></i></button>
                                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-sm btn-outline-info"><i class="bi bi-envelope"></i></button>
                                <button class="btn btn-sm btn-outline-success"><i class="bi bi-printer"></i></button>
                                <button class="btn btn-sm btn-outline-warning"><i class="bi bi-x-circle"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="small text-muted">
                Showing 5 of <span id="totalPayments">312</span> transactions
            </div>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled"><a class="page-link" href="#">Prev</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">4</a></li>
                    <li class="page-item"><a class="page-link" href="#">5</a></li>
                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Record Payment Modal -->
<div class="modal fade" id="recordPaymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Record New Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="recordPaymentForm">
                    <!-- Tenant Selection -->
                    <div class="payment-form-section">
                        <h6><i class="bi bi-person"></i> Tenant Information</h6>
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label">Select Tenant</label>
                                <select class="form-select" name="tenant_id" required id="tenantSelect">
                                    <option value="">Choose tenant...</option>
                                    <option value="1">John Doe - Room 204 (Sunset Residences)</option>
                                    <option value="2">Maria Santos - Room 305 (Green Heights)</option>
                                    <option value="3">Alex Reyes - Room 101 (Bayview Tower)</option>
                                    <option value="4">Jane Lim - Room 412 (City Lights)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Outstanding Balance</label>
                                <input type="text" class="form-control bg-light" id="outstandingBalance" value="₱4,500" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment Details -->
                    <div class="payment-form-section">
                        <h6><i class="bi bi-cash"></i> Payment Details</h6>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Payment For</label>
                                <select class="form-select" name="payment_for" required>
                                    <option value="rent">Monthly Rent</option>
                                    <option value="deposit">Security Deposit</option>
                                    <option value="advance">Advance Payment</option>
                                    <option value="utilities">Utilities</option>
                                    <option value="penalty">Penalty/Late Fee</option>
                                    <option value="others">Others</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Month/Period</label>
                                <select class="form-select" name="period_month">
                                    <option value="2026-03">March 2026</option>
                                    <option value="2026-02">February 2026</option>
                                    <option value="2026-01">January 2026</option>
                                    <option value="2025-12">December 2025</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Amount (₱)</label>
                                <input type="number" class="form-control" name="amount" required id="paymentAmount">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Payment Date</label>
                                <input type="date" class="form-control" name="payment_date" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment Method -->
                    <div class="payment-form-section">
                        <h6><i class="bi bi-credit-card"></i> Payment Method</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Method</label>
                                <select class="form-select" name="payment_method" required id="paymentMethod">
                                    <option value="cash">Cash</option>
                                    <option value="bank">Bank Transfer</option>
                                    <option value="gcash">GCash</option>
                                    <option value="paymaya">PayMaya</option>
                                    <option value="check">Check</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Reference Number</label>
                                <input type="text" class="form-control" name="reference_no" placeholder="Optional">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Bank/Provider</label>
                                <input type="text" class="form-control" name="bank_provider" id="bankProvider" placeholder="e.g., BPI, GCash">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Additional Information -->
                    <div class="payment-form-section">
                        <h6><i class="bi bi-file-text"></i> Additional Information</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">OR Number</label>
                                <input type="text" class="form-control" name="or_number" placeholder="Official Receipt #">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Upload Receipt</label>
                                <input type="file" class="form-control" name="receipt_file">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Notes</label>
                                <textarea class="form-control" name="notes" rows="2" placeholder="Additional notes..."></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="recordPayment()">Record Payment</button>
            </div>
        </div>
    </div>
</div>

<!-- View Receipt Modal -->
<div class="modal fade" id="viewReceiptModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payment Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="receipt-container">
                    <div class="receipt-header">
                        <div class="receipt-logo">
                            <i class="bi bi-house-door"></i>
                        </div>
                        <div class="receipt-title">StayEase Boarding House</div>
                        <div class="receipt-id">Official Receipt #RCP-2026-001</div>
                    </div>
                    
                    <div class="receipt-details">
                        <div class="receipt-row">
                            <span class="receipt-label">Date:</span>
                            <span class="receipt-value">March 1, 2026 10:30 AM</span>
                        </div>
                        <div class="receipt-row">
                            <span class="receipt-label">Transaction ID:</span>
                            <span class="receipt-value">#PAY-2024-001</span>
                        </div>
                        <div class="receipt-row">
                            <span class="receipt-label">Tenant:</span>
                            <span class="receipt-value">John Doe</span>
                        </div>
                        <div class="receipt-row">
                            <span class="receipt-label">Property:</span>
                            <span class="receipt-value">Sunset Residences - Room 204</span>
                        </div>
                        <div class="receipt-row">
                            <span class="receipt-label">Payment For:</span>
                            <span class="receipt-value">March 2026 Rent</span>
                        </div>
                        <div class="receipt-row">
                            <span class="receipt-label">Payment Method:</span>
                            <span class="receipt-value">Cash</span>
                        </div>
                    </div>
                    
                    <div class="receipt-total">
                        <div class="receipt-row">
                            <span class="receipt-label fw-bold">Amount Paid:</span>
                            <span class="receipt-value fw-bold text-success">₱4,500.00</span>
                        </div>
                    </div>
                    
                    <div class="receipt-footer">
                        <p class="mb-1">Thank you for your payment!</p>
                        <p class="mb-0 small">This receipt is system generated and valid without signature.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="printReceipt(1)">
                    <i class="bi bi-printer me-2"></i>Print Receipt
                </button>
                <button type="button" class="btn btn-success" onclick="sendReceipt(1)">
                    <i class="bi bi-envelope me-2"></i>Email Receipt
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        const table = $('#paymentsTable').DataTable({
            pageLength: 10,
            responsive: true,
            searching: true,
            ordering: true,
            info: true,
            lengthChange: false,
            columnDefs: [
                { orderable: false, targets: [0, 10] },
                { searchable: false, targets: [0] }
            ]
        });

        // Initialize date range picker
        flatpickr("#dateRange", {
            mode: "range",
            dateFormat: "Y-m-d",
            allowInput: true
        });

        // Select all checkbox
        $('#selectAll').on('change', function() {
            $('.row-checkbox').prop('checked', $(this).prop('checked'));
        });

        // Search functionality
        $('#searchPayment').on('keyup', function() {
            table.search($(this).val()).draw();
        });

        // Filter by property
        $('#filterProperty').on('change', function() {
            const property = $(this).val();
            if (property) {
                table.column(4).search(property, true, false).draw();
            } else {
                table.column(4).search('').draw();
            }
        });

        // Filter by status
        $('#filterStatus').on('change', function() {
            const status = $(this).val();
            if (status) {
                table.column(8).search('^' + status + '$', true, false).draw();
            } else {
                table.column(8).search('').draw();
            }
        });

        // Filter by method
        $('#filterMethod').on('change', function() {
            const method = $(this).val();
            if (method) {
                table.column(7).search(method, true, false).draw();
            } else {
                table.column(7).search('').draw();
            }
        });

        // Filter by amount
        $('#filterAmount').on('change', function() {
            const range = $(this).val();
            if (range) {
                const [min, max] = range.split('-');
                $.fn.dataTable.ext.search.push(
                    function(settings, data, dataIndex) {
                        const amount = parseFloat(data[6].replace(/[₱,]/g, ''));
                        if (max) {
                            return amount >= min && amount <= max;
                        } else if (min === '10000+') {
                            return amount >= 10000;
                        }
                        return true;
                    }
                );
                table.draw();
                $.fn.dataTable.ext.search.pop();
            } else {
                table.draw();
            }
        });

        // Update total payments count
        $('#totalPayments').text(table.page.info().recordsTotal);

        // Payment method change - show/hide bank fields
        $('#paymentMethod').on('change', function() {
            const method = $(this).val();
            if (method === 'cash') {
                $('#bankProvider').closest('.col-md-4').hide();
            } else {
                $('#bankProvider').closest('.col-md-4').show();
            }
        });

        // Tenant selection - load outstanding balance
        $('#tenantSelect').on('change', function() {
            const tenantId = $(this).val();
            if (tenantId) {
                // Simulate loading balance
                const balances = {
                    '1': '₱4,500',
                    '2': '₱5,000',
                    '3': '₱8,500',
                    '4': '₱2,000'
                };
                $('#outstandingBalance').val(balances[tenantId] || '₱0');
            }
        });
    });

    // Export payments function
    function exportPayments(type) {
        showToast('info', `Exporting as ${type.toUpperCase()}...`);
        setTimeout(() => {
            showToast('success', 'Export completed successfully');
        }, 2000);
    }

    // Generate financial report
    function generateFinancialReport() {
        Swal.fire({
            title: 'Generate Financial Report',
            html: `
                <select class="form-select mb-3" id="reportType">
                    <option value="summary">Summary Report</option>
                    <option value="detailed">Detailed Transaction Report</option>
                    <option value="collection">Collection Report</option>
                    <option value="aging">Aging Report</option>
                </select>
                <select class="form-select mb-3" id="reportPeriod">
                    <option value="today">Today</option>
                    <option value="this-week">This Week</option>
                    <option value="this-month">This Month</option>
                    <option value="last-month">Last Month</option>
                    <option value="this-quarter">This Quarter</option>
                    <option value="this-year">This Year</option>
                    <option value="custom">Custom Range</option>
                </select>
                <div class="d-flex gap-2" id="customRange" style="display: none !important;">
                    <input type="date" class="form-control" id="reportFrom" placeholder="From">
                    <input type="date" class="form-control" id="reportTo" placeholder="To">
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Generate',
            cancelButtonText: 'Cancel',
            didOpen: () => {
                $('#reportPeriod').on('change', function() {
                    if ($(this).val() === 'custom') {
                        $('#customRange').show();
                    } else {
                        $('#customRange').hide();
                    }
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();
                setTimeout(() => {
                    hideLoading();
                    Swal.fire('Success!', 'Report generated successfully', 'success');
                }, 2000);
            }
        });
    }

    // View receipt function
    function viewReceipt(id) {
        $('#viewReceiptModal').modal('show');
    }

    // Edit payment function
    function editPayment(id) {
        showToast('info', 'Loading payment details...');
        // Open edit modal
    }

    // Send receipt function
    function sendReceipt(id) {
        Swal.fire({
            title: 'Send Receipt',
            input: 'email',
            inputLabel: 'Email Address',
            inputValue: 'tenant@email.com',
            showCancelButton: true,
            confirmButtonText: 'Send',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                showToast('success', 'Receipt sent successfully');
            }
        });
    }

    // Print receipt function
    function printReceipt(id) {
        window.print();
    }

    // Void payment function
    function voidPayment(id) {
        Swal.fire({
            title: 'Void Payment',
            text: 'Are you sure you want to void this payment? This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, void payment',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();
                setTimeout(() => {
                    hideLoading();
                    Swal.fire(
                        'Voided!',
                        'Payment has been voided.',
                        'success'
                    );
                }, 1500);
            }
        });
    }

    // Record payment function
    function recordPayment() {
        // Validate form
        const form = document.getElementById('recordPaymentForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        showLoading();
        
        // Simulate API call
        setTimeout(() => {
            hideLoading();
            $('#recordPaymentModal').modal('hide');
            form.reset();
            
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Payment recorded successfully.',
                timer: 2000,
                showConfirmButton: false
            });
        }, 1500);
    }

    // Bulk action function
    function bulkAction(action) {
        const selected = $('.row-checkbox:checked').length;
        if (selected === 0) {
            showToast('warning', 'Please select at least one transaction');
            return;
        }
        
        let title, message;
        switch(action) {
            case 'send-receipts':
                title = 'Send Receipts';
                message = `Send receipts to ${selected} selected transaction(s)?`;
                break;
            case 'send-reminders':
                title = 'Send Payment Reminders';
                message = `Send reminders for ${selected} selected transaction(s)?`;
                break;
            case 'export-selected':
                title = 'Export Selected';
                message = `Export ${selected} selected transaction(s)?`;
                break;
            case 'mark-overdue':
                title = 'Mark as Overdue';
                message = `Mark ${selected} selected transaction(s) as overdue?`;
                break;
        }
        
        Swal.fire({
            title: title,
            text: message,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#667eea',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, proceed'
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();
                setTimeout(() => {
                    hideLoading();
                    showToast('success', `${title} completed successfully`);
                }, 2000);
            }
        });
    }

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
</script>
@endsection