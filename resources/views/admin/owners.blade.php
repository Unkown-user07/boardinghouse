@extends('layouts.admin')

@section('title', 'Owners Management - StayEase Admin')

@section('page_header', 'Owners Management')

@section('page_description', 'Manage boarding house owners and their properties')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Owners</li>
@endsection

@section('header_actions')
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addOwnerModal">
        <i class="bi bi-plus-circle me-2"></i>Add New Owner
    </button>
    <button class="btn btn-outline-secondary ms-2" onclick="exportOwners()">
        <i class="bi bi-download me-2"></i>Export
    </button>
@endsection

@section('styles')
<style>
    /* Owner Stats Cards */
    .owner-stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.03);
        border: 1px solid rgba(0,0,0,0.03);
        height: 100%;
        transition: all 0.3s;
    }
    
    .owner-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.1);
        border-color: rgba(102, 126, 234, 0.15);
    }
    
    .owner-stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }
    
    .owner-stat-icon.primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .owner-stat-icon.success {
        background: linear-gradient(135deg, #34b1aa 0%, #2c9a94 100%);
    }
    
    .owner-stat-icon.warning {
        background: linear-gradient(135deg, #f6b23e 0%, #f4a51e 100%);
    }
    
    .owner-stat-icon.info {
        background: linear-gradient(135deg, #3b7cff 0%, #2b6ef0 100%);
    }
    
    .owner-stat-value {
        font-size: 1.6rem;
        font-weight: 700;
        color: #2d3748;
        line-height: 1.2;
    }
    
    .owner-stat-label {
        color: #718096;
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    .owner-stat-change {
        font-size: 0.75rem;
        padding: 0.2rem 0.4rem;
        border-radius: 16px;
        background: #f0fff4;
        color: #2ecc71;
    }
    
    /* Owner Table Styles */
    .owner-avatar {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 1rem;
    }
    
    .owner-info h6 {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.2rem;
        font-size: 0.95rem;
    }
    
    .owner-info small {
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
        margin-right: 0.25rem;
        margin-bottom: 0.25rem;
    }
    
    .badge-status {
        padding: 0.25rem 0.6rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: capitalize;
    }
    
    .badge-status.active {
        background: #e6f7e6;
        color: #27ae60;
    }
    
    .badge-status.inactive {
        background: #fee9e9;
        color: #e74c3c;
    }
    
    .badge-status.pending {
        background: #fff3e0;
        color: #f39c12;
    }
    
    .badge-status.verified {
        background: #e3f2fd;
        color: #3498db;
    }
    
    .rating-stars {
        color: #ffc107;
        font-size: 0.8rem;
        letter-spacing: 2px;
    }
    
    .rating-value {
        font-size: 0.8rem;
        color: #718096;
        margin-left: 0.3rem;
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
    .quick-stat {
        text-align: center;
        padding: 0.75rem;
        border-right: 1px solid #edf2f7;
    }
    
    .quick-stat:last-child {
        border-right: none;
    }
    
    .quick-stat-value {
        font-size: 1.2rem;
        font-weight: 700;
        color: #2d3748;
        line-height: 1.2;
    }
    
    .quick-stat-label {
        font-size: 0.7rem;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .owner-stat-value {
            font-size: 1.3rem;
        }
        
        .owner-stat-icon {
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
        }
        
        .filter-section {
            padding: 1rem;
        }
        
        .quick-stat {
            border-right: none;
            border-bottom: 1px solid #edf2f7;
            padding: 0.5rem;
        }
        
        .quick-stat:last-child {
            border-bottom: none;
        }
    }
    
    /* Modal Styles */
    .owner-form-section {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .owner-form-section h6 {
        font-size: 0.9rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .owner-form-section h6 i {
        color: #667eea;
    }
</style>
@endsection

@section('content')
<div class="fade-in">
    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="owner-stat-card">
                <div class="d-flex align-items-center">
                    <div class="owner-stat-icon primary me-3">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="owner-stat-value">156</div>
                        <div class="owner-stat-label">Total Owners</div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Active: 142</span>
                        <span class="owner-stat-change">
                            <i class="bi bi-arrow-up"></i> +12 this month
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="owner-stat-card">
                <div class="d-flex align-items-center">
                    <div class="owner-stat-icon success me-3">
                        <i class="bi bi-building"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="owner-stat-value">245</div>
                        <div class="owner-stat-label">Total Properties</div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Avg: 1.6 per owner</span>
                        <span class="owner-stat-change">
                            <i class="bi bi-arrow-up"></i> +8 this month
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="owner-stat-card">
                <div class="d-flex align-items-center">
                    <div class="owner-stat-icon warning me-3">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="owner-stat-value">₱2.4M</div>
                        <div class="owner-stat-label">Monthly Revenue</div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Shared with owners</span>
                        <span class="owner-stat-change">
                            <i class="bi bi-arrow-up"></i> +18%
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="owner-stat-card">
                <div class="d-flex align-items-center">
                    <div class="owner-stat-icon info me-3">
                        <i class="bi bi-star"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="owner-stat-value">4.8</div>
                        <div class="owner-stat-label">Avg Rating</div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Top performing</span>
                        <span class="owner-stat-change">
                            <i class="bi bi-star-fill text-warning"></i> 5 stars
                        </span>
                    </div>
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
                    <input type="text" class="form-control border-0 bg-light" id="searchOwner" placeholder="Name, email, phone...">
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <div class="filter-label">Status</div>
                <select class="form-select bg-light border-0" id="filterStatus">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="pending">Pending</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <div class="filter-label">Verification</div>
                <select class="form-select bg-light border-0" id="filterVerification">
                    <option value="">All</option>
                    <option value="verified">Verified</option>
                    <option value="unverified">Unverified</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <div class="filter-label">Properties</div>
                <select class="form-select bg-light border-0" id="filterProperties">
                    <option value="">All</option>
                    <option value="1">1+ Properties</option>
                    <option value="2">2+ Properties</option>
                    <option value="3">3+ Properties</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-12">
                <div class="filter-label">Date Range</div>
                <div class="d-flex gap-2">
                    <input type="text" class="form-control bg-light border-0" id="dateFrom" placeholder="From">
                    <input type="text" class="form-control bg-light border-0" id="dateTo" placeholder="To">
                </div>
            </div>
        </div>
    </div>

    <!-- Owners Table -->
    <div class="table-container">
        <div class="table-header">
            <h5 class="table-title">Owners List</h5>
            <div class="d-flex align-items-center gap-3">
                <div class="quick-stats d-flex">
                    <div class="quick-stat px-3">
                        <div class="quick-stat-value">142</div>
                        <div class="quick-stat-label">Active</div>
                    </div>
                    <div class="quick-stat px-3">
                        <div class="quick-stat-value">8</div>
                        <div class="quick-stat-label">Pending</div>
                    </div>
                    <div class="quick-stat px-3">
                        <div class="quick-stat-value">6</div>
                        <div class="quick-stat-label">Inactive</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover" id="ownersTable">
                <thead>
                    <tr>
                        <th>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAll">
                            </div>
                        </th>
                        <th>Owner</th>
                        <th>Contact</th>
                        <th>Properties</th>
                        <th>Status</th>
                        <th>Verification</th>
                        <th>Rating</th>
                        <th>Joined</th>
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
                                <div class="owner-avatar">JD</div>
                                <div class="owner-info">
                                    <h6>Juan Dela Cruz</h6>
                                    <small>Owner ID: OWN-2024-001</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div><i class="bi bi-envelope me-1 text-muted"></i> juan@email.com</div>
                                <small class="text-muted"><i class="bi bi-phone me-1"></i> +63 912 345 6789</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <span class="property-badge">Sunset Residences</span>
                                <span class="property-badge">Green Heights</span>
                                <span class="property-badge">+2 more</span>
                            </div>
                            <small class="text-muted">4 total properties</small>
                        </td>
                        <td>
                            <span class="badge-status active">Active</span>
                        </td>
                        <td>
                            <span class="badge-status verified">Verified</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="rating-stars">★★★★★</span>
                                <span class="rating-value">(4.9)</span>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div>Jan 15, 2024</div>
                                <small class="text-muted">8 months ago</small>
                            </div>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-outline-primary" title="View" onclick="viewOwner(1)">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-secondary" title="Edit" onclick="editOwner(1)">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-info" title="Properties" onclick="viewProperties(1)">
                                    <i class="bi bi-building"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-success" title="Payments" onclick="viewPayments(1)">
                                    <i class="bi bi-cash"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-warning" title="Contracts" onclick="viewContracts(1)">
                                    <i class="bi bi-file-text"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" title="Delete" onclick="deleteOwner(1)">
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
                                <div class="owner-avatar">MS</div>
                                <div class="owner-info">
                                    <h6>Maria Santos</h6>
                                    <small>Owner ID: OWN-2024-002</small>
                                </div>
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
                                <span class="property-badge">Bayview Tower</span>
                                <span class="property-badge">City Lights</span>
                            </div>
                            <small class="text-muted">2 total properties</small>
                        </td>
                        <td>
                            <span class="badge-status active">Active</span>
                        </td>
                        <td>
                            <span class="badge-status verified">Verified</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="rating-stars">★★★★☆</span>
                                <span class="rating-value">(4.5)</span>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div>Feb 3, 2024</div>
                                <small class="text-muted">7 months ago</small>
                            </div>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-outline-primary" title="View"><i class="bi bi-eye"></i></button>
                                <button class="btn btn-sm btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-sm btn-outline-info" title="Properties"><i class="bi bi-building"></i></button>
                                <button class="btn btn-sm btn-outline-success" title="Payments"><i class="bi bi-cash"></i></button>
                                <button class="btn btn-sm btn-outline-warning" title="Contracts"><i class="bi bi-file-text"></i></button>
                                <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
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
                                <div class="owner-avatar">PR</div>
                                <div class="owner-info">
                                    <h6>Pedro Reyes</h6>
                                    <small>Owner ID: OWN-2024-003</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div><i class="bi bi-envelope me-1 text-muted"></i> pedro@email.com</div>
                                <small class="text-muted"><i class="bi bi-phone me-1"></i> +63 934 567 8901</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <span class="property-badge">Garden Heights</span>
                            </div>
                            <small class="text-muted">1 total property</small>
                        </td>
                        <td>
                            <span class="badge-status pending">Pending</span>
                        </td>
                        <td>
                            <span class="badge-status pending">Unverified</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="rating-stars">☆☆☆☆☆</span>
                                <span class="rating-value">(No rating)</span>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div>Mar 20, 2024</div>
                                <small class="text-muted">2 weeks ago</small>
                            </div>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-outline-primary" title="View"><i class="bi bi-eye"></i></button>
                                <button class="btn btn-sm btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-sm btn-outline-info" title="Properties"><i class="bi bi-building"></i></button>
                                <button class="btn btn-sm btn-outline-success" title="Payments"><i class="bi bi-cash"></i></button>
                                <button class="btn btn-sm btn-outline-warning" title="Contracts"><i class="bi bi-file-text"></i></button>
                                <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
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
                                <div class="owner-avatar">AL</div>
                                <div class="owner-info">
                                    <h6>Ana Lim</h6>
                                    <small>Owner ID: OWN-2024-004</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div><i class="bi bi-envelope me-1 text-muted"></i> ana@email.com</div>
                                <small class="text-muted"><i class="bi bi-phone me-1"></i> +63 945 678 9012</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <span class="property-badge">Seaside Manor</span>
                                <span class="property-badge">Harbor View</span>
                                <span class="property-badge">Hilltop</span>
                            </div>
                            <small class="text-muted">3 total properties</small>
                        </td>
                        <td>
                            <span class="badge-status active">Active</span>
                        </td>
                        <td>
                            <span class="badge-status verified">Verified</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="rating-stars">★★★★★</span>
                                <span class="rating-value">(5.0)</span>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div>Apr 5, 2024</div>
                                <small class="text-muted">5 months ago</small>
                            </div>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-outline-primary" title="View"><i class="bi bi-eye"></i></button>
                                <button class="btn btn-sm btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-sm btn-outline-info" title="Properties"><i class="bi bi-building"></i></button>
                                <button class="btn btn-sm btn-outline-success" title="Payments"><i class="bi bi-cash"></i></button>
                                <button class="btn btn-sm btn-outline-warning" title="Contracts"><i class="bi bi-file-text"></i></button>
                                <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
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
                                <div class="owner-avatar">CV</div>
                                <div class="owner-info">
                                    <h6>Carlos Villanueva</h6>
                                    <small>Owner ID: OWN-2024-005</small>
                                </div>
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
                                <span class="property-badge">Metro Suites</span>
                                <span class="property-badge">Plaza Residences</span>
                                <span class="property-badge">Tower 1</span>
                                <span class="property-badge">Tower 2</span>
                            </div>
                            <small class="text-muted">4 total properties</small>
                        </td>
                        <td>
                            <span class="badge-status inactive">Inactive</span>
                        </td>
                        <td>
                            <span class="badge-status verified">Verified</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="rating-stars">★★★★☆</span>
                                <span class="rating-value">(4.2)</span>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div>May 12, 2024</div>
                                <small class="text-muted">4 months ago</small>
                            </div>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-outline-primary" title="View"><i class="bi bi-eye"></i></button>
                                <button class="btn btn-sm btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-sm btn-outline-info" title="Properties"><i class="bi bi-building"></i></button>
                                <button class="btn btn-sm btn-outline-success" title="Payments"><i class="bi bi-cash"></i></button>
                                <button class="btn btn-sm btn-outline-warning" title="Contracts"><i class="bi bi-file-text"></i></button>
                                <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="small text-muted">
                Showing 5 of <span id="totalOwners">156</span> owners
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

<!-- Add Owner Modal -->
<div class="modal fade" id="addOwnerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Owner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addOwnerForm">
                    <!-- Personal Information -->
                    <div class="owner-form-section">
                        <h6><i class="bi bi-person"></i> Personal Information</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" name="first_name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="last_name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" name="phone" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" name="address" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Business Information -->
                    <div class="owner-form-section">
                        <h6><i class="bi bi-briefcase"></i> Business Information</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Business Name (Optional)</label>
                                <input type="text" class="form-control" name="business_name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tax ID / TIN</label>
                                <input type="text" class="form-control" name="tax_id">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Bank Name</label>
                                <input type="text" class="form-control" name="bank_name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Account Number</label>
                                <input type="text" class="form-control" name="account_number">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Documents -->
                    <div class="owner-form-section">
                        <h6><i class="bi bi-file-text"></i> Documents</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Valid ID</label>
                                <input type="file" class="form-control" name="valid_id">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Proof of Ownership</label>
                                <input type="file" class="form-control" name="proof_of_ownership">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Account Settings -->
                    <div class="owner-form-section">
                        <h6><i class="bi bi-shield-lock"></i> Account Settings</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="pending">Pending</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Verification</label>
                                <select class="form-select" name="verification">
                                    <option value="verified">Verified</option>
                                    <option value="unverified">Unverified</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Commission Rate</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="commission" value="10">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveOwner()">Save Owner</button>
            </div>
        </div>
    </div>
</div>

<!-- View Owner Modal -->
<div class="modal fade" id="viewOwnerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Owner Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="owner-avatar mx-auto mb-3" style="width: 60px; height: 60px; font-size: 1.5rem;">JD</div>
                    <h4>Juan Dela Cruz</h4>
                    <p class="text-muted">Owner ID: OWN-2024-001</p>
                    <div>
                        <span class="badge-status active me-2">Active</span>
                        <span class="badge-status verified">Verified</span>
                    </div>
                </div>
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <h6 class="mb-3"><i class="bi bi-person me-2 text-primary"></i>Personal Info</h6>
                                <p class="mb-2"><strong>Email:</strong> juan@email.com</p>
                                <p class="mb-2"><strong>Phone:</strong> +63 912 345 6789</p>
                                <p class="mb-2"><strong>Address:</strong> 123 Main St, Manila</p>
                                <p class="mb-0"><strong>Joined:</strong> Jan 15, 2024</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <h6 class="mb-3"><i class="bi bi-briefcase me-2 text-primary"></i>Business Info</h6>
                                <p class="mb-2"><strong>Business:</strong> Dela Cruz Properties</p>
                                <p class="mb-2"><strong>TIN:</strong> 123-456-789-000</p>
                                <p class="mb-2"><strong>Bank:</strong> BDO</p>
                                <p class="mb-0"><strong>Account:</strong> 001234567890</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <h6 class="mb-3"><i class="bi bi-building me-2 text-primary"></i>Properties (4)</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Property Name</th>
                                                <th>Address</th>
                                                <th>Rooms</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Sunset Residences</td>
                                                <td>123 Sunset Blvd</td>
                                                <td>12</td>
                                                <td><span class="badge-status active">Active</span></td>
                                            </tr>
                                            <tr>
                                                <td>Green Heights</td>
                                                <td>456 Green Ave</td>
                                                <td>8</td>
                                                <td><span class="badge-status active">Active</span></td>
                                            </tr>
                                            <tr>
                                                <td>Park View</td>
                                                <td>789 Park St</td>
                                                <td>6</td>
                                                <td><span class="badge-status active">Active</span></td>
                                            </tr>
                                            <tr>
                                                <td>City Lights</td>
                                                <td>321 City Rd</td>
                                                <td>10</td>
                                                <td><span class="badge-status pending">Maintenance</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <h6 class="mb-3"><i class="bi bi-graph-up me-2 text-primary"></i>Performance</h6>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Rating:</span>
                                    <span class="rating-stars">★★★★★</span>
                                    <span>4.9</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Total Revenue:</span>
                                    <span class="fw-bold">₱850,000</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Commission Paid:</span>
                                    <span class="fw-bold">₱85,000</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Occupancy Rate:</span>
                                    <span class="fw-bold">92%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <h6 class="mb-3"><i class="bi bi-file-text me-2 text-primary"></i>Documents</h6>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Valid ID:</span>
                                    <a href="#" class="text-primary"><i class="bi bi-file-pdf"></i> View</a>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Proof of Ownership:</span>
                                    <a href="#" class="text-primary"><i class="bi bi-file-pdf"></i> View</a>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Business Permit:</span>
                                    <a href="#" class="text-primary"><i class="bi bi-file-pdf"></i> View</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="editOwner(1)">Edit Owner</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        const table = $('#ownersTable').DataTable({
            pageLength: 10,
            responsive: true,
            searching: true,
            ordering: true,
            info: true,
            lengthChange: false,
            columnDefs: [
                { orderable: false, targets: [0, 8] },
                { searchable: false, targets: [0, 5, 6, 7] }
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
        $('#searchOwner').on('keyup', function() {
            table.search($(this).val()).draw();
        });

        // Filter by status
        $('#filterStatus').on('change', function() {
            const status = $(this).val();
            if (status) {
                table.column(4).search('^' + status + '$', true, false).draw();
            } else {
                table.column(4).search('').draw();
            }
        });

        // Filter by verification
        $('#filterVerification').on('change', function() {
            const verification = $(this).val();
            if (verification) {
                table.column(5).search('^' + verification + '$', true, false).draw();
            } else {
                table.column(5).search('').draw();
            }
        });

        // Update total owners count
        $('#totalOwners').text(table.page.info().recordsTotal);
    });

    // Export owners function
    function exportOwners() {
        showToast('info', 'Exporting owners data...');
        setTimeout(() => {
            showToast('success', 'Export completed successfully');
        }, 2000);
    }

    // View owner function
    function viewOwner(id) {
        $('#viewOwnerModal').modal('show');
    }

    // Edit owner function
    function editOwner(id) {
        $('#addOwnerModal').modal('show');
        showToast('info', 'Loading owner data...');
    }

    // View properties function
    function viewProperties(id) {
        showToast('info', 'Redirecting to properties...');
        setTimeout(() => {
            window.location.href = '/admin/boarding-houses?owner_id=' + id;
        }, 1000);
    }

    // View payments function
    function viewPayments(id) {
        showToast('info', 'Loading payment history...');
        setTimeout(() => {
            window.location.href = '/admin/payments?owner_id=' + id;
        }, 1000);
    }

    // View contracts function
    function viewContracts(id) {
        showToast('info', 'Loading contracts...');
        setTimeout(() => {
            window.location.href = '/admin/contracts?owner_id=' + id;
        }, 1000);
    }

    // Delete owner function
    function deleteOwner(id) {
        Swal.fire({
            title: 'Delete Owner?',
            text: "This action cannot be undone. All associated properties and data will also be deleted.",
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
                        'Owner has been deleted successfully.',
                        'success'
                    );
                }, 1500);
            }
        });
    }

    // Save owner function
    function saveOwner() {
        // Validate form
        const form = document.getElementById('addOwnerForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        showLoading();
        
        // Simulate API call
        setTimeout(() => {
            hideLoading();
            $('#addOwnerModal').modal('hide');
            form.reset();
            
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Owner has been added successfully.',
                timer: 2000,
                showConfirmButton: false
            });
        }, 1500);
    }

    // Bulk actions
    $('#bulkAction').on('change', function() {
        const action = $(this).val();
        if (!action) return;
        
        const selected = $('.row-checkbox:checked').length;
        if (selected === 0) {
            showToast('warning', 'Please select at least one owner');
            $(this).val('');
            return;
        }
        
        Swal.fire({
            title: `Bulk ${action}`,
            text: `Are you sure you want to ${action} ${selected} selected owner(s)?`,
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
                    showToast('success', `Bulk ${action} completed successfully`);
                    $('#bulkAction').val('');
                }, 2000);
            } else {
                $('#bulkAction').val('');
            }
        });
    });
</script>
@endsection