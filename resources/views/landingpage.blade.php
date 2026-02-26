<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StayEase - Your Comfortable Boarding House</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            color: #333;
            overflow-x: hidden;
        }
        
        /* Navbar */
        .navbar {
            background: transparent;
            padding: 1.5rem 0;
            transition: all 0.3s ease;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }
        
        .navbar.scrolled {
            background: white;
            padding: 1rem 0;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .navbar.scrolled .navbar-brand,
        .navbar.scrolled .nav-link {
            color: #333 !important;
        }
        
        .navbar.scrolled .btn-outline-light {
            border-color: #4361ee;
            color: #4361ee;
        }
        
        .navbar.scrolled .btn-outline-light:hover {
            background: #4361ee;
            color: white;
        }
        
        .navbar-brand {
            font-weight: 800;
            font-size: 1.8rem;
            color: white !important;
            letter-spacing: -0.5px;
        }
        
        .navbar-brand i {
            color: #ffd166;
            margin-right: 0.5rem;
        }
        
        .nav-link {
            color: white !important;
            font-weight: 500;
            margin: 0 1rem;
            position: relative;
            padding: 0.5rem 0 !important;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: #ffd166;
            transition: width 0.3s ease;
        }
        
        .nav-link:hover::after {
            width: 100%;
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #4361ee 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
            background-size: cover;
            background-position: bottom;
            opacity: 0.1;
        }
        
        .hero-content {
            color: white;
            position: relative;
            z-index: 2;
        }
        
        .hero h1 {
            font-size: 4rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }
        
        .hero h1 span {
            color: #ffd166;
            display: block;
        }
        
        .hero p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
            max-width: 600px;
        }
        
        .hero-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .btn-hero-primary {
            background: #ffd166;
            color: #333;
            padding: 1rem 2.5rem;
            border-radius: 50px;
            font-weight: 600;
            border: none;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-hero-primary:hover {
            background: #ffc233;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        
        .btn-hero-outline {
            background: transparent;
            color: white;
            padding: 1rem 2.5rem;
            border-radius: 50px;
            font-weight: 600;
            border: 2px solid white;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-hero-outline:hover {
            background: white;
            color: #4361ee;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        
        .hero-image {
            position: relative;
            z-index: 2;
        }
        
        .hero-image img {
            width: 100%;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        
        /* Features Section */
        .features {
            padding: 100px 0;
            background: #f8f9fa;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 60px;
        }
        
        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 1rem;
        }
        
        .section-title p {
            color: #6c757d;
            font-size: 1.1rem;
            max-width: 700px;
            margin: 0 auto;
        }
        
        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: all 0.3s;
            height: 100%;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #4361ee 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 2rem;
        }
        
        .feature-card h3 {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .feature-card p {
            color: #6c757d;
            margin-bottom: 0;
        }
        
        /* Rooms Section */
        .rooms {
            padding: 100px 0;
        }
        
        .room-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: all 0.3s;
            height: 100%;
        }
        
        .room-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .room-image {
            position: relative;
            height: 250px;
            overflow: hidden;
        }
        
        .room-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }
        
        .room-card:hover .room-image img {
            transform: scale(1.1);
        }
        
        .room-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: #ffd166;
            color: #333;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .room-details {
            padding: 1.5rem;
        }
        
        .room-details h3 {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .room-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: #4361ee;
            margin-bottom: 1rem;
        }
        
        .room-price span {
            font-size: 0.9rem;
            color: #6c757d;
            font-weight: 400;
        }
        
        .room-amenities {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .room-amenities i {
            color: #4361ee;
            margin-right: 0.3rem;
        }
        
        .btn-room {
            width: 100%;
            padding: 0.8rem;
            background: #4361ee;
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        
        .btn-room:hover {
            background: #3046c0;
        }
        
        /* Amenities Section */
        .amenities {
            padding: 100px 0;
            background: #f8f9fa;
        }
        
        .amenities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }
        
        .amenity-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: all 0.3s;
        }
        
        .amenity-item:hover {
            transform: translateX(10px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .amenity-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #4361ee 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }
        
        .amenity-text h4 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.2rem;
        }
        
        .amenity-text p {
            color: #6c757d;
            margin-bottom: 0;
            font-size: 0.9rem;
        }
        
        /* Testimonials */
        .testimonials {
            padding: 100px 0;
        }
        
        .testimonial-card {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            margin: 1rem;
            position: relative;
        }
        
        .testimonial-card::before {
            content: '"';
            position: absolute;
            top: -20px;
            left: 20px;
            font-size: 5rem;
            color: #4361ee;
            opacity: 0.2;
            font-family: serif;
        }
        
        .testimonial-text {
            font-size: 1rem;
            line-height: 1.8;
            color: #6c757d;
            margin-bottom: 1.5rem;
            font-style: italic;
        }
        
        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .testimonial-author img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .author-info h5 {
            font-weight: 600;
            margin-bottom: 0.2rem;
        }
        
        .author-info p {
            color: #6c757d;
            margin-bottom: 0;
            font-size: 0.9rem;
        }
        
        /* CTA Section */
        .cta {
            background: linear-gradient(135deg, #4361ee 0%, #764ba2 100%);
            padding: 80px 0;
            position: relative;
            overflow: hidden;
        }
        
        .cta::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
            background-size: cover;
            background-position: bottom;
            opacity: 0.1;
        }
        
        .cta-content {
            text-align: center;
            color: white;
            position: relative;
            z-index: 2;
        }
        
        .cta h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .cta p {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 2rem;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .btn-cta {
            background: #ffd166;
            color: #333;
            padding: 1rem 3rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            border: none;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-cta:hover {
            background: #ffc233;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        
        /* Footer */
        .footer {
            background: #1a1a2e;
            color: white;
            padding: 60px 0 30px;
        }
        
        .footer h5 {
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: #ffd166;
        }
        
        .footer p {
            color: #a0a0b0;
            line-height: 1.8;
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
        }
        
        .footer-links li {
            margin-bottom: 0.8rem;
        }
        
        .footer-links a {
            color: #a0a0b0;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-links a:hover {
            color: #ffd166;
        }
        
        .social-links {
            display: flex;
            gap: 1rem;
        }
        
        .social-links a {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .social-links a:hover {
            background: #ffd166;
            color: #333;
            transform: translateY(-3px);
        }
        
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-top: 3rem;
            padding-top: 2rem;
            text-align: center;
            color: #a0a0b0;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .hero-content {
                text-align: center;
                margin-bottom: 3rem;
            }
            
            .hero-buttons {
                justify-content: center;
            }
            
            .section-title h2 {
                font-size: 2rem;
            }
            
            .navbar {
                background: white;
            }
            
            .navbar-brand {
                color: #333 !important;
            }
            
            .nav-link {
                color: #333 !important;
            }
            
            .btn-outline-light {
                border-color: #4361ee;
                color: #4361ee;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-house-door-fill"></i>
                StayEase
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#rooms">Rooms</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#amenities">Amenities</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#testimonials">Testimonials</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                </ul>
                
                <div class="d-flex gap-2">
                    <a href="/login" class="btn btn-outline-light">Login</a>
                    <a href="/register" class="btn btn-hero-primary">Sign Up</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content">
                    <h1>
                        Find Your Perfect
                        <span>Home Away From Home</span>
                    </h1>
                    <p>Comfortable, affordable, and secure boarding houses for students and professionals. Your ideal living space awaits.</p>
                    <div class="hero-buttons">
                        <a href="#rooms" class="btn-hero-primary">View Rooms</a>
                        <a href="#contact" class="btn-hero-outline">Contact Us</a>
                    </div>
                </div>
                <div class="col-lg-6 hero-image">
                    <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Modern Room">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <div class="section-title">
                <h2>Why Choose StayEase?</h2>
                <p>We provide the best living experience with premium amenities and excellent service</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h3>Secure & Safe</h3>
                        <p>24/7 security with CCTV surveillance and secure access</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-wifi"></i>
                        </div>
                        <h3>High-Speed WiFi</h3>
                        <p>Fast and reliable internet connection throughout the building</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-cup-hot"></i>
                        </div>
                        <h3>Common Areas</h3>
                        <p>Spacious lounges and study areas for residents</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-water"></i>
                        </div>
                        <h3>Clean Water</h3>
                        <p>24/7 water supply with backup storage</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Rooms Section -->
    <section id="rooms" class="rooms">
        <div class="container">
            <div class="section-title">
                <h2>Our Rooms</h2>
                <p>Choose from our selection of comfortable and well-furnished rooms</p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="room-card">
                        <div class="room-image">
                            <img src="https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Standard Room">
                            <span class="room-badge">Popular</span>
                        </div>
                        <div class="room-details">
                            <h3>Standard Room</h3>
                            <div class="room-price">₱3,500 <span>/ month</span></div>
                            <div class="room-amenities">
                                <span><i class="bi bi-person"></i> 1-2 persons</span>
                                <span><i class="bi bi-rulers"></i> 18 sqm</span>
                            </div>
                            <p class="text-secondary mb-3">Perfect for students, with basic furniture and shared bathroom</p>
                            <a href="/register" class="btn-room">Book Now</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="room-card">
                        <div class="room-image">
                            <img src="https://images.unsplash.com/photo-1560448204-603b3fc33ddc?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Deluxe Room">
                            <span class="room-badge" style="background: #4361ee; color: white;">Best Value</span>
                        </div>
                        <div class="room-details">
                            <h3>Deluxe Room</h3>
                            <div class="room-price">₱4,500 <span>/ month</span></div>
                            <div class="room-amenities">
                                <span><i class="bi bi-person"></i> 2-3 persons</span>
                                <span><i class="bi bi-rulers"></i> 25 sqm</span>
                            </div>
                            <p class="text-secondary mb-3">Spacious room with private bathroom and cabinet</p>
                            <a href="/register" class="btn-room">Book Now</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="room-card">
                        <div class="room-image">
                            <img src="https://images.unsplash.com/photo-1554995207-c18c203602cb?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Premium Room">
                        </div>
                        <div class="room-details">
                            <h3>Premium Room</h3>
                            <div class="room-price">₱6,000 <span>/ month</span></div>
                            <div class="room-amenities">
                                <span><i class="bi bi-person"></i> 2-4 persons</span>
                                <span><i class="bi bi-rulers"></i> 32 sqm</span>
                            </div>
                            <p class="text-secondary mb-3">Fully furnished with aircon, private CR, and balcony</p>
                            <a href="/register" class="btn-room">Book Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Amenities Section -->
    <section id="amenities" class="amenities">
        <div class="container">
            <div class="section-title">
                <h2>Amenities & Facilities</h2>
                <p>Everything you need for a comfortable stay</p>
            </div>
            
            <div class="amenities-grid">
                <div class="amenity-item">
                    <div class="amenity-icon">
                        <i class="bi bi-wifi"></i>
                    </div>
                    <div class="amenity-text">
                        <h4>Free WiFi</h4>
                        <p>High-speed internet in all areas</p>
                    </div>
                </div>
                
                <div class="amenity-item">
                    <div class="amenity-icon">
                        <i class="bi bi-camera-video"></i>
                    </div>
                    <div class="amenity-text">
                        <h4>CCTV Security</h4>
                        <p>24/7 surveillance for safety</p>
                    </div>
                </div>
                
                <div class="amenity-item">
                    <div class="amenity-icon">
                        <i class="bi bi-droplet"></i>
                    </div>
                    <div class="amenity-text">
                        <h4>Water Station</h4>
                        <p>Free drinking water refills</p>
                    </div>
                </div>
                
                <div class="amenity-item">
                    <div class="amenity-icon">
                        <i class="bi bi-tshirt"></i>
                    </div>
                    <div class="amenity-text">
                        <h4>Laundry Area</h4>
                        <p>Washing machines available</p>
                    </div>
                </div>
                
                <div class="amenity-item">
                    <div class="amenity-icon">
                        <i class="bi bi-book"></i>
                    </div>
                    <div class="amenity-text">
                        <h4>Study Area</h4>
                        <p>Quiet space for studying</p>
                    </div>
                </div>
                
                <div class="amenity-item">
                    <div class="amenity-icon">
                        <i class="bi bi-cup"></i>
                    </div>
                    <div class="amenity-text">
                        <h4>Kitchen Area</h4>
                        <p>Shared kitchen with appliances</p>
                    </div>
                </div>
                
                <div class="amenity-item">
                    <div class="amenity-icon">
                        <i class="bi bi-car-front"></i>
                    </div>
                    <div class="amenity-text">
                        <h4>Parking Space</h4>
                        <p>For motorcycles and cars</p>
                    </div>
                </div>
                
                <div class="amenity-item">
                    <div class="amenity-icon">
                        <i class="bi bi-shield"></i>
                    </div>
                    <div class="amenity-text">
                        <h4>Fire Safety</h4>
                        <p>Complete fire protection system</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="testimonials">
        <div class="container">
            <div class="section-title">
                <h2>What Our Residents Say</h2>
                <p>Hear from our happy residents about their experience</p>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <p class="testimonial-text">"StayEase has been my home for 2 years now. The rooms are clean, the staff is friendly, and the rent is affordable. Highly recommended!"</p>
                        <div class="testimonial-author">
                            <img src="https://ui-avatars.com/api/?name=Maria+Santos&background=4361ee&color=fff" alt="Maria Santos">
                            <div class="author-info">
                                <h5>Maria Santos</h5>
                                <p>Student, 2 years</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <p class="testimonial-text">"The location is perfect - near the university and commercial areas. The WiFi is fast and never disconnects. Great place for students!"</p>
                        <div class="testimonial-author">
                            <img src="https://ui-avatars.com/api/?name=John+Reyes&background=764ba2&color=fff" alt="John Reyes">
                            <div class="author-info">
                                <h5>John Reyes</h5>
                                <p>Working Professional, 1 year</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <p class="testimonial-text">"I love the community here. The common areas are great for meeting people. The management is responsive to maintenance requests."</p>
                        <div class="testimonial-author">
                            <img src="https://ui-avatars.com/api/?name=Anna+Cruz&background=06d6a0&color=fff" alt="Anna Cruz">
                            <div class="author-info">
                                <h5>Anna Cruz</h5>
                                <p>Graduate Student, 6 months</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <div class="cta-content">
                <h2>Ready to Experience Comfortable Living?</h2>
                <p>Join our community of students and professionals. Book your room today!</p>
                <a href="/register" class="btn-cta">Get Started Now</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h5>StayEase</h5>
                    <p>Your trusted boarding house partner. We provide comfortable, affordable, and secure living spaces for students and professionals.</p>
                    <div class="social-links">
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-twitter"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="footer-links">
                        <li><a href="#home">Home</a></li>
                        <li><a href="#rooms">Rooms</a></li>
                        <li><a href="#amenities">Amenities</a></li>
                        <li><a href="#testimonials">Testimonials</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-4">
                    <h5>Contact Info</h5>
                    <ul class="footer-links">
                        <li><i class="bi bi-geo-alt me-2"></i> 123 Mabini St., Makati City</li>
                        <li><i class="bi bi-telephone me-2"></i> (02) 1234 5678</li>
                        <li><i class="bi bi-envelope me-2"></i> info@stayease.com</li>
                        <li><i class="bi bi-clock me-2"></i> Office: 8AM - 8PM daily</li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-4">
                    <h5>Newsletter</h5>
                    <p>Subscribe for updates and promotions</p>
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Your email">
                        <button class="btn btn-primary" type="button">Subscribe</button>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2026 StayEase. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>