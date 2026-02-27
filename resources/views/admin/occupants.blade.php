@extends('layouts.admin')

@section('title', 'Occupants Management - StayEase Admin')

@section('page_header', 'Occupants Management')

@section('page_description', 'Manage all tenants and occupants across boarding houses')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Occupants</li>
@endsection

@section('header_actions')
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addOccupantModal">
        <i class="bi bi-plus-circle me-2"></i>Add New Occupant
    </button>
    <div class="btn-group ms-2">
        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
            <i class="bi bi-download me-2"></i>Export
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="#" onclick="exportOccupants('pdf')"><i class="bi bi-file-pdf me-2"></i>PDF</a></li>
            <li><a class="dropdown-item" href="#" onclick="exportOccupants('excel')"><i class="bi bi-file-excel me-2"></i>Excel</a></li>
            <li><a class="dropdown-item" href="#" onclick="exportOccupants('csv')"><i class="bi bi-file-text me-2"></i>CSV</a></li>
        </ul>
    </div>
    <button class="btn btn-outline-primary ms-2" onclick="sendBulkMessage()">
        <i class="bi bi-envelope-paper me-2"></i>Bulk Message
    </button>
@endsection

@section('styles')
<style>
    /* Occupant Stats Cards */
    .occupant-stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.03);
        border: 1px solid rgba(0,0,0,0.03);
        height: 100%;
        transition: all 0.3s;
    }
    
    .occupant-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.1);
        border-color: rgba(102, 126, 234, 0.15);
    }
    
    .occupant-stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }
    
    .occupant-stat-icon.primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .occupant-stat-icon.success {
        background: linear-gradient(135deg, #34b1aa 0%, #2c9a94 100%);
    }
    
    .occupant-stat-icon.warning {
        background: linear-gradient(135deg, #f6b23e 0%, #f4a51e 100%);
    }
    
    .occupant-stat-icon.info {
        background: linear-gradient(135deg, #3b7cff 0%, #2b6ef0 100%);
    }
    
    .occupant-stat-value {
        font-size: 1.6rem;
        font-weight: 700;
        color: #2d3748;
        line-height: 1.2;
    }
    
    .occupant-stat-label {
        color: #718096;
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    .occupant-stat-change {
        font-size: 0.75rem;
        padding: 0.2rem 0.4rem;
        border-radius: 16px;
        background: #f0fff4;
        color: #2ecc71;
    }
    
    .occupant-stat-change.negative {
        background: #fff5f5;
        color: #e74c3c;
    }
    
    /* Occupant Table Styles */
    .occupant-avatar {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 1rem;
    }
    
    .occupant-info h6 {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.2rem;
        font-size: 0.95rem;
    }
    
    .occupant-info small {
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
        max-width: 150px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
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
    
    .badge-status.pending {
        background: #fff3e0;
        color: #f39c12;
    }
    
    .badge-status.inactive {
        background: #fee9e9;
        color: #e74c3c;
    }
    
    .badge-status.blacklisted {
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
    
    /* Timeline */
    .timeline {
        position: relative;
        padding-left: 1.5rem;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #edf2f7;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }
    
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -1.5rem;
        top: 0.25rem;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #667eea;
        border: 2px solid white;
        z-index: 1;
    }
    
    .timeline-date {
        font-size: 0.7rem;
        color: #718096;
        margin-bottom: 0.2rem;
    }
    
    .timeline-title {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.2rem;
        font-size: 0.9rem;
    }
    
    .timeline-text {
        font-size: 0.8rem;
        color: #718096;
    }
    
    /* Document List */
    .document-item {
        display: flex;
        align-items: center;
        padding: 0.5rem;
        border-bottom: 1px solid #edf2f7;
    }
    
    .document-item:last-child {
        border-bottom: none;
    }
    
    .document-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        margin-right: 0.75rem;
    }
    
    .document-info {
        flex: 1;
    }
    
    .document-name {
        font-weight: 500;
        color: #2d3748;
        font-size: 0.85rem;
    }
    
    .document-date {
        font-size: 0.7rem;
        color: #718096;
    }
    
    /* Payment History */
    .payment-history-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem;
        border-bottom: 1px solid #edf2f7;
    }
    
    .payment-history-item:last-child {
        border-bottom: none;
    }
    
    .payment-month {
        font-weight: 500;
        color: #2d3748;
        font-size: 0.85rem;
    }
    
    .payment-amount {
        font-weight: 600;
        color: #27ae60;
    }
    
    .payment-status {
        font-size: 0.7rem;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .occupant-stat-value {
            font-size: 1.3rem;
        }
        
        .occupant-stat-icon {
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
        
        .action-buttons .btn {
            padding: 0.2rem 0.4rem;
            font-size: 0.7rem;
        }
    }
    
    /* Modal Styles */
    .occupant-form-section {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .occupant-form-section h6 {
        font-size: 0.9rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .occupant-form-section h6 i {
        color: #667eea;
    }
    
    /* Tab Navigation */
    .occupant-tabs {
        border-bottom: 1px solid #edf2f7;
        margin-bottom: 1.5rem;
    }
    
    .occupant-tabs .nav-link {
        color: #718096;
        font-weight: 500;
        padding: 0.75rem 1rem;
        border: none;
        position: relative;
    }
    
    .occupant-tabs .nav-link.active {
        color: #667eea;
        background: none;
    }
    
    .occupant-tabs .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    /* Emergency Contact */
    .emergency-contact {
        background: #fff3e0;
        border-radius: 12px;
        padding: 1rem;
        margin-top: 1rem;
    }
    
    .emergency-contact h6 {
        color: #f39c12;
        margin-bottom: 0.75rem;
    }
</style>
@endsection

@section('content')
<div class="fade-in">
    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="occupant-stat-card">
                <div class="d-flex align-items-center">
                    <div class="occupant-stat-icon primary me-3">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="occupant-stat-value">312</div>
                        <div class="occupant-stat-label">Total Occupants</div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Active tenants</span>
                        <span class="occupant-stat-change">
                            <i class="bi bi-arrow-up"></i> +18 this month
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="occupant-stat-card">
                <div class="d-flex align-items-center">
                    <div class="occupant-stat-icon success me-3">
                        <i class="bi bi-door-open"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="occupant-stat-value">284</div>
                        <div class="occupant-stat-label">Currently Housed</div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">In 24 properties</span>
                        <span class="occupant-stat-change">
                            <i class="bi bi-arrow-up"></i> +12
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="occupant-stat-card">
                <div class="d-flex align-items-center">
                    <div class="occupant-stat-icon warning me-3">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="occupant-stat-value">₱1.2M</div>
                        <div class="occupant-stat-label">Monthly Collections</div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">85% collected</span>
                        <span class="occupant-stat-change">
                            <i class="bi bi-arrow-up"></i> +8%
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="occupant-stat-card">
                <div class="d-flex align-items-center">
                    <div class="occupant-stat-icon info me-3">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="occupant-stat-value">23</div>
                        <div class="occupant-stat-label">Due Payments</div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Overdue: 8</span>
                        <span class="occupant-stat-change negative">
                            <i class="bi bi-arrow-up"></i> +3
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
                    <div class="quick-stat-label">Male</div>
                </div>
            </div>
            <div class="col-3">
                <div class="quick-stat-item">
                    <div class="quick-stat-number">128</div>
                    <div class="quick-stat-label">Female</div>
                </div>
            </div>
            <div class="col-3">
                <div class="quick-stat-item">
                    <div class="quick-stat-number">28</div>
                    <div class="quick-stat-label">Check-out today</div>
                </div>
            </div>
            <div class="col-3">
                <div class="quick-stat-item">
                    <div class="quick-stat-number">15</div>
                    <div class="quick-stat-label">Moving in</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="row g-3">
            <div class="col-lg-3 col-md-6">
                <div class="filter-label">Search</div>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-0 bg-light" id="searchOccupant" placeholder="Name, email, phone...">
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <div class="filter-label">Property</div>
                <select class="form-select bg-light border-0" id="filterProperty">
                    <option value="">All Properties</option>
                    <option value="sunset">Sunset Residences</option>
                    <option value="green">Green Heights</option>
                    <option value="bayview">Bayview Tower</option>
                    <option value="city">City Lights</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <div class="filter-label">Status</div>
                <select class="form-select bg-light border-0" id="filterStatus">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="pending">Pending</option>
                    <option value="inactive">Inactive</option>
                    <option value="blacklisted">Blacklisted</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <div class="filter-label">Payment Status</div>
                <select class="form-select bg-light border-0" id="filterPayment">
                    <option value="">All</option>
                    <option value="paid">Paid</option>
                    <option value="pending">Pending</option>
                    <option value="overdue">Overdue</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-12">
                <div class="filter-label">Move-in Date</div>
                <div class="d-flex gap-2">
                    <input type="text" class="form-control bg-light border-0" id="dateFrom" placeholder="From">
                    <input type="text" class="form-control bg-light border-0" id="dateTo" placeholder="To">
                </div>
            </div>
        </div>
    </div>

    <!-- Occupants Table -->
    <div class="table-container">
        <div class="table-header">
            <h5 class="table-title">Occupants List</h5>
            <div class="d-flex align-items-center gap-3">
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-gear"></i> Bulk Actions
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="bulkAction('send-message')"><i class="bi bi-envelope me-2"></i>Send Message</a></li>
                        <li><a class="dropdown-item" href="#" onclick="bulkAction('send-reminder')"><i class="bi bi-bell me-2"></i>Send Payment Reminder</a></li>
                        <li><a class="dropdown-item" href="#" onclick="bulkAction('export-selected')"><i class="bi bi-download me-2"></i>Export Selected</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#" onclick="bulkAction('deactivate')"><i class="bi bi-person-x me-2"></i>Deactivate</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover" id="occupantsTable">
                <thead>
                    <tr>
                        <th width="40">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAll">
                            </div>
                        </th>
                        <th>Occupant</th>
                        <th>Property & Room</th>
                        <th>Contact</th>
                        <th>Move In</th>
                        <th>Monthly Rent</th>
                        <th>Payment Status</th>
                        <th>Status</th>
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
                            <div class="d-flex align-items-center gap-2">
                                <div class="occupant-avatar">JD</div>
                                <div class="occupant-info">
                                    <h6>John Doe</h6>
                                    <small>ID: OCP-2024-001</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <span class="property-badge">Sunset Residences</span>
                                <span class="room-badge ms-1">Rm 204</span>
                                <small class="text-muted d-block">2nd Floor, 4 persons</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div><i class="bi bi-envelope me-1 text-muted"></i> john@email.com</div>
                                <small class="text-muted"><i class="bi bi-phone me-1"></i> +63 912 345 6789</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div>Jan 15, 2026</div>
                                <small class="text-muted">2 months</small>
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
                                <button class="btn btn-sm btn-outline-primary" title="View" onclick="viewOccupant(1)">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-secondary" title="Edit" onclick="editOccupant(1)">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-info" title="Payment History" onclick="viewPayments(1)">
                                    <i class="bi bi-cash"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-success" title="Send Message" onclick="sendMessage(1)">
                                    <i class="bi bi-chat"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-warning" title="Maintenance Requests" onclick="viewMaintenance(1)">
                                    <i class="bi bi-tools"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" title="Delete" onclick="deleteOccupant(1)">
                                    <i class="bi bi-trash"></i>
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
                            <div class="d-flex align-items-center gap-2">
                                <div class="occupant-avatar">MS</div>
                                <div class="occupant-info">
                                    <h6>Maria Santos</h6>
                                    <small>ID: OCP-2024-002</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <span class="property-badge">Green Heights</span>
                                <span class="room-badge ms-1">Rm 305</span>
                                <small class="text-muted d-block">3rd Floor, 2 persons</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div><i class="bi bi-envelope me-1 text-muted"></i> maria@email.com</div>
                                <small class="text-muted"><i class="bi bi-phone me-1"></i> +63 923 456 7890</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div>Feb 1, 2026</div>
                                <small class="text-muted">1 month</small>
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
                                <button class="btn btn-sm btn-outline-success"><i class="bi bi-chat"></i></button>
                                <button class="btn btn-sm btn-outline-warning"><i class="bi bi-tools"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
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
                            <div class="d-flex align-items-center gap-2">
                                <div class="occupant-avatar">AR</div>
                                <div class="occupant-info">
                                    <h6>Alex Reyes</h6>
                                    <small>ID: OCP-2024-003</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <span class="property-badge">Bayview Tower</span>
                                <span class="room-badge ms-1">Rm 101</span>
                                <small class="text-muted d-block">1st Floor, 6 persons</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div><i class="bi bi-envelope me-1 text-muted"></i> alex@email.com</div>
                                <small class="text-muted"><i class="bi bi-phone me-1"></i> +63 934 567 8901</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div>Dec 10, 2025</div>
                                <small class="text-muted">3 months</small>
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold">₱8,500</div>
                        </td>
                        <td>
                            <span class="badge-payment overdue">Overdue</span>
                        </td>
                        <td>
                            <span class="badge-status active">Active</span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-sm btn-outline-info"><i class="bi bi-cash"></i></button>
                                <button class="btn btn-sm btn-outline-success"><i class="bi bi-chat"></i></button>
                                <button class="btn btn-sm btn-outline-warning"><i class="bi bi-tools"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
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
                            <div class="d-flex align-items-center gap-2">
                                <div class="occupant-avatar">JL</div>
                                <div class="occupant-info">
                                    <h6>Jane Lim</h6>
                                    <small>ID: OCP-2024-004</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <span class="property-badge">City Lights</span>
                                <span class="room-badge ms-1">Rm 412</span>
                                <small class="text-muted d-block">4th Floor, 4 persons</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div><i class="bi bi-envelope me-1 text-muted"></i> jane@email.com</div>
                                <small class="text-muted"><i class="bi bi-phone me-1"></i> +63 945 678 9012</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div>Mar 1, 2026</div>
                                <small class="text-muted">Just moved</small>
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold">₱4,000</div>
                        </td>
                        <td>
                            <span class="badge-payment paid">Paid</span>
                        </td>
                        <td>
                            <span class="badge-status pending">Pending</span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-sm btn-outline-info"><i class="bi bi-cash"></i></button>
                                <button class="btn btn-sm btn-outline-success"><i class="bi bi-chat"></i></button>
                                <button class="btn btn-sm btn-outline-warning"><i class="bi bi-tools"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
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
                            <div class="d-flex align-items-center gap-2">
                                <div class="occupant-avatar">CV</div>
                                <div class="occupant-info">
                                    <h6>Carlos Villanueva</h6>
                                    <small>ID: OCP-2024-005</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <span class="property-badge">Sunset Residences</span>
                                <span class="room-badge ms-1">Rm 310</span>
                                <small class="text-muted d-block">3rd Floor, 2 persons</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div><i class="bi bi-envelope me-1 text-muted"></i> carlos@email.com</div>
                                <small class="text-muted"><i class="bi bi-phone me-1"></i> +63 956 789 0123</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div>Feb 15, 2026</div>
                                <small class="text-muted">2 weeks</small>
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold">₱3,800</div>
                        </td>
                        <td>
                            <span class="badge-payment pending">Pending</span>
                        </td>
                        <td>
                            <span class="badge-status blacklisted">Blacklisted</span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-sm btn-outline-info"><i class="bi bi-cash"></i></button>
                                <button class="btn btn-sm btn-outline-success"><i class="bi bi-chat"></i></button>
                                <button class="btn btn-sm btn-outline-warning"><i class="bi bi-tools"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="small text-muted">
                Showing 5 of <span id="totalOccupants">312</span> occupants
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

<!-- Add Occupant Modal -->
<div class="modal fade" id="addOccupantModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Occupant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addOccupantForm">
                    <!-- Personal Information -->
                    <div class="occupant-form-section">
                        <h6><i class="bi bi-person"></i> Personal Information</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" name="first_name" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Middle Name</label>
                                <input type="text" class="form-control" name="middle_name">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="last_name" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Gender</label>
                                <select class="form-select" name="gender">
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Birth Date</label>
                                <input type="date" class="form-control" name="birth_date">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Civil Status</label>
                                <select class="form-select" name="civil_status">
                                    <option value="single">Single</option>
                                    <option value="married">Married</option>
                                    <option value="divorced">Divorced</option>
                                    <option value="widowed">Widowed</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Nationality</label>
                                <input type="text" class="form-control" name="nationality" value="Filipino">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contact Information -->
                    <div class="occupant-form-section">
                        <h6><i class="bi bi-telephone"></i> Contact Information</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" name="phone" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Alternative Phone</label>
                                <input type="tel" class="form-control" name="alt_phone">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Home Address</label>
                                <textarea class="form-control" name="home_address" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Emergency Contact -->
                    <div class="occupant-form-section">
                        <h6><i class="bi bi-exclamation-circle"></i> Emergency Contact</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" name="emergency_name">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Relationship</label>
                                <input type="text" class="form-control" name="emergency_relation">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Contact Number</label>
                                <input type="tel" class="form-control" name="emergency_phone">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Occupancy Details -->
                    <div class="occupant-form-section">
                        <h6><i class="bi bi-door-open"></i> Occupancy Details</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Select Property</label>
                                <select class="form-select" name="property_id" required id="propertySelect">
                                    <option value="">Choose property...</option>
                                    <option value="1">Sunset Residences</option>
                                    <option value="2">Green Heights</option>
                                    <option value="3">Bayview Tower</option>
                                    <option value="4">City Lights</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Select Room</label>
                                <select class="form-select" name="room_id" required id="roomSelect">
                                    <option value="">Choose room...</option>
                                    <option value="101">Room 101 - Available</option>
                                    <option value="102">Room 102 - Available</option>
                                    <option value="201">Room 201 - Available</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Move-in Date</label>
                                <input type="date" class="form-control" name="move_in_date" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Monthly Rent (₱)</label>
                                <input type="number" class="form-control" name="monthly_rent" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Security Deposit (₱)</label>
                                <input type="number" class="form-control" name="deposit">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Lease Duration</label>
                                <select class="form-select" name="lease_duration">
                                    <option value="monthly">Monthly</option>
                                    <option value="3months">3 Months</option>
                                    <option value="6months">6 Months</option>
                                    <option value="1year">1 Year</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="active">Active</option>
                                    <option value="pending">Pending</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Identification -->
                    <div class="occupant-form-section">
                        <h6><i class="bi bi-card-text"></i> Identification</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">ID Type</label>
                                <select class="form-select" name="id_type">
                                    <option value="drivers">Driver's License</option>
                                    <option value="passport">Passport</option>
                                    <option value="national">National ID</option>
                                    <option value="postal">Postal ID</option>
                                    <option value="student">Student ID</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">ID Number</label>
                                <input type="text" class="form-control" name="id_number">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Upload ID</label>
                                <input type="file" class="form-control" name="id_image">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveOccupant()">Save Occupant</button>
            </div>
        </div>
    </div>
</div>

<!-- View Occupant Modal -->
<div class="modal fade" id="viewOccupantModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Occupant Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs occupant-tabs" id="occupantTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab">
                            <i class="bi bi-person me-2"></i>Personal Info
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="occupancy-tab" data-bs-toggle="tab" data-bs-target="#occupancy" type="button" role="tab">
                            <i class="bi bi-door-open me-2"></i>Occupancy
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="payments-tab" data-bs-toggle="tab" data-bs-target="#payments" type="button" role="tab">
                            <i class="bi bi-cash me-2"></i>Payments
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab">
                            <i class="bi bi-file-text me-2"></i>Documents
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab">
                            <i class="bi bi-clock-history me-2"></i>History
                        </button>
                    </li>
                </ul>
                
                <!-- Tabs Content -->
                <div class="tab-content" id="occupantTabsContent">
                    <!-- Personal Info Tab -->
                    <div class="tab-pane fade show active" id="personal" role="tabpanel">
                        <div class="row mt-3">
                            <div class="col-md-3 text-center">
                                <div class="occupant-avatar mx-auto" style="width: 100px; height: 100px; font-size: 2rem;">JD</div>
                                <h5 class="mt-2">John Doe</h5>
                                <p class="text-muted">ID: OCP-2024-001</p>
                                <span class="badge-status active">Active</span>
                            </div>
                            <div class="col-md-9">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="border rounded p-2">
                                            <small class="text-muted d-block">Full Name</small>
                                            <span>John Michael Doe</span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="border rounded p-2">
                                            <small class="text-muted d-block">Gender</small>
                                            <span>Male</span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="border rounded p-2">
                                            <small class="text-muted d-block">Birth Date</small>
                                            <span>Jan 15, 1995 (29)</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="border rounded p-2">
                                            <small class="text-muted d-block">Email</small>
                                            <span>john.doe@email.com</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="border rounded p-2">
                                            <small class="text-muted d-block">Phone</small>
                                            <span>+63 912 345 6789</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="border rounded p-2">
                                            <small class="text-muted d-block">Nationality</small>
                                            <span>Filipino</span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="border rounded p-2">
                                            <small class="text-muted d-block">Home Address</small>
                                            <span>123 Main St, Barangay San Antonio, Manila</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="emergency-contact mt-3">
                                    <h6><i class="bi bi-exclamation-triangle-fill me-2"></i>Emergency Contact</h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <small class="text-muted d-block">Name</small>
                                            <span>Maria Doe</span>
                                        </div>
                                        <div class="col-md-4">
                                            <small class="text-muted d-block">Relationship</small>
                                            <span>Mother</span>
                                        </div>
                                        <div class="col-md-4">
                                            <small class="text-muted d-block">Contact</small>
                                            <span>+63 923 456 7890</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Occupancy Tab -->
                    <div class="tab-pane fade" id="occupancy" role="tabpanel">
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="mb-3"><i class="bi bi-building me-2 text-primary"></i>Property Details</h6>
                                        <p class="mb-2"><strong>Property:</strong> Sunset Residences</p>
                                        <p class="mb-2"><strong>Room:</strong> Room 204 (2nd Floor)</p>
                                        <p class="mb-2"><strong>Room Type:</strong> Deluxe (4 persons)</p>
                                        <p class="mb-2"><strong>Move-in Date:</strong> January 15, 2026</p>
                                        <p class="mb-2"><strong>Lease Duration:</strong> 6 Months</p>
                                        <p class="mb-0"><strong>Lease End:</strong> July 14, 2026</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="mb-3"><i class="bi bi-cash me-2 text-primary"></i>Financial Details</h6>
                                        <p class="mb-2"><strong>Monthly Rent:</strong> ₱4,500</p>
                                        <p class="mb-2"><strong>Security Deposit:</strong> ₱4,500</p>
                                        <p class="mb-2"><strong>Advance Payment:</strong> ₱4,500</p>
                                        <p class="mb-2"><strong>Total Paid:</strong> ₱13,500</p>
                                        <p class="mb-0"><strong>Payment Status:</strong> <span class="badge-payment paid">Paid</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="mb-3"><i class="bi bi-people me-2 text-primary"></i>Roommates</h6>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="occupant-avatar" style="width: 35px; height: 35px; font-size: 0.8rem;">MS</div>
                                                    <div>
                                                        <div class="fw-semibold">Maria Santos</div>
                                                        <small class="text-muted">Since Jan 15</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="occupant-avatar" style="width: 35px; height: 35px; font-size: 0.8rem;">AR</div>
                                                    <div>
                                                        <div class="fw-semibold">Alex Reyes</div>
                                                        <small class="text-muted">Since Feb 1</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="occupant-avatar" style="width: 35px; height: 35px; font-size: 0.8rem;">JL</div>
                                                    <div>
                                                        <div class="fw-semibold">Jane Lim</div>
                                                        <small class="text-muted">Since Mar 1</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="border rounded p-2 text-center">
                                                    <span class="fw-bold">1 spot left</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payments Tab -->
                    <div class="tab-pane fade" id="payments" role="tabpanel">
                        <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6>Payment History</h6>
                                <button class="btn btn-sm btn-primary" onclick="recordPayment(1)">
                                    <i class="bi bi-plus-circle me-1"></i>Record Payment
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Month</th>
                                            <th>Amount</th>
                                            <th>Due Date</th>
                                            <th>Paid Date</th>
                                            <th>Status</th>
                                            <th>Receipt</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>March 2026</td>
                                            <td>₱4,500</td>
                                            <td>Mar 5, 2026</td>
                                            <td>Mar 1, 2026</td>
                                            <td><span class="badge-payment paid">Paid</span></td>
                                            <td><button class="btn btn-sm btn-outline-primary"><i class="bi bi-file-pdf"></i></button></td>
                                        </tr>
                                        <tr>
                                            <td>February 2026</td>
                                            <td>₱4,500</td>
                                            <td>Feb 5, 2026</td>
                                            <td>Feb 3, 2026</td>
                                            <td><span class="badge-payment paid">Paid</span></td>
                                            <td><button class="btn btn-sm btn-outline-primary"><i class="bi bi-file-pdf"></i></button></td>
                                        </tr>
                                        <tr>
                                            <td>January 2026</td>
                                            <td>₱4,500</td>
                                            <td>Jan 5, 2026</td>
                                            <td>Jan 15, 2026</td>
                                            <td><span class="badge-payment paid">Paid</span></td>
                                            <td><button class="btn btn-sm btn-outline-primary"><i class="bi bi-file-pdf"></i></button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Documents Tab -->
                    <div class="tab-pane fade" id="documents" role="tabpanel">
                        <div class="mt-3">
                            <h6 class="mb-3">Uploaded Documents</h6>
                            
                            <div class="document-item">
                                <div class="document-icon bg-primary bg-opacity-10">
                                    <i class="bi bi-card-text text-primary"></i>
                                </div>
                                <div class="document-info">
                                    <div class="document-name">Valid ID - Driver's License</div>
                                    <div class="document-date">Uploaded: Jan 15, 2026</div>
                                </div>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-secondary ms-1">
                                    <i class="bi bi-download"></i>
                                </button>
                            </div>
                            
                            <div class="document-item">
                                <div class="document-icon bg-success bg-opacity-10">
                                    <i class="bi bi-file-text text-success"></i>
                                </div>
                                <div class="document-info">
                                    <div class="document-name">Contract of Lease</div>
                                    <div class="document-date">Uploaded: Jan 15, 2026</div>
                                </div>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-secondary ms-1">
                                    <i class="bi bi-download"></i>
                                </button>
                            </div>
                            
                            <div class="document-item">
                                <div class="document-icon bg-warning bg-opacity-10">
                                    <i class="bi bi-image text-warning"></i>
                                </div>
                                <div class="document-info">
                                    <div class="document-name">Proof of Payment - Deposit</div>
                                    <div class="document-date">Uploaded: Jan 15, 2026</div>
                                </div>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-secondary ms-1">
                                    <i class="bi bi-download"></i>
                                </button>
                            </div>
                            
                            <div class="mt-3">
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-upload me-1"></i>Upload New Document
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- History Tab -->
                    <div class="tab-pane fade" id="history" role="tabpanel">
                        <div class="mt-3">
                            <h6 class="mb-3">Activity Timeline</h6>
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-date">March 1, 2026 - 10:30 AM</div>
                                    <div class="timeline-title">Payment Received</div>
                                    <div class="timeline-text">Paid ₱4,500 for March rent via GCash</div>
                                </div>
                                
                                <div class="timeline-item">
                                    <div class="timeline-date">February 3, 2026 - 2:15 PM</div>
                                    <div class="timeline-title">Payment Received</div>
                                    <div class="timeline-text">Paid ₱4,500 for February rent via Bank Transfer</div>
                                </div>
                                
                                <div class="timeline-item">
                                    <div class="timeline-date">January 20, 2026 - 11:00 AM</div>
                                    <div class="timeline-title">Maintenance Request</div>
                                    <div class="timeline-text">Reported leaking faucet - Completed on Jan 22</div>
                                </div>
                                
                                <div class="timeline-item">
                                    <div class="timeline-date">January 15, 2026 - 9:00 AM</div>
                                    <div class="timeline-title">Move In</div>
                                    <div class="timeline-text">Occupant moved into Room 204, Sunset Residences</div>
                                </div>
                                
                                <div class="timeline-item">
                                    <div class="timeline-date">January 10, 2026 - 3:30 PM</div>
                                    <div class="timeline-title">Contract Signed</div>
                                    <div class="timeline-text">Signed 6-month lease agreement</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="editOccupant(1)">Edit Occupant</button>
                <button type="button" class="btn btn-success" onclick="sendMessage(1)">
                    <i class="bi bi-chat me-1"></i>Send Message
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
        const table = $('#occupantsTable').DataTable({
            pageLength: 10,
            responsive: true,
            searching: true,
            ordering: true,
            info: true,
            lengthChange: false,
            columnDefs: [
                { orderable: false, targets: [0, 8] },
                { searchable: false, targets: [0] }
            ]
        });

        // Initialize date pickers
        flatpickr("#dateFrom", {
            dateFormat: "Y-m-d",
            allowInput: true
        });
        
        flatpickr("#dateTo", {
            dateFormat: "Y-m-d",
            allowInput: true
        });

        // Select all checkbox
        $('#selectAll').on('change', function() {
            $('.row-checkbox').prop('checked', $(this).prop('checked'));
        });

        // Search functionality
        $('#searchOccupant').on('keyup', function() {
            table.search($(this).val()).draw();
        });

        // Filter by property
        $('#filterProperty').on('change', function() {
            const property = $(this).val();
            if (property) {
                table.column(2).search(property, true, false).draw();
            } else {
                table.column(2).search('').draw();
            }
        });

        // Filter by status
        $('#filterStatus').on('change', function() {
            const status = $(this).val();
            if (status) {
                table.column(7).search('^' + status + '$', true, false).draw();
            } else {
                table.column(7).search('').draw();
            }
        });

        // Filter by payment status
        $('#filterPayment').on('change', function() {
            const payment = $(this).val();
            if (payment) {
                table.column(6).search('^' + payment + '$', true, false).draw();
            } else {
                table.column(6).search('').draw();
            }
        });

        // Update total occupants count
        $('#totalOccupants').text(table.page.info().recordsTotal);

        // Property change - load rooms
        $('#propertySelect').on('change', function() {
            const propertyId = $(this).val();
            if (propertyId) {
                // Simulate loading rooms
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
    });

    // Export occupants function
    function exportOccupants(type) {
        showToast('info', `Exporting as ${type.toUpperCase()}...`);
        setTimeout(() => {
            showToast('success', 'Export completed successfully');
        }, 2000);
    }

    // Send bulk message function
    function sendBulkMessage() {
        Swal.fire({
            title: 'Send Bulk Message',
            html: `
                <select class="form-select mb-3" id="messageType">
                    <option value="all">All Occupants</option>
                    <option value="active">Active Only</option>
                    <option value="pending">Pending Payment</option>
                    <option value="overdue">Overdue Accounts</option>
                </select>
                <textarea class="form-control" id="bulkMessage" rows="4" placeholder="Type your message here..."></textarea>
            `,
            showCancelButton: true,
            confirmButtonText: 'Send',
            cancelButtonText: 'Cancel',
            preConfirm: () => {
                const type = document.getElementById('messageType').value;
                const message = document.getElementById('bulkMessage').value;
                if (!message) {
                    Swal.showValidationMessage('Please enter a message');
                    return false;
                }
                return { type, message };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();
                setTimeout(() => {
                    hideLoading();
                    Swal.fire('Success!', 'Bulk message sent successfully', 'success');
                }, 1500);
            }
        });
    }

    // View occupant function
    function viewOccupant(id) {
        $('#viewOccupantModal').modal('show');
    }

    // Edit occupant function
    function editOccupant(id) {
        $('#addOccupantModal').modal('show');
        showToast('info', 'Loading occupant data...');
    }

    // View payments function
    function viewPayments(id) {
        $('#viewOccupantModal').modal('show');
        $('#payments-tab').tab('show');
    }

    // Send message function
    function sendMessage(id) {
        Swal.fire({
            title: 'Send Message',
            input: 'textarea',
            inputLabel: 'Message',
            inputPlaceholder: 'Type your message here...',
            showCancelButton: true,
            confirmButtonText: 'Send',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                showToast('success', 'Message sent successfully');
            }
        });
    }

    // View maintenance function
    function viewMaintenance(id) {
        showToast('info', 'Loading maintenance requests...');
        setTimeout(() => {
            window.location.href = '/admin/maintenance?occupant_id=' + id;
        }, 1000);
    }

    // Delete occupant function
    function deleteOccupant(id) {
        Swal.fire({
            title: 'Delete Occupant?',
            text: "This action cannot be undone. All associated data will also be deleted.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();
                setTimeout(() => {
                    hideLoading();
                    Swal.fire(
                        'Deleted!',
                        'Occupant has been deleted successfully.',
                        'success'
                    );
                }, 1500);
            }
        });
    }

    // Save occupant function
    function saveOccupant() {
        // Validate form
        const form = document.getElementById('addOccupantForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        showLoading();
        
        // Simulate API call
        setTimeout(() => {
            hideLoading();
            $('#addOccupantModal').modal('hide');
            form.reset();
            
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Occupant has been added successfully.',
                timer: 2000,
                showConfirmButton: false
            });
        }, 1500);
    }

    // Record payment function
    function recordPayment(id) {
        Swal.fire({
            title: 'Record Payment',
            html: `
                <div class="mb-3">
                    <label class="form-label">Amount</label>
                    <input type="number" class="form-control" id="paymentAmount" value="4500">
                </div>
                <div class="mb-3">
                    <label class="form-label">Payment Date</label>
                    <input type="date" class="form-control" id="paymentDate" value="${new Date().toISOString().split('T')[0]}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Payment Method</label>
                    <select class="form-select" id="paymentMethod">
                        <option value="cash">Cash</option>
                        <option value="bank">Bank Transfer</option>
                        <option value="gcash">GCash</option>
                        <option value="paymaya">PayMaya</option>
                    </select>
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

    // Bulk action function
    function bulkAction(action) {
        const selected = $('.row-checkbox:checked').length;
        if (selected === 0) {
            showToast('warning', 'Please select at least one occupant');
            return;
        }
        
        let title, message;
        switch(action) {
            case 'send-message':
                title = 'Send Message';
                message = `Send message to ${selected} selected occupant(s)?`;
                break;
            case 'send-reminder':
                title = 'Send Payment Reminder';
                message = `Send payment reminder to ${selected} selected occupant(s)?`;
                break;
            case 'export-selected':
                title = 'Export Selected';
                message = `Export ${selected} selected occupant(s)?`;
                break;
            case 'deactivate':
                title = 'Deactivate Selected';
                message = `Deactivate ${selected} selected occupant(s)?`;
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
                if (action === 'send-message') {
                    sendBulkMessage();
                } else {
                    showLoading();
                    setTimeout(() => {
                        hideLoading();
                        showToast('success', `${title} completed successfully`);
                    }, 2000);
                }
            }
        });
    }
</script>
@endsection