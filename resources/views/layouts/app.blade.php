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
    
    <!-- App Unified Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
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