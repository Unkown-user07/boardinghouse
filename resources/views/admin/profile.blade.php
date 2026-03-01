@extends('layouts.admin')

@section('title', 'My Profile - StayEase Admin')

@section('page_header', 'My Profile')

@section('page_description', 'Manage your personal information and account settings')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Profile</li>
@endsection

@section('header_actions')
    <button class="btn btn-outline-primary" onclick="viewActivityLog()">
        <i class="bi bi-clock-history me-2"></i>Activity Log
    </button>
    <button class="btn btn-primary ms-2" onclick="saveProfile()">
        <i class="bi bi-check-circle me-2"></i>Save Changes
    </button>
@endsection

@section('styles')
<style>
    /* Profile Header */
    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 2rem;
        color: white;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
    }
    
    .profile-header::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }
    
    .profile-header::after {
        content: '';
        position: absolute;
        bottom: -50px;
        left: -50px;
        width: 150px;
        height: 150px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }
    
    .profile-avatar-large {
        width: 120px;
        height: 120px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        font-weight: 600;
        color: #667eea;
        border: 4px solid rgba(255, 255, 255, 0.3);
        cursor: pointer;
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
    }
    
    .profile-avatar-large:hover {
        transform: scale(1.05);
        border-color: white;
    }
    
    .profile-avatar-large:hover .avatar-overlay {
        opacity: 1;
    }
    
    .avatar-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        opacity: 0;
        transition: opacity 0.3s;
    }
    
    .profile-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }
    
    .profile-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        padding: 0.35rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        margin-right: 0.5rem;
    }
    
    .profile-stats {
        display: flex;
        gap: 2rem;
        margin-top: 1rem;
    }
    
    .profile-stat {
        text-align: center;
    }
    
    .profile-stat-value {
        font-size: 1.5rem;
        font-weight: 700;
    }
    
    .profile-stat-label {
        font-size: 0.8rem;
        opacity: 0.8;
    }
    
    /* Profile Cards */
    .profile-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.03);
        border: 1px solid rgba(0,0,0,0.03);
        margin-bottom: 1.5rem;
        transition: all 0.3s;
    }
    
    .profile-card:hover {
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.1);
        border-color: rgba(102, 126, 234, 0.15);
    }
    
    .card-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .card-title i {
        color: #667eea;
        font-size: 1.2rem;
    }
    
    .card-title .badge {
        margin-left: auto;
        font-size: 0.7rem;
        padding: 0.25rem 0.75rem;
    }
    
    /* Form Styles */
    .form-label {
        font-weight: 500;
        color: #4a5568;
        font-size: 0.85rem;
        margin-bottom: 0.3rem;
    }
    
    .form-control, .form-select {
        border: 1.5px solid #edf2f7;
        border-radius: 10px;
        padding: 0.6rem 1rem;
        font-size: 0.95rem;
        transition: all 0.3s;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .form-control[readonly] {
        background-color: #f8fafc;
        cursor: not-allowed;
    }
    
    .input-group-text {
        background: #f8fafc;
        border: 1.5px solid #edf2f7;
        border-radius: 10px;
        color: #718096;
    }
    
    /* Info Items */
    .info-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.75rem;
        background: #f8fafc;
        border-radius: 10px;
        margin-bottom: 0.5rem;
    }
    
    .info-icon {
        width: 40px;
        height: 40px;
        background: white;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #667eea;
        font-size: 1.2rem;
    }
    
    .info-content {
        flex: 1;
    }
    
    .info-label {
        font-size: 0.7rem;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    .info-value {
        font-weight: 600;
        color: #2d3748;
    }
    
    /* Activity List */
    .activity-list {
        max-height: 300px;
        overflow-y: auto;
    }
    
    .activity-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.75rem;
        border-bottom: 1px solid #edf2f7;
        transition: all 0.3s;
    }
    
    .activity-item:hover {
        background: #f8fafc;
        border-radius: 10px;
    }
    
    .activity-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }
    
    .activity-icon.login {
        background: #e3f2fd;
        color: #3498db;
    }
    
    .activity-icon.update {
        background: #fff3e0;
        color: #f39c12;
    }
    
    .activity-icon.security {
        background: #e6f7e6;
        color: #27ae60;
    }
    
    .activity-details {
        flex: 1;
    }
    
    .activity-title {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.2rem;
        font-size: 0.9rem;
    }
    
    .activity-time {
        font-size: 0.7rem;
        color: #718096;
    }
    
    .activity-device {
        font-size: 0.7rem;
        color: #718096;
        background: #edf2f7;
        padding: 0.2rem 0.5rem;
        border-radius: 12px;
    }
    
    /* Security Items */
    .security-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem;
        border-bottom: 1px solid #edf2f7;
    }
    
    .security-item:last-child {
        border-bottom: none;
    }
    
    .security-info h6 {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.2rem;
        font-size: 0.95rem;
    }
    
    .security-info p {
        font-size: 0.8rem;
        color: #718096;
        margin-bottom: 0;
    }
    
    .security-badge {
        font-size: 0.7rem;
        padding: 0.2rem 0.6rem;
        border-radius: 20px;
        background: #e6f7e6;
        color: #27ae60;
    }
    
    .security-badge.inactive {
        background: #fee9e9;
        color: #e74c3c;
    }
    
    /* Toggle Switch */
    .form-switch .form-check-input {
        width: 3em;
        height: 1.5em;
        cursor: pointer;
    }
    
    .form-switch .form-check-input:checked {
        background-color: #667eea;
        border-color: #667eea;
    }
    
    /* Device Cards */
    .device-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 12px;
        margin-bottom: 0.5rem;
    }
    
    .device-icon {
        width: 48px;
        height: 48px;
        background: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #667eea;
    }
    
    .device-info {
        flex: 1;
    }
    
    .device-name {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.2rem;
    }
    
    .device-details {
        font-size: 0.75rem;
        color: #718096;
    }
    
    .device-badge {
        font-size: 0.7rem;
        padding: 0.2rem 0.6rem;
        border-radius: 20px;
        background: #e3f2fd;
        color: #3498db;
    }
    
    .device-badge.current {
        background: #e6f7e6;
        color: #27ae60;
    }
    
    /* Notification Settings */
    .notification-setting {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem;
        border-bottom: 1px solid #edf2f7;
    }
    
    .notification-setting:last-child {
        border-bottom: none;
    }
    
    .notification-info h6 {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.2rem;
        font-size: 0.95rem;
    }
    
    .notification-info p {
        font-size: 0.8rem;
        color: #718096;
        margin-bottom: 0;
    }
    
    /* API Keys */
    .api-key-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.75rem;
        background: #f8fafc;
        border-radius: 10px;
        margin-bottom: 0.5rem;
    }
    
    .api-key {
        font-family: monospace;
        background: white;
        padding: 0.3rem 0.8rem;
        border-radius: 6px;
        font-size: 0.85rem;
        border: 1px solid #edf2f7;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .profile-header {
            padding: 1.5rem;
        }
        
        .profile-avatar-large {
            width: 100px;
            height: 100px;
            font-size: 2.5rem;
        }
        
        .profile-title {
            font-size: 1.5rem;
        }
        
        .profile-stats {
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .profile-stat {
            flex: 1;
            min-width: 80px;
        }
        
        .profile-stat-value {
            font-size: 1.2rem;
        }
        
        .profile-card {
            padding: 1rem;
        }
        
        .info-item {
            flex-direction: column;
            text-align: center;
        }
        
        .security-item {
            flex-direction: column;
            gap: 0.5rem;
            text-align: center;
        }
        
        .device-card {
            flex-direction: column;
            text-align: center;
        }
        
        .api-key-item {
            flex-direction: column;
        }
    }
    
    @media (max-width: 576px) {
        .profile-header .row {
            text-align: center;
        }
        
        .profile-avatar-large {
            margin: 0 auto 1rem;
        }
        
        .profile-stats {
            justify-content: center;
        }
        
        .card-title {
            font-size: 1rem;
        }
    }
    
    /* Modal Styles */
    .avatar-upload-area {
        border: 2px dashed #edf2f7;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .avatar-upload-area:hover {
        border-color: #667eea;
        background: #f0f4ff;
    }
    
    .avatar-upload-area i {
        font-size: 3rem;
        color: #667eea;
        margin-bottom: 1rem;
    }
    
    .avatar-preview {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        margin: 1rem auto;
        overflow: hidden;
        border: 4px solid #667eea;
    }
    
    .avatar-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    /* Password Strength */
    .password-strength {
        margin-top: 0.5rem;
    }
    
    .strength-bars {
        display: flex;
        gap: 5px;
        margin-bottom: 0.3rem;
    }
    
    .strength-bar {
        height: 4px;
        flex: 1;
        background: #edf2f7;
        border-radius: 4px;
        transition: all 0.3s;
    }
    
    .strength-bar.weak {
        background: #e74c3c;
    }
    
    .strength-bar.fair {
        background: #f39c12;
    }
    
    .strength-bar.good {
        background: #3498db;
    }
    
    .strength-bar.strong {
        background: #27ae60;
    }
    
    .strength-text {
        font-size: 0.75rem;
        color: #718096;
    }
</style>
@endsection

@section('content')
<div class="fade-in">
    <!-- Profile Header -->
    <div class="profile-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-4">
                    <div class="profile-avatar-large" onclick="changeAvatar()">
                        <span>AD</span>
                        <div class="avatar-overlay">
                            <i class="bi bi-camera"></i>
                        </div>
                    </div>
                    <div>
                        <h1 class="profile-title">Admin User</h1>
                        <div class="mb-2">
                            <span class="profile-badge"><i class="bi bi-shield-check me-1"></i>Super Admin</span>
                            <span class="profile-badge"><i class="bi bi-building me-1"></i>System Administrator</span>
                        </div>
                        <div class="profile-stats">
                            <div class="profile-stat">
                                <div class="profile-stat-value">1,284</div>
                                <div class="profile-stat-label">Actions</div>
                            </div>
                            <div class="profile-stat">
                                <div class="profile-stat-value">156</div>
                                <div class="profile-stat-label">Days Active</div>
                            </div>
                            <div class="profile-stat">
                                <div class="profile-stat-value">12</div>
                                <div class="profile-stat-label">Properties</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="last-login">
                    <i class="bi bi-clock me-1"></i>
                    Last login: March 15, 2026 09:30 AM
                </div>
                <div class="last-login mt-1">
                    <i class="bi bi-globe me-1"></i>
                    IP Address: 192.168.1.100 (Manila, PH)
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Personal Information -->
        <div class="col-lg-4">
            <!-- Personal Information Card -->
            <div class="profile-card">
                <h5 class="card-title">
                    <i class="bi bi-person"></i>Personal Information
                    <span class="badge bg-primary">Primary</span>
                </h5>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="bi bi-person-circle"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Full Name</div>
                        <div class="info-value">Admin User</div>
                    </div>
                    <button class="btn btn-sm btn-outline-secondary" onclick="editField('name')">
                        <i class="bi bi-pencil"></i>
                    </button>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="bi bi-envelope"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Email Address</div>
                        <div class="info-value">admin@stayease.com</div>
                    </div>
                    <button class="btn btn-sm btn-outline-secondary" onclick="editField('email')">
                        <i class="bi bi-pencil"></i>
                    </button>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="bi bi-phone"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Phone Number</div>
                        <div class="info-value">+63 912 345 6789</div>
                    </div>
                    <button class="btn btn-sm btn-outline-secondary" onclick="editField('phone')">
                        <i class="bi bi-pencil"></i>
                    </button>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="bi bi-calendar"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Date of Birth</div>
                        <div class="info-value">January 15, 1990</div>
                    </div>
                    <button class="btn btn-sm btn-outline-secondary" onclick="editField('dob')">
                        <i class="bi bi-pencil"></i>
                    </button>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="bi bi-gender-ambiguous"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Gender</div>
                        <div class="info-value">Male</div>
                    </div>
                    <button class="btn btn-sm btn-outline-secondary" onclick="editField('gender')">
                        <i class="bi bi-pencil"></i>
                    </button>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="bi bi-building"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Department</div>
                        <div class="info-value">IT Administration</div>
                    </div>
                </div>
            </div>
            
            <!-- Address Information Card -->
            <div class="profile-card">
                <h5 class="card-title">
                    <i class="bi bi-geo-alt"></i>Address Information
                </h5>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="bi bi-house"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Street Address</div>
                        <div class="info-value">123 Mabini Street</div>
                    </div>
                    <button class="btn btn-sm btn-outline-secondary" onclick="editField('street')">
                        <i class="bi bi-pencil"></i>
                    </button>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="bi bi-building"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">City</div>
                        <div class="info-value">Makati City</div>
                    </div>
                    <button class="btn btn-sm btn-outline-secondary" onclick="editField('city')">
                        <i class="bi bi-pencil"></i>
                    </button>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="bi bi-globe"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Province</div>
                        <div class="info-value">Metro Manila</div>
                    </div>
                    <button class="btn btn-sm btn-outline-secondary" onclick="editField('province')">
                        <i class="bi bi-pencil"></i>
                    </button>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="bi bi-mailbox"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Zip Code</div>
                        <div class="info-value">1200</div>
                    </div>
                    <button class="btn btn-sm btn-outline-secondary" onclick="editField('zip')">
                        <i class="bi bi-pencil"></i>
                    </button>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="bi bi-flag"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Country</div>
                        <div class="info-value">Philippines</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Middle Column - Account Settings -->
        <div class="col-lg-4">
            <!-- Account Information Card -->
            <div class="profile-card">
                <h5 class="card-title">
                    <i class="bi bi-shield-lock"></i>Account Information
                </h5>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Username</div>
                        <div class="info-value">admin_user</div>
                    </div>
                    <button class="btn btn-sm btn-outline-secondary" onclick="editField('username')">
                        <i class="bi bi-pencil"></i>
                    </button>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="bi bi-envelope"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Email Verified</div>
                        <div class="info-value">
                            <span class="badge bg-success">Verified</span>
                        </div>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="bi bi-phone"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">2FA Status</div>
                        <div class="info-value">
                            <span class="badge bg-success">Enabled</span>
                        </div>
                    </div>
                    <button class="btn btn-sm btn-outline-secondary" onclick="manage2FA()">
                        <i class="bi bi-gear"></i>
                    </button>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Member Since</div>
                        <div class="info-value">January 15, 2025</div>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Last Password Change</div>
                        <div class="info-value">February 1, 2026</div>
                    </div>
                    <button class="btn btn-sm btn-outline-primary" onclick="changePassword()">
                        Change
                    </button>
                </div>
            </div>
            
            <!-- Security Settings Card -->
            <div class="profile-card">
                <h5 class="card-title">
                    <i class="bi bi-shield-check"></i>Security Settings
                </h5>
                
                <div class="security-item">
                    <div class="security-info">
                        <h6>Two-Factor Authentication</h6>
                        <p>Add an extra layer of security to your account</p>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="twoFactor" checked>
                    </div>
                </div>
                
                <div class="security-item">
                    <div class="security-info">
                        <h6>Login Notifications</h6>
                        <p>Get notified on new device logins</p>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="loginNotifications" checked>
                    </div>
                </div>
                
                <div class="security-item">
                    <div class="security-info">
                        <h6>Session Timeout</h6>
                        <p>Auto logout after 30 minutes of inactivity</p>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="sessionTimeout" checked>
                    </div>
                </div>
                
                <div class="security-item">
                    <div class="security-info">
                        <h6>IP Whitelisting</h6>
                        <p>Restrict access to specific IP addresses</p>
                    </div>
                    <button class="btn btn-sm btn-outline-primary">Configure</button>
                </div>
            </div>
            
            <!-- Active Sessions Card -->
            <div class="profile-card">
                <h5 class="card-title">
                    <i class="bi bi-laptop"></i>Active Sessions
                    <span class="badge bg-primary">2</span>
                </h5>
                
                <div class="device-card">
                    <div class="device-icon">
                        <i class="bi bi-laptop"></i>
                    </div>
                    <div class="device-info">
                        <div class="device-name">Windows PC - Chrome</div>
                        <div class="device-details">192.168.1.100 • Manila, PH</div>
                    </div>
                    <span class="device-badge current">Current</span>
                </div>
                
                <div class="device-card">
                    <div class="device-icon">
                        <i class="bi bi-phone"></i>
                    </div>
                    <div class="device-info">
                        <div class="device-name">iPhone 14 - Safari</div>
                        <div class="device-details">192.168.1.105 • Manila, PH</div>
                    </div>
                    <button class="btn btn-sm btn-outline-danger" onclick="terminateSession()">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
                
                <div class="text-center mt-3">
                    <button class="btn btn-link text-danger" onclick="terminateAllSessions()">
                        <i class="bi bi-x-circle me-1"></i>Terminate All Other Sessions
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Right Column - Activity & Preferences -->
        <div class="col-lg-4">
            <!-- Recent Activity Card -->
            <div class="profile-card">
                <h5 class="card-title">
                    <i class="bi bi-clock-history"></i>Recent Activity
                    <a href="#" class="small ms-auto">View All</a>
                </h5>
                
                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-icon login">
                            <i class="bi bi-box-arrow-in-right"></i>
                        </div>
                        <div class="activity-details">
                            <div class="activity-title">Login successful</div>
                            <div class="activity-time">2 minutes ago</div>
                        </div>
                        <span class="activity-device">Chrome/Win</span>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon update">
                            <i class="bi bi-pencil"></i>
                        </div>
                        <div class="activity-details">
                            <div class="activity-title">Updated room rates</div>
                            <div class="activity-time">15 minutes ago</div>
                        </div>
                        <span class="activity-device">Room 204</span>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon security">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div class="activity-details">
                            <div class="activity-title">Security settings changed</div>
                            <div class="activity-time">1 hour ago</div>
                        </div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon login">
                            <i class="bi bi-box-arrow-in-right"></i>
                        </div>
                        <div class="activity-details">
                            <div class="activity-title">Login from new device</div>
                            <div class="activity-time">3 hours ago</div>
                        </div>
                        <span class="activity-device">iPhone</span>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon update">
                            <i class="bi bi-person-plus"></i>
                        </div>
                        <div class="activity-details">
                            <div class="activity-title">Added new occupant</div>
                            <div class="activity-time">5 hours ago</div>
                        </div>
                        <span class="activity-device">Maria Santos</span>
                    </div>
                </div>
            </div>
            
            <!-- Notification Preferences Card -->
            <div class="profile-card">
                <h5 class="card-title">
                    <i class="bi bi-bell"></i>Notification Preferences
                </h5>
                
                <div class="notification-setting">
                    <div class="notification-info">
                        <h6>Email Notifications</h6>
                        <p>Receive updates via email</p>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="emailNotif" checked>
                    </div>
                </div>
                
                <div class="notification-setting">
                    <div class="notification-info">
                        <h6>SMS Notifications</h6>
                        <p>Receive alerts via SMS</p>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="smsNotif">
                    </div>
                </div>
                
                <div class="notification-setting">
                    <div class="notification-info">
                        <h6>Payment Alerts</h6>
                        <p>Get notified for new payments</p>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="paymentNotif" checked>
                    </div>
                </div>
                
                <div class="notification-setting">
                    <div class="notification-info">
                        <h6>Maintenance Requests</h6>
                        <p>New maintenance ticket alerts</p>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="maintenanceNotif" checked>
                    </div>
                </div>
                
                <div class="notification-setting">
                    <div class="notification-info">
                        <h6>System Updates</h6>
                        <p>Platform updates and announcements</p>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="systemNotif" checked>
                    </div>
                </div>
            </div>
            
            <!-- API Access Card -->
            <div class="profile-card">
                <h5 class="card-title">
                    <i class="bi bi-code-square"></i>API Access
                </h5>
                
                <div class="api-key-item">
                    <div class="flex-grow-1">
                        <div class="api-key">sk_live_••••••••••••••••••••••</div>
                        <small class="text-muted">Last used: 2 hours ago</small>
                    </div>
                    <button class="btn btn-sm btn-outline-secondary" onclick="copyApiKey()">
                        <i class="bi bi-files"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="regenerateApiKey()">
                        <i class="bi bi-arrow-repeat"></i>
                    </button>
                </div>
                
                <div class="api-key-item">
                    <div class="flex-grow-1">
                        <div class="api-key">sk_test_••••••••••••••••••••••</div>
                        <small class="text-muted">Test mode - Never use in production</small>
                    </div>
                    <button class="btn btn-sm btn-outline-secondary" onclick="copyApiKey()">
                        <i class="bi bi-files"></i>
                    </button>
                </div>
                
                <div class="text-center mt-2">
                    <a href="#" class="small">API Documentation <i class="bi bi-box-arrow-up-right ms-1"></i></a>
                </div>
            </div>
            
            <!-- Danger Zone Card -->
            <div class="profile-card border-danger">
                <h5 class="card-title text-danger">
                    <i class="bi bi-exclamation-triangle"></i>Danger Zone
                </h5>
                
                <div class="security-item">
                    <div class="security-info">
                        <h6>Deactivate Account</h6>
                        <p>Temporarily disable your account</p>
                    </div>
                    <button class="btn btn-sm btn-outline-warning" onclick="deactivateAccount()">Deactivate</button>
                </div>
                
                <div class="security-item">
                    <div class="security-info">
                        <h6>Delete Account</h6>
                        <p>Permanently delete your account and all data</p>
                    </div>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteAccount()">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Field Modal -->
<div class="modal fade" id="editFieldModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalTitle">Edit Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editFieldForm">
                    <div class="mb-3">
                        <label class="form-label" id="editFieldLabel">Value</label>
                        <input type="text" class="form-control" id="editFieldValue">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="updateField()">Update</button>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="changePasswordForm">
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="currentPassword" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('currentPassword')">
                                <i class="bi bi-eye-slash" id="currentPasswordToggle"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="newPassword" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('newPassword')">
                                <i class="bi bi-eye-slash" id="newPasswordToggle"></i>
                            </button>
                        </div>
                        
                        <!-- Password Strength Meter -->
                        <div class="password-strength mt-2">
                            <div class="strength-bars">
                                <div class="strength-bar" id="strengthBar1"></div>
                                <div class="strength-bar" id="strengthBar2"></div>
                                <div class="strength-bar" id="strengthBar3"></div>
                                <div class="strength-bar" id="strengthBar4"></div>
                            </div>
                            <span class="strength-text" id="strengthText">Enter a password</span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="confirmPassword" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirmPassword')">
                                <i class="bi bi-eye-slash" id="confirmPasswordToggle"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback" id="passwordMatchError"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="updatePassword()">Update Password</button>
            </div>
        </div>
    </div>
</div>

<!-- Change Avatar Modal -->
<div class="modal fade" id="changeAvatarModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Profile Picture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="avatar-preview" id="avatarPreview">
                    <img src="https://ui-avatars.com/api/?name=Admin+User&background=667eea&color=fff&size=200" alt="Avatar">
                </div>
                
                <div class="avatar-upload-area" onclick="document.getElementById('avatarUpload').click()">
                    <i class="bi bi-cloud-upload"></i>
                    <p>Click to upload or drag and drop</p>
                    <small class="text-muted">PNG, JPG up to 5MB</small>
                    <input type="file" id="avatarUpload" class="d-none" accept="image/*" onchange="previewAvatar(this)">
                </div>
                
                <div class="row g-2 mt-3">
                    <div class="col-3">
                        <img src="https://ui-avatars.com/api/?name=Admin+User&background=667eea&color=fff&size=100" class="img-thumbnail rounded-circle" onclick="selectAvatar('color1')">
                    </div>
                    <div class="col-3">
                        <img src="https://ui-avatars.com/api/?name=Admin+User&background=27ae60&color=fff&size=100" class="img-thumbnail rounded-circle" onclick="selectAvatar('color2')">
                    </div>
                    <div class="col-3">
                        <img src="https://ui-avatars.com/api/?name=Admin+User&background=e74c3c&color=fff&size=100" class="img-thumbnail rounded-circle" onclick="selectAvatar('color3')">
                    </div>
                    <div class="col-3">
                        <img src="https://ui-avatars.com/api/?name=Admin+User&background=f39c12&color=fff&size=100" class="img-thumbnail rounded-circle" onclick="selectAvatar('color4')">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveAvatar()">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- 2FA Setup Modal -->
<div class="modal fade" id="twoFAModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Two-Factor Authentication</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-4">
                    <i class="bi bi-shield-check" style="font-size: 4rem; color: #667eea;"></i>
                </div>
                
                <h6 class="mb-3">Scan QR Code with Google Authenticator</h6>
                
                <div class="bg-light p-4 rounded-3 mb-3 d-inline-block">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=otpauth://totp/StayEase:admin%40stayease.com?secret=JBSWY3DPEHPK3PXP&issuer=StayEase" alt="QR Code">
                </div>
                
                <p class="small text-muted mb-3">Or enter this code manually: <strong>JBSW Y3DP EHPK 3PXP</strong></p>
                
                <div class="mb-3">
                    <label class="form-label">Verification Code</label>
                    <input type="text" class="form-control text-center" placeholder="Enter 6-digit code">
                </div>
                
                <div class="alert alert-info small">
                    <i class="bi bi-info-circle me-2"></i>
                    Download Google Authenticator from App Store or Play Store
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="verify2FA()">Verify & Enable</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Edit field function
    function editField(field) {
        let title = '';
        let currentValue = '';
        let inputType = 'text';
        
        switch(field) {
            case 'name':
                title = 'Edit Full Name';
                currentValue = 'Admin User';
                break;
            case 'email':
                title = 'Edit Email Address';
                currentValue = 'admin@stayease.com';
                inputType = 'email';
                break;
            case 'phone':
                title = 'Edit Phone Number';
                currentValue = '+63 912 345 6789';
                break;
            case 'dob':
                title = 'Edit Date of Birth';
                currentValue = '1990-01-15';
                inputType = 'date';
                break;
            case 'gender':
                title = 'Edit Gender';
                currentValue = 'Male';
                break;
            case 'street':
                title = 'Edit Street Address';
                currentValue = '123 Mabini Street';
                break;
            case 'city':
                title = 'Edit City';
                currentValue = 'Makati City';
                break;
            case 'province':
                title = 'Edit Province';
                currentValue = 'Metro Manila';
                break;
            case 'zip':
                title = 'Edit Zip Code';
                currentValue = '1200';
                break;
            case 'username':
                title = 'Edit Username';
                currentValue = 'admin_user';
                break;
        }
        
        $('#editModalTitle').text(title);
        $('#editFieldLabel').text(title.replace('Edit ', ''));
        $('#editFieldValue').attr('type', inputType).val(currentValue);
        $('#editFieldModal').modal('show');
    }

    // Update field function
    function updateField() {
        const newValue = $('#editFieldValue').val();
        showToast('success', 'Information updated successfully');
        $('#editFieldModal').modal('hide');
    }

    // Change password function
    function changePassword() {
        $('#changePasswordModal').modal('show');
    }

    // Toggle password visibility
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const toggle = document.getElementById(fieldId + 'Toggle');
        
        if (field.type === 'password') {
            field.type = 'text';
            toggle.classList.remove('bi-eye-slash');
            toggle.classList.add('bi-eye');
        } else {
            field.type = 'password';
            toggle.classList.remove('bi-eye');
            toggle.classList.add('bi-eye-slash');
        }
    }

    // Password strength checker
    $('#newPassword').on('input', function() {
        const password = $(this).val();
        const strength = checkPasswordStrength(password);
        
        // Reset bars
        $('.strength-bar').removeClass('weak fair good strong');
        
        // Set active bars
        for (let i = 0; i < strength.score; i++) {
            $(`#strengthBar${i+1}`).addClass(strength.class);
        }
        
        $('#strengthText').text(strength.message).css('color', strength.color);
    });

    function checkPasswordStrength(password) {
        let score = 0;
        let message = '';
        let color = '#718096';
        let className = '';
        
        if (password.length === 0) {
            return { score: 0, message: 'Enter a password', color: '#718096', class: '' };
        }
        
        // Length check
        if (password.length >= 8) score++;
        if (password.length >= 12) score++;
        
        // Complexity checks
        if (/[a-z]/.test(password) && /[A-Z]/.test(password)) score++;
        if (/[0-9]/.test(password)) score++;
        if (/[^a-zA-Z0-9]/.test(password)) score++;
        
        // Cap score at 4
        score = Math.min(score, 4);
        
        // Set message based on score
        switch(score) {
            case 1:
                message = 'Weak';
                color = '#e74c3c';
                className = 'weak';
                break;
            case 2:
                message = 'Fair';
                color = '#f39c12';
                className = 'fair';
                break;
            case 3:
                message = 'Good';
                color = '#3498db';
                className = 'good';
                break;
            case 4:
                message = 'Strong';
                color = '#27ae60';
                className = 'strong';
                break;
            default:
                message = 'Very weak';
                color = '#e74c3c';
                className = 'weak';
        }
        
        return { score, message, color, class: className };
    }

    // Password match validation
    $('#confirmPassword, #newPassword').on('input', function() {
        const newPass = $('#newPassword').val();
        const confirmPass = $('#confirmPassword').val();
        
        if (confirmPass && newPass !== confirmPass) {
            $('#confirmPassword').addClass('is-invalid');
            $('#passwordMatchError').text('Passwords do not match').show();
        } else {
            $('#confirmPassword').removeClass('is-invalid');
            $('#passwordMatchError').hide();
        }
    });

    // Update password function
    function updatePassword() {
        const newPass = $('#newPassword').val();
        const confirmPass = $('#confirmPassword').val();
        
        if (newPass !== confirmPass) {
            showToast('error', 'Passwords do not match');
            return;
        }
        
        showLoading();
        setTimeout(() => {
            hideLoading();
            $('#changePasswordModal').modal('hide');
            $('#changePasswordForm')[0].reset();
            showToast('success', 'Password updated successfully');
        }, 1500);
    }

    // Change avatar function
    function changeAvatar() {
        $('#changeAvatarModal').modal('show');
    }

    // Preview avatar
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#avatarPreview img').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Select avatar color
    function selectAvatar(color) {
        let bgColor = '#667eea';
        switch(color) {
            case 'color1': bgColor = '#667eea'; break;
            case 'color2': bgColor = '#27ae60'; break;
            case 'color3': bgColor = '#e74c3c'; break;
            case 'color4': bgColor = '#f39c12'; break;
        }
        $('#avatarPreview img').attr('src', `https://ui-avatars.com/api/?name=Admin+User&background=${bgColor.replace('#', '')}&color=fff&size=200`);
    }

    // Save avatar function
    function saveAvatar() {
        showLoading();
        setTimeout(() => {
            hideLoading();
            $('#changeAvatarModal').modal('hide');
            showToast('success', 'Profile picture updated successfully');
        }, 1500);
    }

    // Manage 2FA function
    function manage2FA() {
        $('#twoFAModal').modal('show');
    }

    // Verify 2FA function
    function verify2FA() {
        showToast('success', '2FA enabled successfully');
        $('#twoFAModal').modal('hide');
    }

    // View activity log
    function viewActivityLog() {
        showToast('info', 'Loading activity log...');
    }

    // Save profile function
    function saveProfile() {
        showLoading();
        setTimeout(() => {
            hideLoading();
            showToast('success', 'Profile updated successfully');
        }, 1500);
    }

    // Terminate session
    function terminateSession() {
        Swal.fire({
            title: 'Terminate Session?',
            text: 'This will log out this device',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, terminate'
        }).then((result) => {
            if (result.isConfirmed) {
                showToast('success', 'Session terminated');
            }
        });
    }

    // Terminate all sessions
    function terminateAllSessions() {
        Swal.fire({
            title: 'Terminate All Sessions?',
            text: 'This will log out all other devices except this one',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, terminate all'
        }).then((result) => {
            if (result.isConfirmed) {
                showToast('success', 'All other sessions terminated');
            }
        });
    }

    // Copy API key
    function copyApiKey() {
        navigator.clipboard.writeText('sk_live_••••••••••••••••••••••');
        showToast('success', 'API key copied to clipboard');
    }

    // Regenerate API key
    function regenerateApiKey() {
        Swal.fire({
            title: 'Regenerate API Key?',
            text: 'Old API key will stop working immediately',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, regenerate'
        }).then((result) => {
            if (result.isConfirmed) {
                showToast('success', 'New API key generated');
            }
        });
    }

    // Deactivate account
    function deactivateAccount() {
        Swal.fire({
            title: 'Deactivate Account?',
            text: 'Your account will be temporarily disabled',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, deactivate'
        }).then((result) => {
            if (result.isConfirmed) {
                showToast('success', 'Account deactivated');
            }
        });
    }

    // Delete account
    function deleteAccount() {
        Swal.fire({
            title: 'Delete Account?',
            text: 'This action is permanent and cannot be undone',
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete forever'
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();
                setTimeout(() => {
                    hideLoading();
                    Swal.fire('Deleted', 'Account has been deleted', 'success');
                }, 2000);
            }
        });
    }

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
</script>
@endsection