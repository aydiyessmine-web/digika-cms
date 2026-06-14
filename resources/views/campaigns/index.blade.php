<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Campagnes - DigiCMS</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- PDF.js library -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
  <script>
      pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';
  </script>
  <!-- Mammoth.js pour les fichiers DOCX -->
  <script src="https://cdn.jsdelivr.net/npm/mammoth@1.4.2/mammoth.browser.min.js"></script>
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
    .main-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #eef2f6; }
    .header-page-title { font-size: 18px; font-weight: 600; color: #64748b; }
    .header-actions { display: flex; align-items: center; gap: 16px; }
    .search-wrapper { position: relative; width: 260px; }
    .search-wrapper input { width: 100%; height: 36px; padding: 0 38px 0 14px; border: 1.5px solid #e5e7eb; border-radius: 20px; font-size: 12px; outline: none; transition: all 0.2s; }
    .search-wrapper input:focus { border-color: #c91818; box-shadow: 0 0 0 3px rgba(201,24,24,0.1); }
    .search-icon { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #9ca3af; pointer-events: none; font-size: 14px; }
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
    .library-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; }
    .library-title { font-size: 23px; font-weight: 700; color: #0f172a; }
    .library-subtitle { font-size: 12px; color: #64748b; }
    .import-btn { background-color: #c91818; color: #ffffff; border: none; border-radius: 8px; padding: 10px 20px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 8px; }
    .import-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(201,24,24,0.3); }
    .summary-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
    .summary-card { background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px 12px; display: flex; align-items: center; gap: 12px; transition: all 0.2s; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .summary-card:hover { box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.02); transform: translateY(-2px); }
    .summary-icon { width: 38px; height: 38px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 18px; }
    .summary-icon.pink { background-color: #fdf2f2; color: #ef4444; }
    .summary-icon.blue { background-color: #eff6ff; color: #3b82f6; }
    .summary-icon.purple { background-color: #faf5ff; color: #a855f7; }
    .summary-icon.green { background-color: #f0fdf4; color: #22c55e; }
    .summary-info { display: flex; flex-direction: column; }
    .summary-value { font-size: 18px; font-weight: 700; }
    .summary-label { font-size: 9px; color: #64748b; margin-top: 2px; }
    .filters-toolbar { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; flex-wrap: wrap; padding-bottom: 15px; border-bottom: 1px solid #eef2f6; }
    .filter-search-wrapper { flex: 1; }
    .filter-search-wrapper input { width: 100%; height: 36px; padding: 0 16px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 12px; transition: all 0.2s; }
    .filter-search-wrapper input:focus { outline: none; border-color: #c91818; box-shadow: 0 0 0 3px rgba(201,24,24,0.1); }
    .filter-select-wrapper { position: relative; }
    .filter-select-wrapper::after { content: "▼"; font-size: 7px; color: #64748b; position: absolute; right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; }
    .filter-select-wrapper select { height: 36px; padding: 0 28px 0 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 11px; appearance: none; background: white; cursor: pointer; transition: all 0.2s; }
    .filter-select-wrapper select:focus { outline: none; border-color: #c91818; }
    .gallery-title-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
    .gallery-title { font-size: 14px; font-weight: 700; }
    .gallery-count { font-size: 11px; color: #64748b; background: #f1f5f9; padding: 2px 10px; border-radius: 20px; }
    .campaigns-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px; }
    .campaign-card { border: 1px solid #e2e8f0; border-radius: 12px; background-color: #ffffff; transition: transform 0.2s, box-shadow 0.2s; overflow: hidden; box-shadow: 0 1px 2px rgba(0,0,0,0.03); }
    .campaign-card:hover { transform: translateY(-4px); box-shadow: 0 12px 24px -8px rgba(0,0,0,0.12); }
    .highlight { background-color: #fef2f2 !important; border-color: #c91818 !important; transition: all 0.2s ease; }
    .card-header { height: 110px; position: relative; background-size: cover; background-position: center; }
    .status-badge { position: absolute; top: 14px; right: 14px; border-radius: 20px; padding: 3px 10px; font-size: 10px; font-weight: 700; z-index: 2; }
    .card-body { padding: 10px 14px 0; }
    .campaign-title { font-size: 14px; font-weight: 700; color: #0d1b3e; margin-bottom: 4px; }
    .campaign-desc { font-size: 11px; color: #6b7280; line-height: 1.55; margin-bottom: 8px; min-height: 32px; }
    .dates { display: flex; justify-content: space-between; font-size: 11px; color: #9ca3af; margin-bottom: 4px; }
    .progress-row { display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 4px; }
    .progress-label { color: #6b7280; }
    .progress-percent { font-weight: 700; color: #0d1b3e; }
    .progress-bar { background: #f0f2f7; border-radius: 3px; height: 5px; margin-bottom: 12px; overflow: hidden; }
    .progress-fill { height: 100%; border-radius: 3px; transition: width 0.3s ease; }
    .card-footer { border-top: 1px solid #f5f5f8; display: flex; align-items: center; justify-content: space-between; padding: 8px 14px 10px; }
    .media-count { display: flex; align-items: center; gap: 4px; font-size: 11px; color: #9ca3af; cursor: pointer; }
    .media-count:hover { color: #c91818; text-decoration: underline; }
    .card-actions { display: flex; gap: 5px; }
    .pause-btn { border: none; border-radius: 6px; padding: 4px 10px; font-size: 11px; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 3px; }
    .pause-btn.active-pause { background: #fef2f2; color: #e02c3a; }
    .pause-btn.active-pause:hover { background: #fce4e4; }
    .pause-btn.planned-resume { background: #e8f5e9; color: #4caf50; }
    .pause-btn.planned-resume:hover { background: #c8e6c9; }
    .edit-btn, .delete-btn, .preview-btn { width: 30px; height: 26px; border-radius: 6px; display: flex; align-items: center; justify-content: center; cursor: pointer; border: none; transition: all 0.2s; font-size: 12px; }
    .preview-btn { background: #8b5cf6; color: white; }
    .preview-btn:hover { background: #7c3aed; transform: scale(1.05); }
    .edit-btn { background: #f4f6fb; color: #0d1b3e; }
    .edit-btn:hover { background: #e8ecf5; }
    .delete-btn { background: #0d1b3e; color: white; }
    .delete-btn:hover { background: #1e2a5e; }
    .pagination-bar { display: flex; justify-content: center; gap: 6px; margin-top: 24px; padding-top: 16px; flex-wrap: wrap; }
    .pag-btn { min-width: 32px; height: 32px; padding: 0 8px; border-radius: 8px; border: 1px solid #cbd5e1; background: white; color: #64748b; font-size: 12px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
    .pag-btn:hover:not(:disabled) { border-color: #c91818; color: #c91818; background: #fef2f2; }
    .pag-btn.active { background-color: #c91818; border-color: #c91818; color: white; }
    .pag-btn:disabled { opacity: 0.4; cursor: not-allowed; background: #f9fafb; }
    .modal { display: none; position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); align-items: center; justify-content: center; z-index: 1000; }
    .modal-content { background: white; border-radius: 32px; padding: 32px; width: 550px; max-width: 90%; max-height: 85vh; overflow-y: auto; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); animation: modalFadeIn 0.2s ease; border: 1px solid rgba(0,0,0,0.05); }
    @keyframes modalFadeIn { from { opacity: 0; transform: scale(0.96); } to { opacity: 1; transform: scale(1); } }
    .modal-content h3 { font-size: 22px; font-weight: 700; color: #111827; margin-bottom: 24px; display: flex; align-items: center; gap: 10px; }
    .modal-content input, .modal-content select, .modal-content textarea { width: 100%; padding: 12px 16px; margin: 8px 0 16px; border: 1px solid #e5e7eb; border-radius: 16px; font-family: inherit; font-size: 14px; transition: all 0.2s; background: #f9fafb; }
    .modal-content input:focus, .modal-content select:focus, .modal-content textarea:focus { outline: none; border-color: #c91818; background: white; box-shadow: 0 0 0 3px rgba(201,24,24,0.1); }
    .modal-buttons { display: flex; gap: 12px; justify-content: flex-end; margin-top: 24px; }
    .modal-buttons button { padding: 10px 20px; border-radius: 40px; cursor: pointer; font-weight: 600; font-size: 14px; transition: all 0.2s; }
    .modal-buttons .cancel { background: #f3f4f6; color: #374151; border: none; }
    .modal-buttons .cancel:hover { background: #e5e7eb; transform: translateY(-1px); }
    .modal-buttons .submit { background: #c91818; color: white; border: none; }
    .modal-buttons .submit:hover { background: #a81414; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(201,24,24,0.3); }
    .image-preview { margin: 10px 0; text-align: center; background: #f9fafb; border-radius: 16px; padding: 12px; border: 1px dashed #e5e7eb; }
    .image-preview img { max-width: 100%; max-height: 100px; border-radius: 12px; object-fit: cover; }
    .file-input-label { display: inline-flex; align-items: center; gap: 8px; background: #f3f4f6; padding: 8px 16px; border-radius: 40px; cursor: pointer; font-size: 12px; margin-top: 5px; transition: all 0.2s; }
    .file-input-label:hover { background: #e5e7eb; }
    .media-list { max-height: 200px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 16px; padding: 10px; margin: 10px 0; background: #f9fafb; }
    .media-item { display: flex; align-items: center; gap: 10px; padding: 8px; border-bottom: 1px solid #f0f0f0; cursor: pointer; border-radius: 12px; transition: all 0.2s; }
    .media-item:hover { background: #fef2f2; }
    .media-item.selected { background: #fef2f2; border-left: 3px solid #c91818; }
    .media-item img { width: 40px; height: 40px; object-fit: cover; border-radius: 8px; background: #f3f4f6; }
    .media-info { flex: 1; }
    .media-title { font-size: 12px; font-weight: 600; }
    .media-type { font-size: 10px; color: #9ca3af; }
    .selected-count { font-size: 11px; color: #c91818; margin-top: 5px; font-weight: 500; background: #fef2f2; padding: 6px 12px; border-radius: 30px; display: inline-block; }
    .toast-notification { position: fixed; bottom: 30px; right: 30px; background: #1f2937; color: white; padding: 12px 20px; border-radius: 40px; z-index: 2000; animation: slideIn 0.3s ease; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); font-size: 13px; display: flex; align-items: center; gap: 8px; }
    .toast-notification.success { background: #10b981; }
    .toast-notification.error { background: #ef4444; }
    .toast-notification.warning { background: #f59e0b; }
    @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    .preview-modal .modal-content { width: 95%; max-width: 1400px; padding: 0; overflow: hidden; }
    .preview-header { display: flex; justify-content: space-between; align-items: center; padding: 16px 24px; background: #3e4857; color: white; border-bottom: 1px solid #374151; }
    .preview-controls { padding: 12px 24px; background: #f3f4f6; border-bottom: 1px solid #e5e7eb; display: flex; gap: 15px; flex-wrap: wrap; align-items: center; justify-content: space-between; }
    .preview-nav-buttons { display: flex; gap: 10px; align-items: center; }
    .preview-page-buttons { display: flex; gap: 10px; align-items: center; margin-left: auto; }
    .preview-container { background: #0a0a0f; display: flex; align-items: center; justify-content: center; min-height: 550px; padding: 20px; overflow: auto; }
    .preview-screen { background: #0a0a0f; border-radius: 24px; overflow: hidden; box-shadow: 0 25px 40px -12px rgba(0,0,0,0.5); transition: all 0.2s; position: relative; }
    .preview-footer { padding: 16px 24px; background: #f9fafb; border-top: 1px solid #e5e7eb; display: flex; justify-content: flex-end; }
    .document-viewer { width: 100%; height: 100%; background: #f8fafc; color: #1e293b; overflow-y: auto; font-family: 'Segoe UI', 'Poppins', sans-serif; }
    .control-btn { padding: 6px 16px; border-radius: 20px; border: none; cursor: pointer; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; }
    .control-btn-primary { background: #c91818; color: white; }
    .control-btn-primary:hover { background: #a81414; transform: translateY(-1px); }
    .control-btn-secondary { background: #6b7280; color: white; }
    .control-btn-secondary:hover { background: #4b5563; transform: translateY(-1px); }
    .control-btn-active { background: #10b981; box-shadow: 0 0 0 2px #10b981, 0 0 0 4px rgba(16,185,129,0.2); }
    .nav-icon-btn { background: #4b5563; color: white; border: none; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; justify-content: center; }
    .nav-icon-btn:hover { background: #c91818; transform: scale(1.05); }
    .nav-icon-btn:disabled { opacity: 0.4; cursor: not-allowed; transform: none; }
    @media (max-width: 1200px) { .summary-grid { grid-template-columns: repeat(2, 1fr); } .campaigns-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 800px) { .main-content { padding: 20px; } .sidebar { width: 80px; } .sidebar .logo img { height: 40px; } .nav-item span { display: none; } .summary-grid { grid-template-columns: 1fr; } .campaigns-grid { grid-template-columns: 1fr; } }
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
        <a href="{{ route('media.index') }}" class="nav-item"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path><line x1="12" y1="11" x2="12" y2="17"></line><line x1="9" y1="14" x2="15" y2="14"></line></svg><span>Médiathèque</span></a>
        <a href="{{ route('campaigns.index') }}" class="nav-item active"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19l7-2V7l-7-2v14z"></path><path d="M12 5L3 9v6l9 4z"></path><line x1="23" y1="12" x2="19" y2="12"></line></svg><span>Campagnes</span></a>
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
      <div class="header-page-title">Campagnes</div>
      <div class="header-actions">
        <div class="search-wrapper"><input type="text" id="globalSearch" placeholder="Rechercher sur toute la page..."><i class="fas fa-search search-icon"></i></div>
        <div class="settings-container">
          <button class="settings-btn" id="settingsDropdownBtn"><i class="fas fa-sliders-h"></i></button>
          <div class="settings-dropdown" id="settingsDropdown">
            <div class="dropdown-section"><div class="dropdown-title"><i class="fas fa-palette"></i> Thème</div><div class="dropdown-option" data-theme="light"><i class="fas fa-sun"></i> Clair</div><div class="dropdown-option" data-theme="dark"><i class="fas fa-moon"></i> Sombre</div></div>
            <div class="dropdown-section"><div class="dropdown-title"><i class="fas fa-search-plus"></i> Zoom</div><div class="zoom-control"><button class="zoom-btn" id="zoomOut">A-</button><span class="zoom-value" id="zoomValue">100%</span><button class="zoom-btn" id="zoomIn">A+</button></div></div>
          </div>
        </div>
      </div>
    </div>

    <div class="library-header"><div><h1 class="library-title">Gestion des campagnes</h1><span class="library-subtitle">Créez et gérez vos campagnes de communication pour l'affichage dynamique</span></div><button class="import-btn" id="newCampaignBtn"><i class="fas fa-plus"></i> Nouvelle campagne</button></div>

    <div class="summary-grid">
      <div class="summary-card"><div class="summary-icon pink"><i class="fas fa-calendar-alt"></i></div><div class="summary-info"><span class="summary-value">{{ $totalCampaigns ?? 0 }}</span><span class="summary-label">Total campagnes</span></div></div>
      <div class="summary-card"><div class="summary-icon blue"><i class="fas fa-chart-line"></i></div><div class="summary-info"><span class="summary-value">{{ $activeCampaigns ?? 0 }}</span><span class="summary-label">Actives</span></div></div>
      <div class="summary-card"><div class="summary-icon purple"><i class="fas fa-clock"></i></div><div class="summary-info"><span class="summary-value">{{ $plannedCampaigns ?? 0 }}</span><span class="summary-label">Planifiées</span></div></div>
      <div class="summary-card"><div class="summary-icon green"><i class="fas fa-check-circle"></i></div><div class="summary-info"><span class="summary-value">{{ $finishedCampaigns ?? 0 }}</span><span class="summary-label">Terminées</span></div></div>
    </div>

    <div class="filters-toolbar">
      <div class="filter-search-wrapper"><input type="text" id="campaignSearchInput" placeholder="Rechercher une campagne..."></div>
      <div class="filter-select-wrapper"><select id="statusFilter"><option value="all">Tous les statuts</option><option value="active">Actives</option><option value="planned">Planifiées</option><option value="finished">Terminées</option></select></div>
      <div class="filter-select-wrapper"><select id="sortFilter"><option value="recent">Date: Plus récent</option><option value="oldest">Date: Plus ancien</option><option value="name">Nom: A-Z</option></select></div>
    </div>

    <div class="gallery-title-row"><h2 class="gallery-title">Toutes les campagnes</h2><span class="gallery-count" id="campaignsCount">0 campagnes</span></div>
    <div class="campaigns-grid" id="campaignsGrid"></div>
    <div class="pagination-bar" id="paginationControls"></div>
  </main>
</div>

<!-- Modals -->
<div id="campaignModal" class="modal"><div class="modal-content"><h3 id="modalTitle"><i class="fas fa-bullhorn"></i> Nouvelle campagne</h3><form id="campaignForm" enctype="multipart/form-data"><input type="hidden" id="campaignId"><input type="text" id="campaignTitle" placeholder="Titre de la campagne" required><textarea id="campaignDesc" rows="2" placeholder="Description"></textarea><div><label class="file-input-label"><i class="fas fa-upload"></i> Choisir une image de fond</label><input type="file" id="campaignImageFile" accept="image/*" style="display:none"></div><div class="image-preview" id="imagePreview"></div><select id="campaignStatus"><option value="active">Active</option><option value="planned">Planifiée</option></select><div style="display:flex;gap:10px;"><input type="date" id="campaignStart" style="flex:1"><input type="date" id="campaignEnd" style="flex:1"></div><input type="number" id="campaignProgress" placeholder="Progression (0-100)" step="1" min="0" max="100"><label><i class="fas fa-images"></i> Sélectionner les médias :</label><div class="media-list" id="mediaList"></div><div class="selected-count" id="selectedCount">0 média(s) sélectionné(s)</div><div class="modal-buttons"><button type="button" class="cancel" id="closeModalBtn">Annuler</button><button type="submit" class="submit">Enregistrer</button></div></form></div></div>

<div id="viewMediaModal" class="modal"><div class="modal-content"><h3><i class="fas fa-images"></i> Médias de la campagne</h3><div id="campaignMediaList" style="max-height:400px;overflow-y:auto;"></div><div class="modal-buttons"><button type="button" class="cancel" onclick="closeViewMediaModal()">Fermer</button></div></div></div>

<!-- Modal Preview Campaign -->
<div id="previewModal" class="modal preview-modal">
  <div class="modal-content">
    <div class="preview-header"><h3 style="margin:0;display:flex;align-items:center;gap:10px;"><i class="fas fa-tv"></i> Aperçu de la campagne</h3><button onclick="closePreviewCampaignModal()" style="background:none;border:none;color:white;font-size:20px;cursor:pointer;"><i class="fas fa-times"></i></button></div>
    <div class="preview-controls">
      <div class="preview-nav-buttons">
        <button id="prevMediaBtn" class="nav-icon-btn" title="Média précédent"><i class="fas fa-chevron-left"></i></button>
        <span id="mediaCounter" style="font-size:12px;color:#374151;">Média 1 / 0</span>
        <button id="nextMediaBtn" class="nav-icon-btn" title="Média suivant"><i class="fas fa-chevron-right"></i></button>
      </div>
      <div style="display:flex;align-items:center;gap:8px;"><i class="fas fa-play-circle"></i><select id="previewMediaType" style="padding:6px 12px;border-radius:8px;border:1px solid #cbd5e1;font-size:12px;"><option value="all">Tous les médias</option><option value="image">Images uniquement</option><option value="video">Vidéos uniquement</option></select></div>
      <div class="preview-page-buttons">
        <button id="prevPageBtnPreview" class="nav-icon-btn" title="Page précédente"><i class="fas fa-arrow-up"></i></button>
        <span id="pageCounter" style="font-size:12px;color:#374151;"></span>
        <button id="nextPageBtnPreview" class="nav-icon-btn" title="Page suivante"><i class="fas fa-arrow-down"></i></button>
        <button id="fullscreenPreviewBtn" class="control-btn control-btn-secondary"><i class="fas fa-expand"></i> Plein écran</button>
        <button id="playRotationBtn" class="control-btn control-btn-primary"><i class="fas fa-play"></i> Lecture auto</button>
        <button id="stopRotationBtn" class="control-btn control-btn-secondary"><i class="fas fa-stop"></i> Stop</button>
      </div>
    </div>
    <div class="preview-container" id="previewContainer">
      <div class="preview-screen" id="previewScreen"></div>
    </div>
    <div class="preview-footer"><button class="cancel" onclick="closePreviewCampaignModal()">Fermer</button></div>
  </div>
</div>

<script>
  const allCampaignsData = @json($campaignsData ?? []);
  const allMedia = @json($allMedia ?? []);
  let allCampaigns = [...allCampaignsData];
  let currentPage = 1;
  let itemsPerPage = 6;
  let currentStatus = 'all';
  let currentSort = 'recent';
  let currentCampaignSearch = '';
  let globalSearchTerm = '';
  let selectedMediaIds = [];
  let currentPreviewCampaign = null;
  let previewMediaIndex = 0;
  let previewInterval = null;
  let isRotating = true;
  const pdfDocuments = {};
  
  // متغيرات للسكرول التلقائي للمستندات
  let scrollInterval = null;
  let isDocumentPlaying = false;
  let currentDocumentMediaId = null;
  let currentDocumentTotalPages = 0;
  let currentDocumentPage = 1;
  let currentFilteredMedia = [];
  let currentMediaId = null;

  function updateRotationButtons() {
    const playBtn = document.getElementById('playRotationBtn');
    const stopBtn = document.getElementById('stopRotationBtn');
    if (isRotating) {
      playBtn.classList.add('control-btn-active');
      playBtn.classList.remove('control-btn-primary');
      stopBtn.classList.remove('control-btn-active');
      stopBtn.classList.add('control-btn-secondary');
    } else {
      playBtn.classList.remove('control-btn-active');
      playBtn.classList.add('control-btn-primary');
      stopBtn.classList.add('control-btn-active');
      stopBtn.classList.remove('control-btn-secondary');
    }
  }

  const statusLabels = { active: '● Active', planned: '◷ Planifiée', finished: '✓ Terminée' };
  const statusColors = { active: { bg: '#dcfce7', color: '#16a34a' }, planned: { bg: '#fff7ed', color: '#ea580c' }, finished: { bg: '#f3f4f6', color: '#6b7280' } };
  const headerGradients = { active: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)', planned: 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)', finished: 'linear-gradient(135deg, #5ee7df 0%, #b490ca 100%)' };

  function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast-notification ${type}`;
    toast.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : (type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle')}"></i> ${message}`;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
  }

  function formatDate(date) { if(!date) return '---'; const d = new Date(date); return d.toLocaleDateString('fr-FR'); }
  function escapeHtml(str) { if(!str) return ''; return str.replace(/[&<>]/g, function(m){ if(m === '&') return '&amp;'; if(m === '<') return '&lt;'; if(m === '>') return '&gt;'; return m;}); }

  function getHeaderStyle(campaign) {
    if (campaign.background_image && campaign.background_image !== 'null' && campaign.background_image !== '') {
      return `background: url('${campaign.background_image}') center/cover no-repeat;`;
    }
    return `background: ${headerGradients[campaign.status] || headerGradients.active};`;
  }

  function filterAndSort() {
    let result = [...allCampaigns];
    if (currentCampaignSearch.trim()) {
      const s = currentCampaignSearch.toLowerCase();
      result = result.filter(c => c.title.toLowerCase().includes(s) || (c.description && c.description.toLowerCase().includes(s)));
    }
    if (currentStatus !== 'all') result = result.filter(c => c.status === currentStatus);
    if (currentSort === 'recent') result.sort((a,b) => new Date(b.created_at || b.start_date) - new Date(a.created_at || a.start_date));
    else if (currentSort === 'oldest') result.sort((a,b) => new Date(a.created_at || a.start_date) - new Date(b.created_at || b.start_date));
    else if (currentSort === 'name') result.sort((a,b) => a.title.localeCompare(b.title));
    return result;
  }

  function updateGlobalSearchHighlight() {
    const searchTerm = globalSearchTerm.trim().toLowerCase();
    const cards = document.querySelectorAll('.campaign-card');
    cards.forEach(card => {
      const text = card.textContent.toLowerCase();
      if (searchTerm && text.includes(searchTerm)) {
        card.classList.add('highlight');
      } else {
        card.classList.remove('highlight');
      }
    });
  }

  // ========== FONCTIONS POUR LE SCROLL AUTOMATIQUE ==========
  function startAutoScrollForDocument(mediaId, totalPages) {
    if (scrollInterval) clearInterval(scrollInterval);
    if (!isRotating) return;
    currentDocumentMediaId = mediaId;
    currentDocumentTotalPages = totalPages;
    currentDocumentPage = pdfDocuments[`${mediaId}_current`] || 1;
    isDocumentPlaying = true;
    scrollInterval = setInterval(() => {
      if (!isDocumentPlaying || !isRotating) return;
      if (currentDocumentPage < currentDocumentTotalPages) {
        currentDocumentPage++;
        if (pdfDocuments[mediaId]) {
          const media = allMedia.find(m => m.id === mediaId);
          if (media) loadPdfAndRender(mediaId, `/storage/${media.file_path}`, currentDocumentPage);
        } else {
          const container = document.querySelector('#previewScreen .document-viewer');
          if (container) {
            container.scrollTo({ top: container.scrollHeight / currentDocumentTotalPages * (currentDocumentPage - 1), behavior: 'smooth' });
          }
        }
      } else {
        stopAutoScroll();
        if (currentFilteredMedia.length > 1) {
          previewMediaIndex = (previewMediaIndex + 1) % currentFilteredMedia.length;
          renderCampaignPreview();
        }
      }
    }, 6000);
  }
  
  function stopAutoScroll() {
    if (scrollInterval) { clearInterval(scrollInterval); scrollInterval = null; }
    isDocumentPlaying = false;
  }

  // ========== FONCTIONS POUR PDF.JS ==========
  async function loadPdfAndRender(mediaId, pdfUrl, pageNum = 1) {
    try {
      if (!pdfDocuments[mediaId]) {
        pdfDocuments[mediaId] = await pdfjsLib.getDocument(pdfUrl).promise;
      }
      const pdf = pdfDocuments[mediaId];
      const page = await pdf.getPage(pageNum);
      const canvas = document.getElementById(`pdf-canvas-${mediaId}`);
      if (!canvas) return;
      const context = canvas.getContext('2d');
      const container = canvas.parentElement;
      const maxWidth = container ? container.clientWidth - 40 : 700;
      let scale = maxWidth / page.getViewport({ scale: 1 }).width;
      scale = Math.min(scale, 1.5);
      const viewport = page.getViewport({ scale: scale });
      canvas.height = viewport.height;
      canvas.width = viewport.width;
      canvas.style.width = '100%';
      canvas.style.height = 'auto';
      await page.render({ canvasContext: context, viewport: viewport }).promise;
      pdfDocuments[`${mediaId}_current`] = pageNum;
      pdfDocuments[`${mediaId}_total`] = pdf.numPages;
      currentDocumentMediaId = mediaId;
      currentDocumentTotalPages = pdf.numPages;
      currentDocumentPage = pageNum;
      updatePageButtons(true, pageNum, pdf.numPages);
      if (pageNum === 1 && isRotating) {
        startAutoScrollForDocument(mediaId, pdf.numPages);
      }
    } catch (error) { console.error('Error loading PDF:', error); }
  }

  function updatePageButtons(isPdf, currentPage, totalPages) {
    const prevPageBtn = document.getElementById('prevPageBtnPreview');
    const nextPageBtn = document.getElementById('nextPageBtnPreview');
    const pageCounter = document.getElementById('pageCounter');
    if (isPdf && totalPages > 1) {
      prevPageBtn.style.display = 'inline-flex';
      nextPageBtn.style.display = 'inline-flex';
      pageCounter.style.display = 'inline';
      pageCounter.innerText = `Page ${currentPage} / ${totalPages}`;
    } else if (!isPdf && currentDocumentTotalPages > 1) {
      prevPageBtn.style.display = 'inline-flex';
      nextPageBtn.style.display = 'inline-flex';
      pageCounter.style.display = 'inline';
      pageCounter.innerText = `Page ${currentDocumentPage} / ${currentDocumentTotalPages}`;
    } else {
      prevPageBtn.style.display = 'none';
      nextPageBtn.style.display = 'none';
      pageCounter.style.display = 'none';
    }
  }

  function goToPrevPage() {
    if (currentDocumentMediaId && pdfDocuments[currentDocumentMediaId]) {
      const current = pdfDocuments[`${currentDocumentMediaId}_current`];
      if (current > 1) {
        const media = allMedia.find(m => m.id === currentDocumentMediaId);
        if (media) {
          loadPdfAndRender(currentDocumentMediaId, `/storage/${media.file_path}`, current - 1);
          stopAutoScroll();
        }
      }
    } else {
      const container = document.querySelector('#previewScreen .document-viewer');
      if (container) {
        const currentScroll = container.scrollTop;
        const pageHeight = container.clientHeight;
        container.scrollTo({ top: currentScroll - pageHeight, behavior: 'smooth' });
        stopAutoScroll();
        const newPage = Math.max(1, Math.ceil(container.scrollTop / pageHeight) + 1);
        currentDocumentPage = newPage;
        updatePageButtons(false, newPage, currentDocumentTotalPages);
      }
    }
  }

  function goToNextPage() {
    if (currentDocumentMediaId && pdfDocuments[currentDocumentMediaId]) {
      const current = pdfDocuments[`${currentDocumentMediaId}_current`];
      const total = pdfDocuments[`${currentDocumentMediaId}_total`];
      if (current < total) {
        const media = allMedia.find(m => m.id === currentDocumentMediaId);
        if (media) {
          loadPdfAndRender(currentDocumentMediaId, `/storage/${media.file_path}`, current + 1);
          stopAutoScroll();
        }
      }
    } else {
      const container = document.querySelector('#previewScreen .document-viewer');
      if (container) {
        const currentScroll = container.scrollTop;
        const pageHeight = container.clientHeight;
        const maxScroll = container.scrollHeight - pageHeight;
        let newScroll = currentScroll + pageHeight;
        if (newScroll > maxScroll) newScroll = maxScroll;
        container.scrollTo({ top: newScroll, behavior: 'smooth' });
        stopAutoScroll();
        const newPage = Math.min(currentDocumentTotalPages, Math.ceil((newScroll + pageHeight) / pageHeight));
        currentDocumentPage = newPage;
        updatePageButtons(false, newPage, currentDocumentTotalPages);
        if (newScroll >= maxScroll && currentFilteredMedia.length > 1 && isRotating) {
          setTimeout(() => {
            previewMediaIndex = (previewMediaIndex + 1) % currentFilteredMedia.length;
            renderCampaignPreview();
          }, 1000);
        }
      }
    }
  }

  // ========== AFFICHAGE DU CONTENU DES DOCUMENTS ==========
  async function showDocumentContent(media) {
    let extension = '';
    if (media.file_path) {
        const parts = media.file_path.split('.');
        extension = parts.pop().toLowerCase();
    }
    if ((!extension || extension === 'document') && media.type && media.type !== 'document') {
        extension = media.type.toLowerCase();
    }
    const fileUrl = `/storage/${media.file_path}`;
    
    if (extension === 'pdf') {
        return `<div class="document-viewer" style="height: 100%; display: flex; flex-direction: column; background: #525659;"><div style="flex: 1; overflow: auto; padding: 20px; text-align: center;"><canvas id="pdf-canvas-${media.id}" style="margin: 0 auto; display: block; box-shadow: 0 4px 8px rgba(0,0,0,0.2); max-width: 100%;"></canvas></div></div>`;
    }
    
    if (extension === 'docx') {
        try {
            const response = await fetch(fileUrl);
            const arrayBuffer = await response.arrayBuffer();
            const result = await mammoth.extractRawText({ arrayBuffer: arrayBuffer });
            const text = result.value;
            if (text && text.trim().length > 0) {
                const paragraphs = text.split(/\n\s*\n/);
                const formattedText = paragraphs.map(p => `<p style="margin-bottom: 15px; line-height: 1.6;">${escapeHtml(p).replace(/\n/g, '<br>')}</p>`).join('');
                const lineCount = text.split('\n').length;
                const totalPages = Math.max(1, Math.ceil(lineCount / 40));
                currentDocumentMediaId = media.id;
                currentDocumentTotalPages = totalPages;
                currentDocumentPage = 1;
                updatePageButtons(false, 1, totalPages);
                setTimeout(() => { if (isRotating) startAutoScrollForDocument(media.id, totalPages); }, 500);
                return `<div class="document-viewer" style="height: 100%; overflow-y: auto; background: white; padding: 30px;"><div style="max-width: 800px; margin: 0 auto; font-family: 'Segoe UI', sans-serif; font-size: 16px;">${formattedText}</div></div>`;
            }
        } catch(e) { console.error('Erreur DOCX:', e); }
        return `<div class="document-viewer" style="height: 100%; display: flex; align-items: center; justify-content: center; background: #f8fafc;"><div style="text-align: center;"><i class="fas fa-file-word" style="font-size: 60px; color: #2b5797; margin-bottom: 20px;"></i><p>Impossible de lire ce document Word.</p></div></div>`;
    }
    
    if (extension === 'txt') {
        try {
            const res = await fetch(fileUrl);
            const text = await res.text();
            const lines = text.split('\n');
            const totalPages = Math.max(1, Math.ceil(lines.length / 50));
            currentDocumentMediaId = media.id;
            currentDocumentTotalPages = totalPages;
            currentDocumentPage = 1;
            updatePageButtons(false, 1, totalPages);
            setTimeout(() => { if (isRotating) startAutoScrollForDocument(media.id, totalPages); }, 500);
            return `<div class="document-viewer" style="height: 100%; overflow-y: auto; background: #f8fafc; padding: 20px;"><pre style="white-space: pre-wrap; font-family: 'Courier New', monospace; font-size: 14px; line-height: 1.5; margin: 0;">${escapeHtml(text)}</pre></div>`;
        } catch(e) {}
    }
    
    return `<div class="document-viewer" style="height: 100%; display: flex; align-items: center; justify-content: center; background: #f8fafc;"><div style="text-align: center;"><i class="fas fa-file" style="font-size: 60px; color: #64748b; margin-bottom: 20px;"></i><p>Aperçu non disponible pour ce type de fichier (${extension}).</p></div></div>`;
  }

  async function updateMediaCounter() {
    const counterSpan = document.getElementById('mediaCounter');
    if (counterSpan && currentFilteredMedia.length > 0) {
      counterSpan.innerText = `Média ${previewMediaIndex + 1} / ${currentFilteredMedia.length}`;
    }
  }

  // ========== RENDER CAMPAIGN PREVIEW ==========
  async function renderCampaignPreview() {
    if (!currentPreviewCampaign) return;
    stopAutoScroll();
    
    const mediaType = document.getElementById('previewMediaType').value;
    const screenDiv = document.getElementById('previewScreen');
    
    const campaignMedia = (currentPreviewCampaign.media_ids && Array.isArray(currentPreviewCampaign.media_ids)) 
      ? allMedia.filter(m => currentPreviewCampaign.media_ids.includes(m.id))
      : [];
    currentFilteredMedia = mediaType === 'all' ? campaignMedia : campaignMedia.filter(m => m.type === mediaType);
    
    await updateMediaCounter();
    
    if (currentFilteredMedia.length > 0) {
      const media = currentFilteredMedia[previewMediaIndex % currentFilteredMedia.length];
      
      if (media.type === 'image') {
        screenDiv.style.width = `900px`;
        screenDiv.style.height = `600px`;
        screenDiv.innerHTML = `<div style="position: relative; width: 100%; height: 100%;"><img src="/storage/${media.file_path}" style="width: 100%; height: 100%; object-fit: cover;"><div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(0,0,0,0.8), transparent); padding: 20px; color: white;"><h2 style="font-size: 20px;">${escapeHtml(currentPreviewCampaign.title)}</h2>${currentPreviewCampaign.description ? `<p>${escapeHtml(currentPreviewCampaign.description)}</p>` : ''}</div></div>`;
        updatePageButtons(false, 0, 0);
        if (isRotating && currentFilteredMedia.length > 1) {
          if (previewInterval) clearInterval(previewInterval);
          previewInterval = setInterval(async () => { previewMediaIndex = (previewMediaIndex + 1) % currentFilteredMedia.length; await renderCampaignPreview(); }, 5000);
        }
      } else if (media.type === 'video') {
        screenDiv.style.width = `900px`;
        screenDiv.style.height = `600px`;
        screenDiv.innerHTML = `<div style="position: relative; width: 100%; height: 100%;"><video id="preview-video" autoplay muted playsinline style="width: 100%; height: 100%; object-fit: cover;"><source src="/storage/${media.file_path}" type="video/mp4"></video><div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(0,0,0,0.8), transparent); padding: 20px; color: white;"><h2 style="font-size: 20px;">${escapeHtml(currentPreviewCampaign.title)}</h2>${currentPreviewCampaign.description ? `<p>${escapeHtml(currentPreviewCampaign.description)}</p>` : ''}</div></div>`;
        updatePageButtons(false, 0, 0);
        const video = document.getElementById('preview-video');
        if (video) video.onended = () => { if (isRotating && currentFilteredMedia.length > 1) { previewMediaIndex = (previewMediaIndex + 1) % currentFilteredMedia.length; renderCampaignPreview(); } };
      } else {
        screenDiv.style.width = `700px`;
        screenDiv.style.height = `800px`;
        const docContent = await showDocumentContent(media);
        screenDiv.innerHTML = docContent;
        const extension = media.file_path ? media.file_path.split('.').pop().toLowerCase() : '';
        if (extension === 'pdf') {
          currentMediaId = media.id;
          setTimeout(() => { loadPdfAndRender(media.id, `/storage/${media.file_path}`, 1); }, 200);
        }
        if (previewInterval) { clearInterval(previewInterval); previewInterval = null; }
      }
    } else {
      screenDiv.style.width = `900px`;
      screenDiv.style.height = `600px`;
      screenDiv.innerHTML = `<div style="display: flex; align-items: center; justify-content: center; height: 100%; background: #1e293b; color: white;"><div style="text-align: center;"><i class="fas fa-image" style="font-size: 60px; margin-bottom: 20px;"></i><p>Aucun média associé à cette campagne</p></div></div>`;
    }
  }

  async function openPreviewCampaign(campaignId) {
    const campaign = allCampaigns.find(c => c.id === campaignId);
    if (!campaign) return;
    if (previewInterval) clearInterval(previewInterval);
    stopAutoScroll();
    currentPreviewCampaign = campaign;
    previewMediaIndex = 0;
    isRotating = true;
    updateRotationButtons();
    document.getElementById('previewModal').style.display = 'flex';
    await renderCampaignPreview();
  }

  function closePreviewCampaignModal() {
    if (previewInterval) clearInterval(previewInterval);
    previewInterval = null;
    stopAutoScroll();
    document.getElementById('previewModal').style.display = 'none';
    currentPreviewCampaign = null;
    currentDocumentMediaId = null;
  }

  function nextMedia() {
    if (currentFilteredMedia.length > 1) {
      stopAutoScroll();
      previewMediaIndex = (previewMediaIndex + 1) % currentFilteredMedia.length;
      renderCampaignPreview();
    }
  }

  function prevMedia() {
    if (currentFilteredMedia.length > 1) {
      stopAutoScroll();
      previewMediaIndex = (previewMediaIndex - 1 + currentFilteredMedia.length) % currentFilteredMedia.length;
      renderCampaignPreview();
    }
  }

  // ========== RENDER CAMPAIGNS ==========
  function renderCampaigns() {
    const filtered = filterAndSort();
    const total = filtered.length;
    const totalPages = Math.ceil(total / itemsPerPage);
    if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;
    if (currentPage < 1) currentPage = 1;
    const start = (currentPage - 1) * itemsPerPage;
    const pageItems = filtered.slice(start, start + itemsPerPage);
    const grid = document.getElementById('campaignsGrid');
    document.getElementById('campaignsCount').innerText = `${total} campagne${total > 1 ? 's' : ''}`;
    
    if (total === 0) {
      grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:60px;background:white;border-radius:12px;"><i class="fas fa-folder-open"></i> Aucune campagne trouvée</div>';
      document.getElementById('paginationControls').innerHTML = '';
      return;
    }
    
    grid.innerHTML = pageItems.map(c => {
      const progressColor = c.status === 'finished' ? '#9ca3af' : '#e02c3a';
      const headerStyle = getHeaderStyle(c);
      const isActive = c.status === 'active';
      const isPlanned = c.status === 'planned';
      const canPause = isActive || isPlanned;
      return `<div class="campaign-card" data-campaign-id="${c.id}"><div class="card-header" style="${headerStyle}"><span class="status-badge" style="background:${statusColors[c.status]?.bg || '#f3f4f6'};color:${statusColors[c.status]?.color || '#6b7280'}">${statusLabels[c.status] || statusLabels.active}</span></div><div class="card-body"><div class="campaign-title">${escapeHtml(c.title)}</div><div class="campaign-desc">${escapeHtml(c.description || '')}</div><div class="dates"><span><i class="fas fa-calendar-alt"></i> ${formatDate(c.start_date)}</span><span><i class="fas fa-calendar-check"></i> ${formatDate(c.end_date)}</span></div><div class="progress-row"><span class="progress-label">Progression</span><span class="progress-percent">${c.progress || 0}%</span></div><div class="progress-bar"><div class="progress-fill" style="width:${c.progress || 0}%;background:${progressColor}"></div></div></div><div class="card-footer"><div class="media-count" onclick="viewCampaignMedia(${c.id})"><i class="fas fa-image"></i> ${c.media_count || 0} médias</div><div class="card-actions"><button class="preview-btn" onclick="openPreviewCampaign(${c.id})" title="Aperçu"><i class="fas fa-eye"></i></button>${canPause ? `<button class="pause-btn ${isActive ? 'active-pause' : 'planned-resume'}" onclick="togglePause(${c.id}, '${c.status}')"><i class="fas ${isActive ? 'fa-pause' : 'fa-play'}"></i> ${isActive ? 'Pause' : 'Reprendre'}</button>` : ''}<button class="edit-btn" onclick="editCampaign(${c.id})"><i class="fas fa-edit"></i></button><button class="delete-btn" onclick="deleteCampaign(${c.id})"><i class="fas fa-trash"></i></button></div></div></div>`;
    }).join('');
    
    updateGlobalSearchHighlight();
    
    let pagHtml = `<button class="pag-btn" id="prevPageBtn" ${currentPage === 1 ? 'disabled' : ''}><i class="fas fa-chevron-left"></i></button>`;
    let startPage = Math.max(1, currentPage - 2);
    let endPage = Math.min(totalPages, currentPage + 2);
    if (startPage > 1) { pagHtml += `<button class="pag-btn" data-page="1">1</button>`; if (startPage > 2) pagHtml += `<button class="pag-btn disabled" style="cursor:default;">...</button>`; }
    for (let i = startPage; i <= endPage; i++) { pagHtml += `<button class="pag-btn ${i === currentPage ? 'active' : ''}" data-page="${i}">${i}</button>`; }
    if (endPage < totalPages) { if (endPage < totalPages - 1) pagHtml += `<button class="pag-btn disabled" style="cursor:default;">...</button>`; pagHtml += `<button class="pag-btn" data-page="${totalPages}">${totalPages}</button>`; }
    pagHtml += `<button class="pag-btn" id="nextPageBtn" ${currentPage === totalPages ? 'disabled' : ''}><i class="fas fa-chevron-right"></i></button>`;
    document.getElementById('paginationControls').innerHTML = pagHtml;
    
    document.querySelectorAll('[data-page]').forEach(btn => btn.addEventListener('click', () => { currentPage = parseInt(btn.dataset.page); renderCampaigns(); }));
    const prevBtn = document.getElementById('prevPageBtn'), nextBtn = document.getElementById('nextPageBtn');
    if (prevBtn) { const newPrev = prevBtn.cloneNode(true); prevBtn.parentNode.replaceChild(newPrev, prevBtn); newPrev.addEventListener('click', () => { if (currentPage > 1) { currentPage--; renderCampaigns(); } }); }
    if (nextBtn) { const newNext = nextBtn.cloneNode(true); nextBtn.parentNode.replaceChild(newNext, nextBtn); newNext.addEventListener('click', () => { if (currentPage < totalPages) { currentPage++; renderCampaigns(); } }); }
  }

  // ========== GESTION DES MÉDIAS ==========
  function renderMediaSelection() {
    const mediaList = document.getElementById('mediaList');
    if (!mediaList) return;
    if (allMedia.length === 0) { mediaList.innerHTML = '<div style="text-align:center;padding:20px;"><i class="fas fa-folder-open"></i> Aucun média disponible.</div>'; return; }
    mediaList.innerHTML = allMedia.map(m => `<div class="media-item ${selectedMediaIds.includes(m.id) ? 'selected' : ''}" onclick="toggleMediaSelection(${m.id})">${m.type === 'image' ? `<img src="/storage/${m.file_path}" onerror="this.src='https://placehold.co/40x40/f3f4f6/9ca3af?text=Image'">` : `<div style="width:40px;height:40px;background:#f3f4f6;display:flex;align-items:center;justify-content:center;border-radius:6px;"><i class="fas fa-file" style="color:#9ca3af;"></i></div>`}<div class="media-info"><div class="media-title">${escapeHtml(m.title)}</div><div class="media-type">${m.type}</div></div><i class="fas ${selectedMediaIds.includes(m.id) ? 'fa-check-circle' : 'fa-plus-circle'}" style="color:${selectedMediaIds.includes(m.id) ? '#c91818' : '#9ca3af'}; font-size: 18px;"></i></div>`).join('');
    document.getElementById('selectedCount').innerHTML = `<i class="fas fa-images"></i> ${selectedMediaIds.length} média(s) sélectionné(s)`;
  }

  window.toggleMediaSelection = function(mediaId) {
    if (selectedMediaIds.includes(mediaId)) {
      selectedMediaIds = selectedMediaIds.filter(id => id !== mediaId);
    } else {
      selectedMediaIds.push(mediaId);
    }
    renderMediaSelection();
  };

  async function togglePause(id, currentStatus) {
    let newStatus = '', actionText = '';
    if (currentStatus === 'active') { newStatus = 'planned'; actionText = 'mise en pause'; }
    else if (currentStatus === 'planned') { newStatus = 'active'; actionText = 'reprise'; }
    else { showToast('Seules les campagnes actives ou planifiées peuvent être modifiées', 'warning'); return; }
    const res = await fetch(`/campaigns/${id}`, { method: 'PUT', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }, body: JSON.stringify({ status: newStatus }) });
    if (res.ok) { showToast(`Campagne ${actionText} avec succès`, 'success'); setTimeout(() => location.reload(), 1000); }
    else showToast('Erreur lors de la modification', 'error');
  }

  async function viewCampaignMedia(campaignId) {
    const res = await fetch(`/campaigns/${campaignId}/media`);
    const media = await res.json();
    const container = document.getElementById('campaignMediaList');
    if (media.length === 0) { container.innerHTML = '<div style="text-align:center;padding:40px;"><i class="fas fa-folder-open"></i> Aucun média associé à cette campagne</div>'; }
    else { container.innerHTML = media.map(m => `<div style="display:flex;align-items:center;gap:12px;padding:10px;border-bottom:1px solid #eee;">${m.type === 'image' ? `<img src="/storage/${m.file_path}" style="width:50px;height:50px;object-fit:cover;border-radius:8px;">` : `<div style="width:50px;height:50px;background:#f3f4f6;display:flex;align-items:center;justify-content:center;border-radius:8px;"><i class="fas fa-file fa-2x" style="color:#9ca3af;"></i></div>`}<div style="flex:1;"><div style="font-weight:600;">${escapeHtml(m.title)}</div><div style="font-size:11px;color:#9ca3af;">${m.type}</div></div><button class="edit-btn" style="background:#c91818;color:white;width:auto;padding:5px 12px;" onclick="removeMediaFromCampaign(${campaignId}, ${m.id})"><i class="fas fa-trash"></i> Retirer</button></div>`).join(''); }
    document.getElementById('viewMediaModal').style.display = 'flex';
  }

  async function removeMediaFromCampaign(campaignId, mediaId) {
    const res = await fetch(`/campaigns/${campaignId}/media/${mediaId}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
    if (res.ok) { showToast('Média retiré de la campagne', 'success'); viewCampaignMedia(campaignId); setTimeout(() => location.reload(), 500); }
    else showToast('Erreur lors de la suppression', 'error');
  }

  function closeViewMediaModal() { document.getElementById('viewMediaModal').style.display = 'none'; }

  window.editCampaign = function(id) { 
    const c = allCampaigns.find(ca => ca.id === id); 
    if(c) { 
      document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit"></i> Modifier la campagne'; 
      document.getElementById('campaignId').value = c.id; 
      document.getElementById('campaignTitle').value = c.title; 
      document.getElementById('campaignDesc').value = c.description || ''; 
      if (c.background_image && c.background_image !== 'null' && c.background_image !== '') { document.getElementById('imagePreview').innerHTML = `<img src="${c.background_image}" alt="Aperçu">`; } else { document.getElementById('imagePreview').innerHTML = ''; }
      document.getElementById('campaignStatus').value = c.status; 
      document.getElementById('campaignStart').value = c.start_date || ''; 
      document.getElementById('campaignEnd').value = c.end_date || ''; 
      document.getElementById('campaignProgress').value = c.progress || 0;
      selectedMediaIds = (c.media_ids && Array.isArray(c.media_ids)) ? [...c.media_ids] : [];
      renderMediaSelection();
      document.getElementById('campaignModal').style.display = 'flex'; 
    } 
  };
  
  window.deleteCampaign = async function(id) { 
    if(confirm('Êtes-vous sûr de vouloir supprimer cette campagne ?')) { 
      const res = await fetch(`/campaigns/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
      if(res.ok) { showToast('Campagne supprimée avec succès', 'success'); location.reload(); }
      else showToast('Erreur lors de la suppression', 'error');
    } 
  };

  // ========== INITIALISATION ==========
  const globalSearchInput = document.getElementById('globalSearch');
  const campaignSearchInput = document.getElementById('campaignSearchInput');
  const statusSelect = document.getElementById('statusFilter');
  const sortSelect = document.getElementById('sortFilter');

  function updateFilters() {
    currentCampaignSearch = campaignSearchInput.value;
    currentStatus = statusSelect.value;
    currentSort = sortSelect.value;
    currentPage = 1;
    renderCampaigns();
  }

  function updateGlobalSearch() {
    globalSearchTerm = globalSearchInput.value;
    updateGlobalSearchHighlight();
  }

  campaignSearchInput.addEventListener('input', updateFilters);
  globalSearchInput.addEventListener('input', updateGlobalSearch);
  statusSelect.addEventListener('change', updateFilters);
  sortSelect.addEventListener('change', updateFilters);

  const fileInput = document.getElementById('campaignImageFile');
  const imagePreview = document.getElementById('imagePreview');
  document.querySelector('.file-input-label')?.addEventListener('click', () => fileInput.click());
  fileInput?.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (file) { const reader = new FileReader(); reader.onload = function(ev) { imagePreview.innerHTML = `<img src="${ev.target.result}" alt="Aperçu"><div style="font-size:10px;color:#666;margin-top:5px;">${file.name}</div>`; }; reader.readAsDataURL(file); }
  });

  const modal = document.getElementById('campaignModal');
  document.getElementById('newCampaignBtn').addEventListener('click', () => { 
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-bullhorn"></i> Nouvelle campagne'; 
    document.getElementById('campaignForm').reset(); 
    document.getElementById('campaignId').value = ''; 
    imagePreview.innerHTML = '';
    fileInput.value = '';
    selectedMediaIds = [];
    renderMediaSelection();
    modal.style.display = 'flex'; 
  });
  document.getElementById('closeModalBtn').addEventListener('click', () => modal.style.display = 'none');
  window.addEventListener('click', (e) => { if(e.target === modal) modal.style.display = 'none'; if(e.target === document.getElementById('viewMediaModal')) closeViewMediaModal(); if(e.target === document.getElementById('previewModal')) closePreviewCampaignModal(); });
  
  const statusSelectModal = document.getElementById('campaignStatus');
  const progressInputModal = document.getElementById('campaignProgress');
  statusSelectModal?.addEventListener('change', () => {
    if (statusSelectModal.value === 'finished') { progressInputModal.value = 100; progressInputModal.disabled = true; } 
    else { progressInputModal.disabled = false; }
  });
  
  document.getElementById('campaignForm').addEventListener('submit', async (e) => { 
    e.preventDefault(); 
    const id = document.getElementById('campaignId').value; 
    let progress = parseInt(document.getElementById('campaignProgress').value) || 0;
    let status = document.getElementById('campaignStatus').value;
    if (status === 'finished') progress = 100;
    if (progress >= 100) status = 'finished';
    const formData = new FormData();
    formData.append('title', document.getElementById('campaignTitle').value);
    formData.append('description', document.getElementById('campaignDesc').value);
    formData.append('status', status);
    formData.append('start_date', document.getElementById('campaignStart').value);
    formData.append('end_date', document.getElementById('campaignEnd').value);
    formData.append('progress', progress);
    formData.append('media_ids', JSON.stringify(selectedMediaIds));
    if (fileInput.files[0]) formData.append('background_image', fileInput.files[0]);
    if (id) formData.append('_method', 'PUT');
    const url = id ? `/campaigns/${id}` : '{{ route("campaigns.store") }}';
    const res = await fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: formData });
    if(res.ok) { showToast(id ? 'Campagne modifiée avec succès' : 'Campagne créée avec succès', 'success'); location.reload(); }
    else { const error = await res.json(); showToast('Erreur: ' + (error.message || 'Vérifiez les champs'), 'error'); } 
  });

  document.getElementById('playRotationBtn')?.addEventListener('click', () => { 
    isRotating = true; 
    updateRotationButtons(); 
    if (currentPreviewCampaign) {
      if (currentDocumentMediaId && pdfDocuments[currentDocumentMediaId]) {
        startAutoScrollForDocument(currentDocumentMediaId, pdfDocuments[`${currentDocumentMediaId}_total`]);
      } else {
        renderCampaignPreview();
      }
    }
  });
  document.getElementById('stopRotationBtn')?.addEventListener('click', () => { 
    isRotating = false; 
    stopAutoScroll(); 
    if (previewInterval) clearInterval(previewInterval); 
    previewInterval = null; 
    updateRotationButtons(); 
  });
  document.getElementById('prevMediaBtn')?.addEventListener('click', () => prevMedia());
  document.getElementById('nextMediaBtn')?.addEventListener('click', () => nextMedia());
  document.getElementById('prevPageBtnPreview')?.addEventListener('click', () => goToPrevPage());
  document.getElementById('nextPageBtnPreview')?.addEventListener('click', () => goToNextPage());

  const dropdownBtn = document.getElementById('settingsDropdownBtn'), dropdown = document.getElementById('settingsDropdown');
  dropdownBtn?.addEventListener('click', (e) => { e.stopPropagation(); dropdown.classList.toggle('show'); });
  document.addEventListener('click', () => dropdown?.classList.remove('show'));
  let zoom = 100; const zoomSpan = document.getElementById('zoomValue'), mainDiv = document.getElementById('mainContent');
  document.getElementById('zoomIn')?.addEventListener('click', () => { if(zoom < 150) { zoom += 10; zoomSpan.innerText = zoom+'%'; mainDiv.style.zoom = zoom/100; } });
  document.getElementById('zoomOut')?.addEventListener('click', () => { if(zoom > 70) { zoom -= 10; zoomSpan.innerText = zoom+'%'; mainDiv.style.zoom = zoom/100; } });
  
  document.querySelectorAll('[data-theme]').forEach(opt => opt.addEventListener('click', () => { 
    const theme = opt.dataset.theme; 
    if(theme === 'dark') { 
      mainDiv.style.backgroundColor = '#1e293b'; mainDiv.style.color = '#e2e8f0';
      document.querySelectorAll('.summary-card, .campaign-card, .modal-content, .filter-search-wrapper input, .filter-select-wrapper select').forEach(el => { if(el) { el.style.backgroundColor = '#2d3a4e'; el.style.color = '#e2e8f0'; el.style.borderColor = '#4a5568'; } });
    } else { 
      mainDiv.style.backgroundColor = '#fff'; mainDiv.style.color = '#000';
      document.querySelectorAll('.summary-card, .campaign-card, .modal-content, .filter-search-wrapper input, .filter-select-wrapper select').forEach(el => { if(el) { el.style.backgroundColor = 'white'; el.style.color = '#000'; el.style.borderColor = '#cbd5e1'; } });
    } 
    dropdown.classList.remove('show'); 
  }));

  document.getElementById('previewMediaType')?.addEventListener('change', () => { previewMediaIndex = 0; renderCampaignPreview(); });
  document.getElementById('fullscreenPreviewBtn')?.addEventListener('click', () => { const container = document.getElementById('previewContainer'); if (container.requestFullscreen) container.requestFullscreen(); else if (container.webkitRequestFullscreen) container.webkitRequestFullscreen(); else if (container.msRequestFullscreen) container.msRequestFullscreen(); });

  renderMediaSelection();
  renderCampaigns();
</script>
</body>
</html>