@extends('layouts.admin')

@section('title', 'Users & Roles Management - StayEase Admin')

@section('page_header', 'Users & Roles Management')

@section('page_description', 'Manage system users, roles, permissions and access control')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Users & Roles</li>
@endsection

@section('header_actions')
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
        <i class="bi bi-person-plus me-2"></i>Add New User
    </button>
    <button class="btn btn-outline-primary ms-2" data-bs-toggle="modal" data-bs-target="#addRoleModal">
        <i class="bi bi-shield-plus me-2"></i>Create Role
    </button>
    <div class="btn-group ms-2">
        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
            <i class="bi bi-download me-2"></i>Export
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="#" onclick="exportData('users')"><i class="bi bi-people me-2"></i>Export Users</a></li>
            <li><a class="dropdown-item" href="#" onclick="exportData('roles')"><i class="bi bi-shield me-2"></i>Export Roles</a></li>
            <li><a class="dropdown-item" href="#" onclick="exportData('permissions')"><i class="bi bi-key me-2"></i>Export Permissions</a></li>
        </ul>
    </div>
@endsection

@section('styles')
<style>
    /* Stats Cards */
    .users-stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.03);
        border: 1px solid rgba(0,0,0,0.03);
        height: 100%;
        transition: all 0.3s;
    }
    
    .users-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.1);
        border-color: rgba(102, 126, 234, 0.15);
    }
    
    .users-stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }
    
    .users-stat-icon.primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .users-stat-icon.success {
        background: linear-gradient(135deg, #34b1aa 0%, #2c9a94 100%);
    }
    
    .users-stat-icon.warning {
        background: linear-gradient(135deg, #f6b23e 0%, #f4a51e 100%);
    }
    
    .users-stat-icon.info {
        background: linear-gradient(135deg, #3b7cff 0%, #2b6ef0 100%);
    }
    
    .users-stat-value {
        font-size: 1.6rem;
        font-weight: 700;
        color: #2d3748;
        line-height: 1.2;
    }
    
    .users-stat-label {
        color: #718096;
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    .users-stat-change {
        font-size: 0.75rem;
        padding: 0.2rem 0.4rem;
        border-radius: 16px;
        background: #f0fff4;
        color: #2ecc71;
    }
    
    .users-stat-change.negative {
        background: #fff5f5;
        color: #e74c3c;
    }
    
    /* Tab Navigation */
    .users-tabs {
        border-bottom: 1px solid #edf2f7;
        margin-bottom: 1.5rem;
        background: white;
        border-radius: 16px 16px 0 0;
        padding: 0.5rem 1rem 0;
    }
    
    .users-tabs .nav-link {
        color: #718096;
        font-weight: 500;
        padding: 0.75rem 1.25rem;
        border: none;
        position: relative;
        font-size: 0.95rem;
    }
    
    .users-tabs .nav-link i {
        margin-right: 0.5rem;
        font-size: 1rem;
    }
    
    .users-tabs .nav-link.active {
        color: #667eea;
        background: none;
    }
    
    .users-tabs .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 1rem;
        right: 1rem;
        height: 3px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 3px 3px 0 0;
    }
    
    .users-tabs .nav-link .badge {
        margin-left: 0.5rem;
        font-size: 0.65rem;
        padding: 0.25rem 0.5rem;
    }
    
    /* User Table Styles */
    .user-avatar {
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
    
    .user-info h6 {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.2rem;
        font-size: 0.95rem;
    }
    
    .user-info small {
        color: #718096;
        font-size: 0.75rem;
    }
    
    .role-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 600;
        margin-right: 0.25rem;
        margin-bottom: 0.25rem;
    }
    
    .role-badge.super-admin {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .role-badge.admin {
        background: #e3f2fd;
        color: #1976d2;
    }
    
    .role-badge.manager {
        background: #fff3e0;
        color: #f39c12;
    }
    
    .role-badge.staff {
        background: #e8f5e9;
        color: #27ae60;
    }
    
    .role-badge.viewer {
        background: #f3e5f5;
        color: #9c27b0;
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
    
    .badge-status.inactive {
        background: #fee9e9;
        color: #e74c3c;
    }
    
    .badge-status.suspended {
        background: #2d3748;
        color: white;
    }
    
    .badge-status.pending {
        background: #fff3e0;
        color: #f39c12;
    }
    
    .action-buttons .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
        margin: 0 2px;
    }
    
    /* Role Cards */
    .role-card {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.03);
        border: 1px solid rgba(0,0,0,0.03);
        height: 100%;
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
    }
    
    .role-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.1);
        border-color: rgba(102, 126, 234, 0.15);
    }
    
    .role-card.super-admin {
        border-top: 4px solid #667eea;
    }
    
    .role-card.admin {
        border-top: 4px solid #1976d2;
    }
    
    .role-card.manager {
        border-top: 4px solid #f39c12;
    }
    
    .role-card.staff {
        border-top: 4px solid #27ae60;
    }
    
    .role-card.viewer {
        border-top: 4px solid #9c27b0;
    }
    
    .role-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }
    
    .role-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2d3748;
        margin: 0;
    }
    
    .role-badge-count {
        background: #edf2f7;
        padding: 0.2rem 0.6rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    
    .role-description {
        color: #718096;
        font-size: 0.85rem;
        margin-bottom: 1rem;
        line-height: 1.5;
    }
    
    .role-permissions {
        margin-bottom: 1rem;
    }
    
    .permission-tag {
        display: inline-block;
        padding: 0.2rem 0.5rem;
        background: #f8fafc;
        border-radius: 6px;
        font-size: 0.7rem;
        color: #4a5568;
        margin-right: 0.25rem;
        margin-bottom: 0.25rem;
        border: 1px solid #edf2f7;
    }
    
    .role-users {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-top: 1px solid #edf2f7;
        padding-top: 1rem;
        margin-top: 0.5rem;
    }
    
    .user-avatars {
        display: flex;
        align-items: center;
    }
    
    .avatar-mini {
        width: 30px;
        height: 30px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.7rem;
        margin-right: -8px;
        border: 2px solid white;
    }
    
    .avatar-mini:first-child {
        margin-right: 0;
    }
    
    .more-count {
        width: 30px;
        height: 30px;
        background: #edf2f7;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #718096;
        font-weight: 600;
        font-size: 0.7rem;
        margin-left: 4px;
    }
    
    /* Permission Tree */
    .permission-tree {
        padding: 1rem;
        background: #f8fafc;
        border-radius: 12px;
        max-height: 400px;
        overflow-y: auto;
    }
    
    .permission-category {
        margin-bottom: 1rem;
    }
    
    .category-header {
        font-weight: 600;
        color: #2d3748;
        padding: 0.5rem;
        background: white;
        border-radius: 8px;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .category-header .form-check {
        margin-bottom: 0;
    }
    
    .permission-item {
        padding: 0.3rem 0.5rem 0.3rem 2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .permission-name {
        color: #4a5568;
        font-size: 0.9rem;
    }
    
    .permission-name i {
        color: #667eea;
        margin-right: 0.3rem;
        font-size: 0.8rem;
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
    
    /* Responsive */
    @media (max-width: 768px) {
        .users-stat-value {
            font-size: 1.3rem;
        }
        
        .users-stat-icon {
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
        
        .users-tabs .nav-link {
            padding: 0.5rem 0.75rem;
            font-size: 0.8rem;
        }
        
        .users-tabs .nav-link i {
            margin-right: 0.3rem;
        }
        
        .role-card {
            padding: 1rem;
        }
    }
    
    /* Modal Styles */
    .users-form-section {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .users-form-section h6 {
        font-size: 0.9rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .users-form-section h6 i {
        color: #667eea;
    }
    
    /* Permission Grid */
    .permission-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        max-height: 300px;
        overflow-y: auto;
        padding: 0.5rem;
    }
    
    .permission-checkbox {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem;
        background: white;
        border-radius: 8px;
        border: 1px solid #edf2f7;
    }
    
    .permission-checkbox:hover {
        background: #f8fafc;
    }
    
    /* Responsive */
    @media (max-width: 576px) {
        .permission-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="fade-in">
    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="users-stat-card">
                <div class="d-flex align-items-center">
                    <div class="users-stat-icon primary me-3">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="users-stat-value">24</div>
                        <div class="users-stat-label">Total Users</div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Active: 18</span>
                        <span class="users-stat-change">
                            <i class="bi bi-arrow-up"></i> +3 this month
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="users-stat-card">
                <div class="d-flex align-items-center">
                    <div class="users-stat-icon success me-3">
                        <i class="bi bi-shield"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="users-stat-value">8</div>
                        <div class="users-stat-label">Roles</div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Custom: 5</span>
                        <span class="users-stat-change">
                            <i class="bi bi-arrow-up"></i> +2
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="users-stat-card">
                <div class="d-flex align-items-center">
                    <div class="users-stat-icon warning me-3">
                        <i class="bi bi-key"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="users-stat-value">42</div>
                        <div class="users-stat-label">Permissions</div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Assigned: 38</span>
                        <span class="users-stat-change">
                            <i class="bi bi-arrow-up"></i> +4
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="users-stat-card">
                <div class="d-flex align-items-center">
                    <div class="users-stat-icon info me-3">
                        <i class="bi bi-person-check"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="users-stat-value">6</div>
                        <div class="users-stat-label">Online Now</div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Active sessions</span>
                        <span class="users-stat-change">
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
                    <div class="quick-stat-number">2</div>
                    <div class="quick-stat-label">Super Admins</div>
                </div>
            </div>
            <div class="col-3">
                <div class="quick-stat-item">
                    <div class="quick-stat-number">5</div>
                    <div class="quick-stat-label">Admins</div>
                </div>
            </div>
            <div class="col-3">
                <div class="quick-stat-item">
                    <div class="quick-stat-number">8</div>
                    <div class="quick-stat-label">Managers</div>
                </div>
            </div>
            <div class="col-3">
                <div class="quick-stat-item">
                    <div class="quick-stat-number">9</div>
                    <div class="quick-stat-label">Staff</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <ul class="nav nav-tabs users-tabs" id="usersRolesTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab">
                <i class="bi bi-people"></i>Users
                <span class="badge bg-primary">24</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="roles-tab" data-bs-toggle="tab" data-bs-target="#roles" type="button" role="tab">
                <i class="bi bi-shield"></i>Roles
                <span class="badge bg-success">8</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="permissions-tab" data-bs-toggle="tab" data-bs-target="#permissions" type="button" role="tab">
                <i class="bi bi-key"></i>Permissions
                <span class="badge bg-warning">42</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity" type="button" role="tab">
                <i class="bi bi-clock-history"></i>Activity Log
                <span class="badge bg-info">156</span>
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="usersRolesTabContent">
        <!-- Users Tab -->
        <div class="tab-pane fade show active" id="users" role="tabpanel">
            <!-- Filter Section -->
            <div class="filter-section">
                <div class="row g-3">
                    <div class="col-lg-3 col-md-6">
                        <div class="filter-label">Search</div>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-0 bg-light" id="searchUser" placeholder="Name, email, username...">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <div class="filter-label">Role</div>
                        <select class="form-select bg-light border-0" id="filterRole">
                            <option value="">All Roles</option>
                            <option value="super-admin">Super Admin</option>
                            <option value="admin">Admin</option>
                            <option value="manager">Manager</option>
                            <option value="staff">Staff</option>
                            <option value="viewer">Viewer</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <div class="filter-label">Status</div>
                        <select class="form-select bg-light border-0" id="filterUserStatus">
                            <option value="">All</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="suspended">Suspended</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <div class="filter-label">Department</div>
                        <select class="form-select bg-light border-0" id="filterDepartment">
                            <option value="">All</option>
                            <option value="it">IT</option>
                            <option value="hr">HR</option>
                            <option value="finance">Finance</option>
                            <option value="operations">Operations</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-12">
                        <div class="filter-label">Joined Date</div>
                        <div class="d-flex gap-2">
                            <input type="text" class="form-control bg-light border-0" id="dateFrom" placeholder="From">
                            <input type="text" class="form-control bg-light border-0" id="dateTo" placeholder="To">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="table-container">
                <div class="table-header">
                    <h5 class="table-title">System Users</h5>
                    <div class="d-flex align-items-center gap-3">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-gear"></i> Bulk Actions
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="bulkAction('activate')"><i class="bi bi-check-circle me-2"></i>Activate</a></li>
                                <li><a class="dropdown-item" href="#" onclick="bulkAction('deactivate')"><i class="bi bi-pause-circle me-2"></i>Deactivate</a></li>
                                <li><a class="dropdown-item" href="#" onclick="bulkAction('assign-role')"><i class="bi bi-shield me-2"></i>Assign Role</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#" onclick="bulkAction('delete')"><i class="bi bi-trash me-2"></i>Delete</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover" id="usersTable">
                        <thead>
                            <tr>
                                <th width="40">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAllUsers">
                                    </div>
                                </th>
                                <th>User</th>
                                <th>Role</th>
                                <th>Department</th>
                                <th>Status</th>
                                <th>Last Active</th>
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
                                        <div class="user-avatar">AD</div>
                                        <div class="user-info">
                                            <h6>Admin User</h6>
                                            <small>admin@stayease.com</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="role-badge super-admin">Super Admin</span>
                                </td>
                                <td>IT Administration</td>
                                <td>
                                    <span class="badge-status active">Active</span>
                                </td>
                                <td>
                                    <div>
                                        <div>Online now</div>
                                        <small class="text-success">Active session</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div>Jan 15, 2025</div>
                                        <small class="text-muted">1 year ago</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-sm btn-outline-primary" title="View" onclick="viewUser(1)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" title="Edit" onclick="editUser(1)">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-info" title="Permissions" onclick="userPermissions(1)">
                                            <i class="bi bi-key"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-success" title="Impersonate" onclick="impersonateUser(1)">
                                            <i class="bi bi-person-badge"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-warning" title="Reset Password" onclick="resetPassword(1)">
                                            <i class="bi bi-arrow-repeat"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" title="Delete" onclick="deleteUser(1)">
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
                                        <div class="user-avatar">JS</div>
                                        <div class="user-info">
                                            <h6>John Smith</h6>
                                            <small>john.smith@stayease.com</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="role-badge admin">Admin</span>
                                </td>
                                <td>Operations</td>
                                <td>
                                    <span class="badge-status active">Active</span>
                                </td>
                                <td>
                                    <div>
                                        <div>2 hours ago</div>
                                        <small class="text-muted">Chrome/Win</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div>Mar 10, 2025</div>
                                        <small class="text-muted">1 year ago</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                                        <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></button>
                                        <button class="btn btn-sm btn-outline-info"><i class="bi bi-key"></i></button>
                                        <button class="btn btn-sm btn-outline-success"><i class="bi bi-person-badge"></i></button>
                                        <button class="btn btn-sm btn-outline-warning"><i class="bi bi-arrow-repeat"></i></button>
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
                                        <div class="user-avatar">MJ</div>
                                        <div class="user-info">
                                            <h6>Mary Johnson</h6>
                                            <small>mary.j@stayease.com</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="role-badge manager">Manager</span>
                                </td>
                                <td>Finance</td>
                                <td>
                                    <span class="badge-status active">Active</span>
                                </td>
                                <td>
                                    <div>
                                        <div>1 day ago</div>
                                        <small class="text-muted">iPhone/Safari</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div>Jun 5, 2025</div>
                                        <small class="text-muted">9 months ago</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                                        <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></button>
                                        <button class="btn btn-sm btn-outline-info"><i class="bi bi-key"></i></button>
                                        <button class="btn btn-sm btn-outline-success"><i class="bi bi-person-badge"></i></button>
                                        <button class="btn btn-sm btn-outline-warning"><i class="bi bi-arrow-repeat"></i></button>
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
                                        <div class="user-avatar">RB</div>
                                        <div class="user-info">
                                            <h6>Robert Brown</h6>
                                            <small>robert.b@stayease.com</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="role-badge staff">Staff</span>
                                </td>
                                <td>HR</td>
                                <td>
                                    <span class="badge-status inactive">Inactive</span>
                                </td>
                                <td>
                                    <div>
                                        <div>5 days ago</div>
                                        <small class="text-muted">Never</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div>Sep 20, 2025</div>
                                        <small class="text-muted">6 months ago</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                                        <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></button>
                                        <button class="btn btn-sm btn-outline-info"><i class="bi bi-key"></i></button>
                                        <button class="btn btn-sm btn-outline-success"><i class="bi bi-person-badge"></i></button>
                                        <button class="btn btn-sm btn-outline-warning"><i class="bi bi-arrow-repeat"></i></button>
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
                                        <div class="user-avatar">LD</div>
                                        <div class="user-info">
                                            <h6>Lisa Davis</h6>
                                            <small>lisa.d@stayease.com</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="role-badge viewer">Viewer</span>
                                </td>
                                <td>Marketing</td>
                                <td>
                                    <span class="badge-status pending">Pending</span>
                                </td>
                                <td>
                                    <div>
                                        <div>Never</div>
                                        <small class="text-muted">Email not verified</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div>Mar 1, 2026</div>
                                        <small class="text-muted">2 weeks ago</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                                        <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></button>
                                        <button class="btn btn-sm btn-outline-info"><i class="bi bi-key"></i></button>
                                        <button class="btn btn-sm btn-outline-success"><i class="bi bi-person-badge"></i></button>
                                        <button class="btn btn-sm btn-outline-warning"><i class="bi bi-arrow-repeat"></i></button>
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="small text-muted">
                        Showing 5 of 24 users
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
        
        <!-- Roles Tab -->
        <div class="tab-pane fade" id="roles" role="tabpanel">
            <!-- Role Cards Grid -->
            <div class="row g-3 mb-4">
                <div class="col-lg-4 col-md-6">
                    <div class="role-card super-admin">
                        <div class="role-header">
                            <h5 class="role-title">Super Admin</h5>
                            <span class="role-badge-count">2 users</span>
                        </div>
                        <p class="role-description">Full system access with all permissions. Can manage users, roles, and system settings.</p>
                        <div class="role-permissions">
                            <span class="permission-tag">All Permissions</span>
                            <span class="permission-tag">User Management</span>
                            <span class="permission-tag">Role Management</span>
                            <span class="permission-tag">System Config</span>
                            <span class="permission-tag">+12 more</span>
                        </div>
                        <div class="role-users">
                            <div class="user-avatars">
                                <div class="avatar-mini">AD</div>
                                <div class="avatar-mini">JS</div>
                                <div class="more-count">+0</div>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-outline-primary me-1" onclick="editRole('super-admin')">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteRole('super-admin')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="role-card admin">
                        <div class="role-header">
                            <h5 class="role-title">Admin</h5>
                            <span class="role-badge-count">5 users</span>
                        </div>
                        <p class="role-description">Can manage properties, rooms, occupants, and generate reports. Cannot manage users or roles.</p>
                        <div class="role-permissions">
                            <span class="permission-tag">Property Management</span>
                            <span class="permission-tag">Room Management</span>
                            <span class="permission-tag">Occupant Management</span>
                            <span class="permission-tag">Reports</span>
                            <span class="permission-tag">+8 more</span>
                        </div>
                        <div class="role-users">
                            <div class="user-avatars">
                                <div class="avatar-mini">MJ</div>
                                <div class="avatar-mini">RB</div>
                                <div class="avatar-mini">LD</div>
                                <div class="more-count">+2</div>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-outline-primary me-1" onclick="editRole('admin')">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteRole('admin')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="role-card manager">
                        <div class="role-header">
                            <h5 class="role-title">Manager</h5>
                            <span class="role-badge-count">8 users</span>
                        </div>
                        <p class="role-description">Can manage day-to-day operations, handle payments, and respond to maintenance requests.</p>
                        <div class="role-permissions">
                            <span class="permission-tag">Payment Management</span>
                            <span class="permission-tag">Maintenance</span>
                            <span class="permission-tag">Tenant Communication</span>
                            <span class="permission-tag">+6 more</span>
                        </div>
                        <div class="role-users">
                            <div class="user-avatars">
                                <div class="avatar-mini">RT</div>
                                <div class="avatar-mini">KL</div>
                                <div class="avatar-mini">MP</div>
                                <div class="more-count">+5</div>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-outline-primary me-1" onclick="editRole('manager')">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteRole('manager')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="role-card staff">
                        <div class="role-header">
                            <h5 class="role-title">Staff</h5>
                            <span class="role-badge-count">9 users</span>
                        </div>
                        <p class="role-description">Can view information, process check-ins/outs, and create maintenance tickets.</p>
                        <div class="role-permissions">
                            <span class="permission-tag">View Only</span>
                            <span class="permission-tag">Check-in/out</span>
                            <span class="permission-tag">Maintenance Create</span>
                            <span class="permission-tag">+4 more</span>
                        </div>
                        <div class="role-users">
                            <div class="user-avatars">
                                <div class="avatar-mini">CW</div>
                                <div class="avatar-mini">TR</div>
                                <div class="avatar-mini">JB</div>
                                <div class="more-count">+6</div>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-outline-primary me-1" onclick="editRole('staff')">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteRole('staff')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="role-card viewer">
                        <div class="role-header">
                            <h5 class="role-title">Viewer</h5>
                            <span class="role-badge-count">4 users</span>
                        </div>
                        <p class="role-description">Read-only access to most modules. Cannot make any changes to data.</p>
                        <div class="role-permissions">
                            <span class="permission-tag">Read-only</span>
                            <span class="permission-tag">Reports View</span>
                            <span class="permission-tag">Dashboard</span>
                        </div>
                        <div class="role-users">
                            <div class="user-avatars">
                                <div class="avatar-mini">NG</div>
                                <div class="avatar-mini">PS</div>
                                <div class="avatar-mini">AM</div>
                                <div class="more-count">+1</div>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-outline-primary me-1" onclick="editRole('viewer')">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteRole('viewer')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="role-card" style="border-top: 4px solid #9b59b6;">
                        <div class="role-header">
                            <h5 class="role-title">Custom Role</h5>
                            <span class="role-badge-count">1 user</span>
                        </div>
                        <p class="role-description">Custom role with specific permissions for accounting department.</p>
                        <div class="role-permissions">
                            <span class="permission-tag">Financial Reports</span>
                            <span class="permission-tag">Payment View</span>
                            <span class="permission-tag">Export Data</span>
                            <span class="permission-tag">+3 more</span>
                        </div>
                        <div class="role-users">
                            <div class="user-avatars">
                                <div class="avatar-mini">CF</div>
                                <div class="more-count">+0</div>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-outline-primary me-1" onclick="editRole('custom')">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteRole('custom')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Permissions Tab -->
        <div class="tab-pane fade" id="permissions" role="tabpanel">
            <div class="row">
                <div class="col-md-8">
                    <div class="table-container">
                        <div class="table-header">
                            <h5 class="table-title">Permission List</h5>
                            <div>
                                <button class="btn btn-sm btn-outline-primary" onclick="addPermission()">
                                    <i class="bi bi-plus-circle me-1"></i>Add Permission
                                </button>
                            </div>
                        </div>
                        
                        <div class="permission-tree">
                            <!-- User Management Permissions -->
                            <div class="permission-category">
                                <div class="category-header">
                                    <span><i class="bi bi-people me-2"></i>User Management</span>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAllUsersPerm">
                                        <label class="form-check-label" for="selectAllUsersPerm">Select All</label>
                                    </div>
                                </div>
                                <div class="permission-item">
                                    <span class="permission-name"><i class="bi bi-eye"></i> View Users</span>
                                    <span class="badge bg-light text-dark">assigned to: Admin, Manager, Staff</span>
                                </div>
                                <div class="permission-item">
                                    <span class="permission-name"><i class="bi bi-person-plus"></i> Create Users</span>
                                    <span class="badge bg-light text-dark">assigned to: Super Admin, Admin</span>
                                </div>
                                <div class="permission-item">
                                    <span class="permission-name"><i class="bi bi-pencil"></i> Edit Users</span>
                                    <span class="badge bg-light text-dark">assigned to: Super Admin, Admin</span>
                                </div>
                                <div class="permission-item">
                                    <span class="permission-name"><i class="bi bi-trash"></i> Delete Users</span>
                                    <span class="badge bg-light text-dark">assigned to: Super Admin</span>
                                </div>
                            </div>
                            
                            <!-- Role Management Permissions -->
                            <div class="permission-category">
                                <div class="category-header">
                                    <span><i class="bi bi-shield me-2"></i>Role Management</span>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAllRolesPerm">
                                        <label class="form-check-label" for="selectAllRolesPerm">Select All</label>
                                    </div>
                                </div>
                                <div class="permission-item">
                                    <span class="permission-name"><i class="bi bi-eye"></i> View Roles</span>
                                    <span class="badge bg-light text-dark">assigned to: All roles</span>
                                </div>
                                <div class="permission-item">
                                    <span class="permission-name"><i class="bi bi-shield-plus"></i> Create Roles</span>
                                    <span class="badge bg-light text-dark">assigned to: Super Admin</span>
                                </div>
                                <div class="permission-item">
                                    <span class="permission-name"><i class="bi bi-pencil"></i> Edit Roles</span>
                                    <span class="badge bg-light text-dark">assigned to: Super Admin</span>
                                </div>
                                <div class="permission-item">
                                    <span class="permission-name"><i class="bi bi-trash"></i> Delete Roles</span>
                                    <span class="badge bg-light text-dark">assigned to: Super Admin</span>
                                </div>
                            </div>
                            
                            <!-- Property Management Permissions -->
                            <div class="permission-category">
                                <div class="category-header">
                                    <span><i class="bi bi-building me-2"></i>Property Management</span>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAllPropertyPerm">
                                        <label class="form-check-label" for="selectAllPropertyPerm">Select All</label>
                                    </div>
                                </div>
                                <div class="permission-item">
                                    <span class="permission-name"><i class="bi bi-eye"></i> View Properties</span>
                                    <span class="badge bg-light text-dark">assigned to: All roles</span>
                                </div>
                                <div class="permission-item">
                                    <span class="permission-name"><i class="bi bi-plus-circle"></i> Create Properties</span>
                                    <span class="badge bg-light text-dark">assigned to: Admin, Manager</span>
                                </div>
                                <div class="permission-item">
                                    <span class="permission-name"><i class="bi bi-pencil"></i> Edit Properties</span>
                                    <span class="badge bg-light text-dark">assigned to: Admin, Manager</span>
                                </div>
                                <div class="permission-item">
                                    <span class="permission-name"><i class="bi bi-trash"></i> Delete Properties</span>
                                    <span class="badge bg-light text-dark">assigned to: Admin</span>
                                </div>
                            </div>
                            
                            <!-- Room Management Permissions -->
                            <div class="permission-category">
                                <div class="category-header">
                                    <span><i class="bi bi-door-open me-2"></i>Room Management</span>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAllRoomPerm">
                                        <label class="form-check-label" for="selectAllRoomPerm">Select All</label>
                                    </div>
                                </div>
                                <div class="permission-item">
                                    <span class="permission-name"><i class="bi bi-eye"></i> View Rooms</span>
                                    <span class="badge bg-light text-dark">assigned to: All roles</span>
                                </div>
                                <div class="permission-item">
                                    <span class="permission-name"><i class="bi bi-plus-circle"></i> Create Rooms</span>
                                    <span class="badge bg-light text-dark">assigned to: Admin, Manager</span>
                                </div>
                                <div class="permission-item">
                                    <span class="permission-name"><i class="bi bi-pencil"></i> Edit Rooms</span>
                                    <span class="badge bg-light text-dark">assigned to: Admin, Manager</span>
                                </div>
                                <div class="permission-item">
                                    <span class="permission-name"><i class="bi bi-trash"></i> Delete Rooms</span>
                                    <span class="badge bg-light text-dark">assigned to: Admin</span>
                                </div>
                            </div>
                            
                            <!-- Payment Permissions -->
                            <div class="permission-category">
                                <div class="category-header">
                                    <span><i class="bi bi-cash-stack me-2"></i>Payment Management</span>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAllPaymentPerm">
                                        <label class="form-check-label" for="selectAllPaymentPerm">Select All</label>
                                    </div>
                                </div>
                                <div class="permission-item">
                                    <span class="permission-name"><i class="bi bi-eye"></i> View Payments</span>
                                    <span class="badge bg-light text-dark">assigned to: All roles</span>
                                </div>
                                <div class="permission-item">
                                    <span class="permission-name"><i class="bi bi-plus-circle"></i> Record Payments</span>
                                    <span class="badge bg-light text-dark">assigned to: Admin, Manager, Staff</span>
                                </div>
                                <div class="permission-item">
                                    <span class="permission-name"><i class="bi bi-arrow-repeat"></i> Process Refunds</span>
                                    <span class="badge bg-light text-dark">assigned to: Admin, Manager</span>
                                </div>
                                <div class="permission-item">
                                    <span class="permission-name"><i class="bi bi-file-pdf"></i> Generate Receipts</span>
                                    <span class="badge bg-light text-dark">assigned to: All roles</span>
                                </div>
                            </div>
                            
                            <!-- Report Permissions -->
                            <div class="permission-category">
                                <div class="category-header">
                                    <span><i class="bi bi-graph-up me-2"></i>Reports</span>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAllReportPerm">
                                        <label class="form-check-label" for="selectAllReportPerm">Select All</label>
                                    </div>
                                </div>
                                <div class="permission-item">
                                    <span class="permission-name"><i class="bi bi-eye"></i> View Reports</span>
                                    <span class="badge bg-light text-dark">assigned to: All roles</span>
                                </div>
                                <div class="permission-item">
                                    <span class="permission-name"><i class="bi bi-download"></i> Export Reports</span>
                                    <span class="badge bg-light text-dark">assigned to: Admin, Manager</span>
                                </div>
                                <div class="permission-item">
                                    <span class="permission-name"><i class="bi bi-printer"></i> Print Reports</span>
                                    <span class="badge bg-light text-dark">assigned to: All roles</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <!-- Permission Summary Card -->
                    <div class="profile-card mb-3">
                        <h5 class="card-title">
                            <i class="bi bi-info-circle"></i>Permission Summary
                        </h5>
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Total Permissions</div>
                                <div class="info-value">42</div>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="bi bi-shield"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Assigned to Roles</div>
                                <div class="info-value">38</div>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Unassigned</div>
                                <div class="info-value">4</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Actions Card -->
                    <div class="profile-card">
                        <h5 class="card-title">
                            <i class="bi bi-lightning"></i>Quick Actions
                        </h5>
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="syncPermissions()">
                                <i class="bi bi-arrow-repeat me-2"></i>Sync Permissions
                            </button>
                            <button class="btn btn-outline-success btn-sm" onclick="auditPermissions()">
                                <i class="bi bi-shield-check me-2"></i>Audit Permissions
                            </button>
                            <button class="btn btn-outline-info btn-sm" onclick="viewPermissionMatrix()">
                                <i class="bi bi-grid-3x3 me-2"></i>Permission Matrix
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Activity Log Tab -->
        <div class="tab-pane fade" id="activity" role="tabpanel">
            <div class="table-container">
                <div class="table-header">
                    <h5 class="table-title">User Activity Log</h5>
                    <div class="d-flex gap-2">
                        <input type="text" class="form-control form-control-sm" placeholder="Filter by user..." style="width: 200px;">
                        <input type="text" class="form-control form-control-sm" placeholder="Filter by action..." style="width: 200px;">
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Timestamp</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>IP Address</th>
                                <th>Device</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>2026-03-15 09:30:45</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="user-avatar" style="width: 30px; height: 30px; font-size: 0.8rem;">AD</div>
                                        <span>Admin User</span>
                                    </div>
                                </td>
                                <td>Login successful</td>
                                <td>192.168.1.100</td>
                                <td>Chrome / Windows</td>
                                <td><span class="badge bg-success">Success</span></td>
                            </tr>
                            <tr>
                                <td>2026-03-15 08:15:22</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="user-avatar" style="width: 30px; height: 30px; font-size: 0.8rem;">JS</div>
                                        <span>John Smith</span>
                                    </div>
                                </td>
                                <td>Updated room rates</td>
                                <td>192.168.1.105</td>
                                <td>Safari / iPhone</td>
                                <td><span class="badge bg-success">Success</span></td>
                            </tr>
                            <tr>
                                <td>2026-03-14 16:45:10</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="user-avatar" style="width: 30px; height: 30px; font-size: 0.8rem;">MJ</div>
                                        <span>Mary Johnson</span>
                                    </div>
                                </td>
                                <td>Processed payment #PAY-2024-001</td>
                                <td>192.168.1.110</td>
                                <td>Chrome / Mac</td>
                                <td><span class="badge bg-success">Success</span></td>
                            </tr>
                            <tr>
                                <td>2026-03-14 14:20:33</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="user-avatar" style="width: 30px; height: 30px; font-size: 0.8rem;">RB</div>
                                        <span>Robert Brown</span>
                                    </div>
                                </td>
                                <td>Failed login attempt</td>
                                <td>203.177.92.150</td>
                                <td>Firefox / Windows</td>
                                <td><span class="badge bg-danger">Failed</span></td>
                            </tr>
                            <tr>
                                <td>2026-03-14 11:05:17</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="user-avatar" style="width: 30px; height: 30px; font-size: 0.8rem;">LD</div>
                                        <span>Lisa Davis</span>
                                    </div>
                                </td>
                                <td>Created new reservation #RES-2024-006</td>
                                <td>192.168.1.115</td>
                                <td>Edge / Windows</td>
                                <td><span class="badge bg-success">Success</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="small text-muted">
                        Showing 5 of 156 activities
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
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addUserForm">
                    <!-- Personal Information -->
                    <div class="users-form-section">
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
                        </div>
                    </div>
                    
                    <!-- Account Information -->
                    <div class="users-form-section">
                        <h6><i class="bi bi-shield-lock"></i> Account Information</h6>
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
                                <label class="form-label">Role</label>
                                <select class="form-select" name="role" required>
                                    <option value="">Select role...</option>
                                    <option value="super-admin">Super Admin</option>
                                    <option value="admin">Admin</option>
                                    <option value="manager">Manager</option>
                                    <option value="staff">Staff</option>
                                    <option value="viewer">Viewer</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Department</label>
                                <select class="form-select" name="department">
                                    <option value="">Select...</option>
                                    <option value="it">IT</option>
                                    <option value="hr">HR</option>
                                    <option value="finance">Finance</option>
                                    <option value="operations">Operations</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="pending">Pending</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Permissions -->
                    <div class="users-form-section">
                        <h6><i class="bi bi-key"></i> Additional Permissions</h6>
                        <div class="permission-grid">
                            <div class="permission-checkbox">
                                <input class="form-check-input" type="checkbox" id="perm1">
                                <label class="form-check-label" for="perm1">Export Reports</label>
                            </div>
                            <div class="permission-checkbox">
                                <input class="form-check-input" type="checkbox" id="perm2">
                                <label class="form-check-label" for="perm2">Manage Payments</label>
                            </div>
                            <div class="permission-checkbox">
                                <input class="form-check-input" type="checkbox" id="perm3">
                                <label class="form-check-label" for="perm3">Approve Requests</label>
                            </div>
                            <div class="permission-checkbox">
                                <input class="form-check-input" type="checkbox" id="perm4">
                                <label class="form-check-label" for="perm4">API Access</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveUser()">Create User</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Role Modal -->
<div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addRoleForm">
                    <!-- Basic Information -->
                    <div class="users-form-section">
                        <h6><i class="bi bi-shield"></i> Role Information</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Role Name</label>
                                <input type="text" class="form-control" name="role_name" placeholder="e.g., Content Manager" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Role Color</label>
                                <select class="form-select" name="role_color">
                                    <option value="primary">Blue</option>
                                    <option value="success">Green</option>
                                    <option value="warning">Orange</option>
                                    <option value="danger">Red</option>
                                    <option value="info">Light Blue</option>
                                    <option value="purple">Purple</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="2" placeholder="Describe the role and its responsibilities..."></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Permissions -->
                    <div class="users-form-section">
                        <h6><i class="bi bi-key"></i> Assign Permissions</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-2">User Management</h6>
                                <div class="permission-checkbox mb-2">
                                    <input class="form-check-input" type="checkbox" id="rolePerm1">
                                    <label class="form-check-label" for="rolePerm1">View Users</label>
                                </div>
                                <div class="permission-checkbox mb-2">
                                    <input class="form-check-input" type="checkbox" id="rolePerm2">
                                    <label class="form-check-label" for="rolePerm2">Create Users</label>
                                </div>
                                <div class="permission-checkbox mb-2">
                                    <input class="form-check-input" type="checkbox" id="rolePerm3">
                                    <label class="form-check-label" for="rolePerm3">Edit Users</label>
                                </div>
                                <div class="permission-checkbox mb-2">
                                    <input class="form-check-input" type="checkbox" id="rolePerm4">
                                    <label class="form-check-label" for="rolePerm4">Delete Users</label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h6 class="mb-2">Property Management</h6>
                                <div class="permission-checkbox mb-2">
                                    <input class="form-check-input" type="checkbox" id="rolePerm5">
                                    <label class="form-check-label" for="rolePerm5">View Properties</label>
                                </div>
                                <div class="permission-checkbox mb-2">
                                    <input class="form-check-input" type="checkbox" id="rolePerm6">
                                    <label class="form-check-label" for="rolePerm6">Create Properties</label>
                                </div>
                                <div class="permission-checkbox mb-2">
                                    <input class="form-check-input" type="checkbox" id="rolePerm7">
                                    <label class="form-check-label" for="rolePerm7">Edit Properties</label>
                                </div>
                                <div class="permission-checkbox mb-2">
                                    <input class="form-check-input" type="checkbox" id="rolePerm8">
                                    <label class="form-check-label" for="rolePerm8">Delete Properties</label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h6 class="mb-2">Payment Management</h6>
                                <div class="permission-checkbox mb-2">
                                    <input class="form-check-input" type="checkbox" id="rolePerm9">
                                    <label class="form-check-label" for="rolePerm9">View Payments</label>
                                </div>
                                <div class="permission-checkbox mb-2">
                                    <input class="form-check-input" type="checkbox" id="rolePerm10">
                                    <label class="form-check-label" for="rolePerm10">Record Payments</label>
                                </div>
                                <div class="permission-checkbox mb-2">
                                    <input class="form-check-input" type="checkbox" id="rolePerm11">
                                    <label class="form-check-label" for="rolePerm11">Process Refunds</label>
                                </div>
                                <div class="permission-checkbox mb-2">
                                    <input class="form-check-input" type="checkbox" id="rolePerm12">
                                    <label class="form-check-label" for="rolePerm12">Generate Receipts</label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h6 class="mb-2">Reports</h6>
                                <div class="permission-checkbox mb-2">
                                    <input class="form-check-input" type="checkbox" id="rolePerm13">
                                    <label class="form-check-label" for="rolePerm13">View Reports</label>
                                </div>
                                <div class="permission-checkbox mb-2">
                                    <input class="form-check-input" type="checkbox" id="rolePerm14">
                                    <label class="form-check-label" for="rolePerm14">Export Reports</label>
                                </div>
                                <div class="permission-checkbox mb-2">
                                    <input class="form-check-input" type="checkbox" id="rolePerm15">
                                    <label class="form-check-label" for="rolePerm15">Print Reports</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveRole()">Create Role</button>
            </div>
        </div>
    </div>
</div>

<!-- User Permissions Modal -->
<div class="modal fade" id="userPermissionsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">User Permissions - John Smith</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <span class="badge bg-primary">Role: Admin</span>
                </div>
                
                <div class="permission-tree">
                    <div class="permission-category">
                        <div class="category-header">
                            <span>User Management</span>
                        </div>
                        <div class="permission-item">
                            <span class="permission-name">View Users</span>
                            <span class="badge bg-success">Granted</span>
                        </div>
                        <div class="permission-item">
                            <span class="permission-name">Create Users</span>
                            <span class="badge bg-success">Granted</span>
                        </div>
                        <div class="permission-item">
                            <span class="permission-name">Edit Users</span>
                            <span class="badge bg-success">Granted</span>
                        </div>
                        <div class="permission-item">
                            <span class="permission-name">Delete Users</span>
                            <span class="badge bg-danger">Denied</span>
                        </div>
                    </div>
                    
                    <div class="permission-category">
                        <div class="category-header">
                            <span>Property Management</span>
                        </div>
                        <div class="permission-item">
                            <span class="permission-name">View Properties</span>
                            <span class="badge bg-success">Granted</span>
                        </div>
                        <div class="permission-item">
                            <span class="permission-name">Create Properties</span>
                            <span class="badge bg-success">Granted</span>
                        </div>
                        <div class="permission-item">
                            <span class="permission-name">Edit Properties</span>
                            <span class="badge bg-success">Granted</span>
                        </div>
                        <div class="permission-item">
                            <span class="permission-name">Delete Properties</span>
                            <span class="badge bg-danger">Denied</span>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3">
                    <label class="form-label">Add Custom Permission</label>
                    <select class="form-select">
                        <option value="">Select permission to add...</option>
                        <option value="export">Export Reports</option>
                        <option value="api">API Access</option>
                        <option value="audit">Audit Logs</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveUserPermissions()">Save Changes</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable for users
        const usersTable = $('#usersTable').DataTable({
            pageLength: 10,
            responsive: true,
            searching: true,
            ordering: true,
            info: true,
            lengthChange: false,
            columnDefs: [
                { orderable: false, targets: [0, 7] },
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

        // Select all users checkbox
        $('#selectAllUsers').on('change', function() {
            $('.row-checkbox').prop('checked', $(this).prop('checked'));
        });

        // Search functionality
        $('#searchUser').on('keyup', function() {
            usersTable.search($(this).val()).draw();
        });

        // Filter by role
        $('#filterRole').on('change', function() {
            const role = $(this).val();
            if (role) {
                usersTable.column(2).search(role, true, false).draw();
            } else {
                usersTable.column(2).search('').draw();
            }
        });

        // Filter by status
        $('#filterUserStatus').on('change', function() {
            const status = $(this).val();
            if (status) {
                usersTable.column(4).search('^' + status + '$', true, false).draw();
            } else {
                usersTable.column(4).search('').draw();
            }
        });

        // Category select all
        $('#selectAllUsersPerm').on('change', function() {
            $(this).closest('.permission-category').find('.permission-item input[type="checkbox"]').prop('checked', $(this).prop('checked'));
        });

        $('#selectAllRolesPerm').on('change', function() {
            $(this).closest('.permission-category').find('.permission-item input[type="checkbox"]').prop('checked', $(this).prop('checked'));
        });

        $('#selectAllPropertyPerm').on('change', function() {
            $(this).closest('.permission-category').find('.permission-item input[type="checkbox"]').prop('checked', $(this).prop('checked'));
        });
    });

    // Export data function
    function exportData(type) {
        showToast('info', `Exporting ${type}...`);
        setTimeout(() => {
            showToast('success', 'Export completed successfully');
        }, 2000);
    }

    // View user function
    function viewUser(id) {
        showToast('info', 'Loading user details...');
    }

    // Edit user function
    function editUser(id) {
        $('#addUserModal').modal('show');
        showToast('info', 'Loading user data...');
    }

    // User permissions function
    function userPermissions(id) {
        $('#userPermissionsModal').modal('show');
    }

    // Impersonate user function
    function impersonateUser(id) {
        Swal.fire({
            title: 'Impersonate User',
            text: 'You will be logged in as this user. Continue?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#667eea',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, impersonate'
        }).then((result) => {
            if (result.isConfirmed) {
                showToast('success', 'Now impersonating user');
            }
        });
    }

    // Reset password function
    function resetPassword(id) {
        Swal.fire({
            title: 'Reset Password',
            text: 'Send password reset email to user?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Send',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                showToast('success', 'Password reset email sent');
            }
        });
    }

    // Delete user function
    function deleteUser(id) {
        Swal.fire({
            title: 'Delete User?',
            text: "This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete'
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();
                setTimeout(() => {
                    hideLoading();
                    Swal.fire('Deleted!', 'User has been deleted.', 'success');
                }, 1500);
            }
        });
    }

    // Edit role function
    function editRole(role) {
        $('#addRoleModal').modal('show');
        showToast('info', 'Loading role data...');
    }

    // Delete role function
    function deleteRole(role) {
        Swal.fire({
            title: 'Delete Role?',
            text: "Users with this role will be affected.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete'
        }).then((result) => {
            if (result.isConfirmed) {
                showToast('success', 'Role deleted');
            }
        });
    }

    // Add permission function
    function addPermission() {
        showToast('info', 'Add permission modal would open here');
    }

    // Sync permissions function
    function syncPermissions() {
        showLoading();
        setTimeout(() => {
            hideLoading();
            showToast('success', 'Permissions synced successfully');
        }, 1500);
    }

    // Audit permissions function
    function auditPermissions() {
        showToast('info', 'Running permission audit...');
    }

    // View permission matrix
    function viewPermissionMatrix() {
        showToast('info', 'Loading permission matrix...');
    }

    // Save user function
    function saveUser() {
        const form = document.getElementById('addUserForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        showLoading();
        setTimeout(() => {
            hideLoading();
            $('#addUserModal').modal('hide');
            form.reset();
            showToast('success', 'User created successfully');
        }, 1500);
    }

    // Save role function
    function saveRole() {
        const form = document.getElementById('addRoleForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        showLoading();
        setTimeout(() => {
            hideLoading();
            $('#addRoleModal').modal('hide');
            form.reset();
            showToast('success', 'Role created successfully');
        }, 1500);
    }

    // Save user permissions function
    function saveUserPermissions() {
        showToast('success', 'Permissions updated');
        $('#userPermissionsModal').modal('hide');
    }

    // Bulk action function
    function bulkAction(action) {
        const selected = $('.row-checkbox:checked').length;
        if (selected === 0) {
            showToast('warning', 'Please select at least one user');
            return;
        }
        
        let title, message;
        switch(action) {
            case 'activate':
                title = 'Activate Users';
                message = `Activate ${selected} selected user(s)?`;
                break;
            case 'deactivate':
                title = 'Deactivate Users';
                message = `Deactivate ${selected} selected user(s)?`;
                break;
            case 'assign-role':
                title = 'Assign Role';
                message = `Assign role to ${selected} selected user(s)?`;
                break;
            case 'delete':
                title = 'Delete Users';
                message = `Delete ${selected} selected user(s)?`;
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
                if (action === 'assign-role') {
                    // Show role selection
                    Swal.fire({
                        title: 'Select Role',
                        input: 'select',
                        inputOptions: {
                            'admin': 'Admin',
                            'manager': 'Manager',
                            'staff': 'Staff',
                            'viewer': 'Viewer'
                        },
                        showCancelButton: true,
                        confirmButtonText: 'Assign'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            showToast('success', 'Role assigned successfully');
                        }
                    });
                } else {
                    showLoading();
                    setTimeout(() => {
                        hideLoading();
                        showToast('success', `${title} completed successfully`);
                    }, 1500);
                }
            }
        });
    }
</script>
@endsection