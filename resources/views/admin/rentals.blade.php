@extends('layouts.admin')

@section('title', 'Rentals Management - StayEase Admin')

@section('page_header', 'Rentals Management')

@section('page_description', 'Manage all active leases and rental agreements')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Rentals</li>
@endsection

@section('header_actions')
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRentalModal">
        <i class="bi bi-plus-circle me-2"></i>New Rental Agreement
    </button>
    <div class="btn-group ms-2">
        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
            <i class="bi bi-download me-2"></i>Export
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="#" onclick="exportRentals('pdf')"><i class="bi bi-file-pdf me-2"></i>PDF</a></li>
            <li><a class="dropdown-item" href="#" onclick="exportRentals('excel')"><i class="bi bi-file-excel me-2"></i>Excel</a></li>
            <li><a class="dropdown-item" href="#" onclick="exportRentals('csv')"><i class="bi bi-file-text me-2"></i>CSV</a></li>
        </ul>
    </div>
    <button class="btn btn-outline-primary ms-2" onclick="generateReport()">
        <i class="bi bi-file-bar-graph me-2"></i>Generate Report
    </button>
@endsection

@section('styles')
<style>
    /* Rental Stats Cards */
    .rental-stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.03);
        border: 1px solid rgba(0,0,0,0.03);
        height: 100%;
        transition: all 0.3s;
    }
    
    .rental-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.1);
        border-color: rgba(102, 126, 234, 0.15);
    }
    
    .rental-stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }
    
    .rental-stat-icon.primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .rental-stat-icon.success {
        background: linear-gradient(135deg, #34b1aa 0%, #2c9a94 100%);
    }
    
    .rental-stat-icon.warning {
        background: linear-gradient(135deg, #f6b23e 0%, #f4a51e 100%);
    }
    
    .rental-stat-icon.info {
        background: linear-gradient(135deg, #3b7cff 0%, #2b6ef0 100%);
    }
    
    .rental-stat-value {
        font-size: 1.6rem;
        font-weight: 700;
        color: #2d3748;
        line-height: 1.2;
    }
    
    .rental-stat-label {
        color: #718096;
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    .rental-stat-change {
        font-size: 0.75rem;
        padding: 0.2rem 0.4rem;
        border-radius: 16px;
        background: #f0fff4;
        color: #2ecc71;
    }
    
    .rental-stat-change.negative {
        background: #fff5f5;
        color: #e74c3c;
    }
    
    /* Rental Table Styles */
    .rental-info h6 {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.2rem;
        font-size: 0.95rem;
    }
    
    .rental-info small {
        color: #718096;
        font-size: 0.75rem;
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
    
    .badge-status {
        padding: 0.25rem 0.6rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: capitalize;
        white-space: nowrap;
    }
    
    .badge-status.active {
        background: #e6f7e6;
        color: #27ae60;
    }
    
    .badge-status.expiring {
        background: #fff3e0;
        color: #f39c12;
    }
    
    .badge-status.expired {
        background: #fee9e9;
        color: #e74c3c;
    }
    
    .badge-status.terminated {
        background: #2d3748;
        color: white;
    }
    
    .badge-payment {
        padding: 0.25rem 0.6rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
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
    
    /* Progress Bars */
    .lease-progress {
        height: 6px;
        border-radius: 3px;
        background: #edf2f7;
        margin-top: 0.3rem;
        position: relative;
    }
    
    .lease-progress-bar {
        height: 100%;
        border-radius: 3px;
        background: linear-gradient(90deg, #667eea, #764ba2);
    }
    
    .lease-progress-bar.warning {
        background: linear-gradient(90deg, #f6b23e, #f4a51e);
    }
    
    .lease-progress-bar.danger {
        background: linear-gradient(90deg, #ef476f, #e74c3c);
    }
    
    /* Timeline */
    .rental-timeline {
        position: relative;
        padding-left: 2rem;
    }
    
    .rental-timeline::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #edf2f7;
    }
    
    .rental-timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }
    
    .rental-timeline-item::before {
        content: '';
        position: absolute;
        left: -2rem;
        top: 0.25rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #667eea;
        border: 2px solid white;
        z-index: 1;
    }
    
    .rental-timeline-item.completed::before {
        background: #27ae60;
    }
    
    .rental-timeline-item.current::before {
        background: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
    }
    
    .rental-timeline-item.upcoming::before {
        background: #f39c12;
    }
    
    .rental-timeline-date {
        font-size: 0.7rem;
        color: #718096;
        margin-bottom: 0.2rem;
    }
    
    .rental-timeline-title {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.2rem;
        font-size: 0.9rem;
    }
    
    .rental-timeline-text {
        font-size: 0.8rem;
        color: #718096;
    }
    
    /* Contract Card */
    .contract-card {
        background: white;
        border: 1px solid #edf2f7;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .contract-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
    }
    
    .contract-id {
        font-weight: 700;
        color: #667eea;
        font-size: 0.9rem;
    }
    
    .contract-terms {
        display: flex;
        gap: 1rem;
        margin-bottom: 0.75rem;
    }
    
    .term-item {
        text-align: center;
        flex: 1;
        padding: 0.5rem;
        background: #f8fafc;
        border-radius: 8px;
    }
    
    .term-label {
        font-size: 0.65rem;
        color: #718096;
        text-transform: uppercase;
    }
    
    .term-value {
        font-weight: 600;
        color: #2d3748;
        font-size: 0.9rem;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .rental-stat-value {
            font-size: 1.3rem;
        }
        
        .rental-stat-icon {
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
        
        .contract-terms {
            flex-direction: column;
            gap: 0.5rem;
        }
    }
    
    /* Modal Styles */
    .rental-form-section {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .rental-form-section h6 {
        font-size: 0.9rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .rental-form-section h6 i {
        color: #667eea;
    }
    
    /* Payment Schedule */
    .payment-schedule {
        max-height: 300px;
        overflow-y: auto;
    }
    
    .payment-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem;
        border-bottom: 1px solid #edf2f7;
    }
    
    .payment-item:last-child {
        border-bottom: none;
    }
    
    .payment-month {
        font-weight: 500;
        color: #2d3748;
    }
    
    .payment-amount {
        font-weight: 600;
        color: #27ae60;
    }
    
    .payment-status {
        font-size: 0.7rem;
    }
</style>
@endsection

@section('content')
<div class="fade-in">
    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="rental-stat-card">
                <div class="d-flex align-items-center">
                    <div class="rental-stat-icon primary me-3">
                        <i class="bi bi-file-text"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="rental-stat-value">284</div>
                        <div class="rental-stat-label">Active Rentals</div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">81% occupancy rate</span>
                        <span class="rental-stat-change">
                            <i class="bi bi-arrow-up"></i> +12 this month
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="rental-stat-card">
                <div class="d-flex align-items-center">
                    <div class="rental-stat-icon success me-3">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="rental-stat-value">23</div>
                        <div class="rental-stat-label">Expiring Soon</div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Within 30 days</span>
                        <span class="rental-stat-change negative">
                            <i class="bi bi-arrow-up"></i> +5
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="rental-stat-card">
                <div class="d-flex align-items-center">
                    <div class="rental-stat-icon warning me-3">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="rental-stat-value">8</div>
                        <div class="rental-stat-label">Overdue Rentals</div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Payment overdue</span>
                        <span class="rental-stat-change negative">
                            <i class="bi bi-arrow-up"></i> +3
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="rental-stat-card">
                <div class="d-flex align-items-center">
                    <div class="rental-stat-icon info me-3">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="rental-stat-value">₱1.8M</div>
                        <div class="rental-stat-label">Total Contract Value</div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Active contracts</span>
                        <span class="rental-stat-change">
                            <i class="bi bi-arrow-up"></i> +15%
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Row -->
    <div class="quick-stats-row">
        <div class="row g-0">
            <div class="col-3">
                <div class="quick-stat-item">
                    <div class="quick-stat-number">156</div>
                    <div class="quick-stat-label">Monthly</div>
                </div>
            </div>
            <div class="col-3">
                <div class="quick-stat-item">
                    <div class="quick-stat-number">89</div>
                    <div class="quick-stat-label">Quarterly</div>
                </div>
            </div>
            <div class="col-3">
                <div class="quick-stat-item">
                    <div class="quick-stat-number">39</div>
                    <div class="quick-stat-label">Semi-Annual</div>
                </div>
            </div>
            <div class="col-3">
                <div class="quick-stat-item">
                    <div class="quick-stat-number">28</div>
                    <div class="quick-stat-label">Annual</div>
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
                    <input type="text" class="form-control border-0 bg-light" id="searchRental" placeholder="Tenant, room...">
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
                <div class="filter-label">Status</div>
                <select class="form-select bg-light border-0" id="filterStatus">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="expiring">Expiring Soon</option>
                    <option value="expired">Expired</option>
                    <option value="terminated">Terminated</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-4">
                <div class="filter-label">Payment Status</div>
                <select class="form-select bg-light border-0" id="filterPayment">
                    <option value="">All</option>
                    <option value="paid">Paid</option>
                    <option value="pending">Pending</option>
                    <option value="overdue">Overdue</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-4">
                <div class="filter-label">Lease Type</div>
                <select class="form-select bg-light border-0" id="filterLease">
                    <option value="">All Types</option>
                    <option value="monthly">Monthly</option>
                    <option value="quarterly">Quarterly</option>
                    <option value="semi-annual">Semi-Annual</option>
                    <option value="annual">Annual</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-4">
                <div class="filter-label">Expiry Date</div>
                <input type="text" class="form-control bg-light border-0" id="expiryDate" placeholder="Select date range">
            </div>
        </div>
    </div>

    <!-- Rentals Table -->
    <div class="table-container">
        <div class="table-header">
            <h5 class="table-title">Active Rental Agreements</h5>
            <div class="d-flex align-items-center gap-3">
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-gear"></i> Bulk Actions
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="bulkAction('send-reminder')"><i class="bi bi-bell me-2"></i>Send Payment Reminder</a></li>
                        <li><a class="dropdown-item" href="#" onclick="bulkAction('send-renewal')"><i class="bi bi-envelope me-2"></i>Send Renewal Notice</a></li>
                        <li><a class="dropdown-item" href="#" onclick="bulkAction('export-selected')"><i class="bi bi-download me-2"></i>Export Selected</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#" onclick="bulkAction('terminate')"><i class="bi bi-file-x me-2"></i>Terminate Contracts</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover" id="rentalsTable">
                <thead>
                    <tr>
                        <th width="40">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAll">
                            </div>
                        </th>
                        <th>Contract ID</th>
                        <th>Tenant</th>
                        <th>Property & Room</th>
                        <th>Lease Term</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Monthly Rent</th>
                        <th>Payment Status</th>
                        <th>Contract Status</th>
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
                            <div class="rental-info">
                                <h6>#CT-2024-001</h6>
                                <small class="text-muted">Signed: Jan 15, 2026</small>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="occupant-avatar" style="width: 35px; height: 35px; font-size: 0.8rem;">JD</div>
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
                            <div class="text-center">
                                <div class="fw-semibold">6 Months</div>
                                <small class="text-muted">Monthly</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div>Jan 15, 2026</div>
                                <small class="text-muted">2 months ago</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div>Jul 14, 2026</div>
                                <small class="text-muted">4 months left</small>
                            </div>
                            <div class="lease-progress">
                                <div class="lease-progress-bar" style="width: 33%"></div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold">₱4,500</div>
                        </td>
                        <td>
                            <span class="badge-payment paid">Paid</span>
                        </td>
                        <td>
                            <span class="badge-status active">Active</span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-outline-primary" title="View Details" onclick="viewRental(1)">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-secondary" title="Edit Contract" onclick="editRental(1)">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-info" title="Payment Schedule" onclick="viewPayments(1)">
                                    <i class="bi bi-cash"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-success" title="Send Reminder" onclick="sendReminder(1)">
                                    <i class="bi bi-bell"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-warning" title="Renew Contract" onclick="renewContract(1)">
                                    <i class="bi bi-arrow-repeat"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" title="Terminate" onclick="terminateContract(1)">
                                    <i class="bi bi-file-x"></i>
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
                            <div class="rental-info">
                                <h6>#CT-2024-002</h6>
                                <small>Signed: Feb 1, 2026</small>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="occupant-avatar" style="width: 35px; height: 35px; font-size: 0.8rem;">MS</div>
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
                            <div class="text-center">
                                <div class="fw-semibold">3 Months</div>
                                <small class="text-muted">Monthly</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div>Feb 1, 2026</div>
                                <small class="text-muted">1 month ago</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div>Apr 30, 2026</div>
                                <small class="text-muted">2 months left</small>
                            </div>
                            <div class="lease-progress">
                                <div class="lease-progress-bar" style="width: 33%"></div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold">₱5,000</div>
                        </td>
                        <td>
                            <span class="badge-payment pending">Pending</span>
                        </td>
                        <td>
                            <span class="badge-status active">Active</span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-sm btn-outline-info"><i class="bi bi-cash"></i></button>
                                <button class="btn btn-sm btn-outline-success"><i class="bi bi-bell"></i></button>
                                <button class="btn btn-sm btn-outline-warning"><i class="bi bi-arrow-repeat"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-file-x"></i></button>
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
                            <div class="rental-info">
                                <h6>#CT-2024-003</h6>
                                <small>Signed: Dec 10, 2025</small>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="occupant-avatar" style="width: 35px; height: 35px; font-size: 0.8rem;">AR</div>
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
                            <div class="text-center">
                                <div class="fw-semibold">6 Months</div>
                                <small class="text-muted">Monthly</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div>Dec 10, 2025</div>
                                <small class="text-muted">3 months ago</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div>Jun 9, 2026</div>
                                <small class="text-muted expiring">Expiring soon</small>
                            </div>
                            <div class="lease-progress">
                                <div class="lease-progress-bar warning" style="width: 75%"></div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold">₱8,500</div>
                        </td>
                        <td>
                            <span class="badge-payment overdue">Overdue</span>
                        </td>
                        <td>
                            <span class="badge-status expiring">Expiring Soon</span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-sm btn-outline-info"><i class="bi bi-cash"></i></button>
                                <button class="btn btn-sm btn-outline-success"><i class="bi bi-bell"></i></button>
                                <button class="btn btn-sm btn-outline-warning"><i class="bi bi-arrow-repeat"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-file-x"></i></button>
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
                            <div class="rental-info">
                                <h6>#CT-2024-004</h6>
                                <small>Signed: Mar 1, 2026</small>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="occupant-avatar" style="width: 35px; height: 35px; font-size: 0.8rem;">JL</div>
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
                            <div class="text-center">
                                <div class="fw-semibold">1 Year</div>
                                <small class="text-muted">Annual</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div>Mar 1, 2026</div>
                                <small class="text-muted">Just started</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div>Feb 28, 2027</div>
                                <small class="text-muted">11 months left</small>
                            </div>
                            <div class="lease-progress">
                                <div class="lease-progress-bar" style="width: 8%"></div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold">₱4,000</div>
                        </td>
                        <td>
                            <span class="badge-payment paid">Paid</span>
                        </td>
                        <td>
                            <span class="badge-status active">Active</span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-sm btn-outline-info"><i class="bi bi-cash"></i></button>
                                <button class="btn btn-sm btn-outline-success"><i class="bi bi-bell"></i></button>
                                <button class="btn btn-sm btn-outline-warning"><i class="bi bi-arrow-repeat"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-file-x"></i></button>
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
                            <div class="rental-info">
                                <h6>#CT-2024-005</h6>
                                <small>Signed: Jan 5, 2025</small>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="occupant-avatar" style="width: 35px; height: 35px; font-size: 0.8rem;">CV</div>
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
                            <div class="text-center">
                                <div class="fw-semibold">1 Year</div>
                                <small class="text-muted">Annual</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div>Jan 5, 2025</div>
                                <small class="text-muted">1 year ago</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div>Jan 4, 2026</div>
                                <small class="text-muted expired">Expired</small>
                            </div>
                            <div class="lease-progress">
                                <div class="lease-progress-bar danger" style="width: 100%"></div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold">₱3,800</div>
                        </td>
                        <td>
                            <span class="badge-payment paid">Paid</span>
                        </td>
                        <td>
                            <span class="badge-status expired">Expired</span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-sm btn-outline-info"><i class="bi bi-cash"></i></button>
                                <button class="btn btn-sm btn-outline-success"><i class="bi bi-bell"></i></button>
                                <button class="btn btn-sm btn-outline-warning"><i class="bi bi-arrow-repeat"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-file-x"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="small text-muted">
                Showing 5 of <span id="totalRentals">284</span> active rentals
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

<!-- Add/Edit Rental Modal -->
<div class="modal fade" id="addRentalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Rental Agreement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addRentalForm">
                    <!-- Tenant Selection -->
                    <div class="rental-form-section">
                        <h6><i class="bi bi-person"></i> Tenant Information</h6>
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label">Select Occupant</label>
                                <select class="form-select" name="occupant_id" required id="occupantSelect">
                                    <option value="">Choose occupant...</option>
                                    <option value="1">John Doe (ID: OCP-2024-001)</option>
                                    <option value="2">Maria Santos (ID: OCP-2024-002)</option>
                                    <option value="3">Alex Reyes (ID: OCP-2024-003)</option>
                                    <option value="4">Jane Lim (ID: OCP-2024-004)</option>
                                </select>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="button" class="btn btn-outline-primary" onclick="addNewOccupant()">
                                    <i class="bi bi-plus-circle me-1"></i>Add New Occupant
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Property & Room Selection -->
                    <div class="rental-form-section">
                        <h6><i class="bi bi-building"></i> Property & Room</h6>
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label class="form-label">Select Property</label>
                                <select class="form-select" name="property_id" required id="propertySelect">
                                    <option value="">Choose property...</option>
                                    <option value="1">Sunset Residences - Manila</option>
                                    <option value="2">Green Heights - Quezon City</option>
                                    <option value="3">Bayview Tower - Makati</option>
                                    <option value="4">City Lights - Pasig</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">Select Room</label>
                                <select class="form-select" name="room_id" required id="roomSelect">
                                    <option value="">Choose room...</option>
                                    <option value="101">Room 101 - Available (₱3,500)</option>
                                    <option value="102">Room 102 - Available (₱3,800)</option>
                                    <option value="201">Room 201 - Available (₱4,200)</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Room Rate</label>
                                <input type="text" class="form-control bg-light" id="roomRate" value="₱4,500" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Lease Terms -->
                    <div class="rental-form-section">
                        <h6><i class="bi bi-file-text"></i> Lease Terms</h6>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Lease Type</label>
                                <select class="form-select" name="lease_type" id="leaseType" required>
                                    <option value="monthly">Monthly</option>
                                    <option value="quarterly">Quarterly (3 months)</option>
                                    <option value="semi-annual">Semi-Annual (6 months)</option>
                                    <option value="annual">Annual (1 year)</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Start Date</label>
                                <input type="date" class="form-control" name="start_date" id="startDate" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">End Date</label>
                                <input type="date" class="form-control" name="end_date" id="endDate" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Duration</label>
                                <input type="text" class="form-control bg-light" id="duration" value="6 months" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Financial Terms -->
                    <div class="rental-form-section">
                        <h6><i class="bi bi-cash-stack"></i> Financial Terms</h6>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Monthly Rent (₱)</label>
                                <input type="number" class="form-control" name="monthly_rent" id="monthlyRent" value="4500" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Security Deposit (₱)</label>
                                <input type="number" class="form-control" name="deposit" value="4500">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Advance Payment (₱)</label>
                                <input type="number" class="form-control" name="advance" value="4500">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Total Initial Payment</label>
                                <input type="text" class="form-control bg-light" id="totalInitial" value="₱13,500" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Payment Due Day</label>
                                <select class="form-select" name="due_day">
                                    <option value="1">1st of the month</option>
                                    <option value="5" selected>5th of the month</option>
                                    <option value="10">10th of the month</option>
                                    <option value="15">15th of the month</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Late Fee (₱)</label>
                                <input type="number" class="form-control" name="late_fee" value="200">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Grace Period (days)</label>
                                <input type="number" class="form-control" name="grace_period" value="3">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contract Terms -->
                    <div class="rental-form-section">
                        <h6><i class="bi bi-file-earmark-text"></i> Contract Terms & Conditions</h6>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Special Terms & Conditions</label>
                                <textarea class="form-control" name="special_terms" rows="3">- No smoking inside the room
- Quiet hours from 10PM to 6AM
- Guests must register at the office
- No pets allowed
- Monthly payment due on or before the 5th</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Upload Contract Document</label>
                                <input type="file" class="form-control" name="contract_file">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Contract Status</label>
                                <select class="form-select" name="contract_status">
                                    <option value="draft">Draft</option>
                                    <option value="active" selected>Active</option>
                                    <option value="pending">Pending</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment Schedule Preview -->
                    <div class="rental-form-section">
                        <h6><i class="bi bi-calendar-check"></i> Payment Schedule Preview</h6>
                        <div class="payment-schedule">
                            <div class="payment-item">
                                <span class="payment-month">April 2026</span>
                                <span class="payment-amount">₱4,500</span>
                                <span class="badge-payment pending">Pending</span>
                            </div>
                            <div class="payment-item">
                                <span class="payment-month">May 2026</span>
                                <span class="payment-amount">₱4,500</span>
                                <span class="badge-payment pending">Pending</span>
                            </div>
                            <div class="payment-item">
                                <span class="payment-month">June 2026</span>
                                <span class="payment-amount">₱4,500</span>
                                <span class="badge-payment pending">Pending</span>
                            </div>
                            <div class="payment-item">
                                <span class="payment-month">July 2026</span>
                                <span class="payment-amount">₱4,500</span>
                                <span class="badge-payment pending">Pending</span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveRental()">Create Rental Agreement</button>
            </div>
        </div>
    </div>
</div>

<!-- View Rental Modal -->
<div class="modal fade" id="viewRentalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rental Agreement Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <!-- Contract Header -->
                        <div class="contract-card">
                            <div class="contract-header">
                                <div class="contract-id">Contract #CT-2024-001</div>
                                <span class="badge-status active">Active</span>
                            </div>
                            <div class="contract-terms">
                                <div class="term-item">
                                    <div class="term-label">Lease Type</div>
                                    <div class="term-value">6 Months</div>
                                </div>
                                <div class="term-item">
                                    <div class="term-label">Start Date</div>
                                    <div class="term-value">Jan 15, 2026</div>
                                </div>
                                <div class="term-item">
                                    <div class="term-label">End Date</div>
                                    <div class="term-value">Jul 14, 2026</div>
                                </div>
                                <div class="term-item">
                                    <div class="term-label">Duration</div>
                                    <div class="term-value">33% Complete</div>
                                </div>
                            </div>
                            <div class="lease-progress mt-2">
                                <div class="lease-progress-bar" style="width: 33%"></div>
                            </div>
                        </div>
                        
                        <!-- Tenant Information -->
                        <div class="contract-card">
                            <h6 class="mb-3"><i class="bi bi-person me-2 text-primary"></i>Tenant Information</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="occupant-avatar" style="width: 50px; height: 50px; font-size: 1.2rem;">JD</div>
                                        <div>
                                            <h6 class="mb-1">John Doe</h6>
                                            <p class="text-muted small mb-0">ID: OCP-2024-001</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><i class="bi bi-envelope me-2 text-muted"></i>john.doe@email.com</p>
                                    <p class="mb-1"><i class="bi bi-phone me-2 text-muted"></i>+63 912 345 6789</p>
                                    <p class="mb-0"><i class="bi bi-house me-2 text-muted"></i>123 Main St, Manila</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Property Information -->
                        <div class="contract-card">
                            <h6 class="mb-3"><i class="bi bi-building me-2 text-primary"></i>Property Information</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-2"><strong>Property:</strong> Sunset Residences</p>
                                    <p class="mb-2"><strong>Location:</strong> 123 Sunset Blvd, Manila</p>
                                    <p class="mb-2"><strong>Room:</strong> Room 204 (2nd Floor)</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2"><strong>Room Type:</strong> Deluxe (4 persons)</p>
                                    <p class="mb-2"><strong>Monthly Rent:</strong> ₱4,500</p>
                                    <p class="mb-0"><strong>Security Deposit:</strong> ₱4,500</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Financial Summary -->
                        <div class="contract-card">
                            <h6 class="mb-3"><i class="bi bi-cash me-2 text-primary"></i>Financial Summary</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="bg-light p-2 rounded text-center">
                                        <small class="text-muted d-block">Total Contract</small>
                                        <span class="fw-bold fs-6">₱27,000</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="bg-light p-2 rounded text-center">
                                        <small class="text-muted d-block">Paid to Date</small>
                                        <span class="fw-bold fs-6 text-success">₱13,500</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="bg-light p-2 rounded text-center">
                                        <small class="text-muted d-block">Balance</small>
                                        <span class="fw-bold fs-6 text-warning">₱13,500</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="bg-light p-2 rounded text-center">
                                        <small class="text-muted d-block">Next Payment</small>
                                        <span class="fw-bold fs-6">Apr 5, 2026</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Payment History -->
                        <div class="contract-card">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Payment History</h6>
                                <button class="btn btn-sm btn-outline-primary" onclick="recordPayment(1)">
                                    <i class="bi bi-plus-circle me-1"></i>Record Payment
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Month</th>
                                            <th>Due Date</th>
                                            <th>Amount</th>
                                            <th>Paid Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>January 2026</td>
                                            <td>Jan 5, 2026</td>
                                            <td>₱4,500</td>
                                            <td>Jan 15, 2026</td>
                                            <td><span class="badge-payment paid">Paid</span></td>
                                        </tr>
                                        <tr>
                                            <td>February 2026</td>
                                            <td>Feb 5, 2026</td>
                                            <td>₱4,500</td>
                                            <td>Feb 3, 2026</td>
                                            <td><span class="badge-payment paid">Paid</span></td>
                                        </tr>
                                        <tr>
                                            <td>March 2026</td>
                                            <td>Mar 5, 2026</td>
                                            <td>₱4,500</td>
                                            <td>Mar 1, 2026</td>
                                            <td><span class="badge-payment paid">Paid</span></td>
                                        </tr>
                                        <tr>
                                            <td>April 2026</td>
                                            <td>Apr 5, 2026</td>
                                            <td>₱4,500</td>
                                            <td>-</td>
                                            <td><span class="badge-payment pending">Pending</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <!-- Timeline -->
                        <div class="contract-card">
                            <h6 class="mb-3"><i class="bi bi-clock me-2 text-primary"></i>Contract Timeline</h6>
                            <div class="rental-timeline">
                                <div class="rental-timeline-item completed">
                                    <div class="rental-timeline-date">January 10, 2026</div>
                                    <div class="rental-timeline-title">Contract Signed</div>
                                    <div class="rental-timeline-text">Initial agreement signed</div>
                                </div>
                                <div class="rental-timeline-item completed">
                                    <div class="rental-timeline-date">January 15, 2026</div>
                                    <div class="rental-timeline-title">Move In</div>
                                    <div class="rental-timeline-text">Occupant moved in, keys handed over</div>
                                </div>
                                <div class="rental-timeline-item completed">
                                    <div class="rental-timeline-date">February 5, 2026</div>
                                    <div class="rental-timeline-title">First Payment</div>
                                    <div class="rental-timeline-text">February rent paid on time</div>
                                </div>
                                <div class="rental-timeline-item current">
                                    <div class="rental-timeline-date">April 5, 2026</div>
                                    <div class="rental-timeline-title">Next Payment Due</div>
                                    <div class="rental-timeline-text">April rent payment pending</div>
                                </div>
                                <div class="rental-timeline-item upcoming">
                                    <div class="rental-timeline-date">July 14, 2026</div>
                                    <div class="rental-timeline-title">Contract Expiry</div>
                                    <div class="rental-timeline-text">Lease ends, renewal option available</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Documents -->
                        <div class="contract-card">
                            <h6 class="mb-3"><i class="bi bi-file-text me-2 text-primary"></i>Documents</h6>
                            <div class="document-item">
                                <div class="document-icon bg-primary bg-opacity-10">
                                    <i class="bi bi-file-pdf text-primary"></i>
                                </div>
                                <div class="document-info">
                                    <div class="document-name">Signed Contract</div>
                                    <div class="document-date">Uploaded: Jan 15, 2026</div>
                                </div>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-download"></i>
                                </button>
                            </div>
                            <div class="document-item">
                                <div class="document-icon bg-success bg-opacity-10">
                                    <i class="bi bi-file-pdf text-success"></i>
                                </div>
                                <div class="document-info">
                                    <div class="document-name">Valid ID</div>
                                    <div class="document-date">Uploaded: Jan 15, 2026</div>
                                </div>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div class="document-item">
                                <div class="document-icon bg-warning bg-opacity-10">
                                    <i class="bi bi-file-pdf text-warning"></i>
                                </div>
                                <div class="document-info">
                                    <div class="document-name">Proof of Payment</div>
                                    <div class="document-date">Uploaded: Jan 15, 2026</div>
                                </div>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Quick Actions -->
                        <div class="contract-card">
                            <h6 class="mb-3"><i class="bi bi-lightning me-2 text-primary"></i>Quick Actions</h6>
                            <div class="d-grid gap-2">
                                <button class="btn btn-outline-primary btn-sm" onclick="sendReminder(1)">
                                    <i class="bi bi-bell me-2"></i>Send Payment Reminder
                                </button>
                                <button class="btn btn-outline-success btn-sm" onclick="renewContract(1)">
                                    <i class="bi bi-arrow-repeat me-2"></i>Renew Contract
                                </button>
                                <button class="btn btn-outline-warning btn-sm" onclick="editRental(1)">
                                    <i class="bi bi-pencil me-2"></i>Edit Contract
                                </button>
                                <button class="btn btn-outline-danger btn-sm" onclick="terminateContract(1)">
                                    <i class="bi bi-file-x me-2"></i>Terminate Contract
                                </button>
                                <button class="btn btn-outline-secondary btn-sm" onclick="printContract(1)">
                                    <i class="bi bi-printer me-2"></i>Print Contract
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="editRental(1)">Edit Contract</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        const table = $('#rentalsTable').DataTable({
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

        // Initialize date picker
        flatpickr("#expiryDate", {
            mode: "range",
            dateFormat: "Y-m-d",
            allowInput: true
        });

        // Select all checkbox
        $('#selectAll').on('change', function() {
            $('.row-checkbox').prop('checked', $(this).prop('checked'));
        });

        // Search functionality
        $('#searchRental').on('keyup', function() {
            table.search($(this).val()).draw();
        });

        // Filter by property
        $('#filterProperty').on('change', function() {
            const property = $(this).val();
            if (property) {
                table.column(3).search(property, true, false).draw();
            } else {
                table.column(3).search('').draw();
            }
        });

        // Filter by status
        $('#filterStatus').on('change', function() {
            const status = $(this).val();
            if (status) {
                table.column(9).search('^' + status + '$', true, false).draw();
            } else {
                table.column(9).search('').draw();
            }
        });

        // Filter by payment status
        $('#filterPayment').on('change', function() {
            const payment = $(this).val();
            if (payment) {
                table.column(8).search('^' + payment + '$', true, false).draw();
            } else {
                table.column(8).search('').draw();
            }
        });

        // Filter by lease type
        $('#filterLease').on('change', function() {
            const lease = $(this).val();
            if (lease) {
                table.column(4).search(lease, true, false).draw();
            } else {
                table.column(4).search('').draw();
            }
        });

        // Update total rentals count
        $('#totalRentals').text(table.page.info().recordsTotal);

        // Property change - load rooms
        $('#propertySelect').on('change', function() {
            const propertyId = $(this).val();
            if (propertyId) {
                $('#roomSelect').html('<option value="">Loading rooms...</option>');
                setTimeout(() => {
                    $('#roomSelect').html(`
                        <option value="">Choose room...</option>
                        <option value="101">Room 101 - Available (₱3,500)</option>
                        <option value="102">Room 102 - Available (₱3,800)</option>
                        <option value="201">Room 201 - Available (₱4,200)</option>
                        <option value="202">Room 202 - Available (₱4,500)</option>
                    `);
                }, 500);
            }
        });

        // Calculate dates based on lease type
        $('#leaseType, #startDate').on('change', function() {
            calculateEndDate();
        });

        // Calculate total initial payment
        $('#monthlyRent, #deposit, #advance').on('input', function() {
            calculateTotalInitial();
        });
    });

    // Calculate end date function
    function calculateEndDate() {
        const leaseType = $('#leaseType').val();
        const startDate = $('#startDate').val();
        
        if (startDate) {
            const start = new Date(startDate);
            let end = new Date(start);
            
            switch(leaseType) {
                case 'monthly':
                    end.setMonth(end.getMonth() + 1);
                    break;
                case 'quarterly':
                    end.setMonth(end.getMonth() + 3);
                    break;
                case 'semi-annual':
                    end.setMonth(end.getMonth() + 6);
                    break;
                case 'annual':
                    end.setFullYear(end.getFullYear() + 1);
                    break;
            }
            
            const endDateStr = end.toISOString().split('T')[0];
            $('#endDate').val(endDateStr);
            
            // Calculate duration
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            const diffMonths = Math.round(diffDays / 30);
            $('#duration').val(diffMonths + ' months');
        }
    }

    // Calculate total initial payment
    function calculateTotalInitial() {
        const monthly = parseFloat($('#monthlyRent').val()) || 0;
        const deposit = parseFloat($('input[name="deposit"]').val()) || 0;
        const advance = parseFloat($('input[name="advance"]').val()) || 0;
        const total = monthly + deposit + advance;
        $('#totalInitial').val('₱' + total.toLocaleString());
    }

    // Export rentals function
    function exportRentals(type) {
        showToast('info', `Exporting as ${type.toUpperCase()}...`);
        setTimeout(() => {
            showToast('success', 'Export completed successfully');
        }, 2000);
    }

    // Generate report function
    function generateReport() {
        Swal.fire({
            title: 'Generate Report',
            html: `
                <select class="form-select mb-3" id="reportType">
                    <option value="summary">Summary Report</option>
                    <option value="detailed">Detailed Report</option>
                    <option value="expiring">Expiring Contracts</option>
                    <option value="financial">Financial Report</option>
                </select>
                <select class="form-select mb-3" id="reportPeriod">
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

    // View rental function
    function viewRental(id) {
        $('#viewRentalModal').modal('show');
    }

    // Edit rental function
    function editRental(id) {
        $('#addRentalModal').modal('show');
        showToast('info', 'Loading rental data...');
    }

    // View payments function
    function viewPayments(id) {
        showToast('info', 'Loading payment schedule...');
        // Navigate to payments page with filter
    }

    // Send reminder function
    function sendReminder(id) {
        Swal.fire({
            title: 'Send Payment Reminder',
            text: 'Send payment reminder to tenant?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Send',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                showToast('success', 'Reminder sent successfully');
            }
        });
    }

    // Renew contract function
    function renewContract(id) {
        Swal.fire({
            title: 'Renew Contract',
            html: `
                <select class="form-select mb-3">
                    <option value="monthly">Monthly (1 month)</option>
                    <option value="quarterly">Quarterly (3 months)</option>
                    <option value="semi-annual">Semi-Annual (6 months)</option>
                    <option value="annual">Annual (1 year)</option>
                </select>
                <input type="date" class="form-control" placeholder="New start date">
            `,
            showCancelButton: true,
            confirmButtonText: 'Renew',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();
                setTimeout(() => {
                    hideLoading();
                    Swal.fire('Success!', 'Contract renewed successfully', 'success');
                }, 1500);
            }
        });
    }

    // Terminate contract function
    function terminateContract(id) {
        Swal.fire({
            title: 'Terminate Contract',
            text: 'Are you sure you want to terminate this contract? This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, terminate',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();
                setTimeout(() => {
                    hideLoading();
                    Swal.fire(
                        'Terminated!',
                        'Contract has been terminated.',
                        'success'
                    );
                }, 1500);
            }
        });
    }

    // Save rental function
    function saveRental() {
        // Validate form
        const form = document.getElementById('addRentalForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        showLoading();
        
        // Simulate API call
        setTimeout(() => {
            hideLoading();
            $('#addRentalModal').modal('hide');
            form.reset();
            
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Rental agreement created successfully.',
                timer: 2000,
                showConfirmButton: false
            });
        }, 1500);
    }

    // Add new occupant function
    function addNewOccupant() {
        $('#addRentalModal').modal('hide');
        // Open add occupant modal
        showToast('info', 'Redirecting to add occupant...');
    }

    // Record payment function
    function recordPayment(id) {
        Swal.fire({
            title: 'Record Payment',
            html: `
                <div class="mb-3">
                    <label class="form-label">Amount</label>
                    <input type="number" class="form-control" value="4500">
                </div>
                <div class="mb-3">
                    <label class="form-label">Payment Date</label>
                    <input type="date" class="form-control" value="${new Date().toISOString().split('T')[0]}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Payment Method</label>
                    <select class="form-select">
                        <option value="cash">Cash</option>
                        <option value="bank">Bank Transfer</option>
                        <option value="gcash">GCash</option>
                        <option value="paymaya">PayMaya</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Reference Number</label>
                    <input type="text" class="form-control" placeholder="Optional">
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Record Payment',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                showToast('success', 'Payment recorded successfully');
            }
        });
    }

    // Print contract function
    function printContract(id) {
        window.print();
    }

    // Bulk action function
    function bulkAction(action) {
        const selected = $('.row-checkbox:checked').length;
        if (selected === 0) {
            showToast('warning', 'Please select at least one rental');
            return;
        }
        
        let title, message;
        switch(action) {
            case 'send-reminder':
                title = 'Send Payment Reminder';
                message = `Send payment reminder to ${selected} selected tenant(s)?`;
                break;
            case 'send-renewal':
                title = 'Send Renewal Notice';
                message = `Send renewal notice to ${selected} selected tenant(s)?`;
                break;
            case 'export-selected':
                title = 'Export Selected';
                message = `Export ${selected} selected rental(s)?`;
                break;
            case 'terminate':
                title = 'Terminate Contracts';
                message = `Terminate ${selected} selected contract(s)?`;
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
</script>
@endsection