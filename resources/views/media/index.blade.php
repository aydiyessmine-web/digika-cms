<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Médiathèque - DigiCMS</title>
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
       SIDEBAR
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
       MAIN CONTENT
    ======================================== */
    .main-content { flex: 1; background-color: #ffffff; padding: 28px 36px; overflow-y: auto; display: flex; flex-direction: column; }
    .main-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #eef2f6; }
    .header-page-title { font-size: 18px; font-weight: 600; color: #64748b; }
    .header-actions { display: flex; align-items: center; gap: 16px; }
    
    /* Search Wrapper */
    .search-wrapper { position: relative; width: 260px; }
    .search-wrapper input { width: 100%; height: 36px; padding: 0 38px 0 14px; border: 1.5px solid #e5e7eb; border-radius: 20px; font-size: 12px; outline: none; transition: all 0.2s; }
    .search-wrapper input:focus { border-color: #c91818; box-shadow: 0 0 0 3px rgba(201,24,24,0.1); }
    .search-icon { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #9ca3af; pointer-events: none; font-size: 14px; }
    
    /* Settings Dropdown */
    .settings-container { position: relative; }
    .settings-btn { background: none; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 50%; transition: background-color 0.2s; color: #4b5563; font-size: 18px; }
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

    /* Library Header */
    .library-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; }
    .library-title { font-size: 23px; font-weight: 700; color: #0f172a; }
    .library-subtitle { font-size: 12px; color: #64748b; }
    .import-btn { background-color: #c91818; color: #ffffff; border: none; border-radius: 8px; padding: 10px 20px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 8px; }
    .import-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(201,24,24,0.3); }

    /* Stats Cards */
    .summary-grid { display: grid; grid-template-columns: repeat(6, 1fr); gap: 16px; margin-bottom: 24px; }
    .summary-card { background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px 12px; display: flex; align-items: center; gap: 12px; transition: all 0.2s ease; box-shadow: 0 1px 2px rgba(0,0,0,0.03); }
    .summary-card:hover { transform: translateY(-2px); box-shadow: 0 10px 20px -8px rgba(0,0,0,0.1); }
    .summary-icon { width: 38px; height: 38px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 18px; }
    .summary-icon.pink { background-color: #fdf2f2; color: #ef4444; }
    .summary-icon.blue { background-color: #eff6ff; color: #3b82f6; }
    .summary-icon.purple { background-color: #faf5ff; color: #a855f7; }
    .summary-icon.green { background-color: #f0fdf4; color: #22c55e; }
    .summary-icon.orange { background-color: #fff7ed; color: #f97316; }
    .summary-icon.slate { background-color: #f1f5f9; color: #64748b; }
    .summary-info { display: flex; flex-direction: column; }
    .summary-value { font-size: 18px; font-weight: 700; }
    .summary-label { font-size: 9px; color: #64748b; margin-top: 2px; }

    /* Upload Zone */
    .upload-zone { background-color: #fcfcfd; border: 2px dashed #cbd5e1; border-radius: 20px; padding: 30px; text-align: center; margin-bottom: 24px; cursor: pointer; transition: all 0.2s ease; }
    .upload-zone:hover { background-color: #f8fafc; border-color: #c91818; transform: translateY(-2px); }
    .upload-faint-icon { width: 48px; height: 48px; border-radius: 50%; background-color: #fef2f2; display: flex; align-items: center; justify-content: center; color: #ef4444; margin: 0 auto 8px; font-size: 24px; }
    .upload-text { font-size: 14px; font-weight: 600; color: #334155; }
    .upload-text span { color: #c91818; cursor: pointer; }
    .upload-subtext { font-size: 11px; color: #64748b; margin-top: 6px; margin-bottom: 16px; }
    .browse-btn { background-color: #c91818; color: #ffffff; border: none; border-radius: 8px; padding: 10px 24px; font-size: 12px; font-weight: 600; cursor: pointer; margin-top: 8px; transition: all 0.2s; }
    .browse-btn:hover { background-color: #b01414; transform: translateY(-1px); }

    /* Filters */
    .filters-toolbar { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; flex-wrap: wrap; padding-bottom: 15px; border-bottom: 1px solid #eef2f6; }
    .filter-search-wrapper { flex: 1; }
    .filter-search-wrapper input { width: 100%; height: 36px; padding: 0 16px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 12px; transition: all 0.2s; }
    .filter-search-wrapper input:focus { outline: none; border-color: #c91818; box-shadow: 0 0 0 3px rgba(201,24,24,0.1); }
    .filter-select-wrapper { position: relative; }
    .filter-select-wrapper::after { content: "▼"; font-size: 7px; color: #64748b; position: absolute; right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; }
    .filter-select-wrapper select { height: 36px; padding: 0 28px 0 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 11px; appearance: none; background: white; cursor: pointer; transition: all 0.2s; }
    .filter-select-wrapper select:focus { outline: none; border-color: #c91818; }

    /* Gallery */
    .gallery-title-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
    .gallery-title { font-size: 14px; font-weight: 700; }
    .gallery-count { font-size: 11px; color: #64748b; background: #f1f5f9; padding: 2px 10px; border-radius: 20px; }

    /* Media Grid */
    .media-library-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 16px; margin-bottom: 24px; }
    .media-lib-card { border: 1px solid #e2e8f0; border-radius: 12px; padding: 8px; background-color: #ffffff; transition: all 0.2s ease; cursor: pointer; box-shadow: 0 1px 2px rgba(0,0,0,0.03); }
    .media-lib-card:hover { transform: translateY(-4px); box-shadow: 0 12px 24px -8px rgba(0,0,0,0.12); }
    .card-thumb-wrapper { position: relative; width: 100%; height: 105px; background-color: #f3f4f6; border-radius: 8px; overflow: hidden; margin-bottom: 8px; background-size: cover; background-position: center; }
    .thumb-type-badge { position: absolute; top: 6px; left: 6px; font-size: 7px; font-weight: 700; padding: 1px 5px; border-radius: 4px; z-index: 5; color: white; }
    .thumb-type-badge.red { background-color: rgba(239,68,68,0.85); }
    .thumb-type-badge.blue { background-color: rgba(59,130,246,0.85); }
    .thumb-type-badge.orange { background-color: rgba(249,115,22,0.85); }
    .thumb-type-badge.green { background-color: rgba(34,197,94,0.85); }
    .thumb-duration-tag { position: absolute; bottom: 6px; right: 6px; background-color: rgba(0,0,0,0.7); color: white; font-size: 8px; padding: 1px 4px; border-radius: 3px; }
    .card-actions-overlay { position: absolute; top:0; left:0; right:0; bottom:0; background-color: rgba(15,23,42,0.6); display: flex; align-items: center; justify-content: center; gap: 10px; opacity: 0; transition: opacity 0.2s; z-index: 10; backdrop-filter: blur(2px); }
    .media-lib-card:hover .card-actions-overlay { opacity: 1; }
    .action-btn { width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; cursor: pointer; border: none; transition: all 0.2s; font-size: 12px; }
    .action-btn.view { background-color: #3b82f6; }
    .action-btn.view:hover { transform: scale(1.1); background-color: #2563eb; }
    .action-btn.edit { background-color: #10b981; }
    .action-btn.edit:hover { transform: scale(1.1); background-color: #059669; }
    .action-btn.delete { background-color: #ef4444; }
    .action-btn.delete:hover { transform: scale(1.1); background-color: #dc2626; }
    .card-title { font-size: 10px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 2px; }
    .card-footer { display: flex; justify-content: space-between; align-items: center; }
    .category-tag { font-size: 8px; font-weight: 600; padding: 2px 8px; border-radius: 20px; }
    .category-tag.red { background-color: #fdf2f2; color: #b91c1c; }
    .category-tag.blue { background-color: #eff6ff; color: #1d4ed8; }
    .category-tag.green { background-color: #f0fdf4; color: #15803d; }
    .category-tag.orange { background-color: #fff7ed; color: #c2410c; }
    .card-size { font-size: 8px; font-weight: 600; color: #64748b; }

    /* Pagination */
    .pagination-bar { display: flex; justify-content: center; gap: 6px; margin-top: 24px; padding-top: 16px; flex-wrap: wrap; }
    .pag-btn { min-width: 32px; height: 32px; padding: 0 8px; border-radius: 8px; border: 1px solid #cbd5e1; background: white; color: #64748b; font-size: 12px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
    .pag-btn:hover:not(:disabled) { border-color: #c91818; color: #c91818; background: #fef2f2; }
    .pag-btn.active { background-color: #c91818; border-color: #c91818; color: white; }
    .pag-btn:disabled { opacity: 0.4; cursor: not-allowed; background: #f9fafb; }

    /* Modals */
    .modal { display: none; position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); align-items: center; justify-content: center; z-index: 1000; }
    .modal-content { background: white; border-radius: 24px; padding: 28px; width: 500px; max-width: 90%; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); animation: modalFadeIn 0.2s ease; }
    @keyframes modalFadeIn { from { opacity: 0; transform: scale(0.96); } to { opacity: 1; transform: scale(1); } }
    .modal-content h3 { font-size: 20px; font-weight: 700; color: #111827; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
    .modal-content input, .modal-content select, .modal-content textarea { width: 100%; padding: 12px; margin: 12px 0; border: 1px solid #cbd5e1; border-radius: 12px; transition: all 0.2s; font-family: inherit; }
    .modal-content input:focus, .modal-content select:focus, .modal-content textarea:focus { outline: none; border-color: #c91818; box-shadow: 0 0 0 3px rgba(201,24,24,0.1); }
    .modal-buttons { display: flex; gap: 12px; justify-content: flex-end; margin-top: 20px; }
    .modal-buttons button { padding: 10px 20px; border-radius: 40px; cursor: pointer; font-weight: 600; transition: all 0.2s; }
    .modal-buttons .cancel { background: #f3f4f6; color: #374151; border: none; }
    .modal-buttons .cancel:hover { background: #e5e7eb; transform: translateY(-1px); }
    .modal-buttons .submit { background: #c91818; color: white; border: none; }
    .modal-buttons .submit:hover { background: #a81414; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(201,24,24,0.3); }

    .preview-modal .modal-content { text-align: center; width: auto; max-width: 90%; }
    .preview-modal img, .preview-modal video, .preview-modal iframe { max-width: 100%; max-height: 70vh; border-radius: 12px; }

    /* Toast */
    .toast-notification { position: fixed; bottom: 30px; right: 30px; background: #1f2937; color: white; padding: 12px 20px; border-radius: 40px; z-index: 2000; animation: slideIn 0.3s ease; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); font-size: 13px; display: flex; align-items: center; gap: 8px; }
    .toast-notification.success { background: #10b981; }
    .toast-notification.error { background: #ef4444; }
    .toast-notification.warning { background: #f59e0b; }
    @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

    /* Highlight */
    .highlight { background-color: #fef2f2 !important; border-color: #c91818 !important; transition: all 0.2s ease; }

    @media (max-width: 1200px) { .summary-grid { grid-template-columns: repeat(3, 1fr); } .media-library-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 800px) { .main-content { padding: 20px; } .sidebar { width: 80px; } .sidebar .logo img { height: 40px; } .nav-item span { display: none; } .summary-grid { grid-template-columns: repeat(2, 1fr); } .media-library-grid { grid-template-columns: repeat(2, 1fr); } }
  </style>
</head>
<body>
<div class="dashboard-container">
  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sidebar-top">
      <div class="logo"><img src="{{ asset('images/logo.png') }}" alt="Digika CMS" onerror="this.src='https://placehold.co/120x38/ffffff/c91818?text=DigikaCMS'"></div>
      <nav class="nav-menu">
        <a href="{{ route('dashboard') }}" class="nav-item"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="9"></rect><rect x="14" y="3" width="7" height="5"></rect><rect x="14" y="12" width="7" height="9"></rect><rect x="3" y="16" width="7" height="5"></rect></svg><span>Dashboard</span></a>
        <a href="{{ route('media.index') }}" class="nav-item active"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path><line x1="12" y1="11" x2="12" y2="17"></line><line x1="9" y1="14" x2="15" y2="14"></line></svg><span>Médiathèque</span></a>
        <a href="{{ route('campaigns.index') }}" class="nav-item"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19l7-2V7l-7-2v14z"></path><path d="M12 5L3 9v6l9 4z"></path><line x1="23" y1="12" x2="19" y2="12"></line></svg><span>Campagnes</span></a>
        <div class="nav-section-title">INTELLIGENCE IA</div>
        <a href="#" class="nav-item" onclick="showToast('Assistant IA bientôt disponible', 'info')"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="10" r="3"></circle><path d="M12 2a8 8 0 0 0-8 8c0 4 3 6 8 10 5-4 8-6 8-10a8 8 0 0 0-8-8z"></path></svg><span>Assistant IA</span></a>
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

  <!-- MAIN CONTENT -->
  <main class="main-content" id="mainContent">
    <div class="main-header">
      <div class="header-page-title">Médiathèque</div>
      <div class="header-actions">
        <div class="search-wrapper"><input type="text" id="globalSearch" placeholder="Rechercher..."><i class="fas fa-search search-icon"></i></div>
        <div class="settings-container">
          <button class="settings-btn" id="settingsDropdownBtn"><i class="fas fa-sliders-h"></i></button>
          <div class="settings-dropdown" id="settingsDropdown">
            <div class="dropdown-section">
              <div class="dropdown-title"><i class="fas fa-palette"></i> Thème</div>
              <div class="dropdown-option" data-theme="light"><i class="fas fa-sun"></i> Clair</div>
              <div class="dropdown-option" data-theme="dark"><i class="fas fa-moon"></i> Sombre</div>
            </div>
            <div class="dropdown-section">
              <div class="dropdown-title"><i class="fas fa-search-plus"></i> Zoom</div>
              <div class="zoom-control">
                <button class="zoom-btn" id="zoomOut">A-</button>
                <span class="zoom-value" id="zoomValue">100%</span>
                <button class="zoom-btn" id="zoomIn">A+</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="library-header">
      <div><h1 class="library-title">Bibliothèque de médias</h1><span class="library-subtitle">Gérez vos images, vidéos et documents pour l'affichage dynamique</span></div>
      <button class="import-btn" id="importBtn"><i class="fas fa-upload"></i> Importer un fichier</button>
    </div>

    <!-- STATS CARDS -->
    <div class="summary-grid">
      <div class="summary-card"><div class="summary-icon pink"><i class="fas fa-images"></i></div><div class="summary-info"><span class="summary-value">{{ $totalMedia ?? 0 }}</span><span class="summary-label">Total fichiers</span></div></div>
      <div class="summary-card"><div class="summary-icon blue"><i class="fas fa-image"></i></div><div class="summary-info"><span class="summary-value">{{ $imagesCount ?? 0 }}</span><span class="summary-label">Images</span></div></div>
      <div class="summary-card"><div class="summary-icon purple"><i class="fas fa-video"></i></div><div class="summary-info"><span class="summary-value">{{ $videosCount ?? 0 }}</span><span class="summary-label">Vidéos</span></div></div>
      <div class="summary-card"><div class="summary-icon orange"><i class="fas fa-file-pdf"></i></div><div class="summary-info"><span class="summary-value">{{ $pdfCount ?? 0 }}</span><span class="summary-label">PDF</span></div></div>
      <div class="summary-card"><div class="summary-icon green"><i class="fas fa-file-alt"></i></div><div class="summary-info"><span class="summary-value">{{ $documentsCount ?? 0 }}</span><span class="summary-label">Documents</span></div></div>
      <div class="summary-card"><div class="summary-icon slate"><i class="fas fa-database"></i></div><div class="summary-info"><span class="summary-value">{{ $totalSize ?? '0' }} MB</span><span class="summary-label">Stockage utilisé</span></div></div>
    </div>

    <!-- UPLOAD ZONE -->
    <div class="upload-zone" id="uploadZone">
      <div class="upload-faint-icon"><i class="fas fa-cloud-upload-alt"></i></div>
      <div class="upload-text">Glissez vos fichiers ici ou <span>cliquez pour importer</span></div>
      <div class="upload-subtext">Formats supportés : JPG, PNG, MP4, PDF, DOCX, TXT — Max 50 MB par fichier</div>
      <button class="browse-btn" id="browseBtn"><i class="fas fa-folder-open"></i> Parcourir les fichiers</button>
    </div>

    <!-- FILTERS -->
    <div class="filters-toolbar">
      <div class="filter-search-wrapper"><input type="text" id="mediaSearchInput" placeholder="Rechercher un média..."></div>
      <div class="filter-select-wrapper"><select id="typeFilter"><option value="all">Tous types</option><option value="image">Images</option><option value="video">Vidéos</option><option value="pdf">PDF</option><option value="document">Documents</option></select></div>
      <div class="filter-select-wrapper"><select id="sortFilter"><option value="recent">Date: Plus récent</option><option value="oldest">Date: Plus ancien</option><option value="name">Nom: A-Z</option></select></div>
    </div>

    <div class="gallery-title-row"><h2 class="gallery-title">Tous les médias</h2><span class="gallery-count" id="fileCountDisplay">0 fichiers</span></div>
    <div class="media-library-grid" id="dynamicMediaGrid"></div>
    <div class="pagination-bar" id="paginationControls"></div>
  </main>
</div>

<!-- MODALS -->
<div id="importModal" class="modal">
  <div class="modal-content">
    <h3><i class="fas fa-upload"></i> Importer un fichier</h3>
    <form id="importForm" enctype="multipart/form-data">
      @csrf
      <input type="file" name="file" id="fileInput" required>
      <input type="text" name="title" id="fileTitle" placeholder="Titre du média" required>
      <select name="category_id" id="fileCategory">
        <option value="">Sélectionner une catégorie</option>
        @foreach($categories ?? [] as $cat)
          <option value="{{ $cat->id }}">{{ $cat->name }}</option>
        @endforeach
      </select>
      <div class="modal-buttons">
        <button type="button" class="cancel" id="closeModalBtn">Annuler</button>
        <button type="submit" class="submit">Importer</button>
      </div>
    </form>
  </div>
</div>

<div id="previewModal" class="modal preview-modal">
  <div class="modal-content">
    <h3><i class="fas fa-eye"></i> Aperçu</h3>
    <div id="previewContent"></div>
    <div class="modal-buttons">
      <button class="cancel" onclick="closePreviewModal()">Fermer</button>
    </div>
  </div>
</div>

<div id="editModal" class="modal">
  <div class="modal-content">
    <h3><i class="fas fa-edit"></i> Modifier le média</h3>
    <form id="editForm">
      <input type="hidden" name="id" id="editId">
      <input type="text" name="title" id="editTitle" placeholder="Titre" required>
      <select name="category_id" id="editCategorySelect">
        <option value="">Sélectionner une catégorie</option>
        @foreach($categories ?? [] as $cat)
          <option value="{{ $cat->id }}">{{ $cat->name }}</option>
        @endforeach
      </select>
      <div class="modal-buttons">
        <button type="button" class="cancel" id="closeEditModalBtn">Annuler</button>
        <button type="submit" class="submit">Enregistrer</button>
      </div>
    </form>
  </div>
</div>

<script>
  const allMediaData = @json($mediaData ?? []);
  let allMedia = [...allMediaData];
  let currentPage = 1;
  let itemsPerPage = 10;
  let currentType = 'all';
  let currentSort = 'recent';
  let currentMediaSearch = '';
  let globalSearchTerm = '';

  function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast-notification ${type}`;
    toast.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : (type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle')}"></i> ${message}`;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
  }

  // Détection automatique du type de fichier par extension
  function detectFileType(fileName) {
    const ext = fileName.split('.').pop().toLowerCase();
    if (['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'].includes(ext)) return 'image';
    if (['mp4', 'avi', 'mov', 'webm', 'mkv', 'flv', 'wmv'].includes(ext)) return 'video';
    if (ext === 'pdf') return 'pdf';
    if (['doc', 'docx', 'txt', 'rtf', 'odt', 'xls', 'xlsx', 'ppt', 'pptx'].includes(ext)) return 'document';
    return 'document';
  }

  function filterAndSort() {
    let result = [...allMedia];
    if (globalSearchTerm.trim()) {
      const s = globalSearchTerm.toLowerCase();
      result = result.filter(m => m.title.toLowerCase().includes(s));
    }
    if (currentMediaSearch.trim()) {
      const s = currentMediaSearch.toLowerCase();
      result = result.filter(m => m.title.toLowerCase().includes(s));
    }
    if (currentType !== 'all') result = result.filter(m => m.type === currentType);
    if (currentSort === 'recent') result.sort((a,b) => new Date(b.created_at) - new Date(a.created_at));
    else if (currentSort === 'oldest') result.sort((a,b) => new Date(a.created_at) - new Date(b.created_at));
    else if (currentSort === 'name') result.sort((a,b) => a.title.localeCompare(b.title));
    return result;
  }

  function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, function(m) {
      if (m === '&') return '&amp;';
      if (m === '<') return '&lt;';
      if (m === '>') return '&gt;';
      return m;
    });
  }

  function formatFileSize(bytes) {
    if (!bytes) return '0 MB';
    const mb = bytes / 1024 / 1024;
    if (mb < 1) return (bytes / 1024).toFixed(1) + ' KB';
    return mb.toFixed(1) + ' MB';
  }

  function applyGlobalSearchHighlight() {
    const mediaCards = document.querySelectorAll('.media-lib-card');
    if (globalSearchTerm.trim() === '') {
      mediaCards.forEach(card => card.classList.remove('highlight'));
      return;
    }
    const searchLower = globalSearchTerm.toLowerCase();
    mediaCards.forEach(card => {
      const title = card.getAttribute('data-title') || '';
      if (title.includes(searchLower)) {
        card.classList.add('highlight');
      } else {
        card.classList.remove('highlight');
      }
    });
  }

  function renderGridAndPagination() {
    const filtered = filterAndSort();
    const total = filtered.length;
    const totalPages = Math.ceil(total / itemsPerPage);
    
    if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;
    if (currentPage < 1) currentPage = 1;
    
    const start = (currentPage - 1) * itemsPerPage;
    const pageItems = filtered.slice(start, start + itemsPerPage);
    const grid = document.getElementById('dynamicMediaGrid');
    document.getElementById('fileCountDisplay').innerText = `${total} fichier${total > 1 ? 's' : ''}`;
    
    if (total === 0) {
      grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:60px;background:white;border-radius:12px;"><i class="fas fa-folder-open"></i> Aucun média trouvé</div>';
      document.getElementById('paginationControls').innerHTML = '';
      return;
    }
    
    grid.innerHTML = pageItems.map(item => {
      const ext = (item.file_path || '').split('.').pop() || 'file';
      let badgeClass = 'green';
      if (item.type === 'image') badgeClass = 'red';
      else if (item.type === 'video') badgeClass = 'blue';
      else if (item.type === 'pdf') badgeClass = 'orange';
      
      const thumbStyle = (item.type === 'image' && item.file_path) 
        ? `background-image: url('${window.location.origin}/storage/${item.file_path}'); background-size: cover; background-position: center;` 
        : '';
      
      const catName = item.category?.name || 'Non catégorisé';
      let catClass = 'green';
      if (catName === 'Promotion') catClass = 'red';
      else if (catName === 'Événement') catClass = 'blue';
      else if (catName === 'Branding') catClass = 'orange';
      
      const sizeText = formatFileSize(item.size);
      
      return `<div class="media-lib-card" data-title="${escapeHtml(item.title).toLowerCase()}">
        <div class="card-thumb-wrapper" style="${thumbStyle}">
          ${!thumbStyle ? `<div style="display:flex;align-items:center;justify-content:center;height:100%;background:#f3f4f6;">
            <i class="fas ${item.type === 'video' ? 'fa-video' : (item.type === 'pdf' ? 'fa-file-pdf' : 'fa-file-alt')}" style="font-size:32px;color:#9ca3af;"></i>
          </div>` : ''}
          <span class="thumb-type-badge ${badgeClass}">${ext.toUpperCase()}</span>
          <div class="card-actions-overlay">
            <button class="action-btn view" onclick="previewMedia(${item.id},'${item.type}','${window.location.origin}/storage/${item.file_path}')"><i class="fas fa-eye"></i></button>
            <button class="action-btn edit" onclick="openEditModal(${item.id},'${item.title.replace(/'/g, "\\'")}','${item.category_id || ''}')"><i class="fas fa-edit"></i></button>
            <button class="action-btn delete" onclick="deleteMedia(${item.id})"><i class="fas fa-trash"></i></button>
          </div>
        </div>
        <div class="card-title">${escapeHtml(item.title)}.${ext}</div>
        <div class="card-footer">
          <span class="category-tag ${catClass}">${escapeHtml(catName)}</span>
          <span class="card-size">${sizeText}</span>
        </div>
      </div>`;
    }).join('');
    
    applyGlobalSearchHighlight();
    
    let pagHtml = `<button class="pag-btn" id="prevPageBtn" ${currentPage === 1 ? 'disabled' : ''}><i class="fas fa-chevron-left"></i></button>`;
    let startPage = Math.max(1, currentPage - 2);
    let endPage = Math.min(totalPages, currentPage + 2);
    if (startPage > 1) {
      pagHtml += `<button class="pag-btn" data-page="1">1</button>`;
      if (startPage > 2) pagHtml += `<button class="pag-btn disabled" style="cursor:default;">...</button>`;
    }
    for (let i = startPage; i <= endPage; i++) {
      pagHtml += `<button class="pag-btn ${i === currentPage ? 'active' : ''}" data-page="${i}">${i}</button>`;
    }
    if (endPage < totalPages) {
      if (endPage < totalPages - 1) pagHtml += `<button class="pag-btn disabled" style="cursor:default;">...</button>`;
      pagHtml += `<button class="pag-btn" data-page="${totalPages}">${totalPages}</button>`;
    }
    pagHtml += `<button class="pag-btn" id="nextPageBtn" ${currentPage === totalPages ? 'disabled' : ''}><i class="fas fa-chevron-right"></i></button>`;
    document.getElementById('paginationControls').innerHTML = pagHtml;
    
    document.querySelectorAll('[data-page]').forEach(btn => {
      btn.addEventListener('click', () => {
        currentPage = parseInt(btn.getAttribute('data-page'));
        renderGridAndPagination();
        document.getElementById('mainContent').scrollTop = 0;
      });
    });
    
    const prevBtn = document.getElementById('prevPageBtn');
    const nextBtn = document.getElementById('nextPageBtn');
    if (prevBtn && !prevBtn.disabled) {
      const newPrevBtn = prevBtn.cloneNode(true);
      prevBtn.parentNode.replaceChild(newPrevBtn, prevBtn);
      newPrevBtn.addEventListener('click', () => { if (currentPage > 1) { currentPage--; renderGridAndPagination(); document.getElementById('mainContent').scrollTop = 0; } });
    }
    if (nextBtn && !nextBtn.disabled) {
      const newNextBtn = nextBtn.cloneNode(true);
      nextBtn.parentNode.replaceChild(newNextBtn, nextBtn);
      newNextBtn.addEventListener('click', () => { if (currentPage < totalPages) { currentPage++; renderGridAndPagination(); document.getElementById('mainContent').scrollTop = 0; } });
    }
  }

  const globalSearchInput = document.getElementById('globalSearch');
  const mediaSearchInput = document.getElementById('mediaSearchInput');
  const typeSelect = document.getElementById('typeFilter');
  const sortSelect = document.getElementById('sortFilter');
  
  function updateFilters() {
    currentMediaSearch = mediaSearchInput.value;
    currentType = typeSelect.value;
    currentSort = sortSelect.value;
    currentPage = 1;
    renderGridAndPagination();
  }
  
  function updateGlobalSearch() {
    globalSearchTerm = globalSearchInput.value;
    currentPage = 1;
    renderGridAndPagination();
  }
  
  mediaSearchInput.addEventListener('input', updateFilters);
  globalSearchInput.addEventListener('input', updateGlobalSearch);
  typeSelect.addEventListener('change', updateFilters);
  sortSelect.addEventListener('change', updateFilters);

  function previewMedia(id, type, url) {
    const previewDiv = document.getElementById('previewContent');
    if (type === 'image') {
      previewDiv.innerHTML = `<img src="${url}" alt="Aperçu" style="max-width:100%; max-height:70vh; border-radius:8px;">`;
    } else if (type === 'video') {
      previewDiv.innerHTML = `<video controls src="${url}" style="max-width:100%; max-height:70vh;"></video>`;
    } else if (type === 'pdf') {
      previewDiv.innerHTML = `<div style="padding:40px;text-align:center;"><i class="fas fa-file-pdf" style="font-size:80px;color:#ef4444;"></i><p style="margin-top:16px;">Fichier PDF</p><a href="${url}" target="_blank" style="display:inline-block;margin-top:16px;padding:8px 20px;background:#c91818;color:white;border-radius:30px;text-decoration:none;"><i class="fas fa-external-link-alt"></i> Ouvrir le PDF</a></div>`;
    } else {
      previewDiv.innerHTML = `<div style="padding:40px;text-align:center;"><i class="fas fa-file-alt" style="font-size:80px;color:#64748b;"></i><p style="margin-top:16px;">Document</p><a href="${url}" download style="display:inline-block;margin-top:16px;padding:8px 20px;background:#c91818;color:white;border-radius:30px;text-decoration:none;"><i class="fas fa-download"></i> Télécharger</a></div>`;
    }
    document.getElementById('previewModal').style.display = 'flex';
  }
  window.previewMedia = previewMedia;
  
  function closePreviewModal() { document.getElementById('previewModal').style.display = 'none'; }
  window.closePreviewModal = closePreviewModal;
  
  function openEditModal(id, title, categoryId) {
    document.getElementById('editId').value = id;
    document.getElementById('editTitle').value = title;
    document.getElementById('editCategorySelect').value = categoryId || '';
    document.getElementById('editModal').style.display = 'flex';
  }
  window.openEditModal = openEditModal;
  
  document.getElementById('closeEditModalBtn')?.addEventListener('click', () => document.getElementById('editModal').style.display = 'none');
  document.getElementById('editForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const id = document.getElementById('editId').value;
    const title = document.getElementById('editTitle').value;
    const category_id = document.getElementById('editCategorySelect').value;
    const res = await fetch(`/media/${id}`, {
      method: 'PUT',
      headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
      body: JSON.stringify({ title, category_id })
    });
    if (res.ok) { showToast('Média modifié avec succès', 'success'); setTimeout(() => location.reload(), 500); }
    else { const error = await res.json(); showToast('Erreur: ' + (error.message || 'Modification échouée'), 'error'); }
  });
  
  async function deleteMedia(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce média ?')) {
      const res = await fetch(`/media/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
      if (res.ok) { showToast('Média supprimé avec succès', 'success'); setTimeout(() => location.reload(), 500); }
      else showToast('Erreur lors de la suppression', 'error');
    }
  }
  window.deleteMedia = deleteMedia;
  
  const importModal = document.getElementById('importModal');
  const importBtn = document.getElementById('importBtn');
  const browseBtn = document.getElementById('browseBtn');
  const uploadZone = document.getElementById('uploadZone');
  const closeModalBtn = document.getElementById('closeModalBtn');
  const importFormElem = document.getElementById('importForm');
  
  function openModal() { importModal.style.display = 'flex'; }
  function closeModal() { importModal.style.display = 'none'; importFormElem.reset(); }
  
  importBtn?.addEventListener('click', openModal);
  browseBtn?.addEventListener('click', openModal);
  uploadZone?.addEventListener('click', openModal);
  closeModalBtn?.addEventListener('click', closeModal);
  
  window.addEventListener('click', (e) => {
    if (e.target === importModal) closeModal();
    if (e.target === document.getElementById('editModal')) document.getElementById('editModal').style.display = 'none';
    if (e.target === document.getElementById('previewModal')) closePreviewModal();
  });
  
  importFormElem?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const fileInput = document.getElementById('fileInput');
    const file = fileInput.files[0];
    if (!file) { showToast('Veuillez sélectionner un fichier', 'error'); return; }
    
    // Détection automatique du type
    const detectedType = detectFileType(file.name);
    
    const fd = new FormData(importFormElem);
    fd.set('type', detectedType);
    
    const res = await fetch('{{ route("media.store") }}', {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
      body: fd
    });
    if (res.ok) { showToast('Fichier importé avec succès', 'success'); setTimeout(() => location.reload(), 500); }
    else { const error = await res.json(); showToast('Erreur: ' + (error.message || 'Importation échouée'), 'error'); }
  });
  
  const dropdownBtn = document.getElementById('settingsDropdownBtn');
  const dropdown = document.getElementById('settingsDropdown');
  dropdownBtn?.addEventListener('click', (e) => { e.stopPropagation(); dropdown.classList.toggle('show'); });
  document.addEventListener('click', () => dropdown?.classList.remove('show'));
  
  let zoom = 100;
  const zoomSpan = document.getElementById('zoomValue');
  const mainDiv = document.getElementById('mainContent');
  document.getElementById('zoomIn')?.addEventListener('click', () => { if (zoom < 150) { zoom += 10; zoomSpan.innerText = zoom+'%'; mainDiv.style.zoom = zoom/100; } });
  document.getElementById('zoomOut')?.addEventListener('click', () => { if (zoom > 70) { zoom -= 10; zoomSpan.innerText = zoom+'%'; mainDiv.style.zoom = zoom/100; } });
  
  document.querySelectorAll('[data-theme]').forEach(opt => {
    opt.addEventListener('click', () => {
      const theme = opt.getAttribute('data-theme');
      if (theme === 'dark') { 
        mainDiv.style.backgroundColor = '#1e293b'; 
        mainDiv.style.color = '#e2e8f0';
        document.querySelectorAll('.summary-card, .media-lib-card, .modal-content, .filter-search-wrapper input, .filter-select-wrapper select').forEach(el => { if(el) { el.style.backgroundColor = '#2d3a4e'; el.style.color = '#e2e8f0'; el.style.borderColor = '#4a5568'; } });
      } else { 
        mainDiv.style.backgroundColor = '#fff'; 
        mainDiv.style.color = '#000';
        document.querySelectorAll('.summary-card, .media-lib-card, .modal-content, .filter-search-wrapper input, .filter-select-wrapper select').forEach(el => { if(el) { el.style.backgroundColor = 'white'; el.style.color = '#000'; el.style.borderColor = '#cbd5e1'; } });
      }
      dropdown.classList.remove('show');
    });
  });
  
  renderGridAndPagination();
</script>
</body>
</html>