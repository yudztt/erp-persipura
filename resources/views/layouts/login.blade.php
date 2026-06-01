<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login - Cendrawasih Karsa Store')</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <style>
        *{ margin:0; padding:0; box-sizing:border-box; }

        :root{
            --primary:#b30000;
            --primary-hover:#8f0000;
            --white:#ffffff;
            --gray-100:#f8fafc;
            --gray-200:#e5e7eb;
            --gray-300:#d1d5db;
            --gray-400:#9ca3af;
            --gray-500:#6b7280;
            --gray-600:#4b5563;
            --gray-700:#374151;
            --gray-800:#1f2937;
            --gray-900:#111827;
        }

        body{
            font-family:'Inter', sans-serif;
            background:#f4f6f9;
            min-height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            padding:24px;
        }

        .login-container{
            width:100%;
            max-width:400px;
        }

        .login-card{
            background:#fff;
            border-radius:20px;
            box-shadow: 0 10px 30px rgba(0,0,0,.06), 0 2px 10px rgba(0,0,0,.03);
            overflow:hidden;
            border:none;
        }

        .login-header{
            text-align:center;
            padding:40px 32px 24px; 
        }

        /* Style khusus logo agar presisi dan proporsional */
        .login-logo {
            display: block;
            margin: 0 auto 20px;
            height: 84px; /* Tinggi disesuaikan agar pas dengan bentuk logo burung */
            width: auto;
            object-fit: contain;
        }

        .login-title{
            font-size:22px; 
            font-weight:700;
            color:var(--gray-900);
            margin-bottom:6px;
        }

        .login-subtitle{
            font-size:14px;
            color:var(--gray-500);
            line-height:1.5;
        }

        .login-body{
            padding:0 32px 40px; 
        }

        .form-group{
            margin-bottom:20px; 
        }

        .form-label{
            display:block;
            margin-bottom:8px;
            font-size:13px;
            font-weight:600;
            color:var(--gray-700);
        }

        .form-input-wrapper{
            position:relative;
        }

        .form-input-icon{
            position:absolute;
            left:14px;
            top:50%;
            transform:translateY(-50%);
            color:var(--gray-400);
            font-size:18px;
        }

        .form-input{
            width:100%;
            height:46px;
            padding:0 14px 0 42px;
            border:1px solid var(--gray-300);
            border-radius:10px;
            outline:none;
            font-size:14px;
            transition:.2s;
            font-family:'Inter',sans-serif;
            color: var(--gray-800);
            background-color: var(--gray-100);
        }

        .form-input:focus{
            border-color:var(--primary);
            box-shadow:0 0 0 3px rgba(179,0,0,.12);
            background-color: var(--white);
        }

        .password-toggle{
            position:absolute;
            right:14px;
            top:50%;
            transform:translateY(-50%);
            cursor:pointer;
            color:var(--gray-400);
            font-size: 18px;
        }

        .form-footer{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin:8px 0 24px;
        }

        .checkbox-wrapper{
            display:flex;
            align-items:center;
            gap:8px;
        }

        .checkbox-wrapper label{
            font-size:13px;
            color:var(--gray-600);
            cursor:pointer;
        }

        .forgot-link{
            color:var(--primary);
            text-decoration:none;
            font-size:13px;
            font-weight:600;
        }

        .forgot-link:hover{
            text-decoration:underline;
        }

        .btn-login{
            width:100%;
            height:48px;
            border:none;
            border-radius:10px;
            background:var(--primary);
            color:#fff;
            font-size:14px;
            font-weight:600;
            cursor:pointer;
            transition:.2s;
            display:flex;
            justify-content:center;
            align-items:center;
            gap:8px;
        }

        .btn-login:hover{
            background:var(--primary-hover);
        }

        .login-footer{
            margin-top:24px;
            text-align:center;
            font-size:12px;
            color:var(--gray-500);
        }

        @media(max-width:480px){
            body{ padding:16px; }
            .login-header{ padding:32px 24px 20px; }
            .login-body{ padding:0 24px 32px; }
            .login-title{ font-size:20px; }
            .login-logo{ height: 70px; margin-bottom: 16px; }
        }
    </style>
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
                        <label class="form-label">Email Address</label>
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
                            <label for="remember">Remember me</label>
                        </div>
                        <a href="#" class="forgot-link">Forgot password?</a>
                    </div>

                    <button type="submit" class="btn-login">
                        <i class="ti ti-login"></i>
                        <span class="btn-text">Sign In</span>
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