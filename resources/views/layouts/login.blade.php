<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login - Cendrawasih Karsa Store')</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <!-- Login Specific Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    
    @stack('styles')
</head>
<body>

    <div class="login-container">
        
        <div class="login-card">
            <div class="login-header">
                <img src="{{ asset('img/logo-2.png') }}" alt="Logo Cendrawasih Karsa Store" class="login-logo">
                
                <h1 class="login-title">Cendrawasih Karsa Store</h1>
                <p class="login-subtitle">Inventory Management System</p>
            </div>
            
            <div class="login-body">
                <form action="#" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <div class="form-input-wrapper">
                            <i class="ti ti-mail form-input-icon"></i>
                            <input type="email" name="email" class="form-input" placeholder="rasta@karsa.com" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="form-input-wrapper">
                            <i class="ti ti-lock form-input-icon"></i>
                            <input type="password" name="password" class="form-input" placeholder="••••••••" required>
                            <i class="ti ti-eye password-toggle"></i>
                        </div>
                    </div>

                    <div class="form-footer">
                        <div class="checkbox-wrapper">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">Ingat Saya</label>
                        </div>
                        <a href="#" class="forgot-link">Lupa Password?</a>
                    </div>

                    <button type="submit" class="btn-login">
                        <i class="ti ti-login"></i>
                        <span class="btn-text">Masuk</span>
                    </button>
                </form>
            </div>
        </div>

        <div class="login-footer">
            &copy; 2026 Cendrawasih Karsa Store. All rights reserved.
        </div>

    </div>

</body>
</html>