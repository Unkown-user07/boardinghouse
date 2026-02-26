@extends('layouts.user')

@section('title', 'Settings - StayEase')

@section('page_header', 'Account Settings')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Settings</li>
@endsection

@section('header_actions')
    <button class="btn btn-primary" onclick="saveAllSettings()">
        <i class="bi bi-check-circle me-2"></i>Save All Changes
    </button>
@endsection

@section('styles')
<style>
    /* Settings Navigation */
    .settings-nav {
        background: white;
        border-radius: 15px;
        padding: 1rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        position: sticky;
        top: 100px;
    }
    
    .settings-nav-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s;
        margin-bottom: 0.5rem;
        color: #6c757d;
    }
    
    .settings-nav-item:hover {
        background: #f8f9fa;
        color: #4361ee;
    }
    
    .settings-nav-item.active {
        background: #eef2ff;
        color: #4361ee;
        font-weight: 500;
    }
    
    .settings-nav-item i {
        width: 24px;
        font-size: 1.2rem;
        margin-right: 1rem;
    }
    
    /* Settings Sections */
    .settings-section {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        scroll-margin-top: 100px;
    }
    
    .section-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f0f0f0;
    }
    
    /* Form Styles */
    .settings-form-group {
        margin-bottom: 1.5rem;
    }
    
    .settings-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .settings-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        transition: all 0.3s;
    }
    
    .settings-input:focus {
        border-color: #4361ee;
        outline: none;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }
    
    .settings-input.error {
        border-color: #ef476f;
    }
    
    .input-hint {
        font-size: 0.85rem;
        color: #6c757d;
        margin-top: 0.3rem;
    }
    
    .error-message {
        color: #ef476f;
        font-size: 0.85rem;
        margin-top: 0.3rem;
    }
    
    /* Toggle Switch */
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }
    
    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 34px;
    }
    
    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    
    input:checked + .toggle-slider {
        background-color: #4361ee;
    }
    
    input:checked + .toggle-slider:before {
        transform: translateX(26px);
    }
    
    /* Two-factor authentication */
    .twofa-setup {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 1.5rem;
        margin-top: 1rem;
        display: none;
    }
    
    .twofa-setup.show {
        display: block;
        animation: fadeIn 0.3s ease;
    }
    
    .qr-code {
        background: white;
        padding: 1rem;
        border-radius: 10px;
        display: inline-block;
        margin: 1rem 0;
    }
    
    /* Notification Options */
    .notification-option {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        border-bottom: 1px solid #e9ecef;
    }
    
    .notification-option:last-child {
        border-bottom: none;
    }
    
    .notification-info h6 {
        margin-bottom: 0.2rem;
        font-weight: 600;
    }
    
    .notification-info p {
        margin-bottom: 0;
        color: #6c757d;
        font-size: 0.9rem;
    }
    
    /* Privacy Options */
    .privacy-option {
        padding: 1rem;
        border-bottom: 1px solid #e9ecef;
    }
    
    .privacy-option:last-child {
        border-bottom: none;
    }
    
    .privacy-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }
    
    .privacy-header h6 {
        margin-bottom: 0;
        font-weight: 600;
    }
    
    .privacy-desc {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 0;
    }
    
    /* Connected Apps */
    .connected-app {
        display: flex;
        align-items: center;
        padding: 1rem;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        margin-bottom: 1rem;
    }
    
    .app-icon {
        width: 50px;
        height: 50px;
        background: #f8f9fa;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 1.5rem;
    }
    
    .app-info {
        flex: 1;
    }
    
    .app-info h6 {
        margin-bottom: 0.2rem;
        font-weight: 600;
    }
    
    .app-info p {
        margin-bottom: 0;
        color: #6c757d;
        font-size: 0.85rem;
    }
    
    .app-status {
        padding: 0.3rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .app-status.connected {
        background: #d4edda;
        color: #155724;
    }
    
    /* Danger Zone */
    .danger-zone {
        background: #fff5f5;
        border-radius: 15px;
        padding: 1.5rem;
        border: 1px solid #fcc;
    }
    
    .danger-zone .section-title {
        color: #dc3545;
        border-bottom-color: #fcc;
    }
    
    .danger-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 0;
        border-bottom: 1px solid #fcc;
    }
    
    .danger-item:last-child {
        border-bottom: none;
    }
    
    .btn-danger {
        background: #dc3545;
        color: white;
        border: none;
        padding: 0.5rem 1.5rem;
        border-radius: 8px;
        transition: all 0.3s;
    }
    
    .btn-danger:hover {
        background: #c82333;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
    }
    
    /* Session List */
    .session-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 10px;
        margin-bottom: 1rem;
    }
    
    .session-device {
        width: 40px;
        height: 40px;
        background: white;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 1.2rem;
    }
    
    .session-info {
        flex: 1;
    }
    
    .session-info h6 {
        margin-bottom: 0.2rem;
        font-weight: 600;
    }
    
    .session-meta {
        font-size: 0.85rem;
        color: #6c757d;
    }
    
    .session-current {
        background: #d4edda;
        color: #155724;
        padding: 0.2rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    /* Save Indicator */
    .save-indicator {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: #4361ee;
        color: white;
        padding: 1rem 2rem;
        border-radius: 50px;
        box-shadow: 0 5px 20px rgba(67, 97, 238, 0.4);
        display: none;
        align-items: center;
        gap: 0.5rem;
        z-index: 1000;
        animation: slideUp 0.3s ease;
    }
    
    @keyframes slideUp {
        from { transform: translateY(100px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    
    /* Password Strength */
    .password-strength {
        margin-top: 0.5rem;
    }
    
    .strength-bar {
        height: 5px;
        background: #e9ecef;
        border-radius: 5px;
        overflow: hidden;
        margin-bottom: 0.3rem;
    }
    
    .strength-progress {
        height: 100%;
        width: 0;
        transition: width 0.3s, background-color 0.3s;
    }
    
    .strength-text {
        font-size: 0.85rem;
        color: #6c757d;
    }
    
    /* API Key */
    .api-key {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-family: monospace;
        font-size: 1rem;
    }
    
    .api-key .key {
        letter-spacing: 2px;
    }
    
    .copy-btn {
        background: none;
        border: none;
        color: #4361ee;
        cursor: pointer;
        padding: 0.3rem 1rem;
        border-radius: 5px;
        transition: all 0.2s;
    }
    
    .copy-btn:hover {
        background: #eef2ff;
    }
</style>
@endsection

@section('content')
<div class="row fade-in">
    <!-- Settings Navigation -->
    <div class="col-lg-3 mb-4">
        <div class="settings-nav">
            <div class="settings-nav-item active" onclick="scrollToSection('account', this)">
                <i class="bi bi-person-circle"></i>
                Account Settings
            </div>
            <div class="settings-nav-item" onclick="scrollToSection('profile', this)">
                <i class="bi bi-card-text"></i>
                Profile Information
            </div>
            <div class="settings-nav-item" onclick="scrollToSection('password', this)">
                <i class="bi bi-shield-lock"></i>
                Password & Security
            </div>
            <div class="settings-nav-item" onclick="scrollToSection('notifications', this)">
                <i class="bi bi-bell"></i>
                Notifications
            </div>
            <div class="settings-nav-item" onclick="scrollToSection('privacy', this)">
                <i class="bi bi-eye"></i>
                Privacy & Data
            </div>
            <div class="settings-nav-item" onclick="scrollToSection('sessions', this)">
                <i class="bi bi-phone"></i>
                Active Sessions
            </div>
            <div class="settings-nav-item" onclick="scrollToSection('apps', this)">
                <i class="bi bi-grid-3x3-gap-fill"></i>
                Connected Apps
            </div>
            <div class="settings-nav-item" onclick="scrollToSection('billing', this)">
                <i class="bi bi-credit-card"></i>
                Billing Settings
            </div>
            <div class="settings-nav-item" onclick="scrollToSection('danger', this)">
                <i class="bi bi-exclamation-triangle"></i>
                Danger Zone
            </div>
        </div>
    </div>
    
    <!-- Settings Content -->
    <div class="col-lg-9">
        <!-- Account Settings Section -->
        <div id="account" class="settings-section">
            <h5 class="section-title">
                <i class="bi bi-person-circle me-2 text-primary"></i>
                Account Settings
            </h5>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="settings-form-group">
                        <label class="settings-label">Email Address</label>
                        <input type="email" class="settings-input" value="john.doe@example.com" id="email">
                        <div class="input-hint">
                            <i class="bi bi-check-circle-fill text-success me-1"></i>
                            Verified
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="settings-form-group">
                        <label class="settings-label">Username</label>
                        <input type="text" class="settings-input" value="john_doe_2026" id="username">
                        <div class="input-hint">Minimum 3 characters, letters and numbers only</div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="settings-form-group">
                        <label class="settings-label">Phone Number</label>
                        <input type="tel" class="settings-input" value="+63 912 345 6789" id="phone">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="settings-form-group">
                        <label class="settings-label">Alternative Email</label>
                        <input type="email" class="settings-input" value="john.doe.backup@email.com" id="alt_email">
                    </div>
                </div>
            </div>
            
            <div class="settings-form-group">
                <label class="settings-label">Language</label>
                <select class="settings-input" id="language">
                    <option value="en" selected>English</option>
                    <option value="fil">Filipino</option>
                    <option value="es">Spanish</option>
                    <option value="zh">Chinese</option>
                </select>
            </div>
            
            <div class="settings-form-group">
                <label class="settings-label">Time Zone</label>
                <select class="settings-input" id="timezone">
                    <option value="Asia/Manila" selected>Asia/Manila (GMT+8)</option>
                    <option value="Asia/Tokyo">Asia/Tokyo (GMT+9)</option>
                    <option value="America/New_York">America/New York (GMT-5)</option>
                </select>
            </div>
            
            <button class="btn btn-primary" onclick="saveSection('account')">
                <i class="bi bi-check-circle me-2"></i>Save Account Settings
            </button>
        </div>
        
        <!-- Profile Information Section -->
        <div id="profile" class="settings-section">
            <h5 class="section-title">
                <i class="bi bi-card-text me-2 text-primary"></i>
                Profile Information
            </h5>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="settings-form-group">
                        <label class="settings-label">First Name</label>
                        <input type="text" class="settings-input" value="John" id="first_name">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="settings-form-group">
                        <label class="settings-label">Last Name</label>
                        <input type="text" class="settings-input" value="Doe" id="last_name">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="settings-form-group">
                        <label class="settings-label">Date of Birth</label>
                        <input type="date" class="settings-input" value="1998-03-15" id="dob">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="settings-form-group">
                        <label class="settings-label">Gender</label>
                        <select class="settings-input" id="gender">
                            <option value="male" selected>Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                            <option value="prefer_not">Prefer not to say</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="settings-form-group">
                <label class="settings-label">Bio</label>
                <textarea class="settings-input" rows="3" id="bio" placeholder="Tell us a little about yourself">Boarding house tenant since 2026. Working professional from Makati.</textarea>
                <div class="input-hint">Maximum 200 characters</div>
            </div>
            
            <div class="settings-form-group">
                <label class="settings-label">Profile Picture</label>
                <div class="d-flex align-items-center">
                    <img src="https://ui-avatars.com/api/?name=John+Doe&background=4361ee&color=fff&size=100" 
                         class="rounded-circle me-3" width="80" height="80">
                    <div>
                        <button class="btn btn-outline-primary btn-sm me-2">
                            <i class="bi bi-upload me-1"></i>Upload New
                        </button>
                        <button class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-trash me-1"></i>Remove
                        </button>
                        <div class="input-hint mt-2">JPG, PNG or GIF. Max 2MB.</div>
                    </div>
                </div>
            </div>
            
            <button class="btn btn-primary" onclick="saveSection('profile')">
                <i class="bi bi-check-circle me-2"></i>Save Profile Information
            </button>
        </div>
        
        <!-- Password & Security Section -->
        <div id="password" class="settings-section">
            <h5 class="section-title">
                <i class="bi bi-shield-lock me-2 text-primary"></i>
                Password & Security
            </h5>
            
            <div class="settings-form-group">
                <label class="settings-label">Current Password</label>
                <input type="password" class="settings-input" id="current_password" placeholder="Enter current password">
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="settings-form-group">
                        <label class="settings-label">New Password</label>
                        <input type="password" class="settings-input" id="new_password" placeholder="Enter new password" onkeyup="checkPasswordStrength()">
                        <div class="password-strength">
                            <div class="strength-bar">
                                <div class="strength-progress" id="strengthBar"></div>
                            </div>
                            <span class="strength-text" id="strengthText">Password strength</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="settings-form-group">
                        <label class="settings-label">Confirm New Password</label>
                        <input type="password" class="settings-input" id="confirm_password" placeholder="Confirm new password" onkeyup="checkPasswordMatch()">
                        <div class="input-hint" id="passwordMatchHint"></div>
                    </div>
                </div>
            </div>
            
            <button class="btn btn-primary mb-4" onclick="changePassword()">
                <i class="bi bi-key me-2"></i>Change Password
            </button>
            
            <hr class="my-4">
            
            <!-- Two-Factor Authentication -->
            <h6 class="fw-bold mb-3">Two-Factor Authentication (2FA)</h6>
            
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <p class="mb-1 fw-semibold">Enable Two-Factor Authentication</p>
                    <small class="text-secondary">Add an extra layer of security to your account</small>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" id="twofaToggle" onchange="toggle2FA()">
                    <span class="toggle-slider"></span>
                </label>
            </div>
            
            <div id="twofaSetup" class="twofa-setup">
                <p class="mb-3">Scan this QR code with your authenticator app (Google Authenticator, Authy, etc.)</p>
                
                <div class="qr-code">
                    <img src="https://via.placeholder.com/150" alt="2FA QR Code">
                </div>
                
                <div class="settings-form-group">
                    <label class="settings-label">Setup Key</label>
                    <div class="api-key">
                        <span class="key">JBSWY3DPEHPK3PXP</span>
                        <button class="copy-btn" onclick="copyToClipboard('JBSWY3DPEHPK3PXP')">
                            <i class="bi bi-files me-1"></i>Copy
                        </button>
                    </div>
                </div>
                
                <div class="settings-form-group">
                    <label class="settings-label">Verification Code</label>
                    <input type="text" class="settings-input" placeholder="Enter 6-digit code" maxlength="6">
                </div>
                
                <button class="btn btn-primary">Verify & Enable</button>
            </div>
        </div>
        
        <!-- Notifications Section -->
        <div id="notifications" class="settings-section">
            <h5 class="section-title">
                <i class="bi bi-bell me-2 text-primary"></i>
                Notification Preferences
            </h5>
            
            <h6 class="fw-semibold mb-3">Email Notifications</h6>
            
            <div class="notification-option">
                <div class="notification-info">
                    <h6>Payment Reminders</h6>
                    <p>Receive reminders before your rent is due</p>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" checked id="notify_payment">
                    <span class="toggle-slider"></span>
                </label>
            </div>
            
            <div class="notification-option">
                <div class="notification-info">
                    <h6>Maintenance Updates</h6>
                    <p>Get notified about maintenance schedules</p>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" checked id="notify_maintenance">
                    <span class="toggle-slider"></span>
                </label>
            </div>
            
            <div class="notification-option">
                <div class="notification-info">
                    <h6>Announcements</h6>
                    <p>Receive boarding house announcements</p>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" checked id="notify_announcements">
                    <span class="toggle-slider"></span>
                </label>
            </div>
            
            <div class="notification-option">
                <div class="notification-info">
                    <h6>Promotions & Offers</h6>
                    <p>Get special offers and promotions</p>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" id="notify_promos">
                    <span class="toggle-slider"></span>
                </label>
            </div>
            
            <hr class="my-4">
            
            <h6 class="fw-semibold mb-3">Push Notifications</h6>
            
            <div class="notification-option">
                <div class="notification-info">
                    <h6>Browser Notifications</h6>
                    <p>Receive notifications in your browser</p>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" checked id="notify_browser">
                    <span class="toggle-slider"></span>
                </label>
            </div>
            
            <div class="notification-option">
                <div class="notification-info">
                    <h6>Mobile Push Notifications</h6>
                    <p>Receive notifications on your mobile device</p>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" id="notify_mobile">
                    <span class="toggle-slider"></span>
                </label>
            </div>
            
            <button class="btn btn-primary mt-3" onclick="saveSection('notifications')">
                <i class="bi bi-check-circle me-2"></i>Save Notification Settings
            </button>
        </div>
        
        <!-- Privacy & Data Section -->
        <div id="privacy" class="settings-section">
            <h5 class="section-title">
                <i class="bi bi-eye me-2 text-primary"></i>
                Privacy & Data
            </h5>
            
            <div class="privacy-option">
                <div class="privacy-header">
                    <h6>Profile Visibility</h6>
                    <select class="form-select form-select-sm w-auto" id="profile_visibility">
                        <option value="public">Public</option>
                        <option value="private" selected>Private</option>
                        <option value="roommates">Roommates Only</option>
                    </select>
                </div>
                <p class="privacy-desc">Control who can see your profile information</p>
            </div>
            
            <div class="privacy-option">
                <div class="privacy-header">
                    <h6>Show Email Address</h6>
                    <label class="toggle-switch">
                        <input type="checkbox" id="show_email">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <p class="privacy-desc">Display your email address to roommates</p>
            </div>
            
            <div class="privacy-option">
                <div class="privacy-header">
                    <h6>Show Phone Number</h6>
                    <label class="toggle-switch">
                        <input type="checkbox" id="show_phone">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <p class="privacy-desc">Display your phone number to roommates</p>
            </div>
            
            <hr class="my-4">
            
            <h6 class="fw-semibold mb-3">Data Management</h6>
            
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <p class="mb-1 fw-semibold">Download Your Data</p>
                    <small class="text-secondary">Get a copy of your personal data</small>
                </div>
                <button class="btn btn-outline-primary" onclick="downloadData()">
                    <i class="bi bi-download me-2"></i>Download
                </button>
            </div>
            
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="mb-1 fw-semibold">Delete Account Data</p>
                    <small class="text-secondary">Permanently delete your account data</small>
                </div>
                <button class="btn btn-outline-danger" onclick="requestDataDeletion()">
                    <i class="bi bi-trash me-2"></i>Request Deletion
                </button>
            </div>
        </div>
        
        <!-- Active Sessions Section -->
        <div id="sessions" class="settings-section">
            <h5 class="section-title">
                <i class="bi bi-phone me-2 text-primary"></i>
                Active Sessions
            </h5>
            
            <div class="session-item">
                <div class="session-device">
                    <i class="bi bi-laptop"></i>
                </div>
                <div class="session-info">
                    <h6>Windows PC - Chrome</h6>
                    <div class="session-meta">
                        <i class="bi bi-geo-alt me-1"></i> Makati City, Philippines
                        <span class="mx-2">•</span>
                        <i class="bi bi-clock me-1"></i> Active now
                    </div>
                </div>
                <span class="session-current">Current Session</span>
            </div>
            
            <div class="session-item">
                <div class="session-device">
                    <i class="bi bi-phone"></i>
                </div>
                <div class="session-info">
                    <h6>iPhone 13 - Safari</h6>
                    <div class="session-meta">
                        <i class="bi bi-geo-alt me-1"></i> Makati City, Philippines
                        <span class="mx-2">•</span>
                        <i class="bi bi-clock me-1"></i> Last active 2 hours ago
                    </div>
                </div>
                <button class="btn btn-sm btn-outline-danger" onclick="terminateSession('iPhone')">
                    <i class="bi bi-x-circle me-1"></i>Terminate
                </button>
            </div>
            
            <div class="session-item">
                <div class="session-device">
                    <i class="bi bi-tablet"></i>
                </div>
                <div class="session-info">
                    <h6>iPad - Safari</h6>
                    <div class="session-meta">
                        <i class="bi bi-geo-alt me-1"></i> Quezon City, Philippines
                        <span class="mx-2">•</span>
                        <i class="bi bi-clock me-1"></i> Last active 2 days ago
                    </div>
                </div>
                <button class="btn btn-sm btn-outline-danger" onclick="terminateSession('iPad')">
                    <i class="bi bi-x-circle me-1"></i>Terminate
                </button>
            </div>
            
            <button class="btn btn-warning mt-3" onclick="terminateAllSessions()">
                <i class="bi bi-shield-exclamation me-2"></i>Sign Out All Other Devices
            </button>
        </div>
        
        <!-- Connected Apps Section -->
        <div id="apps" class="settings-section">
            <h5 class="section-title">
                <i class="bi bi-grid-3x3-gap-fill me-2 text-primary"></i>
                Connected Apps
            </h5>
            
            <div class="connected-app">
                <div class="app-icon">
                    <i class="bi bi-google text-danger"></i>
                </div>
                <div class="app-info">
                    <h6>Google Calendar</h6>
                    <p>Access granted • Used for payment reminders</p>
                </div>
                <span class="app-status connected me-3">Connected</span>
                <button class="btn btn-sm btn-outline-danger" onclick="revokeApp('google')">
                    Revoke
                </button>
            </div>
            
            <div class="connected-app">
                <div class="app-icon">
                    <i class="bi bi-facebook text-primary"></i>
                </div>
                <div class="app-info">
                    <h6>Facebook</h6>
                    <p>Access granted • Used for login</p>
                </div>
                <span class="app-status connected me-3">Connected</span>
                <button class="btn btn-sm btn-outline-danger" onclick="revokeApp('facebook')">
                    Revoke
                </button>
            </div>
            
            <div class="connected-app">
                <div class="app-icon">
                    <i class="bi bi-slack text-success"></i>
                </div>
                <div class="app-info">
                    <h6>Slack</h6>
                    <p>Access granted • Used for announcements</p>
                </div>
                <span class="app-status connected me-3">Connected</span>
                <button class="btn btn-sm btn-outline-danger" onclick="revokeApp('slack')">
                    Revoke
                </button>
            </div>
            
            <button class="btn btn-primary mt-3" onclick="connectNewApp()">
                <i class="bi bi-plus-circle me-2"></i>Connect New App
            </button>
        </div>
        
        <!-- Billing Settings Section -->
        <div id="billing" class="settings-section">
            <h5 class="section-title">
                <i class="bi bi-credit-card me-2 text-primary"></i>
                Billing Settings
            </h5>
            
            <h6 class="fw-semibold mb-3">Default Payment Method</h6>
            
            <div class="d-flex align-items-center p-3 border rounded-3 mb-3">
                <div class="bg-primary bg-opacity-10 p-3 rounded-3 me-3">
                    <i class="bi bi-credit-card-2-front text-primary fs-4"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-0 fw-semibold">Credit Card</h6>
                    <small class="text-secondary">**** **** **** 4242</small>
                </div>
                <span class="badge bg-success me-2">Default</span>
                <button class="btn btn-sm btn-outline-primary">Change</button>
            </div>
            
            <div class="settings-form-group mb-4">
                <label class="settings-label">Auto-pay Settings</label>
                <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3">
                    <div>
                        <p class="mb-1 fw-semibold">Enable Auto-pay</p>
                        <small class="text-secondary">Automatically pay your rent on the due date</small>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" id="autopay">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>
            
            <div class="settings-form-group">
                <label class="settings-label">Billing Address</label>
                <textarea class="settings-input" rows="2" id="billing_address">123 Mabini St., Barangay Poblacion, Makati City</textarea>
            </div>
            
            <div class="settings-form-group">
                <label class="settings-label">Tax Identification Number (TIN)</label>
                <input type="text" class="settings-input" value="123-456-789-000" id="tin">
                <div class="input-hint">Optional - for official receipts</div>
            </div>
            
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="email_receipt" checked>
                <label class="form-check-label" for="email_receipt">
                    Receive receipts via email
                </label>
            </div>
            
            <button class="btn btn-primary" onclick="saveSection('billing')">
                <i class="bi bi-check-circle me-2"></i>Save Billing Settings
            </button>
        </div>
        
        <!-- Danger Zone -->
        <div id="danger" class="settings-section danger-zone">
            <h5 class="section-title">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Danger Zone
            </h5>
            
            <div class="danger-item">
                <div>
                    <h6 class="fw-semibold mb-1">Disable Account</h6>
                    <small class="text-secondary">Temporarily disable your account</small>
                </div>
                <button class="btn btn-warning" onclick="disableAccount()">
                    Disable
                </button>
            </div>
            
            <div class="danger-item">
                <div>
                    <h6 class="fw-semibold mb-1">Delete Account</h6>
                    <small class="text-secondary">Permanently delete your account and all data</small>
                </div>
                <button class="btn-danger" onclick="deleteAccount()">
                    Delete Account
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Save Indicator -->
<div id="saveIndicator" class="save-indicator">
    <i class="bi bi-check-circle-fill"></i>
    <span>Changes saved successfully</span>
</div>
@endsection

@section('scripts')
<script>
    // Smooth scroll to section
    function scrollToSection(sectionId, element) {
        // Update active nav item
        document.querySelectorAll('.settings-nav-item').forEach(item => {
            item.classList.remove('active');
        });
        element.classList.add('active');
        
        // Scroll to section
        const section = document.getElementById(sectionId);
        section.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
    
    // Save individual section
    function saveSection(section) {
        showSaveIndicator();
    }
    
    // Save all settings
    function saveAllSettings() {
        showSaveIndicator();
    }
    
    // Show save indicator
    function showSaveIndicator() {
        const indicator = document.getElementById('saveIndicator');
        indicator.style.display = 'flex';
        
        setTimeout(() => {
            indicator.style.display = 'none';
        }, 3000);
    }
    
    // Password strength checker
    function checkPasswordStrength() {
        const password = document.getElementById('new_password').value;
        const strengthBar = document.getElementById('strengthBar');
        const strengthText = document.getElementById('strengthText');
        
        let strength = 0;
        
        if (password.length >= 8) strength++;
        if (password.match(/[a-z]/)) strength++;
        if (password.match(/[A-Z]/)) strength++;
        if (password.match(/[0-9]/)) strength++;
        if (password.match(/[^a-zA-Z0-9]/)) strength++;
        
        const strengthLevels = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];
        const strengthColors = ['#ef476f', '#ef476f', '#ffd166', '#06d6a0', '#06d6a0'];
        
        const width = (strength / 5) * 100;
        strengthBar.style.width = width + '%';
        strengthBar.style.backgroundColor = strengthColors[strength - 1] || '#ccc';
        
        if (password.length === 0) {
            strengthText.textContent = 'Password strength';
        } else {
            strengthText.textContent = strengthLevels[strength - 1] || 'Very Weak';
        }
    }
    
    // Check password match
    function checkPasswordMatch() {
        const password = document.getElementById('new_password').value;
        const confirm = document.getElementById('confirm_password').value;
        const hint = document.getElementById('passwordMatchHint');
        
        if (confirm.length === 0) {
            hint.innerHTML = '';
        } else if (password === confirm) {
            hint.innerHTML = '<span class="text-success"><i class="bi bi-check-circle-fill me-1"></i>Passwords match</span>';
        } else {
            hint.innerHTML = '<span class="text-danger"><i class="bi bi-exclamation-circle-fill me-1"></i>Passwords do not match</span>';
        }
    }
    
    // Change password
    function changePassword() {
        const current = document.getElementById('current_password').value;
        const newPass = document.getElementById('new_password').value;
        const confirm = document.getElementById('confirm_password').value;
        
        if (!current) {
            alert('Please enter your current password');
            return;
        }
        
        if (newPass.length < 8) {
            alert('Password must be at least 8 characters');
            return;
        }
        
        if (newPass !== confirm) {
            alert('Passwords do not match');
            return;
        }
        
        alert('Password changed successfully!');
        
        // Clear fields
        document.getElementById('current_password').value = '';
        document.getElementById('new_password').value = '';
        document.getElementById('confirm_password').value = '';
        checkPasswordStrength();
        checkPasswordMatch();
    }
    
    // Toggle 2FA
    function toggle2FA() {
        const toggle = document.getElementById('twofaToggle');
        const setup = document.getElementById('twofaSetup');
        
        if (toggle.checked) {
            setup.classList.add('show');
        } else {
            setup.classList.remove('show');
        }
    }
    
    // Copy to clipboard
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Copied to clipboard!');
        });
    }
    
    // Terminate session
    function terminateSession(device) {
        if (confirm(`Are you sure you want to terminate this session on ${device}?`)) {
            alert(`Session on ${device} terminated`);
        }
    }
    
    // Terminate all sessions
    function terminateAllSessions() {
        if (confirm('Are you sure you want to sign out all other devices? This will not affect your current session.')) {
            alert('All other devices have been signed out');
        }
    }
    
    // Revoke app access
    function revokeApp(app) {
        if (confirm(`Are you sure you want to revoke access for ${app}?`)) {
            alert(`${app} access has been revoked`);
        }
    }
    
    // Connect new app
    function connectNewApp() {
        alert('This would open the app connection wizard');
    }
    
    // Download data
    function downloadData() {
        alert('Preparing your data for download...');
    }
    
    // Request data deletion
    function requestDataDeletion() {
        if (confirm('Are you sure you want to request deletion of your account data? This action can take up to 30 days to complete.')) {
            alert('Data deletion request submitted');
        }
    }
    
    // Disable account
    function disableAccount() {
        if (confirm('Are you sure you want to temporarily disable your account? You can reactivate it anytime by logging in.')) {
            alert('Account has been disabled');
        }
    }
    
    // Delete account
    function deleteAccount() {
        if (confirm('WARNING: This action is permanent and cannot be undone. All your data will be permanently deleted. Are you absolutely sure?')) {
            const confirmText = prompt('Type "DELETE" to confirm account deletion:');
            if (confirmText === 'DELETE') {
                alert('Account deletion initiated. You will receive a confirmation email.');
            }
        }
    }
    
    // Connect new app
    function connectNewApp() {
        alert('This would open the app connection wizard');
    }
    
    // Update active nav item on scroll
    window.addEventListener('scroll', function() {
        const sections = document.querySelectorAll('.settings-section');
        const navItems = document.querySelectorAll('.settings-nav-item');
        
        let current = '';
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop - 150;
            const sectionHeight = section.clientHeight;
            if (scrollY >= sectionTop && scrollY < sectionTop + sectionHeight) {
                current = section.getAttribute('id');
            }
        });
        
        navItems.forEach(item => {
            item.classList.remove('active');
            if (item.getAttribute('onclick')?.includes(current)) {
                item.classList.add('active');
            }
        });
    });
    
    // Format phone number
    document.getElementById('phone')?.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 12) value = value.slice(0, 12);
        
        if (value.startsWith('63')) {
            value = '+' + value.slice(0, 2) + ' ' + value.slice(2, 5) + ' ' + value.slice(5, 8) + ' ' + value.slice(8, 12);
        }
        e.target.value = value.trim();
    });
    
    // Character counter for bio
    document.getElementById('bio')?.addEventListener('input', function(e) {
        const maxLength = 200;
        const currentLength = e.target.value.length;
        const hint = e.target.nextElementSibling;
        
        if (currentLength > maxLength) {
            e.target.value = e.target.value.slice(0, maxLength);
        }
        
        if (hint) {
            hint.innerHTML = `${currentLength}/${maxLength} characters`;
            if (currentLength >= maxLength) {
                hint.classList.add('text-danger');
            } else {
                hint.classList.remove('text-danger');
            }
        }
    });
</script>
@endsection