@extends('layouts.user')

@section('title', 'Dashboard - StayEase')

@section('page_header', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('header_actions')
    <a href="/reports" class="btn btn-outline-primary me-2">
        <i class="bi bi-download"></i> <span class="d-none d-md-inline">Download Report</span>
    </a>
    <a href="/payments/make" class="btn btn-primary">
        <i class="bi bi-credit-card"></i> <span class="d-none d-md-inline">Make Payment</span>
    </a>
@endsection

@section('styles')
<style>
    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
    }
    .stat-icon {
        font-size: 2.5rem;
        opacity: 0.3;
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
    }
    .progress-custom {
        height: 8px;
        border-radius: 4px;
        background: rgba(255,255,255,0.2);
    }
    .progress-bar-custom {
        background: white;
        border-radius: 4px;
    }
    .due-badge {
        background: #fee2e2;
        color: #dc2626;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .activity-item {
        border-left: 3px solid transparent;
        transition: all 0.3s;
    }
    .activity-item:hover {
        border-left-color: #4361ee;
        background: #f8f9fa;
    }
    
    /* Responsive fixes */
    .card-header {
        padding: 1rem;
    }
    
    .card-body {
        padding: 1.25rem;
    }
    
    @media (max-width: 768px) {
        .card-body {
            padding: 1rem;
        }
        
        .table td, .table th {
            font-size: 0.85rem;
            padding: 0.75rem 0.5rem;
        }
        
        .btn-sm {
            padding: 0.25rem 0.4rem;
        }
        
        h3.fw-bold {
            font-size: 1.5rem;
        }
        
        .display-6 {
            font-size: 1.75rem;
        }
        
        .welcome-banner {
            text-align: center;
        }
        
        .welcome-banner .btn {
            margin-bottom: 1rem;
        }
    }
    
    @media (max-width: 576px) {
        .table {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }
        
        .stat-card {
            padding: 1rem;
        }
        
        .stat-icon {
            font-size: 2rem;
        }
        
        .card-header h5 {
            font-size: 1rem;
        }
        
        .badge {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
        }
    }
    
    /* Animation */
    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Hover effects */
    .stat-card-hover {
        transition: transform 0.3s, box-shadow 0.3s;
    }
    
    .stat-card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
    }
    
    /* Custom scrollbar for tables */
    .table-responsive::-webkit-scrollbar {
        height: 6px;
    }
    
    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .table-responsive::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 10px;
    }
    
    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
</style>
@endsection

@section('content')
<div class="row fade-in g-3 g-lg-4">
    <!-- Welcome Banner -->
    <div class="col-12 mb-3 mb-lg-4">
        <div class="card bg-primary text-white border-0 welcome-banner">
            <div class="card-body p-3 p-md-4">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h3 class="fw-bold mb-2 fs-4 fs-md-3">Welcome back, John! 👋</h3>
                        <p class="mb-3 opacity-75 small">Your rent is due in 5 days. Make sure to pay on time to avoid penalties.</p>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="/payments/make" class="btn btn-light btn-sm">
                                <i class="bi bi-credit-card me-2"></i>Pay Now
                            </a>
                            <a href="/maintenance/create" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-tools me-2"></i>Report Issue
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4 text-end d-none d-lg-block">
                        <i class="bi bi-house-door" style="font-size: 6rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="col-xl-3 col-md-6 col-sm-6 mb-3 mb-lg-4">
        <div class="card border-0 shadow-sm h-100 stat-card-hover">
            <div class="card-body p-3 p-xl-4">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-primary bg-opacity-10 p-2 p-md-3 rounded-3">
                        <i class="bi bi-door-open fs-5 fs-md-4 text-primary"></i>
                    </div>
                    <div class="flex-grow-1 ms-2 ms-md-3">
                        <h6 class="text-muted mb-1 small">Room Number</h6>
                        <h4 class="fw-bold mb-0 fs-5 fs-md-4">204</h4>
                        <small class="text-success">2nd Floor</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 col-sm-6 mb-3 mb-lg-4">
        <div class="card border-0 shadow-sm h-100 stat-card-hover">
            <div class="card-body p-3 p-xl-4">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-success bg-opacity-10 p-2 p-md-3 rounded-3">
                        <i class="bi bi-people fs-5 fs-md-4 text-success"></i>
                    </div>
                    <div class="flex-grow-1 ms-2 ms-md-3">
                        <h6 class="text-muted mb-1 small">Roommates</h6>
                        <h4 class="fw-bold mb-0 fs-5 fs-md-4">3/4</h4>
                        <small class="text-warning">1 spot available</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 col-sm-6 mb-3 mb-lg-4">
        <div class="card border-0 shadow-sm h-100 stat-card-hover">
            <div class="card-body p-3 p-xl-4">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-info bg-opacity-10 p-2 p-md-3 rounded-3">
                        <i class="bi bi-calendar-check fs-5 fs-md-4 text-info"></i>
                    </div>
                    <div class="flex-grow-1 ms-2 ms-md-3">
                        <h6 class="text-muted mb-1 small">Days Remaining</h6>
                        <h4 class="fw-bold mb-0 fs-5 fs-md-4">156</h4>
                        <small class="text-primary">Until renewal</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 col-sm-6 mb-3 mb-lg-4">
        <div class="card border-0 shadow-sm h-100 stat-card-hover">
            <div class="card-body p-3 p-xl-4">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-warning bg-opacity-10 p-2 p-md-3 rounded-3">
                        <i class="bi bi-cash-stack fs-5 fs-md-4 text-warning"></i>
                    </div>
                    <div class="flex-grow-1 ms-2 ms-md-3">
                        <h6 class="text-muted mb-1 small">Monthly Rent</h6>
                        <h4 class="fw-bold mb-0 fs-5 fs-md-4">₱4,500</h4>
                        <small class="text-danger">Due in 5 days</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="col-lg-8 mb-3 mb-lg-4">
        <!-- Payment History -->
        <div class="card border-0 shadow-sm mb-3 mb-lg-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 fw-bold fs-6 fs-md-5">Payment History</h5>
                <a href="/payments" class="text-decoration-none small">View All <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-3">Month</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date Paid</th>
                                <th class="pe-3">Receipt</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="ps-3">February 2026</td>
                                <td>₱4,500</td>
                                <td><span class="badge bg-success">Paid</span></td>
                                <td>Feb 1, 2026</td>
                                <td class="pe-3">
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-file-pdf"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-3">January 2026</td>
                                <td>₱4,500</td>
                                <td><span class="badge bg-success">Paid</span></td>
                                <td>Jan 3, 2026</td>
                                <td class="pe-3">
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-file-pdf"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-3">December 2025</td>
                                <td>₱4,500</td>
                                <td><span class="badge bg-success">Paid</span></td>
                                <td>Dec 2, 2025</td>
                                <td class="pe-3">
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-file-pdf"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-3">Utilities (Jan)</td>
                                <td>₱850</td>
                                <td><span class="badge bg-warning">Pending</span></td>
                                <td>—</td>
                                <td class="pe-3">
                                    <button class="btn btn-sm btn-primary">
                                        Pay Now
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Maintenance Requests -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 fw-bold fs-6 fs-md-5">Maintenance Requests</h5>
                <a href="/maintenance/create" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-circle"></i> <span class="d-none d-sm-inline">New Request</span>
                </a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <div class="list-group-item px-3 py-3 activity-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="flex-grow-1 me-3">
                                <h6 class="mb-1 fs-6">Broken Aircon</h6>
                                <p class="mb-1 small text-muted">Submitted on Feb 25, 2026</p>
                                <span class="badge bg-warning">In Progress</span>
                            </div>
                            <a href="#" class="text-primary small text-nowrap">View</a>
                        </div>
                    </div>
                    <div class="list-group-item px-3 py-3 activity-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="flex-grow-1 me-3">
                                <h6 class="mb-1 fs-6">Leaking Faucet</h6>
                                <p class="mb-1 small text-muted">Submitted on Feb 20, 2026</p>
                                <span class="badge bg-success">Completed</span>
                            </div>
                            <a href="#" class="text-primary small text-nowrap">View</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Sidebar -->
    <div class="col-lg-4 mb-3 mb-lg-4">
        <!-- Announcements -->
        <div class="card border-0 shadow-sm mb-3 mb-lg-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 fw-bold fs-6 fs-md-5">Announcements</h5>
                <span class="badge bg-primary">3 New</span>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <div class="list-group-item p-3">
                        <div class="d-flex gap-2 gap-md-3">
                            <div class="bg-warning bg-opacity-10 p-2 rounded-3 flex-shrink-0">
                                <i class="bi bi-exclamation-triangle text-warning"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fs-6">Water Interruption</h6>
                                <p class="mb-1 small">Feb 28, 2026 • 8AM-5PM</p>
                                <a href="#" class="small text-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item p-3">
                        <div class="d-flex gap-2 gap-md-3">
                            <div class="bg-info bg-opacity-10 p-2 rounded-3 flex-shrink-0">
                                <i class="bi bi-megaphone text-info"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fs-6">General Cleaning</h6>
                                <p class="mb-1 small">Every Saturday • 9AM</p>
                                <a href="#" class="small text-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item p-3">
                        <div class="d-flex gap-2 gap-md-3">
                            <div class="bg-success bg-opacity-10 p-2 rounded-3 flex-shrink-0">
                                <i class="bi bi-calendar-heart text-success"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fs-6">Boarding House Party</h6>
                                <p class="mb-1 small">March 15, 2026 • 7PM</p>
                                <a href="#" class="small text-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Roommates -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 fw-bold fs-6 fs-md-5">Roommates</h5>
                <a href="/roommates" class="text-decoration-none small">View All</a>
            </div>
            <div class="card-body p-3">
                <div class="d-flex align-items-center mb-3">
                    <img src="https://ui-avatars.com/api/?name=Michael+Chen&background=4361ee&color=fff&size=50" class="rounded-circle me-3" width="45" height="45" alt="Michael Chen">
                    <div class="flex-grow-1">
                        <h6 class="mb-0 fw-bold fs-6">Michael Chen</h6>
                        <small class="text-muted">Roommate since Jan 2026</small>
                    </div>
                </div>
                <div class="d-flex align-items-center mb-3">
                    <img src="https://ui-avatars.com/api/?name=Sarah+Santos&background=3f37c9&color=fff&size=50" class="rounded-circle me-3" width="45" height="45" alt="Sarah Santos">
                    <div class="flex-grow-1">
                        <h6 class="mb-0 fw-bold fs-6">Sarah Santos</h6>
                        <small class="text-muted">Roommate since Dec 2025</small>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <img src="https://ui-avatars.com/api/?name=Alex+Reyes&background=4cc9f0&color=fff&size=50" class="rounded-circle me-3" width="45" height="45" alt="Alex Reyes">
                    <div class="flex-grow-1">
                        <h6 class="mb-0 fw-bold fs-6">Alex Reyes</h6>
                        <small class="text-muted">Roommate since Feb 2026</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Dashboard specific scripts
    $(document).ready(function() {
        // Initialize charts or other dashboard features here
        console.log('Dashboard loaded');
        
        // Add responsive table handling
        function handleTableResponsive() {
            if ($(window).width() < 768) {
                $('.table-responsive').css('margin-bottom', '1rem');
            } else {
                $('.table-responsive').css('margin-bottom', '0');
            }
        }
        
        // Run on load
        handleTableResponsive();
        
        // Run on resize
        $(window).resize(function() {
            handleTableResponsive();
        });
    });
</script>
@endsection