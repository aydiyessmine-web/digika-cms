// ========== FONCTIONS COMMUNES ==========
function showToast(message, type = 'success') {
  const toast = document.createElement('div');
  toast.className = `toast-notification ${type}`;
  toast.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : (type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle')}"></i> ${message}`;
  document.body.appendChild(toast);
  setTimeout(() => toast.remove(), 3000);
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

function formatDate(date) {
  if (!date) return '---';
  const d = new Date(date);
  return d.toLocaleDateString('fr-FR');
}

// ========== SETTINGS & ZOOM & THEME ==========
document.addEventListener('DOMContentLoaded', function() {
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
        document.querySelectorAll('.summary-card, .campaign-card, .modal-content, .filter-search-wrapper input, .filter-select-wrapper select, .media-lib-card, .stat-card').forEach(el => { if(el) { el.style.backgroundColor = '#2d3a4e'; el.style.color = '#e2e8f0'; el.style.borderColor = '#4a5568'; } });
      } else { 
        mainDiv.style.backgroundColor = '#fff'; 
        mainDiv.style.color = '#000';
        document.querySelectorAll('.summary-card, .campaign-card, .modal-content, .filter-search-wrapper input, .filter-select-wrapper select, .media-lib-card, .stat-card').forEach(el => { if(el) { el.style.backgroundColor = 'white'; el.style.color = '#000'; el.style.borderColor = '#cbd5e1'; } });
      }
      dropdown.classList.remove('show');
    });
  });
});