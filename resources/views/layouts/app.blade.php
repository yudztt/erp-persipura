<!DOCTYPE html>
<html lang="id">
<head>
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Cendrawasih Karsa Store')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('img/logo-2.png') }}">

    <!-- Untuk kompatibilitas browser lama -->
    <link rel="shortcut icon" href="{{ asset('img/logo-2.png') }}">
    
    <!-- Font & Tabler Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Custom global SweetAlert theme styles & Profile Dropdown styles -->
    <style>
        .swal2-popup {
            font-family: 'Inter', sans-serif !important;
            border-radius: 16px !important;
        }
        .swal2-confirm {
            border-radius: 8px !important;
            padding: 10px 24px !important;
            font-weight: 500 !important;
        }
        .swal2-cancel {
            border-radius: 8px !important;
            padding: 10px 24px !important;
            font-weight: 500 !important;
        }
        .user-profile-trigger:hover {
            background: var(--gray-100);
        }
        .profile-dropdown-item {
            color: var(--gray-700);
            text-decoration: none;
            transition: 0.15s;
        }
        .profile-dropdown-item:hover {
            background: var(--gray-50);
        }
        .profile-dropdown-item.logout-btn:hover {
            background: #FEE2E2 !important;
            color: var(--red) !important;
        }
    </style>
    <style>
        /* Menggunakan seluruh CSS dari persipura_erp_inventory_system.html */
        *{margin:0;padding:0;box-sizing:border-box}
        :root{
            --red:#C1121F;--red-dark:#991018;--red-light:#F8E7E8;--red-mid:#E8474F;
            --white:#FFFFFF;--gray-50:#F9FAFB;--gray-100:#F3F4F6;--gray-200:#E5E7EB;
            --gray-300:#D1D5DB;--gray-400:#9CA3AF;--gray-500:#6B7280;--gray-600:#4B5563;
            --gray-700:#374151;--gray-800:#1F2937;--gray-900:#111827;
            --sidebar-w:240px;--topbar-h:60px;
        }
        body{font-family:'Inter',sans-serif;background:#F5F6F8;color:var(--gray-900);font-size:14px;line-height:1.5;display:flex;height:100vh;overflow:hidden}
        .sr-only{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);border:0}
        
        /* SIDEBAR */
        .sidebar{width:var(--sidebar-w);height:100vh;background:var(--gray-900);display:flex;flex-direction:column;flex-shrink:0;overflow:hidden;transition:width .25s}
        .sidebar-brand{min-height:var(--topbar-h);display:flex;align-items:center;gap:12px;padding:14px 16px;border-bottom:1px solid rgba(255,255,255,.06);flex-shrink:0}
        .brand-logo{width:38px;height:38px;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:transparent;border:none;padding:0;overflow:visible}
        .brand-logo img{width:38px;height:38px;object-fit:contain;display:block;border-radius:50%}
        .brand-logo svg{width:18px;height:18px;fill:white}
        .brand-text{display:flex;flex-direction:column;gap:2px;overflow:hidden;flex:1;min-width:0}
        .brand-name{font-size:12px;font-weight:600;color:white;letter-spacing:.01em;line-height:1.25;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
        .brand-sub{font-size:9px;color:var(--gray-500);white-space:nowrap;text-transform:uppercase;letter-spacing:.5px;font-weight:500}
        .sidebar-nav{flex:1;overflow-y:auto;padding:6px 10px 16px;scrollbar-width:none}
        .sidebar-nav::-webkit-scrollbar{display:none}
        .nav-section{padding:14px 10px 5px;font-size:9.5px;font-weight:600;color:var(--gray-500);text-transform:uppercase;letter-spacing:.08em}
        .nav-section:first-child{padding-top:8px}
        .nav-item{text-decoration:none;display:flex;align-items:center;gap:11px;padding:8px 12px;margin:1px 0;border-radius:8px;cursor:pointer;color:var(--gray-400);font-size:13px;font-weight:400;transition:background .15s,color .15s;position:relative;white-space:nowrap}
        .nav-item:hover{background:rgba(255,255,255,.06);color:rgba(255,255,255,.92)}
        .nav-item.active{background:rgba(193,18,31,.18);color:white;font-weight:500}
        .nav-item.active::before{content:'';position:absolute;left:0;top:50%;transform:translateY(-50%);width:3px;height:22px;background:var(--red);border-radius:0 3px 3px 0}
        .nav-item .ni{font-size:17px;flex-shrink:0;width:20px;text-align:center;opacity:.9}
        .nav-item.active .ni{opacity:1}
        .nav-badge{margin-left:auto;background:var(--red);color:white;font-size:10px;font-weight:600;padding:2px 6px;border-radius:10px}
        .sidebar-footer{padding:12px 16px;border-top:1px solid rgba(255,255,255,.07)}
        .user-row{display:flex;align-items:center;gap:10px;padding:8px;border-radius:8px;cursor:pointer}
        .user-row:hover{background:rgba(255,255,255,.05)}
        .user-avatar{width:32px;height:32px;border-radius:50%;background:var(--red);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:white;flex-shrink:0}
        .user-info{overflow:hidden;flex:1}
        .user-name{font-size:13px;color:white;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .user-role{font-size:11px;color:var(--gray-500);white-space:nowrap}

        /* MAIN */
        .main{flex:1;display:flex;flex-direction:column;overflow:hidden;min-width:0}
        .topbar{height:var(--topbar-h);background:white;border-bottom:1px solid var(--gray-200);display:flex;align-items:center;gap:12px;padding:0 24px;flex-shrink:0}
        .page-title{font-size:16px;font-weight:600;color:var(--gray-900)}
        .page-breadcrumb{font-size:12px;color:var(--gray-400);margin-left:4px}
        .topbar-spacer{flex:1}
        .topbar-search{display:flex;align-items:center;gap:8px;background:var(--gray-100);border-radius:8px;padding:0 12px;height:34px;width:220px;border:1px solid transparent;transition:.15s}
        .topbar-search:focus-within{border-color:var(--red-light);background:white}
        .topbar-search input{border:none;background:none;outline:none;font-size:13px;color:var(--gray-700);width:100%;font-family:'Inter',sans-serif}
        .topbar-btn{width:36px;height:36px;border-radius:8px;border:1px solid var(--gray-200);background:white;display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--gray-500);font-size:16px;transition:.15s;position:relative}
        .notif-dot{position:absolute;top:8px;right:8px;width:6px;height:6px;background:var(--red);border-radius:50%;border:1px solid white}
        .content{flex:1;overflow-y:auto;padding:24px;scrollbar-width:thin;scrollbar-color:var(--gray-300) transparent}
        
        /* COMPONENTS (Cards, Buttons, Tables, Modal dll) */
        .kpi-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px}
        .kpi-card{background:white;border:1px solid var(--gray-200);border-radius:12px;padding:18px 20px;position:relative;overflow:hidden}
        .kpi-card::after{content:'';position:absolute;top:0;right:0;width:4px;height:100%;background:var(--accent,var(--gray-200));border-radius:0 12px 12px 0}
        .kpi-icon{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;margin-bottom:12px}
        .kpi-label{font-size:12px;color:var(--gray-500);font-weight:500;text-transform:uppercase;letter-spacing:.5px}
        .kpi-value{font-size:26px;font-weight:700;color:var(--gray-900);margin:4px 0;line-height:1.1}
        .kpi-change{font-size:12px;font-weight:500;display:flex;align-items:center;gap:4px}
        .kpi-change.up{color:#059669}
        .kpi-change.down{color:var(--red)}
        
        .section-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px}
        .section-title{font-size:15px;font-weight:600;color:var(--gray-900)}
        .section-subtitle{font-size:12px;color:var(--gray-400);margin-top:2px}
        .btn{display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;border:none;font-family:'Inter',sans-serif;transition:.15s}
        .btn-primary{background:var(--red);color:white}
        .btn-secondary{background:white;color:var(--gray-700);border:1px solid var(--gray-200)}
        .btn-sm{padding:6px 12px;font-size:12px}
        .btn-ghost{background:none;color:var(--gray-500);border:1px solid transparent;padding:6px 10px;border-radius:6px}
        
        .charts-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px}
        .chart-card{background:white;border:1px solid var(--gray-200);border-radius:12px;padding:20px}
        .chart-svg-container{width:100%;height:180px;margin-top:12px}
        
        .table-card{background:white;border:1px solid var(--gray-200);border-radius:12px;overflow:hidden;margin-bottom:24px}
        .table-header{display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid var(--gray-100)}
        table{width:100%;border-collapse:collapse}
        th{font-size:11px;font-weight:600;color:var(--gray-500);text-transform:uppercase;letter-spacing:.5px;padding:10px 16px;text-align:left;background:var(--gray-50);border-bottom:1px solid var(--gray-200)}
        td{padding:11px 16px;border-bottom:1px solid var(--gray-100);font-size:13px;color:var(--gray-700)}
        tr:hover td{background:var(--gray-50)}
        .badge{display:inline-flex;align-items:center;gap:4px;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:600}
        .badge-success{background:#D1FAE5;color:#065F46}
        .badge-warning{background:#FEF3C7;color:#92400E}
        .badge-danger{background:#FEE2E2;color:#991B1B}
        .badge-gray{background:var(--gray-100);color:var(--gray-600)}

        .ai-card{background:linear-gradient(135deg,var(--gray-900) 0%,#2d1a1c 100%);border-radius:12px;padding:24px;color:white;margin-bottom:24px;position:relative;overflow:hidden}
        .ai-tag{display:inline-flex;align-items:center;gap:6px;background:rgba(193,18,31,.3);color:#FCA5A5;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:600;margin-bottom:12px}
        .ai-title{font-size:18px;font-weight:700;margin-bottom:6px}
        .ai-sub{font-size:13px;color:rgba(255,255,255,.6);margin-bottom:16px}
        .forecast-row{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:16px}
        .forecast-item{background:rgba(255,255,255,.07);border-radius:8px;padding:12px 14px}
        .forecast-item .fi-label{font-size:11px;color:rgba(255,255,255,.5);margin-bottom:4px}
        .forecast-item .fi-val{font-size:20px;font-weight:700;color:white}
        .forecast-item .fi-change{font-size:11px;color:#86EFAC;margin-top:2px}

        .form-grid{display:grid;gap:16px}
        .form-grid-2{grid-template-columns:1fr 1fr}
        .form-group{display:flex;flex-direction:column;gap:5px}
        .form-label{font-size:12px;font-weight:500;color:var(--gray-700)}
        .form-input, .form-select{padding:9px 12px;border:1px solid var(--gray-300);border-radius:8px;font-size:13px;color:var(--gray-900);background:white;outline:none;font-family:'Inter',sans-serif;width:100%}
        
        .toolbar{display:flex;align-items:center;gap:8px;margin-bottom:16px}
        .search-box{display:flex;align-items:center;gap:8px;background:white;border:1px solid var(--gray-200);border-radius:8px;padding:0 12px;height:36px;flex:1;max-width:280px}
        .search-box input{border:none;background:none;outline:none;font-size:13px;color:var(--gray-700);width:100%;font-family:'Inter',sans-serif}
        .filter-btn{height:36px;padding:0 12px;border:1px solid var(--gray-200);border-radius:8px;background:white;font-size:13px;color:var(--gray-600);cursor:pointer;font-family:'Inter',sans-serif;display:flex;align-items:center;gap:6px}

        .pagination{display:flex;align-items:center;gap:4px;justify-content:flex-end;padding:12px 16px;border-top:1px solid var(--gray-100)}
        .page-btn{width:28px;height:28px;border-radius:6px;border:1px solid var(--gray-200);background:white;font-size:12px;cursor:pointer;display:flex;align-items:center;justify-content:center;color:var(--gray-600)}
        .page-btn.active{background:var(--red);color:white;border-color:var(--red)}

        .modal-overlay{display:none;position:absolute;inset:0;background:rgba(0,0,0,.4);z-index:100;align-items:center;justify-content:center;backdrop-filter:blur(2px)}
        .modal-overlay.open{display:flex}
        .modal{background:white;border-radius:14px;width:500px;max-width:95%;max-height:80vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.2)}
        .modal-header{display:flex;align-items:center;justify-content:space-between;padding:20px 24px 16px;border-bottom:1px solid var(--gray-100)}
        .modal-title{font-size:16px;font-weight:600;color:var(--gray-900)}
        .modal-close{width:28px;height:28px;border:none;background:var(--gray-100);border-radius:6px;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:16px}
        .modal-body{padding:20px 24px}
        .modal-footer{padding:16px 24px;border-top:1px solid var(--gray-100);display:flex;justify-content:flex-end;gap:8px}

        .notif-panel{position:absolute;top:52px;right:0;width:320px;background:white;border:1px solid var(--gray-200);border-radius:12px;box-shadow:0 8px 30px rgba(0,0,0,.12);z-index:50;display:none}
        .notif-panel.open{display:block}
        .notif-item{display:flex;gap:10px;padding:12px 16px;border-bottom:1px solid var(--gray-100);cursor:pointer;}
        .notif-dot-status{width:8px;height:8px;border-radius:50%;flex-shrink:0;margin-top:4px}
        .notif-text{font-size:12px;color:var(--gray-700);line-height:1.4}
        .notif-time{font-size:11px;color:var(--gray-400);margin-top:2px}

        .settings-tabs{display:flex;gap:0;border-bottom:1px solid var(--gray-200);margin-bottom:24px}
        .settings-tab{padding:10px 16px;font-size:13px;cursor:pointer;color:var(--gray-500);border-bottom:2px solid transparent;margin-bottom:-1px;font-weight:500}
        .settings-tab.active{color:var(--red);border-bottom-color:var(--red)}
    </style>
    @stack('styles')
</head>
<body>

    <h2 class="sr-only">Persipura Enterprise ERP</h2>

    <div style="display:flex; height:100vh; width:100%; position:relative; overflow:hidden;">
        
        <!-- Sidebar -->
        @include('components.sidebar')

        <div class="main">
            <!-- Topbar -->
            @include('components.topbar')

            <!-- Main Content Area -->
            <div class="content">
                @yield('content')
            </div>
            
        </div>
    </div>

    <!-- Modals Terpusat (Bisa diisi oleh child view) -->
    @stack('modals')

    <!-- AI Chatbot Global -->
    @auth
        @include('components.chatbot')
    @endauth

    <!-- Scripts Global -->
    <script>
        function toggleSidebar(){
            const s=document.getElementById('sidebar');
            const w=s.style.width;
            s.style.width=(w==='0px'||w==='0')?'var(--sidebar-w)':w?'0px':w==='240px'?'0px':'240px';
        }
        function toggleNotif(){
            const p=document.getElementById('notifPanel');
            p.classList.toggle('open');
        }
        function closeNotif(){
            const p=document.getElementById('notifPanel');
            if(p) p.classList.remove('open');
        }
        function toggleProfileDropdown(){
            const p=document.getElementById('profileDropdown');
            if (p) p.style.display = p.style.display === 'block' ? 'none' : 'block';
        }
        function closeProfileDropdown(){
            const p=document.getElementById('profileDropdown');
            if(p) p.style.display = 'none';
        }
        document.addEventListener('click',e=>{
            if(!e.target.closest('.topbar-btn')&&!e.target.closest('.notif-panel'))closeNotif();
            if(!e.target.closest('.user-profile-trigger')&&!e.target.closest('.profile-dropdown'))closeProfileDropdown();
        });
        
        function openModal(id){document.getElementById(id).classList.add('open')}
        function closeModal(id){document.getElementById(id).classList.remove('open')}
        document.querySelectorAll('.modal-overlay').forEach(m=>m.addEventListener('click',e=>{if(e.target===m)m.classList.remove('open')}));

        // SweetAlert2 Flash Messages
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: {!! json_encode(session('success')) !!},
                confirmButtonColor: '#C1121F',
                timer: 3500,
                timerProgressBar: true
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: {!! json_encode(session('error')) !!},
                confirmButtonColor: '#C1121F'
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian!',
                text: {!! json_encode($errors->first()) !!},
                confirmButtonColor: '#C1121F'
            });
        @endif
    </script>
    @stack('scripts')
</body>
</html>