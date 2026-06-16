@extends('layouts.app')

@section('title', 'Médiathèque - DigiCMS')

@push('styles')
<link href="{{ asset('css/media.css') }}" rel="stylesheet">
@endpush

@section('content')
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
    <div><h1 class="library-title">Bibliothèque de médias</h1><span class="library-subtitle">Gérez vos images, vidéos, documents et pages web pour l'affichage dynamique</span></div>
    <button class="import-btn" id="importBtn"><i class="fas fa-upload"></i> Importer un fichier</button>
  </div>

  <!-- STATS CARDS -->
  <div class="summary-grid">
    <div class="summary-card"><div class="summary-icon pink"><i class="fas fa-images"></i></div><div class="summary-info"><span class="summary-value">{{ $totalMedia ?? 0 }}</span><span class="summary-label">Total</span></div></div>
    <div class="summary-card"><div class="summary-icon blue"><i class="fas fa-image"></i></div><div class="summary-info"><span class="summary-value">{{ $imagesCount ?? 0 }}</span><span class="summary-label">Images</span></div></div>
    <div class="summary-card"><div class="summary-icon purple"><i class="fas fa-video"></i></div><div class="summary-info"><span class="summary-value">{{ $videosCount ?? 0 }}</span><span class="summary-label">Vidéos</span></div></div>
    <div class="summary-card"><div class="summary-icon orange"><i class="fas fa-file-pdf"></i></div><div class="summary-info"><span class="summary-value">{{ $pdfCount ?? 0 }}</span><span class="summary-label">PDF</span></div></div>
    <div class="summary-card"><div class="summary-icon green"><i class="fas fa-file-alt"></i></div><div class="summary-info"><span class="summary-value">{{ $documentsCount ?? 0 }}</span><span class="summary-label">Documents</span></div></div>
    <div class="summary-card"><div class="summary-icon teal"><i class="fas fa-globe"></i></div><div class="summary-info"><span class="summary-value">{{ $webpagesCount ?? 0 }}</span><span class="summary-label">Pages Web</span></div></div>
    <div class="summary-card"><div class="summary-icon slate"><i class="fas fa-database"></i></div><div class="summary-info"><span class="summary-value">{{ $totalSize ?? '0' }} MB</span><span class="summary-label">Stockage</span></div></div>
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
    <div class="filter-select-wrapper"><select id="typeFilter"><option value="all">Tous types</option><option value="image">Images</option><option value="video">Vidéos</option><option value="pdf">PDF</option><option value="document">Documents</option><option value="webpage">Pages Web</option></select></div>
    <div class="filter-select-wrapper"><select id="sortFilter"><option value="recent">Date: Plus récent</option><option value="oldest">Date: Plus ancien</option><option value="name">Nom: A-Z</option></select></div>
  </div>

  <div class="gallery-title-row"><h2 class="gallery-title">Tous les médias</h2><span class="gallery-count" id="fileCountDisplay">0 fichiers</span></div>
  <div class="media-library-grid" id="dynamicMediaGrid"></div>
  <div class="pagination-bar" id="paginationControls"></div>
</main>

<!-- ============================================ -->
<!-- IMPORT MODAL (Sans select type) -->
<!-- ============================================ -->
<div id="importModal" class="modal">
  <div class="modal-content">
    <h3><i class="fas fa-upload"></i> Importer un média</h3>
    <form id="importForm" enctype="multipart/form-data">
      @csrf
      
      <!-- Titre -->
      <input type="text" name="title" id="fileTitle" placeholder="Titre du média" required>
      
      <!-- Boutons de bascule Fichier / Lien -->
      <div class="import-type-toggle">
        <button type="button" class="toggle-btn active" id="toggleFileBtn" data-type="file">
          <i class="fas fa-file"></i> Fichier
        </button>
        <button type="button" class="toggle-btn" id="toggleUrlBtn" data-type="url">
          <i class="fas fa-link"></i> Lien (URL)
        </button>
      </div>
      
      <!-- Zone de fichier -->
      <div id="fileInputWrapper">
        <input type="file" name="file" id="fileInput" accept="image/*,video/*,.pdf,.doc,.docx,.txt">
      </div>
      
      <!-- Zone de URL -->
      <div id="urlInputWrapper" style="display:none;">
        <input type="url" name="url" id="fileUrl" placeholder="https://exemple.com/page-web">
      </div>
      
      <!-- Catégorie -->
      <select name="category_id" id="fileCategory">
        <option value="">Sélectionner une catégorie</option>
        @foreach($categories ?? [] as $cat)
          <option value="{{ $cat->id }}">{{ $cat->name }}</option>
        @endforeach
      </select>
      
      <div class="modal-buttons">
        <button type="button" class="cancel" id="closeModalBtn">Annuler</button>
        <button type="submit" class="submit" id="importSubmitBtn">Importer</button>
      </div>
    </form>
  </div>
</div>

<!-- Preview Modal -->
<div id="previewModal" class="modal preview-modal">
  <div class="modal-content">
    <h3><i class="fas fa-eye"></i> Aperçu</h3>
    <div id="previewContent"></div>
    <div class="modal-buttons">
      <button class="cancel" onclick="closePreviewModal()">Fermer</button>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <h3><i class="fas fa-edit"></i> Modifier le média</h3>
    <form id="editForm">
      <input type="hidden" name="id" id="editId">
      <input type="text" name="title" id="editTitle" placeholder="Titre" required>
      <div id="editUrlWrapper" style="display:none;">
        <input type="url" name="url" id="editUrl" placeholder="URL de la page web">
      </div>
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
      const ext = (item.file_path || '').split('.').pop() || (item.type === 'webpage' ? 'web' : 'file');
      let badgeClass = 'green';
      if (item.type === 'image') badgeClass = 'red';
      else if (item.type === 'video') badgeClass = 'blue';
      else if (item.type === 'pdf') badgeClass = 'orange';
      else if (item.type === 'webpage') badgeClass = 'teal';
      
      const thumbStyle = (item.type === 'image' && item.file_path) 
        ? `background-image: url('${window.location.origin}/storage/${item.file_path}'); background-size: cover; background-position: center;` 
        : '';
      
      let thumbContent = '';
      if (item.type === 'webpage') {
        thumbContent = `<div style="display:flex;align-items:center;justify-content:center;height:100%;background:#eff6ff;color:#3b82f6;font-size:32px;"><i class="fas fa-globe"></i></div>`;
      } else if (!thumbStyle) {
        thumbContent = `<div style="display:flex;align-items:center;justify-content:center;height:100%;background:#f3f4f6;font-size:32px;color:#9ca3af;">
          <i class="fas ${item.type === 'video' ? 'fa-video' : (item.type === 'pdf' ? 'fa-file-pdf' : 'fa-file-alt')}"></i>
        </div>`;
      }
      
      const catName = item.category?.name || 'Non catégorisé';
      let catClass = 'green';
      if (catName === 'Promotion') catClass = 'red';
      else if (catName === 'Événement') catClass = 'blue';
      else if (catName === 'Branding') catClass = 'orange';
      
      const sizeText = item.type === 'webpage' ? 'Lien' : formatFileSize(item.size);
      
      return `<div class="media-lib-card" data-title="${escapeHtml(item.title).toLowerCase()}">
        <div class="card-thumb-wrapper" style="${thumbStyle}">
          ${thumbContent}
          <span class="thumb-type-badge ${badgeClass}">${ext.toUpperCase()}</span>
          <div class="card-actions-overlay">
            <button class="action-btn view" onclick="previewMedia(${item.id})"><i class="fas fa-eye"></i></button>
            <button class="action-btn edit" onclick="openEditModal(${item.id})"><i class="fas fa-edit"></i></button>
            <button class="action-btn delete" onclick="deleteMedia(${item.id})"><i class="fas fa-trash"></i></button>
          </div>
        </div>
        <div class="card-title">${escapeHtml(item.title)}${item.type !== 'webpage' ? '.' + ext : ''}</div>
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
  
  if (mediaSearchInput) mediaSearchInput.addEventListener('input', updateFilters);
  if (globalSearchInput) globalSearchInput.addEventListener('input', updateGlobalSearch);
  if (typeSelect) typeSelect.addEventListener('change', updateFilters);
  if (sortSelect) sortSelect.addEventListener('change', updateFilters);

  function previewMedia(id) {
    const mediaItem = allMedia.find(m => m.id === id);
    if (!mediaItem) { showToast('Média non trouvé', 'error'); return; }
    
    const previewDiv = document.getElementById('previewContent');
    
    if (mediaItem.type === 'image') {
      const url = window.location.origin + '/storage/' + mediaItem.file_path;
      previewDiv.innerHTML = `<img src="${url}" alt="Aperçu" style="max-width:100%; max-height:70vh; border-radius:8px;">`;
    } else if (mediaItem.type === 'video') {
      const url = window.location.origin + '/storage/' + mediaItem.file_path;
      previewDiv.innerHTML = `<video controls src="${url}" style="max-width:100%; max-height:70vh;"></video>`;
    } else if (mediaItem.type === 'pdf') {
      const url = window.location.origin + '/storage/' + mediaItem.file_path;
      previewDiv.innerHTML = `<div style="padding:40px;text-align:center;">
        <i class="fas fa-file-pdf" style="font-size:80px;color:#ef4444;"></i>
        <p style="margin-top:16px;">Fichier PDF</p>
        <a href="${url}" target="_blank" style="display:inline-block;margin-top:16px;padding:8px 20px;background:#c91818;color:white;border-radius:30px;text-decoration:none;">
          <i class="fas fa-external-link-alt"></i> Ouvrir le PDF
        </a>
      </div>`;
    } else if (mediaItem.type === 'webpage') {
      const url = mediaItem.url || '#';
      previewDiv.innerHTML = `<div style="padding:20px;text-align:center;background:#f8fafc;border-radius:12px;">
        <i class="fas fa-globe" style="font-size:80px;color:#3b82f6;"></i>
        <p style="margin:16px 0;font-weight:600;">${escapeHtml(mediaItem.title)}</p>
        <div style="margin-bottom:16px;padding:12px;background:#ffffff;border-radius:8px;border:1px solid #e2e8f0;word-break:break-all;">
          <strong>URL:</strong> <a href="${url}" target="_blank" style="color:#3b82f6;">${escapeHtml(url)}</a>
        </div>
        <a href="${url}" target="_blank" style="display:inline-block;padding:10px 24px;background:#c91818;color:white;border-radius:30px;text-decoration:none;">
          <i class="fas fa-external-link-alt"></i> Ouvrir la page
        </a>
        <div style="margin-top:16px;padding:12px;background:#f1f5f9;border-radius:8px;text-align:left;font-size:12px;color:#64748b;">
          <i class="fas fa-info-circle"></i> Cette page s'ouvrira dans un nouvel onglet
        </div>
      </div>`;
    } else {
      const url = window.location.origin + '/storage/' + mediaItem.file_path;
      previewDiv.innerHTML = `<div style="padding:40px;text-align:center;">
        <i class="fas fa-file-alt" style="font-size:80px;color:#64748b;"></i>
        <p style="margin-top:16px;">Document</p>
        <a href="${url}" download style="display:inline-block;margin-top:16px;padding:8px 20px;background:#c91818;color:white;border-radius:30px;text-decoration:none;">
          <i class="fas fa-download"></i> Télécharger
        </a>
      </div>`;
    }
    document.getElementById('previewModal').style.display = 'flex';
  }
  window.previewMedia = previewMedia;
  
  function closePreviewModal() { document.getElementById('previewModal').style.display = 'none'; }
  window.closePreviewModal = closePreviewModal;
  
  function openEditModal(id) {
    const mediaItem = allMedia.find(m => m.id === id);
    if (!mediaItem) { showToast('Média non trouvé', 'error'); return; }
    
    document.getElementById('editId').value = id;
    document.getElementById('editTitle').value = mediaItem.title;
    document.getElementById('editCategorySelect').value = mediaItem.category_id || '';
    
    const editUrlWrapper = document.getElementById('editUrlWrapper');
    const editUrlInput = document.getElementById('editUrl');
    if (mediaItem.type === 'webpage') {
      editUrlWrapper.style.display = 'block';
      editUrlInput.value = mediaItem.url || '';
      editUrlInput.required = true;
    } else {
      editUrlWrapper.style.display = 'none';
      editUrlInput.required = false;
    }
    
    document.getElementById('editModal').style.display = 'flex';
  }
  window.openEditModal = openEditModal;
  
  const closeEditModalBtn = document.getElementById('closeEditModalBtn');
  if (closeEditModalBtn) closeEditModalBtn.addEventListener('click', () => document.getElementById('editModal').style.display = 'none');
  
  const editForm = document.getElementById('editForm');
  if (editForm) {
    editForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const id = document.getElementById('editId').value;
      const title = document.getElementById('editTitle').value;
      const category_id = document.getElementById('editCategorySelect').value;
      const url = document.getElementById('editUrl').value;
      
      const payload = { title, category_id };
      if (document.getElementById('editUrlWrapper').style.display !== 'none') {
        payload.url = url;
      }
      
      const res = await fetch(`/media/${id}`, {
        method: 'PUT',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });
      if (res.ok) { showToast('Média modifié avec succès', 'success'); setTimeout(() => location.reload(), 500); }
      else { const error = await res.json(); showToast('Erreur: ' + (error.message || 'Modification échouée'), 'error'); }
    });
  }
  
  window.deleteMedia = async function(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce média ?')) {
      const res = await fetch(`/media/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
      if (res.ok) { showToast('Média supprimé avec succès', 'success'); setTimeout(() => location.reload(), 500); }
      else showToast('Erreur lors de la suppression', 'error');
    }
  };
  
  // ============================================
  // IMPORT MODAL - SANS SELECT TYPE
  // ============================================
  const importModal = document.getElementById('importModal');
  const importBtn = document.getElementById('importBtn');
  const browseBtn = document.getElementById('browseBtn');
  const uploadZone = document.getElementById('uploadZone');
  const closeModalBtn = document.getElementById('closeModalBtn');
  const importFormElem = document.getElementById('importForm');
  const importSubmitBtn = document.getElementById('importSubmitBtn');
  const fileInputWrapper = document.getElementById('fileInputWrapper');
  const urlInputWrapper = document.getElementById('urlInputWrapper');
  const fileInput = document.getElementById('fileInput');
  const fileUrlInput = document.getElementById('fileUrl');
  const toggleFileBtn = document.getElementById('toggleFileBtn');
  const toggleUrlBtn = document.getElementById('toggleUrlBtn');
  
  let currentImportType = 'file'; // 'file' ou 'url'
  
  function openModal() { 
    if (importModal) importModal.style.display = 'flex';
    if (importFormElem) importFormElem.reset();
    setImportType('file');
  }
  
  function closeModal() { 
    if (importModal) importModal.style.display = 'none';
    if (importFormElem) importFormElem.reset();
    setImportType('file');
  }
  
  function setImportType(type) {
    currentImportType = type;
    if (type === 'file') {
      fileInputWrapper.style.display = 'block';
      urlInputWrapper.style.display = 'none';
      fileInput.required = true;
      fileUrlInput.required = false;
      toggleFileBtn.classList.add('active');
      toggleUrlBtn.classList.remove('active');
    } else {
      fileInputWrapper.style.display = 'none';
      urlInputWrapper.style.display = 'block';
      fileInput.required = false;
      fileUrlInput.required = true;
      toggleUrlBtn.classList.add('active');
      toggleFileBtn.classList.remove('active');
    }
  }
  
  if (importBtn) importBtn.addEventListener('click', openModal);
  if (browseBtn) browseBtn.addEventListener('click', openModal);
  if (uploadZone) uploadZone.addEventListener('click', openModal);
  if (closeModalBtn) closeModalBtn.addEventListener('click', closeModal);
  
  if (toggleFileBtn) {
    toggleFileBtn.addEventListener('click', () => setImportType('file'));
  }
  if (toggleUrlBtn) {
    toggleUrlBtn.addEventListener('click', () => setImportType('url'));
  }
  
  window.addEventListener('click', (e) => {
    if (e.target === importModal) closeModal();
    if (e.target === document.getElementById('editModal')) document.getElementById('editModal').style.display = 'none';
    if (e.target === document.getElementById('previewModal')) closePreviewModal();
  });
  
  // ============================================
  // FORM SUBMISSION
  // ============================================
  if (importFormElem) {
    importFormElem.addEventListener('submit', async (e) => {
      e.preventDefault();
      
      const title = document.getElementById('fileTitle').value;
      
      if (!title) {
        showToast('Veuillez entrer un titre', 'error');
        return;
      }
      
      const formData = new FormData(importFormElem);
      
      if (currentImportType === 'url') {
        const url = document.getElementById('fileUrl').value;
        if (!url) {
          showToast('Veuillez entrer une URL valide', 'error');
          return;
        }
        try {
          new URL(url);
        } catch (_) {
          showToast('URL invalide. Format: https://exemple.com', 'error');
          return;
        }
        // Définir le type comme 'webpage'
        formData.set('type', 'webpage');
      } else {
        const file = document.getElementById('fileInput').files[0];
        if (!file) {
          showToast('Veuillez sélectionner un fichier', 'error');
          return;
        }
        // Détection automatique du type
        const detectedType = detectFileType(file.name);
        formData.set('type', detectedType);
      }
      
      if (importSubmitBtn) {
        importSubmitBtn.disabled = true;
        importSubmitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Importation...';
      }
      
      try {
        const res = await fetch('{{ route("media.store") }}', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
          },
          body: formData
        });
        
        const data = await res.json();
        
        if (res.ok && data.success) {
          showToast('Média importé avec succès', 'success');
          closeModal();
          setTimeout(() => location.reload(), 500);
        } else {
          let errorMsg = 'Importation échouée';
          if (data.message) {
            errorMsg = data.message;
          } else if (data.errors) {
            if (typeof data.errors === 'object') {
              errorMsg = Object.values(data.errors).flat().join(', ');
            } else {
              errorMsg = String(data.errors);
            }
          } else if (data.error) {
            errorMsg = data.error;
          }
          showToast('Erreur: ' + errorMsg, 'error');
        }
      } catch (error) {
        showToast('Erreur de connexion: ' + error.message, 'error');
      } finally {
        if (importSubmitBtn) {
          importSubmitBtn.disabled = false;
          importSubmitBtn.innerHTML = 'Importer';
        }
      }
    });
  }
  
  const dropdownBtn = document.getElementById('settingsDropdownBtn');
  const dropdown = document.getElementById('settingsDropdown');
  if (dropdownBtn) {
    dropdownBtn.addEventListener('click', (e) => { e.stopPropagation(); dropdown.classList.toggle('show'); });
    document.addEventListener('click', () => { if (dropdown) dropdown.classList.remove('show'); });
  }
  
  let zoom = 100;
  const zoomSpan = document.getElementById('zoomValue');
  const mainDiv = document.getElementById('mainContent');
  const zoomIn = document.getElementById('zoomIn');
  const zoomOut = document.getElementById('zoomOut');
  if (zoomIn) zoomIn.addEventListener('click', () => { if (zoom < 150) { zoom += 10; zoomSpan.innerText = zoom+'%'; mainDiv.style.zoom = zoom/100; } });
  if (zoomOut) zoomOut.addEventListener('click', () => { if (zoom > 70) { zoom -= 10; zoomSpan.innerText = zoom+'%'; mainDiv.style.zoom = zoom/100; } });
  
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
      if (dropdown) dropdown.classList.remove('show');
    });
  });
  
  renderGridAndPagination();
</script>

@endsection