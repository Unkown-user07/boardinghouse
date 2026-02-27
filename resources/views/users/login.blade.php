<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - StayEase Boarding House</title>
    
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
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        /* Animated Background */
        .bg-bubbles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }
        
        .bg-bubbles li {
            position: absolute;
            list-style: none;
            display: block;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.15);
            bottom: -160px;
            animation: square 25s infinite;
            transition-timing-function: linear;
            border-radius: 50%;
        }
        
        .bg-bubbles li:nth-child(1) {
            left: 10%;
            width: 80px;
            height: 80px;
            animation-delay: 0s;
        }
        
        .bg-bubbles li:nth-child(2) {
            left: 20%;
            width: 60px;
            height: 60px;
            animation-delay: 2s;
            animation-duration: 17s;
        }
        
        .bg-bubbles li:nth-child(3) {
            left: 25%;
            width: 100px;
            height: 100px;
            animation-delay: 4s;
        }
        
        .bg-bubbles li:nth-child(4) {
            left: 40%;
            width: 50px;
            height: 50px;
            animation-delay: 0s;
            animation-duration: 22s;
        }
        
        .bg-bubbles li:nth-child(5) {
            left: 70%;
            width: 70px;
            height: 70px;
            animation-delay: 0s;
        }
        
        .bg-bubbles li:nth-child(6) {
            left: 80%;
            width: 40px;
            height: 40px;
            animation-delay: 3s;
            animation-duration: 18s;
        }
        
        @keyframes square {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(-1000px) rotate(600deg);
                opacity: 0;
            }
        }
        
        /* Login Container */
        .login-container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 450px;
            margin: 20px;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 30px;
            padding: 3rem 2.5rem;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.3);
            animation: fadeInUp 0.8s ease;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Logo */
        .logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .logo-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .logo-icon i {
            font-size: 2rem;
            color: white;
        }
        
        .logo h2 {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.3rem;
        }
        
        .logo p {
            color: #6c757d;
            font-size: 0.95rem;
        }
        
        /* Form */
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 0.5rem;
            display: block;
            font-size: 0.95rem;
        }
        
        .input-group {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #667eea;
            font-size: 1.2rem;
            z-index: 10;
        }
        
        .form-control {
            width: 100%;
            padding: 0.9rem 1rem 0.9rem 3rem;
            border: 2px solid #e9ecef;
            border-radius: 15px;
            font-size: 1rem;
            transition: all 0.3s;
            background: white;
        }
        
        .form-control:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }
        
        .form-control.is-invalid {
            border-color: #ef476f;
        }
        
        .invalid-feedback {
            color: #ef476f;
            font-size: 0.85rem;
            margin-top: 0.3rem;
            padding-left: 0.5rem;
        }
        
        /* Password Toggle */
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            font-size: 1.2rem;
            z-index: 10;
        }
        
        .password-toggle:hover {
            color: #667eea;
        }
        
        /* Remember Me & Forgot Password */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }
        
        .remember-me input {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #667eea;
        }
        
        .remember-me span {
            color: #6c757d;
            font-size: 0.95rem;
        }
        
        .forgot-link {
            color: #667eea;
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .forgot-link:hover {
            color: #764ba2;
            text-decoration: underline;
        }
        
        /* Login Button */
        .btn-login {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 15px;
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .btn-login.loading {
            opacity: 0.7;
            cursor: not-allowed;
        }
        
        .btn-login.loading .btn-text {
            display: none;
        }
        
        .btn-login.loading .spinner {
            display: inline-block;
        }
        
        .spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Social Login */
        .social-login {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        .social-login p {
            color: #6c757d;
            position: relative;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }
        
        .social-login p::before,
        .social-login p::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 30%;
            height: 1px;
            background: #e9ecef;
        }
        
        .social-login p::before {
            left: 0;
        }
        
        .social-login p::after {
            right: 0;
        }
        
        .social-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }
        
        .social-btn {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            transition: all 0.3s;
            cursor: pointer;
            border: none;
        }
        
        .social-btn:hover {
            transform: translateY(-3px);
        }
        
        .social-btn.google {
            background: #DB4437;
        }
        
        .social-btn.facebook {
            background: #4267B2;
        }
        
        .social-btn.twitter {
            background: #1DA1F2;
        }
        
        /* Sign Up Link */
        .signup-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e9ecef;
        }
        
        .signup-link p {
            color: #6c757d;
            margin-bottom: 0;
            font-size: 0.95rem;
        }
        
        .signup-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }
        
        .signup-link a:hover {
            color: #764ba2;
            text-decoration: underline;
        }
        
        /* Alert Messages */
        .alert {
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            animation: slideIn 0.3s ease;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert i {
            font-size: 1.2rem;
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
        
        /* Back to Home */
        .back-home {
            text-align: center;
            margin-top: 1rem;
        }
        
        .back-home a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-size: 0.95rem;
            transition: color 0.3s;
        }
        
        .back-home a:hover {
            color: white;
        }
        
        .back-home i {
            margin-right: 0.3rem;
        }
        
        /* Responsive */
        @media (max-width: 480px) {
            .login-card {
                padding: 2rem 1.5rem;
            }
            
            .logo h2 {
                font-size: 1.8rem;
            }
            
            .form-options {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .social-buttons {
                gap: 0.5rem;
            }
            
            .social-btn {
                width: 40px;
                height: 40px;
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Animated Background Bubbles -->
    <ul class="bg-bubbles">
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
    </ul>

    <!-- Login Container -->
    <div class="login-container">
        <div class="login-card">
            <!-- Logo -->
            <div class="logo">
                <div class="logo-icon">
                    <i class="bi bi-house-door-fill"></i>
                </div>
                <h2>Welcome Back</h2>
                <p>Sign in to continue to StayEase</p>
            </div>
            
            <!-- Alert Messages -->
            @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
            @endif
            
            @if(session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill"></i>
                {{ session('error') }}
            </div>
            @endif
            
            @if($errors->any())
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill"></i>
                {{ $errors->first() }}
            </div>
            @endif
            
            <!-- Login Form -->
            <form method="POST" action="#" id="loginForm">
                @csrf
                
                <!-- Email Field -->
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-icon">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               name="email" 
                               value="{{ old('email') }}" 
                               placeholder="Enter your email"
                               required 
                               autofocus>
                    </div>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Password Field -->
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-icon">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               name="password" 
                               id="password"
                               placeholder="Enter your password"
                               required>
                        <span class="password-toggle" onclick="togglePassword()">
                            <i class="bi bi-eye-slash" id="toggleIcon"></i>
                        </span>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Remember Me & Forgot Password -->
                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span>Remember me</span>
                    </label>
                    
                    @if (Route::has('password.request'))
                    <a href="#" class="forgot-link">
                        Forgot Password?
                    </a>
                    @endif
                </div>
                
                <!-- Login Button -->
                <button type="submit" class="btn-login" id="loginButton">
                    <span class="btn-text">Sign In</span>
                    <span class="spinner"></span>
                </button>
            </form>
            
            <!-- Social Login -->
            <div class="social-login">
                <p>Or continue with</p>
                <div class="social-buttons">
                    <button class="social-btn google" onclick="socialLogin('google')">
                        <i class="bi bi-google"></i>
                    </button>
                    <button class="social-btn facebook" onclick="socialLogin('facebook')">
                        <i class="bi bi-facebook"></i>
                    </button>
                    <button class="social-btn twitter" onclick="socialLogin('twitter')">
                        <i class="bi bi-twitter"></i>
                    </button>
                </div>
            </div>
            
            <!-- Sign Up Link -->
            <div class="signup-link">
                <p>Don't have an account? <a href="{{ route('register') }}">Sign up here</a></p>
            </div>
        </div>
        
        <!-- Back to Home -->
        <div class="back-home">
            <a href="{{ route('landingpage') }}">
                <i class="bi bi-arrow-left"></i> Back to Home
            </a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle Password Visibility
        function togglePassword() {
            const password = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (password.type === 'password') {
                password.type = 'text';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            } else {
                password.type = 'password';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            }
        }

        
        
        // Form Loading State
            document.getElementById('loginForm')?.addEventListener('submit', function(e) {
            e.preventDefault(); // prevent real form submission

            const button = document.getElementById('loginButton');
            button.classList.add('loading');

            // Simulate login loading effect
            setTimeout(function() {
                window.location.href = "/dashboard"; // change to your dashboard URL
            }, 1000);
        });


        // Email validation on blur
        document.querySelector('input[name="email"]')?.addEventListener('blur', function(e) {
            const email = e.target.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email && !emailRegex.test(email)) {
                e.target.classList.add('is-invalid');
                let feedback = e.target.parentElement.parentElement.querySelector('.invalid-feedback');
                if (!feedback) {
                    feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    feedback.textContent = 'Please enter a valid email address';
                    e.target.parentElement.parentElement.appendChild(feedback);
                }
            } else {
                e.target.classList.remove('is-invalid');
            }
        });
        
        // Password validation
        document.querySelector('input[name="password"]')?.addEventListener('input', function(e) {
            const password = e.target.value;
            if (password && password.length < 8) {
                e.target.classList.add('is-invalid');
                let feedback = e.target.parentElement.parentElement.querySelector('.invalid-feedback');
                if (!feedback) {
                    feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    feedback.textContent = 'Password must be at least 8 characters';
                    e.target.parentElement.parentElement.appendChild(feedback);
                }
            } else {
                e.target.classList.remove('is-invalid');
            }
        });
        
        // Remember me hover effect
        const rememberMe = document.querySelector('.remember-me');
        if (rememberMe) {
            rememberMe.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.02)';
            });
            rememberMe.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        }
        
        // Input focus effects
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
        
        // Prevent form resubmission on page refresh
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
        
        // Add keyboard shortcut for demo (Ctrl+Shift+D to fill demo credentials)
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.shiftKey && e.key === 'D') {
                e.preventDefault();
                document.querySelector('input[name="email"]').value = 'john.doe@example.com';
                document.querySelector('input[name="password"]').value = 'password123';
                document.querySelector('.remember-me input').checked = true;
                
                // Show demo notification
                const notification = document.createElement('div');
                notification.className = 'alert alert-success';
                notification.style.position = 'fixed';
                notification.style.top = '20px';
                notification.style.right = '20px';
                notification.style.zIndex = '9999';
                notification.innerHTML = '<i class="bi bi-info-circle-fill me-2"></i>Demo credentials filled';
                document.body.appendChild(notification);
                
                setTimeout(() => notification.remove(), 3000);
            }
        });
    </script>
</body>
</html>