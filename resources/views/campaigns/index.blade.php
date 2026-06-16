<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Campagnes - DigiCMS</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="{{ asset('css/campaigns.css') }}" rel="stylesheet">
  
  <!-- PDF.js library -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
  <script>pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';</script>
  
  <!-- Mammoth.js pour les fichiers DOCX -->
  <script src="https://cdn.jsdelivr.net/npm/mammoth@1.4.2/mammoth.browser.min.js"></script>
</head>
<body>
<div class="dashboard-container">
  
  <!-- SIDEBAR (corrigé) -->
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
        <a href="{{ route('campaigns.index') }}" class="nav-item active">
          <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19l7-2V7l-7-2v14z"></path><path d="M12 5L3 9v6l9 4z"></path><line x1="23" y1="12" x2="19" y2="12"></line></svg>
          <span>Campagnes</span>
        </a>
        <div class="nav-section-title">INTELLIGENCE IA</div>
        <a href="{{ route('ai.assistant') }}" class="nav-item {{ request()->routeIs('ai.assistant') ? 'active' : '' }}">
          <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="10" r="3"></circle><path d="M12 2a8 8 0 0 0-8 8c0 4 3 6 8 10 5-4 8-6 8-10a8 8 0 0 0-8-8z"></path></svg>
          <span>Assistant IA</span>
        </a>
      </nav>
    </div>
    <div class="sidebar-bottom">
      <div class="user-profile"><div class="user-avatar">{{ auth()->user() ? substr(auth()->user()->name, 0, 1) : 'U' }}</div><div class="user-info"><div class="user-name">{{ auth()->user() ? auth()->user()->name : 'Utilisateur' }}</div><div class="user-role">@if(auth()->user())@if(auth()->user()->role == 'admin') Administrateur @elseif(auth()->user()->role == 'marketing') Marketing Manager @elseif(auth()->user()->role == 'viewer') Visualisateur @else {{ ucfirst(auth()->user()->role) }} @endif @else Invité @endif</div></div></div>
      <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="logout-btn"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg><span>Logout</span></button></form>
    </div>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="main-content" id="mainContent">
    <div class="main-header">
      <div class="header-page-title">Campagnes</div>
      <div class="header-actions">
        <div class="search-wrapper"><input type="text" id="globalSearch" placeholder="Rechercher sur toute la page..."><i class="fas fa-search search-icon"></i></div>
        <div class="settings-container"><button class="settings-btn" id="settingsDropdownBtn"><i class="fas fa-sliders-h"></i></button><div class="settings-dropdown" id="settingsDropdown"><div class="dropdown-section"><div class="dropdown-title"><i class="fas fa-palette"></i> Thème</div><div class="dropdown-option" data-theme="light"><i class="fas fa-sun"></i> Clair</div><div class="dropdown-option" data-theme="dark"><i class="fas fa-moon"></i> Sombre</div></div><div class="dropdown-section"><div class="dropdown-title"><i class="fas fa-search-plus"></i> Zoom</div><div class="zoom-control"><button class="zoom-btn" id="zoomOut">A-</button><span class="zoom-value" id="zoomValue">100%</span><button class="zoom-btn" id="zoomIn">A+</button></div></div></div></div>
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

<!-- Modal Preview Campaign avec Zonage -->
<div id="previewModal" class="modal preview-modal">
  <div class="modal-content">
    <div class="preview-header"><h3 style="margin:0;display:flex;align-items:center;gap:10px;"><i class="fas fa-tv"></i> Aperçu de la campagne</h3><button onclick="closePreviewCampaignModal()" style="background:none;border:none;color:white;font-size:20px;cursor:pointer;"><i class="fas fa-times"></i></button></div>
    <div class="preview-controls">
      <div class="preview-nav-buttons">
        <button id="prevMediaBtn" class="nav-icon-btn" title="Média précédent"><i class="fas fa-chevron-left"></i></button>
        <span id="mediaCounter" style="font-size:12px;color:#374151;">Média 1 / 0</span>
        <button id="nextMediaBtn" class="nav-icon-btn" title="Média suivant"><i class="fas fa-chevron-right"></i></button>
      </div>
      
      <!-- Sélecteur de mode de zonage -->
      <div style="display:flex;align-items:center;gap:8px;background:#eef2f6;padding:4px 12px;border-radius:30px;">
        <i class="fas fa-layer-group" style="color:#6b7280;font-size:13px;"></i>
        <select id="previewLayoutMode" class="layout-select" style="border:none;background:transparent;font-size:12px;font-weight:500;outline:none;cursor:pointer;">
          <option value="full">📺 Plein écran</option>
          <option value="header-main-footer">🏢 Header + Main + Footer</option>
          <option value="split-horizontal">🔄 Split (Gauche/Droite)</option>
          <option value="dashboard">📊 Dashboard (3 colonnes)</option>
        </select>
      </div>
      
      <!-- Sélecteur de dimensions d'écran -->
      <div style="display:flex;align-items:center;gap:8px;background:#eef2f6;padding:4px 12px;border-radius:30px;">
        <i class="fas fa-mobile-alt" style="color:#6b7280;font-size:13px;"></i>
        <select id="screenSizeMode" class="layout-select" style="border:none;background:transparent;font-size:12px;font-weight:500;outline:none;cursor:pointer;">
          <option value="desktop">🖥️ Desktop (1920x1080)</option>
          <option value="tablet-landscape">📱 Tablette Paysage (1280x800)</option>
          <option value="tablet-portrait">📱 Tablette Portrait (800x1280)</option>
          <option value="mobile-landscape">📱 Mobile Paysage (896x414)</option>
          <option value="mobile-portrait">📱 Mobile Portrait (414x896)</option>
          <option value="tv">📺 TV (3840x2160)</option>
        </select>
      </div>
      
      <div style="display:flex;align-items:center;gap:8px;"><i class="fas fa-play-circle"></i><select id="previewMediaType" style="padding:6px 12px;border-radius:8px;border:1px solid #cbd5e1;font-size:12px;"><option value="all">Tous les médias</option><option value="image">Images uniquement</option><option value="video">Vidéos uniquement</option></select></div>
      
      <!-- Contrôles de zonage -->
      <div id="zoneControls" style="display:flex;gap:15px;align-items:center;margin-left:auto;flex-wrap:wrap;">
        
        <div id="headerMainFooterControls" style="display:none;gap:12px;align-items:center;background:#ffffff;padding:5px 12px;border-radius:30px;box-shadow:0 1px 2px rgba(0,0,0,0.05);">
          <div style="display:flex;align-items:center;gap:6px;"><i class="fas fa-arrow-up" style="color:#c91818;font-size:11px;"></i><span>Header:</span>
            <select id="headerMediaIndex" style="padding:4px 8px;border-radius:6px;border:1px solid #e5e7eb;font-size:11px;"><option value="-1">Aucun</option></select>
          </div>
          <div style="display:flex;align-items:center;gap:6px;"><i class="fas fa-square" style="color:#3b82f6;font-size:11px;"></i><span>Main:</span>
            <select id="mainMediaIndex" style="padding:4px 8px;border-radius:6px;border:1px solid #e5e7eb;font-size:11px;"><option value="-1">Aucun</option></select>
          </div>
          <div style="display:flex;align-items:center;gap:6px;"><i class="fas fa-arrow-down" style="color:#10b981;font-size:11px;"></i><span>Footer:</span>
            <select id="footerMediaIndex" style="padding:4px 8px;border-radius:6px;border:1px solid #e5e7eb;font-size:11px;"><option value="-1">Aucun</option></select>
          </div>
        </div>
        
        <div id="splitControls" style="display:none;gap:12px;align-items:center;background:#ffffff;padding:5px 12px;border-radius:30px;box-shadow:0 1px 2px rgba(0,0,0,0.05);">
          <div style="display:flex;align-items:center;gap:6px;"><i class="fas fa-arrow-left" style="color:#c91818;font-size:11px;"></i><span>Gauche:</span>
            <select id="splitLeftIndex" style="padding:4px 8px;border-radius:6px;border:1px solid #e5e7eb;font-size:11px;"><option value="-1">Aucun</option></select>
          </div>
          <div style="display:flex;align-items:center;gap:6px;"><i class="fas fa-arrow-right" style="color:#3b82f6;font-size:11px;"></i><span>Droite:</span>
            <select id="splitRightIndex" style="padding:4px 8px;border-radius:6px;border:1px solid #e5e7eb;font-size:11px;"><option value="-1">Aucun</option></select>
          </div>
        </div>
        
        <div id="dashboardControls" style="display:none;gap:12px;align-items:center;background:#ffffff;padding:5px 12px;border-radius:30px;box-shadow:0 1px 2px rgba(0,0,0,0.05);">
          <div style="display:flex;align-items:center;gap:6px;"><i class="fas fa-columns" style="color:#c91818;font-size:11px;"></i><span>Colonne Gauche:</span>
            <select id="dashboardLeftIndex" style="padding:4px 8px;border-radius:6px;border:1px solid #e5e7eb;font-size:11px;"><option value="-1">Aucun</option></select>
          </div>
          <div style="display:flex;align-items:center;gap:6px;"><i class="fas fa-tv" style="color:#3b82f6;font-size:11px;"></i><span>Centrale:</span>
            <select id="dashboardCenterIndex" style="padding:4px 8px;border-radius:6px;border:1px solid #e5e7eb;font-size:11px;"><option value="-1">Aucun</option></select>
          </div>
          <div style="display:flex;align-items:center;gap:6px;"><i class="fas fa-columns" style="color:#10b981;font-size:11px;transform:scaleX(-1);"></i><span>Colonne Droite:</span>
            <select id="dashboardRightIndex" style="padding:4px 8px;border-radius:6px;border:1px solid #e5e7eb;font-size:11px;"><option value="-1">Aucun</option></select>
          </div>
        </div>
      </div>
      
      <div class="preview-page-buttons">
        <button id="prevPageBtnPreview" class="nav-icon-btn" title="Page précédente" style="display:none;"><i class="fas fa-arrow-up"></i></button>
        <span id="pageCounter" style="font-size:12px;color:#374151;display:none;"></span>
        <button id="nextPageBtnPreview" class="nav-icon-btn" title="Page suivante" style="display:none;"><i class="fas fa-arrow-down"></i></button>
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
  // Données passées par le contrôleur
  const allCampaignsData = @json($campaignsData ?? []);
  const allMedia = @json($allMedia ?? []);
  
  // ========== VARIABLES GLOBALES ==========
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
  let currentLayoutMode = 'full';
  let currentScreenSize = 'desktop';
  
  const pdfDocuments = {};
  let scrollInterval = null;
  let isDocumentPlaying = false;
  let currentDocumentMediaId = null;
  let currentDocumentTotalPages = 0;
  let currentDocumentPage = 1;
  let currentFilteredMedia = [];
  let currentMediaId = null;

  let currentAssignment = {
    header: 0,
    main: 1,
    footer: 2,
    splitLeft: 0,
    splitRight: 1,
    dashboardLeft: 0,
    dashboardCenter: 1,
    dashboardRight: 2
  };

  // Dimensions des écrans
  const screenDimensions = {
    desktop: { width: 1200, height: 800 },
    'tablet-landscape': { width: 1280, height: 800 },
    'tablet-portrait': { width: 800, height: 1280 },
    'mobile-landscape': { width: 896, height: 414 },
    'mobile-portrait': { width: 414, height: 896 },
    tv: { width: 1920, height: 1080 }
  };

  function updateScreenDimensions() {
    const screenDiv = document.getElementById('previewScreen');
    const dims = screenDimensions[currentScreenSize];
    if (dims) {
      screenDiv.style.width = `${dims.width}px`;
      screenDiv.style.height = `${dims.height}px`;
    }
  }

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
    return `background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);`;
  }

  const statusLabels = { active: '● Active', planned: '◷ Planifiée', finished: '✓ Terminée' };
  const statusColors = { active: { bg: '#dcfce7', color: '#16a34a' }, planned: { bg: '#fff7ed', color: '#ea580c' }, finished: { bg: '#f3f4f6', color: '#6b7280' } };

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
      if (searchTerm && text.includes(searchTerm)) card.classList.add('highlight');
      else card.classList.remove('highlight');
    });
  }

  async function renderSingleMedia(media, zoneName = 'default') {
    if (!media || media === -1) return '<div class="empty-zone"><i class="fas fa-ban"></i><span>Aucun média</span></div>';
    
    if (media.type === 'image') {
      return `<div style="width:100%;height:100%;overflow:hidden;">
                <img src="/storage/${media.file_path}" style="width:100%;height:100%;object-fit:cover;object-position:center;">
              </div>`;
    } else if (media.type === 'video') {
      return `<div style="width:100%;height:100%;overflow:hidden;">
                <video autoplay loop muted playsinline style="width:100%;height:100%;object-fit:cover;object-position:center;">
                  <source src="/storage/${media.file_path}" type="video/mp4">
                </video>
              </div>`;
    } else {
      const extension = media.file_path ? media.file_path.split('.').pop().toLowerCase() : '';
      if (extension === 'pdf') {
        const pdfId = zoneName;
        setTimeout(() => { loadPdfAndRender(media.id, `/storage/${media.file_path}`, 1, pdfId); }, 100);
        return `<div style="width:100%;height:100%;background:#525659;display:flex;align-items:center;justify-content:center;overflow:auto;">
                  <canvas id="pdf-canvas-${media.id}-${pdfId}" style="max-width:100%;max-height:100%;"></canvas>
                </div>`;
      } else {
        const docContent = await showDocumentContent(media);
        return `<div style="width:100%;height:100%;overflow:auto;background:#f8fafc;padding:15px;">${docContent}</div>`;
      }
    }
  }

  async function loadPdfAndRender(mediaId, pdfUrl, pageNum, containerId) {
    try {
      if (!pdfDocuments[mediaId]) pdfDocuments[mediaId] = await pdfjsLib.getDocument(pdfUrl).promise;
      const pdf = pdfDocuments[mediaId];
      const page = await pdf.getPage(pageNum);
      const canvas = document.getElementById(`pdf-canvas-${mediaId}-${containerId}`);
      if (!canvas) return;
      const context = canvas.getContext('2d');
      const container = canvas.parentElement;
      const maxWidth = container ? container.clientWidth - 40 : Math.min(container.clientWidth, 600);
      let scale = maxWidth / page.getViewport({ scale: 1 }).width;
      scale = Math.min(scale, 1.2);
      const viewport = page.getViewport({ scale: scale });
      canvas.height = viewport.height;
      canvas.width = viewport.width;
      canvas.style.maxWidth = '100%';
      canvas.style.height = 'auto';
      await page.render({ canvasContext: context, viewport: viewport }).promise;
    } catch (error) { console.error('Error loading PDF:', error); }
  }

  async function showDocumentContent(media) {
    let extension = '';
    if (media.file_path) extension = media.file_path.split('.').pop().toLowerCase();
    const fileUrl = `/storage/${media.file_path}`;
    
    if (extension === 'pdf') return `<div>Chargement du PDF...</div>`;
    if (extension === 'docx') {
      try {
        const response = await fetch(fileUrl);
        const arrayBuffer = await response.arrayBuffer();
        const result = await mammoth.extractRawText({ arrayBuffer: arrayBuffer });
        const text = result.value;
        if (text && text.trim().length > 0) {
          const paragraphs = text.split(/\n\s*\n/);
          return `<div style="padding:20px;max-width:800px;margin:0 auto;">${paragraphs.map(p => `<p style="margin-bottom:15px;">${escapeHtml(p).replace(/\n/g, '<br>')}</p>`).join('')}</div>`;
        }
      } catch(e) {}
      return `<div style="padding:20px;text-align:center;"><i class="fas fa-file-word" style="font-size:48px;"></i><p>Document Word</p></div>`;
    }
    if (extension === 'txt') {
      try {
        const res = await fetch(fileUrl);
        const text = await res.text();
        return `<pre style="white-space:pre-wrap;padding:20px;">${escapeHtml(text)}</pre>`;
      } catch(e) {}
    }
    return `<div style="padding:20px;text-align:center;"><i class="fas fa-file" style="font-size:48px;"></i><p>Document</p></div>`;
  }

  async function renderCampaignPreview() {
    if (!currentPreviewCampaign) return;
    stopAutoScroll();
    
    const mediaType = document.getElementById('previewMediaType')?.value || 'all';
    const layoutMode = document.getElementById('previewLayoutMode')?.value || 'full';
    const screenDiv = document.getElementById('previewScreen');
    
    updateScreenDimensions();
    
    const campaignMedia = (currentPreviewCampaign.media_ids && Array.isArray(currentPreviewCampaign.media_ids)) 
      ? allMedia.filter(m => currentPreviewCampaign.media_ids.includes(m.id))
      : [];
    currentFilteredMedia = mediaType === 'all' ? campaignMedia : campaignMedia.filter(m => m.type === mediaType);
    
    await updateMediaCounter();
    updateZoneSelectors();
    
    let html = '';
    
    if (layoutMode === 'full') {
      const media = currentFilteredMedia[previewMediaIndex % currentFilteredMedia.length] || null;
      html = `<div style="position:relative;width:100%;height:100%;">`;
      html += await renderSingleMedia(media, 'full');
      html += `<div style="position:absolute;bottom:0;left:0;right:0;background:linear-gradient(to top,rgba(0,0,0,0.9) 0%,rgba(0,0,0,0.4) 60%,transparent 100%);padding:25px;color:white;">
                <h2 style="font-size:22px;font-weight:700;margin-bottom:8px;">${escapeHtml(currentPreviewCampaign.title)}</h2>
                ${currentPreviewCampaign.description ? `<p style="font-size:13px;opacity:0.85;">${escapeHtml(currentPreviewCampaign.description)}</p>` : ''}
              </div>`;
      html += `</div>`;
      updatePageButtons(false, 0, 0);
      if (isRotating && currentFilteredMedia.length > 1) {
        if (previewInterval) clearInterval(previewInterval);
        previewInterval = setInterval(async () => {
          previewMediaIndex = (previewMediaIndex + 1) % currentFilteredMedia.length;
          await renderCampaignPreview();
        }, 6000);
      } else if (previewInterval) clearInterval(previewInterval);
    }
    else if (layoutMode === 'header-main-footer') {
      const headerMedia = currentFilteredMedia[currentAssignment.header] || null;
      const mainMedia = currentFilteredMedia[currentAssignment.main] || null;
      const footerMedia = currentFilteredMedia[currentAssignment.footer] || null;
      
      html = `<div style="display:flex;flex-direction:column;width:100%;height:100%;gap:2px;background:#0a0a0f;">`;
      html += `<div style="height:18%;background:#0f172a;border-radius:12px 12px 0 0;overflow:hidden;position:relative;border-bottom:2px solid #c91818;">`;
      html += await renderSingleMedia(headerMedia, 'header');
      if (headerMedia && headerMedia !== -1) html += `<div class="zone-badge header" style="position:absolute;bottom:8px;left:12px;"><i class="fas fa-arrow-up"></i> Header</div>`;
      html += `</div>`;
      html += `<div style="height:64%;overflow:hidden;position:relative;">`;
      html += await renderSingleMedia(mainMedia, 'main');
      if (mainMedia && mainMedia !== -1) html += `<div class="zone-badge main" style="position:absolute;bottom:8px;left:12px;"><i class="fas fa-square"></i> Main</div>`;
      html += `</div>`;
      html += `<div style="height:18%;background:#0f172a;border-radius:0 0 12px 12px;overflow:hidden;position:relative;border-top:2px solid #c91818;">`;
      html += await renderSingleMedia(footerMedia, 'footer');
      if (footerMedia && footerMedia !== -1) html += `<div class="zone-badge footer" style="position:absolute;bottom:8px;left:12px;"><i class="fas fa-arrow-down"></i> Footer</div>`;
      html += `</div>`;
      html += `</div>`;
      updatePageButtons(false, 0, 0);
      if (previewInterval) clearInterval(previewInterval);
    }
    else if (layoutMode === 'split-horizontal') {
      const leftMedia = currentFilteredMedia[currentAssignment.splitLeft] || null;
      const rightMedia = currentFilteredMedia[currentAssignment.splitRight] || null;
      
      html = `<div style="display:flex;width:100%;height:100%;gap:2px;background:#0a0a0f;">`;
      html += `<div style="flex:1;overflow:hidden;position:relative;border-radius:12px;">`;
      html += await renderSingleMedia(leftMedia, 'split-left');
      if (leftMedia && leftMedia !== -1) html += `<div class="zone-badge left" style="position:absolute;bottom:8px;left:12px;"><i class="fas fa-arrow-left"></i> Gauche</div>`;
      html += `</div>`;
      html += `<div style="flex:1;overflow:hidden;position:relative;border-radius:12px;">`;
      html += await renderSingleMedia(rightMedia, 'split-right');
      if (rightMedia && rightMedia !== -1) html += `<div class="zone-badge right" style="position:absolute;bottom:8px;left:12px;"><i class="fas fa-arrow-right"></i> Droite</div>`;
      html += `</div>`;
      html += `</div>`;
      updatePageButtons(false, 0, 0);
      if (previewInterval) clearInterval(previewInterval);
    }
    else if (layoutMode === 'dashboard') {
      const leftMedia = currentFilteredMedia[currentAssignment.dashboardLeft] || null;
      const centerMedia = currentFilteredMedia[currentAssignment.dashboardCenter] || null;
      const rightMedia = currentFilteredMedia[currentAssignment.dashboardRight] || null;
      
      html = `<div style="display:flex;width:100%;height:100%;gap:2px;background:#0a0a0f;">`;
      html += `<div style="width:25%;overflow:hidden;position:relative;border-radius:12px;background:#0f172a;">`;
      html += await renderSingleMedia(leftMedia, 'dashboard-left');
      if (leftMedia && leftMedia !== -1) html += `<div class="zone-badge sidebar" style="position:absolute;bottom:8px;left:12px;"><i class="fas fa-columns"></i> Sidebar</div>`;
      html += `</div>`;
      html += `<div style="width:50%;overflow:hidden;position:relative;border-radius:12px;">`;
      html += await renderSingleMedia(centerMedia, 'dashboard-center');
      if (centerMedia && centerMedia !== -1) html += `<div class="zone-badge center" style="position:absolute;bottom:8px;left:12px;"><i class="fas fa-tv"></i> Main</div>`;
      html += `</div>`;
      html += `<div style="width:25%;overflow:hidden;position:relative;border-radius:12px;background:#0f172a;">`;
      html += await renderSingleMedia(rightMedia, 'dashboard-right');
      if (rightMedia && rightMedia !== -1) html += `<div class="zone-badge sidebar" style="position:absolute;bottom:8px;left:12px;"><i class="fas fa-columns"></i> Sidebar</div>`;
      html += `</div>`;
      html += `</div>`;
      updatePageButtons(false, 0, 0);
      if (previewInterval) clearInterval(previewInterval);
    }
    
    screenDiv.innerHTML = html;
    updateControlsVisibility(layoutMode);
  }

  function updateZoneSelectors() {
    const mediaCount = currentFilteredMedia.length;
    
    const updateSelect = (select, currentVal) => {
      if (!select) return;
      select.innerHTML = '<option value="-1">Aucun</option>';
      for (let i = 0; i < mediaCount; i++) {
        const option = document.createElement('option');
        option.value = i;
        option.textContent = `${currentFilteredMedia[i]?.title || 'Média ' + (i+1)} (${currentFilteredMedia[i]?.type || '?'})`;
        if (i === currentVal) option.selected = true;
        select.appendChild(option);
      }
    };
    
    updateSelect(document.getElementById('headerMediaIndex'), currentAssignment.header);
    updateSelect(document.getElementById('mainMediaIndex'), currentAssignment.main);
    updateSelect(document.getElementById('footerMediaIndex'), currentAssignment.footer);
    updateSelect(document.getElementById('splitLeftIndex'), currentAssignment.splitLeft);
    updateSelect(document.getElementById('splitRightIndex'), currentAssignment.splitRight);
    updateSelect(document.getElementById('dashboardLeftIndex'), currentAssignment.dashboardLeft);
    updateSelect(document.getElementById('dashboardCenterIndex'), currentAssignment.dashboardCenter);
    updateSelect(document.getElementById('dashboardRightIndex'), currentAssignment.dashboardRight);
  }

  function updateControlsVisibility(layoutMode) {
    const headerControls = document.getElementById('headerMainFooterControls');
    const splitControls = document.getElementById('splitControls');
    const dashboardControls = document.getElementById('dashboardControls');
    
    if (layoutMode === 'header-main-footer') {
      if (headerControls) headerControls.style.display = 'flex';
      if (splitControls) splitControls.style.display = 'none';
      if (dashboardControls) dashboardControls.style.display = 'none';
    } else if (layoutMode === 'split-horizontal') {
      if (headerControls) headerControls.style.display = 'none';
      if (splitControls) splitControls.style.display = 'flex';
      if (dashboardControls) dashboardControls.style.display = 'none';
    } else if (layoutMode === 'dashboard') {
      if (headerControls) headerControls.style.display = 'none';
      if (splitControls) splitControls.style.display = 'none';
      if (dashboardControls) dashboardControls.style.display = 'flex';
    } else {
      if (headerControls) headerControls.style.display = 'none';
      if (splitControls) splitControls.style.display = 'none';
      if (dashboardControls) dashboardControls.style.display = 'none';
    }
  }

  function updatePageButtons(isPdf, currentPage, totalPages) {
    const prevPageBtn = document.getElementById('prevPageBtnPreview');
    const nextPageBtn = document.getElementById('nextPageBtnPreview');
    const pageCounter = document.getElementById('pageCounter');
    if (isPdf && totalPages > 1) {
      if (prevPageBtn) prevPageBtn.style.display = 'inline-flex';
      if (nextPageBtn) nextPageBtn.style.display = 'inline-flex';
      if (pageCounter) { pageCounter.style.display = 'inline'; pageCounter.innerText = `Page ${currentPage} / ${totalPages}`; }
    } else {
      if (prevPageBtn) prevPageBtn.style.display = 'none';
      if (nextPageBtn) nextPageBtn.style.display = 'none';
      if (pageCounter) pageCounter.style.display = 'none';
    }
  }

  function goToPrevPage() { stopAutoScroll(); }
  function goToNextPage() { stopAutoScroll(); }
  function stopAutoScroll() { if (scrollInterval) { clearInterval(scrollInterval); scrollInterval = null; } isDocumentPlaying = false; }
  async function updateMediaCounter() { const counterSpan = document.getElementById('mediaCounter'); if (counterSpan && currentFilteredMedia.length > 0) counterSpan.innerText = `Média ${previewMediaIndex + 1} / ${currentFilteredMedia.length}`; }
  function nextMedia() { if (currentFilteredMedia.length > 1) { stopAutoScroll(); previewMediaIndex = (previewMediaIndex + 1) % currentFilteredMedia.length; renderCampaignPreview(); } }
  function prevMedia() { if (currentFilteredMedia.length > 1) { stopAutoScroll(); previewMediaIndex = (previewMediaIndex - 1 + currentFilteredMedia.length) % currentFilteredMedia.length; renderCampaignPreview(); } }
  function updateRotationButtons() { const playBtn = document.getElementById('playRotationBtn'), stopBtn = document.getElementById('stopRotationBtn'); if (isRotating) { playBtn?.classList.add('control-btn-active'); playBtn?.classList.remove('control-btn-primary'); stopBtn?.classList.remove('control-btn-active'); stopBtn?.classList.add('control-btn-secondary'); } else { playBtn?.classList.remove('control-btn-active'); playBtn?.classList.add('control-btn-primary'); stopBtn?.classList.add('control-btn-active'); stopBtn?.classList.remove('control-btn-secondary'); } }

  async function openPreviewCampaign(campaignId) {
    const campaign = allCampaigns.find(c => c.id === campaignId);
    if (!campaign) return;
    if (previewInterval) clearInterval(previewInterval);
    stopAutoScroll();
    currentPreviewCampaign = campaign;
    previewMediaIndex = 0;
    isRotating = true;
    currentLayoutMode = 'full';
    currentScreenSize = 'desktop';
    currentAssignment = {
      header: 0, main: Math.min(1, (allMedia.length || 1) - 1), footer: Math.min(2, (allMedia.length || 1) - 1),
      splitLeft: 0, splitRight: Math.min(1, (allMedia.length || 1) - 1),
      dashboardLeft: 0, dashboardCenter: Math.min(1, (allMedia.length || 1) - 1), dashboardRight: Math.min(2, (allMedia.length || 1) - 1)
    };
    updateRotationButtons();
    document.getElementById('previewModal').style.display = 'flex';
    await renderCampaignPreview();
  }

  function closePreviewCampaignModal() { if (previewInterval) clearInterval(previewInterval); previewInterval = null; stopAutoScroll(); document.getElementById('previewModal').style.display = 'none'; currentPreviewCampaign = null; currentDocumentMediaId = null; }

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
    
    if (total === 0) { grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:60px;background:white;border-radius:12px;"><i class="fas fa-folder-open"></i> Aucune campagne trouvée</div>'; document.getElementById('paginationControls').innerHTML = ''; return; }
    
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

  function renderMediaSelection() {
    const mediaList = document.getElementById('mediaList');
    if (!mediaList) return;
    if (allMedia.length === 0) { mediaList.innerHTML = '<div style="text-align:center;padding:20px;"><i class="fas fa-folder-open"></i> Aucun média disponible.</div>'; return; }
    mediaList.innerHTML = allMedia.map(m => `<div class="media-item ${selectedMediaIds.includes(m.id) ? 'selected' : ''}" onclick="toggleMediaSelection(${m.id})">${m.type === 'image' ? `<img src="/storage/${m.file_path}" onerror="this.src='https://placehold.co/40x40/f3f4f6/9ca3af?text=Image'">` : `<div style="width:40px;height:40px;background:#f3f4f6;display:flex;align-items:center;justify-content:center;border-radius:6px;"><i class="fas fa-file" style="color:#9ca3af;"></i></div>`}<div class="media-info"><div class="media-title">${escapeHtml(m.title)}</div><div class="media-type">${m.type}</div></div><i class="fas ${selectedMediaIds.includes(m.id) ? 'fa-check-circle' : 'fa-plus-circle'}" style="color:${selectedMediaIds.includes(m.id) ? '#c91818' : '#9ca3af'}; font-size: 18px;"></i></div>`).join('');
    document.getElementById('selectedCount').innerHTML = `<i class="fas fa-images"></i> ${selectedMediaIds.length} média(s) sélectionné(s)`;
  }

  window.toggleMediaSelection = function(mediaId) {
    if (selectedMediaIds.includes(mediaId)) selectedMediaIds = selectedMediaIds.filter(id => id !== mediaId);
    else selectedMediaIds.push(mediaId);
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
    if (media.length === 0) container.innerHTML = '<div style="text-align:center;padding:40px;"><i class="fas fa-folder-open"></i> Aucun média associé à cette campagne</div>';
    else container.innerHTML = media.map(m => `<div style="display:flex;align-items:center;gap:12px;padding:10px;border-bottom:1px solid #eee;">${m.type === 'image' ? `<img src="/storage/${m.file_path}" style="width:50px;height:50px;object-fit:cover;border-radius:8px;">` : `<div style="width:50px;height:50px;background:#f3f4f6;display:flex;align-items:center;justify-content:center;border-radius:8px;"><i class="fas fa-file fa-2x" style="color:#9ca3af;"></i></div>`}<div style="flex:1;"><div style="font-weight:600;">${escapeHtml(m.title)}</div><div style="font-size:11px;color:#9ca3af;">${m.type}</div></div><button class="edit-btn" style="background:#c91818;color:white;width:auto;padding:5px 12px;" onclick="removeMediaFromCampaign(${campaignId}, ${m.id})"><i class="fas fa-trash"></i> Retirer</button></div>`).join('');
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
      if (c.background_image && c.background_image !== 'null' && c.background_image !== '') document.getElementById('imagePreview').innerHTML = `<img src="${c.background_image}" alt="Aperçu">`;
      else document.getElementById('imagePreview').innerHTML = '';
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

  // INITIALISATION
  const globalSearchInput = document.getElementById('globalSearch');
  const campaignSearchInput = document.getElementById('campaignSearchInput');
  const statusSelect = document.getElementById('statusFilter');
  const sortSelect = document.getElementById('sortFilter');

  function updateFilters() { currentCampaignSearch = campaignSearchInput.value; currentStatus = statusSelect.value; currentSort = sortSelect.value; currentPage = 1; renderCampaigns(); }
  function updateGlobalSearch() { globalSearchTerm = globalSearchInput.value; updateGlobalSearchHighlight(); }

  campaignSearchInput.addEventListener('input', updateFilters);
  globalSearchInput.addEventListener('input', updateGlobalSearch);
  statusSelect.addEventListener('change', updateFilters);
  sortSelect.addEventListener('change', updateFilters);

  const fileInput = document.getElementById('campaignImageFile');
  const imagePreview = document.getElementById('imagePreview');
  document.querySelector('.file-input-label')?.addEventListener('click', () => fileInput.click());
  fileInput?.addEventListener('change', (e) => { const file = e.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = function(ev) { imagePreview.innerHTML = `<img src="${ev.target.result}" alt="Aperçu"><div style="font-size:10px;color:#666;margin-top:5px;">${file.name}</div>`; }; reader.readAsDataURL(file); } });

  const modal = document.getElementById('campaignModal');
  document.getElementById('newCampaignBtn').addEventListener('click', () => { document.getElementById('modalTitle').innerHTML = '<i class="fas fa-bullhorn"></i> Nouvelle campagne'; document.getElementById('campaignForm').reset(); document.getElementById('campaignId').value = ''; imagePreview.innerHTML = ''; fileInput.value = ''; selectedMediaIds = []; renderMediaSelection(); modal.style.display = 'flex'; });
  document.getElementById('closeModalBtn').addEventListener('click', () => modal.style.display = 'none');
  window.addEventListener('click', (e) => { if(e.target === modal) modal.style.display = 'none'; if(e.target === document.getElementById('viewMediaModal')) closeViewMediaModal(); if(e.target === document.getElementById('previewModal')) closePreviewCampaignModal(); });
  
  const statusSelectModal = document.getElementById('campaignStatus');
  const progressInputModal = document.getElementById('campaignProgress');
  statusSelectModal?.addEventListener('change', () => { if (statusSelectModal.value === 'finished') { progressInputModal.value = 100; progressInputModal.disabled = true; } else { progressInputModal.disabled = false; } });
  
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

  document.getElementById('playRotationBtn')?.addEventListener('click', () => { isRotating = true; updateRotationButtons(); if (currentPreviewCampaign) renderCampaignPreview(); });
  document.getElementById('stopRotationBtn')?.addEventListener('click', () => { isRotating = false; stopAutoScroll(); if (previewInterval) clearInterval(previewInterval); previewInterval = null; updateRotationButtons(); });
  document.getElementById('prevMediaBtn')?.addEventListener('click', () => prevMedia());
  document.getElementById('nextMediaBtn')?.addEventListener('click', () => nextMedia());
  document.getElementById('prevPageBtnPreview')?.addEventListener('click', () => goToPrevPage());
  document.getElementById('nextPageBtnPreview')?.addEventListener('click', () => goToNextPage());
  
  document.getElementById('previewLayoutMode')?.addEventListener('change', (e) => { currentLayoutMode = e.target.value; renderCampaignPreview(); });
  document.getElementById('screenSizeMode')?.addEventListener('change', (e) => { currentScreenSize = e.target.value; updateScreenDimensions(); renderCampaignPreview(); });
  
  document.getElementById('headerMediaIndex')?.addEventListener('change', (e) => { currentAssignment.header = parseInt(e.target.value); renderCampaignPreview(); });
  document.getElementById('mainMediaIndex')?.addEventListener('change', (e) => { currentAssignment.main = parseInt(e.target.value); renderCampaignPreview(); });
  document.getElementById('footerMediaIndex')?.addEventListener('change', (e) => { currentAssignment.footer = parseInt(e.target.value); renderCampaignPreview(); });
  document.getElementById('splitLeftIndex')?.addEventListener('change', (e) => { currentAssignment.splitLeft = parseInt(e.target.value); renderCampaignPreview(); });
  document.getElementById('splitRightIndex')?.addEventListener('change', (e) => { currentAssignment.splitRight = parseInt(e.target.value); renderCampaignPreview(); });
  document.getElementById('dashboardLeftIndex')?.addEventListener('change', (e) => { currentAssignment.dashboardLeft = parseInt(e.target.value); renderCampaignPreview(); });
  document.getElementById('dashboardCenterIndex')?.addEventListener('change', (e) => { currentAssignment.dashboardCenter = parseInt(e.target.value); renderCampaignPreview(); });
  document.getElementById('dashboardRightIndex')?.addEventListener('change', (e) => { currentAssignment.dashboardRight = parseInt(e.target.value); renderCampaignPreview(); });

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