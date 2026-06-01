<header class="topbar">
  <button onclick="toggleSidebar()" style="border:none;background:none;cursor:pointer;padding:4px;border-radius:6px;color:var(--gray-500);font-size:18px">
    <i class="ti ti-menu-2"></i>
  </button>
  <span class="page-title">@yield('header_title', 'Dashboard')</span>
    
  <div class="topbar-spacer"></div>
  
  <div class="topbar-search">
    <i class="ti ti-search" style="color:var(--gray-400);font-size:14px"></i>
    <input type="text" placeholder="Search inventory, products...">
  </div>
  
  <div style="position:relative">
    <button class="topbar-btn" onclick="toggleNotif()">
      <i class="ti ti-bell"></i>
      <span class="notif-dot"></span>
    </button>
    <div class="notif-panel" id="notifPanel">
      <div style="padding:12px 16px;border-bottom:1px solid var(--gray-100);display:flex;justify-content:space-between;align-items:center">
        <span style="font-size:14px;font-weight:600">Notifications</span>
        <span style="font-size:11px;color:var(--red);cursor:pointer">Mark all read</span>
      </div>
      <div class="notif-item">
        <div class="notif-dot-status" style="background:var(--red)"></div>
        <div><div class="notif-text"><strong>Low stock alert</strong> — Jersey Kandang Persipura (Sz L) has 8 units left.</div><div class="notif-time">5 min ago</div></div>
      </div>
      <div class="notif-item">
        <div class="notif-dot-status" style="background:#059669"></div>
        <div><div class="notif-text"><strong>Stock In confirmed</strong> — PO #SI-2024-089 received.</div><div class="notif-time">1 hr ago</div></div>
      </div>
    </div>
  </div>
  
  <button class="topbar-btn"><i class="ti ti-help-circle"></i></button>
  <div style="position:relative">
    <div class="user-profile-trigger" onclick="toggleProfileDropdown()" style="display:flex;align-items:center;gap:8px;cursor:pointer;padding:4px 8px;border-radius:8px;transition:0.15s">
      <div class="user-avatar" style="width:32px;height:32px;font-size:11px;margin:0">
        {{ strtoupper(substr(Auth::user()->nama, 0, 2)) }}
      </div>
      <div style="display:flex;flex-direction:column;text-align:left;line-height:1.2">
        <span style="font-size:12px;font-weight:600;color:var(--gray-800)">{{ Auth::user()->nama }}</span>
        <span style="font-size:10px;color:var(--gray-500)">{{ Auth::user()->role->nama_role ?? 'User' }}</span>
      </div>
      <i class="ti ti-chevron-down" style="font-size:12px;color:var(--gray-400)"></i>
    </div>
    
    <div class="profile-dropdown" id="profileDropdown" style="position:absolute;top:44px;right:0;width:200px;background:white;border:1px solid var(--gray-200);border-radius:12px;box-shadow:0 8px 30px rgba(0,0,0,.12);z-index:50;display:none;padding:8px 0">
      <div style="padding:10px 16px;border-bottom:1px solid var(--gray-100)">
        <div style="font-size:12px;font-weight:600;color:var(--gray-800)">{{ Auth::user()->nama }}</div>
        <div style="font-size:10px;color:var(--gray-500)">{{ Auth::user()->role->nama_role ?? 'User' }}</div>
      </div>
      <div style="height:1px;background:var(--gray-100);margin:4px 0"></div>
      <form action="{{ route('logout') }}" method="POST" style="margin:0">
        @csrf
        <button type="submit" style="width:100%;text-align:left;background:none;border:none;cursor:pointer;display:flex;align-items:center;gap:8px;padding:10px 16px;color:var(--red);font-size:13px;font-family:inherit;font-weight:500;transition:0.15s" class="profile-dropdown-item logout-btn">
          <i class="ti ti-logout" style="font-size:16px"></i> Keluar (Logout)
        </button>
      </form>
    </div>
  </div>
</header>