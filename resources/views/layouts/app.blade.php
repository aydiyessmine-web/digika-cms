<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'DigiCMS')</title>
  
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  
  @stack('styles')

  <!-- ============================================ -->
  <!-- THEME CSS - Version Simplifiée -->
  <!-- ============================================ -->
  @php
    $isDark = isset($appSettings) && $appSettings['is_dark'];
    $themeClass = isset($appSettings) ? $appSettings['theme_class'] : 'theme-light';
  @endphp

  <style>
    /* ============================================
       THEME DARK - Appliqué via body class
    ============================================ */
    body.theme-dark {
      background-color: #0f172a !important;
      color: #e2e8f0 !important;
    }
    
    body.theme-dark .dashboard-container {
      background-color: #0f172a !important;
    }
    
    body.theme-dark .main-content {
      background-color: #1e293b !important;
      color: #e2e8f0 !important;
    }
    
    body.theme-dark .sidebar {
      background-color: #0f172a !important;
      border-right-color: #1e293b !important;
    }
    
    body.theme-dark .stat-card,
    body.theme-dark .media-widget,
    body.theme-dark .campaigns-widget,
    body.theme-dark .settings-card,
    body.theme-dark .media-card,
    body.theme-dark .campaign-card,
    body.theme-dark .summary-card,
    body.theme-dark .modal-content,
    body.theme-dark .settings-dropdown {
      background-color: #2d3a4e !important;
      border-color: #4a5568 !important;
      color: #e2e8f0 !important;
    }
    
    body.theme-dark .card-number,
    body.theme-dark .campaign-name,
    body.theme-dark .media-title,
    body.theme-dark .settings-header h1,
    body.theme-dark .library-title,
    body.theme-dark .campaign-title,
    body.theme-dark .summary-value {
      color: #f1f5f9 !important;
    }
    
    body.theme-dark .card-label,
    body.theme-dark .media-meta,
    body.theme-dark .campaign-detail,
    body.theme-dark .settings-header p,
    body.theme-dark .library-subtitle,
    body.theme-dark .campaign-desc,
    body.theme-dark .dates,
    body.theme-dark .summary-label,
    body.theme-dark .media-count,
    body.theme-dark .progress-label {
      color: #94a3b8 !important;
    }
    
    body.theme-dark .search-wrapper input,
    body.theme-dark .filter-search-wrapper input,
    body.theme-dark .filter-select-wrapper select,
    body.theme-dark .form-group input,
    body.theme-dark .form-group select {
      background-color: #1e293b !important;
      color: #e2e8f0 !important;
      border-color: #4a5568 !important;
    }
    
    body.theme-dark .settings-tab {
      color: #94a3b8 !important;
    }
    
    body.theme-dark .settings-tab.active {
      color: #c91818 !important;
      background: #2d3a4e !important;
    }
    
    body.theme-dark .ai-assistant-widget {
      background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;
    }
    
    body.theme-dark .nav-item:hover {
      background-color: #1e293b !important;
    }
    
    body.theme-dark .user-profile {
      border-top-color: #1e293b !important;
    }
    
    body.theme-dark .logout-btn {
      background-color: #3b1515 !important;
      border-color: #5a1e1e !important;
    }
    
    body.theme-dark .card-footer,
    body.theme-dark .divider {
      border-top-color: #4a5568 !important;
    }
    
    body.theme-dark .progress-bar-container {
      background-color: #1e293b !important;
    }
    
    body.theme-dark .modal {
      background: rgba(0,0,0,0.8) !important;
    }
    
    body.theme-dark .nav-item.active {
      background-color: #c91818 !important;
    }
    
    body.theme-dark .nav-item.active::before {
      background-color: #ffffff !important;
    }
    
    body.theme-dark .btn-primary {
      background: #c91818 !important;
    }
    
    body.theme-dark .btn-primary:hover {
      background: #a81414 !important;
    }
    
    body.theme-dark .btn-secondary {
      background: #1e293b !important;
      color: #e2e8f0 !important;
      border-color: #4a5568 !important;
    }
    
    body.theme-dark .btn-secondary:hover {
      background: #2d3a4e !important;
    }
    
    body.theme-dark .stats-panel {
      background-color: #0f172a !important;
      border-right-color: #1e293b !important;
    }
    
    body.theme-dark .stats-header .stats-title {
      color: #f1f5f9 !important;
    }
    
    body.theme-dark .stats-header .stats-subtitle {
      color: #94a3b8 !important;
    }
    
    body.theme-dark .main-header {
      border-bottom-color: #4a5568 !important;
    }
    
    body.theme-dark .header-page-title {
      color: #94a3b8 !important;
    }
    
    body.theme-dark .settings-btn {
      color: #94a3b8 !important;
    }
    
    body.theme-dark .settings-btn:hover {
      background-color: #1e293b !important;
    }
    
    body.theme-dark .session-item {
      border-bottom-color: #4a5568 !important;
    }
    
    body.theme-dark .session-location {
      color: #f1f5f9 !important;
    }
    
    body.theme-dark .session-device,
    body.theme-dark .session-time {
      color: #94a3b8 !important;
    }
    
    body.theme-dark .security-level {
      background: #1e293b !important;
      color: #10b981 !important;
    }
    
    body.theme-dark .checkbox-item label {
      color: #e2e8f0 !important;
    }
    
    body.theme-dark .status-badge.success {
      background: #1e293b !important;
      color: #10b981 !important;
    }
    
    body.theme-dark .status-badge.error {
      background: #1e293b !important;
      color: #ef4444 !important;
    }
    
    body.theme-dark .upload-zone {
      background-color: #1e293b !important;
      border-color: #4a5568 !important;
    }
    
    body.theme-dark .upload-text {
      color: #e2e8f0 !important;
    }
    
    body.theme-dark .upload-subtext {
      color: #94a3b8 !important;
    }
    
    body.theme-dark .logs-table th {
      background: #1e293b !important;
      color: #94a3b8 !important;
    }
    
    body.theme-dark .logs-table td {
      border-bottom-color: #4a5568 !important;
      color: #e2e8f0 !important;
    }
    
    body.theme-dark .logs-table tr:hover td {
      background: #2d3a4e !important;
    }
    
    body.theme-dark .summary-icon.pink,
    body.theme-dark .summary-icon.blue,
    body.theme-dark .summary-icon.purple,
    body.theme-dark .summary-icon.green,
    body.theme-dark .summary-icon.orange,
    body.theme-dark .summary-icon.slate {
      background-color: #1e293b !important;
    }
    
    body.theme-dark .gallery-count {
      background-color: #1e293b !important;
      color: #94a3b8 !important;
    }
  </style>
</head>

<body class="{{ isset($appSettings) ? $appSettings['theme_class'] : 'theme-light' }}">

<div class="dashboard-container">
  
  <!-- ============================================ -->
  <!-- SIDEBAR -->
  <!-- ============================================ -->
  <aside class="sidebar">
    <div class="sidebar-top">
      <div class="logo"><img src="{{ asset('images/logo.png') }}" alt="Digika CMS" onerror="this.src='https://placehold.co/120x38/ffffff/c91818?text=DigikaCMS'"></div>
      <nav class="nav-menu">
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
          <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="9"></rect><rect x="14" y="3" width="7" height="5"></rect><rect x="14" y="12" width="7" height="9"></rect><rect x="3" y="16" width="7" height="5"></rect></svg>
          <span>Dashboard</span>
        </a>
        <a href="{{ route('media.index') }}" class="nav-item {{ request()->routeIs('media.*') ? 'active' : '' }}">
          <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path><line x1="12" y1="11" x2="12" y2="17"></line><line x1="9" y1="14" x2="15" y2="14"></line></svg>
          <span>Médiathèque</span>
        </a>
        <a href="{{ route('campaigns.index') }}" class="nav-item {{ request()->routeIs('campaigns.*') ? 'active' : '' }}">
          <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19l7-2V7l-7-2v14z"></path><path d="M12 5L3 9v6l9 4z"></path><line x1="23" y1="12" x2="19" y2="12"></line></svg>
          <span>Campagnes</span>
        </a>

        @php
            $user = auth()->user();
            $isAdmin = $user && $user->role == 'admin';
        @endphp

       <!-- ADMINISTRATION - Visible uniquement pour admin -->
@if(auth()->check() && auth()->user()->role == 'admin')
<div class="nav-section-title">ADMINISTRATION</div>
<a href="{{ route('settings.index') }}" class="nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="12" r="3"></circle>
        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
    </svg>
    <span>Paramètres</span>
</a>
<a href="{{ route('profiles.index') }}" class="nav-item {{ request()->routeIs('profiles.*') ? 'active' : '' }}">
    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
        <circle cx="12" cy="7" r="4"></circle>
    </svg>
    <span>Profils</span>
</a>
@endif

        <div class="nav-section-title">INTELLIGENCE IA</div>
        <a href="{{ route('ai.assistant') }}" class="nav-item {{ request()->routeIs('ai.assistant') ? 'active' : '' }}">
          <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="10" r="3"></circle><path d="M12 2a8 8 0 0 0-8 8c0 4 3 6 8 10 5-4 8-6 8-10a8 8 0 0 0-8-8z"></path></svg>
          <span>Assistant IA</span>
        </a>
      </nav>
    </div>
    <div class="sidebar-bottom">
      <div class="user-profile">
        <div class="user-avatar">{{ auth()->user() ? substr(auth()->user()->name, 0, 1) : 'U' }}</div>
        <div class="user-info">
          <div class="user-name">{{ auth()->user() ? auth()->user()->name : 'Utilisateur' }}</div>
          <div class="user-role">
            @if(auth()->user())
              @if(auth()->user()->role == 'admin') Administrateur
              @elseif(auth()->user()->role == 'marketing') Marketing
              @else {{ ucfirst(auth()->user()->role) }}
              @endif
            @else Invité
            @endif
          </div>
        </div>
      </div>
      <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="logout-btn"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg><span>Logout</span></button></form>
    </div>
  </aside>

  @yield('content')
</div>

<script src="{{ asset('js/dashboard.js') }}"></script>
@stack('scripts')

<!-- Script pour appliquer le thème dynamiquement -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Récupérer le thème depuis les paramètres
    fetch('/settings/get')
        .then(response => response.json())
        .then(data => {
            if (data.theme === 'dark') {
                document.body.classList.add('theme-dark');
                document.body.classList.remove('theme-light');
            } else {
                document.body.classList.add('theme-light');
                document.body.classList.remove('theme-dark');
            }
        })
        .catch(error => {
            console.log('Theme error:', error);
        });
});
</script>
</body>
</html>