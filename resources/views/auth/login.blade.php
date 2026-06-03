@extends('layouts.login')

@section('title', 'Login - Cendrawasih Karsa Store')

@section('content')
<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <div class="brand-logo-login">
                <img src="{{ asset('img/logo.png') }}" alt="Cendrawasih Karsa Store" style="width:100%;height:100%;object-fit:contain;">
            </div>
            <h1 class="login-title">Cendrawasih Karsa Store</h1>
            <p class="login-subtitle">Inventory Management System</p>
        </div>

        <div class="login-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <i class="ti ti-alert-circle"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    <i class="ti ti-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" id="loginForm">
                @csrf
                
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="form-input-wrapper">
                        <i class="ti ti-mail form-input-icon"></i>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-input" 
                            placeholder="your.email@store.id"
                            value="{{ old('email') }}"
                            required
                            autofocus
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="form-input-wrapper">
                        <i class="ti ti-lock form-input-icon"></i>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-input" 
                            placeholder="Enter your password"
                            required
                        >
                        <i class="ti ti-eye password-toggle" id="togglePassword"></i>
                    </div>
                </div>

                <div class="form-footer">
                    <div class="checkbox-wrapper">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <a href="#" class="forgot-link">Forgot password?</a>
                </div>

                <button type="submit" class="btn-login" id="loginBtn">
                    <span class="spinner"></span>
                    <span class="btn-text">
                        <i class="ti ti-login"></i>
                        Masuk
                    </span>
                </button>
            </form>
        </div>

        <div class="login-footer">
            &copy; 2026 Cendrawasih Karsa Store. All rights reserved.
        </div>
    </div>
</div>

<script>
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        // Toggle icon
        this.classList.toggle('ti-eye');
        this.classList.toggle('ti-eye-off');
    });

    // Form submit loading state
    const loginForm = document.getElementById('loginForm');
    const loginBtn = document.getElementById('loginBtn');
    
    loginForm.addEventListener('submit', function() {
        loginBtn.classList.add('loading');
        loginBtn.disabled = true;
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.3s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        });
    }, 5000);
</script>
@endsection
