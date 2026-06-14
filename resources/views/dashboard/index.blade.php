<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Tableau de bord - DigiCMS</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    /* ========================================
       RESET & BASE
    ======================================== */
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body, html { height: 100vh; width: 100vw; overflow: hidden; font-family: 'Poppins', sans-serif; background-color: #ffffff; }
    .dashboard-container { display: flex; height: 100vh; width: 100vw; overflow: hidden; }

    /* ========================================
       SIDEBAR - IDENTIQUE À MÉDIATHÈQUE ET CAMPAGNES
    ======================================== */
    .sidebar { flex: 0 0 240px; width: 240px; background-color: #121214; display: flex; flex-direction: column; justify-content: space-between; border-right: 1px solid #1f1f23; flex-shrink: 0; }
    .sidebar-top { padding-top: 36px; display: flex; flex-direction: column; }
    .sidebar-top .logo { margin-left: 24px; margin-bottom: 40px; }
    .sidebar-top .logo img { height: 58px; width: auto; }
    .nav-menu { display: flex; flex-direction: column; }
    .nav-item { display: flex; align-items: center; gap: 12px; padding: 10px 16px; margin: 3px 12px; border-radius: 8px; color: #a1a1aa; text-decoration: none; font-size: 13px; font-weight: 500; transition: all 0.2s ease; position: relative; }
    .nav-icon { flex-shrink: 0; width: 20px; height: 20px; }
    .nav-item:hover { background-color: #1a1a1e; color: #f4f4f5; }
    .nav-item.active { background-color: #c91818; color: #ffffff; font-weight: 600; }
    .nav-item.active::before { content: ""; position: absolute; left: 10px; top: 50%; transform: translateY(-50%); width: 3px; height: 16px; background-color: #ffffff; border-radius: 2px; }
    .nav-section-title { font-size: 9px; font-weight: 700; color: #52525b; letter-spacing: 1.2px; margin: 22px 24px 8px 24px; }
    .sidebar-bottom { display: flex; flex-direction: column; padding-bottom: 24px; }
    .user-profile { display: flex; align-items: center; gap: 12px; padding: 16px 24px; border-top: 1px solid #1f1f23; }
    .user-avatar { width: 38px; height: 38px; border-radius: 50%; background-color: #c91818; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 16px; }
    .user-info { display: flex; flex-direction: column; }
    .user-name { color: #ffffff; font-size: 13px; font-weight: 600; }
    .user-role { color: #71717a; font-size: 11px; margin-top: 2px; }
    .logout-btn { display: flex; align-items: center; justify-content: center; gap: 10px; background-color: #3b1515; border: 1px solid #5a1e1e; border-radius: 8px; color: #fecaca; font-size: 13px; font-weight: 600; height: 40px; cursor: pointer; margin: 12px 16px 0 16px; width: calc(100% - 32px); }

    /* ========================================
       STATISTICS PANEL
    ======================================== */
    .stats-panel { flex: 0 0 280px; width: 280px; background-color: #f8f7f5; border-right: 1px solid #ededeb; padding: 28px 20px; display: flex; flex-direction: column; overflow-y: auto; flex-shrink: 0; }
    .stats-header { margin-bottom: 16px; }
    .stats-subtitle { font-size: 12px; color: #7c7c7f; font-weight: 500; letter-spacing: 0.5px; }
    .stats-title { font-size: 24px; font-weight: 700; color: #1e1e21; margin-top: 4px; }
    .stats-cards { display: flex; flex-direction: column; gap: 15px; }
    
    .stat-card { background-color: #ffffff; border: 1px solid #ededeb; border-radius: 16px; padding: 18px 16px; transition: all 0.2s ease; box-shadow: 0 1px 2px rgba(0,0,0,0.03); }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 10px 20px -8px rgba(0,0,0,0.1); }
    .stat-card .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
    .stat-card .indicator { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; }
    .indicator.blue { background-color: #eff6ff; color: #2563eb; }
    .indicator.red { background-color: #fee2e2; color: #e02c3a; }
    .indicator.green { background-color: #dcfce7; color: #059669; }
    .indicator.purple { background-color: #f3e8ff; color: #7c3aed; }
    .stat-card .badge { font-size: 9px; font-weight: 600; padding: 3px 8px; border-radius: 12px; }
    .badge.blue { background-color: #eff6ff; color: #1d4ed8; }
    .badge.red { background-color: #fee2e2; color: #b91c1c; }
    .badge.green { background-color: #dcfce7; color: #15803d; }
    .badge.purple { background-color: #f3e8ff; color: #7e22ce; }
    .card-number { font-size: 28px; font-weight: 700; color: #0d121c; margin-bottom: 4px; }
    .card-label { font-size: 11px; color: #6b7280; font-weight: 500; }
    .card-trend { font-size: 10px; font-weight: 600; margin-top: 8px; padding-top: 8px; border-top: 1px solid #f0f0f0; }
    .card-trend.green { color: #10b981; }
    .card-trend.red { color: #ef4444; }

    /* ========================================
       MAIN CONTENT
    ======================================== */
    .main-content { flex: 1; background-color: #ffffff; padding: 28px 36px; overflow-y: auto; display: flex; flex-direction: column; }
    .main-header { display: flex; justify-content: flex-end; align-items: center; margin-bottom: 28px; padding-bottom: 15px; border-bottom: 2px solid #eef2f6; gap: 16px; }
    .search-wrapper { position: relative; width: 280px; }
    .search-wrapper input { width: 100%; height: 38px; padding: 0 38px 0 16px; border: 1.5px solid #e5e7eb; border-radius: 30px; font-size: 13px; outline: none; transition: all 0.2s; }
    .search-wrapper input:focus { border-color: #c91818; box-shadow: 0 0 0 3px rgba(201,24,24,0.1); }
    .search-icon { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: #9ca3af; pointer-events: none; font-size: 14px; }
    .settings-container { position: relative; }
    .settings-btn { background: none; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; width: 38px; height: 38px; border-radius: 50%; transition: background-color 0.2s; font-size: 18px; color: #4b5563; }
    .settings-btn:hover { background-color: #f3f4f6; }
    .settings-dropdown { position: absolute; top: 45px; right: 0; background: white; border-radius: 12px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); width: 220px; z-index: 100; display: none; border: 1px solid #e5e7eb; overflow: hidden; }
    .settings-dropdown.show { display: block; }
    .dropdown-section { padding: 12px 16px; border-bottom: 1px solid #f0f0f0; }
    .dropdown-section:last-child { border-bottom: none; }
    .dropdown-title { font-size: 11px; font-weight: 600; color: #6b7280; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 8px; }
    .dropdown-option { display: flex; align-items: center; gap: 10px; padding: 6px 0; cursor: pointer; font-size: 12px; color: #374151; transition: all 0.2s; border-radius: 6px; }
    .dropdown-option:hover { color: #c91818; padding-left: 4px; }
    .zoom-control { display: flex; align-items: center; gap: 12px; justify-content: space-between; }
    .zoom-btn { background: #f3f4f6; border: none; width: 28px; height: 28px; border-radius: 6px; cursor: pointer; font-weight: bold; transition: all 0.2s; }
    .zoom-btn:hover { background: #e5e7eb; }
    .zoom-value { font-size: 12px; font-weight: 500; }

    /* ========================================
       SECTIONS HEADER
    ======================================== */
    .dashboard-section { margin-bottom: 32px; }
    .section-header { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 16px; }
    .section-title { font-size: 18px; font-weight: 700; color: #1f2937; }
    .section-link { font-size: 12px; font-weight: 600; color: #c91818; text-decoration: none; transition: all 0.2s; }
    .section-link:hover { text-decoration: underline; }

    /* ========================================
       MEDIA WIDGET
    ======================================== */
    .media-widget { background: white; border-radius: 20px; padding: 20px; margin-bottom: 32px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); transition: all 0.2s; border: 1px solid #eef2f6; }
    .media-widget:hover { box-shadow: 0 8px 20px -8px rgba(0,0,0,0.08); transform: translateY(-2px); }
    .media-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 16px; margin-top: 16px; }
    @media (max-width: 1200px) { .media-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 800px) { .media-grid { grid-template-columns: repeat(2, 1fr); } }
    
    .media-card { border-radius: 12px; overflow: hidden; transition: all 0.2s ease; cursor: pointer; background: white; border: 1px solid #eef2f6; position: relative; }
    .media-card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px -8px rgba(0,0,0,0.12); }
    .media-card:hover .media-zoom-btn { opacity: 1; }
    .media-thumb { height: 120px; background-size: cover; background-position: center; background-color: #f3f4f6; position: relative; }
    .media-thumb .type-badge { position: absolute; top: 8px; left: 8px; font-size: 9px; font-weight: 700; padding: 2px 8px; border-radius: 20px; background: rgba(0,0,0,0.7); color: white; z-index: 2; }
    .media-thumb .type-badge.image { background: rgba(239,68,68,0.9); }
    .media-thumb .type-badge.video { background: rgba(59,130,246,0.9); }
    .media-thumb .type-badge.pdf { background: rgba(249,115,22,0.9); }
    .media-thumb .type-badge.document { background: rgba(34,197,94,0.9); }
    .media-zoom-btn { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 36px; height: 36px; background: rgba(0,0,0,0.7); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 16px; opacity: 0; transition: all 0.2s ease; z-index: 3; cursor: pointer; backdrop-filter: blur(2px); }
    .media-zoom-btn:hover { background: #c91818; transform: translate(-50%, -50%) scale(1.1); }
    .media-info { padding: 12px; }
    .media-title { font-size: 12px; font-weight: 600; color: #1f2937; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .media-meta { font-size: 10px; color: #9ca3af; margin-top: 4px; display: flex; justify-content: space-between; }

    /* ========================================
       CAMPAIGNS WIDGET - FULL WIDTH
    ======================================== */
    .campaigns-widget { background: white; border-radius: 20px; padding: 20px; margin-bottom: 32px; transition: all 0.2s; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #eef2f6; width: 100%; }
    .campaigns-widget:hover { box-shadow: 0 8px 20px -8px rgba(0,0,0,0.08); transform: translateY(-2px); }
    .campaign-list { display: flex; flex-direction: column; gap: 16px; }
    .campaign-item { display: flex; align-items: center; justify-content: space-between; padding: 16px 0; border-bottom: 1px solid #f0f0f0; cursor: pointer; transition: all 0.2s; }
    .campaign-item:hover { background-color: #fef2f2; margin: 0 -8px; padding: 16px 8px; border-radius: 12px; }
    .campaign-item:last-child { border-bottom: none; }
    .campaign-info { flex: 1; }
    .campaign-name { font-size: 15px; font-weight: 700; color: #1f2937; margin-bottom: 6px; }
    .campaign-detail { font-size: 12px; color: #9ca3af; display: flex; align-items: center; gap: 20px; flex-wrap: wrap; }
    .campaign-detail span { display: flex; align-items: center; gap: 6px; }
    .campaign-detail i { font-size: 11px; color: #c91818; width: 14px; }
    .campaign-progress { display: flex; align-items: center; gap: 14px; }
    .progress-percent { font-size: 16px; font-weight: 700; color: #1f2937; min-width: 45px; text-align: right; }
    .progress-bar-container { width: 100px; height: 6px; background-color: #f0f2f7; border-radius: 10px; overflow: hidden; }
    .progress-bar { height: 100%; border-radius: 10px; transition: width 0.3s ease; }
    .progress-bar.red { background-color: #ef4444; }
    .progress-bar.blue { background-color: #3b82f6; }
    .progress-bar.green { background-color: #16a34a; }

    /* ========================================
       AI WIDGET
    ======================================== */
    .ai-assistant-widget { background: linear-gradient(135deg, #121214 0%, #1a1a1e 100%); border-radius: 20px; padding: 28px; color: #ffffff; transition: all 0.2s; box-shadow: 0 8px 20px -8px rgba(0,0,0,0.15); margin-top: 8px; }
    .ai-title { font-size: 20px; font-weight: 700; }
    .ai-subtitle { font-size: 13px; color: #a1a1aa; margin-top: 6px; }
    .ai-pills { display: flex; flex-wrap: wrap; gap: 12px; margin: 24px 0; }
    .ai-pill { background-color: #27272a; border: 1px solid #3f3f46; border-radius: 30px; padding: 8px 16px; font-size: 12px; cursor: pointer; color: white; transition: all 0.2s; }
    .ai-pill:hover { background-color: #c91818; border-color: #c91818; transform: translateY(-1px); }
    .ai-input-wrapper { display: flex; gap: 12px; background-color: #27272a; border-radius: 14px; padding: 6px 6px 6px 18px; align-items: center; }
    .ai-input-wrapper input { flex: 1; background: none; border: none; color: white; outline: none; font-size: 13px; padding: 12px 0; }
    .ai-input-wrapper input::placeholder { color: #71717a; }
    .ai-generate-btn { background-color: #c91818; border: none; border-radius: 12px; padding: 10px 28px; color: white; cursor: pointer; font-size: 13px; font-weight: 600; transition: all 0.2s; }
    .ai-generate-btn:hover { background-color: #a81414; transform: translateY(-1px); }

    /* ========================================
       MODAL PREVIEW
    ======================================== */
    .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); backdrop-filter: blur(8px); align-items: center; justify-content: center; z-index: 2000; }
    .modal-content { background: white; border-radius: 24px; padding: 24px; max-width: 90%; max-height: 90%; overflow: auto; text-align: center; animation: modalFadeIn 0.2s ease; }
    @keyframes modalFadeIn { from { opacity: 0; transform: scale(0.96); } to { opacity: 1; transform: scale(1); } }
    .modal-content img, .modal-content video { max-width: 100%; max-height: 70vh; border-radius: 12px; }
    .modal-content .modal-close { margin-top: 20px; padding: 8px 24px; background: #c91818; color: white; border: none; border-radius: 30px; cursor: pointer; font-weight: 600; }
    .modal-content .modal-close:hover { background: #a81414; }

    /* ========================================
       TOAST & UTILITIES
    ======================================== */
    .toast-notification { position: fixed; bottom: 30px; right: 30px; background: #1f2937; color: white; padding: 12px 20px; border-radius: 40px; z-index: 2000; animation: slideIn 0.3s ease; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); font-size: 13px; display: flex; align-items: center; gap: 8px; }
    .toast-notification.success { background: #10b981; }
    .toast-notification.error { background: #ef4444; }
    @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    .highlight { background-color: #fef2f2 !important; border-color: #c91818 !important; transition: all 0.2s ease; }
  </style>
</head>
<body>
<div class="dashboard-container">
  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sidebar-top">
      <div class="logo"><img src="{{ asset('images/logo.png') }}" alt="Digika CMS" onerror="this.src='https://placehold.co/120x38/ffffff/c91818?text=DigikaCMS'"></div>
      <nav class="nav-menu">
        <a href="{{ route('dashboard') }}" class="nav-item active">
          <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="9"></rect><rect x="14" y="3" width="7" height="5"></rect><rect x="14" y="12" width="7" height="9"></rect><rect x="3" y="16" width="7" height="5"></rect></svg>
          <span>Dashboard</span>
        </a>
        <a href="{{ route('media.index') }}" class="nav-item" id="nav-media">
          <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path><line x1="12" y1="11" x2="12" y2="17"></line><line x1="9" y1="14" x2="15" y2="14"></line></svg>
          <span>Médiathèque</span>
        </a>
        <a href="{{ route('campaigns.index') }}" class="nav-item" id="nav-campaigns">
          <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19l7-2V7l-7-2v14z"></path><path d="M12 5L3 9v6l9 4z"></path><line x1="23" y1="12" x2="19" y2="12"></line></svg>
          <span>Campagnes</span>
        </a>
        <div class="nav-section-title">INTELLIGENCE IA</div>
        <a href="#" class="nav-item" onclick="showToast('Assistant IA bientôt disponible', 'info'); return false;">
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
              @elseif(auth()->user()->role == 'marketing') Marketing Manager
              @elseif(auth()->user()->role == 'viewer') Visualisateur
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

  <!-- STATISTICS PANEL -->
  <section class="stats-panel">
    <div class="stats-header"><span class="stats-subtitle">Tableau de bord</span><h1 class="stats-title">Statistiques</h1></div>
    <div class="stats-cards">
      <div class="stat-card">
        <div class="card-header"><div class="indicator blue"><i class="fas fa-images"></i></div><span class="badge blue">+{{ $newMediaThisMonth ?? 0 }} ce mois</span></div>
        <div class="card-number">{{ $totalMedia ?? 0 }}</div>
        <div class="card-label">Médias en bibliothèque</div>
        <div class="card-trend green"><i class="fas fa-arrow-up"></i> {{ $mediaGrowth ?? 0 }}% vs mois dernier</div>
      </div>
      <div class="stat-card">
        <div class="card-header"><div class="indicator red"><i class="fas fa-bullhorn"></i></div><span class="badge red">{{ $activeCampaigns ?? 0 }} actives</span></div>
        <div class="card-number">{{ $totalCampaigns ?? 0 }}</div>
        <div class="card-label">Campagnes créées</div>
        <div class="card-trend green"><i class="fas fa-plus"></i> {{ $newCampaignsThisWeek ?? 0 }} nouvelles cette semaine</div>
      </div>
      <div class="stat-card">
        <div class="card-header"><div class="indicator purple"><i class="fas fa-robot"></i></div><span class="badge purple">Aujourd'hui</span></div>
        <div class="card-number">{{ $iaRequestsToday ?? 0 }}</div>
        <div class="card-label">Requêtes IA utilisées</div>
        <div class="card-trend red"><i class="fas fa-chart-line"></i> {{ $iaGrowth ?? 0 }}% vs hier</div>
      </div>
    </div>
  </section>

  <!-- MAIN CONTENT -->
  <main class="main-content" id="mainContent">
    <div class="main-header">
      <div class="search-wrapper"><input type="text" id="globalSearch" placeholder="Rechercher (médias, campagnes)..."><i class="fas fa-search search-icon"></i></div>
      <div class="settings-container">
        <button class="settings-btn" id="settingsDropdownBtn"><i class="fas fa-sliders-h"></i></button>
        <div class="settings-dropdown" id="settingsDropdown">
          <div class="dropdown-section"><div class="dropdown-title"><i class="fas fa-palette"></i> Thème</div><div class="dropdown-option" data-theme="light"><i class="fas fa-sun"></i> Clair</div><div class="dropdown-option" data-theme="dark"><i class="fas fa-moon"></i> Sombre</div></div>
          <div class="dropdown-section"><div class="dropdown-title"><i class="fas fa-search-plus"></i> Zoom</div><div class="zoom-control"><button class="zoom-btn" id="zoomOut">A-</button><span class="zoom-value" id="zoomValue">100%</span><button class="zoom-btn" id="zoomIn">A+</button></div></div>
        </div>
      </div>
    </div>

    <!-- Médiathèque récente -->
    <div class="media-widget">
      <div class="section-header"><h2 class="section-title">Médiathèque récente</h2><a href="{{ route('media.index') }}" class="section-link">Voir tout <i class="fas fa-arrow-right"></i></a></div>
      <div class="media-grid" id="mediaGrid">
        @forelse(($recentMedia ?? []) as $media)
        <div class="media-card" data-media-id="{{ $media->id }}" data-media-type="{{ $media->type }}" data-media-url="{{ asset('storage/' . $media->file_path) }}" data-media-title="{{ $media->title }}" data-search="{{ strtolower($media->title) }} {{ strtolower($media->type) }}">
          <div class="media-thumb" style="background-image: url('{{ $media->type === 'image' ? asset('storage/' . $media->file_path) : '' }}'); background-size: cover;">
            @if($media->type !== 'image')
            <div style="display:flex;align-items:center;justify-content:center;height:100%;background:#f3f4f6;"><i class="fas {{ $media->type === 'video' ? 'fa-video' : ($media->type === 'pdf' ? 'fa-file-pdf' : 'fa-file-alt') }}" style="font-size:32px;color:#9ca3af;"></i></div>
            @endif
            <span class="type-badge {{ $media->type }}">{{ strtoupper(substr($media->type, 0, 4)) }}</span>
            <div class="media-zoom-btn" onclick="event.stopPropagation(); previewMedia('{{ asset('storage/' . $media->file_path) }}', '{{ $media->type }}')"><i class="fas fa-search-plus"></i></div>
          </div>
          <div class="media-info">
            <div class="media-title">{{ $media->title }}</div>
            <div class="media-meta"><span>{{ $media->type }}</span><span>{{ round(($media->size ?? 0) / 1024 / 1024, 1) }} MB</span></div>
          </div>
        </div>
        @empty
        <div class="media-card"><div class="media-info"><div class="media-title">Aucun média</div></div></div>
        @endforelse
      </div>
    </div>

    <!-- Campagnes actives -->
    <div class="campaigns-widget">
      <div class="section-header"><h2 class="section-title">Campagnes actives</h2><a href="{{ route('campaigns.index') }}" class="section-link">Voir tout <i class="fas fa-arrow-right"></i></a></div>
      <div class="campaign-list" id="campaignList">
        @forelse(($activeCampaignsList ?? []) as $campaign)
        <div class="campaign-item" data-campaign-id="{{ $campaign->id }}" data-search="{{ strtolower($campaign->title) }} {{ strtolower($campaign->description ?? '') }}">
          <div class="campaign-info">
            <div class="campaign-name">{{ $campaign->title }}</div>
            <div class="campaign-detail">
              <span><i class="fas fa-images"></i> {{ $campaign->media_count ?? 0 }} médias</span>
              <span><i class="fas fa-calendar-alt"></i> Du {{ \Carbon\Carbon::parse($campaign->start_date)->translatedFormat('d M Y') ?? '—' }}</span>
              <span><i class="fas fa-calendar-check"></i> Au {{ \Carbon\Carbon::parse($campaign->end_date)->translatedFormat('d M Y') ?? '—' }}</span>
            </div>
          </div>
          <div class="campaign-progress">
            <div class="progress-bar-container"><div class="progress-bar {{ $loop->index % 3 == 0 ? 'red' : ($loop->index % 3 == 1 ? 'blue' : 'green') }}" style="width: {{ $campaign->progress ?? 0 }}%;"></div></div>
            <span class="progress-percent">{{ $campaign->progress ?? 0 }}%</span>
          </div>
        </div>
        @empty
        <div class="campaign-item"><div class="campaign-info"><div class="campaign-name">Aucune campagne active</div></div></div>
        @endforelse
      </div>
    </div>

    <!-- Assistant IA -->
    <div class="ai-assistant-widget">
      <div class="ai-header"><h2 class="ai-title">Assistant IA - DigiCMS</h2><p class="ai-subtitle">Générez, optimisez et évaluez vos contenus marketing en quelques secondes</p></div>
      <div class="ai-pills">
        <button class="ai-pill" onclick="document.getElementById('aiPrompt').value='Génère un slogan percutant pour notre nouvelle campagne'"><i class="fas fa-magic"></i> Générer un slogan</button>
        <button class="ai-pill" onclick="document.getElementById('aiPrompt').value='Propose 10 mots-clés stratégiques pour un affichage dynamique'"><i class="fas fa-key"></i> Proposer des mots-clés</button>
        <button class="ai-pill" onclick="document.getElementById('aiPrompt').value='Évalue la qualité de ce message marketing'"><i class="fas fa-star"></i> Évaluer mon message</button>
        <button class="ai-pill" onclick="document.getElementById('aiPrompt').value='Donne-moi une idée créative de visuel pour'"><i class="fas fa-lightbulb"></i> Idée de visuel</button>
        <button class="ai-pill" onclick="document.getElementById('aiPrompt').value='Rédige une description attrayante pour'"><i class="fas fa-pen-fancy"></i> Description d'affichage</button>
      </div>
      <div class="ai-input-wrapper">
        <input type="text" id="aiPrompt" placeholder="Ex: Génère un slogan pour une promo été sur écran extérieur...">
        <button class="ai-generate-btn" onclick="showToast('Demande envoyée à l\'IA', 'success')">Générer <i class="fas fa-paper-plane"></i></button>
      </div>
    </div>
  </main>
</div>

<!-- Modal Preview -->
<div id="previewModal" class="modal">
  <div class="modal-content">
    <div id="previewContent"></div>
    <button class="modal-close" onclick="closePreviewModal()">Fermer</button>
  </div>
</div>

<script>
  function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast-notification ${type}`;
    toast.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-info-circle'}"></i> ${message}`;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
  }

  // Fonction d'aperçu des médias (agrandissement)
  function previewMedia(url, type) {
    const previewDiv = document.getElementById('previewContent');
    if (type === 'image') {
      previewDiv.innerHTML = `<img src="${url}" alt="Aperçu">`;
    } else if (type === 'video') {
      previewDiv.innerHTML = `<video controls src="${url}" style="max-width:100%; max-height:70vh;"></video>`;
    } else if (type === 'pdf') {
      previewDiv.innerHTML = `<div style="padding:40px;text-align:center;"><i class="fas fa-file-pdf" style="font-size:80px;color:#ef4444;"></i><p style="margin-top:16px;">Fichier PDF</p><a href="${url}" target="_blank" style="display:inline-block;margin-top:16px;padding:8px 20px;background:#c91818;color:white;border-radius:30px;text-decoration:none;"><i class="fas fa-external-link-alt"></i> Ouvrir le PDF</a></div>`;
    } else {
      previewDiv.innerHTML = `<div style="padding:40px;text-align:center;"><i class="fas fa-file-alt" style="font-size:80px;color:#64748b;"></i><p style="margin-top:16px;">Document</p><a href="${url}" download style="display:inline-block;margin-top:16px;padding:8px 20px;background:#c91818;color:white;border-radius:30px;text-decoration:none;"><i class="fas fa-download"></i> Télécharger</a></div>`;
    }
    document.getElementById('previewModal').style.display = 'flex';
  }

  function closePreviewModal() {
    document.getElementById('previewModal').style.display = 'none';
  }

  // Double-clic sur média -> redirection vers médiathèque (ou vers le média spécifique)
  document.querySelectorAll('.media-card').forEach(card => {
    card.addEventListener('dblclick', function(e) {
      const mediaId = this.getAttribute('data-media-id');
      if (mediaId) {
        window.location.href = "{{ route('media.index') }}?highlight=" + mediaId;
      } else {
        window.location.href = "{{ route('media.index') }}";
      }
    });
  });

  // Double-clic sur campagne -> redirection vers campagnes
  document.querySelectorAll('.campaign-item').forEach(item => {
    item.addEventListener('dblclick', function(e) {
      window.location.href = "{{ route('campaigns.index') }}";
    });
  });

  // Recherche globale
  const searchInput = document.getElementById('globalSearch');
  const mediaCards = document.querySelectorAll('#mediaGrid .media-card');
  const campaignItems = document.querySelectorAll('#campaignList .campaign-item');

  function applySearchHighlight() {
    const searchTerm = searchInput.value.trim().toLowerCase();
    mediaCards.forEach(card => {
      const searchData = card.getAttribute('data-search') || '';
      if (searchTerm === '') {
        card.style.opacity = '1';
        card.classList.remove('highlight');
      } else if (searchData.includes(searchTerm)) {
        card.style.opacity = '1';
        card.classList.add('highlight');
      } else {
        card.style.opacity = '0.4';
        card.classList.remove('highlight');
      }
    });
    campaignItems.forEach(item => {
      const searchData = item.getAttribute('data-search') || '';
      if (searchTerm === '') {
        item.style.opacity = '1';
        item.classList.remove('highlight');
      } else if (searchData.includes(searchTerm)) {
        item.style.opacity = '1';
        item.classList.add('highlight');
      } else {
        item.style.opacity = '0.4';
        item.classList.remove('highlight');
      }
    });
  }
  searchInput.addEventListener('input', applySearchHighlight);

  // Settings dropdown
  const dropdownBtn = document.getElementById('settingsDropdownBtn');
  const dropdown = document.getElementById('settingsDropdown');
  dropdownBtn.addEventListener('click', (e) => { e.stopPropagation(); dropdown.classList.toggle('show'); });
  document.addEventListener('click', () => dropdown.classList.remove('show'));

  // Zoom
  let zoom = 100; const zoomSpan = document.getElementById('zoomValue'), mainDiv = document.getElementById('mainContent');
  document.getElementById('zoomIn').addEventListener('click', () => { if(zoom < 150) { zoom += 10; zoomSpan.innerText = zoom+'%'; mainDiv.style.zoom = zoom/100; } });
  document.getElementById('zoomOut').addEventListener('click', () => { if(zoom > 70) { zoom -= 10; zoomSpan.innerText = zoom+'%'; mainDiv.style.zoom = zoom/100; } });

  // Theme
  document.querySelectorAll('[data-theme]').forEach(opt => {
    opt.addEventListener('click', () => {
      const theme = opt.dataset.theme;
      if(theme === 'dark') { 
        mainDiv.style.backgroundColor = '#1e293b'; 
        mainDiv.style.color = '#e2e8f0'; 
        document.querySelectorAll('.stat-card, .campaigns-widget, .media-widget, .media-card, .modal-content').forEach(el => { if(el) el.style.backgroundColor = '#2d3a4e'; }); 
        document.querySelectorAll('.media-card, .campaigns-widget, .media-widget, .stat-card').forEach(el => { if(el) el.style.borderColor = '#4a5568'; }); 
      } else { 
        mainDiv.style.backgroundColor = '#fff'; 
        mainDiv.style.color = '#000'; 
        document.querySelectorAll('.stat-card, .campaigns-widget, .media-widget, .media-card, .modal-content').forEach(el => { if(el) el.style.backgroundColor = 'white'; }); 
        document.querySelectorAll('.media-card, .campaigns-widget, .media-widget, .stat-card').forEach(el => { if(el) el.style.borderColor = '#eef2f6'; }); 
      }
      dropdown.classList.remove('show');
    });
  });

  // AI pills
  document.querySelectorAll('.ai-pill').forEach(pill => {
    pill.addEventListener('click', () => { 
      const text = pill.textContent.replace(/[✦⭐]/g, '').trim();
      document.getElementById('aiPrompt').value = text; 
    });
  });

  // Fermer modal avec Echap
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      closePreviewModal();
    }
  });
</script>
</body>
</html>