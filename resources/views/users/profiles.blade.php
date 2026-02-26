@extends('layouts.user')

@section('title', 'My Profile - StayEase')

@section('page_header', 'My Profile')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Profile</li>
@endsection

@section('header_actions')
    <button class="btn btn-primary" onclick="openEditModal()">
        <i class="bi bi-pencil-square me-2"></i>Edit Profile
    </button>
@endsection

@section('styles')
<style>
    .profile-header {
        background: linear-gradient(135deg, #4361ee 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    
    .profile-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        pointer-events: none;
    }
    
    .profile-avatar-wrapper {
        position: relative;
        display: inline-block;
    }
    
    .profile-avatar {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        border: 5px solid white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        object-fit: cover;
        transition: transform 0.3s;
    }
    
    .profile-avatar:hover {
        transform: scale(1.05);
    }
    
    .avatar-upload-btn {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #4361ee;
        cursor: pointer;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        transition: all 0.3s;
    }
    
    .avatar-upload-btn:hover {
        background: #4361ee;
        color: white;
        transform: scale(1.1);
    }
    
    .info-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        height: 100%;
        border: 1px solid #eee;
        transition: all 0.3s;
    }
    
    .info-card:hover {
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    
    .info-label {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 0.3rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .info-value {
        font-size: 1.1rem;
        font-weight: 600;
        color: #333;
    }
    
    .detail-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid #eee;
        transition: background 0.2s;
    }
    
    .detail-item:hover {
        background: #f8f9fa;
    }
    
    .detail-item:last-child {
        border-bottom: none;
    }
    
    .detail-icon {
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
    }
    
    .badge-status {
        background: #d4edda;
        color: #155724;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .badge-pending {
        background: #fff3cd;
        color: #856404;
    }
    
    .edit-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 1000;
        animation: fadeIn 0.3s ease;
    }
    
    .edit-modal-content {
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
    
    .tab-container {
        border-bottom: 2px solid #eee;
        margin-bottom: 1.5rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .tab {
        display: inline-block;
        padding: 0.8rem 1.5rem;
        cursor: pointer;
        color: #6c757d;
        font-weight: 500;
        border-bottom: 2px solid transparent;
        margin-bottom: -2px;
        transition: all 0.3s;
    }
    
    .tab:hover {
        color: #4361ee;
    }
    
    .tab.active {
        color: #4361ee;
        border-bottom-color: #4361ee;
    }
    
    .tab-content {
        display: none;
    }
    
    .tab-content.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }
    
    .document-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 10px;
        margin-bottom: 1rem;
    }
    
    .document-icon {
        width: 40px;
        height: 40px;
        background: white;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        color: #4361ee;
    }
    
    @media (max-width: 768px) {
        .profile-avatar {
            width: 100px;
            height: 100px;
        }
        
        .tab {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
        
        .detail-item {
            padding: 0.75rem;
        }
        
        .detail-icon {
            width: 35px;
            height: 35px;
            font-size: 1rem;
        }
    }
</style>
@endsection

@section('content')
<div class="row fade-in">
    <!-- Profile Header -->
    <div class="col-12">
        <div class="profile-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center flex-wrap">
                        <div class="profile-avatar-wrapper">
                            <img src="https://ui-avatars.com/api/?name=John+Doe&background=ffffff&color=4361ee&size=150" 
                                 class="profile-avatar" id="profileAvatar" alt="Profile Avatar">
                            <label class="avatar-upload-btn" for="avatarUpload" title="Upload new photo">
                                <i class="bi bi-camera-fill"></i>
                            </label>
                            <input type="file" id="avatarUpload" style="display: none;" accept="image/*">
                        </div>
                        <div class="ms-0 ms-md-4 mt-3 mt-md-0">
                            <h2 class="fw-bold mb-1">John Doe</h2>
                            <p class="mb-2 opacity-75"><i class="bi bi-envelope me-2"></i>john.doe@example.com</p>
                            <span class="badge-status">
                                <i class="bi bi-check-circle-fill"></i> Verified Account
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <div class="bg-white bg-opacity-10 p-3 rounded-3">
                        <small class="d-block opacity-75">Member Since</small>
                        <strong class="fs-5">January 15, 2026</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="col-12">
        <div class="tab-container">
            <span class="tab active" onclick="switchTab('personal', this)">Personal Information</span>
            <span class="tab" onclick="switchTab('room', this)">Room Details</span>
            <span class="tab" onclick="switchTab('documents', this)">Documents</span>
            <span class="tab" onclick="switchTab('emergency', this)">Emergency Contact</span>
        </div>
    </div>

    <!-- Personal Information Tab -->
    <div id="personal-tab" class="col-12 tab-content active">
        <div class="row">
            <!-- Basic Information -->
            <div class="col-md-6 mb-4">
                <div class="info-card">
                    <h5 class="mb-4 fw-bold">
                        <i class="bi bi-person-circle text-primary me-2"></i>
                        Basic Information
                    </h5>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-person"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="info-label">Full Name</div>
                            <div class="info-value">John Michael Doe</div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-gender-ambiguous"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="info-label">Gender</div>
                            <div class="info-value">Male</div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-calendar"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="info-label">Date of Birth</div>
                            <div class="info-value">March 15, 1998 (26 years)</div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-flag"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="info-label">Nationality</div>
                            <div class="info-value">Filipino</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Contact Information -->
            <div class="col-md-6 mb-4">
                <div class="info-card">
                    <h5 class="mb-4 fw-bold">
                        <i class="bi bi-telephone text-primary me-2"></i>
                        Contact Information
                    </h5>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="info-label">Email Address</div>
                            <div class="info-value">john.doe@example.com</div>
                            <small class="text-success"><i class="bi bi-check-circle-fill me-1"></i>Verified</small>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-phone"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="info-label">Mobile Number</div>
                            <div class="info-value">+63 912 345 6789</div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-telephone"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="info-label">Alternative Number</div>
                            <div class="info-value">+63 998 765 4321</div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="info-label">Address</div>
                            <div class="info-value">123 Mabini St., Barangay Poblacion</div>
                            <small class="text-secondary">Makati City, Metro Manila</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Identity Information -->
            <div class="col-md-6 mb-4">
                <div class="info-card">
                    <h5 class="mb-4 fw-bold">
                        <i class="bi bi-card-text text-primary me-2"></i>
                        Identity Information
                    </h5>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-card-heading"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="info-label">Valid ID Type</div>
                            <div class="info-value">Philippine Passport</div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-upc-scan"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="info-label">ID Number</div>
                            <div class="info-value">P123456789</div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-calendar"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="info-label">Expiry Date</div>
                            <div class="info-value">March 15, 2028</div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="info-label">Verification Status</div>
                            <div class="info-value">
                                <span class="badge bg-success">Verified</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Account Information -->
            <div class="col-md-6 mb-4">
                <div class="info-card">
                    <h5 class="mb-4 fw-bold">
                        <i class="bi bi-shield-lock text-primary me-2"></i>
                        Account Information
                    </h5>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="info-label">Username</div>
                            <div class="info-value">john_doe_2026</div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="info-label">Last Login</div>
                            <div class="info-value">February 26, 2026 08:30 AM</div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="info-label">Login History</div>
                            <div class="info-value">
                                <a href="#" class="text-primary text-decoration-none">View login history</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-shield-exclamation"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="info-label">Account Status</div>
                            <div class="info-value">
                                <span class="badge bg-success">Active</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Room Details Tab -->
    <div id="room-tab" class="col-12 tab-content">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="info-card">
                    <h5 class="mb-4 fw-bold">
                        <i class="bi bi-door-open text-primary me-2"></i>
                        Room Information
                    </h5>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-hash"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="info-label">Room Number</div>
                            <div class="info-value">204</div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-layers"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="info-label">Floor</div>
                            <div class="info-value">2nd Floor</div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="info-label">Room Type</div>
                            <div class="info-value">Shared (4 persons)</div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-rulers"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="info-label">Room Size</div>
                            <div class="info-value">25 sqm</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-4">
                <div class="info-card">
                    <h5 class="mb-4 fw-bold">
                        <i class="bi bi-calendar-range text-primary me-2"></i>
                        Contract Details
                    </h5>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-calendar-plus"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="info-label">Move In Date</div>
                            <div class="info-value">January 15, 2026</div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-calendar-minus"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="info-label">Contract End</div>
                            <div class="info-value">January 14, 2027</div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="info-label">Remaining Days</div>
                            <div class="info-value">322 days</div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="info-label">Monthly Rent</div>
                            <div class="info-value">₱4,500.00</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-12">
                <div class="info-card">
                    <h5 class="mb-4 fw-bold">
                        <i class="bi bi-people-fill text-primary me-2"></i>
                        Roommates
                    </h5>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                <img src="https://ui-avatars.com/api/?name=Michael+Chen&background=4361ee&color=fff" 
                                     class="rounded-circle me-3" width="50" alt="Michael Chen">
                                <div>
                                    <h6 class="mb-1 fw-bold">Michael Chen</h6>
                                    <small class="text-secondary">Since Jan 2026</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                <img src="https://ui-avatars.com/api/?name=Sarah+Santos&background=764ba2&color=fff" 
                                     class="rounded-circle me-3" width="50" alt="Sarah Santos">
                                <div>
                                    <h6 class="mb-1 fw-bold">Sarah Santos</h6>
                                    <small class="text-secondary">Since Dec 2025</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                <img src="https://ui-avatars.com/api/?name=Alex+Reyes&background=06d6a0&color=fff" 
                                     class="rounded-circle me-3" width="50" alt="Alex Reyes">
                                <div>
                                    <h6 class="mb-1 fw-bold">Alex Reyes</h6>
                                    <small class="text-secondary">Since Feb 2026</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Documents Tab -->
    <div id="documents-tab" class="col-12 tab-content">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="info-card">
                    <h5 class="mb-4 fw-bold">
                        <i class="bi bi-file-text text-primary me-2"></i>
                        Submitted Documents
                    </h5>
                    
                    <div class="document-item">
                        <div class="document-icon">
                            <i class="bi bi-file-pdf text-danger fs-4"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold">Valid ID - Passport</h6>
                            <small class="text-secondary">Uploaded: Jan 10, 2026</small>
                        </div>
                        <span class="badge bg-success">Verified</span>
                    </div>
                    
                    <div class="document-item">
                        <div class="document-icon">
                            <i class="bi bi-file-image text-primary fs-4"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold">Profile Photo</h6>
                            <small class="text-secondary">Uploaded: Jan 10, 2026</small>
                        </div>
                        <span class="badge bg-success">Verified</span>
                    </div>
                    
                    <div class="document-item">
                        <div class="document-icon">
                            <i class="bi bi-file-text text-warning fs-4"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold">Proof of Income</h6>
                            <small class="text-secondary">Uploaded: Jan 12, 2026</small>
                        </div>
                        <span class="badge bg-warning">Pending</span>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-4">
                <div class="info-card">
                    <h5 class="mb-4 fw-bold">
                        <i class="bi bi-upload text-primary me-2"></i>
                        Upload New Document
                    </h5>
                    
                    <form onsubmit="uploadDocument(event)">
                        <div class="mb-3">
                            <label class="form-label">Document Type</label>
                            <select class="form-select" id="docType">
                                <option value="valid_id">Valid ID</option>
                                <option value="proof_income">Proof of Income</option>
                                <option value="student_id">Student ID</option>
                                <option value="employment">Employment Certificate</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Upload File</label>
                            <input type="file" class="form-control" id="docFile" accept=".pdf,.jpg,.jpeg,.png">
                            <small class="text-secondary">Accepted formats: PDF, JPG, PNG (Max 5MB)</small>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-cloud-upload me-2"></i>Upload Document
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Emergency Contact Tab -->
    <div id="emergency-tab" class="col-12 tab-content">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="info-card">
                    <h5 class="mb-4 fw-bold">
                        <i class="bi bi-shield-exclamation text-primary me-2"></i>
                        Primary Emergency Contact
                    </h5>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-person"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="info-label">Full Name</div>
                            <div class="info-value">Maria Santos Doe</div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-phone"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="info-label">Contact Number</div>
                            <div class="info-value">+63 917 123 4567</div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="info-label">Email Address</div>
                            <div class="info-value">maria.doe@email.com</div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-heart"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="info-label">Relationship</div>
                            <div class="info-value">Mother</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-4">
                <div class="info-card">
                    <h5 class="mb-4 fw-bold">
                        <i class="bi bi-shield-plus text-primary me-2"></i>
                        Secondary Emergency Contact
                    </h5>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-person"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="info-label">Full Name</div>
                            <div class="info-value">Juan Carlos Santos</div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-phone"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="info-label">Contact Number</div>
                            <div class="info-value">+63 918 765 4321</div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="info-label">Email Address</div>
                            <div class="info-value">juancarlos@email.com</div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="bi bi-heart"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="info-label">Relationship</div>
                            <div class="info-value">Brother</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-12">
                <div class="info-card">
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Emergency contacts will be notified in case of emergencies or if you cannot be reached.
                        Please keep this information up to date.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div id="editProfileModal" class="edit-modal">
    <div class="edit-modal-content">
        <div class="modal-header">
            <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Profile</h5>
            <button type="button" class="btn-close btn-close-white" onclick="closeEditModal()" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="editProfileForm" onsubmit="saveProfile(event)">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">First Name</label>
                        <input type="text" class="form-control" value="John" id="firstName">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Last Name</label>
                        <input type="text" class="form-control" value="Doe" id="lastName">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control" value="john.doe@example.com" id="email">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mobile Number</label>
                        <input type="tel" class="form-control" value="+63 912 345 6789" id="mobile">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" value="1998-03-15" id="dob">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Gender</label>
                        <select class="form-select" id="gender">
                            <option value="male" selected>Male</option>
                            <option value="female">Female</option>
                            <option value="other">Prefer not to say</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <textarea class="form-control" rows="2" id="address">123 Mabini St., Barangay Poblacion, Makati City</textarea>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Emergency Contact Name</label>
                        <input type="text" class="form-control" value="Maria Santos Doe" id="emergencyName">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Emergency Contact Number</label>
                        <input type="tel" class="form-control" value="+63 917 123 4567" id="emergencyPhone">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Relationship</label>
                    <input type="text" class="form-control" value="Mother" id="relationship">
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
            <button class="btn btn-primary" onclick="saveProfile()">Save Changes</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Tab switching function
    function switchTab(tabName, element) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.remove('active');
        });
        
        // Remove active class from all tabs
        document.querySelectorAll('.tab').forEach(tab => {
            tab.classList.remove('active');
        });
        
        // Show selected tab content
        document.getElementById(tabName + '-tab').classList.add('active');
        
        // Add active class to clicked tab
        element.classList.add('active');
    }
    
    // Avatar upload preview
    document.getElementById('avatarUpload')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Check file size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('File size must be less than 2MB');
                return;
            }
            
            // Check file type
            if (!file.type.match('image.*')) {
                alert('Please upload an image file');
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profileAvatar').src = e.target.result;
                // Show success message (optional)
                showNotification('Profile picture updated successfully', 'success');
            }
            reader.readAsDataURL(file);
        }
    });
    
    // Edit modal functions
    function openEditModal() {
        document.getElementById('editProfileModal').style.display = 'block';
        document.body.style.overflow = 'hidden'; // Prevent scrolling
    }
    
    function closeEditModal() {
        document.getElementById('editProfileModal').style.display = 'none';
        document.body.style.overflow = 'auto'; // Restore scrolling
    }
    
    function saveProfile(event) {
        if (event) event.preventDefault();
        
        // Get form values
        const firstName = document.getElementById('firstName')?.value || 'John';
        const lastName = document.getElementById('lastName')?.value || 'Doe';
        
        // Simulate save
        showNotification('Profile updated successfully!', 'success');
        closeEditModal();
        
        // Update displayed name (optional)
        const nameElement = document.querySelector('.profile-header h2');
        if (nameElement) {
            nameElement.textContent = firstName + ' ' + lastName;
        }
    }
    
    // Document upload function
    function uploadDocument(event) {
        event.preventDefault();
        
        const docType = document.getElementById('docType')?.value;
        const docFile = document.getElementById('docFile')?.files[0];
        
        if (!docFile) {
            alert('Please select a file to upload');
            return;
        }
        
        // Check file size (max 5MB)
        if (docFile.size > 5 * 1024 * 1024) {
            alert('File size must be less than 5MB');
            return;
        }
        
        // Simulate upload
        showNotification('Document uploaded successfully!', 'success');
        
        // Reset form
        event.target.reset();
    }
    
    // Show notification function
    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
        notification.style.zIndex = '9999';
        notification.style.minWidth = '300px';
        notification.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="bi bi-${type === 'success' ? 'check-circle' : 'info-circle'}-fill me-2"></i>
                <span>${message}</span>
                <button type="button" class="btn-close ms-3" onclick="this.parentElement.parentElement.remove()"></button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('editProfileModal');
        if (event.target === modal) {
            closeEditModal();
        }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeEditModal();
        }
    });
    
    // Phone number formatting
    document.querySelectorAll('input[type="tel"]').forEach(input => {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 12) value = value.slice(0, 12);
            
            if (value.length > 0) {
                if (value.startsWith('63')) {
                    value = '+' + value.slice(0, 2) + ' ' + value.slice(2, 5) + ' ' + value.slice(5, 8) + ' ' + value.slice(8, 12);
                }
            }
            e.target.value = value.trim();
        });
    });
    
    // File input validation
    document.getElementById('docFile')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const fileName = file.name;
            const fileSize = (file.size / 1024 / 1024).toFixed(2);
            console.log(`Selected file: ${fileName} (${fileSize} MB)`);
        }
    });
    
    // Dirty form tracking
    let isDirty = false;
    document.querySelectorAll('#editProfileForm input, #editProfileForm select, #editProfileForm textarea').forEach(field => {
        field.addEventListener('change', () => {
            isDirty = true;
        });
    });
    
    // Warn before leaving with unsaved changes
    window.addEventListener('beforeunload', function(e) {
        if (isDirty && document.getElementById('editProfileModal').style.display === 'block') {
            e.preventDefault();
            e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
        }
    });
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Profile page loaded');
        
        // Add any initialization code here
    });
</script>
@endsection