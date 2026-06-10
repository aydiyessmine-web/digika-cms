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
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body, html { height: 100vh; width: 100vw; overflow: hidden; font-family: 'Poppins', sans-serif; background-color: #ffffff; }
    .dashboard-container { display: flex; height: 100vh; width: 100vw; overflow: hidden; }
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
    .main-content { flex: 1; background-color: #ffffff; padding: 28px 36px; overflow-y: auto; display: flex; flex-direction: column; }
    .main-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .header-page-title { font-size: 18px; font-weight: 600; color: #64748b; }
    .header-actions { display: flex; align-items: center; gap: 16px; }
    .search-wrapper { position: relative; width: 260px; }
    .search-wrapper input { width: 100%; height: 36px; padding: 0 38px 0 14px; border: 1.5px solid #e5e7eb; border-radius: 20px; font-size: 12px; outline: none; }
    .search-icon { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #9ca3af; pointer-events: none; }
    .settings-container { position: relative; }
    .settings-btn { background: none; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 50%; }
    .settings-dropdown { position: absolute; top: 45px; right: 0; background: white; border-radius: 12px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); width: 220px; z-index: 100; display: none; border: 1px solid #e5e7eb; }
    .settings-dropdown.show { display: block; }
    .dropdown-section { padding: 12px 16px; border-bottom: 1px solid #f0f0f0; }
    .dropdown-title { font-size: 11px; font-weight: 600; color: #6b7280; margin-bottom: 8px; text-transform: uppercase; }
    .dropdown-option { display: flex; align-items: center; gap: 10px; padding: 6px 0; cursor: pointer; font-size: 12px; }
    .zoom-control { display: flex; align-items: center; gap: 12px; justify-content: space-between; }
    .zoom-btn { background: #f3f4f6; border: none; width: 28px; height: 28px; border-radius: 6px; cursor: pointer; font-weight: bold; }
    .library-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; }
    .library-title { font-size: 23px; font-weight: 700; color: #0f172a; }
    .library-subtitle { font-size: 12px; color: #64748b; }
    .import-btn { background-color: #c91818; color: #ffffff; border: none; border-radius: 8px; padding: 10px 20px; font-size: 12px; font-weight: 600; cursor: pointer; }
    .summary-grid { display: grid; grid-template-columns: repeat(6, 1fr); gap: 16px; margin-bottom: 24px; }
    .summary-card { background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px 12px; display: flex; align-items: center; gap: 12px; }
    .summary-icon { width: 38px; height: 38px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .summary-icon.pink { background-color: #fdf2f2; color: #ef4444; }
    .summary-icon.blue { background-color: #eff6ff; color: #3b82f6; }
    .summary-icon.purple { background-color: #faf5ff; color: #a855f7; }
    .summary-icon.green { background-color: #f0fdf4; color: #22c55e; }
    .summary-icon.orange { background-color: #fff7ed; color: #f97316; }
    .summary-icon.slate { background-color: #f1f5f9; color: #64748b; }
    .summary-info { display: flex; flex-direction: column; }
    .summary-value { font-size: 18px; font-weight: 700; }
    .summary-label { font-size: 9px; color: #64748b; margin-top: 2px; }
    .upload-zone { background-color: #fcfcfd; border: 2px dashed #cbd5e1; border-radius: 20px; padding: 30px; text-align: center; margin-bottom: 24px; cursor: pointer; transition: all 0.2s ease; }
    .upload-zone:hover { background-color: #f8fafc; border-color: #c91818; }
    .upload-faint-icon { width: 48px; height: 48px; border-radius: 50%; background-color: #fef2f2; display: flex; align-items: center; justify-content: center; color: #ef4444; margin: 0 auto 8px; }
    .upload-text { font-size: 14px; font-weight: 600; color: #334155; }
    .upload-text span { color: #c91818; cursor: pointer; }
    .upload-subtext { font-size: 11px; color: #64748b; margin-top: 6px; margin-bottom: 16px; }
    .browse-btn { background-color: #c91818; color: #ffffff; border: none; border-radius: 8px; padding: 10px 24px; font-size: 12px; font-weight: 600; cursor: pointer; margin-top: 8px; }
    .browse-btn:hover { background-color: #b01414; }
    .filters-toolbar { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; flex-wrap: wrap; }
    .filter-search-wrapper { flex: 1; }
    .filter-search-wrapper input { width: 100%; height: 36px; padding: 0 16px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 12px; }
    .filter-search-wrapper input:focus { outline: none; border-color: #c91818; }
    .filter-select-wrapper { position: relative; }
    .filter-select-wrapper::after { content: "▼"; font-size: 7px; color: #64748b; position: absolute; right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; }
    .filter-select-wrapper select { height: 36px; padding: 0 28px 0 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 11px; appearance: none; background: white; cursor: pointer; }
    .gallery-title-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
    .gallery-title { font-size: 14px; font-weight: 700; }
    .gallery-count { font-size: 11px; color: #64748b; }
    .media-library-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 16px; margin-bottom: 24px; }
    .media-lib-card { border: 1px solid #e2e8f0; border-radius: 12px; padding: 8px; background-color: #ffffff; transition: transform 0.2s; cursor: pointer; }
    .media-lib-card:hover { transform: translateY(-4px); box-shadow: 0 8px 20px rgba(0,0,0,0.08); }
    .card-thumb-wrapper { position: relative; width: 100%; height: 105px; background-color: #f3f4f6; border-radius: 8px; overflow: hidden; margin-bottom: 8px; background-size: cover; background-position: center; }
    .thumb-type-badge { position: absolute; top: 6px; left: 6px; font-size: 7px; font-weight: 700; padding: 1px 5px; border-radius: 4px; z-index: 5; }
    .thumb-type-badge.red { background-color: rgba(239,68,68,0.1); color: #ef4444; }
    .thumb-type-badge.blue { background-color: rgba(59,130,246,0.1); color: #3b82f6; }
    .thumb-type-badge.orange { background-color: rgba(249,115,22,0.1); color: #f97316; }
    .thumb-type-badge.green { background-color: rgba(34,197,94,0.1); color: #22c55e; }
    .thumb-duration-tag { position: absolute; bottom: 6px; right: 6px; background-color: rgba(0,0,0,0.7); color: white; font-size: 8px; padding: 1px 4px; border-radius: 3px; }
    .card-actions-overlay { position: absolute; top:0; left:0; right:0; bottom:0; background-color: rgba(15,23,42,0.5); display: flex; align-items: center; justify-content: center; gap: 10px; opacity: 0; transition: opacity 0.2s; z-index: 10; }
    .media-lib-card:hover .card-actions-overlay { opacity: 1; }
    .action-btn { width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; cursor: pointer; border: none; }
    .action-btn.view { background-color: #3b82f6; }
    .action-btn.edit { background-color: #10b981; }
    .action-btn.delete { background-color: #ef4444; }
    .card-title { font-size: 10px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 2px; }
    .card-dimensions { font-size: 8px; color: #94a3b8; margin-bottom: 8px; }
    .card-footer { display: flex; justify-content: space-between; align-items: center; }
    .category-tag { font-size: 8px; font-weight: 600; padding: 1px 6px; border-radius: 8px; }
    .category-tag.red { background-color: #fdf2f2; color: #b91c1c; }
    .category-tag.blue { background-color: #eff6ff; color: #1d4ed8; }
    .category-tag.green { background-color: #f0fdf4; color: #15803d; }
    .category-tag.orange { background-color: #fff7ed; color: #c2410c; }
    .card-size { font-size: 8px; font-weight: 600; color: #64748b; }
    .pagination-bar { display: flex; justify-content: center; gap: 6px; margin-top: 24px; padding-top: 16px; }
    .pag-btn { min-width: 32px; height: 32px; padding: 0 8px; border-radius: 8px; border: 1px solid #cbd5e1; background: white; color: #64748b; font-size: 12px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
    .pag-btn:hover:not(:disabled) { border-color: #c91818; color: #c91818; }
    .pag-btn.active { background-color: #c91818; border-color: #c91818; color: white; }
    .pag-btn:disabled { opacity: 0.4; cursor: not-allowed; }
    .modal { display: none; position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; z-index: 1000; }
    .modal-content { background: white; border-radius: 20px; padding: 28px; width: 500px; max-width: 90%; }
    .modal-content input, .modal-content select { width: 100%; padding: 12px; margin: 12px 0; border: 1px solid #cbd5e1; border-radius: 8px; }
    .modal-buttons { display: flex; gap: 12px; justify-content: flex-end; margin-top: 20px; }
    .modal-buttons button { padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600; }
    .modal-buttons .cancel { background: #f3f4f6; color: #374151; }
    .modal-buttons .submit { background: #c91818; color: white; }
    .preview-modal .modal-content { text-align: center; width: auto; max-width: 90%; }
    .preview-modal img, .preview-modal video { max-width: 100%; max-height: 70vh; border-radius: 8px; }
    @media (max-width: 1200px) { .summary-grid { grid-template-columns: repeat(3, 1fr); } .media-library-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 800px) { .main-content { padding: 20px; } .sidebar { width: 80px; } .sidebar .logo img { height: 40px; } .nav-item span { display: none; } .summary-grid { grid-template-columns: repeat(2, 1fr); } .media-library-grid { grid-template-columns: repeat(2, 1fr); } }
  </style>
</head>
<body>
<div class="dashboard-container">
  <aside class="sidebar">
    <div class="sidebar-top">
      <div class="logo"><img src="{{ asset('images/logo.png') }}" alt="Digika CMS" onerror="this.src='https://placehold.co/120x38/ffffff/c91818?text=DigikaCMS'"></div>
      <nav class="nav-menu">
        <a href="{{ route('dashboard') }}" class="nav-item"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="9"></rect><rect x="14" y="3" width="7" height="5"></rect><rect x="14" y="12" width="7" height="9"></rect><rect x="3" y="16" width="7" height="5"></rect></svg><span>Dashboard</span></a>
        <a href="{{ route('media.index') }}" class="nav-item active"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path><line x1="12" y1="11" x2="12" y2="17"></line><line x1="9" y1="14" x2="15" y2="14"></line></svg><span>Médiathèque</span></a>
        <a href="{{ route('campaigns.index') }}" class="nav-item"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19l7-2V7l-7-2v14z"></path><path d="M12 5L3 9v6l9 4z"></path><line x1="23" y1="12" x2="19" y2="12"></line></svg><span>Campagnes</span></a>
        <div class="nav-section-title">INTELLIGENCE IA</div>
        <a href="{{ route('ai.assistant') }}" class="nav-item"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="10" r="3"></circle><path d="M12 2a8 8 0 0 0-8 8c0 4 3 6 8 10 5-4 8-6 8-10a8 8 0 0 0-8-8z"></path></svg><span>Assistant IA</span></a>
      </nav>
    </div>
    <div class="sidebar-bottom">
      <div class="user-profile"><div class="user-avatar">{{ auth()->user() ? substr(auth()->user()->name, 0, 1) : 'E' }}</div><div class="user-info"><div class="user-name">{{ auth()->user() ? auth()->user()->name : 'Evano' }}</div><div class="user-role">Marketing Manager</div></div></div>
      <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="logout-btn"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg><span>Logout</span></button></form>
    </div>
  </aside>

  <main class="main-content" id="mainContent">
    <div class="main-header">
      <div class="header-page-title">Médiathèque</div>
      <div class="header-actions">
        <div class="search-wrapper"><input type="text" id="appSearchInput" placeholder="Rechercher..."><svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg></div>
        <div class="settings-container"><button class="settings-btn" id="settingsDropdownBtn"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l-.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg></button><div class="settings-dropdown" id="settingsDropdown"><div class="dropdown-section"><div class="dropdown-title">🎨 Thème</div><div class="dropdown-option" data-theme="light">☀️ Clair</div><div class="dropdown-option" data-theme="dark">🌙 Sombre</div></div><div class="dropdown-section"><div class="dropdown-title">🔍 Zoom</div><div class="zoom-control"><button class="zoom-btn" id="zoomOut">A-</button><span class="zoom-value" id="zoomValue">100%</span><button class="zoom-btn" id="zoomIn">A+</button></div></div></div></div>
      </div>
    </div>

    <div class="library-header"><div><h1 class="library-title">Bibliothèque de médias</h1><span class="library-subtitle">Gérez vos images, vidéos et contenus visuels pour l'affichage dynamique</span></div><button class="import-btn" id="importBtn">Importer un fichier</button></div>

    <div class="summary-grid">
      <div class="summary-card"><div class="summary-icon pink"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg></div><div class="summary-info"><span class="summary-value">{{ $totalMedia ?? 0 }}</span><span class="summary-label">Total fichiers</span></div></div>
      <div class="summary-card"><div class="summary-icon blue"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle></svg></div><div class="summary-info"><span class="summary-value">{{ $imagesCount ?? 0 }}</span><span class="summary-label">Images</span></div></div>
      <div class="summary-card"><div class="summary-icon purple"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg></div><div class="summary-info"><span class="summary-value">{{ $videosCount ?? 0 }}</span><span class="summary-label">Vidéos</span></div></div>
      <div class="summary-card"><div class="summary-icon orange"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg></div><div class="summary-info"><span class="summary-value">{{ $pdfCount ?? 0 }}</span><span class="summary-label">PDF</span></div></div>
      <div class="summary-card"><div class="summary-icon green"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 4h16v16H4z"></path><path d="M8 8h8v8H8z"></path></svg></div><div class="summary-info"><span class="summary-value">{{ $documentsCount ?? 0 }}</span><span class="summary-label">Documents</span></div></div>
      <div class="summary-card"><div class="summary-icon slate"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg></div><div class="summary-info"><span class="summary-value">{{ $totalSize ?? '4.7' }} GB</span><span class="summary-label">Stockage utilisé</span></div></div>
    </div>

    <div class="upload-zone" id="uploadZone"><div class="upload-faint-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg></div><div class="upload-text">Glissez vos fichiers ici ou <span>cliquez pour importer</span></div><div class="upload-subtext">Formats supportés : JPG, PNG, MP4, PDF, DOC — Max 50 MB par fichier</div><button class="browse-btn" id="browseBtn">Parcourir les fichiers</button></div>

    <div class="filters-toolbar">
      <div class="filter-search-wrapper"><input type="text" id="mediaSearchInput" placeholder="Rechercher un média..."></div>
      <div class="filter-select-wrapper"><select id="categoryFilter"><option value="all">Toutes catégories</option>@foreach($categories ?? [] as $cat)<option value="{{ $cat->name }}">{{ $cat->name }}</option>@endforeach</select></div>
      <div class="filter-select-wrapper"><select id="typeFilter"><option value="all">Tous types</option><option value="image">Images</option><option value="video">Vidéos</option><option value="pdf">PDF</option><option value="document">Documents</option></select></div>
      <div class="filter-select-wrapper"><select id="sortFilter"><option value="recent">Date: Plus récent</option><option value="oldest">Date: Plus ancien</option><option value="name">Nom: A-Z</option></select></div>
    </div>

    <div class="gallery-title-row"><h2 class="gallery-title">Tous les médias</h2><span class="gallery-count" id="fileCountDisplay">0 fichiers</span></div>
    <div class="media-library-grid" id="dynamicMediaGrid"></div>
    <div class="pagination-bar" id="paginationControls"></div>
  </main>
</div>

<div id="importModal" class="modal"><div class="modal-content"><h3>📤 Importer un fichier</h3><form id="importForm" enctype="multipart/form-data">@csrf<input type="file" name="file" id="fileInput" required><input type="text" name="title" id="fileTitle" placeholder="Titre du média" required><select name="type" id="fileType" required><option value="">-- Sélectionner un type --</option><option value="image">Image</option><option value="video">Vidéo</option><option value="pdf">PDF</option><option value="document">Document</option></select><input type="text" name="resolution" id="fileResolution" placeholder="Résolution"><select name="category_id" id="fileCategory"><option value="">Sélectionner une catégorie</option>@foreach($categories ?? [] as $cat)<option value="{{ $cat->id }}">{{ $cat->name }}</option>@endforeach</select><input type="text" name="duration" id="fileDuration" placeholder="Durée vidéo" style="display:none;"><div class="modal-buttons"><button type="button" class="cancel" id="closeModalBtn">Annuler</button><button type="submit" class="submit">Importer</button></div></form></div></div>
<div id="previewModal" class="modal preview-modal"><div class="modal-content"><div id="previewContent"></div><div class="modal-buttons"><button class="cancel" onclick="closePreviewModal()">Fermer</button></div></div></div>
<div id="editModal" class="modal"><div class="modal-content"><h3>✏️ Modifier le média</h3><form id="editForm"><input type="hidden" name="id" id="editId"><input type="text" name="title" id="editTitle" placeholder="Titre" required><select name="category_id" id="editCategorySelect"><option value="">Sélectionner une catégorie</option>@foreach($categories ?? [] as $cat)<option value="{{ $cat->id }}">{{ $cat->name }}</option>@endforeach</select><input type="text" name="resolution" id="editResolution" placeholder="Résolution"><div class="modal-buttons"><button type="button" class="cancel" id="closeEditModalBtn">Annuler</button><button type="submit" class="submit">Enregistrer</button></div></form></div></div>

<script>
  const serverMedia = @json($media->items() ?? []);
  // Nombre d'éléments par page (peut être modifié)
  let itemsPerPage = 12;
  let currentPage = 1;
  let filteredMedia = [...serverMedia];
  let filters = { search: '', category: 'all', type: 'all', sort: 'recent' };

  function filterAndSort() {
    let result = [...serverMedia];
    if (filters.search.trim()) {
      const s = filters.search.toLowerCase();
      result = result.filter(m => m.title.toLowerCase().includes(s));
    }
    if (filters.category !== 'all') result = result.filter(m => m.category?.name === filters.category);
    if (filters.type !== 'all') result = result.filter(m => m.type === filters.type);
    if (filters.sort === 'recent') result.sort((a,b) => new Date(b.created_at) - new Date(a.created_at));
    else if (filters.sort === 'oldest') result.sort((a,b) => new Date(a.created_at) - new Date(b.created_at));
    else if (filters.sort === 'name') result.sort((a,b) => a.title.localeCompare(b.title));
    return result;
  }

  function renderGridAndPagination() {
    filteredMedia = filterAndSort();
    const total = filteredMedia.length;
    const totalPages = Math.ceil(total / itemsPerPage);
    
    // Ajuster la page courante si elle dépasse
    if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;
    if (currentPage < 1) currentPage = 1;
    
    const start = (currentPage - 1) * itemsPerPage;
    const pageItems = filteredMedia.slice(start, start + itemsPerPage);
    const grid = document.getElementById('dynamicMediaGrid');
    
    if (total === 0 || pageItems.length === 0) {
      grid.innerHTML = '<div class="media-lib-card" style="grid-column:1/-1;text-align:center;padding:40px;">Aucun média trouvé</div>';
      document.getElementById('paginationControls').innerHTML = '';
      document.getElementById('fileCountDisplay').innerText = '0 fichiers';
      return;
    }
    
    grid.innerHTML = pageItems.map(item => {
      const ext = (item.file_path || '').split('.').pop() || 'jpg';
      let badgeClass = 'green';
      if(item.type === 'image') badgeClass = 'red';
      else if(item.type === 'video') badgeClass = 'blue';
      else if(item.type === 'pdf') badgeClass = 'orange';
      const thumbStyle = (item.type === 'image' && item.file_path) ? `background-image: url('${window.location.origin}/storage/${item.file_path}'); background-size: cover; background-position: center;` : '';
      const catName = item.category?.name || 'Non catégorisé';
      let catClass = 'green';
      if(catName === 'Promotion') catClass = 'red';
      else if(catName === 'Événement') catClass = 'blue';
      else if(catName === 'Branding') catClass = 'orange';
      const sizeMB = ((item.size || 0) / 1024 / 1024).toFixed(1);
      return `<div class="media-lib-card"><div class="card-thumb-wrapper" style="${thumbStyle}"><span class="thumb-type-badge ${badgeClass}">${ext.toUpperCase()}</span>${item.type === 'video' && item.duration ? `<span class="thumb-duration-tag">${item.duration}</span>` : ''}<div class="card-actions-overlay"><button class="action-btn view" onclick="previewMedia(${item.id},'${item.type}','${window.location.origin}/storage/${item.file_path}')">👁</button><button class="action-btn edit" onclick="openEditModal(${item.id},'${item.title.replace(/'/g, "\\'")}','${item.category_id || ''}','${item.resolution || ''}')">✏️</button><button class="action-btn delete" onclick="deleteMedia(${item.id})">🗑️</button></div></div><div class="card-title">${item.title}.${ext}</div><div class="card-dimensions">${item.resolution || '1920x1080'}</div><div class="card-footer"><span class="category-tag ${catClass}">${catName}</span><span class="card-size">${sizeMB} MB</span></div></div>`;
    }).join('');
    
    document.getElementById('fileCountDisplay').innerText = `${total} fichiers`;
    
    // Générer la pagination
    let pagHtml = `<button class="pag-btn" id="prevPageBtn" ${currentPage === 1 ? 'disabled' : ''}>◀</button>`;
    for (let i = 1; i <= totalPages; i++) {
      pagHtml += `<button class="pag-btn ${i === currentPage ? 'active' : ''}" data-page="${i}">${i}</button>`;
    }
    pagHtml += `<button class="pag-btn" id="nextPageBtn" ${currentPage === totalPages ? 'disabled' : ''}>▶</button>`;
    document.getElementById('paginationControls').innerHTML = pagHtml;
    
    // Attacher les événements pour les numéros de page
    document.querySelectorAll('[data-page]').forEach(btn => {
      btn.addEventListener('click', (e) => {
        currentPage = parseInt(btn.getAttribute('data-page'));
        renderGridAndPagination();
      });
    });
    
    // Attacher les événements pour les boutons précédent/suivant
    const prevBtn = document.getElementById('prevPageBtn');
    const nextBtn = document.getElementById('nextPageBtn');
    if (prevBtn) {
      // Remplacer l'ancien événement par un nouveau pour éviter les doublons
      const newPrevBtn = prevBtn.cloneNode(true);
      prevBtn.parentNode.replaceChild(newPrevBtn, prevBtn);
      newPrevBtn.addEventListener('click', () => {
        if (currentPage > 1) {
          currentPage--;
          renderGridAndPagination();
        }
      });
    }
    if (nextBtn) {
      const newNextBtn = nextBtn.cloneNode(true);
      nextBtn.parentNode.replaceChild(newNextBtn, nextBtn);
      newNextBtn.addEventListener('click', () => {
        if (currentPage < totalPages) {
          currentPage++;
          renderGridAndPagination();
        }
      });
    }
  }

  const searchInput = document.getElementById('mediaSearchInput');
  const categorySelect = document.getElementById('categoryFilter');
  const typeSelect = document.getElementById('typeFilter');
  const sortSelect = document.getElementById('sortFilter');
  
  function updateFilters() {
    filters.search = searchInput.value;
    filters.category = categorySelect.value;
    filters.type = typeSelect.value;
    filters.sort = sortSelect.value;
    currentPage = 1;
    renderGridAndPagination();
  }
  
  searchInput.addEventListener('input', updateFilters);
  categorySelect.addEventListener('change', updateFilters);
  typeSelect.addEventListener('change', updateFilters);
  sortSelect.addEventListener('change', updateFilters);

  function previewMedia(id, type, url) {
    const previewDiv = document.getElementById('previewContent');
    if(type === 'image') previewDiv.innerHTML = `<img src="${url}" alt="Aperçu" style="max-width:100%; max-height:70vh; border-radius:8px;">`;
    else if(type === 'video') previewDiv.innerHTML = `<video controls src="${url}" style="max-width:100%; max-height:70vh;"></video>`;
    else previewDiv.innerHTML = `<div style="padding:60px;text-align:center;"><i class="fas fa-file-pdf" style="font-size:64px;color:#ef4444;"></i><p style="margin-top:16px;">Aperçu non disponible</p></div>`;
    document.getElementById('previewModal').style.display = 'flex';
  }
  window.previewMedia = previewMedia;
  
  function closePreviewModal() { document.getElementById('previewModal').style.display = 'none'; }
  window.closePreviewModal = closePreviewModal;
  
  function openEditModal(id, title, categoryId, resolution) {
    document.getElementById('editId').value = id;
    document.getElementById('editTitle').value = title;
    document.getElementById('editCategorySelect').value = categoryId || '';
    document.getElementById('editResolution').value = resolution || '';
    document.getElementById('editModal').style.display = 'flex';
  }
  window.openEditModal = openEditModal;
  
  document.getElementById('closeEditModalBtn')?.addEventListener('click', () => document.getElementById('editModal').style.display = 'none');
  document.getElementById('editForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const id = document.getElementById('editId').value;
    const title = document.getElementById('editTitle').value;
    const category_id = document.getElementById('editCategorySelect').value;
    const resolution = document.getElementById('editResolution').value;
    const res = await fetch(`/media/${id}`, {
      method: 'PUT',
      headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
      body: JSON.stringify({ title, category_id, resolution })
    });
    if(res.ok) location.reload();
    else alert('Erreur lors de la modification');
  });
  
  async function deleteMedia(id) {
    if(confirm('⚠️ Êtes-vous sûr de vouloir supprimer ce média ?')) {
      await fetch(`/media/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
      location.reload();
    }
  }
  window.deleteMedia = deleteMedia;
  
  const importModal = document.getElementById('importModal');
  const importBtn = document.getElementById('importBtn');
  const browseBtn = document.getElementById('browseBtn');
  const uploadZone = document.getElementById('uploadZone');
  const closeModalBtn = document.getElementById('closeModalBtn');
  const importFormElem = document.getElementById('importForm');
  const fileTypeSelect = document.getElementById('fileType');
  const durationField = document.getElementById('fileDuration');
  
  function openModal() { importModal.style.display = 'flex'; }
  function closeModal() { importModal.style.display = 'none'; importFormElem.reset(); if(durationField) durationField.style.display = 'none'; }
  
  importBtn?.addEventListener('click', openModal);
  browseBtn?.addEventListener('click', openModal);
  uploadZone?.addEventListener('click', openModal);
  closeModalBtn?.addEventListener('click', closeModal);
  
  window.addEventListener('click', (e) => {
    if(e.target === importModal) closeModal();
    if(e.target === document.getElementById('editModal')) document.getElementById('editModal').style.display = 'none';
    if(e.target === document.getElementById('previewModal')) closePreviewModal();
  });
  
  fileTypeSelect?.addEventListener('change', () => {
    if(durationField) durationField.style.display = fileTypeSelect.value === 'video' ? 'block' : 'none';
  });
  
  importFormElem?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const file = document.getElementById('fileInput').files[0];
    if(!file) { alert('Veuillez sélectionner un fichier'); return; }
    const ext = file.name.split('.').pop().toLowerCase();
    let detected = 'document';
    if(['jpg','jpeg','png','gif','webp'].includes(ext)) detected = 'image';
    else if(['mp4','avi','mov','webm','mkv'].includes(ext)) detected = 'video';
    else if(ext === 'pdf') detected = 'pdf';
    const fd = new FormData(importFormElem);
    fd.set('type', detected);
    const res = await fetch('{{ route("media.store") }}', {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
      body: fd
    });
    if(res.ok) { alert('✅ Fichier importé avec succès !'); location.reload(); }
    else alert('❌ Erreur lors de l\'importation');
  });
  
  const dropdownBtn = document.getElementById('settingsDropdownBtn');
  const dropdown = document.getElementById('settingsDropdown');
  dropdownBtn?.addEventListener('click', (e) => { e.stopPropagation(); dropdown.classList.toggle('show'); });
  document.addEventListener('click', () => dropdown?.classList.remove('show'));
  
  let zoom = 100;
  const zoomSpan = document.getElementById('zoomValue');
  const mainDiv = document.getElementById('mainContent');
  document.getElementById('zoomIn')?.addEventListener('click', () => {
    if(zoom < 150) { zoom += 10; zoomSpan.innerText = zoom+'%'; mainDiv.style.zoom = zoom/100; }
  });
  document.getElementById('zoomOut')?.addEventListener('click', () => {
    if(zoom > 70) { zoom -= 10; zoomSpan.innerText = zoom+'%'; mainDiv.style.zoom = zoom/100; }
  });
  
  document.querySelectorAll('[data-theme]').forEach(opt => {
    opt.addEventListener('click', () => {
      const theme = opt.getAttribute('data-theme');
      if(theme === 'dark') { mainDiv.style.backgroundColor = '#1e293b'; mainDiv.style.color = '#fff'; }
      else { mainDiv.style.backgroundColor = '#fff'; mainDiv.style.color = '#000'; }
      dropdown.classList.remove('show');
    });
  });
  
  renderGridAndPagination();
</script>
</body>
</html>