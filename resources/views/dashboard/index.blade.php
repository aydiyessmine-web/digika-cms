<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Tableau de bord - DigiCMS</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body, html {
      height: 100vh;
      width: 100vw;
      overflow: hidden;
      font-family: 'Poppins', sans-serif;
      background-color: #ffffff;
    }

    .dashboard-container {
      display: flex;
      height: 100vh;
      width: 100vw;
      overflow: hidden;
    }

    /* ========== LEFT SIDEBAR ========== */
    .sidebar {
      flex: 0 0 240px;
      width: 240px;
      background-color: #121214;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      border-right: 1px solid #1f1f23;
      flex-shrink: 0;
    }

    .sidebar-top {
      padding-top: 36px;
      display: flex;
      flex-direction: column;
    }

    .sidebar-top .logo {
      margin-left: 24px;
      margin-bottom: 40px;
    }

    .sidebar-top .logo img {
      height: 58px;
      width: auto;
    }

    .nav-menu {
      display: flex;
      flex-direction: column;
    }

    .nav-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 10px 16px;
      margin: 3px 12px;
      border-radius: 8px;
      color: #a1a1aa;
      text-decoration: none;
      font-size: 13px;
      font-weight: 500;
      transition: all 0.2s ease;
      position: relative;
    }

    .nav-icon {
      flex-shrink: 0;
      width: 20px;
      height: 20px;
    }

    .nav-item:hover {
      background-color: #1a1a1e;
      color: #f4f4f5;
    }

    .nav-item.active {
      background-color: #c91818;
      color: #ffffff;
      font-weight: 600;
    }

    .nav-section-title {
      font-size: 9px;
      font-weight: 700;
      color: #52525b;
      letter-spacing: 1.2px;
      margin: 22px 24px 8px 24px;
    }

    .sidebar-bottom {
      display: flex;
      flex-direction: column;
      padding-bottom: 24px;
    }

    .user-profile {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 16px 24px;
      border-top: 1px solid #1f1f23;
    }

    .user-avatar {
      width: 38px;
      height: 38px;
      border-radius: 50%;
      background-color: #c91818;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: 600;
      font-size: 16px;
    }

    .user-info {
      display: flex;
      flex-direction: column;
    }

    .user-name {
      color: #ffffff;
      font-size: 13px;
      font-weight: 600;
    }

    .user-role {
      color: #71717a;
      font-size: 11px;
      margin-top: 2px;
    }

    .logout-btn {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      background-color: #8b1a1a;
      border: none;
      border-radius: 10px;
      color: white;
      font-size: 13px;
      font-weight: 600;
      height: 46px;
      cursor: pointer;
      margin: 12px 16px;
      transition: all 0.2s ease;
      text-decoration: none;
      width: calc(100% - 32px);
    }

    .logout-btn:hover {
      background-color: #a81c1c;
    }

    /* ========== STATISTICS PANEL ========== */
    .stats-panel {
      flex: 0 0 260px;
      width: 260px;
      background-color: #f8f7f5;
      border-right: 1px solid #ededeb;
      padding: 28px 20px;
      display: flex;
      flex-direction: column;
      overflow-y: auto;
      flex-shrink: 0;
    }

    .stats-header {
      margin-bottom: 20px;
    }

    .stats-subtitle {
      font-size: 11px;
      color: #7c7c7f;
      font-weight: 500;
    }

    .stats-title {
      font-size: 22px;
      font-weight: 700;
      color: #1e1e21;
      margin-top: 2px;
    }

    .stats-cards {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .stat-card {
      background-color: #ffffff;
      border: 1px solid #ededeb;
      border-radius: 10px;
      padding: 12px;
    }

    .stat-card .card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 8px;
    }

    .stat-card .indicator {
      width: 10px;
      height: 10px;
      border-radius: 3px;
    }

    .indicator.blue { background-color: #eff6ff; border: 1px solid #bfdbfe; }
    .indicator.red { background-color: #fee2e2; border: 1px solid #fecaca; }
    .indicator.green { background-color: #dcfce7; border: 1px solid #bbf7d0; }
    .indicator.purple { background-color: #f3e8ff; border: 1px solid #e9d5ff; }

    .stat-card .badge {
      font-size: 8px;
      font-weight: 600;
      padding: 2px 6px;
      border-radius: 10px;
    }

    .badge.blue { background-color: #eff6ff; color: #1d4ed8; }
    .badge.red { background-color: #fee2e2; color: #b91c1c; }
    .badge.green { background-color: #dcfce7; color: #15803d; }
    .badge.purple { background-color: #f3e8ff; color: #7e22ce; }

    .card-number {
      font-size: 24px;
      font-weight: 700;
      color: #0d121c;
    }

    .card-label {
      font-size: 10px;
      color: #6b7280;
      font-weight: 500;
    }

    .card-trend {
      font-size: 9px;
      font-weight: 600;
    }
    .card-trend.green { color: #10b981; }
    .card-trend.red { color: #ef4444; }

    /* ========== MAIN CONTENT ========== */
    .main-content {
      flex: 1;
      background-color: #ffffff;
      padding: 28px 36px;
      overflow-y: auto;
      display: flex;
      flex-direction: column;
    }

    /* Header avec paramètres et recherche */
    .main-header {
      display: flex;
      justify-content: flex-end;
      align-items: center;
      margin-bottom: 24px;
      gap: 16px;
      position: relative;
    }

    .search-wrapper {
      position: relative;
      width: 260px;
    }

    .search-wrapper input {
      width: 100%;
      height: 36px;
      padding: 0 38px 0 14px;
      border: 1.5px solid #e5e7eb;
      border-radius: 20px;
      font-size: 12px;
      outline: none;
    }

    .search-wrapper input:focus {
      border-color: #c91818;
    }

    .search-icon {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: #9ca3af;
      pointer-events: none;
    }

    /* Settings button with dropdown */
    .settings-container {
      position: relative;
    }

    .settings-btn {
      background: none;
      border: none;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      width: 36px;
      height: 36px;
      border-radius: 50%;
      transition: background-color 0.2s;
    }

    .settings-btn:hover {
      background-color: #f3f4f6;
    }

    .settings-dropdown {
      position: absolute;
      top: 45px;
      right: 0;
      background: white;
      border-radius: 12px;
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
      width: 220px;
      z-index: 100;
      display: none;
      border: 1px solid #e5e7eb;
      overflow: hidden;
    }

    .settings-dropdown.show {
      display: block;
    }

    .dropdown-section {
      padding: 12px 16px;
      border-bottom: 1px solid #f0f0f0;
    }

    .dropdown-section:last-child {
      border-bottom: none;
    }

    .dropdown-title {
      font-size: 11px;
      font-weight: 600;
      color: #6b7280;
      margin-bottom: 8px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .dropdown-option {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 6px 0;
      cursor: pointer;
      font-size: 12px;
      color: #374151;
    }

    .dropdown-option:hover {
      color: #c91818;
    }

    .color-preview {
      width: 16px;
      height: 16px;
      border-radius: 4px;
    }

    .zoom-control {
      display: flex;
      align-items: center;
      gap: 12px;
      justify-content: space-between;
    }

    .zoom-btn {
      background: #f3f4f6;
      border: none;
      width: 28px;
      height: 28px;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
    }

    .zoom-value {
      font-size: 12px;
      font-weight: 500;
    }

    /* Dashboard sections */
    .dashboard-section {
      margin-bottom: 24px;
    }

    .section-header {
      display: flex;
      justify-content: space-between;
      align-items: baseline;
      margin-bottom: 12px;
    }

    .section-title {
      font-size: 14px;
      font-weight: 700;
      color: #1f2937;
    }

    .section-link {
      font-size: 10px;
      font-weight: 600;
      color: #c91818;
      text-decoration: none;
    }

    /* Media grid */
    .media-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 12px;
    }

    .media-card {
      height: 95px;
      border-radius: 10px;
      padding: 10px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      cursor: pointer;
    }
    .media-card.pink { background-color: #fdf2f2; }
    .media-card.blue { background-color: #eff6ff; }
    .media-card.green { background-color: #f0fdf4; }
    .media-card.purple { background-color: #faf5ff; }

    .media-card-top { display: flex; justify-content: flex-end; }
    .file-badge {
      font-size: 7px;
      font-weight: 700;
      padding: 1px 5px;
      border-radius: 4px;
    }
    .file-badge.red { background-color: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .file-badge.blue { background-color: rgba(59, 130, 246, 0.1); color: #3b82f6; }

    .file-name { font-size: 10px; font-weight: 600; color: #111827; }
    .file-meta { font-size: 8px; color: #6b7280; }

    /* Split row */
    .split-row { display: flex; gap: 16px; margin-bottom: 24px; }
    .split-widget { flex: 1; border: 1px solid #f3f4f6; border-radius: 10px; padding: 14px; }

    .campaign-list { display: flex; flex-direction: column; gap: 10px; }
    .campaign-item { display: flex; align-items: center; gap: 10px; }
    .campaign-thumb { width: 28px; height: 28px; border-radius: 6px; }
    .campaign-thumb.pink { background-color: #fee2e2; }
    .campaign-thumb.blue { background-color: #dbeafe; }
    .campaign-thumb.green { background-color: #dcfce7; }
    .campaign-details { flex: 1; }
    .campaign-name { font-size: 11px; font-weight: 600; }
    .campaign-meta { font-size: 9px; color: #6b7280; }
    .progress-bar-container { width: 70px; height: 4px; background-color: #f3f4f6; border-radius: 2px; overflow: hidden; }
    .progress-bar { height: 100%; border-radius: 2px; }
    .progress-bar.red { background-color: #ef4444; }
    .progress-bar.blue { background-color: #3b82f6; }
    .progress-bar.green { background-color: #16a34a; }

    .screen-list { display: flex; flex-direction: column; gap: 10px; }
    .screen-item { display: flex; align-items: center; justify-content: space-between; font-size: 10px; }
    .status-dot { width: 5px; height: 5px; border-radius: 50%; display: inline-block; margin-right: 6px; }
    .status-dot.green { background-color: #10b981; }
    .status-dot.yellow { background-color: #f59e0b; }
    .status-dot.red { background-color: #ef4444; }
    .screen-status { font-size: 8px; font-weight: 600; padding: 1px 5px; border-radius: 10px; }
    .screen-status.green { background-color: #dcfce7; color: #15803d; }
    .screen-status.yellow { background-color: #fef3c7; color: #b45309; }
    .screen-status.red { background-color: #fee2e2; color: #b91c1c; }

    /* AI Widget */
    .ai-assistant-widget {
      background-color: #141416;
      border-radius: 14px;
      padding: 18px;
      color: #ffffff;
    }
    .ai-title { font-size: 16px; font-weight: 700; }
    .ai-subtitle { font-size: 13px; color: #a1a1aa; margin-top: 2px; }
    .ai-pills { display: flex; flex-wrap: wrap; gap: 6px; margin: 14px 0; }
    .ai-pill {
      background-color: #27272a;
      border: 1px solid #3f3f46;
      border-radius: 16px;
      padding: 4px 10px;
      font-size: 9px;
      cursor: pointer;
      color: white;
    }
    .ai-pill:hover {
      background-color: #723434;
    }
    .ai-input-wrapper { display: flex; gap: 10px; background-color: #27272a; border-radius: 8px; padding: 5px 5px 5px 10px; height: 37px; }
    .ai-input-wrapper input { flex: 1; background: none; border: none; color: white; outline: none; font-size: 10px; }
    .ai-generate-btn { background-color: #c91818; border: none; border-radius: 6px; padding: 0 16px; color: white; cursor: pointer; font-size: 10px; height: 27px; }

    /* Highlight pour recherche */
    .highlight {
      background-color: #c91818 !important;
      color: white !important;
      transition: all 0.2s ease;
    }
  </style>
</head>
<body>
  <div class="dashboard-container">
    
    <!-- LEFT SIDEBAR -->
    <aside class="sidebar">
      <div class="sidebar-top">
        <div class="logo">
          <img src="{{ asset('images/logo.png') }}" alt="Digika CMS" onerror="this.src='https://placehold.co/120x38/ffffff/c91818?text=DigikaCMS'">
        </div>

        <nav class="nav-menu">
          <a href="{{ route('dashboard') }}" class="nav-item active" id="nav-dashboard">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="3" width="7" height="9"></rect>
              <rect x="14" y="3" width="7" height="5"></rect>
              <rect x="14" y="12" width="7" height="9"></rect>
              <rect x="3" y="16" width="7" height="5"></rect>
            </svg>
            <span>Dashboard</span>
          </a>
          
          <a href="{{ route('media.index') }}" class="nav-item" id="nav-media">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
              <line x1="12" y1="11" x2="12" y2="17"></line>
              <line x1="9" y1="14" x2="15" y2="14"></line>
            </svg>
            <span>Médiathèque</span>
          </a>

          <a href="{{ route('campaigns.index') }}" class="nav-item" id="nav-campaigns">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M12 19l7-2V7l-7-2v14z"></path>
              <path d="M12 5L3 9v6l9 4z"></path>
              <line x1="23" y1="12" x2="19" y2="12"></line>
            </svg>
            <span>Campagnes</span>
          </a>

          <div class="nav-section-title">INTELLIGENCE IA</div>

          <a href="{{ route('ai.assistant') }}" class="nav-item" id="nav-ai">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="10" r="3"></circle>
              <path d="M12 2a8 8 0 0 0-8 8c0 4 3 6 8 10 5-4 8-6 8-10a8 8 0 0 0-8-8z"></path>
            </svg>
            <span>Assistant IA</span>
          </a>

          @if(auth()->user() && auth()->user()->isAdmin())
            <div class="nav-section-title">ADMINISTRATION</div>
            <a href="{{ route('settings.index') }}" class="nav-item" id="nav-settings">
              <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="3"></circle>
                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
              </svg>
              <span>Paramètres</span>
            </a>
            <a href="{{ route('profiles.index') }}" class="nav-item" id="nav-profiles">
              <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
              </svg>
              <span>Profils</span>
            </a>
          @endif
        </nav>
      </div>

      <div class="sidebar-bottom">
        <div class="user-profile">
          <div class="user-avatar">{{ auth()->user() ? substr(auth()->user()->name, 0, 1) : 'U' }}</div>
          <div class="user-info">
            <div class="user-name">{{ auth()->user() ? auth()->user()->name : 'Utilisateur' }}</div>
            <div class="user-role">
              @if(auth()->user() && auth()->user()->isAdmin()) Administrateur
              @elseif(auth()->user() && auth()->user()->isMarketing()) Marketing
              @else Visualisateur
              @endif
            </div>
          </div>
        </div>
        
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="logout-btn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
              <polyline points="16 17 21 12 16 7"></polyline>
              <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
            <span>Logout</span>
          </button>
        </form>
      </div>
    </aside>

    <!-- STATISTICS PANEL -->
    <section class="stats-panel">
      <div class="stats-header">
        <span class="stats-subtitle">Tableau de bord</span>
        <h1 class="stats-title">Statistiques</h1>
      </div>

      <div class="stats-cards">
        <div class="stat-card">
          <div class="card-header">
            <div class="indicator blue"></div>
            <span class="badge blue">+12 ce mois</span>
          </div>
          <div class="card-number">{{ $totalMedia ?? 0 }}</div>
          <div class="card-label">Médias en bibliothèque</div>
          <div class="card-trend green">8.4% vs mois dernier</div>
        </div>

        <div class="stat-card">
          <div class="card-header">
            <div class="indicator red"></div>
            <span class="badge red">{{ $activeCampaigns ?? 0 }} actives</span>
          </div>
          <div class="card-number">{{ $totalCampaigns ?? 0 }}</div>
          <div class="card-label">Campagnes créées</div>
          <div class="card-trend green">{{ $newCampaignsThisWeek ?? 0 }} nouvelles cette semaine</div>
        </div>

        <div class="stat-card">
          <div class="card-header">
            <div class="indicator green"></div>
            <span class="badge green">En ligne</span>
          </div>
          <div class="card-number">{{ $connectedScreens ?? 0 }}</div>
          <div class="card-label">Écrans connectés</div>
          <div class="card-trend green">100% opérationnels</div>
        </div>

        <div class="stat-card">
          <div class="card-header">
            <div class="indicator purple"></div>
            <span class="badge purple">Aujourd'hui</span>
          </div>
          <div class="card-number">{{ $iaRequestsToday ?? 0 }}</div>
          <div class="card-label">Requêtes IA utilisées</div>
          <div class="card-trend red">5.1% vs hier</div>
        </div>
      </div>
    </section>

    <!-- MAIN CONTENT -->
    <main class="main-content">
      <div class="main-header">
        <div class="search-wrapper">
          <input type="text" id="searchInput" placeholder="Rechercher (médias, campagnes, écrans)...">
          <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
        </div>
        <div class="settings-container">
          <button class="settings-btn" id="settingsDropdownBtn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="3"></circle>
              <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
            </svg>
          </button>
          <div class="settings-dropdown" id="settingsDropdown">
            <div class="dropdown-section">
              <div class="dropdown-title">🎨 Thème</div>
              <div class="dropdown-option" data-theme="light">☀️ Clair</div>
              <div class="dropdown-option" data-theme="dark">🌙 Sombre</div>
              <div class="dropdown-option" data-theme="system">💻 Système</div>
            </div>
            <div class="dropdown-section">
              <div class="dropdown-title">🔍 Zoom</div>
              <div class="zoom-control">
                <button class="zoom-btn" id="zoomOut">A-</button>
                <span class="zoom-value" id="zoomValue">100%</span>
                <button class="zoom-btn" id="zoomIn">A+</button>
              </div>
            </div>
            <div class="dropdown-section">
              <div class="dropdown-title">🎨 Couleur accent</div>
              <div class="dropdown-option" data-color="red">
                <span class="color-preview" style="background:#c91818;"></span> Rouge (par défaut)
              </div>
              <div class="dropdown-option" data-color="blue">
                <span class="color-preview" style="background:#3b82f6;"></span> Bleu
              </div>
              <div class="dropdown-option" data-color="green">
                <span class="color-preview" style="background:#10b981;"></span> Vert
              </div>
              <div class="dropdown-option" data-color="purple">
                <span class="color-preview" style="background:#8b5cf6;"></span> Violet
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Media -->
      <section class="dashboard-section">
        <div class="section-header">
          <h2 class="section-title">Médiathèque récente</h2>
          <a href="{{ route('media.index') }}" class="section-link">Voir tout</a>
        </div>
        
        <div class="media-grid" id="mediaGrid">
          @forelse(($recentMedia ?? []) as $media)
          <div class="media-card {{ $loop->iteration % 4 == 0 ? 'purple' : ($loop->iteration % 3 == 0 ? 'green' : ($loop->iteration % 2 == 0 ? 'blue' : 'pink')) }}" data-search="{{ strtolower($media->title) }} {{ strtolower($media->type) }}">
            <div class="media-card-top">
              <span class="file-badge {{ $media->type == 'image' ? 'red' : 'blue' }}">{{ strtoupper(substr($media->type, 0, 3)) }}</span>
            </div>
            <div class="media-card-info">
              <div class="file-name">{{ $media->title }}</div>
              <div class="file-meta">{{ $media->type }} - {{ round(($media->size ?? 0) / 1024, 1) }} MB</div>
            </div>
          </div>
          @empty
          <div class="media-card pink"><div class="media-card-info"><div class="file-name">Aucun média</div></div></div>
          @endforelse
        </div>
      </section>

      <!-- Campaigns & Screens -->
      <div class="split-row">
        <section class="split-widget campaigns-widget">
          <div class="section-header">
            <h2 class="section-title">Campagnes actives</h2>
            <a href="{{ route('campaigns.index') }}" class="section-link">Gérer</a>
          </div>
          <div class="campaign-list" id="campaignList">
            @forelse(($activeCampaignsList ?? []) as $campaign)
            <div class="campaign-item" data-search="{{ strtolower($campaign->title) }}">
              <div class="campaign-thumb {{ $loop->iteration % 3 == 0 ? 'green' : ($loop->iteration % 2 == 0 ? 'blue' : 'pink') }}"></div>
              <div class="campaign-details">
                <div class="campaign-name">{{ $campaign->title }}</div>
                <div class="campaign-meta">{{ $campaign->screens_count ?? 0 }} écrans</div>
              </div>
              <div class="progress-bar-container">
                <div class="progress-bar {{ $loop->iteration % 3 == 0 ? 'green' : ($loop->iteration % 2 == 0 ? 'blue' : 'red') }}" style="width: {{ $campaign->progress ?? 0 }}%;"></div>
              </div>
            </div>
            @empty
            <div class="campaign-item"><div class="campaign-details"><div class="campaign-name">Aucune campagne active</div></div></div>
            @endforelse
          </div>
        </section>

        <section class="split-widget screens-widget">
          <div class="section-header">
            <h2 class="section-title">Écrans connectés</h2>
            <a href="{{ route('screens.index') }}" class="section-link">Gérer les écrans</a>
          </div>
          <div class="screen-list" id="screenList">
            @forelse(($connectedScreensList ?? []) as $screen)
            <div class="screen-item" data-search="{{ strtolower($screen->name) }} {{ strtolower($screen->location ?? '') }}">
              <div class="screen-details">
                <div class="screen-name">
                  <span class="status-dot {{ $screen->status == 'online' ? 'green' : ($screen->status == 'idle' ? 'yellow' : 'red') }}"></span>
                  <strong>{{ $screen->name }}</strong>
                </div>
                <div class="screen-meta">{{ $screen->location ?? '' }}</div>
              </div>
              <div class="screen-status {{ $screen->status == 'online' ? 'green' : ($screen->status == 'idle' ? 'yellow' : 'red') }}">
                {{ $screen->status == 'online' ? 'En ligne' : ($screen->status == 'idle' ? 'Idle' : 'Hors ligne') }}
              </div>
            </div>
            @empty
            <div class="screen-item"><div class="screen-details"><div class="screen-name">Aucun écran connecté</div></div></div>
            @endforelse
          </div>
        </section>
      </div>

      <!-- AI Assistant -->
      <section class="ai-assistant-widget">
        <div class="ai-header">
          <h2 class="ai-title">Assistant IA - DigiCMS</h2>
          <p class="ai-subtitle">Générez, optimisez et évaluez vos contenus marketing en quelques secondes</p>
        </div>
        <div class="ai-pills">
          <button class="ai-pill" onclick="document.getElementById('aiPrompt').value='Génère un slogan percutant pour notre nouvelle campagne'">✦ Générer un slogan</button>
          <button class="ai-pill" onclick="document.getElementById('aiPrompt').value='Propose 10 mots-clés stratégiques pour un affichage dynamique'">✦ Proposer des mots-clés</button>
          <button class="ai-pill" onclick="document.getElementById('aiPrompt').value='Évalue la qualité de ce message marketing'">✦ Évaluer mon message</button>
          <button class="ai-pill" onclick="document.getElementById('aiPrompt').value='Donne-moi une idée créative de visuel pour'">✦ Idée de visuel</button>
          <button class="ai-pill" onclick="document.getElementById('aiPrompt').value='Rédige une description attrayante pour'">✦ Description d'affichage</button>
        </div>
        <div class="ai-input-wrapper">
          <input type="text" id="aiPrompt" placeholder="Ex: Génère un slogan pour une promo été sur écran extérieur...">
          <button class="ai-generate-btn" onclick="alert('Génération IA: ' + document.getElementById('aiPrompt').value)">Générer</button>
        </div>
      </section>
    </main>
  </div>

  <script>
    // ========== RECHERCHE INTERNE ==========
    const searchInput = document.getElementById('searchInput');
    const mediaCards = document.querySelectorAll('#mediaGrid .media-card');
    const campaignItems = document.querySelectorAll('#campaignList .campaign-item');
    const screenItems = document.querySelectorAll('#screenList .screen-item');

    function removeHighlights() {
      mediaCards.forEach(card => card.classList.remove('highlight'));
      campaignItems.forEach(item => item.classList.remove('highlight'));
      screenItems.forEach(item => item.classList.remove('highlight'));
    }

    function performSearch() {
      const searchTerm = searchInput.value.trim().toLowerCase();
      removeHighlights();
      
      if (searchTerm === '') return;
      
      // Search in media
      mediaCards.forEach(card => {
        const searchData = card.getAttribute('data-search') || '';
        if (searchData.includes(searchTerm)) {
          card.classList.add('highlight');
        }
      });
      
      // Search in campaigns
      campaignItems.forEach(item => {
        const searchData = item.getAttribute('data-search') || '';
        if (searchData.includes(searchTerm)) {
          item.classList.add('highlight');
        }
      });
      
      // Search in screens
      screenItems.forEach(item => {
        const searchData = item.getAttribute('data-search') || '';
        if (searchData.includes(searchTerm)) {
          item.classList.add('highlight');
        }
      });
    }
    
    searchInput.addEventListener('input', performSearch);
    
    // ========== SETTINGS DROPDOWN ==========
    const dropdownBtn = document.getElementById('settingsDropdownBtn');
    const dropdown = document.getElementById('settingsDropdown');
    
    dropdownBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      dropdown.classList.toggle('show');
    });
    
    document.addEventListener('click', () => {
      dropdown.classList.remove('show');
    });
    
    dropdown.addEventListener('click', (e) => {
      e.stopPropagation();
    });
    
    // Zoom functionality
    let currentZoom = 100;
    const zoomValue = document.getElementById('zoomValue');
    const mainContent = document.querySelector('.main-content');
    
    document.getElementById('zoomIn').addEventListener('click', () => {
      if (currentZoom < 150) {
        currentZoom += 10;
        zoomValue.textContent = currentZoom + '%';
        mainContent.style.zoom = currentZoom / 100;
      }
    });
    
    document.getElementById('zoomOut').addEventListener('click', () => {
      if (currentZoom > 70) {
        currentZoom -= 10;
        zoomValue.textContent = currentZoom + '%';
        mainContent.style.zoom = currentZoom / 100;
      }
    });
    
    // Theme change
    document.querySelectorAll('[data-theme]').forEach(option => {
      option.addEventListener('click', () => {
        const theme = option.getAttribute('data-theme');
        if (theme === 'dark') {
          document.body.style.background = '#1a1a2e';
          document.querySelector('.main-content').style.background = '#1a1a2e';
          document.querySelector('.stats-panel').style.background = '#1f1f2e';
        } else if (theme === 'light') {
          document.body.style.background = '#ffffff';
          document.querySelector('.main-content').style.background = '#ffffff';
          document.querySelector('.stats-panel').style.background = '#f8f7f5';
        }
        dropdown.classList.remove('show');
      });
    });
    
    // Accent color change
    document.querySelectorAll('[data-color]').forEach(option => {
      option.addEventListener('click', () => {
        const color = option.getAttribute('data-color');
        let newColor = '#c91818';
        if (color === 'blue') newColor = '#3b82f6';
        if (color === 'green') newColor = '#10b981';
        if (color === 'purple') newColor = '#8b5cf6';
        
        document.documentElement.style.setProperty('--primary-color', newColor);
        
        document.querySelectorAll('.nav-item.active, .logout-btn, .ai-generate-btn, .section-link, .progress-bar.red, .progress-bar.blue, .progress-bar.green').forEach(el => {
          if (el.classList.contains('nav-item.active')) el.style.backgroundColor = newColor;
          if (el.classList.contains('logout-btn')) el.style.backgroundColor = newColor;
          if (el.classList.contains('ai-generate-btn')) el.style.backgroundColor = newColor;
        });
        dropdown.classList.remove('show');
      });
    });
    
    // Pill buttons for AI
    document.querySelectorAll('.ai-pill').forEach(pill => {
      pill.addEventListener('click', () => {
        document.getElementById('aiPrompt').value = pill.textContent.replace('✦ ', '');
      });
    });
  </script>
</body>
</html>