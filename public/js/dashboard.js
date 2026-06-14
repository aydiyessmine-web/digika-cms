// Preview Media
function previewMedia(url, type) {
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

function closePreviewModal() {
  document.getElementById('previewModal').style.display = 'none';
}

// Hover pour le bouton zoom
document.querySelectorAll('.media-card').forEach(card => {
  card.addEventListener('mouseenter', () => {
    const btn = card.querySelector('.media-zoom-btn');
    if (btn) btn.style.opacity = '1';
  });
  card.addEventListener('mouseleave', () => {
    const btn = card.querySelector('.media-zoom-btn');
    if (btn) btn.style.opacity = '0';
  });
});

// Double-clic sur média -> redirection vers médiathèque
document.querySelectorAll('.media-card').forEach(card => {
  card.addEventListener('dblclick', function(e) {
    const mediaId = this.getAttribute('data-media-id');
    if (mediaId) {
      window.location.href = "/media?highlight=" + mediaId;
    } else {
      window.location.href = "/media";
    }
  });
});

// Double-clic sur campagne -> redirection vers campagnes
document.querySelectorAll('.campaign-item').forEach(item => {
  item.addEventListener('dblclick', function(e) {
    window.location.href = "/campaigns";
  });
});

// Recherche globale
const searchInput = document.getElementById('globalSearch');
if (searchInput) {
  const mediaCards = document.querySelectorAll('.media-card');
  const campaignItems = document.querySelectorAll('.campaign-item');
  function applySearchHighlight() {
    const searchTerm = searchInput.value.trim().toLowerCase();
    mediaCards.forEach(card => {
      const title = card.querySelector('.media-title')?.textContent || '';
      if (searchTerm === '') {
        card.style.opacity = '1';
        card.classList.remove('highlight');
      } else if (title.toLowerCase().includes(searchTerm)) {
        card.style.opacity = '1';
        card.classList.add('highlight');
      } else {
        card.style.opacity = '0.4';
        card.classList.remove('highlight');
      }
    });
    campaignItems.forEach(item => {
      const title = item.querySelector('.campaign-name')?.textContent || '';
      if (searchTerm === '') {
        item.style.opacity = '1';
        item.classList.remove('highlight');
      } else if (title.toLowerCase().includes(searchTerm)) {
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

// Fermer modal avec Echap
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') closePreviewModal();
});