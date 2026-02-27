@extends('layouts.admin')

@section('title', 'Rooms Management - StayEase Admin')

@section('page_header', 'Rooms Management')

@section('page_description', 'Manage all boarding house rooms across properties')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Rooms</li>
@endsection

@section('header_actions')
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoomModal">
        <i class="bi bi-plus-circle me-2"></i>Add New Room
    </button>
    <div class="btn-group ms-2">
        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
            <i class="bi bi-download me-2"></i>Export
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="#" onclick="exportRooms('pdf')"><i class="bi bi-file-pdf me-2"></i>PDF</a></li>
            <li><a class="dropdown-item" href="#" onclick="exportRooms('excel')"><i class="bi bi-file-excel me-2"></i>Excel</a></li>
            <li><a class="dropdown-item" href="#" onclick="exportRooms('csv')"><i class="bi bi-file-text me-2"></i>CSV</a></li>
        </ul>
    </div>
@endsection

@section('styles')
<style>
    /* Room Stats Cards */
    .room-stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.03);
        border: 1px solid rgba(0,0,0,0.03);
        height: 100%;
        transition: all 0.3s;
    }
    
    .room-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.1);
        border-color: rgba(102, 126, 234, 0.15);
    }
    
    .room-stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }
    
    .room-stat-icon.primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .room-stat-icon.success {
        background: linear-gradient(135deg, #34b1aa 0%, #2c9a94 100%);
    }
    
    .room-stat-icon.warning {
        background: linear-gradient(135deg, #f6b23e 0%, #f4a51e 100%);
    }
    
    .room-stat-icon.info {
        background: linear-gradient(135deg, #3b7cff 0%, #2b6ef0 100%);
    }
    
    .room-stat-value {
        font-size: 1.6rem;
        font-weight: 700;
        color: #2d3748;
        line-height: 1.2;
    }
    
    .room-stat-label {
        color: #718096;
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    .room-stat-change {
        font-size: 0.75rem;
        padding: 0.2rem 0.4rem;
        border-radius: 16px;
        background: #f0fff4;
        color: #2ecc71;
    }
    
    .room-stat-change.negative {
        background: #fff5f5;
        color: #e74c3c;
    }
    
    /* Room Table Styles */
    .room-image-thumb {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        object-fit: cover;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
    }
    
    .room-info h6 {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.2rem;
        font-size: 0.95rem;
    }
    
    .room-info small {
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
    
    .room-type-badge {
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
    
    .badge-status.available {
        background: #e6f7e6;
        color: #27ae60;
    }
    
    .badge-status.occupied {
        background: #fff3e0;
        color: #f39c12;
    }
    
    .badge-status.maintenance {
        background: #fee9e9;
        color: #e74c3c;
    }
    
    .badge-status.reserved {
        background: #e3f2fd;
        color: #3498db;
    }
    
    .amenity-tag {
        background: #edf2f7;
        color: #4a5568;
        padding: 0.15rem 0.4rem;
        border-radius: 4px;
        font-size: 0.6rem;
        display: inline-block;
        margin-right: 0.2rem;
        margin-bottom: 0.2rem;
    }
    
    .price-tag {
        font-weight: 700;
        color: #2d3748;
        font-size: 0.95rem;
    }
    
    .price-tag small {
        font-weight: 400;
        color: #718096;
        font-size: 0.7rem;
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
    
    /* Room Type Cards */
    .room-type-stats {
        background: white;
        border-radius: 16px;
        padding: 1rem;
        border: 1px solid rgba(0,0,0,0.03);
        margin-bottom: 1.5rem;
    }
    
    .type-stat-item {
        text-align: center;
        padding: 0.75rem;
        border-right: 1px solid #edf2f7;
    }
    
    .type-stat-item:last-child {
        border-right: none;
    }
    
    .type-stat-value {
        font-size: 1.1rem;
        font-weight: 700;
        color: #2d3748;
    }
    
    .type-stat-label {
        font-size: 0.7rem;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    .type-stat-label.standard { color: #667eea; }
    .type-stat-label.deluxe { color: #9c27b0; }
    .type-stat-label.suite { color: #f39c12; }
    
    /* Quick Stats */
    .quick-stat {
        text-align: center;
        padding: 0.5rem 1rem;
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
    
    /* Room Gallery */
    .room-gallery {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.5rem;
        margin-top: 1rem;
    }
    
    .gallery-item {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        aspect-ratio: 1;
    }
    
    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .gallery-item .overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0,0,0,0.5);
        color: white;
        padding: 0.2rem;
        font-size: 0.6rem;
        text-align: center;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .room-stat-value {
            font-size: 1.3rem;
        }
        
        .room-stat-icon {
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
        }
        
        .filter-section {
            padding: 1rem;
        }
        
        .room-gallery {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .quick-stat {
            border-right: none;
            border-bottom: 1px solid #edf2f7;
            padding: 0.5rem;
        }
        
        .quick-stat:last-child {
            border-bottom: none;
        }
        
        .type-stat-item {
            border-right: none;
            border-bottom: 1px solid #edf2f7;
        }
        
        .type-stat-item:last-child {
            border-bottom: none;
        }
    }
    
    /* Modal Styles */
    .room-form-section {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .room-form-section h6 {
        font-size: 0.9rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .room-form-section h6 i {
        color: #667eea;
    }
    
    .amenities-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.5rem;
    }
    
    .amenity-checkbox {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
    }
    
    /* Image Upload */
    .image-upload-area {
        border: 2px dashed #edf2f7;
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        background: #f8fafc;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .image-upload-area:hover {
        border-color: #667eea;
        background: #f0f4ff;
    }
    
    .image-upload-area i {
        font-size: 2rem;
        color: #667eea;
        margin-bottom: 0.5rem;
    }
    
    .image-upload-area p {
        font-size: 0.85rem;
        color: #718096;
        margin-bottom: 0;
    }
    
    .image-preview {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.5rem;
        margin-top: 1rem;
    }
    
    .preview-item {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        aspect-ratio: 1;
    }
    
    .preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .preview-item .remove {
        position: absolute;
        top: 2px;
        right: 2px;
        background: rgba(255,255,255,0.8);
        border: none;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        cursor: pointer;
    }
</style>
@endsection

@section('content')
<div class="fade-in">
    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="room-stat-card">
                <div class="d-flex align-items-center">
                    <div class="room-stat-icon primary me-3">
                        <i class="bi bi-door-open"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="room-stat-value">384</div>
                        <div class="room-stat-label">Total Rooms</div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Across 8 boarding houses</span>
                        <span class="room-stat-change">
                            <i class="bi bi-arrow-up"></i> +12 this month
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="room-stat-card">
                <div class="d-flex align-items-center">
                    <div class="room-stat-icon success me-3">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="room-stat-value">312</div>
                        <div class="room-stat-label">Occupied Rooms</div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">81% occupancy rate</span>
                        <span class="room-stat-change">
                            <i class="bi bi-arrow-up"></i> +5%
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="room-stat-card">
                <div class="d-flex align-items-center">
                    <div class="room-stat-icon warning me-3">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="room-stat-value">45</div>
                        <div class="room-stat-label">Available Rooms</div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Ready for occupancy</span>
                        <span class="room-stat-change">
                            <i class="bi bi-arrow-down"></i> -3
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="room-stat-card">
                <div class="d-flex align-items-center">
                    <div class="room-stat-icon info me-3">
                        <i class="bi bi-tools"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="room-stat-value">27</div>
                        <div class="room-stat-label">Maintenance</div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Under repair</span>
                        <span class="room-stat-change negative">
                            <i class="bi bi-arrow-up"></i> +4
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Room Type Distribution -->
    <div class="room-type-stats">
        <div class="row g-0">
            <div class="col-4 col-md-2">
                <div class="type-stat-item">
                    <div class="type-stat-value">156</div>
                    <div class="type-stat-label standard">Standard</div>
                </div>
            </div>
            <div class="col-4 col-md-2">
                <div class="type-stat-item">
                    <div class="type-stat-value">98</div>
                    <div class="type-stat-label deluxe">Deluxe</div>
                </div>
            </div>
            <div class="col-4 col-md-2">
                <div class="type-stat-item">
                    <div class="type-stat-value">45</div>
                    <div class="type-stat-label suite">Suite</div>
                </div>
            </div>
            <div class="col-4 col-md-2">
                <div class="type-stat-item">
                    <div class="type-stat-value">85</div>
                    <div class="type-stat-label">Single</div>
                </div>
            </div>
            <div class="col-4 col-md-2">
                <div class="type-stat-item">
                    <div class="type-stat-value">120</div>
                    <div class="type-stat-label">Double</div>
                </div>
            </div>
            <div class="col-4 col-md-2">
                <div class="type-stat-item">
                    <div class="type-stat-value">179</div>
                    <div class="type-stat-label">Shared</div>
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
                    <input type="text" class="form-control border-0 bg-light" id="searchRoom" placeholder="Room #, property...">
                </div>
            </div>
            <div class="col-lg-2 col-md-4">
                <div class="filter-label">Boarding House</div>
                <select class="form-select bg-light border-0" id="filterProperty">
                    <option value="">All Properties</option>
                    <option value="sunset">Sunset Residences</option>
                    <option value="green">Green Heights</option>
                    <option value="bayview">Bayview Tower</option>
                    <option value="city">City Lights</option>
                    <option value="garden">Garden Heights</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-4">
                <div class="filter-label">Status</div>
                <select class="form-select bg-light border-0" id="filterStatus">
                    <option value="">All Status</option>
                    <option value="available">Available</option>
                    <option value="occupied">Occupied</option>
                    <option value="maintenance">Maintenance</option>
                    <option value="reserved">Reserved</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-4">
                <div class="filter-label">Room Type</div>
                <select class="form-select bg-light border-0" id="filterType">
                    <option value="">All Types</option>
                    <option value="standard">Standard</option>
                    <option value="deluxe">Deluxe</option>
                    <option value="suite">Suite</option>
                    <option value="single">Single</option>
                    <option value="double">Double</option>
                    <option value="shared">Shared</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-4">
                <div class="filter-label">Price Range</div>
                <select class="form-select bg-light border-0" id="filterPrice">
                    <option value="">All Prices</option>
                    <option value="0-3000">Below ₱3,000</option>
                    <option value="3000-5000">₱3,000 - ₱5,000</option>
                    <option value="5000-8000">₱5,000 - ₱8,000</option>
                    <option value="8000-10000">₱8,000 - ₱10,000</option>
                    <option value="10000+">Above ₱10,000</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-4">
                <div class="filter-label">Floor</div>
                <select class="form-select bg-light border-0" id="filterFloor">
                    <option value="">All Floors</option>
                    <option value="1">1st Floor</option>
                    <option value="2">2nd Floor</option>
                    <option value="3">3rd Floor</option>
                    <option value="4">4th Floor</option>
                    <option value="5">5th Floor</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Rooms Table -->
    <div class="table-container">
        <div class="table-header">
            <h5 class="table-title">Rooms List</h5>
            <div class="d-flex align-items-center gap-3">
                <div class="quick-stats d-flex">
                    <div class="quick-stat">
                        <div class="quick-stat-value">312</div>
                        <div class="quick-stat-label">Occupied</div>
                    </div>
                    <div class="quick-stat">
                        <div class="quick-stat-value">45</div>
                        <div class="quick-stat-label">Available</div>
                    </div>
                    <div class="quick-stat">
                        <div class="quick-stat-value">27</div>
                        <div class="quick-stat-label">Maintenance</div>
                    </div>
                </div>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-gear"></i> Bulk Actions
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="bulkAction('status-available')"><i class="bi bi-check-circle me-2"></i>Set Available</a></li>
                        <li><a class="dropdown-item" href="#" onclick="bulkAction('status-maintenance')"><i class="bi bi-tools me-2"></i>Set Maintenance</a></li>
                        <li><a class="dropdown-item" href="#" onclick="bulkAction('export')"><i class="bi bi-download me-2"></i>Export Selected</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#" onclick="bulkAction('delete')"><i class="bi bi-trash me-2"></i>Delete Selected</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover" id="roomsTable">
                <thead>
                    <tr>
                        <th width="40">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAll">
                            </div>
                        </th>
                        <th>Room</th>
                        <th>Property</th>
                        <th>Type</th>
                        <th>Floor</th>
                        <th>Capacity</th>
                        <th>Price/Month</th>
                        <th>Status</th>
                        <th>Amenities</th>
                        <th>Current Occupant</th>
                        <th>Last Maintained</th>
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
                                <div class="room-image-thumb">
                                    <i class="bi bi-door-open"></i>
                                </div>
                                <div class="room-info">
                                    <h6>Room 204</h6>
                                    <small><i class="bi bi-building me-1"></i>Sunset Residences</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="property-badge">Sunset Residences</div>
                            <small class="text-muted d-block">Owner: Juan Dela Cruz</small>
                        </td>
                        <td>
                            <span class="room-type-badge">Deluxe</span>
                        </td>
                        <td>2nd Floor</td>
                        <td>4 persons</td>
                        <td>
                            <div class="price-tag">₱4,500 <small>/month</small></div>
                        </td>
                        <td>
                            <span class="badge-status occupied">Occupied</span>
                        </td>
                        <td>
                            <span class="amenity-tag"><i class="bi bi-wifi"></i> WiFi</span>
                            <span class="amenity-tag"><i class="bi bi-snow"></i> AC</span>
                            <span class="amenity-tag"><i class="bi bi-droplet"></i> CR</span>
                            <span class="amenity-tag"><i class="bi bi-tv"></i> TV</span>
                            <span class="amenity-tag">+3</span>
                        </td>
                        <td>
                            <div>
                                <div class="fw-semibold">John Doe</div>
                                <small class="text-muted">Since Jan 15, 2026</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div>Feb 1, 2026</div>
                                <small class="text-muted">2 weeks ago</small>
                            </div>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-outline-primary" title="View" onclick="viewRoom(1)">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-secondary" title="Edit" onclick="editRoom(1)">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-info" title="Occupant" onclick="viewOccupant(1)">
                                    <i class="bi bi-person"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-success" title="Payment History" onclick="viewPayments(1)">
                                    <i class="bi bi-cash"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-warning" title="Maintenance" onclick="maintenanceHistory(1)">
                                    <i class="bi bi-tools"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" title="Delete" onclick="deleteRoom(1)">
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
                                <div class="room-image-thumb" style="background: linear-gradient(135deg, #34b1aa 0%, #2c9a94 100%);">
                                    <i class="bi bi-door-open"></i>
                                </div>
                                <div class="room-info">
                                    <h6>Room 305</h6>
                                    <small><i class="bi bi-building me-1"></i>Green Heights</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="property-badge">Green Heights</div>
                            <small class="text-muted d-block">Owner: Maria Santos</small>
                        </td>
                        <td>
                            <span class="room-type-badge">Standard</span>
                        </td>
                        <td>3rd Floor</td>
                        <td>2 persons</td>
                        <td>
                            <div class="price-tag">₱3,200 <small>/month</small></div>
                        </td>
                        <td>
                            <span class="badge-status available">Available</span>
                        </td>
                        <td>
                            <span class="amenity-tag"><i class="bi bi-wifi"></i> WiFi</span>
                            <span class="amenity-tag"><i class="bi bi-droplet"></i> CR</span>
                            <span class="amenity-tag"><i class="bi bi-fan"></i> Fan</span>
                        </td>
                        <td>
                            <div class="text-muted">—</div>
                        </td>
                        <td>
                            <div>
                                <div>Jan 15, 2026</div>
                                <small class="text-muted">1 month ago</small>
                            </div>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-sm btn-outline-info"><i class="bi bi-person"></i></button>
                                <button class="btn btn-sm btn-outline-success"><i class="bi bi-cash"></i></button>
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
                                <div class="room-image-thumb" style="background: linear-gradient(135deg, #f6b23e 0%, #f4a51e 100%);">
                                    <i class="bi bi-door-open"></i>
                                </div>
                                <div class="room-info">
                                    <h6>Room 101</h6>
                                    <small><i class="bi bi-building me-1"></i>Bayview Tower</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="property-badge">Bayview Tower</div>
                            <small class="text-muted d-block">Owner: Pedro Reyes</small>
                        </td>
                        <td>
                            <span class="room-type-badge">Suite</span>
                        </td>
                        <td>1st Floor</td>
                        <td>6 persons</td>
                        <td>
                            <div class="price-tag">₱8,500 <small>/month</small></div>
                        </td>
                        <td>
                            <span class="badge-status reserved">Reserved</span>
                        </td>
                        <td>
                            <span class="amenity-tag"><i class="bi bi-wifi"></i> WiFi</span>
                            <span class="amenity-tag"><i class="bi bi-snow"></i> AC</span>
                            <span class="amenity-tag"><i class="bi bi-droplet"></i> CR</span>
                            <span class="amenity-tag"><i class="bi bi-tv"></i> TV</span>
                            <span class="amenity-tag"><i class="bi bi-fridge"></i> Ref</span>
                            <span class="amenity-tag"><i class="bi bi-cup"></i> Kitchen</span>
                        </td>
                        <td>
                            <div>
                                <div class="fw-semibold">Maria Santos</div>
                                <small class="text-muted">Moves in Mar 1, 2026</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div>Feb 10, 2026</div>
                                <small class="text-muted">1 week ago</small>
                            </div>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-sm btn-outline-info"><i class="bi bi-person"></i></button>
                                <button class="btn btn-sm btn-outline-success"><i class="bi bi-cash"></i></button>
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
                                <div class="room-image-thumb" style="background: linear-gradient(135deg, #3b7cff 0%, #2b6ef0 100%);">
                                    <i class="bi bi-door-open"></i>
                                </div>
                                <div class="room-info">
                                    <h6>Room 412</h6>
                                    <small><i class="bi bi-building me-1"></i>City Lights</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="property-badge">City Lights</div>
                            <small class="text-muted d-block">Owner: Ana Lim</small>
                        </td>
                        <td>
                            <span class="room-type-badge">Standard</span>
                        </td>
                        <td>4th Floor</td>
                        <td>4 persons</td>
                        <td>
                            <div class="price-tag">₱4,000 <small>/month</small></div>
                        </td>
                        <td>
                            <span class="badge-status maintenance">Maintenance</span>
                        </td>
                        <td>
                            <span class="amenity-tag"><i class="bi bi-wifi"></i> WiFi</span>
                            <span class="amenity-tag"><i class="bi bi-droplet"></i> CR</span>
                            <span class="amenity-tag"><i class="bi bi-fan"></i> Fan</span>
                        </td>
                        <td>
                            <div class="text-muted">—</div>
                        </td>
                        <td>
                            <div>
                                <div>Feb 20, 2026</div>
                                <small class="text-muted">5 days ago</small>
                            </div>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-sm btn-outline-info"><i class="bi bi-person"></i></button>
                                <button class="btn btn-sm btn-outline-success"><i class="bi bi-cash"></i></button>
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
                Showing 4 of <span id="totalRooms">384</span> rooms
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

<!-- Add Room Modal -->
<div class="modal fade" id="addRoomModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Room</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addRoomForm">
                    <!-- Basic Information -->
                    <div class="room-form-section">
                        <h6><i class="bi bi-info-circle"></i> Basic Information</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Select Property</label>
                                <select class="form-select" name="property_id" required>
                                    <option value="">Choose property...</option>
                                    <option value="1">Sunset Residences - Juan Dela Cruz</option>
                                    <option value="2">Green Heights - Maria Santos</option>
                                    <option value="3">Bayview Tower - Pedro Reyes</option>
                                    <option value="4">City Lights - Ana Lim</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Room Number</label>
                                <input type="text" class="form-control" name="room_number" placeholder="e.g., 204" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Floor</label>
                                <select class="form-select" name="floor">
                                    <option value="1">1st Floor</option>
                                    <option value="2">2nd Floor</option>
                                    <option value="3">3rd Floor</option>
                                    <option value="4">4th Floor</option>
                                    <option value="5">5th Floor</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Room Type</label>
                                <select class="form-select" name="room_type" required>
                                    <option value="">Select type...</option>
                                    <option value="standard">Standard</option>
                                    <option value="deluxe">Deluxe</option>
                                    <option value="suite">Suite</option>
                                    <option value="single">Single</option>
                                    <option value="double">Double</option>
                                    <option value="shared">Shared</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pricing & Capacity -->
                    <div class="room-form-section">
                        <h6><i class="bi bi-cash-stack"></i> Pricing & Capacity</h6>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Monthly Rent (₱)</label>
                                <input type="number" class="form-control" name="monthly_rent" placeholder="4500" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Security Deposit (₱)</label>
                                <input type="number" class="form-control" name="deposit" value="4500">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Capacity (persons)</label>
                                <input type="number" class="form-control" name="capacity" min="1" max="10" value="4">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Room Size (sqm)</label>
                                <input type="number" class="form-control" name="size" placeholder="25">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="available">Available</option>
                                    <option value="occupied">Occupied</option>
                                    <option value="maintenance">Maintenance</option>
                                    <option value="reserved">Reserved</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Amenities -->
                    <div class="room-form-section">
                        <h6><i class="bi bi-grid"></i> Amenities</h6>
                        <div class="amenities-grid">
                            <div class="amenity-checkbox">
                                <input type="checkbox" class="form-check-input" name="amenities[]" value="wifi">
                                <span><i class="bi bi-wifi me-1"></i> WiFi</span>
                            </div>
                            <div class="amenity-checkbox">
                                <input type="checkbox" class="form-check-input" name="amenities[]" value="ac">
                                <span><i class="bi bi-snow me-1"></i> Air Conditioning</span>
                            </div>
                            <div class="amenity-checkbox">
                                <input type="checkbox" class="form-check-input" name="amenities[]" value="cr">
                                <span><i class="bi bi-droplet me-1"></i> Own CR</span>
                            </div>
                            <div class="amenity-checkbox">
                                <input type="checkbox" class="form-check-input" name="amenities[]" value="tv">
                                <span><i class="bi bi-tv me-1"></i> Television</span>
                            </div>
                            <div class="amenity-checkbox">
                                <input type="checkbox" class="form-check-input" name="amenities[]" value="ref">
                                <span><i class="bi bi-fridge me-1"></i> Refrigerator</span>
                            </div>
                            <div class="amenity-checkbox">
                                <input type="checkbox" class="form-check-input" name="amenities[]" value="kitchen">
                                <span><i class="bi bi-cup me-1"></i> Kitchen Access</span>
                            </div>
                            <div class="amenity-checkbox">
                                <input type="checkbox" class="form-check-input" name="amenities[]" value="fan">
                                <span><i class="bi bi-fan me-1"></i> Electric Fan</span>
                            </div>
                            <div class="amenity-checkbox">
                                <input type="checkbox" class="form-check-input" name="amenities[]" value="table">
                                <span><i class="bi bi-table me-1"></i> Study Table</span>
                            </div>
                            <div class="amenity-checkbox">
                                <input type="checkbox" class="form-check-input" name="amenities[]" value="bed">
                                <span><i class="bi bi-bed me-1"></i> Bed Frame</span>
                            </div>
                            <div class="amenity-checkbox">
                                <input type="checkbox" class="form-check-input" name="amenities[]" value="cabinet">
                                <span><i class="bi bi-archive me-1"></i> Cabinet</span>
                            </div>
                            <div class="amenity-checkbox">
                                <input type="checkbox" class="form-check-input" name="amenities[]" value="water">
                                <span><i class="bi bi-droplet-half me-1"></i> Water Heater</span>
                            </div>
                            <div class="amenity-checkbox">
                                <input type="checkbox" class="form-check-input" name="amenities[]" value="balcony">
                                <span><i class="bi bi-window me-1"></i> Balcony</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Room Images -->
                    <div class="room-form-section">
                        <h6><i class="bi bi-images"></i> Room Images</h6>
                        <div class="image-upload-area" onclick="document.getElementById('roomImages').click()">
                            <i class="bi bi-cloud-upload"></i>
                            <p>Click to upload or drag and drop</p>
                            <small class="text-muted">PNG, JPG, JPEG up to 5MB</small>
                            <input type="file" id="roomImages" class="d-none" multiple accept="image/*" onchange="previewImages(this)">
                        </div>
                        <div class="image-preview" id="imagePreview"></div>
                    </div>
                    
                    <!-- Additional Details -->
                    <div class="room-form-section">
                        <h6><i class="bi bi-file-text"></i> Additional Details</h6>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Room Description</label>
                                <textarea class="form-control" name="description" rows="3" placeholder="Describe the room, its features, etc..."></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Notes (for staff)</label>
                                <textarea class="form-control" name="notes" rows="2" placeholder="Internal notes..."></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Rules & Restrictions</label>
                                <textarea class="form-control" name="rules" rows="2" placeholder="e.g., No pets, quiet hours..."></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveRoom()">Save Room</button>
            </div>
        </div>
    </div>
</div>

<!-- View Room Modal -->
<div class="modal fade" id="viewRoomModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Room Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5">
                        <div class="room-gallery">
                            <div class="gallery-item">
                                <img src="https://via.placeholder.com/150" alt="Room">
                                <div class="overlay">Main</div>
                            </div>
                            <div class="gallery-item">
                                <img src="https://via.placeholder.com/150" alt="Room">
                            </div>
                            <div class="gallery-item">
                                <img src="https://via.placeholder.com/150" alt="Room">
                            </div>
                            <div class="gallery-item">
                                <img src="https://via.placeholder.com/150" alt="Room">
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <h6 class="mb-2">Amenities</h6>
                            <div>
                                <span class="amenity-tag"><i class="bi bi-wifi"></i> WiFi</span>
                                <span class="amenity-tag"><i class="bi bi-snow"></i> AC</span>
                                <span class="amenity-tag"><i class="bi bi-droplet"></i> Own CR</span>
                                <span class="amenity-tag"><i class="bi bi-tv"></i> TV</span>
                                <span class="amenity-tag"><i class="bi bi-fridge"></i> Ref</span>
                                <span class="amenity-tag"><i class="bi bi-cup"></i> Kitchen</span>
                                <span class="amenity-tag"><i class="bi bi-bed"></i> Bed</span>
                                <span class="amenity-tag"><i class="bi bi-archive"></i> Cabinet</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-7">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h4>Room 204</h4>
                                <p class="text-muted mb-1"><i class="bi bi-building me-2"></i>Sunset Residences - 2nd Floor</p>
                                <p class="text-muted"><i class="bi bi-person me-2"></i>Owner: Juan Dela Cruz</p>
                            </div>
                            <span class="badge-status occupied">Occupied</span>
                        </div>
                        
                        <div class="row g-3 mb-3">
                            <div class="col-4">
                                <div class="bg-light p-2 rounded text-center">
                                    <small class="text-muted d-block">Type</small>
                                    <span class="fw-bold">Deluxe</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="bg-light p-2 rounded text-center">
                                    <small class="text-muted d-block">Capacity</small>
                                    <span class="fw-bold">4 persons</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="bg-light p-2 rounded text-center">
                                    <small class="text-muted d-block">Size</small>
                                    <span class="fw-bold">28 sqm</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <div class="bg-light p-2 rounded">
                                    <small class="text-muted d-block">Monthly Rent</small>
                                    <span class="fw-bold fs-5">₱4,500</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light p-2 rounded">
                                    <small class="text-muted d-block">Security Deposit</small>
                                    <span class="fw-bold">₱4,500</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <h6>Current Occupant</h6>
                            <div class="d-flex align-items-center gap-2 p-2 bg-light rounded">
                                <div class="owner-avatar">JD</div>
                                <div>
                                    <div class="fw-semibold">John Doe</div>
                                    <small class="text-muted">Since January 15, 2026 • Paid until March 5, 2026</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <h6>Description</h6>
                            <p class="small text-muted">Spacious deluxe room with city view, complete with air conditioning, own bathroom, and kitchen access. Perfect for families or groups of friends.</p>
                        </div>
                        
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="border rounded p-2">
                                    <small class="text-muted d-block">Last Maintenance</small>
                                    <span>February 1, 2026</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-2">
                                    <small class="text-muted d-block">Next Maintenance</small>
                                    <span>March 1, 2026</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="editRoom(1)">Edit Room</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        const table = $('#roomsTable').DataTable({
            pageLength: 10,
            responsive: true,
            searching: true,
            ordering: true,
            info: true,
            lengthChange: false,
            columnDefs: [
                { orderable: false, targets: [0, 8, 11] },
                { searchable: false, targets: [0, 5, 8, 9, 10] }
            ]
        });

        // Select all checkbox
        $('#selectAll').on('change', function() {
            $('.row-checkbox').prop('checked', $(this).prop('checked'));
        });

        // Search functionality
        $('#searchRoom').on('keyup', function() {
            table.search($(this).val()).draw();
        });

        // Filter by property
        $('#filterProperty').on('change', function() {
            table.column(2).search($(this).val()).draw();
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

        // Filter by type
        $('#filterType').on('change', function() {
            const type = $(this).val();
            if (type) {
                table.column(3).search('^' + type + '$', true, false).draw();
            } else {
                table.column(3).search('').draw();
            }
        });

        // Filter by price
        $('#filterPrice').on('change', function() {
            const price = $(this).val();
            if (price) {
                const [min, max] = price.split('-');
                // Custom filtering for price range
                $.fn.dataTable.ext.search.push(
                    function(settings, data, dataIndex) {
                        const priceValue = parseFloat(data[6].replace(/[₱,]/g, ''));
                        if (max) {
                            return priceValue >= min && priceValue <= max;
                        } else if (min === '10000+') {
                            return priceValue >= 10000;
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

        // Update total rooms count
        $('#totalRooms').text(table.page.info().recordsTotal);
    });

    // Export rooms function
    function exportRooms(type) {
        showToast('info', `Exporting as ${type.toUpperCase()}...`);
        setTimeout(() => {
            showToast('success', 'Export completed successfully');
        }, 2000);
    }

    // View room function
    function viewRoom(id) {
        $('#viewRoomModal').modal('show');
    }

    // Edit room function
    function editRoom(id) {
        $('#addRoomModal').modal('show');
        showToast('info', 'Loading room data...');
    }

    // View occupant function
    function viewOccupant(id) {
        showToast('info', 'Loading occupant details...');
        setTimeout(() => {
            window.location.href = '/admin/occupants?room_id=' + id;
        }, 1000);
    }

    // View payments function
    function viewPayments(id) {
        showToast('info', 'Loading payment history...');
        setTimeout(() => {
            window.location.href = '/admin/payments?room_id=' + id;
        }, 1000);
    }

    // Maintenance history function
    function maintenanceHistory(id) {
        showToast('info', 'Loading maintenance history...');
        setTimeout(() => {
            window.location.href = '/admin/maintenance?room_id=' + id;
        }, 1000);
    }

    // Delete room function
    function deleteRoom(id) {
        Swal.fire({
            title: 'Delete Room?',
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
                        'Room has been deleted successfully.',
                        'success'
                    );
                }, 1500);
            }
        });
    }

    // Save room function
    function saveRoom() {
        // Validate form
        const form = document.getElementById('addRoomForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        showLoading();
        
        // Simulate API call
        setTimeout(() => {
            hideLoading();
            $('#addRoomModal').modal('hide');
            form.reset();
            document.getElementById('imagePreview').innerHTML = '';
            
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Room has been added successfully.',
                timer: 2000,
                showConfirmButton: false
            });
        }, 1500);
    }

    // Bulk action function
    function bulkAction(action) {
        const selected = $('.row-checkbox:checked').length;
        if (selected === 0) {
            showToast('warning', 'Please select at least one room');
            return;
        }
        
        let title, message;
        switch(action) {
            case 'status-available':
                title = 'Set Available';
                message = `Set ${selected} selected room(s) as available?`;
                break;
            case 'status-maintenance':
                title = 'Set Maintenance';
                message = `Set ${selected} selected room(s) under maintenance?`;
                break;
            case 'export':
                title = 'Export Selected';
                message = `Export ${selected} selected room(s)?`;
                break;
            case 'delete':
                title = 'Delete Selected';
                message = `Are you sure you want to delete ${selected} selected room(s)?`;
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

    // Preview images function
    function previewImages(input) {
        const preview = document.getElementById('imagePreview');
        preview.innerHTML = '';
        
        if (input.files) {
            for (let i = 0; i < input.files.length; i++) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'preview-item';
                    div.innerHTML = `
                        <img src="${e.target.result}" alt="Preview">
                        <button class="remove" onclick="this.parentElement.remove()">×</button>
                    `;
                    preview.appendChild(div);
                }
                reader.readAsDataURL(input.files[i]);
            }
        }
    }
</script>
@endsection