@extends('layouts.user')

@section('title', 'My Room - StayEase')

@section('page_header', 'My Room')

@section('page_description', 'View and manage your room details, roommates, and amenities')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">My Room</li>
@endsection

@section('header_actions')
    <button class="btn btn-outline-primary" onclick="reportIssue()">
        <i class="bi bi-tools me-2"></i>Report Issue
    </button>
    <button class="btn btn-primary ms-2" onclick="requestMaintenance()">
        <i class="bi bi-calendar-check me-2"></i>Schedule Maintenance
    </button>
@endsection

@section('styles')
<style>
    /* Room Header */
    .room-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 2rem;
        color: white;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
    }
    
    .room-header::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }
    
    .room-header::after {
        content: '';
        position: absolute;
        bottom: -50px;
        left: -50px;
        width: 150px;
        height: 150px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }
    
    .room-number {
        font-size: 3rem;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 0.5rem;
    }
    
    .room-type {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 0;
    }
    
    .property-name {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.9rem;
    }
    
    .room-stats {
        display: flex;
        gap: 2rem;
        margin-top: 1rem;
    }
    
    .room-stat {
        text-align: center;
    }
    
    .room-stat-value {
        font-size: 1.5rem;
        font-weight: 700;
    }
    
    .room-stat-label {
        font-size: 0.8rem;
        opacity: 0.8;
    }
    
    /* Room Gallery */
    .room-gallery {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 5px 20px rgba(0,0,0,0.02);
        border: 1px solid rgba(0,0,0,0.03);
        margin-bottom: 1.5rem;
    }
    
    .gallery-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 1rem;
    }
    
    .main-image {
        width: 100%;
        height: 400px;
        border-radius: 15px;
        overflow: hidden;
        margin-bottom: 1rem;
        cursor: pointer;
        position: relative;
    }
    
    .main-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s;
    }
    
    .main-image:hover img {
        transform: scale(1.05);
    }
    
    .main-image-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
        color: white;
        padding: 1rem;
        transform: translateY(100%);
        transition: transform 0.3s;
    }
    
    .main-image:hover .main-image-overlay {
        transform: translateY(0);
    }
    
    .thumbnail-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 0.5rem;
    }
    
    .thumbnail {
        height: 80px;
        border-radius: 10px;
        overflow: hidden;
        cursor: pointer;
        opacity: 0.6;
        transition: all 0.3s;
    }
    
    .thumbnail.active {
        opacity: 1;
        border: 3px solid #667eea;
    }
    
    .thumbnail:hover {
        opacity: 1;
    }
    
    .thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    /* Room Details Card */
    .room-details-card {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 5px 20px rgba(0,0,0,0.02);
        border: 1px solid rgba(0,0,0,0.03);
        margin-bottom: 1.5rem;
    }
    
    .details-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .details-title i {
        color: #667eea;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    
    .info-item {
        padding: 0.75rem;
        background: #f8fafc;
        border-radius: 12px;
    }
    
    .info-label {
        font-size: 0.8rem;
        color: #718096;
        margin-bottom: 0.25rem;
    }
    
    .info-value {
        font-weight: 600;
        color: #2d3748;
    }
    
    /* Amenities Grid */
    .amenities-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-top: 1rem;
    }
    
    .amenity-tag {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem;
        background: #f8fafc;
        border-radius: 12px;
        transition: all 0.3s;
    }
    
    .amenity-tag:hover {
        background: #667eea;
        color: white;
        transform: translateY(-3px);
    }
    
    .amenity-tag:hover i {
        color: white;
    }
    
    .amenity-tag i {
        font-size: 1.2rem;
        color: #667eea;
        transition: color 0.3s;
    }
    
    .amenity-tag span {
        font-size: 0.9rem;
        font-weight: 500;
    }
    
    /* Roommates Card */
    .roommates-card {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 5px 20px rgba(0,0,0,0.02);
        border: 1px solid rgba(0,0,0,0.03);
    }
    
    .roommate-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border-bottom: 1px solid #edf2f7;
        transition: all 0.3s;
    }
    
    .roommate-item:last-child {
        border-bottom: none;
    }
    
    .roommate-item:hover {
        background: #f8fafc;
        border-radius: 12px;
    }
    
    .roommate-avatar {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    .roommate-info {
        flex: 1;
    }
    
    .roommate-name {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.2rem;
    }
    
    .roommate-details {
        font-size: 0.8rem;
        color: #718096;
    }
    
    .roommate-status {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
        border-radius: 20px;
        background: #e6f7e6;
        color: #27ae60;
    }
    
    .roommate-status.away {
        background: #fff3e0;
        color: #f39c12;
    }
    
    .invite-button {
        width: 100%;
        padding: 0.75rem;
        background: #f8fafc;
        border: 2px dashed #cbd5e0;
        border-radius: 12px;
        color: #718096;
        font-weight: 500;
        transition: all 0.3s;
        margin-top: 1rem;
    }
    
    .invite-button:hover {
        border-color: #667eea;
        color: #667eea;
        background: #f0f4ff;
    }
    
    /* Maintenance Card */
    .maintenance-card {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 5px 20px rgba(0,0,0,0.02);
        border: 1px solid rgba(0,0,0,0.03);
    }
    
    .maintenance-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border-bottom: 1px solid #edf2f7;
    }
    
    .maintenance-item:last-child {
        border-bottom: none;
    }
    
    .maintenance-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
    
    .maintenance-icon.pending {
        background: #fff3e0;
        color: #f39c12;
    }
    
    .maintenance-icon.completed {
        background: #e6f7e6;
        color: #27ae60;
    }
    
    .maintenance-icon.in-progress {
        background: #e3f2fd;
        color: #3498db;
    }
    
    .maintenance-content {
        flex: 1;
    }
    
    .maintenance-title {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.2rem;
        font-size: 0.95rem;
    }
    
    .maintenance-date {
        font-size: 0.75rem;
        color: #718096;
    }
    
    .maintenance-status {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
        border-radius: 20px;
    }
    
    .maintenance-status.pending {
        background: #fff3e0;
        color: #f39c12;
    }
    
    .maintenance-status.completed {
        background: #e6f7e6;
        color: #27ae60;
    }
    
    .maintenance-status.in-progress {
        background: #e3f2fd;
        color: #3498db;
    }
    
    /* Inventory List */
    .inventory-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .inventory-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem;
        border-bottom: 1px solid #edf2f7;
    }
    
    .inventory-item:last-child {
        border-bottom: none;
    }
    
    .inventory-name {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
    }
    
    .inventory-name i {
        color: #667eea;
    }
    
    .inventory-condition {
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
        border-radius: 20px;
    }
    
    .inventory-condition.good {
        background: #e6f7e6;
        color: #27ae60;
    }
    
    .inventory-condition.fair {
        background: #fff3e0;
        color: #f39c12;
    }
    
    .inventory-condition.poor {
        background: #fee9e9;
        color: #e74c3c;
    }
    
    /* Rules List */
    .rules-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .rules-list li {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem 0;
        border-bottom: 1px solid #edf2f7;
    }
    
    .rules-list li:last-child {
        border-bottom: none;
    }
    
    .rules-list li i {
        color: #667eea;
        font-size: 1rem;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .room-number {
            font-size: 2rem;
        }
        
        .room-stats {
            gap: 1rem;
        }
        
        .main-image {
            height: 250px;
        }
        
        .thumbnail-grid {
            grid-template-columns: repeat(3, 1fr);
        }
        
        .info-grid {
            grid-template-columns: 1fr;
        }
        
        .amenities-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 576px) {
        .room-header {
            padding: 1.5rem;
        }
        
        .room-stats {
            flex-wrap: wrap;
        }
        
        .amenities-grid {
            grid-template-columns: 1fr;
        }
        
        .thumbnail-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    /* Modal Styles */
    .issue-form-section {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .issue-form-section h6 {
        font-size: 0.9rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .issue-form-section h6 i {
        color: #667eea;
    }
</style>
@endsection

@section('content')
<div class="fade-in">
    <!-- Room Header -->
    <div class="room-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="property-name">
                    <i class="bi bi-building me-2"></i>Sunset Residences
                </div>
                <div class="room-number">Room 204</div>
                <div class="room-type">Deluxe Room (4 persons)</div>
                
                <div class="room-stats">
                    <div class="room-stat">
                        <div class="room-stat-value">2nd</div>
                        <div class="room-stat-label">Floor</div>
                    </div>
                    <div class="room-stat">
                        <div class="room-stat-value">28m²</div>
                        <div class="room-stat-label">Room Size</div>
                    </div>
                    <div class="room-stat">
                        <div class="room-stat-value">₱4,500</div>
                        <div class="room-stat-label">Monthly Rent</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="qr-code bg-white p-3 rounded-3 d-inline-block">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=Room204-SunsetResidences" alt="QR Code" width="100">
                    <p class="text-white mt-2 mb-0">Scan to report issue</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Room Gallery -->
            <div class="room-gallery">
                <h5 class="gallery-title">
                    <i class="bi bi-images me-2"></i>Room Gallery
                </h5>
                
                <div class="main-image" onclick="openGallery()">
                    <img src="https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Room" id="mainRoomImage">
                    <div class="main-image-overlay">
                        <i class="bi bi-arrows-fullscreen me-2"></i>Click to view full gallery
                    </div>
                </div>
                
                <div class="thumbnail-grid">
                    <div class="thumbnail active" onclick="changeImage(this, 'https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80')">
                        <img src="https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Room">
                    </div>
                    <div class="thumbnail" onclick="changeImage(this, 'https://images.unsplash.com/photo-1560448204-603b3fc33ddc?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80')">
                        <img src="https://images.unsplash.com/photo-1560448204-603b3fc33ddc?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Room">
                    </div>
                    <div class="thumbnail" onclick="changeImage(this, 'https://images.unsplash.com/photo-1554995207-c18c203602cb?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80')">
                        <img src="https://images.unsplash.com/photo-1554995207-c18c203602cb?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Room">
                    </div>
                    <div class="thumbnail" onclick="changeImage(this, 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80')">
                        <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Room">
                    </div>
                    <div class="thumbnail" onclick="changeImage(this, 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80')">
                        <img src="https://images.unsplash.com/photo-1586023492125-27b2c045efd7?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Room">
                    </div>
                </div>
            </div>

            <!-- Room Details -->
            <div class="room-details-card">
                <h5 class="details-title">
                    <i class="bi bi-info-circle"></i>Room Details
                </h5>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Room Number</div>
                                <div class="info-value">204</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Floor</div>
                                <div class="info-value">2nd Floor</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Room Type</div>
                                <div class="info-value">Deluxe</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Capacity</div>
                                <div class="info-value">4 persons</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Room Size</div>
                                <div class="info-value">28 m²</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Monthly Rent</div>
                                <div class="info-value">₱4,500</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Security Deposit</div>
                                <div class="info-value">₱4,500</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Move In Date</div>
                                <div class="info-value">January 15, 2026</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Lease End</div>
                                <div class="info-value">July 14, 2026</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Days Remaining</div>
                                <div class="info-value">104 days</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Last Maintained</div>
                                <div class="info-value">February 1, 2026</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Next Maintenance</div>
                                <div class="info-value">March 1, 2026</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Amenities -->
            <div class="room-details-card">
                <h5 class="details-title">
                    <i class="bi bi-grid"></i>Amenities & Features
                </h5>
                
                <div class="amenities-grid">
                    <div class="amenity-tag">
                        <i class="bi bi-wifi"></i>
                        <span>Free WiFi</span>
                    </div>
                    <div class="amenity-tag">
                        <i class="bi bi-snow"></i>
                        <span>Air Conditioning</span>
                    </div>
                    <div class="amenity-tag">
                        <i class="bi bi-droplet"></i>
                        <span>Own Bathroom</span>
                    </div>
                    <div class="amenity-tag">
                        <i class="bi bi-tv"></i>
                        <span>Television</span>
                    </div>
                    <div class="amenity-tag">
                        <i class="bi bi-fridge"></i>
                        <span>Refrigerator</span>
                    </div>
                    <div class="amenity-tag">
                        <i class="bi bi-cup"></i>
                        <span>Kitchen Access</span>
                    </div>
                    <div class="amenity-tag">
                        <i class="bi bi-fan"></i>
                        <span>Electric Fan</span>
                    </div>
                    <div class="amenity-tag">
                        <i class="bi bi-table"></i>
                        <span>Study Table</span>
                    </div>
                    <div class="amenity-tag">
                        <i class="bi bi-bed"></i>
                        <span>Bed Frame</span>
                    </div>
                    <div class="amenity-tag">
                        <i class="bi bi-archive"></i>
                        <span>Cabinet</span>
                    </div>
                    <div class="amenity-tag">
                        <i class="bi bi-droplet-half"></i>
                        <span>Water Heater</span>
                    </div>
                    <div class="amenity-tag">
                        <i class="bi bi-window"></i>
                        <span>Balcony</span>
                    </div>
                </div>
            </div>

            <!-- Room Inventory -->
            <div class="room-details-card">
                <h5 class="details-title">
                    <i class="bi bi-box"></i>Room Inventory
                </h5>
                
                <ul class="inventory-list">
                    <li class="inventory-item">
                        <span class="inventory-name">
                            <i class="bi bi-bed"></i>Double Deck Bed
                        </span>
                        <span class="inventory-condition good">Good</span>
                    </li>
                    <li class="inventory-item">
                        <span class="inventory-name">
                            <i class="bi bi-archive"></i>Wooden Cabinet
                        </span>
                        <span class="inventory-condition good">Good</span>
                    </li>
                    <li class="inventory-item">
                        <span class="inventory-name">
                            <i class="bi bi-table"></i>Study Desk
                        </span>
                        <span class="inventory-condition fair">Fair</span>
                    </li>
                    <li class="inventory-item">
                        <span class="inventory-name">
                            <i class="bi bi-lamp"></i>Desk Lamp
                        </span>
                        <span class="inventory-condition good">Good</span>
                    </li>
                    <li class="inventory-item">
                        <span class="inventory-name">
                            <i class="bi bi-fan"></i>Ceiling Fan
                        </span>
                        <span class="inventory-condition good">Good</span>
                    </li>
                    <li class="inventory-item">
                        <span class="inventory-name">
                            <i class="bi bi-trash"></i>Trash Can
                        </span>
                        <span class="inventory-condition good">Good</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Roommates -->
            <div class="roommates-card mb-4">
                <h5 class="details-title">
                    <i class="bi bi-people"></i>Roommates (3/4)
                </h5>
                
                <div class="roommate-item">
                    <div class="roommate-avatar">JD</div>
                    <div class="roommate-info">
                        <div class="roommate-name">John Doe</div>
                        <div class="roommate-details">Since Jan 15, 2026</div>
                    </div>
                    <span class="roommate-status">Active</span>
                </div>
                
                <div class="roommate-item">
                    <div class="roommate-avatar">MS</div>
                    <div class="roommate-info">
                        <div class="roommate-name">Maria Santos</div>
                        <div class="roommate-details">Since Jan 15, 2026</div>
                    </div>
                    <span class="roommate-status">Active</span>
                </div>
                
                <div class="roommate-item">
                    <div class="roommate-avatar">AR</div>
                    <div class="roommate-info">
                        <div class="roommate-name">Alex Reyes</div>
                        <div class="roommate-details">Since Feb 1, 2026</div>
                    </div>
                    <span class="roommate-status away">Away</span>
                </div>
                
                <div class="roommate-item">
                    <div class="roommate-avatar" style="background: #cbd5e0; color: #718096;">
                        <i class="bi bi-plus"></i>
                    </div>
                    <div class="roommate-info">
                        <div class="roommate-name">Empty Space</div>
                        <div class="roommate-details">Available for new roommate</div>
                    </div>
                </div>
                
                <button class="invite-button" onclick="inviteRoommate()">
                    <i class="bi bi-envelope-plus me-2"></i>Invite Roommate
                </button>
            </div>

            <!-- Maintenance Requests -->
            <div class="maintenance-card mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="details-title mb-0">
                        <i class="bi bi-tools"></i>Maintenance
                    </h5>
                    <button class="btn btn-sm btn-outline-primary" onclick="requestMaintenance()">
                        <i class="bi bi-plus"></i>New
                    </button>
                </div>
                
                <div class="maintenance-item">
                    <div class="maintenance-icon pending">
                        <i class="bi bi-droplet"></i>
                    </div>
                    <div class="maintenance-content">
                        <div class="maintenance-title">Leaking Faucet</div>
                        <div class="maintenance-date">Reported Feb 20, 2026</div>
                    </div>
                    <span class="maintenance-status pending">Pending</span>
                </div>
                
                <div class="maintenance-item">
                    <div class="maintenance-icon in-progress">
                        <i class="bi bi-lightbulb"></i>
                    </div>
                    <div class="maintenance-content">
                        <div class="maintenance-title">Broken Light</div>
                        <div class="maintenance-date">Reported Feb 15, 2026</div>
                    </div>
                    <span class="maintenance-status in-progress">In Progress</span>
                </div>
                
                <div class="maintenance-item">
                    <div class="maintenance-icon completed">
                        <i class="bi bi-tools"></i>
                    </div>
                    <div class="maintenance-content">
                        <div class="maintenance-title">Aircon Cleaning</div>
                        <div class="maintenance-date">Completed Feb 10, 2026</div>
                    </div>
                    <span class="maintenance-status completed">Completed</span>
                </div>
                
                <div class="text-center mt-3">
                    <a href="#" class="text-primary small">View All Requests <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>

            <!-- House Rules -->
            <div class="room-details-card">
                <h5 class="details-title">
                    <i class="bi bi-file-text"></i>House Rules
                </h5>
                
                <ul class="rules-list">
                    <li>
                        <i class="bi bi-check-circle-fill text-success"></i>
                        Quiet hours from 10PM to 6AM
                    </li>
                    <li>
                        <i class="bi bi-check-circle-fill text-success"></i>
                        No smoking inside the room
                    </li>
                    <li>
                        <i class="bi bi-check-circle-fill text-success"></i>
                        Guests must register at the office
                    </li>
                    <li>
                        <i class="bi bi-check-circle-fill text-success"></i>
                        No pets allowed
                    </li>
                    <li>
                        <i class="bi bi-check-circle-fill text-success"></i>
                        Clean common areas after use
                    </li>
                    <li>
                        <i class="bi bi-check-circle-fill text-success"></i>
                        Report damages immediately
                    </li>
                </ul>
            </div>

            <!-- Emergency Contacts -->
            <div class="room-details-card">
                <h5 class="details-title">
                    <i class="bi bi-exclamation-triangle"></i>Emergency Contacts
                </h5>
                
                <div class="info-item mb-2">
                    <div class="info-label">Landlord</div>
                    <div class="info-value">Juan Dela Cruz</div>
                    <small class="text-muted">+63 912 345 6789</small>
                </div>
                
                <div class="info-item mb-2">
                    <div class="info-label">Property Manager</div>
                    <div class="info-value">Maria Santos</div>
                    <small class="text-muted">+63 923 456 7890</small>
                </div>
                
                <div class="info-item mb-2">
                    <div class="info-label">Security</div>
                    <div class="info-value">Security Guard</div>
                    <small class="text-muted">+63 934 567 8901</small>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Emergency Hotline</div>
                    <div class="info-value">911 / 117</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Report Issue Modal -->
<div class="modal fade" id="reportIssueModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Report an Issue</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="reportIssueForm">
                    <div class="issue-form-section">
                        <h6><i class="bi bi-exclamation-circle"></i> Issue Details</h6>
                        <div class="mb-3">
                            <label class="form-label">Issue Type</label>
                            <select class="form-select" name="issue_type" required>
                                <option value="">Select issue type...</option>
                                <option value="electrical">Electrical</option>
                                <option value="plumbing">Plumbing</option>
                                <option value="appliance">Appliance</option>
                                <option value="furniture">Furniture</option>
                                <option value="cleaning">Cleaning</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" name="title" placeholder="Brief description" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3" placeholder="Describe the issue in detail" required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Priority</label>
                            <select class="form-select" name="priority">
                                <option value="low">Low - Can wait</option>
                                <option value="medium" selected>Medium - Needs attention</option>
                                <option value="high">High - Urgent</option>
                                <option value="emergency">Emergency - Immediate</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="issue-form-section">
                        <h6><i class="bi bi-camera"></i> Attachments</h6>
                        <div class="mb-3">
                            <label class="form-label">Upload Photos</label>
                            <input type="file" class="form-control" name="photos[]" multiple accept="image/*">
                            <small class="text-muted">You can upload multiple photos</small>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitIssue()">Submit Report</button>
            </div>
        </div>
    </div>
</div>

<!-- Invite Roommate Modal -->
<div class="modal fade" id="inviteRoommateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Invite a Roommate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="inviteRoommateForm">
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control" name="email" placeholder="friend@email.com" required>
                        <small class="text-muted">Send invitation to your potential roommate</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Personal Message (Optional)</label>
                        <textarea class="form-control" name="message" rows="3" placeholder="Hi! I'd like to invite you to be my roommate at StayEase..."></textarea>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="shareContact">
                        <label class="form-check-label" for="shareContact">
                            Share my contact information
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="sendInvitation()">Send Invitation</button>
            </div>
        </div>
    </div>
</div>

<!-- Full Gallery Modal -->
<div class="modal fade" id="galleryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Room Gallery</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="roomGalleryCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" class="d-block w-100" alt="Room">
                        </div>
                        <div class="carousel-item">
                            <img src="https://images.unsplash.com/photo-1560448204-603b3fc33ddc?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" class="d-block w-100" alt="Room">
                        </div>
                        <div class="carousel-item">
                            <img src="https://images.unsplash.com/photo-1554995207-c18c203602cb?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" class="d-block w-100" alt="Room">
                        </div>
                        <div class="carousel-item">
                            <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" class="d-block w-100" alt="Room">
                        </div>
                        <div class="carousel-item">
                            <img src="https://images.unsplash.com/photo-1586023492125-27b2c045efd7?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" class="d-block w-100" alt="Room">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#roomGalleryCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#roomGalleryCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Change main image
    function changeImage(thumbnail, imageUrl) {
        document.querySelectorAll('.thumbnail').forEach(thumb => {
            thumb.classList.remove('active');
        });
        thumbnail.classList.add('active');
        document.getElementById('mainRoomImage').src = imageUrl;
    }

    // Open full gallery
    function openGallery() {
        $('#galleryModal').modal('show');
    }

    // Report issue
    function reportIssue() {
        $('#reportIssueModal').modal('show');
    }

    // Request maintenance
    function requestMaintenance() {
        $('#reportIssueModal').modal('show');
        // Pre-select maintenance type
        $('select[name="issue_type"]').val('plumbing');
    }

    // Invite roommate
    function inviteRoommate() {
        $('#inviteRoommateModal').modal('show');
    }

    // Submit issue report
    function submitIssue() {
        const form = document.getElementById('reportIssueForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        showLoading();
        
        setTimeout(() => {
            hideLoading();
            $('#reportIssueModal').modal('hide');
            form.reset();
            
            Swal.fire({
                icon: 'success',
                title: 'Report Submitted!',
                text: 'Your maintenance request has been submitted successfully.',
                timer: 2000,
                showConfirmButton: false
            });
        }, 1500);
    }

    // Send invitation
    function sendInvitation() {
        const form = document.getElementById('inviteRoommateForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        showLoading();
        
        setTimeout(() => {
            hideLoading();
            $('#inviteRoommateModal').modal('hide');
            form.reset();
            
            Swal.fire({
                icon: 'success',
                title: 'Invitation Sent!',
                text: 'Your roommate invitation has been sent.',
                timer: 2000,
                showConfirmButton: false
            });
        }, 1500);
    }

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // QR Code download
    function downloadQR() {
        const canvas = document.querySelector('canvas');
        const link = document.createElement('a');
        link.download = 'room-204-qr.png';
        link.href = canvas.toDataURL();
        link.click();
    }
</script>
@endsection