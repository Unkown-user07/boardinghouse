@extends('layouts.admin')

@section('title', 'Boarding Houses Management - StayEase Admin')

@section('page_header', 'Boarding Houses Management')

@section('page_description', 'Manage all boarding houses and their properties')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Boarding Houses</li>
@endsection

@section('header_actions')
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBoardingHouseModal">
        <i class="bi bi-plus-circle me-2"></i>Add New Boarding House
    </button>
    <div class="btn-group ms-2">
        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
            <i class="bi bi-download me-2"></i>Export
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="#" onclick="exportData('pdf')"><i class="bi bi-file-pdf me-2"></i>PDF</a></li>
            <li><a class="dropdown-item" href="#" onclick="exportData('excel')"><i class="bi bi-file-excel me-2"></i>Excel</a></li>
            <li><a class="dropdown-item" href="#" onclick="exportData('csv')"><i class="bi bi-file-text me-2"></i>CSV</a></li>
        </ul>
    </div>
@endsection

@section('styles')
<style>
    /* Property Stats Cards */
    .property-stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.03);
        border: 1px solid rgba(0,0,0,0.03);
        height: 100%;
        transition: all 0.3s;
    }
    
    .property-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.1);
        border-color: rgba(102, 126, 234, 0.15);
    }
    
    .property-stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }
    
    .property-stat-icon.primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .property-stat-icon.success {
        background: linear-gradient(135deg, #34b1aa 0%, #2c9a94 100%);
    }
    
    .property-stat-icon.warning {
        background: linear-gradient(135deg, #f6b23e 0%, #f4a51e 100%);
    }
    
    .property-stat-icon.info {
        background: linear-gradient(135deg, #3b7cff 0%, #2b6ef0 100%);
    }
    
    .property-stat-value {
        font-size: 1.6rem;
        font-weight: 700;
        color: #2d3748;
        line-height: 1.2;
    }
    
    .property-stat-label {
        color: #718096;
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    .property-stat-change {
        font-size: 0.75rem;
        padding: 0.2rem 0.4rem;
        border-radius: 16px;
        background: #f0fff4;
        color: #2ecc71;
    }
    
    /* Property Card Styles */
    .property-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.03);
        border: 1px solid rgba(0,0,0,0.03);
        transition: all 0.3s;
        height: 100%;
        position: relative;
    }
    
    .property-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(102, 126, 234, 0.1);
        border-color: rgba(102, 126, 234, 0.2);
    }
    
    .property-image {
        height: 200px;
        position: relative;
        overflow: hidden;
    }
    
    .property-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s;
    }
    
    .property-card:hover .property-image img {
        transform: scale(1.1);
    }
    
    .property-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        padding: 0.4rem 1rem;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: capitalize;
        z-index: 2;
    }
    
    .property-badge.active {
        background: #e6f7e6;
        color: #27ae60;
    }
    
    .property-badge.pending {
        background: #fff3e0;
        color: #f39c12;
    }
    
    .property-badge.inactive {
        background: #fee9e9;
        color: #e74c3c;
    }
    
    .property-content {
        padding: 1.25rem;
    }
    
    .property-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 0.25rem;
    }
    
    .property-location {
        color: #718096;
        font-size: 0.85rem;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }
    
    .property-location i {
        color: #667eea;
        font-size: 0.9rem;
    }
    
    .property-stats {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-top: 1px solid #edf2f7;
        border-bottom: 1px solid #edf2f7;
        margin-bottom: 0.75rem;
    }
    
    .stat-item {
        text-align: center;
        flex: 1;
    }
    
    .stat-number {
        font-weight: 700;
        color: #2d3748;
        font-size: 1rem;
    }
    
    .stat-label {
        font-size: 0.65rem;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    .owner-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
    }
    
    .owner-avatar-small {
        width: 30px;
        height: 30px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.8rem;
    }
    
    .owner-details {
        font-size: 0.8rem;
    }
    
    .owner-name {
        font-weight: 600;
        color: #2d3748;
    }
    
    .owner-contact {
        font-size: 0.7rem;
        color: #718096;
    }
    
    .property-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .price-range {
        font-weight: 700;
        color: #27ae60;
        font-size: 0.9rem;
    }
    
    .price-range small {
        font-weight: 400;
        color: #718096;
        font-size: 0.7rem;
    }
    
    .rating {
        color: #ffc107;
        font-size: 0.8rem;
    }
    
    .rating span {
        color: #718096;
        margin-left: 0.2rem;
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
    
    /* View Toggle */
    .view-toggle {
        background: white;
        border-radius: 30px;
        padding: 0.2rem;
        border: 1px solid #edf2f7;
        display: inline-flex;
    }
    
    .view-toggle .btn {
        border: none;
        padding: 0.3rem 1rem;
        font-size: 0.8rem;
        border-radius: 30px;
    }
    
    .view-toggle .btn.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    /* Table View Styles */
    .property-table-image {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        object-fit: cover;
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
    
    .action-buttons .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
        margin: 0 2px;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .property-stat-value {
            font-size: 1.3rem;
        }
        
        .property-stat-icon {
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
        }
        
        .filter-section {
            padding: 1rem;
        }
        
        .property-image {
            height: 160px;
        }
        
        .view-toggle {
            width: 100%;
        }
        
        .view-toggle .btn {
            flex: 1;
        }
    }
    
    /* Modal Styles */
    .property-form-section {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .property-form-section h6 {
        font-size: 0.9rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .property-form-section h6 i {
        color: #667eea;
    }
    
    /* Map Preview */
    .map-preview {
        height: 200px;
        background: #e9ecef;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #718096;
        border: 2px dashed #ced4da;
    }
</style>
@endsection

@section('content')
<div class="fade-in">
    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="property-stat-card">
                <div class="d-flex align-items-center">
                    <div class="property-stat-icon primary me-3">
                        <i class="bi bi-building"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="property-stat-value">24</div>
                        <div class="property-stat-label">Total Properties</div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Across 12 owners</span>
                        <span class="property-stat-change">
                            <i class="bi bi-arrow-up"></i> +3 this month
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="property-stat-card">
                <div class="d-flex align-items-center">
                    <div class="property-stat-icon success me-3">
                        <i class="bi bi-door-open"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="property-stat-value">384</div>
                        <div class="property-stat-label">Total Rooms</div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Avg 16 rooms/property</span>
                        <span class="property-stat-change">
                            <i class="bi bi-arrow-up"></i> +24
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="property-stat-card">
                <div class="d-flex align-items-center">
                    <div class="property-stat-icon warning me-3">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="property-stat-value">312</div>
                        <div class="property-stat-label">Total Occupants</div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">81% occupancy rate</span>
                        <span class="property-stat-change">
                            <i class="bi bi-arrow-up"></i> +12%
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="property-stat-card">
                <div class="d-flex align-items-center">
                    <div class="property-stat-icon info me-3">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="property-stat-value">₱1.8M</div>
                        <div class="property-stat-label">Monthly Revenue</div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Average ₱75k/property</span>
                        <span class="property-stat-change">
                            <i class="bi bi-arrow-up"></i> +15%
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
                    <input type="text" class="form-control border-0 bg-light" id="searchProperty" placeholder="Property name, location...">
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <div class="filter-label">Status</div>
                <select class="form-select bg-light border-0" id="filterStatus">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="pending">Pending</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <div class="filter-label">Owner</div>
                <select class="form-select bg-light border-0" id="filterOwner">
                    <option value="">All Owners</option>
                    <option value="1">Juan Dela Cruz</option>
                    <option value="2">Maria Santos</option>
                    <option value="3">Pedro Reyes</option>
                    <option value="4">Ana Lim</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <div class="filter-label">City</div>
                <select class="form-select bg-light border-0" id="filterCity">
                    <option value="">All Cities</option>
                    <option value="manila">Manila</option>
                    <option value="quezon">Quezon City</option>
                    <option value="makati">Makati</option>
                    <option value="pasig">Pasig</option>
                    <option value="taguig">Taguig</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-12">
                <div class="filter-label">Date Added</div>
                <div class="d-flex gap-2">
                    <input type="text" class="form-control bg-light border-0" id="dateFrom" placeholder="From">
                    <input type="text" class="form-control bg-light border-0" id="dateTo" placeholder="To">
                </div>
            </div>
        </div>
    </div>

    <!-- View Toggle -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center gap-3">
            <div class="view-toggle">
                <button class="btn active" onclick="toggleView('grid')" id="gridViewBtn">
                    <i class="bi bi-grid-3x3-gap-fill me-1"></i> Grid
                </button>
                <button class="btn" onclick="toggleView('list')" id="listViewBtn">
                    <i class="bi bi-list-ul me-1"></i> List
                </button>
            </div>
            <span class="text-muted small">Showing 8 of 24 properties</span>
        </div>
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-sort"></i> Sort by
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#" onclick="sortBy('name')">Name</a></li>
                <li><a class="dropdown-item" href="#" onclick="sortBy('newest')">Newest</a></li>
                <li><a class="dropdown-item" href="#" onclick="sortBy('oldest')">Oldest</a></li>
                <li><a class="dropdown-item" href="#" onclick="sortBy('occupancy')">Occupancy Rate</a></li>
                <li><a class="dropdown-item" href="#" onclick="sortBy('revenue')">Revenue</a></li>
            </ul>
        </div>
    </div>

    <!-- Grid View -->
    <div class="row g-3" id="gridView">
        <!-- Property Card 1 -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="property-card">
                <div class="property-image">
                    <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=400" alt="Sunset Residences">
                    <span class="property-badge active">Active</span>
                </div>
                <div class="property-content">
                    <h6 class="property-title">Sunset Residences</h6>
                    <div class="property-location">
                        <i class="bi bi-geo-alt-fill"></i>
                        123 Sunset Blvd, Manila
                    </div>
                    
                    <div class="owner-info">
                        <div class="owner-avatar-small">JD</div>
                        <div class="owner-details">
                            <div class="owner-name">Juan Dela Cruz</div>
                            <div class="owner-contact">juan@email.com</div>
                        </div>
                    </div>
                    
                    <div class="property-stats">
                        <div class="stat-item">
                            <div class="stat-number">24</div>
                            <div class="stat-label">Rooms</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">18</div>
                            <div class="stat-label">Occupied</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">75%</div>
                            <div class="stat-label">Rate</div>
                        </div>
                    </div>
                    
                    <div class="property-footer">
                        <div class="price-range">
                            ₱3,500 - ₱6,500 <small>/mo</small>
                        </div>
                        <div class="rating">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-half"></i>
                            <span>4.5</span>
                        </div>
                    </div>
                    
                    <div class="mt-2 d-flex gap-1">
                        <button class="btn btn-sm btn-outline-primary flex-grow-1" onclick="viewProperty(1)">
                            <i class="bi bi-eye"></i> View
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" onclick="editProperty(1)">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteProperty(1)">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Property Card 2 -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="property-card">
                <div class="property-image">
                    <img src="https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=400" alt="Green Heights">
                    <span class="property-badge active">Active</span>
                </div>
                <div class="property-content">
                    <h6 class="property-title">Green Heights</h6>
                    <div class="property-location">
                        <i class="bi bi-geo-alt-fill"></i>
                        456 Green Ave, Quezon City
                    </div>
                    
                    <div class="owner-info">
                        <div class="owner-avatar-small">MS</div>
                        <div class="owner-details">
                            <div class="owner-name">Maria Santos</div>
                            <div class="owner-contact">maria@email.com</div>
                        </div>
                    </div>
                    
                    <div class="property-stats">
                        <div class="stat-item">
                            <div class="stat-number">18</div>
                            <div class="stat-label">Rooms</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">15</div>
                            <div class="stat-label">Occupied</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">83%</div>
                            <div class="stat-label">Rate</div>
                        </div>
                    </div>
                    
                    <div class="property-footer">
                        <div class="price-range">
                            ₱3,200 - ₱5,800 <small>/mo</small>
                        </div>
                        <div class="rating">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star"></i>
                            <span>4.0</span>
                        </div>
                    </div>
                    
                    <div class="mt-2 d-flex gap-1">
                        <button class="btn btn-sm btn-outline-primary flex-grow-1" onclick="viewProperty(2)">
                            <i class="bi bi-eye"></i> View
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" onclick="editProperty(2)">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteProperty(2)">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Property Card 3 -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="property-card">
                <div class="property-image">
                    <img src="https://images.unsplash.com/photo-1575517111478-7f6afd0973db?w=400" alt="Bayview Tower">
                    <span class="property-badge active">Active</span>
                </div>
                <div class="property-content">
                    <h6 class="property-title">Bayview Tower</h6>
                    <div class="property-location">
                        <i class="bi bi-geo-alt-fill"></i>
                        789 Bayview Dr, Makati
                    </div>
                    
                    <div class="owner-info">
                        <div class="owner-avatar-small">PR</div>
                        <div class="owner-details">
                            <div class="owner-name">Pedro Reyes</div>
                            <div class="owner-contact">pedro@email.com</div>
                        </div>
                    </div>
                    
                    <div class="property-stats">
                        <div class="stat-item">
                            <div class="stat-number">32</div>
                            <div class="stat-label">Rooms</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">28</div>
                            <div class="stat-label">Occupied</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">88%</div>
                            <div class="stat-label">Rate</div>
                        </div>
                    </div>
                    
                    <div class="property-footer">
                        <div class="price-range">
                            ₱4,500 - ₱8,500 <small>/mo</small>
                        </div>
                        <div class="rating">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <span>5.0</span>
                        </div>
                    </div>
                    
                    <div class="mt-2 d-flex gap-1">
                        <button class="btn btn-sm btn-outline-primary flex-grow-1" onclick="viewProperty(3)">
                            <i class="bi bi-eye"></i> View
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" onclick="editProperty(3)">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteProperty(3)">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Property Card 4 -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="property-card">
                <div class="property-image">
                    <img src="https://images.unsplash.com/photo-1580587771525-78b9dba3b914?w=400" alt="City Lights">
                    <span class="property-badge active">Active</span>
                </div>
                <div class="property-content">
                    <h6 class="property-title">City Lights</h6>
                    <div class="property-location">
                        <i class="bi bi-geo-alt-fill"></i>
                        321 City Rd, Pasig
                    </div>
                    
                    <div class="owner-info">
                        <div class="owner-avatar-small">AL</div>
                        <div class="owner-details">
                            <div class="owner-name">Ana Lim</div>
                            <div class="owner-contact">ana@email.com</div>
                        </div>
                    </div>
                    
                    <div class="property-stats">
                        <div class="stat-item">
                            <div class="stat-number">20</div>
                            <div class="stat-label">Rooms</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">16</div>
                            <div class="stat-label">Occupied</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">80%</div>
                            <div class="stat-label">Rate</div>
                        </div>
                    </div>
                    
                    <div class="property-footer">
                        <div class="price-range">
                            ₱3,800 - ₱6,200 <small>/mo</small>
                        </div>
                        <div class="rating">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-half"></i>
                            <span>4.3</span>
                        </div>
                    </div>
                    
                    <div class="mt-2 d-flex gap-1">
                        <button class="btn btn-sm btn-outline-primary flex-grow-1" onclick="viewProperty(4)">
                            <i class="bi bi-eye"></i> View
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" onclick="editProperty(4)">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteProperty(4)">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Property Card 5 -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="property-card">
                <div class="property-image">
                    <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=400" alt="Garden Heights">
                    <span class="property-badge pending">Pending</span>
                </div>
                <div class="property-content">
                    <h6 class="property-title">Garden Heights</h6>
                    <div class="property-location">
                        <i class="bi bi-geo-alt-fill"></i>
                        654 Garden St, Taguig
                    </div>
                    
                    <div class="owner-info">
                        <div class="owner-avatar-small">CV</div>
                        <div class="owner-details">
                            <div class="owner-name">Carlos Villanueva</div>
                            <div class="owner-contact">carlos@email.com</div>
                        </div>
                    </div>
                    
                    <div class="property-stats">
                        <div class="stat-item">
                            <div class="stat-number">15</div>
                            <div class="stat-label">Rooms</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">0</div>
                            <div class="stat-label">Occupied</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">0%</div>
                            <div class="stat-label">Rate</div>
                        </div>
                    </div>
                    
                    <div class="property-footer">
                        <div class="price-range">
                            ₱3,000 - ₱5,500 <small>/mo</small>
                        </div>
                        <div class="rating">
                            <i class="bi bi-star"></i>
                            <i class="bi bi-star"></i>
                            <i class="bi bi-star"></i>
                            <i class="bi bi-star"></i>
                            <i class="bi bi-star"></i>
                            <span>New</span>
                        </div>
                    </div>
                    
                    <div class="mt-2 d-flex gap-1">
                        <button class="btn btn-sm btn-outline-primary flex-grow-1" onclick="viewProperty(5)">
                            <i class="bi bi-eye"></i> View
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" onclick="editProperty(5)">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteProperty(5)">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Property Card 6 -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="property-card">
                <div class="property-image">
                    <img src="https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=400" alt="Metro Suites">
                    <span class="property-badge inactive">Inactive</span>
                </div>
                <div class="property-content">
                    <h6 class="property-title">Metro Suites</h6>
                    <div class="property-location">
                        <i class="bi bi-geo-alt-fill"></i>
                        987 Metro Ave, Manila
                    </div>
                    
                    <div class="owner-info">
                        <div class="owner-avatar-small">FT</div>
                        <div class="owner-details">
                            <div class="owner-name">Francis Tan</div>
                            <div class="owner-contact">francis@email.com</div>
                        </div>
                    </div>
                    
                    <div class="property-stats">
                        <div class="stat-item">
                            <div class="stat-number">28</div>
                            <div class="stat-label">Rooms</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">0</div>
                            <div class="stat-label">Occupied</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">0%</div>
                            <div class="stat-label">Rate</div>
                        </div>
                    </div>
                    
                    <div class="property-footer">
                        <div class="price-range">
                            ₱4,000 - ₱7,500 <small>/mo</small>
                        </div>
                        <div class="rating">
                            <i class="bi bi-star"></i>
                            <i class="bi bi-star"></i>
                            <i class="bi bi-star"></i>
                            <i class="bi bi-star"></i>
                            <i class="bi bi-star"></i>
                            <span>Closed</span>
                        </div>
                    </div>
                    
                    <div class="mt-2 d-flex gap-1">
                        <button class="btn btn-sm btn-outline-primary flex-grow-1" onclick="viewProperty(6)">
                            <i class="bi bi-eye"></i> View
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" onclick="editProperty(6)">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteProperty(6)">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- List View (Hidden by default) -->
    <div class="table-container" id="listView" style="display: none;">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Property</th>
                        <th>Owner</th>
                        <th>Location</th>
                        <th>Rooms</th>
                        <th>Occupied</th>
                        <th>Rate</th>
                        <th>Price Range</th>
                        <th>Status</th>
                        <th>Rating</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=100" class="property-table-image" alt="Sunset">
                                <div>
                                    <div class="fw-semibold">Sunset Residences</div>
                                    <small class="text-muted">Added Jan 15, 2024</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="owner-avatar-small">JD</div>
                                <div>Juan Dela Cruz</div>
                            </div>
                        </td>
                        <td>Manila</td>
                        <td>24</td>
                        <td>18</td>
                        <td>75%</td>
                        <td>₱3.5k - ₱6.5k</td>
                        <td><span class="badge-status active">Active</span></td>
                        <td>4.5</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-outline-primary" title="View"><i class="bi bi-eye"></i></button>
                                <button class="btn btn-sm btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=100" class="property-table-image" alt="Green Heights">
                                <div>
                                    <div class="fw-semibold">Green Heights</div>
                                    <small class="text-muted">Added Feb 3, 2024</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="owner-avatar-small">MS</div>
                                <div>Maria Santos</div>
                            </div>
                        </td>
                        <td>Quezon City</td>
                        <td>18</td>
                        <td>15</td>
                        <td>83%</td>
                        <td>₱3.2k - ₱5.8k</td>
                        <td><span class="badge-status active">Active</span></td>
                        <td>4.0</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="small text-muted">
            Showing 1 to 6 of 24 properties
        </div>
        <nav>
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item disabled"><a class="page-link" href="#">Prev</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">4</a></li>
                <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
        </nav>
    </div>
</div>

<!-- Add/Edit Boarding House Modal -->
<div class="modal fade" id="addBoardingHouseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Boarding House</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addBoardingHouseForm">
                    <!-- Basic Information -->
                    <div class="property-form-section">
                        <h6><i class="bi bi-info-circle"></i> Basic Information</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Property Name</label>
                                <input type="text" class="form-control" name="name" placeholder="e.g., Sunset Residences" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Select Owner</label>
                                <select class="form-select" name="owner_id" required>
                                    <option value="">Choose owner...</option>
                                    <option value="1">Juan Dela Cruz</option>
                                    <option value="2">Maria Santos</option>
                                    <option value="3">Pedro Reyes</option>
                                    <option value="4">Ana Lim</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Property Type</label>
                                <select class="form-select" name="type">
                                    <option value="dormitory">Dormitory</option>
                                    <option value="apartment">Apartment</option>
                                    <option value="bedspace">Bedspace</option>
                                    <option value="condo">Condo Unit</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Year Built</label>
                                <input type="number" class="form-control" name="year_built" placeholder="2020">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Total Floors</label>
                                <input type="number" class="form-control" name="total_floors" min="1" value="3">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Location -->
                    <div class="property-form-section">
                        <h6><i class="bi bi-geo-alt"></i> Location</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Street Address</label>
                                <input type="text" class="form-control" name="street" placeholder="123 Main St" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Barangay</label>
                                <input type="text" class="form-control" name="barangay" placeholder="Barangay" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">City</label>
                                <input type="text" class="form-control" name="city" placeholder="Manila" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Province</label>
                                <input type="text" class="form-control" name="province" value="Metro Manila">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Zip Code</label>
                                <input type="text" class="form-control" name="zip_code" placeholder="1000">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Google Maps Link</label>
                                <input type="url" class="form-control" name="map_link" placeholder="https://maps.google.com/...">
                            </div>
                            <div class="col-12">
                                <div class="map-preview">
                                    <i class="bi bi-map me-2"></i> Map preview will appear here
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contact Information -->
                    <div class="property-form-section">
                        <h6><i class="bi bi-telephone"></i> Contact Information</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" name="phone" placeholder="+63 912 345 6789">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control" name="email" placeholder="property@email.com">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Website (Optional)</label>
                                <input type="url" class="form-control" name="website" placeholder="https://...">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Property Details -->
                    <div class="property-form-section">
                        <h6><i class="bi bi-building"></i> Property Details</h6>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Total Rooms</label>
                                <input type="number" class="form-control" name="total_rooms" min="1" value="10">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Min Price (₱)</label>
                                <input type="number" class="form-control" name="min_price" placeholder="3000">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Max Price (₱)</label>
                                <input type="number" class="form-control" name="max_price" placeholder="8000">
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
                    
                    <!-- Amenities -->
                    <div class="property-form-section">
                        <h6><i class="bi bi-grid"></i> Amenities & Features</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="amenities[]" value="wifi">
                                    <label class="form-check-label">Free WiFi</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="amenities[]" value="cctv">
                                    <label class="form-check-label">CCTV Security</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="amenities[]" value="parking">
                                    <label class="form-check-label">Parking</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="amenities[]" value="elevator">
                                    <label class="form-check-label">Elevator</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="amenities[]" value="generator">
                                    <label class="form-check-label">Generator</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="amenities[]" value="water">
                                    <label class="form-check-label">Water Tank</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="amenities[]" value="laundry">
                                    <label class="form-check-label">Laundry Area</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="amenities[]" value="kitchen">
                                    <label class="form-check-label">Common Kitchen</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="amenities[]" value="curfew">
                                    <label class="form-check-label">No Curfew</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Images -->
                    <div class="property-form-section">
                        <h6><i class="bi bi-images"></i> Property Images</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Main Image</label>
                                <input type="file" class="form-control" name="main_image" accept="image/*">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Additional Images</label>
                                <input type="file" class="form-control" name="additional_images[]" multiple accept="image/*">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div class="property-form-section">
                        <h6><i class="bi bi-file-text"></i> Description</h6>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Property Description</label>
                                <textarea class="form-control" name="description" rows="4" placeholder="Describe the property, its features, location benefits..."></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Rules & Policies</label>
                                <textarea class="form-control" name="rules" rows="3" placeholder="e.g., No smoking, quiet hours, guest policy..."></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveBoardingHouse()">Save Boarding House</button>
            </div>
        </div>
    </div>
</div>

<!-- View Boarding House Modal -->
<div class="modal fade" id="viewBoardingHouseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Boarding House Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5">
                        <div id="propertyCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner rounded-3">
                                <div class="carousel-item active">
                                    <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=600" class="d-block w-100" alt="Property">
                                </div>
                                <div class="carousel-item">
                                    <img src="https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=600" class="d-block w-100" alt="Property">
                                </div>
                                <div class="carousel-item">
                                    <img src="https://images.unsplash.com/photo-1575517111478-7f6afd0973db?w=600" class="d-block w-100" alt="Property">
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#propertyCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#propertyCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        </div>
                        
                        <div class="mt-3">
                            <h6>Amenities</h6>
                            <div class="d-flex flex-wrap gap-1">
                                <span class="badge bg-light text-dark p-2"><i class="bi bi-wifi text-primary me-1"></i>WiFi</span>
                                <span class="badge bg-light text-dark p-2"><i class="bi bi-camera-video text-primary me-1"></i>CCTV</span>
                                <span class="badge bg-light text-dark p-2"><i class="bi bi-car-front text-primary me-1"></i>Parking</span>
                                <span class="badge bg-light text-dark p-2"><i class="bi bi-arrow-up text-primary me-1"></i>Elevator</span>
                                <span class="badge bg-light text-dark p-2"><i class="bi bi-lightning text-primary me-1"></i>Generator</span>
                                <span class="badge bg-light text-dark p-2"><i class="bi bi-droplet text-primary me-1"></i>Water Tank</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-7">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h3>Sunset Residences</h3>
                                <p class="text-muted mb-1">
                                    <i class="bi bi-geo-alt-fill me-1"></i> 123 Sunset Blvd, Manila
                                </p>
                                <div>
                                    <span class="badge-status active">Active</span>
                                    <span class="badge bg-light text-dark ms-2">Dormitory</span>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="rating mb-1">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-half text-warning"></i>
                                    <span class="ms-1">4.5</span>
                                </div>
                                <small class="text-muted">24 reviews</small>
                            </div>
                        </div>
                        
                        <div class="row g-3 mb-3">
                            <div class="col-4">
                                <div class="bg-light p-2 rounded text-center">
                                    <small class="text-muted d-block">Total Rooms</small>
                                    <span class="fw-bold fs-5">24</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="bg-light p-2 rounded text-center">
                                    <small class="text-muted d-block">Occupied</small>
                                    <span class="fw-bold fs-5">18</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="bg-light p-2 rounded text-center">
                                    <small class="text-muted d-block">Available</small>
                                    <span class="fw-bold fs-5">6</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <h6>Owner Information</h6>
                            <div class="d-flex align-items-center gap-3 p-2 bg-light rounded">
                                <div class="owner-avatar-small" style="width: 40px; height: 40px; font-size: 1rem;">JD</div>
                                <div>
                                    <div class="fw-semibold">Juan Dela Cruz</div>
                                    <div class="small text-muted">
                                        <i class="bi bi-envelope me-1"></i> juan@email.com |
                                        <i class="bi bi-phone me-1 ms-2"></i> +63 912 345 6789
                                    </div>
                                </div>
                                <button class="btn btn-sm btn-outline-primary ms-auto" onclick="viewOwner(1)">
                                    View Profile
                                </button>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <h6>Contact Information</h6>
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="border rounded p-2">
                                        <small class="text-muted d-block">Phone</small>
                                        <span>+63 912 345 6789</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="border rounded p-2">
                                        <small class="text-muted d-block">Email</small>
                                        <span>sunset@stay
                                                                                <span>sunset@stay ease.com</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <h6>Description</h6>
                            <p class="small text-muted">Sunset Residences is a modern dormitory located in the heart of Manila. Perfect for students and young professionals, our property offers comfortable rooms with complete amenities, 24/7 security, and easy access to schools, offices, and commercial establishments.</p>
                        </div>
                        
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="border rounded p-2">
                                    <small class="text-muted d-block">Price Range</small>
                                    <span class="fw-bold text-success">₱3,500 - ₱6,500 /month</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-2">
                                    <small class="text-muted d-block">Year Built</small>
                                    <span>2020 • 3 floors</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <h6>Quick Actions</h6>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-primary" onclick="viewRooms(1)">
                                    <i class="bi bi-door-open me-1"></i> View Rooms
                                </button>
                                <button class="btn btn-sm btn-outline-success" onclick="viewPayments(1)">
                                    <i class="bi bi-cash me-1"></i> Payments
                                </button>
                                <button class="btn btn-sm btn-outline-info" onclick="viewOccupants(1)">
                                    <i class="bi bi-people me-1"></i> Occupants
                                </button>
                                <button class="btn btn-sm btn-outline-warning" onclick="viewMaintenance(1)">
                                    <i class="bi bi-tools me-1"></i> Maintenance
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="editProperty(1)">Edit Property</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize date pickers
        flatpickr("#dateFrom", {
            dateFormat: "Y-m-d",
            allowInput: true
        });
        
        flatpickr("#dateTo", {
            dateFormat: "Y-m-d",
            allowInput: true
        });

        // Search functionality
        $('#searchProperty').on('keyup', function() {
            const searchTerm = $(this).val().toLowerCase();
            filterProperties();
        });

        // Filter by status
        $('#filterStatus').on('change', function() {
            filterProperties();
        });

        // Filter by owner
        $('#filterOwner').on('change', function() {
            filterProperties();
        });

        // Filter by city
        $('#filterCity').on('change', function() {
            filterProperties();
        });
    });

    // View toggle function
    function toggleView(view) {
        if (view === 'grid') {
            $('#gridView').show();
            $('#listView').hide();
            $('#gridViewBtn').addClass('active');
            $('#listViewBtn').removeClass('active');
        } else {
            $('#gridView').hide();
            $('#listView').show();
            $('#listViewBtn').addClass('active');
            $('#gridViewBtn').removeClass('active');
        }
    }

    // Filter properties function
    function filterProperties() {
        const searchTerm = $('#searchProperty').val().toLowerCase();
        const status = $('#filterStatus').val();
        const owner = $('#filterOwner').val();
        const city = $('#filterCity').val();

        $('.property-card').each(function() {
            let show = true;
            const card = $(this);
            const title = card.find('.property-title').text().toLowerCase();
            const location = card.find('.property-location').text().toLowerCase();
            const cardStatus = card.find('.property-badge').text().toLowerCase();
            const cardOwner = card.find('.owner-name').text().toLowerCase();
            const cardCity = location.includes('manila') ? 'manila' : 
                            location.includes('quezon') ? 'quezon' :
                            location.includes('makati') ? 'makati' : '';

            // Search filter
            if (searchTerm && !title.includes(searchTerm) && !location.includes(searchTerm)) {
                show = false;
            }

            // Status filter
            if (status && !cardStatus.includes(status)) {
                show = false;
            }

            // Owner filter (simplified - in real app, use owner IDs)
            if (owner && owner !== '') {
                const ownerNames = ['juan', 'maria', 'pedro', 'ana'];
                if (!cardOwner.includes(ownerNames[owner-1])) {
                    show = false;
                }
            }

            // City filter
            if (city && !cardCity.includes(city)) {
                show = false;
            }

            if (show) {
                card.parent().show();
            } else {
                card.parent().hide();
            }
        });
    }

    // Sort function
    function sortBy(type) {
        const grid = $('#gridView');
        const cards = grid.children('.col-xl-3').toArray();

        cards.sort(function(a, b) {
            const aCard = $(a).find('.property-card');
            const bCard = $(b).find('.property-card');
            
            switch(type) {
                case 'name':
                    const aName = aCard.find('.property-title').text();
                    const bName = bCard.find('.property-title').text();
                    return aName.localeCompare(bName);
                
                case 'newest':
                    // Simplified - in real app, use actual dates
                    return -1;
                
                case 'oldest':
                    return 1;
                
                case 'occupancy':
                    const aOcc = parseInt(aCard.find('.stat-item:eq(1) .stat-number').text());
                    const bOcc = parseInt(bCard.find('.stat-item:eq(1) .stat-number').text());
                    return bOcc - aOcc;
                
                case 'revenue':
                    const aPrice = parseInt(aCard.find('.price-range').text().replace(/[₱,k]/g, ''));
                    const bPrice = parseInt(bCard.find('.price-range').text().replace(/[₱,k]/g, ''));
                    return bPrice - aPrice;
            }
        });

        grid.empty().append(cards);
    }

    // Export data function
    function exportData(type) {
        showToast('info', `Exporting as ${type.toUpperCase()}...`);
        setTimeout(() => {
            showToast('success', 'Export completed successfully');
        }, 2000);
    }

    // View property function
    function viewProperty(id) {
        $('#viewBoardingHouseModal').modal('show');
    }

    // Edit property function
    function editProperty(id) {
        $('#addBoardingHouseModal').modal('show');
        showToast('info', 'Loading property data...');
    }

    // Delete property function
    function deleteProperty(id) {
        Swal.fire({
            title: 'Delete Boarding House?',
            text: "This action cannot be undone. All rooms and associated data will also be deleted.",
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
                        'Boarding house has been deleted successfully.',
                        'success'
                    );
                }, 1500);
            }
        });
    }

    // Save boarding house function
    function saveBoardingHouse() {
        // Validate form
        const form = document.getElementById('addBoardingHouseForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        showLoading();
        
        // Simulate API call
        setTimeout(() => {
            hideLoading();
            $('#addBoardingHouseModal').modal('hide');
            form.reset();
            
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Boarding house has been added successfully.',
                timer: 2000,
                showConfirmButton: false
            });
        }, 1500);
    }

    // View owner function
    function viewOwner(id) {
        showToast('info', 'Loading owner details...');
        setTimeout(() => {
            window.location.href = '/admin/owners/' + id;
        }, 1000);
    }

    // View rooms function
    function viewRooms(id) {
        showToast('info', 'Loading rooms...');
        setTimeout(() => {
            window.location.href = '/admin/rooms?property_id=' + id;
        }, 1000);
    }

    // View payments function
    function viewPayments(id) {
        showToast('info', 'Loading payment history...');
        setTimeout(() => {
            window.location.href = '/admin/payments?property_id=' + id;
        }, 1000);
    }

    // View occupants function
    function viewOccupants(id) {
        showToast('info', 'Loading occupants...');
        setTimeout(() => {
            window.location.href = '/admin/occupants?property_id=' + id;
        }, 1000);
    }

    // View maintenance function
    function viewMaintenance(id) {
        showToast('info', 'Loading maintenance requests...');
        setTimeout(() => {
            window.location.href = '/admin/maintenance?property_id=' + id;
        }, 1000);
    }

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
</script>
@endsection