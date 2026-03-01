<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'StayEase - Boarding House')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    
    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
    <!-- Custom User Styles -->
    @yield('styles')
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #f5f7fb;
            min-height: 100vh;
        }
        
        /* User Wrapper */
        .user-wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .sidebar {
            width: 280px;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.02);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
            transition: all 0.3s;
            z-index: 1000;
            border-right: 1px solid rgba(0,0,0,0.05);
        }
        
        .sidebar::-webkit-scrollbar {
            width: 5px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 5px;
        }
        
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        
        .sidebar.collapsed {
            width: 80px;
        }
        
        .sidebar.collapsed .sidebar-logo span,
        .sidebar.collapsed .nav-link span,
        .sidebar.collapsed .nav-section-title {
            display: none;
        }
        
        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 0.8rem;
        }
        
        .sidebar.collapsed .nav-link i {
            margin: 0;
            font-size: 1.3rem;
        }
        
        .sidebar.collapsed .sidebar-logo {
            justify-content: center;
            padding: 1rem 0;
        }
        
        .sidebar.collapsed .sidebar-logo img {
            width: 40px;
        }
        
        .sidebar.collapsed .user-info-sidebar {
            display: none;
        }
        
        /* Sidebar Logo */
        .sidebar-logo {
            padding: 1.5rem 1.5rem;
            border-bottom: 1px solid #edf2f7;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .sidebar-logo img {
            width: 35px;
            height: 35px;
        }
        
        .sidebar-logo span {
            font-size: 1.25rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        /* User Info Sidebar */
        .user-info-sidebar {
            padding: 1.5rem;
            border-bottom: 1px solid #edf2f7;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .user-avatar-sidebar {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.2rem;
        }
        
        .user-details-sidebar h6 {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.2rem;
        }
        
        .user-details-sidebar p {
            font-size: 0.8rem;
            color: #718096;
            margin-bottom: 0.25rem;
        }
        
        .room-badge {
            background: #e6f7e6;
            color: #27ae60;
            padding: 0.2rem 0.5rem;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 600;
            display: inline-block;
        }
        
        /* Sidebar Nav */
        .sidebar-nav {
            padding: 1.5rem 0;
        }
        
        .nav-section {
            margin-bottom: 1.5rem;
        }
        
        .nav-section-title {
            padding: 0 1.5rem;
            margin-bottom: 0.75rem;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #a0aec0;
        }
        
        .nav-item {
            list-style: none;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.7rem 1.5rem;
            color: #4a5568;
            text-decoration: none;
            transition: all 0.3s;
            position: relative;
            font-weight: 500;
            font-size: 0.95rem;
        }
        
        .nav-link i {
            font-size: 1.2rem;
            color: #a0aec0;
            transition: all 0.3s;
            width: 24px;
        }
        
        .nav-link:hover {
            background: #f7f9fc;
            color: #667eea;
        }
        
        .nav-link:hover i {
            color: #667eea;
        }
        
        .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .nav-link.active i {
            color: white;
        }
        
        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: white;
        }
        
        .nav-link .badge {
            margin-left: auto;
            font-size: 0.65rem;
            padding: 0.25rem 0.5rem;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 280px;
            transition: all 0.3s;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .main-content.expanded {
            margin-left: 80px;
        }
        
        /* Top Navbar */
        .top-navbar {
            background: white;
            padding: 0.8rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
            position: sticky;
            top: 0;
            z-index: 999;
        }
        
        .navbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .sidebar-toggle {
            background: none;
            border: none;
            font-size: 1.3rem;
            color: #4a5568;
            cursor: pointer;
            padding: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .sidebar-toggle:hover {
            background: #f7f9fc;
            color: #667eea;
        }
        
        .page-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2d3748;
            margin: 0;
        }
        
        .breadcrumb {
            margin: 0;
            padding: 0;
            background: none;
            font-size: 0.85rem;
        }
        
        .breadcrumb-item a {
            color: #718096;
            text-decoration: none;
        }
        
        .breadcrumb-item a:hover {
            color: #667eea;
        }
        
        .breadcrumb-item.active {
            color: #2d3748;
            font-weight: 500;
        }
        
        .navbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        /* Search Bar */
        .search-box {
            position: relative;
        }
        
        .search-box i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 0.9rem;
        }
        
        .search-box input {
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            border: 1px solid #edf2f7;
            border-radius: 30px;
            font-size: 0.9rem;
            width: 250px;
            transition: all 0.3s;
        }
        
        .search-box input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            width: 300px;
        }
        
        /* Notifications */
        .notifications {
            position: relative;
        }
        
        .notifications .btn {
            padding: 0.5rem;
            font-size: 1.2rem;
            color: #4a5568;
            border-radius: 8px;
        }
        
        .notifications .btn:hover {
            background: #f7f9fc;
            color: #667eea;
        }
        
        .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            background: #ef476f;
            color: white;
            font-size: 0.6rem;
            padding: 0.15rem 0.4rem;
            border-radius: 30px;
            font-weight: 600;
        }
        
        .notification-dropdown {
            width: 320px;
            padding: 0;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-radius: 15px;
        }
        
        .notification-header {
            padding: 1rem;
            border-bottom: 1px solid #edf2f7;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .notification-header h6 {
            margin: 0;
            font-weight: 600;
            color: #2d3748;
        }
        
        .notification-item {
            padding: 0.8rem 1rem;
            display: flex;
            gap: 0.75rem;
            border-bottom: 1px solid #edf2f7;
            transition: all 0.3s;
        }
        
        .notification-item:hover {
            background: #f7f9fc;
        }
        
        .notification-icon {
            width: 35px;
            height: 35px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }
        
        .notification-content {
            flex: 1;
        }
        
        .notification-title {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.2rem;
            font-size: 0.9rem;
        }
        
        .notification-time {
            font-size: 0.7rem;
            color: #a0aec0;
        }
        
        .notification-footer {
            padding: 0.8rem;
            text-align: center;
            border-top: 1px solid #edf2f7;
        }
        
        .notification-footer a {
            color: #667eea;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        /* User Menu */
        .user-menu .btn {
            padding: 0.3rem 0.5rem 0.3rem 1rem;
            border: 1px solid #edf2f7;
            border-radius: 30px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .user-menu .btn:hover {
            background: #f7f9fc;
        }
        
        .user-avatar {
            width: 35px;
            height: 35px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
        
        .user-info {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            line-height: 1.2;
        }
        
        .user-name {
            font-weight: 600;
            color: #2d3748;
            font-size: 0.9rem;
        }
        
        .user-role {
            font-size: 0.7rem;
            color: #a0aec0;
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-radius: 12px;
            padding: 0.5rem;
        }
        
        .dropdown-item {
            padding: 0.6rem 1rem;
            border-radius: 8px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .dropdown-item i {
            font-size: 1rem;
            width: 20px;
            color: #718096;
        }
        
        .dropdown-item:hover {
            background: #f7f9fc;
            color: #667eea;
        }
        
        .dropdown-item:hover i {
            color: #667eea;
        }
        
        .dropdown-divider {
            margin: 0.3rem 0;
        }
        
        /* Content Wrapper */
        .content-wrapper {
            padding: 2rem;
            flex: 1;
        }
        
        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .page-header-left h1 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 0.25rem;
        }
        
        .page-header-left p {
            color: #718096;
            margin: 0;
            font-size: 0.95rem;
        }
        
        .page-header-right {
            display: flex;
            gap: 0.5rem;
        }
        
        /* Footer */
        .footer {
            background: white;
            padding: 1rem 2rem;
            border-top: 1px solid #edf2f7;
            font-size: 0.85rem;
            color: #718096;
            margin-top: auto;
        }
        
        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        
        .loading-overlay.show {
            display: flex;
        }
        
        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Alerts */
        .alert {
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            animation: slideIn 0.3s ease;
            border: none;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
        }
        
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Cards */
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.02);
            transition: all 0.3s;
            border: 1px solid rgba(0,0,0,0.03);
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.1);
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid #edf2f7;
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            border-radius: 20px 20px 0 0 !important;
        }
        
        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }
        
        .btn-outline-primary {
            border: 2px solid #667eea;
            color: #667eea;
            padding: 0.5rem 1.2rem;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-outline-primary:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: transparent;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                left: -280px;
            }
            
            .sidebar.show {
                left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .main-content.expanded {
                margin-left: 0;
            }
            
            .search-box input {
                width: 200px;
            }
            
            .search-box input:focus {
                width: 250px;
            }
        }
        
        @media (max-width: 768px) {
            .top-navbar {
                padding: 0.8rem 1rem;
            }
            
            .content-wrapper {
                padding: 1.5rem;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .page-header-right {
                width: 100%;
            }
            
            .search-box {
                display: none;
            }
            
            .user-info {
                display: none;
            }
            
            .page-header-left h1 {
                font-size: 1.5rem;
            }
        }
        
        @media (max-width: 576px) {
            .content-wrapper {
                padding: 1rem;
            }
            
            .notification-dropdown {
                width: 280px;
            }
        }
        
        /* Utility Classes */
        .cursor-pointer {
            cursor: pointer;
        }
        
        .text-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .min-width-0 {
            min-width: 0;
        }
    </style>
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <!-- User Wrapper -->
    <div class="user-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-logo">
                <img src="/images/logo.png" alt="StayEase">
                <span>StayEase</span>
            </div>
            
            <!-- User Info in Sidebar -->
            <div class="user-info-sidebar">
                <div class="user-avatar-sidebar">
                    JD
                </div>
                <div class="user-details-sidebar">
                    <h6>John Doe</h6>
                    <p>Room 204</p>
                    <span class="room-badge">
                        <i class="bi bi-door-open me-1"></i>2nd Floor
                    </span>
                </div>
            </div>
            
            <div class="sidebar-nav">
                <!-- Main Menu -->
                <div class="nav-section">
                    <div class="nav-section-title">Main</div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="{{ route('user.dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="bi bi-speedometer2"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.profile') }}" class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}">
                                <i class="bi bi-person"></i>
                                <span>My Profile</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.payments') }}" class="nav-link {{ request()->routeIs('payments*') ? 'active' : '' }}">
                                <i class="bi bi-credit-card"></i>
                                <span>Payments</span>
                                <span class="badge bg-warning">Due in 5d</span>
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Boarding House -->
                <div class="nav-section">
                    <div class="nav-section-title">Boarding House</div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="{{ route('user.myroom') }}" class="nav-link {{ request()->routeIs('room') ? 'active' : '' }}">
                                <i class="bi bi-door-open"></i>
                                <span>My Room</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link {{ request()->routeIs('roommates') ? 'active' : '' }}">
                                <i class="bi bi-people"></i>
                                <span>Roommates</span>
                                <span class="badge bg-success">3</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link {{ request()->routeIs('amenities') ? 'active' : '' }}">
                                <i class="bi bi-grid"></i>
                                <span>Amenities</span>
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Services -->
                <div class="nav-section">
                    <div class="nav-section-title">Services</div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="#" class="nav-link {{ request()->routeIs('maintenance') ? 'active' : '' }}">
                                <i class="bi bi-tools"></i>
                                <span>Maintenance</span>
                                <span class="badge bg-info">2</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.announcements') }}" class="nav-link {{ request()->routeIs('announcements') ? 'active' : '' }}">
                                <i class="bi bi-megaphone"></i>
                                <span>Announcements</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link {{ request()->routeIs('complaints') ? 'active' : '' }}">
                                <i class="bi bi-chat-dots"></i>
                                <span>Complaints</span>
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Settings -->
                <div class="nav-section">
                    <div class="nav-section-title">Settings</div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="{{ route('user.settings') }}" class="nav-link {{ request()->routeIs('settings') ? 'active' : '' }}">
                                <i class="bi bi-gear"></i>
                                <span>Settings</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link {{ request()->routeIs('help') ? 'active' : '' }}">
                                <i class="bi bi-question-circle"></i>
                                <span>Help & Support</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content" id="mainContent">
            <!-- Top Navbar -->
            <nav class="top-navbar">
                <div class="navbar-left">
                    <button class="sidebar-toggle" id="sidebarToggle">
                        <i class="bi bi-list"></i>
                    </button>
                    
                    <div>
                        <h5 class="page-title">@yield('page_header', 'Dashboard')</h5>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
                                @yield('breadcrumb')
                            </ol>
                        </nav>
                    </div>
                </div>
                
                <div class="navbar-right">
                    <!-- Search -->
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" placeholder="Search..." id="globalSearch">
                    </div>
                    
                    <!-- Notifications -->
                    <div class="notifications dropdown">
                        <button class="btn" data-bs-toggle="dropdown">
                            <i class="bi bi-bell"></i>
                            <span class="notification-badge">3</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end notification-dropdown">
                            <div class="notification-header">
                                <h6>Notifications</h6>
                                <a href="#" class="small">Mark all read</a>
                            </div>
                            
                            <div class="notification-item">
                                <div class="notification-icon bg-primary bg-opacity-10">
                                    <i class="bi bi-cash text-primary"></i>
                                </div>
                                <div class="notification-content">
                                    <div class="notification-title">Payment Reminder</div>
                                    <div class="notification-time">Rent due in 5 days</div>
                                </div>
                            </div>
                            
                            <div class="notification-item">
                                <div class="notification-icon bg-warning bg-opacity-10">
                                    <i class="bi bi-tools text-warning"></i>
                                </div>
                                <div class="notification-content">
                                    <div class="notification-title">Maintenance Update</div>
                                    <div class="notification-time">Your request is in progress</div>
                                </div>
                            </div>
                            
                            <div class="notification-item">
                                <div class="notification-icon bg-info bg-opacity-10">
                                    <i class="bi bi-megaphone text-info"></i>
                                </div>
                                <div class="notification-content">
                                    <div class="notification-title">New Announcement</div>
                                    <div class="notification-time">Water interruption tomorrow</div>
                                </div>
                            </div>
                            
                            <div class="notification-footer">
                                <a href="#">View all notifications</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- User Menu -->
                    <div class="user-menu dropdown">
                        <button class="btn" data-bs-toggle="dropdown">
                            <div class="user-avatar">JD</div>
                            <div class="user-info">
                                <span class="user-name">John Doe</span>
                                <span class="user-role">Room 204</span>
                            </div>
                            <i class="bi bi-chevron-down"></i>
                        </button>
                        
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="{{ route('user.profile') }}">
                                <i class="bi bi-person"></i> My Profile
                            </a>
                            <a class="dropdown-item" href="{{ route('user.settings') }}">
                                <i class="bi bi-gear"></i> Settings
                            </a>
                            <a class="dropdown-item" href="#">
                                <i class="bi bi-question-circle"></i> Help
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="#">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Content Wrapper -->
            <div class="content-wrapper">
                <!-- Page Header -->
                @hasSection('page_header')
                <div class="page-header">
                    <div class="page-header-left">
                        <h1>@yield('page_header')</h1>
                        @hasSection('page_description')
                        <p>@yield('page_description')</p>
                        @endif
                    </div>
                    <div class="page-header-right">
                        @yield('header_actions')
                    </div>
                </div>
                @endif
                
                <!-- Alert Messages -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                
                <!-- Main Content -->
                @yield('content')
            </div>

            <!-- Footer -->
            <footer class="footer">
                <div class="d-flex justify-content-between align-items-center">
                    <span>&copy; {{ date('Y') }} StayEase Boarding House. All rights reserved.</span>
                    <span>Version 1.0.0</span>
                </div>
            </footer>
        </main>
    </div>

    <!-- Scripts -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
    <!-- Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    
    <!-- Custom User JS -->
    <script>
        $(document).ready(function() {
            // Sidebar Toggle
            $('#sidebarToggle').click(function() {
                $('#sidebar').toggleClass('collapsed');
                $('#mainContent').toggleClass('expanded');
                
                // Store preference in localStorage
                const isCollapsed = $('#sidebar').hasClass('collapsed');
                localStorage.setItem('userSidebarCollapsed', isCollapsed);
            });
            
            // Load sidebar state from localStorage
            const sidebarCollapsed = localStorage.getItem('userSidebarCollapsed') === 'true';
            if (sidebarCollapsed) {
                $('#sidebar').addClass('collapsed');
                $('#mainContent').addClass('expanded');
            }
            
            // Mobile sidebar toggle
            if ($(window).width() <= 992) {
                $('#sidebar').removeClass('collapsed');
                $('#mainContent').removeClass('expanded');
            }
            
            // Global search functionality
            $('#globalSearch').on('keyup', function() {
                const searchTerm = $(this).val();
                if (searchTerm.length > 2) {
                    // Implement search logic here
                    console.log('Searching for:', searchTerm);
                }
            });
            
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Auto-hide alerts
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
            
            // AJAX setup for CSRF token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            // Handle window resize
            $(window).resize(function() {
                if ($(window).width() <= 992) {
                    $('#sidebar').addClass('collapsed');
                    $('#mainContent').addClass('expanded');
                } else {
                    const storedState = localStorage.getItem('userSidebarCollapsed') === 'true';
                    if (!storedState) {
                        $('#sidebar').removeClass('collapsed');
                        $('#mainContent').removeClass('expanded');
                    }
                }
            });
        });
        
        // Global loading function
        function showLoading() {
            $('#loadingOverlay').addClass('show');
        }
        
        function hideLoading() {
            $('#loadingOverlay').removeClass('show');
        }
        
        // Toast notification function
        function showToast(type, message, title = '') {
            toastr[type](message, title, {
                closeButton: true,
                progressBar: true,
                positionClass: 'toast-top-right',
                timeOut: 5000
            });
        }
        
        // Confirmation dialog
        function confirmAction(message, callback) {
            if (confirm(message)) {
                callback();
            }
        }
        
        // Format currency
        function formatCurrency(amount) {
            return '₱' + parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        }
        
        // Format date
        function formatDate(date) {
            return moment(date).format('MMM D, YYYY');
        }
        
        // Handle AJAX errors
        $(document).ajaxError(function(event, jqxhr, settings, error) {
            if (jqxhr.status === 419) {
                showToast('error', 'Session expired. Please refresh the page.');
            } else if (jqxhr.status === 403) {
                showToast('error', 'You don\'t have permission to perform this action.');
            } else if (jqxhr.status === 500) {
                showToast('error', 'Server error. Please try again later.');
            } else {
                showToast('error', 'An error occurred. Please try again.');
            }
        });
    </script>
    
    <!-- Page Specific Scripts -->
    @yield('scripts')
</body>
</html>