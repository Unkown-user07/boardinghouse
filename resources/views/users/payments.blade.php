@extends('layouts.user')

@section('title', 'Payments - StayEase')

@section('page_header', 'Payments')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Payments</li>
@endsection

@section('header_actions')
    <button class="btn btn-primary" onclick="openPaymentModal()">
        <i class="bi bi-plus-circle me-2"></i>Make Payment
    </button>
@endsection

@section('styles')
<style>
    /* Summary Cards */
    .summary-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        height: 100%;
        border: 1px solid #eee;
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
    }
    
    .summary-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(135deg, #4361ee, #764ba2);
    }
    
    .summary-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .summary-icon {
        width: 50px;
        height: 50px;
        background: #eef2ff;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #4361ee;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }
    
    .summary-label {
        color: #6c757d;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }
    
    .summary-amount {
        font-size: 1.8rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 0.3rem;
    }
    
    .summary-due {
        font-size: 0.9rem;
        color: #ef476f;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }
    
    /* Payment Methods */
    .method-card {
        background: white;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 1.2rem;
        cursor: pointer;
        transition: all 0.3s;
        text-align: center;
        height: 100%;
    }
    
    .method-card:hover {
        border-color: #4361ee;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(67, 97, 238, 0.1);
    }
    
    .method-card.selected {
        border-color: #4361ee;
        background: #eef2ff;
    }
    
    .method-icon {
        width: 60px;
        height: 60px;
        background: #f8f9fa;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.8rem;
        color: #4361ee;
    }
    
    .method-name {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.3rem;
    }
    
    .method-desc {
        font-size: 0.8rem;
        color: #6c757d;
    }
    
    /* Transaction List */
    .transaction-list {
        max-height: 500px;
        overflow-y: auto;
    }
    
    .transaction-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid #eee;
        transition: background 0.2s;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .transaction-item:hover {
        background: #f8f9fa;
    }
    
    .transaction-item:last-child {
        border-bottom: none;
    }
    
    .transaction-icon {
        width: 45px;
        height: 45px;
        background: #eef2ff;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        color: #4361ee;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    
    .transaction-icon.success {
        background: #d4edda;
        color: #155724;
    }
    
    .transaction-icon.pending {
        background: #fff3cd;
        color: #856404;
    }
    
    .transaction-icon.failed {
        background: #f8d7da;
        color: #721c24;
    }
    
    .transaction-details {
        flex: 1;
        min-width: 200px;
    }
    
    .transaction-title {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.2rem;
    }
    
    .transaction-meta {
        font-size: 0.85rem;
        color: #6c757d;
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        align-items: center;
    }
    
    .transaction-amount {
        font-weight: 700;
        color: #333;
        min-width: 100px;
        text-align: right;
    }
    
    .transaction-amount.text-danger {
        color: #ef476f;
    }
    
    .receipt-btn {
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        border: 1px solid #4361ee;
        color: #4361ee;
        background: transparent;
        transition: all 0.2s;
        white-space: nowrap;
    }
    
    .receipt-btn:hover {
        background: #4361ee;
        color: white;
    }
    
    .pay-btn {
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        border: none;
        background: #4361ee;
        color: white;
        transition: all 0.2s;
        white-space: nowrap;
    }
    
    .pay-btn:hover {
        background: #3046c0;
        transform: translateY(-2px);
        box-shadow: 0 5px 10px rgba(67, 97, 238, 0.3);
    }
    
    .pay-btn.danger {
        background: #ef476f;
    }
    
    .pay-btn.danger:hover {
        background: #d63e62;
    }
    
    /* Payment Modal */
    .payment-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 1000;
        animation: fadeIn 0.3s ease;
        overflow-y: auto;
    }
    
    .payment-modal-content {
        background: white;
        max-width: 600px;
        margin: 50px auto;
        border-radius: 15px;
        overflow: hidden;
        animation: slideIn 0.3s ease;
    }
    
    @keyframes slideIn {
        from { transform: translateY(-50px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    
    .modal-header {
        background: linear-gradient(135deg, #4361ee 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
    }
    
    .modal-body {
        padding: 2rem;
    }
    
    .modal-footer {
        padding: 1rem 1.5rem;
        background: #f8f9fa;
        border-top: 1px solid #eee;
    }
    
    /* Payment Summary */
    .payment-summary {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px dashed #dee2e6;
    }
    
    .summary-row:last-child {
        border-bottom: none;
    }
    
    .summary-row.total {
        font-weight: 700;
        font-size: 1.2rem;
        color: #4361ee;
        padding-top: 1rem;
    }
    
    /* Status Badges */
    .status-badge {
        padding: 0.4rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        white-space: nowrap;
    }
    
    .status-paid {
        background: #d4edda;
        color: #155724;
    }
    
    .status-pending {
        background: #fff3cd;
        color: #856404;
    }
    
    .status-overdue {
        background: #f8d7da;
        color: #721c24;
    }
    
    .status-partial {
        background: #cff4fc;
        color: #055160;
    }
    
    /* Filter Tabs */
    .filter-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }
    
    .filter-tab {
        padding: 0.5rem 1.2rem;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid #dee2e6;
        color: #6c757d;
    }
    
    .filter-tab:hover {
        border-color: #4361ee;
        color: #4361ee;
    }
    
    .filter-tab.active {
        background: #4361ee;
        border-color: #4361ee;
        color: white;
    }
    
    /* Payment Proof Upload */
    .proof-upload {
        border: 2px dashed #dee2e6;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        margin-bottom: 1rem;
    }
    
    .proof-upload:hover {
        border-color: #4361ee;
        background: #eef2ff;
    }
    
    .proof-upload i {
        font-size: 2.5rem;
        color: #6c757d;
        margin-bottom: 0.5rem;
    }
    
    .proof-upload p {
        margin-bottom: 0;
        color: #6c757d;
    }
    
    .file-info {
        display: none;
        background: #eef2ff;
        padding: 1rem;
        border-radius: 8px;
        margin-top: 1rem;
        align-items: center;
        gap: 0.5rem;
    }
    
    .file-info.show {
        display: flex;
    }
    
    /* Upcoming Bills */
    .bill-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid #eee;
    }
    
    .bill-item:last-child {
        border-bottom: none;
    }
    
    .bill-title {
        font-weight: 600;
        margin-bottom: 0.2rem;
    }
    
    .bill-date {
        font-size: 0.85rem;
        color: #6c757d;
    }
    
    .bill-amount {
        font-weight: 700;
        color: #333;
    }
    
    .days-left {
        font-size: 0.8rem;
        padding: 0.2rem 0.5rem;
        border-radius: 20px;
        background: #fff3cd;
        color: #856404;
    }
    
    /* Saved Payment Methods */
    .saved-method {
        display: flex;
        align-items: center;
        padding: 1rem;
        border: 1px solid #eee;
        border-radius: 10px;
        margin-bottom: 0.8rem;
        transition: all 0.2s;
    }
    
    .saved-method:hover {
        background: #f8f9fa;
    }
    
    .method-icon-small {
        width: 40px;
        height: 40px;
        background: #eef2ff;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        color: #4361ee;
    }
    
    .default-badge {
        background: #d4edda;
        color: #155724;
        padding: 0.2rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .transaction-item {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .transaction-amount {
            text-align: left;
            margin-left: 3.5rem;
        }
        
        .receipt-btn, .pay-btn {
            margin-left: 3.5rem;
        }
        
        .payment-modal-content {
            margin: 20px;
        }
        
        .summary-amount {
            font-size: 1.5rem;
        }
        
        .summary-card {
            padding: 1.2rem;
        }
    }
</style>
@endsection

@section('content')
<div class="row fade-in">
    <!-- Summary Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="summary-card">
            <div class="summary-icon">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div class="summary-label">Total Balance</div>
            <div class="summary-amount">₱5,350.00</div>
            <div class="summary-due">
                <i class="bi bi-exclamation-circle"></i>
                Due in 3 days
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="summary-card">
            <div class="summary-icon">
                <i class="bi bi-calendar-check"></i>
            </div>
            <div class="summary-label">Monthly Rent</div>
            <div class="summary-amount">₱4,500.00</div>
            <div class="text-success small">
                <i class="bi bi-check-circle"></i>
                Fixed rate
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="summary-card">
            <div class="summary-icon">
                <i class="bi bi-lightning"></i>
            </div>
            <div class="summary-label">Utilities (Jan)</div>
            <div class="summary-amount">₱850.00</div>
            <div class="text-warning small">
                <i class="bi bi-clock"></i>
                Pending
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="summary-card">
            <div class="summary-icon">
                <i class="bi bi-piggy-bank"></i>
            </div>
            <div class="summary-label">Security Deposit</div>
            <div class="summary-amount">₱3,000.00</div>
            <div class="text-info small">
                <i class="bi bi-shield-check"></i>
                Fully paid
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="col-lg-8 mb-4">
        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <span class="filter-tab active" onclick="filterTransactions('all', this)">All Transactions</span>
            <span class="filter-tab" onclick="filterTransactions('paid', this)">Paid</span>
            <span class="filter-tab" onclick="filterTransactions('pending', this)">Pending</span>
            <span class="filter-tab" onclick="filterTransactions('overdue', this)">Overdue</span>
        </div>

        <!-- Transactions Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Transaction History</h5>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-download me-1"></i>Export
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="exportData('pdf', event)">Export as PDF</a></li>
                        <li><a class="dropdown-item" href="#" onclick="exportData('csv', event)">Export as CSV</a></li>
                        <li><a class="dropdown-item" href="#" onclick="exportData('print', event)">Print</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="transaction-list">
                    <!-- Paid Transaction -->
                    <div class="transaction-item" data-status="paid">
                        <div class="transaction-icon success">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <div class="transaction-details">
                            <div class="transaction-title">Rent Payment - February 2026</div>
                            <div class="transaction-meta">
                                <i class="bi bi-calendar me-1"></i> Feb 1, 2026
                                <span class="mx-2">•</span>
                                <i class="bi bi-credit-card me-1"></i> Credit Card
                                <span class="mx-2">•</span>
                                <span class="status-badge status-paid">
                                    <i class="bi bi-check-circle-fill"></i> Paid
                                </span>
                            </div>
                        </div>
                        <div class="transaction-amount">₱4,500.00</div>
                        <button class="receipt-btn" onclick="viewReceipt('INV-2026-001')">
                            <i class="bi bi-file-pdf me-1"></i>Receipt
                        </button>
                    </div>

                    <!-- Paid Transaction -->
                    <div class="transaction-item" data-status="paid">
                        <div class="transaction-icon success">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <div class="transaction-details">
                            <div class="transaction-title">Rent Payment - January 2026</div>
                            <div class="transaction-meta">
                                <i class="bi bi-calendar me-1"></i> Jan 3, 2026
                                <span class="mx-2">•</span>
                                <i class="bi bi-cash me-1"></i> Cash
                                <span class="mx-2">•</span>
                                <span class="status-badge status-paid">
                                    <i class="bi bi-check-circle-fill"></i> Paid
                                </span>
                            </div>
                        </div>
                        <div class="transaction-amount">₱4,500.00</div>
                        <button class="receipt-btn" onclick="viewReceipt('INV-2026-002')">
                            <i class="bi bi-file-pdf me-1"></i>Receipt
                        </button>
                    </div>

                    <!-- Pending Transaction -->
                    <div class="transaction-item" data-status="pending">
                        <div class="transaction-icon pending">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                        <div class="transaction-details">
                            <div class="transaction-title">Utilities - January 2026</div>
                            <div class="transaction-meta">
                                <i class="bi bi-calendar me-1"></i> Due: Feb 28, 2026
                                <span class="mx-2">•</span>
                                <i class="bi bi-wifi me-1"></i> Electricity, Water
                                <span class="mx-2">•</span>
                                <span class="status-badge status-pending">
                                    <i class="bi bi-clock"></i> Pending
                                </span>
                            </div>
                        </div>
                        <div class="transaction-amount">₱850.00</div>
                        <button class="pay-btn" onclick="paySpecificBill('utilities')">
                            Pay Now
                        </button>
                    </div>

                    <!-- Overdue Transaction -->
                    <div class="transaction-item" data-status="overdue">
                        <div class="transaction-icon failed">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                        <div class="transaction-details">
                            <div class="transaction-title">Late Fee - December 2025</div>
                            <div class="transaction-meta">
                                <i class="bi bi-calendar me-1"></i> Due: Jan 5, 2026
                                <span class="mx-2">•</span>
                                <span class="status-badge status-overdue">
                                    <i class="bi bi-exclamation-circle-fill"></i> Overdue
                                </span>
                            </div>
                        </div>
                        <div class="transaction-amount text-danger">₱200.00</div>
                        <button class="pay-btn danger" onclick="paySpecificBill('latefee')">
                            Pay Fee
                        </button>
                    </div>

                    <!-- Partial Payment -->
                    <div class="transaction-item" data-status="pending">
                        <div class="transaction-icon pending">
                            <i class="bi bi-pie-chart"></i>
                        </div>
                        <div class="transaction-details">
                            <div class="transaction-title">Security Deposit (Installment 2/3)</div>
                            <div class="transaction-meta">
                                <i class="bi bi-calendar me-1"></i> Next due: Mar 15, 2026
                                <span class="mx-2">•</span>
                                <span class="status-badge status-partial">
                                    <i class="bi bi-arrow-repeat"></i> Partial (₱1,000/₱3,000)
                                </span>
                            </div>
                        </div>
                        <div class="transaction-amount">₱1,000.00</div>
                        <button class="pay-btn" onclick="paySpecificBill('deposit')">
                            Continue
                        </button>
                    </div>

                    <!-- Paid Transaction -->
                    <div class="transaction-item" data-status="paid">
                        <div class="transaction-icon success">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <div class="transaction-details">
                            <div class="transaction-title">Rent Payment - December 2025</div>
                            <div class="transaction-meta">
                                <i class="bi bi-calendar me-1"></i> Dec 2, 2025
                                <span class="mx-2">•</span>
                                <i class="bi bi-bank me-1"></i> Bank Transfer
                                <span class="mx-2">•</span>
                                <span class="status-badge status-paid">
                                    <i class="bi bi-check-circle-fill"></i> Paid
                                </span>
                            </div>
                        </div>
                        <div class="transaction-amount">₱4,500.00</div>
                        <button class="receipt-btn" onclick="viewReceipt('INV-2025-089')">
                            <i class="bi bi-file-pdf me-1"></i>Receipt
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Pagination -->
            <div class="card-footer bg-white border-0 py-3">
                <nav aria-label="Transaction pagination">
                    <ul class="pagination justify-content-center mb-0">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- Right Sidebar -->
    <div class="col-lg-4 mb-4">
        <!-- Upcoming Bills -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold">Upcoming Bills</h5>
            </div>
            <div class="card-body p-0">
                <div class="bill-item">
                    <div>
                        <div class="bill-title">March Rent</div>
                        <div class="bill-date">Due: March 3, 2026</div>
                    </div>
                    <div class="text-end">
                        <div class="bill-amount">₱4,500.00</div>
                        <span class="days-left">5 days left</span>
                    </div>
                </div>
                <div class="bill-item">
                    <div>
                        <div class="bill-title">February Utilities</div>
                        <div class="bill-date">Due: March 10, 2026</div>
                    </div>
                    <div class="text-end">
                        <div class="bill-amount">₱950.00</div>
                        <span class="days-left">12 days left</span>
                    </div>
                </div>
                <div class="bill-item">
                    <div>
                        <div class="bill-title">WiFi Subscription</div>
                        <div class="bill-date">Due: March 15, 2026</div>
                    </div>
                    <div class="text-end">
                        <div class="bill-amount">₱300.00</div>
                        <span class="badge bg-success">Auto-pay</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Saved Payment Methods</h5>
                <a href="#" class="text-primary small text-decoration-none" onclick="addPaymentMethod(event)">+ Add New</a>
            </div>
            <div class="card-body">
                <div class="saved-method">
                    <div class="method-icon-small">
                        <i class="bi bi-credit-card"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold">Credit Card</div>
                        <small class="text-secondary">**** **** **** 4242</small>
                    </div>
                    <span class="default-badge">Default</span>
                </div>
                
                <div class="saved-method">
                    <div class="method-icon-small">
                        <i class="bi bi-bank"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold">Bank Account</div>
                        <small class="text-secondary">BPI - **** 1234</small>
                    </div>
                </div>
                
                <div class="saved-method">
                    <div class="method-icon-small">
                        <i class="bi bi-phone"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold">GCash</div>
                        <small class="text-secondary">0912*******</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Summary Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold">Payment Summary</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-secondary">Total Paid (2026):</span>
                    <span class="fw-bold">₱13,500.00</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-secondary">Pending Payments:</span>
                    <span class="fw-bold text-warning">₱1,850.00</span>
                </div>
                <div class="d-flex justify-content-between mb-4">
                    <span class="text-secondary">Overdue Amount:</span>
                    <span class="fw-bold text-danger">₱200.00</span>
                </div>
                
                <hr>
                
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-primary" onclick="viewStatement()">
                        <i class="bi bi-file-text me-2"></i>View Statement
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div id="paymentModal" class="payment-modal">
    <div class="payment-modal-content">
        <div class="modal-header">
            <h5 class="mb-0 fw-bold"><i class="bi bi-credit-card me-2"></i>Make a Payment</h5>
            <button type="button" class="btn-close btn-close-white" onclick="closePaymentModal()" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <!-- Payment Summary -->
            <div class="payment-summary">
                <h6 class="fw-bold mb-3">Payment Summary</h6>
                <div class="summary-row">
                    <span>Rent (February 2026):</span>
                    <span>₱4,500.00</span>
                </div>
                <div class="summary-row">
                    <span>Utilities (January 2026):</span>
                    <span>₱850.00</span>
                </div>
                <div class="summary-row">
                    <span>Late Fee:</span>
                    <span>₱0.00</span>
                </div>
                <div class="summary-row total">
                    <span>Total Amount Due:</span>
                    <span>₱5,350.00</span>
                </div>
            </div>
            
            <!-- Payment Method Selection -->
            <h6 class="fw-bold mb-3">Select Payment Method</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="method-card selected" onclick="selectPaymentMethod(this, 'card')">
                        <div class="method-icon">
                            <i class="bi bi-credit-card"></i>
                        </div>
                        <div class="method-name">Credit/Debit Card</div>
                        <div class="method-desc">Pay via card online</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="method-card" onclick="selectPaymentMethod(this, 'bank')">
                        <div class="method-icon">
                            <i class="bi bi-bank"></i>
                        </div>
                        <div class="method-name">Bank Transfer</div>
                        <div class="method-desc">BPI, BDO, Metrobank</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="method-card" onclick="selectPaymentMethod(this, 'gcash')">
                        <div class="method-icon">
                            <i class="bi bi-phone"></i>
                        </div>
                        <div class="method-name">GCash / Maya</div>
                        <div class="method-desc">Instant payment</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="method-card" onclick="selectPaymentMethod(this, 'cash')">
                        <div class="method-icon">
                            <i class="bi bi-cash"></i>
                        </div>
                        <div class="method-name">Cash</div>
                        <div class="method-desc">Pay at the office</div>
                    </div>
                </div>
            </div>
            
            <!-- Payment Form (Card) -->
            <div id="cardForm" class="payment-form">
                <div class="mb-3">
                    <label class="form-label">Card Number</label>
                    <input type="text" class="form-control" id="cardNumber" placeholder="1234 5678 9012 3456" maxlength="19">
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Expiry Date</label>
                        <input type="text" class="form-control" id="expiryDate" placeholder="MM/YY" maxlength="5">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">CVV</label>
                        <input type="text" class="form-control" id="cvv" placeholder="123" maxlength="3">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Cardholder Name</label>
                    <input type="text" class="form-control" id="cardName" placeholder="John Doe">
                </div>
            </div>
            
            <!-- Payment Form (Bank Transfer) -->
            <div id="bankForm" class="payment-form" style="display: none;">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    Transfer to the following bank account:
                </div>
                <div class="mb-3 p-3 bg-light rounded-3">
                    <div class="row mb-2">
                        <div class="col-5 text-secondary">Bank:</div>
                        <div class="col-7 fw-bold">BPI</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-secondary">Account Name:</div>
                        <div class="col-7 fw-bold">StayEase Boarding House</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-secondary">Account Number:</div>
                        <div class="col-7 fw-bold">1234-5678-90</div>
                    </div>
                    <div class="row">
                        <div class="col-5 text-secondary">Amount:</div>
                        <div class="col-7 fw-bold">₱5,350.00</div>
                    </div>
                </div>
                
                <!-- Proof of Payment Upload -->
                <div class="proof-upload" onclick="document.getElementById('proofFile').click()">
                    <i class="bi bi-cloud-upload"></i>
                    <p class="fw-bold">Click to upload proof of payment</p>
                    <small class="text-secondary">PDF, JPG, PNG (Max 5MB)</small>
                    <input type="file" id="proofFile" style="display: none;" accept=".pdf,.jpg,.jpeg,.png" onchange="handleFileUpload(this)">
                </div>
                
                <div id="fileInfo" class="file-info">
                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                    <span id="fileName"></span>
                </div>
            </div>
            
            <!-- Payment Form (GCash) -->
            <div id="gcashForm" class="payment-form" style="display: none;">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    Send payment to:
                </div>
                <div class="mb-3 p-3 bg-light rounded-3">
                    <div class="row mb-2">
                        <div class="col-5 text-secondary">GCash Number:</div>
                        <div class="col-7 fw-bold">0912 345 6789</div>
                    </div>
                    <div class="row">
                        <div class="col-5 text-secondary">Account Name:</div>
                        <div class="col-7 fw-bold">StayEase Boarding</div>
                    </div>
                </div>
                
                <!-- Reference Number -->
                <div class="mb-3">
                    <label class="form-label">Reference Number</label>
                    <input type="text" class="form-control" id="gcashRef" placeholder="Enter GCash reference number">
                </div>
            </div>
            
            <!-- Payment Form (Cash) -->
            <div id="cashForm" class="payment-form" style="display: none;">
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Please pay at the boarding house office during office hours.
                </div>
                <div class="mb-3 p-3 bg-light rounded-3">
                    <div class="row mb-2">
                        <div class="col-5 text-secondary">Office Hours:</div>
                        <div class="col-7">8:00 AM - 8:00 PM</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-secondary">Location:</div>
                        <div class="col-7">Ground Floor, Admin Office</div>
                    </div>
                    <div class="row">
                        <div class="col-5 text-secondary">Contact:</div>
                        <div class="col-7">(02) 1234 5678</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closePaymentModal()">Cancel</button>
            <button class="btn btn-primary" onclick="processPayment()">
                <i class="bi bi-lock me-2"></i>Pay ₱5,350.00
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Filter Transactions
    function filterTransactions(status, element) {
        // Update active tab
        document.querySelectorAll('.filter-tab').forEach(tab => {
            tab.classList.remove('active');
        });
        element.classList.add('active');
        
        // Filter transaction items
        const transactions = document.querySelectorAll('.transaction-item');
        transactions.forEach(transaction => {
            if (status === 'all') {
                transaction.style.display = 'flex';
            } else {
                if (transaction.dataset.status === status) {
                    transaction.style.display = 'flex';
                } else {
                    transaction.style.display = 'none';
                }
            }
        });
        
        showNotification(`Showing ${status} transactions`, 'info');
    }

    // Payment Modal Functions
    function openPaymentModal() {
        document.getElementById('paymentModal').style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
    
    function closePaymentModal() {
        document.getElementById('paymentModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    
    // Select Payment Method
    function selectPaymentMethod(element, method) {
        // Update selected card
        document.querySelectorAll('.method-card').forEach(card => {
            card.classList.remove('selected');
        });
        element.classList.add('selected');
        
        // Show corresponding form
        document.querySelectorAll('.payment-form').forEach(form => {
            form.style.display = 'none';
        });
        
        document.getElementById(method + 'Form').style.display = 'block';
    }
    
    // Handle File Upload
    function handleFileUpload(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            // Check file size (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('File size must be less than 5MB');
                input.value = '';
                return;
            }
            
            document.getElementById('fileName').textContent = file.name;
            document.getElementById('fileInfo').classList.add('show');
        }
    }
    
    // Pay Specific Bill
    function paySpecificBill(billType) {
        openPaymentModal();
        
        let message = '';
        
        switch(billType) {
            case 'utilities':
                message = 'Processing utilities payment';
                break;
            case 'latefee':
                message = 'Processing late fee payment';
                break;
            case 'deposit':
                message = 'Continuing deposit payment';
                break;
            default:
                message = 'Processing payment';
        }
        
        showNotification(message, 'info');
    }
    
    // Process Payment
    function processPayment() {
        // Validate form based on selected method
        const selectedMethod = document.querySelector('.method-card.selected');
        if (!selectedMethod) {
            alert('Please select a payment method');
            return;
        }
        
        // Simulate payment processing
        showNotification('Processing payment...', 'info');
        
        setTimeout(() => {
            showNotification('Payment successful! Your transaction has been recorded.', 'success');
            closePaymentModal();
        }, 1500);
    }
    
    // View Receipt
    function viewReceipt(invoiceNumber) {
        showNotification(`Opening receipt for ${invoiceNumber}`, 'info');
    }
    
    // Export Data
    function exportData(format, event) {
        event.preventDefault();
        showNotification(`Exporting as ${format.toUpperCase()}...`, 'info');
        
        setTimeout(() => {
            showNotification(`Export completed successfully`, 'success');
        }, 1500);
    }
    
    // View Statement
    function viewStatement() {
        showNotification('Generating payment statement...', 'info');
        
        setTimeout(() => {
            showNotification('Statement ready for download', 'success');
        }, 1500);
    }
    
    // Add Payment Method
    function addPaymentMethod(event) {
        event.preventDefault();
        showNotification('Add new payment method - Form would open', 'info');
    }
    
    // Format Card Number
    document.getElementById('cardNumber')?.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 16) value = value.slice(0, 16);
        value = value.replace(/(\d{4})(?=\d)/g, '$1 ');
        e.target.value = value;
    });
    
    // Format Expiry Date
    document.getElementById('expiryDate')?.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 4) value = value.slice(0, 4);
        if (value.length > 2) {
            value = value.slice(0, 2) + '/' + value.slice(2);
        }
        e.target.value = value;
    });
    
    // Format CVV (numbers only)
    document.getElementById('cvv')?.addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/\D/g, '').slice(0, 3);
    });
    
    // Show Notification Function
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
        notification.style.zIndex = '9999';
        notification.style.minWidth = '300px';
        notification.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="bi bi-${type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-circle' : 'info-circle'}-fill me-2"></i>
                <span>${message}</span>
                <button type="button" class="btn-close ms-3" onclick="this.parentElement.parentElement.remove()"></button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('paymentModal');
        if (event.target === modal) {
            closePaymentModal();
        }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closePaymentModal();
        }
    });
</script>
@endsection