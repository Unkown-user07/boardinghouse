@extends('layouts.admin')

@section('title', 'Dashboard - StayEase Admin')

@section('page_header', 'Dashboard Overview')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('header_actions')
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-outline-primary" onclick="exportReport('pdf')">
            <i class="bi bi-file-pdf"></i> <span class="d-none d-md-inline">PDF</span>
        </button>
        <button type="button" class="btn btn-outline-primary" onclick="exportReport('excel')">
            <i class="bi bi-file-excel"></i> <span class="d-none d-md-inline">Excel</span>
        </button>
        <button type="button" class="btn btn-outline-primary" onclick="exportReport('print')">
            <i class="bi bi-printer"></i> <span class="d-none d-md-inline">Print</span>
        </button>
    </div>
    <button class="btn btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#addRoomModal">
        <i class="bi bi-plus-circle"></i> <span class="d-none d-md-inline">Add Room</span>
    </button>
@endsection

@section('styles')
<style>
    /* Stat Cards - Compact Design */
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.03);
        transition: all 0.3s;
        border: 1px solid rgba(0,0,0,0.03);
        height: 100%;
    }
    
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(67, 97, 238, 0.1);
        border-color: rgba(67, 97, 238, 0.15);
    }
    
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    
    .stat-icon.primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .stat-icon.success {
        background: linear-gradient(135deg, #34b1aa 0%, #2c9a94 100%);
        color: white;
    }
    
    .stat-icon.warning {
        background: linear-gradient(135deg, #f6b23e 0%, #f4a51e 100%);
        color: white;
    }
    
    .stat-icon.info {
        background: linear-gradient(135deg, #3b7cff 0%, #2b6ef0 100%);
        color: white;
    }
    
    .stat-value {
        font-size: 1.6rem;
        font-weight: 700;
        color: #2d3748;
        line-height: 1.2;
    }
    
    .stat-label {
        color: #718096;
        font-size: 0.85rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    .stat-change {
        font-size: 0.75rem;
        padding: 0.2rem 0.4rem;
        border-radius: 16px;
        background: #f0fff4;
        color: #2ecc71;
        font-weight: 500;
    }
    
    .stat-change.negative {
        background: #fff5f5;
        color: #e74c3c;
    }
    
    /* Progress Bar - Thinner */
    .progress {
        height: 4px;
        border-radius: 2px;
        background: #edf2f7;
        margin-top: 0.5rem;
    }
    
    .progress-bar {
        border-radius: 2px;
    }
    
    /* Charts Container - More Compact */
    .chart-container {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.03);
        border: 1px solid rgba(0,0,0,0.03);
        margin-bottom: 1.25rem;
        height: 100%;
    }
    
    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .chart-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #2d3748;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    .chart-actions {
        display: flex;
        gap: 0.25rem;
    }
    
    .chart-actions .btn {
        padding: 0.2rem 0.5rem;
        font-size: 0.7rem;
        border-radius: 6px;
    }
    
    /* Chart Sizes */
    .chart-lg {
        height: 250px;
    }
    
    .chart-sm {
        height: 180px;
    }
    
    /* Tables */
    .table-container {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.03);
        border: 1px solid rgba(0,0,0,0.03);
    }
    
    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .table-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #2d3748;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    .table thead th {
        border-top: none;
        border-bottom: 1px solid #edf2f7;
        color: #718096;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        padding: 0.75rem 0.5rem;
        white-space: nowrap;
    }
    
    .table tbody td {
        padding: 0.75rem 0.5rem;
        vertical-align: middle;
        color: #4a5568;
        border-bottom: 1px solid #edf2f7;
        font-size: 0.85rem;
    }
    
    .table tbody tr:hover {
        background: #fafbfc;
    }
    
    /* Status Badges - Smaller */
    .badge-status {
        padding: 0.2rem 0.5rem;
        border-radius: 20px;
        font-size: 0.65rem;
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
    
    .badge-status.maintenance {
        background: #e3f2fd;
        color: #3498db;
    }
    
    /* Avatar - Smaller */
    .avatar-circle {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.85rem;
        flex-shrink: 0;
    }
    
    .avatar-sm {
        width: 28px;
        height: 28px;
        font-size: 0.75rem;
    }
    
    /* Activity Feed - Compact */
    .activity-item {
        padding: 0.75rem;
        border-left: 2px solid transparent;
        transition: all 0.3s;
        border-bottom: 1px solid #edf2f7;
    }
    
    .activity-item:last-child {
        border-bottom: none;
    }
    
    .activity-item:hover {
        border-left-color: #667eea;
        background: #fafbfc;
    }
    
    .activity-icon {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        flex-shrink: 0;
    }
    
    .activity-content {
        flex: 1;
        min-width: 0;
    }
    
    .activity-title {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.1rem;
        font-size: 0.85rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .activity-time {
        font-size: 0.7rem;
        color: #a0aec0;
    }
    
    /* Quick Actions - Compact */
    .quick-action-card {
        background: white;
        border-radius: 12px;
        padding: 0.75rem 0.5rem;
        text-align: center;
        transition: all 0.3s;
        border: 1px solid #edf2f7;
        cursor: pointer;
    }
    
    .quick-action-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(102, 126, 234, 0.1);
        border-color: #667eea;
    }
    
    .quick-action-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        margin: 0 auto 0.5rem;
    }
    
    .quick-action-title {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.1rem;
        font-size: 0.8rem;
    }
    
    .quick-action-desc {
        font-size: 0.65rem;
        color: #718096;
    }
    
    /* Occupancy Stats - Compact */
    .occupancy-stats {
        display: flex;
        justify-content: space-around;
        margin-top: 0.75rem;
        padding-top: 0.75rem;
        border-top: 1px solid #edf2f7;
    }
    
    .occupancy-item {
        text-align: center;
    }
    
    .occupancy-value {
        font-weight: 700;
        font-size: 1rem;
    }
    
    .occupancy-label {
        font-size: 0.65rem;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    /* Pagination - Smaller */
    .pagination-sm .page-link {
        padding: 0.2rem 0.5rem;
        font-size: 0.75rem;
    }
    
    /* Responsive Breakpoints */
    @media (max-width: 1200px) {
        .stat-value {
            font-size: 1.4rem;
        }
        
        .stat-icon {
            width: 42px;
            height: 42px;
            font-size: 1.3rem;
        }
    }
    
    @media (max-width: 992px) {
        .chart-lg {
            height: 220px;
        }
        
        .chart-sm {
            height: 160px;
        }
        
        .quick-action-card {
            padding: 0.5rem 0.25rem;
        }
        
        .quick-action-icon {
            width: 32px;
            height: 32px;
            font-size: 1rem;
        }
    }
    
    @media (max-width: 768px) {
        .stat-card {
            padding: 1rem;
        }
        
        .stat-value {
            font-size: 1.3rem;
        }
        
        .stat-icon {
            width: 38px;
            height: 38px;
            font-size: 1.2rem;
            margin-right: 0.75rem !important;
        }
        
        .chart-container,
        .table-container {
            padding: 1rem;
        }
        
        .chart-lg {
            height: 200px;
        }
        
        .chart-sm {
            height: 150px;
        }
        
        .table thead th,
        .table tbody td {
            padding: 0.5rem 0.4rem;
            font-size: 0.75rem;
        }
        
        .avatar-circle {
            width: 28px;
            height: 28px;
            font-size: 0.75rem;
        }
        
        .btn-group .btn {
            padding: 0.25rem 0.4rem;
            font-size: 0.75rem;
        }
    }
    
    @media (max-width: 576px) {
        .stat-card {
            padding: 0.875rem;
        }
        
        .stat-value {
            font-size: 1.2rem;
        }
        
        .stat-label {
            font-size: 0.7rem;
        }
        
        .stat-icon {
            width: 34px;
            height: 34px;
            font-size: 1rem;
            margin-right: 0.5rem !important;
        }
        
        .stat-change {
            font-size: 0.65rem;
            padding: 0.15rem 0.3rem;
        }
        
        .chart-title {
            font-size: 0.85rem;
        }
        
        .quick-action-title {
            font-size: 0.7rem;
        }
        
        .quick-action-desc {
            display: none;
        }
        
        .quick-action-icon {
            width: 30px;
            height: 30px;
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }
        
        .occupancy-value {
            font-size: 0.9rem;
        }
        
        .occupancy-label {
            font-size: 0.6rem;
        }
        
        .table thead th,
        .table tbody td {
            padding: 0.4rem 0.25rem;
            font-size: 0.7rem;
        }
        
        .badge-status {
            padding: 0.15rem 0.4rem;
            font-size: 0.6rem;
        }
    }
    
    /* Animations */
    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 8px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 8px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
    
    /* Table Responsive */
    .table-responsive {
        border-radius: 12px;
        margin-bottom: 0.5rem;
    }
    
    /* Gap Utilities */
    .gap-2 {
        gap: 0.5rem;
    }
    
    .gap-3 {
        gap: 0.75rem;
    }
    
    /* Text Utilities */
    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection

@section('content')
<div class="fade-in">
    <!-- Statistics Cards - More Compact -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon primary me-3">
                        <i class="bi bi-house-door"></i>
                    </div>
                    <div class="flex-grow-1 min-width-0">
                        <div class="stat-value">24</div>
                        <div class="stat-label">Total Rooms</div>
                        <div class="d-flex align-items-center mt-1">
                            <span class="stat-change me-2">
                                <i class="bi bi-arrow-up"></i> 12%
                            </span>
                            <span class="text-muted small">vs last month</span>
                        </div>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Occupied: 18</span>
                        <span>Available: 6</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-primary" style="width: 75%"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon success me-3">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="stat-value">48</div>
                        <div class="stat-label">Total Tenants</div>
                        <div class="d-flex align-items-center mt-1">
                            <span class="stat-change me-2">
                                <i class="bi bi-arrow-up"></i> 8%
                            </span>
                            <span class="text-muted small">vs last month</span>
                        </div>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>New: 6</span>
                        <span>Checkouts: 2</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-success" style="width: 75%"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon warning me-3">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="stat-value">₱189.5k</div>
                        <div class="stat-label">Monthly Revenue</div>
                        <div class="d-flex align-items-center mt-1">
                            <span class="stat-change me-2">
                                <i class="bi bi-arrow-up"></i> 15%
                            </span>
                            <span class="text-muted small">vs last month</span>
                        </div>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Collected: ₱145k</span>
                        <span>Pending: ₱44.5k</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-warning" style="width: 76%"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon info me-3">
                        <i class="bi bi-tools"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="stat-value">12</div>
                        <div class="stat-label">Maintenance</div>
                        <div class="d-flex align-items-center mt-1">
                            <span class="stat-change negative me-2">
                                <i class="bi bi-arrow-down"></i> 5%
                            </span>
                            <span class="text-muted small">vs last month</span>
                        </div>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Pending: 8</span>
                        <span>In Progress: 4</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-info" style="width: 60%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row - Better Proportions -->
    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="chart-container">
                <div class="chart-header">
                    <h5 class="chart-title">Revenue Overview</h5>
                    <div class="chart-actions">
                        <button class="btn btn-outline-secondary btn-sm active">Week</button>
                        <button class="btn btn-outline-secondary btn-sm">Month</button>
                        <button class="btn btn-outline-secondary btn-sm">Year</button>
                    </div>
                </div>
                <div class="chart-lg">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="chart-container">
                <div class="chart-header">
                    <h5 class="chart-title">Room Occupancy</h5>
                    <button class="btn btn-link text-primary p-0" onclick="refreshChart('occupancy')">
                        <i class="bi bi-arrow-repeat"></i>
                    </button>
                </div>
                <div class="chart-sm">
                    <canvas id="occupancyChart"></canvas>
                </div>
                <div class="occupancy-stats">
                    <div class="occupancy-item">
                        <div class="occupancy-value text-primary">75%</div>
                        <div class="occupancy-label">Occupied</div>
                    </div>
                    <div class="occupancy-item">
                        <div class="occupancy-value text-success">20%</div>
                        <div class="occupancy-label">Available</div>
                    </div>
                    <div class="occupancy-item">
                        <div class="occupancy-value text-warning">5%</div>
                        <div class="occupancy-label">Maintenance</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activities, Payments & Quick Actions - Balanced -->
    <div class="row g-3 mb-4">
        <div class="col-lg-4">
            <div class="chart-container">
                <div class="chart-header">
                    <h5 class="chart-title">Recent Activities</h5>
                    <a href="/admin/activities" class="text-primary small">View All</a>
                </div>
                <div class="activity-feed">
                    <div class="activity-item d-flex align-items-start gap-2">
                        <div class="activity-icon bg-primary bg-opacity-10">
                            <i class="bi bi-person-plus text-primary"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">New tenant registered</div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="activity-time">2 min ago</span>
                                <span class="badge bg-light text-dark">Maria S.</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="activity-item d-flex align-items-start gap-2">
                        <div class="activity-icon bg-success bg-opacity-10">
                            <i class="bi bi-cash text-success"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Payment received</div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="activity-time">15 min ago</span>
                                <span class="badge bg-light text-dark">₱4,500</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="activity-item d-flex align-items-start gap-2">
                        <div class="activity-icon bg-warning bg-opacity-10">
                            <i class="bi bi-tools text-warning"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Maintenance request</div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="activity-time">1 hour ago</span>
                                <span class="badge bg-light text-dark">Rm 204</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="activity-item d-flex align-items-start gap-2">
                        <div class="activity-icon bg-info bg-opacity-10">
                            <i class="bi bi-box-arrow-right text-info"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Tenant checkout</div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="activity-time">3 hours ago</span>
                                <span class="badge bg-light text-dark">Rm 105</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="chart-container">
                <div class="chart-header">
                    <h5 class="chart-title">Upcoming Payments</h5>
                    <a href="/admin/payments" class="text-primary small">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>Tenant</th>
                                <th>Amount</th>
                                <th>Due</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-circle avatar-sm">JD</div>
                                        <span class="text-truncate" style="max-width: 70px;">John</span>
                                    </div>
                                </td>
                                <td>₱4.5k</td>
                                <td>Mar 5</td>
                                <td><span class="badge-status pending">Pending</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-circle avatar-sm">MS</div>
                                        <span class="text-truncate" style="max-width: 70px;">Maria</span>
                                    </div>
                                </td>
                                <td>₱4.5k</td>
                                <td>Mar 7</td>
                                <td><span class="badge-status pending">Pending</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-circle avatar-sm">AR</div>
                                        <span class="text-truncate" style="max-width: 70px;">Alex</span>
                                    </div>
                                </td>
                                <td>₱4.5k</td>
                                <td>Mar 3</td>
                                <td><span class="badge-status active">Paid</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-circle avatar-sm">MC</div>
                                        <span class="text-truncate" style="max-width: 70px;">Michael</span>
                                    </div>
                                </td>
                                <td>₱4.5k</td>
                                <td>Mar 10</td>
                                <td><span class="badge-status pending">Pending</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="chart-container">
                <div class="chart-header">
                    <h5 class="chart-title">Quick Actions</h5>
                </div>
                <div class="row g-2">
                    <div class="col-4">
                        <div class="quick-action-card" onclick="location.href='/admin/rooms/add'">
                            <div class="quick-action-icon">
                                <i class="bi bi-plus-lg"></i>
                            </div>
                            <div class="quick-action-title">Add Room</div>
                            <div class="quick-action-desc">Create</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="quick-action-card" onclick="location.href='/admin/tenants/add'">
                            <div class="quick-action-icon">
                                <i class="bi bi-person-plus"></i>
                            </div>
                            <div class="quick-action-title">Add Tenant</div>
                            <div class="quick-action-desc">Register</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="quick-action-card" onclick="location.href='/admin/payments/record'">
                            <div class="quick-action-icon">
                                <i class="bi bi-cash"></i>
                            </div>
                            <div class="quick-action-title">Record Payment</div>
                            <div class="quick-action-desc">Log</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="quick-action-card" onclick="location.href='/admin/maintenance/new'">
                            <div class="quick-action-icon">
                                <i class="bi bi-tools"></i>
                            </div>
                            <div class="quick-action-title">Maintenance</div>
                            <div class="quick-action-desc">Create</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="quick-action-card" onclick="location.href='/admin/reports'">
                            <div class="quick-action-icon">
                                <i class="bi bi-file-text"></i>
                            </div>
                            <div class="quick-action-title">Reports</div>
                            <div class="quick-action-desc">Generate</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="quick-action-card" onclick="location.href='/admin/settings'">
                            <div class="quick-action-icon">
                                <i class="bi bi-gear"></i>
                            </div>
                            <div class="quick-action-title">Settings</div>
                            <div class="quick-action-desc">Configure</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Tenants Table - Compact -->
    <div class="table-container">
        <div class="table-header">
            <h5 class="table-title">Recent Tenants</h5>
            <div>
                <input type="text" class="form-control form-control-sm" placeholder="Search..." style="width: 180px;" id="tenantSearch">
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover" id="tenantsTable">
                <thead>
                    <tr>
                        <th>Tenant</th>
                        <th>Room</th>
                        <th>Contact</th>
                        <th>Move In</th>
                        <th>Rent</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-circle">JD</div>
                                <div class="text-truncate" style="max-width: 100px;">
                                    <div class="fw-semibold">John Doe</div>
                                    <small class="text-muted">john@email.com</small>
                                </div>
                            </div>
                        </td>
                        <td>204</td>
                        <td>0912***789</td>
                        <td>Jan 15</td>
                        <td>₱4.5k</td>
                        <td><span class="badge-status active">Paid</span></td>
                        <td><span class="badge-status active">Active</span></td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-primary" title="View">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-secondary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-circle">MS</div>
                                <div class="text-truncate" style="max-width: 100px;">
                                    <div class="fw-semibold">Maria Santos</div>
                                    <small class="text-muted">maria@email.com</small>
                                </div>
                            </div>
                        </td>
                        <td>305</td>
                        <td>0923***890</td>
                        <td>Feb 1</td>
                        <td>₱5.0k</td>
                        <td><span class="badge-status pending">Pending</span></td>
                        <td><span class="badge-status active">Active</span></td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-primary" title="View">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-secondary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-circle">AR</div>
                                <div class="text-truncate" style="max-width: 100px;">
                                    <div class="fw-semibold">Alex Reyes</div>
                                    <small class="text-muted">alex@email.com</small>
                                </div>
                            </div>
                        </td>
                        <td>102</td>
                        <td>0934***901</td>
                        <td>Dec 10</td>
                        <td>₱4.2k</td>
                        <td><span class="badge-status active">Paid</span></td>
                        <td><span class="badge-status active">Active</span></td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-primary" title="View">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-secondary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="small text-muted">Showing 3 of 48 tenants</div>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled"><a class="page-link" href="#">Prev</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Add Room Modal -->
<div class="modal fade" id="addRoomModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Room</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addRoomForm">
                    <div class="mb-3">
                        <label class="form-label">Room Number</label>
                        <input type="text" class="form-control" name="room_number" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Floor</label>
                        <select class="form-select" name="floor">
                            <option value="1">1st Floor</option>
                            <option value="2">2nd Floor</option>
                            <option value="3">3rd Floor</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Capacity</label>
                            <input type="number" class="form-control" name="capacity" min="1" max="6" value="4">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Monthly Rent</label>
                            <input type="number" class="form-control" name="monthly_rent" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="available">Available</option>
                            <option value="occupied">Occupied</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="addRoom()">Add Room</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize Revenue Chart - Optimized for container
        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    data: [120000, 135000, 142000, 138000, 145000, 152000, 158000, 165000, 172000, 180000, 185000, 189500],
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.05)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 2,
                    pointHoverRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: true }
                },
                scales: {
                    y: { 
                        beginAtZero: true,
                        grid: { display: true, color: 'rgba(0,0,0,0.02)' },
                        ticks: { 
                            callback: function(value) { return '₱' + (value/1000) + 'k'; },
                            font: { size: 10 }
                        }
                    },
                    x: { 
                        grid: { display: false },
                        ticks: { font: { size: 10 } }
                    }
                },
                layout: { padding: { top: 10, bottom: 10 } }
            }
        });

        // Initialize Occupancy Chart - Compact
        const occupancyCtx = document.getElementById('occupancyChart').getContext('2d');
        new Chart(occupancyCtx, {
            type: 'doughnut',
            data: {
                labels: ['Occupied', 'Available', 'Maintenance'],
                datasets: [{
                    data: [75, 20, 5],
                    backgroundColor: ['#667eea', '#2ecc71', '#f39c12'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: { legend: { display: false } },
                layout: { padding: 5 }
            }
        });

        // Search functionality
        $('#tenantSearch').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('#tenantsTable tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
        
        // Handle window resize for charts
        $(window).resize(function() {
            // Charts will auto-resize
        });
    });

    // Export report function
    function exportReport(type) {
        showToast('info', `Exporting as ${type.toUpperCase()}...`);
        // Implement actual export logic here
    }

    // Refresh chart function
    function refreshChart(chartId) {
        showToast('info', 'Refreshing chart data...');
        // Implement chart refresh logic here
    }

    // Add room function
    function addRoom() {
        Swal.fire({
            title: 'Adding Room...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        setTimeout(() => {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Room added successfully',
                timer: 1500,
                showConfirmButton: false
            });
            
            $('#addRoomModal').modal('hide');
            document.getElementById('addRoomForm').reset();
        }, 1000);
    }

    // Toast notification helper
    function showToast(type, message) {
        toastr[type](message, '', {
            closeButton: true,
            progressBar: true,
            timeOut: 2000,
            positionClass: 'toast-top-right'
        });
    }

    // Initialize tooltips
    document.querySelectorAll('[title]').forEach(el => new bootstrap.Tooltip(el));

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.shiftKey && e.key === 'A') {
            e.preventDefault();
            $('#addRoomModal').modal('show');
        }
        if (e.ctrlKey && e.shiftKey && e.key === 'R') {
            e.preventDefault();
            location.reload();
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection