@extends('layouts.admin')

@section('title', 'Reservations Management - StayEase Admin')

@section('page_header', 'Reservations Management')

@section('page_description', 'Manage all room booking requests and reservations')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Reservations</li>
@endsection

@section('header_actions')
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addReservationModal">
        <i class="bi bi-plus-circle me-2"></i>Manual Reservation
    </button>
    <div class="btn-group ms-2">
        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
            <i class="bi bi-download me-2"></i>Export
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="#" onclick="exportReservations('pdf')"><i class="bi bi-file-pdf me-2"></i>PDF</a></li>
            <li><a class="dropdown-item" href="#" onclick="exportReservations('excel')"><i class="bi bi-file-excel me-2"></i>Excel</a></li>
            <li><a class="dropdown-item" href="#" onclick="exportReservations('csv')"><i class="bi bi-file-text me-2"></i>CSV</a></li>
        </ul>
    </div>
    <button class="btn btn-outline-primary ms-2" onclick="showCalendar()">
        <i class="bi bi-calendar-week me-2"></i>Calendar View
    </button>
@endsection

@section('styles')
<style>
    /* Reservation Stats Cards */
    .reservation-stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.03);
        border: 1px solid rgba(0,0,0,0.03);
        height: 100%;
        transition: all 0.3s;
    }
    
    .reservation-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.1);
        border-color: rgba(102, 126, 234, 0.15);
    }
    
    .reservation-stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }
    
    .reservation-stat-icon.primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .reservation-stat-icon.success {
        background: linear-gradient(135deg, #34b1aa 0%, #2c9a94 100%);
    }
    
    .reservation-stat-icon.warning {
        background: linear-gradient(135deg, #f6b23e 0%, #f4a51e 100%);
    }
    
    .reservation-stat-icon.info {
        background: linear-gradient(135deg, #3b7cff 0%, #2b6ef0 100%);
    }
    
    .reservation-stat-value {
        font-size: 1.6rem;
        font-weight: 700;
        color: #2d3748;
        line-height: 1.2;
    }
    
    .reservation-stat-label {
        color: #718096;
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    .reservation-stat-change {
        font-size: 0.75rem;
        padding: 0.2rem 0.4rem;
        border-radius: 16px;
        background: #f0fff4;
        color: #2ecc71;
    }
    
    .reservation-stat-change.negative {
        background: #fff5f5;
        color: #e74c3c;
    }
    
    /* Reservation Table Styles */
    .reservation-info h6 {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.2rem;
        font-size: 0.95rem;
    }
    
    .reservation-info small {
        color: #718096;
        font-size: 0.75rem;
    }
    
    .guest-avatar {
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
    
    .badge-reservation {
        padding: 0.25rem 0.8rem;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: capitalize;
        white-space: nowrap;
    }
    
    .badge-reservation.pending {
        background: #fff3e0;
        color: #f39c12;
    }
    
    .badge-reservation.confirmed {
        background: #e6f7e6;
        color: #27ae60;
    }
    
    .badge-reservation.checked-in {
        background: #e3f2fd;
        color: #3498db;
    }
    
    .badge-reservation.checked-out {
        background: #e9ecef;
        color: #6c757d;
    }
    
    .badge-reservation.cancelled {
        background: #fee9e9;
        color: #e74c3c;
    }
    
    .badge-reservation.no-show {
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
    
    /* Timeline */
    .reservation-timeline {
        position: relative;
        padding-left: 2rem;
    }
    
    .reservation-timeline::before {
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
        left: -2rem;
        top: 0.25rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #667eea;
        border: 2px solid white;
        z-index: 1;
    }
    
    .timeline-item.completed::before {
        background: #27ae60;
    }
    
    .timeline-item.current::before {
        background: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
    }
    
    .timeline-item.cancelled::before {
        background: #e74c3c;
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
    
    /* Guest Info Card */
    .guest-card {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .guest-detail {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem;
        border-bottom: 1px solid #edf2f7;
    }
    
    .guest-detail:last-child {
        border-bottom: none;
    }
    
    .guest-detail i {
        width: 20px;
        color: #667eea;
    }
    
    /* Payment Summary */
    .payment-summary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 1rem;
        color: white;
    }
    
    .payment-row {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px solid rgba(255,255,255,0.2);
    }
    
    .payment-row:last-child {
        border-bottom: none;
    }
    
    .payment-total {
        font-size: 1.2rem;
        font-weight: 700;
    }
    
    /* Calendar View */
    .calendar-container {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.03);
        border: 1px solid rgba(0,0,0,0.03);
        margin-bottom: 1.5rem;
    }
    
    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    
    .calendar-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2d3748;
    }
    
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 0.5rem;
    }
    
    .calendar-day-header {
        text-align: center;
        font-weight: 600;
        color: #718096;
        font-size: 0.85rem;
        padding: 0.5rem;
    }
    
    .calendar-day {
        min-height: 100px;
        background: #f8fafc;
        border-radius: 8px;
        padding: 0.5rem;
        position: relative;
    }
    
    .calendar-day.today {
        background: #e3f2fd;
        border: 2px solid #667eea;
    }
    
    .calendar-day-number {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }
    
    .calendar-event {
        background: #667eea;
        color: white;
        padding: 0.15rem 0.3rem;
        border-radius: 4px;
        font-size: 0.65rem;
        margin-bottom: 0.15rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        cursor: pointer;
    }
    
    .calendar-event.arrival {
        background: #27ae60;
    }
    
    .calendar-event.departure {
        background: #e74c3c;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .reservation-stat-value {
            font-size: 1.3rem;
        }
        
        .reservation-stat-icon {
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
        
        .calendar-grid {
            grid-template-columns: repeat(1, 1fr);
        }
        
        .calendar-day {
            min-height: auto;
        }
    }
    
    /* Modal Styles */
    .reservation-form-section {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .reservation-form-section h6 {
        font-size: 0.9rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .reservation-form-section h6 i {
        color: #667eea;
    }
    
    /* Availability Indicator */
    .availability-indicator {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }
    
    .availability-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }
    
    .availability-dot.available {
        background: #27ae60;
    }
    
    .availability-dot.booked {
        background: #e74c3c;
    }
    
    .availability-dot.partial {
        background: #f39c12;
    }
</style>
@endsection

@section('content')
<div class="fade-in">
    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="reservation-stat-card">
                <div class="d-flex align-items-center">
                    <div class="reservation-stat-icon primary me-3">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="reservation-stat-value">48</div>
                        <div class="reservation-stat-label">Active Reservations</div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">This month</span>
                        <span class="reservation-stat-change">
                            <i class="bi bi-arrow-up"></i> +12
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="reservation-stat-card">
                <div class="d-flex align-items-center">
                    <div class="reservation-stat-icon success me-3">
                        <i class="bi bi-person-check"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="reservation-stat-value">24</div>
                        <div class="reservation-stat-label">Checked In</div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Currently staying</span>
                        <span class="reservation-stat-change">
                            <i class="bi bi-arrow-up"></i> +5
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="reservation-stat-card">
                <div class="d-flex align-items-center">
                    <div class="reservation-stat-icon warning me-3">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="reservation-stat-value">16</div>
                        <div class="reservation-stat-label">Pending</div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Awaiting confirmation</span>
                        <span class="reservation-stat-change negative">
                            <i class="bi bi-arrow-up"></i> +3
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="reservation-stat-card">
                <div class="d-flex align-items-center">
                    <div class="reservation-stat-icon info me-3">
                        <i class="bi bi-calendar-range"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="reservation-stat-value">8</div>
                        <div class="reservation-stat-label">Arriving Today</div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Expected check-in</span>
                        <span class="reservation-stat-change">
                            <i class="bi bi-arrow-up"></i> +2
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
                    <div class="quick-stat-number">₱189,500</div>
                    <div class="quick-stat-label">Potential Revenue</div>
                </div>
            </div>
            <div class="col-3">
                <div class="quick-stat-item">
                    <div class="quick-stat-number">72%</div>
                    <div class="quick-stat-label">Occupancy Rate</div>
                </div>
            </div>
            <div class="col-3">
                <div class="quick-stat-item">
                    <div class="quick-stat-number">4.8</div>
                    <div class="quick-stat-label">Avg Stay (days)</div>
                </div>
            </div>
            <div class="col-3">
                <div class="quick-stat-item">
                    <div class="quick-stat-number">₱3,950</div>
                    <div class="quick-stat-label">Avg Daily Rate</div>
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
                    <input type="text" class="form-control border-0 bg-light" id="searchReservation" placeholder="Guest, ID, room...">
                </div>
            </div>
            <div class="col-lg-2 col-md-4">
                <div class="filter-label">Status</div>
                <select class="form-select bg-light border-0" id="filterStatus">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="checked-in">Checked In</option>
                    <option value="checked-out">Checked Out</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="no-show">No Show</option>
                </select>
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
                <div class="filter-label">Check-in Date</div>
                <input type="text" class="form-control bg-light border-0" id="checkinDate" placeholder="Select date">
            </div>
            <div class="col-lg-2 col-md-4">
                <div class="filter-label">Check-out Date</div>
                <input type="text" class="form-control bg-light border-0" id="checkoutDate" placeholder="Select date">
            </div>
            <div class="col-lg-2 col-md-4">
                <div class="filter-label">Payment Status</div>
                <select class="form-select bg-light border-0" id="filterPayment">
                    <option value="">All</option>
                    <option value="paid">Paid</option>
                    <option value="partial">Partial</option>
                    <option value="pending">Pending</option>
                    <option value="refunded">Refunded</option>
                </select>
            </div>
        </div>
    </div>

    <!-- View Toggle -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="btn-group" role="group">
            <button class="btn btn-outline-primary active" onclick="toggleView('list')" id="listViewBtn">
                <i class="bi bi-list-ul me-1"></i> List
            </button>
            <button class="btn btn-outline-primary" onclick="toggleView('calendar')" id="calendarViewBtn">
                <i class="bi bi-calendar-week me-1"></i> Calendar
            </button>
        </div>
        <span class="text-muted small">Showing 12 of 48 reservations</span>
    </div>

    <!-- List View -->
    <div id="listView">
        <!-- Reservations Table -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-hover" id="reservationsTable">
                    <thead>
                        <tr>
                            <th width="40">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                </div>
                            </th>
                            <th>Reservation ID</th>
                            <th>Guest</th>
                            <th>Property & Room</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Nights</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Payment</th>
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
                                <div class="reservation-info">
                                    <h6>#RES-2024-001</h6>
                                    <small class="text-muted">Booked: Mar 1, 2026</small>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="guest-avatar">JD</div>
                                    <div>
                                        <div class="fw-semibold">John Doe</div>
                                        <small class="text-muted">+63 912 345 6789</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <span class="property-badge">Sunset Residences</span>
                                    <span class="room-badge ms-1">Rm 204</span>
                                </div>
                                <small class="text-muted">Deluxe Room</small>
                            </td>
                            <td>
                                <div>
                                    <div>Mar 15, 2026</div>
                                    <small class="text-muted">2:00 PM</small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div>Mar 20, 2026</div>
                                    <small class="text-muted">12:00 PM</small>
                                </div>
                            </td>
                            <td>
                                <div class="text-center">
                                    <span class="fw-semibold">5</span>
                                    <small class="text-muted d-block">nights</small>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold">₱22,500</div>
                                <small class="text-muted">₱4,500/night</small>
                            </td>
                            <td>
                                <span class="badge-reservation confirmed">Confirmed</span>
                            </td>
                            <td>
                                <span class="badge-payment paid">Paid</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-outline-primary" title="View Details" onclick="viewReservation(1)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" title="Edit" onclick="editReservation(1)">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-success" title="Check In" onclick="checkIn(1)">
                                        <i class="bi bi-box-arrow-in-right"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-warning" title="Send Reminder" onclick="sendReminder(1)">
                                        <i class="bi bi-bell"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-info" title="Message Guest" onclick="messageGuest(1)">
                                        <i class="bi bi-chat"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" title="Cancel" onclick="cancelReservation(1)">
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
                                <div class="reservation-info">
                                    <h6>#RES-2024-002</h6>
                                    <small>Booked: Feb 28, 2026</small>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="guest-avatar">MS</div>
                                    <div>
                                        <div class="fw-semibold">Maria Santos</div>
                                        <small class="text-muted">+63 923 456 7890</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <span class="property-badge">Green Heights</span>
                                    <span class="room-badge ms-1">Rm 305</span>
                                </div>
                                <small class="text-muted">Standard Room</small>
                            </td>
                            <td>
                                <div>
                                    <div>Mar 16, 2026</div>
                                    <small class="text-muted">2:00 PM</small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div>Mar 18, 2026</div>
                                    <small class="text-muted">12:00 PM</small>
                                </div>
                            </td>
                            <td>
                                <div class="text-center">
                                    <span class="fw-semibold">2</span>
                                    <small class="text-muted d-block">nights</small>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold">₱10,000</div>
                                <small class="text-muted">₱5,000/night</small>
                            </td>
                            <td>
                                <span class="badge-reservation pending">Pending</span>
                            </td>
                            <td>
                                <span class="badge-payment pending">Pending</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                                    <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></button>
                                    <button class="btn btn-sm btn-outline-success"><i class="bi bi-box-arrow-in-right"></i></button>
                                    <button class="btn btn-sm btn-outline-warning"><i class="bi bi-bell"></i></button>
                                    <button class="btn btn-sm btn-outline-info"><i class="bi bi-chat"></i></button>
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-x-circle"></i></button>
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
                                <div class="reservation-info">
                                    <h6>#RES-2024-003</h6>
                                    <small>Booked: Feb 25, 2026</small>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="guest-avatar">AR</div>
                                    <div>
                                        <div class="fw-semibold">Alex Reyes</div>
                                        <small class="text-muted">+63 934 567 8901</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <span class="property-badge">Bayview Tower</span>
                                    <span class="room-badge ms-1">Rm 101</span>
                                </div>
                                <small class="text-muted">Suite</small>
                            </td>
                            <td>
                                <div>
                                    <div>Mar 14, 2026</div>
                                    <small class="text-muted">2:00 PM</small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div>Mar 17, 2026</div>
                                    <small class="text-muted">12:00 PM</small>
                                </div>
                            </td>
                            <td>
                                <div class="text-center">
                                    <span class="fw-semibold">3</span>
                                    <small class="text-muted d-block">nights</small>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold">₱25,500</div>
                                <small class="text-muted">₱8,500/night</small>
                            </td>
                            <td>
                                <span class="badge-reservation checked-in">Checked In</span>
                            </td>
                            <td>
                                <span class="badge-payment paid">Paid</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                                    <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></button>
                                    <button class="btn btn-sm btn-outline-success"><i class="bi bi-box-arrow-in-right"></i></button>
                                    <button class="btn btn-sm btn-outline-warning"><i class="bi bi-bell"></i></button>
                                    <button class="btn btn-sm btn-outline-info"><i class="bi bi-chat"></i></button>
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-x-circle"></i></button>
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
                                <div class="reservation-info">
                                    <h6>#RES-2024-004</h6>
                                    <small>Booked: Feb 20, 2026</small>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="guest-avatar">JL</div>
                                    <div>
                                        <div class="fw-semibold">Jane Lim</div>
                                        <small class="text-muted">+63 945 678 9012</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <span class="property-badge">City Lights</span>
                                    <span class="room-badge ms-1">Rm 412</span>
                                </div>
                                <small class="text-muted">Standard Room</small>
                            </td>
                            <td>
                                <div>
                                    <div>Mar 10, 2026</div>
                                    <small class="text-muted">2:00 PM</small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div>Mar 15, 2026</div>
                                    <small class="text-muted">12:00 PM</small>
                                </div>
                            </td>
                            <td>
                                <div class="text-center">
                                    <span class="fw-semibold">5</span>
                                    <small class="text-muted d-block">nights</small>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold">₱20,000</div>
                                <small class="text-muted">₱4,000/night</small>
                            </td>
                            <td>
                                <span class="badge-reservation checked-out">Checked Out</span>
                            </td>
                            <td>
                                <span class="badge-payment paid">Paid</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                                    <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></button>
                                    <button class="btn btn-sm btn-outline-success"><i class="bi bi-box-arrow-in-right"></i></button>
                                    <button class="btn btn-sm btn-outline-warning"><i class="bi bi-bell"></i></button>
                                    <button class="btn btn-sm btn-outline-info"><i class="bi bi-chat"></i></button>
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-x-circle"></i></button>
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
                                <div class="reservation-info">
                                    <h6>#RES-2024-005</h6>
                                    <small>Booked: Feb 15, 2026</small>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="guest-avatar">CV</div>
                                    <div>
                                        <div class="fw-semibold">Carlos Villanueva</div>
                                        <small class="text-muted">+63 956 789 0123</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <span class="property-badge">Sunset Residences</span>
                                    <span class="room-badge ms-1">Rm 310</span>
                                </div>
                                <small class="text-muted">Standard Room</small>
                            </td>
                            <td>
                                <div>
                                    <div>Mar 5, 2026</div>
                                    <small class="text-muted">2:00 PM</small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div>Mar 8, 2026</div>
                                    <small class="text-muted">12:00 PM</small>
                                </div>
                            </td>
                            <td>
                                <div class="text-center">
                                    <span class="fw-semibold">3</span>
                                    <small class="text-muted d-block">nights</small>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold">₱11,400</div>
                                <small class="text-muted">₱3,800/night</small>
                            </td>
                            <td>
                                <span class="badge-reservation cancelled">Cancelled</span>
                            </td>
                            <td>
                                <span class="badge-payment refunded">Refunded</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                                    <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></button>
                                    <button class="btn btn-sm btn-outline-success"><i class="bi bi-box-arrow-in-right"></i></button>
                                    <button class="btn btn-sm btn-outline-warning"><i class="bi bi-bell"></i></button>
                                    <button class="btn btn-sm btn-outline-info"><i class="bi bi-chat"></i></button>
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-x-circle"></i></button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="small text-muted">
                    Showing 5 of 48 reservations
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

    <!-- Calendar View (Hidden by default) -->
    <div id="calendarView" style="display: none;">
        <div class="calendar-container">
            <div class="calendar-header">
                <h5 class="calendar-title">March 2026</h5>
                <div class="btn-group">
                    <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-chevron-left"></i></button>
                    <button class="btn btn-sm btn-outline-primary">Today</button>
                    <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-chevron-right"></i></button>
                </div>
            </div>
            
            <div class="calendar-grid">
                <div class="calendar-day-header">Sun</div>
                <div class="calendar-day-header">Mon</div>
                <div class="calendar-day-header">Tue</div>
                <div class="calendar-day-header">Wed</div>
                <div class="calendar-day-header">Thu</div>
                <div class="calendar-day-header">Fri</div>
                <div class="calendar-day-header">Sat</div>
                
                <!-- Empty days for March 1 starting on Sunday -->
                <div class="calendar-day"></div>
                <div class="calendar-day"></div>
                <div class="calendar-day"></div>
                <div class="calendar-day"></div>
                <div class="calendar-day"></div>
                <div class="calendar-day"></div>
                
                <!-- March 1 -->
                <div class="calendar-day">
                    <div class="calendar-day-number">1</div>
                    <div class="calendar-event arrival" onclick="viewReservation(1)">John Doe - Arrival</div>
                </div>
                
                <!-- March 2 -->
                <div class="calendar-day">
                    <div class="calendar-day-number">2</div>
                </div>
                
                <!-- March 3 -->
                <div class="calendar-day">
                    <div class="calendar-day-number">3</div>
                    <div class="calendar-event" onclick="viewReservation(2)">Maria Santos</div>
                </div>
                
                <!-- March 4 -->
                <div class="calendar-day">
                    <div class="calendar-day-number">4</div>
                </div>
                
                <!-- March 5 -->
                <div class="calendar-day">
                    <div class="calendar-day-number">5</div>
                    <div class="calendar-event arrival" onclick="viewReservation(5)">Carlos V. - Arrival</div>
                    <div class="calendar-event departure" onclick="viewReservation(3)">Alex R. - Departure</div>
                </div>
                
                <!-- March 6 -->
                <div class="calendar-day">
                    <div class="calendar-day-number">6</div>
                </div>
                
                <!-- March 7 -->
                <div class="calendar-day">
                    <div class="calendar-day-number">7</div>
                </div>
                
                <!-- March 8 -->
                <div class="calendar-day">
                    <div class="calendar-day-number">8</div>
                    <div class="calendar-event departure" onclick="viewReservation(5)">Carlos V. - Departure</div>
                </div>
                
                <!-- March 9 -->
                <div class="calendar-day">
                    <div class="calendar-day-number">9</div>
                </div>
                
                <!-- March 10 -->
                <div class="calendar-day">
                    <div class="calendar-day-number">10</div>
                    <div class="calendar-event arrival" onclick="viewReservation(4)">Jane Lim - Arrival</div>
                </div>
                
                <!-- Continue for the rest of the month -->
                <div class="calendar-day">
                    <div class="calendar-day-number">11</div>
                </div>
                <div class="calendar-day">
                    <div class="calendar-day-number">12</div>
                </div>
                <div class="calendar-day">
                    <div class="calendar-day-number">13</div>
                </div>
                <div class="calendar-day today">
                    <div class="calendar-day-number">14</div>
                    <div class="calendar-event arrival" onclick="viewReservation(3)">Alex R. - Arrival</div>
                </div>
                <div class="calendar-day">
                    <div class="calendar-day-number">15</div>
                    <div class="calendar-event arrival" onclick="viewReservation(1)">John Doe - Arrival</div>
                    <div class="calendar-event departure" onclick="viewReservation(4)">Jane Lim - Departure</div>
                </div>
                <div class="calendar-day">
                    <div class="calendar-day-number">16</div>
                    <div class="calendar-event arrival" onclick="viewReservation(2)">Maria Santos - Arrival</div>
                </div>
                <div class="calendar-day">
                    <div class="calendar-day-number">17</div>
                    <div class="calendar-event departure" onclick="viewReservation(3)">Alex R. - Departure</div>
                </div>
                <div class="calendar-day">
                    <div class="calendar-day-number">18</div>
                    <div class="calendar-event departure" onclick="viewReservation(2)">Maria Santos - Departure</div>
                </div>
                <div class="calendar-day">
                    <div class="calendar-day-number">19</div>
                </div>
                <div class="calendar-day">
                    <div class="calendar-day-number">20</div>
                    <div class="calendar-event departure" onclick="viewReservation(1)">John Doe - Departure</div>
                </div>
                <div class="calendar-day">
                    <div class="calendar-day-number">21</div>
                </div>
                <div class="calendar-day">
                    <div class="calendar-day-number">22</div>
                </div>
                <div class="calendar-day">
                    <div class="calendar-day-number">23</div>
                </div>
                <div class="calendar-day">
                    <div class="calendar-day-number">24</div>
                </div>
                <div class="calendar-day">
                    <div class="calendar-day-number">25</div>
                </div>
                <div class="calendar-day">
                    <div class="calendar-day-number">26</div>
                </div>
                <div class="calendar-day">
                    <div class="calendar-day-number">27</div>
                </div>
                <div class="calendar-day">
                    <div class="calendar-day-number">28</div>
                </div>
                <div class="calendar-day">
                    <div class="calendar-day-number">29</div>
                </div>
                <div class="calendar-day">
                    <div class="calendar-day-number">30</div>
                </div>
                <div class="calendar-day">
                    <div class="calendar-day-number">31</div>
                </div>
            </div>
            
            <div class="mt-3 d-flex gap-3">
                <div class="d-flex align-items-center">
                    <span class="calendar-event arrival me-2" style="width: 20px; height: 10px;"></span>
                    <small>Arrival</small>
                </div>
                <div class="d-flex align-items-center">
                    <span class="calendar-event departure me-2" style="width: 20px; height: 10px;"></span>
                    <small>Departure</small>
                </div>
                <div class="d-flex align-items-center">
                    <span class="calendar-event me-2" style="width: 20px; height: 10px;"></span>
                    <small>Reservation</small>
                </div>
                <div class="d-flex align-items-center">
                    <span class="bg-primary me-2" style="width: 20px; height: 10px;"></span>
                    <small>Today</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Reservation Modal -->
<div class="modal fade" id="addReservationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Reservation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addReservationForm">
                    <!-- Guest Information -->
                    <div class="reservation-form-section">
                        <h6><i class="bi bi-person"></i> Guest Information</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Guest Type</label>
                                <select class="form-select" name="guest_type" id="guestType">
                                    <option value="existing">Existing Guest</option>
                                    <option value="new">New Guest</option>
                                </select>
                            </div>
                            <div class="col-md-6" id="existingGuestField">
                                <label class="form-label">Select Guest</label>
                                <select class="form-select" name="guest_id">
                                    <option value="">Choose guest...</option>
                                    <option value="1">John Doe - +63 912 345 6789</option>
                                    <option value="2">Maria Santos - +63 923 456 7890</option>
                                    <option value="3">Alex Reyes - +63 934 567 8901</option>
                                    <option value="4">Jane Lim - +63 945 678 9012</option>
                                </select>
                            </div>
                            <div class="col-md-6" id="newGuestFields" style="display: none;">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" name="guest_name" placeholder="Enter full name">
                            </div>
                            <div class="col-md-3 new-guest" style="display: none;">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="guest_email" placeholder="guest@email.com">
                            </div>
                            <div class="col-md-3 new-guest" style="display: none;">
                                <label class="form-label">Phone</label>
                                <input type="tel" class="form-control" name="guest_phone" placeholder="+63 912 345 6789">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Reservation Details -->
                    <div class="reservation-form-section">
                        <h6><i class="bi bi-calendar-check"></i> Reservation Details</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Property</label>
                                <select class="form-select" name="property_id" id="propertySelect" required>
                                    <option value="">Select property...</option>
                                    <option value="1">Sunset Residences - Manila</option>
                                    <option value="2">Green Heights - Quezon City</option>
                                    <option value="3">Bayview Tower - Makati</option>
                                    <option value="4">City Lights - Pasig</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Room</label>
                                <select class="form-select" name="room_id" id="roomSelect" required>
                                    <option value="">Select room...</option>
                                    <option value="101">Room 101 - Standard (₱3,500)</option>
                                    <option value="102">Room 102 - Standard (₱3,500)</option>
                                    <option value="201">Room 201 - Deluxe (₱4,500)</option>
                                    <option value="202">Room 202 - Deluxe (₱4,500)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Room Type</label>
                                <input type="text" class="form-control" id="roomTypeDisplay" value="Standard" readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Check-in Date</label>
                                <input type="date" class="form-control" name="checkin_date" id="checkinDate" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Check-out Date</label>
                                <input type="date" class="form-control" name="checkout_date" id="checkoutDate" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Nights</label>
                                <input type="text" class="form-control bg-light" id="nightsCount" value="1" readonly>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Guests</label>
                                <input type="number" class="form-control" name="guests" value="2" min="1" max="6">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="pending">Pending</option>
                                    <option value="confirmed" selected>Confirmed</option>
                                    <option value="checked-in">Checked In</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="availability-indicator mt-2">
                            <span class="availability-dot available"></span>
                            <small class="text-muted">Room available for selected dates</small>
                        </div>
                    </div>
                    
                    <!-- Payment Information -->
                    <div class="reservation-form-section">
                        <h6><i class="bi bi-cash"></i> Payment Information</h6>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Nightly Rate (₱)</label>
                                <input type="number" class="form-control" name="nightly_rate" id="nightlyRate" value="4500" readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Total Amount (₱)</label>
                                <input type="text" class="form-control bg-light" id="totalAmount" value="₱4,500" readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Payment Status</label>
                                <select class="form-select" name="payment_status">
                                    <option value="pending">Pending</option>
                                    <option value="partial">Partial</option>
                                    <option value="paid">Paid</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Payment Method</label>
                                <select class="form-select" name="payment_method">
                                    <option value="">Select...</option>
                                    <option value="cash">Cash</option>
                                    <option value="bank">Bank Transfer</option>
                                    <option value="card">Credit Card</option>
                                    <option value="gcash">GCash</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Special Requests -->
                    <div class="reservation-form-section">
                        <h6><i class="bi bi-chat"></i> Special Requests</h6>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Special Requests / Notes</label>
                                <textarea class="form-control" name="special_requests" rows="3" placeholder="Any special requests or notes for this reservation..."></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveReservation()">Create Reservation</button>
            </div>
        </div>
    </div>
</div>

<!-- View Reservation Modal -->
<div class="modal fade" id="viewReservationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reservation Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <!-- Reservation Header -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h4>#RES-2024-001</h4>
                                <p class="text-muted">Booked on March 1, 2026 • Booking Agent: Online</p>
                            </div>
                            <span class="badge-reservation confirmed">Confirmed</span>
                        </div>
                        
                        <!-- Guest Information -->
                        <div class="guest-card">
                            <h6 class="mb-3"><i class="bi bi-person me-2 text-primary"></i>Guest Information</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="guest-detail">
                                        <i class="bi bi-person-circle"></i>
                                        <div>
                                            <strong>John Doe</strong>
                                            <small class="text-muted d-block">Guest ID: G-2024-001</small>
                                        </div>
                                    </div>
                                    <div class="guest-detail">
                                        <i class="bi bi-envelope"></i>
                                        <span>john.doe@email.com</span>
                                    </div>
                                    <div class="guest-detail">
                                        <i class="bi bi-phone"></i>
                                        <span>+63 912 345 6789</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="guest-detail">
                                        <i class="bi bi-house"></i>
                                        <span>123 Main St, Manila</span>
                                    </div>
                                    <div class="guest-detail">
                                        <i class="bi bi-calendar"></i>
                                        <span>Birthdate: Jan 15, 1995 (29)</span>
                                    </div>
                                    <div class="guest-detail">
                                        <i class="bi bi-card-text"></i>
                                        <span>ID: Driver's License - D12-34-567890</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Reservation Details -->
                        <div class="guest-card">
                            <h6 class="mb-3"><i class="bi bi-calendar-check me-2 text-primary"></i>Reservation Details</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="guest-detail">
                                        <i class="bi bi-building"></i>
                                        <div>
                                            <strong>Sunset Residences</strong>
                                            <small class="text-muted d-block">Room 204 - Deluxe</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="guest-detail">
                                        <i class="bi bi-calendar"></i>
                                        <div>
                                            <strong>Mar 15, 2026 - Mar 20, 2026</strong>
                                            <small class="text-muted d-block">5 nights</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="guest-detail">
                                        <i class="bi bi-people"></i>
                                        <div>
                                            <strong>2 Guests</strong>
                                            <small class="text-muted d-block">1 room</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Payment Summary -->
                        <div class="guest-card">
                            <h6 class="mb-3"><i class="bi bi-cash me-2 text-primary"></i>Payment Summary</h6>
                            <div class="payment-summary">
                                <div class="payment-row">
                                    <span>Room Charge (5 nights x ₱4,500)</span>
                                    <span>₱22,500</span>
                                </div>
                                <div class="payment-row">
                                    <span>Additional Services</span>
                                    <span>₱0</span>
                                </div>
                                <div class="payment-row">
                                    <span>Tax (12%)</span>
                                    <span>₱2,700</span>
                                </div>
                                <div class="payment-row">
                                    <span>Total Amount</span>
                                    <span class="payment-total">₱25,200</span>
                                </div>
                                <div class="payment-row">
                                    <span>Paid Amount</span>
                                    <span class="text-success">₱25,200</span>
                                </div>
                                <div class="payment-row">
                                    <span>Balance</span>
                                    <span>₱0</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Timeline -->
                        <div class="guest-card">
                            <h6 class="mb-3"><i class="bi bi-clock-history me-2 text-primary"></i>Timeline</h6>
                            <div class="reservation-timeline">
                                <div class="timeline-item completed">
                                    <div class="timeline-date">March 1, 2026 - 10:30 AM</div>
                                    <div class="timeline-title">Reservation Created</div>
                                    <div class="timeline-text">Online booking via website</div>
                                </div>
                                <div class="timeline-item completed">
                                    <div class="timeline-date">March 1, 2026 - 10:35 AM</div>
                                    <div class="timeline-title">Payment Received</div>
                                    <div class="timeline-text">Full payment via GCash</div>
                                </div>
                                <div class="timeline-item completed">
                                    <div class="timeline-date">March 1, 2026 - 11:00 AM</div>
                                    <div class="timeline-title">Confirmation Sent</div>
                                    <div class="timeline-text">Email confirmation sent to guest</div>
                                </div>
                                <div class="timeline-item current">
                                    <div class="timeline-date">March 15, 2026 - 2:00 PM</div>
                                    <div class="timeline-title">Expected Check-in</div>
                                    <div class="timeline-text">Room 204 ready for guest</div>
                                </div>
                                <div class="timeline-item upcoming">
                                    <div class="timeline-date">March 20, 2026 - 12:00 PM</div>
                                    <div class="timeline-title">Check-out</div>
                                    <div class="timeline-text">Room inspection required</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <!-- Quick Actions -->
                        <div class="guest-card">
                            <h6 class="mb-3"><i class="bi bi-lightning me-2 text-primary"></i>Quick Actions</h6>
                            <div class="d-grid gap-2">
                                <button class="btn btn-outline-primary btn-sm" onclick="checkIn(1)">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Check In
                                </button>
                                <button class="btn btn-outline-success btn-sm" onclick="sendReminder(1)">
                                    <i class="bi bi-bell me-2"></i>Send Reminder
                                </button>
                                <button class="btn btn-outline-info btn-sm" onclick="messageGuest(1)">
                                    <i class="bi bi-chat me-2"></i>Message Guest
                                </button>
                                <button class="btn btn-outline-warning btn-sm" onclick="editReservation(1)">
                                    <i class="bi bi-pencil me-2"></i>Edit Reservation
                                </button>
                                <button class="btn btn-outline-secondary btn-sm" onclick="printConfirmation(1)">
                                    <i class="bi bi-printer me-2"></i>Print Confirmation
                                </button>
                                <button class="btn btn-outline-danger btn-sm" onclick="cancelReservation(1)">
                                    <i class="bi bi-x-circle me-2"></i>Cancel Reservation
                                </button>
                            </div>
                        </div>
                        
                        <!-- Special Requests -->
                        <div class="guest-card">
                            <h6 class="mb-3"><i class="bi bi-chat me-2 text-primary"></i>Special Requests</h6>
                            <p class="small mb-0">• Early check-in requested (12:00 PM instead of 2:00 PM)</p>
                            <p class="small mb-0">• Extra pillows requested</p>
                            <p class="small">• Allergic to feathers - please use synthetic pillows</p>
                        </div>
                        
                        <!-- Documents -->
                        <div class="guest-card">
                            <h6 class="mb-3"><i class="bi bi-file-text me-2 text-primary"></i>Documents</h6>
                            <div class="document-item">
                                <div class="document-icon bg-primary bg-opacity-10">
                                    <i class="bi bi-file-pdf text-primary"></i>
                                </div>
                                <div class="document-info">
                                    <div class="document-name">Booking Confirmation</div>
                                    <div class="document-date">Generated: Mar 1, 2026</div>
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
                                    <div class="document-name">Payment Receipt</div>
                                    <div class="document-date">Generated: Mar 1, 2026</div>
                                </div>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Audit Log -->
                        <div class="guest-card">
                            <h6 class="mb-3"><i class="bi bi-journal me-2 text-primary"></i>Audit Log</h6>
                            <div class="small">
                                <p class="mb-1"><span class="text-muted">Created by:</span> Admin User</p>
                                <p class="mb-1"><span class="text-muted">Last modified:</span> Mar 2, 2026</p>
                                <p class="mb-1"><span class="text-muted">Modified by:</span> Admin User</p>
                                <p class="mb-0"><span class="text-muted">IP Address:</span> 192.168.1.100</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="editReservation(1)">Edit Reservation</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        const table = $('#reservationsTable').DataTable({
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

        // Initialize date pickers
        flatpickr("#checkinDate", {
            dateFormat: "Y-m-d",
            allowInput: true,
            minDate: "today"
        });
        
        flatpickr("#checkoutDate", {
            dateFormat: "Y-m-d",
            allowInput: true,
            minDate: "today"
        });

        flatpickr("#filterCheckin", {
            dateFormat: "Y-m-d",
            allowInput: true
        });

        flatpickr("#filterCheckout", {
            dateFormat: "Y-m-d",
            allowInput: true
        });

        // Select all checkbox
        $('#selectAll').on('change', function() {
            $('.row-checkbox').prop('checked', $(this).prop('checked'));
        });

        // Search functionality
        $('#searchReservation').on('keyup', function() {
            table.search($(this).val()).draw();
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

        // Filter by property
        $('#filterProperty').on('change', function() {
            const property = $(this).val();
            if (property) {
                table.column(3).search(property, true, false).draw();
            } else {
                table.column(3).search('').draw();
            }
        });

        // Guest type toggle
        $('#guestType').on('change', function() {
            if ($(this).val() === 'new') {
                $('#existingGuestField').hide();
                $('#newGuestFields').show();
                $('.new-guest').show();
            } else {
                $('#existingGuestField').show();
                $('#newGuestFields').hide();
                $('.new-guest').hide();
            }
        });

        // Calculate nights and total
        function calculateNights() {
            const checkin = $('#checkinDate').val();
            const checkout = $('#checkoutDate').val();
            
            if (checkin && checkout) {
                const start = new Date(checkin);
                const end = new Date(checkout);
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                $('#nightsCount').val(diffDays);
                
                const rate = parseFloat($('#nightlyRate').val()) || 0;
                const total = diffDays * rate;
                $('#totalAmount').val('₱' + total.toLocaleString());
            }
        }

        $('#checkinDate, #checkoutDate, #nightlyRate').on('change', calculateNights);
    });

    // View toggle function
    function toggleView(view) {
        if (view === 'list') {
            $('#listView').show();
            $('#calendarView').hide();
            $('#listViewBtn').addClass('active');
            $('#calendarViewBtn').removeClass('active');
        } else {
            $('#listView').hide();
            $('#calendarView').show();
            $('#calendarViewBtn').addClass('active');
            $('#listViewBtn').removeClass('active');
        }
    }

    // Show calendar view
    function showCalendar() {
        toggleView('calendar');
    }

    // Export reservations function
    function exportReservations(type) {
        showToast('info', `Exporting as ${type.toUpperCase()}...`);
        setTimeout(() => {
            showToast('success', 'Export completed successfully');
        }, 2000);
    }

    // View reservation function
    function viewReservation(id) {
        $('#viewReservationModal').modal('show');
    }

    // Edit reservation function
    function editReservation(id) {
        $('#addReservationModal').modal('show');
        showToast('info', 'Loading reservation data...');
    }

    // Check in function
    function checkIn(id) {
        Swal.fire({
            title: 'Check In Guest',
            html: `
                <div class="text-start">
                    <p>Confirm check-in for John Doe?</p>
                    <div class="mb-3">
                        <label class="form-label">Room Status</label>
                        <select class="form-select">
                            <option value="ready">Room Ready</option>
                            <option value="inspection">Needs Inspection</option>
                            <option value="not-ready">Not Ready</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ID Verification</label>
                        <select class="form-select">
                            <option value="verified">Verified</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Status</label>
                        <select class="form-select">
                            <option value="paid">Paid</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonColor: '#27ae60',
            confirmButtonText: 'Check In',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();
                setTimeout(() => {
                    hideLoading();
                    Swal.fire('Checked In!', 'Guest has been checked in successfully', 'success');
                }, 1500);
            }
        });
    }

    // Send reminder function
    function sendReminder(id) {
        Swal.fire({
            title: 'Send Reminder',
            text: 'Send check-in reminder to guest?',
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

    // Message guest function
    function messageGuest(id) {
        Swal.fire({
            title: 'Message Guest',
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

    // Cancel reservation function
    function cancelReservation(id) {
        Swal.fire({
            title: 'Cancel Reservation',
            text: 'Are you sure you want to cancel this reservation?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, cancel',
            cancelButtonText: 'No, keep it'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Select Cancellation Reason',
                    input: 'select',
                    inputOptions: {
                        'guest-request': 'Guest Request',
                        'payment-issue': 'Payment Issue',
                        'double-booking': 'Double Booking',
                        'maintenance': 'Maintenance Issue',
                        'other': 'Other'
                    },
                    inputPlaceholder: 'Select a reason',
                    showCancelButton: true,
                    confirmButtonText: 'Confirm Cancellation',
                    cancelButtonText: 'Back'
                }).then((result) => {
                    if (result.isConfirmed) {
                        showLoading();
                        setTimeout(() => {
                            hideLoading();
                            Swal.fire(
                                'Cancelled!',
                                'Reservation has been cancelled.',
                                'success'
                            );
                        }, 1500);
                    }
                });
            }
        });
    }

    // Save reservation function
    function saveReservation() {
        // Validate form
        const form = document.getElementById('addReservationForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        showLoading();
        
        // Simulate API call
        setTimeout(() => {
            hideLoading();
            $('#addReservationModal').modal('hide');
            form.reset();
            
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Reservation created successfully.',
                timer: 2000,
                showConfirmButton: false
            });
        }, 1500);
    }

    // Print confirmation function
    function printConfirmation(id) {
        window.print();
    }

    // Bulk action function
    function bulkAction(action) {
        const selected = $('.row-checkbox:checked').length;
        if (selected === 0) {
            showToast('warning', 'Please select at least one reservation');
            return;
        }
        
        let title, message;
        switch(action) {
            case 'confirm':
                title = 'Confirm Reservations';
                message = `Confirm ${selected} selected reservation(s)?`;
                break;
            case 'send-reminders':
                title = 'Send Reminders';
                message = `Send reminders for ${selected} selected reservation(s)?`;
                break;
            case 'check-in':
                title = 'Bulk Check-in';
                message = `Check in ${selected} selected reservation(s)?`;
                break;
            case 'export':
                title = 'Export Selected';
                message = `Export ${selected} selected reservation(s)?`;
                break;
            case 'cancel':
                title = 'Cancel Reservations';
                message = `Cancel ${selected} selected reservation(s)?`;
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