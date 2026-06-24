function showToast(message, type = 'success') {
  const toast = document.createElement('div');
  toast.className = `toast-notification ${type}`;
  toast.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-info-circle'}"></i> ${message}`;
  document.body.appendChild(toast);
  setTimeout(() => toast.remove(), 3000);
}

function previewMedia(url, type) {
  const previewDiv = document.getElementById('previewContent');
  if (type === 'image') {
    previewDiv.innerHTML = `<img src="${url}" alt="Aperçu" style="max-width:100%; max-height:70vh; border-radius:8px;">`;
  } else if (type === 'video') {
    previewDiv.innerHTML = `<video controls src="${url}" style="max-width:100%; max-height:70vh;"></video>`;
  } else if (type === 'pdf') {
    previewDiv.innerHTML = `<div style="padding:40px;text-align:center;"><i class="fas fa-file-pdf" style="font-size:80px;color:#ef4444;"></i><p style="margin-top:16px;">Fichier PDF</p><a href="${url}" target="_blank" style="display:inline-block;margin-top:16px;padding:8px 20px;background:#c91818;color:white;border-radius:30px;text-decoration:none;"><i class="fas fa-external-link-alt"></i> Ouvrir le PDF</a></div>`;
  } else if (type === 'webpage') {
    previewDiv.innerHTML = `<div style="padding:20px;text-align:center;background:#f8fafc;border-radius:12px;">
      <i class="fas fa-globe" style="font-size:80px;color:#3b82f6;"></i>
      <p style="margin:16px 0;font-weight:600;">Page Web</p>
      <div style="margin-bottom:16px;padding:12px;background:#ffffff;border-radius:8px;border:1px solid #e2e8f0;word-break:break-all;">
        <strong>URL:</strong> <a href="${url}" target="_blank" style="color:#3b82f6;">${url}</a>
      </div>
      <a href="${url}" target="_blank" style="display:inline-block;padding:10px 24px;background:#c91818;color:white;border-radius:30px;text-decoration:none;">
        <i class="fas fa-external-link-alt"></i> Ouvrir la page
      </a>
    </div>`;
  } else {
    previewDiv.innerHTML = `<div style="padding:40px;text-align:center;"><i class="fas fa-file-alt" style="font-size:80px;color:#64748b;"></i><p style="margin-top:16px;">Document</p><a href="${url}" download style="display:inline-block;margin-top:16px;padding:8px 20px;background:#c91818;color:white;border-radius:30px;text-decoration:none;"><i class="fas fa-download"></i> Télécharger</a></div>`;
  }
  document.getElementById('previewModal').style.display = 'flex';
}

function closePreviewModal() {
  document.getElementById('previewModal').style.display = 'none';
}

// Double-clic sur média
document.querySelectorAll('.media-card').forEach(card => {
  card.addEventListener('dblclick', function(e) {
    const mediaId = this.getAttribute('data-media-id');
    window.location.href = mediaId ? "/media?highlight=" + mediaId : "/media";
  });
});

// Double-clic sur campagne
document.querySelectorAll('.campaign-item').forEach(item => {
  item.addEventListener('dblclick', function(e) {
    window.location.href = "/campaigns";
  });
});

// Recherche globale
const searchInput = document.getElementById('globalSearch');
if (searchInput) {
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
}

// Settings dropdown
const dropdownBtn = document.getElementById('settingsDropdownBtn');
const dropdown = document.getElementById('settingsDropdown');
if (dropdownBtn) {
  dropdownBtn.addEventListener('click', (e) => { e.stopPropagation(); dropdown.classList.toggle('show'); });
  document.addEventListener('click', () => dropdown?.classList.remove('show'));
}

// Zoom
let zoom = 100;
const zoomSpan = document.getElementById('zoomValue');
const mainDiv = document.getElementById('mainContent');
const zoomIn = document.getElementById('zoomIn');
const zoomOut = document.getElementById('zoomOut');
if (zoomIn) zoomIn.addEventListener('click', () => { if(zoom < 150) { zoom += 10; zoomSpan.innerText = zoom+'%'; mainDiv.style.zoom = zoom/100; } });
if (zoomOut) zoomOut.addEventListener('click', () => { if(zoom > 70) { zoom -= 10; zoomSpan.innerText = zoom+'%'; mainDiv.style.zoom = zoom/100; } });

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
    const input = document.getElementById('aiPrompt');
    if (input) {
      // استخراج النص من الزر (بدون الأيقونات)
      const text = pill.textContent.trim();
      input.value = text;
    }
  });
});

// Fermer modal avec Echap
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') closePreviewModal();
});

// ============================================================
//Assistant IA de Dashboard
// ============================================================


function setAIPrompt(text) {
    const input = document.getElementById('aiPrompt');
    if (input) {
        input.value = text;
    }
}


function sendToAI() {
    const input = document.getElementById('aiPrompt');
    const query = input.value.trim();
    
    if (query === '') {
        showToast('Veuillez entrer une requête', 'error');
        return;
    }
    
    showToast('Demande envoyée à l\'IA', 'success');
    
    
    setTimeout(function() {
        window.location.href = '/ai-assistant?query=' + encodeURIComponent(query);
    }, 500);
}