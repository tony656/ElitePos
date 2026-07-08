<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config("app.name") }} - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy: #0B1E3D;
            --navy-mid: #112952;
            --navy-light: #1A3A6B;
            --amber: #F59E0B;
            --amber-pale: #FEF3C7;
            --emerald: #059669;
            --emerald-pale: #D1FAE5;
            --rose: #E11D48;
            --rose-pale: #FFE4E6;
            --violet: #7C3AED;
            --violet-pale: #EDE9FE;
            --sky: #0284C7;
            --sky-pale: #E0F2FE;
            --slate-50: #F8FAFC;
            --slate-100: #F1F5F9;
            --slate-200: #E2E8F0;
            --slate-300: #CBD5E1;
            --slate-400: #94A3B8;
            --slate-500: #64748B;
            --slate-600: #475569;
            --slate-700: #334155;
            --slate-800: #1E293B;
            --white: #FFFFFF;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--navy);
            position: relative;
            overflow: hidden;
        }

        /* Animated Background */
        .bg-shapes {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.12;
            animation: float 20s ease-in-out infinite;
        }

        .shape-1 {
            width: 600px;
            height: 600px;
            background: var(--amber);
            top: -200px;
            right: -200px;
            animation-delay: 0s;
        }

        .shape-2 {
            width: 500px;
            height: 500px;
            background: var(--sky);
            bottom: -150px;
            left: -150px;
            animation-delay: -7s;
        }

        .shape-3 {
            width: 300px;
            height: 300px;
            background: var(--violet);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation-delay: -14s;
            opacity: 0.06;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(40px, -40px) scale(1.05); }
            50% { transform: translate(-30px, 30px) scale(0.95); }
            75% { transform: translate(30px, 40px) scale(1.02); }
        }

        /* Grid Pattern */
        .grid-overlay {
            position: fixed;
            inset: 0;
            z-index: 1;
            background-image: 
                linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 60px 60px;
            pointer-events: none;
        }

        /* Main Container */
        .login-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 460px;
            padding: 1.5rem;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(40px);
            -webkit-backdrop-filter: blur(40px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 32px;
            padding: 2.5rem 2.5rem 3rem;
            box-shadow: 
                0 30px 80px rgba(0, 0, 0, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.05);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .login-card:hover {
            border-color: rgba(245, 158, 11, 0.15);
            box-shadow: 
                0 40px 100px rgba(0, 0, 0, 0.5),
                inset 0 1px 0 rgba(255, 255, 255, 0.08);
        }

        /* Brand */
        .brand {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .brand-logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 72px;
            height: 72px;
            background: var(--amber);
            border-radius: 20px;
            margin-bottom: 1rem;
            font-size: 2rem;
            font-weight: 800;
            color: var(--navy);
            box-shadow: 0 10px 40px rgba(245, 158, 11, 0.3);
            transition: transform 0.3s ease;
        }

        .brand-logo:hover {
            transform: scale(1.05) rotate(-3deg);
        }

        .brand h1 {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--white);
            letter-spacing: -0.5px;
            margin-bottom: 0.25rem;
        }

        .brand p {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.95rem;
            font-weight: 400;
        }

        /* Alerts */
        .alert {
            padding: 0.85rem 1.25rem;
            border-radius: 14px;
            margin-bottom: 1.25rem;
            font-size: 0.875rem;
            font-weight: 500;
            border: none;
            animation: slideDown 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-15px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .alert-success {
            background: rgba(5, 150, 105, 0.15);
            color: #6ee7b7;
            border-left: 3px solid var(--emerald);
        }

        .alert-danger {
            background: rgba(225, 29, 72, 0.15);
            color: #fca5a5;
            border-left: 3px solid var(--rose);
        }

        .alert .btn-close {
            filter: invert(1) brightness(0.6);
            opacity: 0.5;
            font-size: 0.7rem;
            margin-left: auto;
        }

        .alert .btn-close:hover {
            opacity: 1;
        }

        /* Form */
        .form-group {
            position: relative;
            margin-bottom: 1.25rem;
        }

        .form-group .input-icon {
            position: absolute;
            left: 1.1rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.35);
            font-size: 1.1rem;
            transition: all 0.3s ease;
            pointer-events: none;
        }

        .form-control {
            width: 100%;
            padding: 0.95rem 1.1rem 0.95rem 3rem;
            background: rgba(255, 255, 255, 0.06);
            border: 2px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            color: var(--white);
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.3);
            font-weight: 400;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--amber);
            box-shadow: 0 0 30px rgba(245, 158, 11, 0.08), inset 0 0 30px rgba(245, 158, 11, 0.02);
        }

        .form-control:focus + .input-icon,
        .form-group:has(.form-control:focus) .input-icon {
            color: var(--amber);
        }

        /* Password Toggle */
        .password-toggle-btn {
            position: absolute;
            right: 1.1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.35);
            cursor: pointer;
            padding: 0;
            font-size: 1.1rem;
            transition: color 0.3s ease;
        }

        .password-toggle-btn:hover {
            color: rgba(255, 255, 255, 0.6);
        }

        /* Options */
        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 1.5rem 0 1.75rem;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .checkbox-wrapper input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--amber);
            cursor: pointer;
            border-radius: 4px;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
        }

        .checkbox-wrapper label {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            user-select: none;
        }

        .forgot-link {
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .forgot-link:hover {
            color: var(--amber);
        }

        /* Submit Button */
        .btn-login {
            width: 100%;
            padding: 1.1rem;
            background: linear-gradient(135deg, var(--amber) 0%, #d97706 100%);
            border: none;
            border-radius: 16px;
            color: var(--navy);
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #fbbf24 0%, var(--amber) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(245, 158, 11, 0.3);
        }

        .btn-login:hover::before {
            opacity: 1;
        }

        .btn-login span,
        .btn-login i {
            position: relative;
            z-index: 1;
        }

        .btn-login i {
            transition: transform 0.3s ease;
        }

        .btn-login:hover i {
            transform: translateX(4px);
        }

        .btn-login:active {
            transform: scale(0.97);
        }

        .btn-login.loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .btn-login.loading .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(11, 30, 61, 0.1);
            border-top-color: var(--navy);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Footer */
        .login-footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .login-footer p {
            color: rgba(255, 255, 255, 0.2);
            font-size: 0.75rem;
            font-weight: 400;
        }

        .login-footer a {
            color: rgba(255, 255, 255, 0.3);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .login-footer a:hover {
            color: var(--amber);
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-card {
                padding: 2rem 1.5rem 2.5rem;
                border-radius: 24px;
            }

            .brand h1 {
                font-size: 1.5rem;
            }

            .brand-logo {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }

            .form-control {
                padding: 0.85rem 1rem 0.85rem 2.75rem;
                font-size: 0.9rem;
            }

            .login-wrapper {
                padding: 1rem;
            }

            .form-options {
                flex-direction: column;
                gap: 0.75rem;
                align-items: flex-start;
            }

            .shape-1 {
                width: 300px;
                height: 300px;
            }
            .shape-2 {
                width: 250px;
                height: 250px;
            }
        }
    </style>
</head>
<body>

    <!-- Animated Background Shapes -->
    <div class="bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <!-- Grid Overlay -->
    <div class="grid-overlay"></div>

    <!-- Login Form -->
    <div class="login-wrapper">
        <form action="{{ route('login') }}" method="post" id="loginForm">
            @csrf
            <div class="login-card">

                <!-- Brand -->
                <div class="brand">
                    <div class="brand-logo">
                        <img src="/public/images/leruma.png" width="70" />
                    </div>
                    <h1>{{ config('app.name') }}</h1>
                    <p>Sign in to continue to your dashboard</p>
                </div>

                <!-- Alerts -->
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle-fill"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        {{ $errors->first() }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Email -->
                <div class="form-group">
                    <i class="bi bi-envelope input-icon"></i>
                    <input type="email" name="email" class="form-control" placeholder="Email address" value="{{ old('email') }}" required autofocus>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <i class="bi bi-lock input-icon"></i>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                    <button type="button" class="password-toggle-btn" onclick="togglePassword()">
                        <i class="bi bi-eye" id="toggleIcon"></i>
                    </button>
                </div>

                <!-- Options -->
                <div class="form-options">
                    <label class="checkbox-wrapper">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember">Remember me</label>
                    </label>
                    <a href="#" class="forgot-link">Forgot password?</a>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn-login" id="loginBtn">
                    <span>Sign In</span>
                    <i class="bi bi-arrow-right"></i>
                </button>

                <!-- Footer -->
                <div class="login-footer">
                    <p>© {{ date('Y') }} <a href="#">{{ config('app.name') }}</a> — All rights reserved</p>
                </div>

            </div>
        </form>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.className = 'bi bi-eye-slash';
            } else {
                passwordInput.type = 'password';
                toggleIcon.className = 'bi bi-eye';
            }
        }

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('loginBtn');
            btn.innerHTML = '<span class="spinner"></span> Signing in...';
            btn.classList.add('loading');
        });

        // Auto-dismiss alerts after 5 seconds
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 500);
            }, 5000);
        });
    </script>

</body>
</html>