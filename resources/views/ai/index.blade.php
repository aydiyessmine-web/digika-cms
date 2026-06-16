<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Assistant IA - DigiCMS</title>
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

    /* Library Header */
    .library-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; }
    .library-title { font-size: 23px; font-weight: 700; color: #0f172a; }
    .library-subtitle { font-size: 12px; color: #64748b; }

    /* ========================================
       PAGE ASSISTANT IA - STYLES
    ======================================== */

    /* AI Tools Panel (en haut, horizontal) */
    .ai-tools-panel {
      background: #ffffff;
      border: 1px solid #eef2f6;
      border-radius: 20px;
      padding: 20px;
      margin-bottom: 24px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    }

    .tools-header {
      margin-bottom: 16px;
      padding-bottom: 12px;
      border-bottom: 2px solid #eef2f6;
    }

    .tools-header h3 {
      font-size: 16px;
      font-weight: 700;
      color: #1f2937;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .tools-header h3 i {
      color: #c91818;
      font-size: 18px;
    }

    .tools-header p {
      font-size: 12px;
      color: #6b7280;
      margin-top: 4px;
    }

    .tools-list {
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
    }

    .tool-card {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 10px 16px;
      background: #f8fafc;
      border: 1px solid #eef2f6;
      border-radius: 14px;
      cursor: pointer;
      transition: all 0.2s ease;
      flex: 1;
      min-width: 160px;
    }

    .tool-card:hover {
      background: #fef2f2;
      border-color: #c91818;
      transform: translateY(-2px);
    }

    .tool-icon {
      width: 36px;
      height: 36px;
      background: #ffffff;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      color: #c91818;
    }

    .tool-info h4 {
      font-size: 13px;
      font-weight: 600;
      color: #1f2937;
    }

    .tool-info p {
      font-size: 10px;
      color: #9ca3af;
    }

    /* AI Grid Layout (Chat + History) */
    .ai-grid {
      display: flex;
      gap: 24px;
    }

    /* CENTER PANEL : CHAT (agrandi) */
    .ai-chat-panel {
      flex: 2;
      background: #ffffff;
      border: 1px solid #eef2f6;
      border-radius: 20px;
      display: flex;
      flex-direction: column;
      overflow: hidden;
      box-shadow: 0 1px 3px rgba(0,0,0,0.03);
      max-height: calc(100vh - 120px);
    }

    .chat-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 18px 24px;
      border-bottom: 1px solid #eef2f6;
      background: #ffffff;
    }

    .ai-status {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .ai-status i {
      font-size: 28px;
      color: #c91818;
    }

    .ai-status span:first-of-type {
      font-size: 16px;
      font-weight: 700;
      color: #1f2937;
    }

    .status-badge {
      font-size: 11px;
      background: #dcfce7;
      color: #16a34a;
      padding: 4px 10px;
      border-radius: 30px;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .status-badge i {
      font-size: 8px;
      color: #22c55e;
    }

    .clear-btn {
      background: #f3f4f6;
      border: none;
      padding: 8px 16px;
      border-radius: 30px;
      font-size: 12px;
      font-weight: 500;
      color: #4b5563;
      cursor: pointer;
      transition: all 0.2s;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .clear-btn:hover {
      background: #e5e7eb;
      color: #c91818;
    }

    .chat-messages {
      flex: 1;
      padding: 24px;
      overflow-y: auto;
      background: #fafbfc;
    }

    .message {
      display: flex;
      gap: 14px;
      margin-bottom: 24px;
    }

    .message.bot .avatar {
      background: #fef2f2;
      color: #c91818;
    }

    .message.user .avatar {
      background: #e0e7ff;
      color: #3b82f6;
    }

    .message .avatar {
      width: 40px;
      height: 40px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      flex-shrink: 0;
    }

    .message .content {
      flex: 1;
    }

    .message .name {
      font-size: 13px;
      font-weight: 600;
      color: #1f2937;
      margin-bottom: 6px;
    }

    .message .text {
      font-size: 13px;
      color: #4b5563;
      line-height: 1.5;
      margin-bottom: 8px;
      background: #ffffff;
      padding: 12px 16px;
      border-radius: 16px;
      border: 1px solid #eef2f6;
    }

    .message.bot .text {
      background: #ffffff;
    }

    .message.user .text {
      background: #fef2f2;
      border-color: #fecaca;
    }

    .chat-input {
      padding: 16px 24px;
      border-top: 1px solid #eef2f6;
      background: #ffffff;
      display: flex;
      gap: 12px;
    }

    .chat-input textarea {
      flex: 1;
      border: 1px solid #e5e7eb;
      border-radius: 20px;
      padding: 12px 16px;
      font-size: 13px;
      font-family: 'Poppins', sans-serif;
      resize: none;
      outline: none;
      transition: all 0.2s;
      background: #fafbfc;
    }

    .chat-input textarea:focus {
      border-color: #c91818;
      box-shadow: 0 0 0 3px rgba(201,24,24,0.1);
      background: #ffffff;
    }

    .send-btn {
      background: #c91818;
      border: none;
      border-radius: 30px;
      padding: 0 24px;
      color: white;
      font-weight: 600;
      font-size: 13px;
      cursor: pointer;
      transition: all 0.2s;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .send-btn:hover {
      background: #a81414;
      transform: translateY(-1px);
    }

    /* RIGHT PANEL : HISTORY & STATS (gardé pareil) */
    .ai-history-panel {
      flex: 1;
      background: #ffffff;
      border: 1px solid #eef2f6;
      border-radius: 20px;
      padding: 20px;
      height: fit-content;
      box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    }

    .history-header {
      margin-bottom: 16px;
      padding-bottom: 12px;
      border-bottom: 2px solid #eef2f6;
    }

    .history-header h3 {
      font-size: 14px;
      font-weight: 700;
      color: #1f2937;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .history-header h3 i {
      color: #c91818;
      font-size: 14px;
    }

    .history-list {
      max-height: 220px;
      overflow-y: auto;
      margin-bottom: 20px;
    }

    .history-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 10px 0;
      border-bottom: 1px solid #f0f0f0;
      cursor: pointer;
      transition: all 0.2s;
    }

    .history-item:hover {
      background: #fef2f2;
      margin: 0 -8px;
      padding: 10px 8px;
      border-radius: 10px;
    }

    .history-icon {
      width: 36px;
      height: 36px;
      background: #f3f4f6;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 16px;
      color: #c91818;
    }

    .history-info {
      flex: 1;
    }

    .history-title {
      font-size: 12px;
      font-weight: 600;
      color: #1f2937;
      margin-bottom: 4px;
    }

    .history-time {
      font-size: 10px;
      color: #9ca3af;
      display: flex;
      align-items: center;
      gap: 4px;
    }

    /* Stats Section */
    .stats-section {
      margin-top: 20px;
      padding-top: 16px;
      border-top: 1px solid #eef2f6;
    }

    .stats-section h3 {
      font-size: 14px;
      font-weight: 700;
      color: #1f2937;
      margin-bottom: 16px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .stats-section h3 i {
      color: #c91818;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 12px;
    }

    .stat-item {
      background: #f8fafc;
      border-radius: 12px;
      padding: 12px;
      text-align: center;
    }

    .stat-value {
      font-size: 20px;
      font-weight: 700;
      color: #1f2937;
      display: block;
      margin-bottom: 4px;
    }

    .stat-label {
      font-size: 10px;
      color: #6b7280;
    }

    /* Keywords Section */
    .keywords-section {
      margin-top: 20px;
      padding-top: 16px;
      border-top: 1px solid #eef2f6;
    }

    .keywords-section h3 {
      font-size: 14px;
      font-weight: 700;
      color: #1f2937;
      margin-bottom: 16px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .keywords-section h3 i {
      color: #f97316;
    }

    .keywords-cloud {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
    }

    .keyword-tag {
      background: #f3f4f6;
      padding: 6px 14px;
      border-radius: 30px;
      font-size: 11px;
      font-weight: 500;
      color: #4b5563;
      cursor: pointer;
      transition: all 0.2s;
    }

    .keyword-tag:hover {
      background: #c91818;
      color: white;
      transform: translateY(-2px);
    }

    /* Loading animation */
    .loading-dots {
      display: flex;
      gap: 4px;
      padding: 12px 16px;
    }

    .loading-dots span {
      width: 8px;
      height: 8px;
      background: #c91818;
      border-radius: 50%;
      animation: bounce 1.4s infinite ease-in-out both;
    }

    .loading-dots span:nth-child(1) { animation-delay: -0.32s; }
    .loading-dots span:nth-child(2) { animation-delay: -0.16s; }

    @keyframes bounce {
      0%, 80%, 100% { transform: scale(0); }
      40% { transform: scale(1); }
    }

    /* Toast Notification */
    .toast-notification {
      position: fixed;
      bottom: 30px;
      right: 30px;
      background: #1f2937;
      color: white;
      padding: 12px 20px;
      border-radius: 40px;
      z-index: 2000;
      animation: slideIn 0.3s ease;
      box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
      font-size: 13px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .toast-notification.success { background: #10b981; }
    .toast-notification.error { background: #ef4444; }
    .toast-notification.warning { background: #f59e0b; }
    @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

    /* Responsive */
    @media (max-width: 1200px) {
      .ai-grid {
        flex-direction: column;
      }
      .ai-history-panel {
        width: 100%;
      }
      .tools-list {
        flex-direction: column;
      }
      .tool-card {
        min-width: auto;
      }
    }
    @media (max-width: 800px) {
      .main-content { padding: 20px; }
      .sidebar { width: 80px; }
      .sidebar .logo img { height: 40px; }
      .nav-item span { display: none; }
      .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }
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
        <a href="{{ route('campaigns.index') }}" class="nav-item"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19l7-2V7l-7-2v14z"></path><path d="M12 5L3 9v6l9 4z"></path><line x1="23" y1="12" x2="19" y2="12"></line></svg><span>Campagnes</span></a>
        <div class="nav-section-title">INTELLIGENCE IA</div>
        <a href="#" class="nav-item active"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="10" r="3"></circle><path d="M12 2a8 8 0 0 0-8 8c0 4 3 6 8 10 5-4 8-6 8-10a8 8 0 0 0-8-8z"></path></svg><span>Assistant IA</span></a>
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
      <div class="header-page-title">Assistant IA</div>
      <div class="header-actions">
        <div class="search-wrapper"><input type="text" id="globalSearch" placeholder="Rechercher dans l'historique..."><i class="fas fa-search search-icon"></i></div>
        <div class="settings-container">
          <button class="settings-btn" id="settingsDropdownBtn"><i class="fas fa-sliders-h"></i></button>
          <div class="settings-dropdown" id="settingsDropdown">
            <div class="dropdown-section"><div class="dropdown-title"><i class="fas fa-palette"></i> Thème</div><div class="dropdown-option" data-theme="light"><i class="fas fa-sun"></i> Clair</div><div class="dropdown-option" data-theme="dark"><i class="fas fa-moon"></i> Sombre</div></div>
            <div class="dropdown-section"><div class="dropdown-title"><i class="fas fa-search-plus"></i> Zoom</div><div class="zoom-control"><button class="zoom-btn" id="zoomOut">A-</button><span class="zoom-value" id="zoomValue">100%</span><button class="zoom-btn" id="zoomIn">A+</button></div></div>
          </div>
        </div>
      </div>
    </div>

    <div class="library-header">
      <div><h1 class="library-title">Assistant IA - DigiCMS</h1><span class="library-subtitle">Générez, optimisez et évaluez vos contenus marketing pour l'affichage dynamique</span></div>
    </div>

    <!-- TOOLS PANEL (déplacé en haut) -->
    <div class="ai-tools-panel">
      <div class="tools-header">
        <h3><i class="fas fa-magic"></i> Fonctionnalités IA</h3>
        <p>Générez du contenu en un clic</p>
      </div>
      <div class="tools-list">
        <button class="tool-card" data-tool="slogan">
          <div class="tool-icon"><i class="fas fa-bullhorn"></i></div>
          <div class="tool-info">
            <h4>Générer un slogan</h4>
            <p>Titre & accroche marketing</p>
          </div>
        </button>
        <button class="tool-card" data-tool="keywords">
          <div class="tool-icon"><i class="fas fa-tags"></i></div>
          <div class="tool-info">
            <h4>Mots-clés / Hashtags</h4>
            <p>Suggestions pertinentes</p>
          </div>
        </button>
        <button class="tool-card" data-tool="evaluate">
          <div class="tool-icon"><i class="fas fa-star-of-life"></i></div>
          <div class="tool-info">
            <h4>Évaluer un message</h4>
            <p>Score qualité & conseils</p>
          </div>
        </button>
        <button class="tool-card" data-tool="visual">
          <div class="tool-icon"><i class="fas fa-palette"></i></div>
          <div class="tool-info">
            <h4>Idée de visuel</h4>
            <p>Description d'affiche</p>
          </div>
        </button>
        <button class="tool-card" data-tool="description">
          <div class="tool-icon"><i class="fas fa-file-alt"></i></div>
          <div class="tool-info">
            <h4>Description affichage</h4>
            <p>Texte adapté aux écrans</p>
          </div>
        </button>
      </div>
    </div>

    <!-- AI GRID (Chat + History) -->
    <div class="ai-grid">
      
      <!-- CENTER PANEL : Chat / Generation (agrandi) -->
      <div class="ai-chat-panel">
        <div class="chat-header">
          <div class="ai-status">
            <i class="fas fa-robot"></i>
            <span>DigiCMS IA</span>
            <span class="status-badge"><i class="fas fa-circle"></i> Prêt à générer</span>
          </div>
          <button id="clearChatBtn" class="clear-btn" title="Nouvelle conversation"><i class="fas fa-plus"></i> Nouvelle conversation</button>
        </div>
        <div class="chat-messages" id="chatMessages">
          <div class="message bot">
            <div class="avatar"><i class="fas fa-robot"></i></div>
            <div class="content">
              <div class="name">DigiCMS IA</div>
              <div class="text">Bonjour ! Je suis votre assistant IA DigiCMS.</div>
              <div class="text">Je peux vous aider à générer des slogans, proposer des mots-clés, évaluer la qualité de vos messages marketing, suggérer des idées de visuels et rédiger des descriptions d'affichage dynamique.</div>
              <div class="text">Choisissez une fonctionnalité ci-dessus ou écrivez votre demande directement !</div>
            </div>
          </div>
        </div>
        <div class="chat-input">
          <textarea id="userPrompt" placeholder="Ex: Génère un slogan pour une promo été sur écran extérieur..." rows="2"></textarea>
          <button id="sendPromptBtn" class="send-btn"><i class="fas fa-paper-plane"></i> Générer</button>
        </div>
      </div>

      <!-- RIGHT PANEL : Historique & Stats (gardé pareil) -->
      <div class="ai-history-panel">
        <div class="history-header">
          <h3><i class="fas fa-history"></i> Historique récent</h3>
        </div>
        <div class="history-list" id="historyList">
          <div class="history-item" data-prompt="Génère un slogan percutant pour notre nouvelle campagne">
            <div class="history-icon"><i class="fas fa-bullhorn"></i></div>
            <div class="history-info">
              <div class="history-title">Slogans – Promo Été 2025</div>
              <div class="history-time"><i class="far fa-clock"></i> Il y a 3 min</div>
            </div>
          </div>
          <div class="history-item" data-prompt="Évalue la qualité de ce message marketing pour notre vitrine">
            <div class="history-icon"><i class="fas fa-star-of-life"></i></div>
            <div class="history-info">
              <div class="history-title">Évaluation message vitrine</div>
              <div class="history-time"><i class="far fa-clock"></i> Il y a 8 min</div>
            </div>
          </div>
          <div class="history-item" data-prompt="Propose des mots-clés stratégiques pour la rentrée scolaire">
            <div class="history-icon"><i class="fas fa-tags"></i></div>
            <div class="history-info">
              <div class="history-title">Mots-clés rentrée scolaire</div>
              <div class="history-time"><i class="far fa-clock"></i> Il y a 12 min</div>
            </div>
          </div>
          <div class="history-item" data-prompt="Donne-moi une idée créative de visuel pour le Ramadan">
            <div class="history-icon"><i class="fas fa-palette"></i></div>
            <div class="history-info">
              <div class="history-title">Idée visuel Ramadan</div>
              <div class="history-time"><i class="far fa-clock"></i> Hier, 14:20</div>
            </div>
          </div>
          <div class="history-item" data-prompt="Rédige une description attrayante pour l'écran du Hall A">
            <div class="history-icon"><i class="fas fa-file-alt"></i></div>
            <div class="history-info">
              <div class="history-title">Description écran Hall A</div>
              <div class="history-time"><i class="far fa-clock"></i> Hier, 10:05</div>
            </div>
          </div>
        </div>
        
        <div class="stats-section">
          <h3><i class="fas fa-chart-line"></i> Statistiques IA</h3>
          <div class="stats-grid">
            <div class="stat-item">
              <span class="stat-value">34</span>
              <span class="stat-label">Requêtes aujourd'hui</span>
            </div>
            <div class="stat-item">
              <span class="stat-value">12</span>
              <span class="stat-label">Slogans générés</span>
            </div>
            <div class="stat-item">
              <span class="stat-value">8</span>
              <span class="stat-label">Évaluations faites</span>
            </div>
            <div class="stat-item">
              <span class="stat-value">47</span>
              <span class="stat-label">Mots-clés proposés</span>
            </div>
            <div class="stat-item">
              <span class="stat-value">7.4</span>
              <span class="stat-label">Score moyen /10</span>
            </div>
          </div>
        </div>
        
        <div class="keywords-section">
          <h3><i class="fas fa-fire"></i> Mots-clés populaires</h3>
          <div class="keywords-cloud">
            <span class="keyword-tag" data-keyword="Promo">Promo</span>
            <span class="keyword-tag" data-keyword="Été 2025">Été 2025</span>
            <span class="keyword-tag" data-keyword="Soldes">Soldes</span>
            <span class="keyword-tag" data-keyword="Affichage">Affichage</span>
            <span class="keyword-tag" data-keyword="Marketing">Marketing</span>
            <span class="keyword-tag" data-keyword="Digika">Digika</span>
            <span class="keyword-tag" data-keyword="Campagne">Campagne</span>
            <span class="keyword-tag" data-keyword="Rentrée">Rentrée</span>
            <span class="keyword-tag" data-keyword="Visuel">Visuel</span>
            <span class="keyword-tag" data-keyword="Écran">Écran</span>
            <span class="keyword-tag" data-keyword="Lancement">Lancement</span>
            <span class="keyword-tag" data-keyword="Boutique">Boutique</span>
          </div>
        </div>
      </div>
    </div>
  </main>
</div>

<script>
  let chatHistory = [];

  function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast-notification ${type}`;
    toast.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-info-circle'}"></i> ${message}`;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
  }

  function formatMessage(text) {
    return text.replace(/\n/g, '<br>');
  }

  function addMessage(text, isUser = false) {
    const chatContainer = document.getElementById('chatMessages');
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${isUser ? 'user' : 'bot'}`;
    
    messageDiv.innerHTML = `
      <div class="avatar"><i class="fas ${isUser ? 'fa-user' : 'fa-robot'}"></i></div>
      <div class="content">
        <div class="name">${isUser ? 'Vous' : 'DigiCMS IA'}</div>
        <div class="text">${formatMessage(text)}</div>
      </div>
    `;
    
    chatContainer.appendChild(messageDiv);
    chatContainer.scrollTop = chatContainer.scrollHeight;
    
    if (isUser) {
      chatHistory.push({ role: 'user', content: text });
    } else {
      chatHistory.push({ role: 'assistant', content: text });
    }
    saveHistory();
  }

  function addLoadingIndicator() {
    const chatContainer = document.getElementById('chatMessages');
    const loadingDiv = document.createElement('div');
    loadingDiv.className = 'message bot';
    loadingDiv.id = 'loadingIndicator';
    loadingDiv.innerHTML = `
      <div class="avatar"><i class="fas fa-robot"></i></div>
      <div class="content">
        <div class="name">DigiCMS IA</div>
        <div class="loading-dots"><span></span><span></span><span></span></div>
      </div>
    `;
    chatContainer.appendChild(loadingDiv);
    chatContainer.scrollTop = chatContainer.scrollHeight;
  }

  function removeLoadingIndicator() {
    const loadingDiv = document.getElementById('loadingIndicator');
    if (loadingDiv) loadingDiv.remove();
  }

  // ============================================
  // دالة الذكاء الاصطناعي - تخدم مع OpenRouter
  // ============================================
  // ============================================
// دالة الذكاء الاصطناعي - تخدم مع OpenRouter عبر Laravel
// ============================================
async function generateAIResponse(prompt, toolType = null) {
    let systemPrompt = "Tu es un assistant IA intelligent, naturel et conversationnel. Tu t'appelles DigiCMS IA. Tu parles UNIQUEMENT en français. Tu réponds à toutes les questions de façon naturelle, utile et professionnelle. Si on te demande l'heure, donne l'heure actuelle. Si on te demande la date, donne la date. Sois amical et serviable. Tu peux répondre à toutes les questions, pas seulement le marketing.";
    
    if (toolType === 'slogan') {
        systemPrompt = "Tu es un expert en marketing digital. Génère 3 slogans créatifs, percutants et professionnels pour une campagne marketing en français. Utilise des émojis et des sauts de ligne.";
    } else if (toolType === 'keywords') {
        systemPrompt = "Tu es un spécialiste SEO. Propose 10 hashtags et mots-clés pertinents et tendance pour une campagne marketing en français.";
    } else if (toolType === 'evaluate') {
        systemPrompt = "Tu es consultant marketing. Évalue ce message publicitaire: donne un score sur 10, les points forts, les points faibles, et 3 conseils d'amélioration. Réponds UNIQUEMENT en français.";
    } else if (toolType === 'visual') {
        systemPrompt = "Tu es directeur artistique. Propose une idée de visuel détaillée: couleurs, typographie, composition, message principal, CTA. Réponds UNIQUEMENT en français.";
    } else if (toolType === 'description') {
        systemPrompt = "Tu es rédacteur publicitaire. Rédige une description d'affichage dynamique impactante: titre accrocheur, sous-titre, CTA. Réponds UNIQUEMENT en français.";
    }
    
    try {
        const response = await fetch('/ai/chat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                prompt: prompt,
                system_prompt: systemPrompt
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            return data.response;
        } else {
            return "⚠️ " + (data.error || "Erreur inconnue");
        }
        
    } catch (error) {
        console.error('Erreur:', error);
        return "⚠️ Erreur de connexion au serveur. Vérifiez que Laravel est en cours d'exécution.";c
    }
}

  async function sendRequest(prompt, toolType = null) {
    if (!prompt.trim()) {
      showToast('Veuillez écrire une demande', 'warning');
      return;
    }
    
    addMessage(prompt, true);
    document.getElementById('userPrompt').value = '';
    addLoadingIndicator();
    const response = await generateAIResponse(prompt, toolType);
    removeLoadingIndicator();
    addMessage(response, false);
  }

  function saveHistory() {
    localStorage.setItem('aiChatHistory', JSON.stringify(chatHistory.slice(-20)));
  }

  function loadHistory() {
    const saved = localStorage.getItem('aiChatHistory');
    if (saved) {
      try {
        const history = JSON.parse(saved);
        if (history.length > 0) {
          const chatContainer = document.getElementById('chatMessages');
          chatContainer.innerHTML = '';
          history.forEach(msg => {
            addMessage(msg.content, msg.role === 'user');
          });
        }
      } catch(e) {}
    }
  }

  function clearConversation() {
    const chatContainer = document.getElementById('chatMessages');
    chatContainer.innerHTML = `
      <div class="message bot">
        <div class="avatar"><i class="fas fa-robot"></i></div>
        <div class="content">
          <div class="name">DigiCMS IA</div>
          <div class="text">Bonjour ! Je suis votre assistant IA DigiCMS.</div>
          <div class="text">Je peux vous aider à générer des slogans, proposer des mots-clés, évaluer la qualité de vos messages marketing, suggérer des idées de visuels et rédiger des descriptions d'affichage dynamique.</div>
          <div class="text">Choisissez une fonctionnalité ci-dessus ou écrivez votre demande directement !</div>
        </div>
      </div>
    `;
    chatHistory = [];
    saveHistory();
    showToast('Conversation effacée', 'success');
  }

  document.addEventListener('DOMContentLoaded', () => {
    loadHistory();
    
    if (chatHistory.length === 0) {
      clearConversation();
    }
    
    document.querySelectorAll('.tool-card').forEach(btn => {
      btn.addEventListener('click', async () => {
        const tool = btn.dataset.tool;
        let prompt = '';
        switch(tool) {
          case 'slogan': prompt = 'Génère un slogan percutant pour notre nouvelle campagne marketing'; break;
          case 'keywords': prompt = 'Propose 10 mots-clés stratégiques pour une campagne d\'affichage dynamique'; break;
          case 'evaluate': prompt = 'Évalue la qualité de ce message marketing : "Profitez de nos promotions exceptionnelles !"'; break;
          case 'visual': prompt = 'Donne-moi une idée créative de visuel pour une campagne d\'été sur écran extérieur'; break;
          case 'description': prompt = 'Rédige une description attrayante pour une campagne d\'affichage dynamique'; break;
        }
        await sendRequest(prompt, tool);
      });
    });
    
    document.getElementById('sendPromptBtn')?.addEventListener('click', () => {
      sendRequest(document.getElementById('userPrompt').value);
    });
    
    document.getElementById('userPrompt')?.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        document.getElementById('sendPromptBtn').click();
      }
    });
    
    document.getElementById('clearChatBtn')?.addEventListener('click', clearConversation);
    
    document.querySelectorAll('.keyword-tag').forEach(tag => {
      tag.addEventListener('click', () => {
        document.getElementById('userPrompt').value = `Propose des mots-clés similaires à "${tag.textContent}" pour ma campagne`;
        document.getElementById('sendPromptBtn').click();
      });
    });
    
    document.querySelectorAll('.history-item').forEach(item => {
      item.addEventListener('click', () => {
        const prompt = item.getAttribute('data-prompt') || item.querySelector('.history-title')?.textContent || '';
        document.getElementById('userPrompt').value = prompt;
        document.getElementById('sendPromptBtn').click();
      });
    });
    
    const dropdownBtn = document.getElementById('settingsDropdownBtn');
    const dropdown = document.getElementById('settingsDropdown');
    if (dropdownBtn) {
      dropdownBtn.addEventListener('click', (e) => { e.stopPropagation(); dropdown.classList.toggle('show'); });
      document.addEventListener('click', () => dropdown?.classList.remove('show'));
    }
    
    let zoom = 100;
    const zoomSpan = document.getElementById('zoomValue');
    const mainDiv = document.getElementById('mainContent');
    const zoomIn = document.getElementById('zoomIn');
    const zoomOut = document.getElementById('zoomOut');
    if (zoomIn) zoomIn.addEventListener('click', () => { if(zoom < 150) { zoom += 10; zoomSpan.innerText = zoom+'%'; mainDiv.style.zoom = zoom/100; } });
    if (zoomOut) zoomOut.addEventListener('click', () => { if(zoom > 70) { zoom -= 10; zoomSpan.innerText = zoom+'%'; mainDiv.style.zoom = zoom/100; } });
    
    document.querySelectorAll('[data-theme]').forEach(opt => {
      opt.addEventListener('click', () => {
        const theme = opt.dataset.theme;
        if(theme === 'dark') { 
          mainDiv.style.backgroundColor = '#1e293b'; 
          mainDiv.style.color = '#e2e8f0';
          document.querySelectorAll('.ai-tools-panel, .ai-chat-panel, .ai-history-panel, .tool-card, .chat-messages, .history-item, .stat-item, .keyword-tag').forEach(el => { if(el) { el.style.backgroundColor = '#2d3a4e'; el.style.color = '#e2e8f0'; el.style.borderColor = '#4a5568'; } });
          document.querySelectorAll('.tool-icon').forEach(el => { if(el) el.style.backgroundColor = '#1e293b'; });
        } else { 
          mainDiv.style.backgroundColor = '#fff'; 
          mainDiv.style.color = '#000';
          document.querySelectorAll('.ai-tools-panel, .ai-chat-panel, .ai-history-panel, .tool-card, .chat-messages, .history-item, .stat-item, .keyword-tag').forEach(el => { if(el) { el.style.backgroundColor = 'white'; el.style.color = '#000'; el.style.borderColor = '#eef2f6'; } });
          document.querySelectorAll('.tool-icon').forEach(el => { if(el) el.style.backgroundColor = '#fff'; });
        }
        dropdown.classList.remove('show');
      });
    });
  });
</script>
</body>
</html>