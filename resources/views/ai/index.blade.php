@extends('layouts.app')

@section('title', 'Assistant IA - DigiCMS')

@push('styles')

<link href="{{ asset('css/ai.css') }}" rel="stylesheet">
<link href="{{ asset('css/assistant.css') }}" rel="stylesheet">


@endpush

@section('content')
<!-- MAIN CONTENT -->
<main class="main-content" id="mainContent">
  <div class="main-header">
    <div class="header-page-title">Assistant IA</div>
    <div class="header-actions">
      <div class="search-wrapper">
        <input type="text" id="globalSearch" class="search-input" placeholder="Rechercher dans l'historique...">
        <i class="fas fa-search search-icon"></i>
        <button class="search-clear" id="searchClear" title="Effacer la recherche"><i class="fas fa-times-circle"></i></button>
      </div>
      <div class="settings-container" style="position:relative;">
        <button class="settings-btn" id="settingsDropdownBtn"><i class="fas fa-sliders-h"></i></button>
        <div class="settings-dropdown" id="settingsDropdown" style="display:none;position:absolute;top:45px;right:0;background:white;border-radius:12px;box-shadow:0 10px 25px -5px rgba(0,0,0,0.1);width:220px;z-index:100;border:1px solid #e5e7eb;overflow:hidden;">
          <div class="dropdown-section" style="padding:12px 16px;border-bottom:1px solid #f0f0f0;">
            <div class="dropdown-title" style="font-size:11px;font-weight:600;color:#6b7280;margin-bottom:8px;text-transform:uppercase;letter-spacing:0.5px;display:flex;align-items:center;gap:8px;"><i class="fas fa-palette"></i> Thème</div>
            <div class="dropdown-option" data-theme="light" style="display:flex;align-items:center;gap:10px;padding:6px 0;cursor:pointer;font-size:12px;color:#374151;transition:all 0.2s;border-radius:6px;"><i class="fas fa-sun"></i> Clair</div>
            <div class="dropdown-option" data-theme="dark" style="display:flex;align-items:center;gap:10px;padding:6px 0;cursor:pointer;font-size:12px;color:#374151;transition:all 0.2s;border-radius:6px;"><i class="fas fa-moon"></i> Sombre</div>
          </div>
          <div class="dropdown-section" style="padding:12px 16px;border-bottom:1px solid #f0f0f0;">
            <div class="dropdown-title" style="font-size:11px;font-weight:600;color:#6b7280;margin-bottom:8px;text-transform:uppercase;letter-spacing:0.5px;display:flex;align-items:center;gap:8px;"><i class="fas fa-search-plus"></i> Zoom</div>
            <div class="zoom-control" style="display:flex;align-items:center;gap:12px;justify-content:space-between;">
              <button class="zoom-btn" id="zoomOut" style="background:#f3f4f6;border:none;width:28px;height:28px;border-radius:6px;cursor:pointer;font-weight:bold;transition:all 0.2s;">A-</button>
              <span class="zoom-value" id="zoomValue" style="font-size:12px;font-weight:500;">100%</span>
              <button class="zoom-btn" id="zoomIn" style="background:#f3f4f6;border:none;width:28px;height:28px;border-radius:6px;cursor:pointer;font-weight:bold;transition:all 0.2s;">A+</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="library-header">
    <div><h1 class="library-title">Assistant IA - DigiCMS</h1><span class="library-subtitle">Générez, optimisez et évaluez vos contenus marketing pour l'affichage dynamique</span></div>
  </div>

  <!-- TOOLS PANEL -->
  <div class="ai-tools-panel">
    <div class="tools-header">
      <h3><i class="fas fa-wand-magic-sparkles"></i> Fonctionnalités IA</h3>
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
    
    <!-- CENTER PANEL : Chat -->
    <div class="ai-chat-panel">
      <div class="chat-header">
        <div class="ai-status">
          <i class="fas fa-robot"></i>
          <span>DigiCMS IA</span>
          <span class="status-badge"><i class="fas fa-circle"></i> Prêt à générer</span>
        </div>
        <div class="chat-actions">
          <button id="clearChatBtn" class="clear-btn" title="Nouvelle conversation"><i class="fas fa-plus"></i> Nouvelle</button>
          <button id="copyAllBtn" class="clear-btn" title="Copier tout"><i class="fas fa-copy"></i> Copier tout</button>
          <button id="exportBtn" class="clear-btn" title="Exporter"><i class="fas fa-download"></i> Exporter</button>
        </div>
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

    <!-- RIGHT PANEL : Historique (Agrandi) -->
    <div class="ai-history-panel">
      <div class="history-header">
        <h3><i class="fas fa-clock-rotate-left"></i> Historique récent</h3>
        <button id="clearHistoryBtn"><i class="fas fa-trash-can"></i> Vider</button>
      </div>
      <div class="history-list" id="historyList">
        <div style="text-align:center;padding:20px;color:#9ca3af;font-size:12px;">
          <i class="fas fa-spinner fa-spin"></i> Chargement...
        </div>
      </div>
    </div>
  </div>
</main>
@endsection

@push('scripts')
<script>
  let currentSessionId = null;
  let chatHistory = [];
  let searchTerm = '';

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
    
    if (searchTerm) {
      applySearchHighlight(searchTerm);
    }
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

  // ============================================================
  // RECHERCHE GLOBALE
  // ============================================================
  function applySearchHighlight(term) {
    searchTerm = term.trim().toLowerCase();
    const messages = document.querySelectorAll('.message');
    const historyItems = document.querySelectorAll('.history-item');
    
    messages.forEach(msg => {
      const text = msg.querySelector('.text')?.innerText?.toLowerCase() || '';
      if (searchTerm && text.includes(searchTerm)) {
        msg.classList.add('message-highlight');
      } else {
        msg.classList.remove('message-highlight');
      }
    });
    
    historyItems.forEach(item => {
      const title = item.querySelector('.history-title')?.innerText?.toLowerCase() || '';
      if (searchTerm && title.includes(searchTerm)) {
        item.classList.add('highlight');
      } else {
        item.classList.remove('highlight');
      }
    });
    
    const clearBtn = document.getElementById('searchClear');
    if (clearBtn) {
      if (searchTerm) {
        clearBtn.classList.add('visible');
      } else {
        clearBtn.classList.remove('visible');
      }
    }
  }

  // ============================================================
  // PARTIE API - Appel à Laravel
  // ============================================================
  async function generateAIResponse(prompt, toolType = null) {
    try {
      const response = await fetch('/ai/chat', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
          prompt: prompt,
          type: toolType,
          session_id: currentSessionId
        })
      });
      
      const data = await response.json();
      
      if (data.success) {
        if (data.session_id) {
          currentSessionId = data.session_id;
        }
        return data.response;
      } else {
        return "⚠️ " + (data.error || "Erreur inconnue");
      }
    } catch (error) {
      console.error('Erreur:', error);
      return "⚠️ Erreur de connexion au serveur.";
    }
  }

  

  async function loadChatHistoryFromDB() {
    try {
      const response = await fetch('/ai/history');
      
      if (!response.ok) {
        throw new Error('Erreur HTTP: ' + response.status);
      }
      
      const data = await response.json();
      console.log('Historique chargé:', data);
      
      if (data && data.length > 0) {
        const lastSession = data[0];
        loadConversation(lastSession.session_id);
      }
      updateHistoryList(data);
      
    } catch (error) {
      console.error('Erreur chargement historique:', error);
      document.getElementById('historyList').innerHTML = `
        <div style="text-align:center;padding:20px;color:#ef4444;font-size:12px;">
          <i class="fas fa-exclamation-circle"></i> Erreur: ${error.message}
        </div>
      `;
    }
  }

  
  function loadConversation(sessionId) {
    fetch('/ai/history')
      .then(res => res.json())
      .then(data => {
        const session = data.find(s => s.session_id === sessionId);
        if (session && session.messages) {
          currentSessionId = sessionId;
          const chatContainer = document.getElementById('chatMessages');
          chatContainer.innerHTML = '';
          session.messages.forEach(msg => {
            addMessage(msg.content, msg.role === 'user');
          });
        }
      })
      .catch(err => console.error('Erreur chargement conversation:', err));
  }

  
  function updateHistoryList(data) {
    const historyList = document.getElementById('historyList');
    
    if (!data || data.length === 0) {
      historyList.innerHTML = `
        <div style="text-align:center;padding:20px;color:#9ca3af;font-size:12px;">
          <i class="fas fa-inbox"></i> Aucun historique
        </div>
      `;
      return;
    }
    
    historyList.innerHTML = data.map(session => `
      <div class="history-item" data-session="${session.session_id}">
        <div class="history-icon"><i class="fas fa-comment"></i></div>
        <div class="history-info">
          <div class="history-title" title="${session.title || 'Conversation'}">${session.title || 'Conversation'}</div>
          <div class="history-time"><i class="far fa-clock"></i> ${session.time || 'Récemment'}</div>
        </div>
      </div>
    `).join('');
    
    document.querySelectorAll('.history-item').forEach(item => {
      item.addEventListener('click', function() {
        const sessionId = this.getAttribute('data-session');
        if (sessionId) {
          loadConversation(sessionId);
        }
      });
    });
    
    if (searchTerm) {
      applySearchHighlight(searchTerm);
    }
  }

 
  async function clearHistoryFromDB() {
    if (!confirm('Voulez-vous effacer tout l\'historique de la conversation ?')) return;
    
    try {
      const response = await fetch('/ai/history', {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      });
      
      if (response.ok) {
        clearConversation();
        document.getElementById('historyList').innerHTML = `
          <div style="text-align:center;padding:20px;color:#9ca3af;font-size:12px;">
            <i class="fas fa-inbox"></i> Aucun historique
          </div>
        `;
        showToast('Historique effacé', 'success');
      }
    } catch (error) {
      showToast('Erreur lors de la suppression', 'error');
    }
  }

 
  async function sendRequest(prompt, toolType = null) {
    if (!prompt.trim()) {
        showToast('Veuillez écrire une demande', 'warning');
        return;
    }
    
    if (!currentSessionId) {
        currentSessionId = null;
    }
    
    addMessage(prompt, true);
    document.getElementById('userPrompt').value = '';
    addLoadingIndicator();
    const response = await generateAIResponse(prompt, toolType);
    removeLoadingIndicator();
    addMessage(response, false);
    
    setTimeout(async () => {
        try {
            const res = await fetch('/ai/history');
            const data = await res.json();
            updateHistoryList(data);
        } catch (e) {
            console.error('Erreur mise à jour historique:', e);
        }
    }, 1000);
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
    currentSessionId = null;
  }


  function copyAllChat() {
    const messages = document.querySelectorAll('.message .text');
    let text = '';
    messages.forEach(msg => {
      text += msg.innerText + '\n\n';
    });
    navigator.clipboard.writeText(text).then(() => {
      showToast('Historique copié !', 'success');
    }).catch(() => {
      const textarea = document.createElement('textarea');
      textarea.value = text;
      document.body.appendChild(textarea);
      textarea.select();
      document.execCommand('copy');
      textarea.remove();
      showToast('Historique copié !', 'success');
    });
  }

  
  function exportChat() {
    const messages = document.querySelectorAll('.message');
    let text = '--- Assistant IA DigiCMS ---\n\n';
    messages.forEach(msg => {
      const name = msg.querySelector('.name')?.innerText || '';
      const content = msg.querySelector('.text')?.innerText || '';
      text += `${name}:\n${content}\n\n`;
    });
    
    const blob = new Blob([text], { type: 'text/plain' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `chat_export_${new Date().toISOString().slice(0,10)}.txt`;
    a.click();
    URL.revokeObjectURL(url);
    showToast('Export terminé !', 'success');
  }

  
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

 
  function handleQueryFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    const query = urlParams.get('query');
    if (query) {
        const userPrompt = document.getElementById('userPrompt');
        if (userPrompt) {
            userPrompt.value = decodeURIComponent(query);
        }
        setTimeout(function() {
            const sendBtn = document.getElementById('sendPromptBtn');
            if (sendBtn) {
                sendBtn.click();
            }
        }, 800);
    }
  }

  // ============================================================
  // Initialisation
  // ============================================================
  document.addEventListener('DOMContentLoaded', function() {
    loadChatHistoryFromDB();
    
    handleQueryFromURL();
    
    // ============================================================
    // RECHERCHE
    // ============================================================
    const searchInput = document.getElementById('globalSearch');
    const searchClear = document.getElementById('searchClear');
    
    if (searchInput) {
      searchInput.addEventListener('input', function() {
        applySearchHighlight(this.value);
      });
    }
    
    if (searchClear) {
      searchClear.addEventListener('click', function() {
        const searchInput = document.getElementById('globalSearch');
        if (searchInput) {
          searchInput.value = '';
          applySearchHighlight('');
        }
      });
    }
    
    // ============================================================
    // TOOLS
    // ============================================================
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
    
    // ============================================================
    // ENVOI
    // ============================================================
    document.getElementById('sendPromptBtn')?.addEventListener('click', function() {
      sendRequest(document.getElementById('userPrompt').value);
    });
    
    document.getElementById('userPrompt')?.addEventListener('keydown', function(e) {
      if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        document.getElementById('sendPromptBtn').click();
      }
    });
    
    // ============================================================
    // CHAT ACTIONS
    // ============================================================
    document.getElementById('clearChatBtn')?.addEventListener('click', function() {
        clearConversation();
        currentSessionId = null;
        showToast('Conversation effacée', 'success');
    });
    
    document.getElementById('clearHistoryBtn')?.addEventListener('click', clearHistoryFromDB);
    document.getElementById('copyAllBtn')?.addEventListener('click', copyAllChat);
    document.getElementById('exportBtn')?.addEventListener('click', exportChat);
    
    // ============================================================
    // KEYWORDS
    // ============================================================
    document.querySelectorAll('.keyword-tag').forEach(tag => {
      tag.addEventListener('click', function() {
        document.getElementById('userPrompt').value = `Propose des mots-clés similaires à "${this.textContent}" pour ma campagne`;
        document.getElementById('sendPromptBtn').click();
      });
    });
    
    // ============================================================
    // SETTINGS DROPDOWN
    // ============================================================
 
    const dropdownBtn = document.getElementById('settingsDropdownBtn');
    const dropdown = document.getElementById('settingsDropdown');

    if (dropdownBtn && dropdown) {
        dropdownBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            if (dropdown.style.display === 'block') {
                dropdown.style.display = 'none';
            } else {
                dropdown.style.display = 'block';
            }
        });
        
        document.addEventListener('click', function(e) {
            if (!dropdown.contains(e.target) && e.target !== dropdownBtn) {
                dropdown.style.display = 'none';
            }
        });
    }
    
    // ============================================================
    // ZOOM
    // ============================================================
    let zoom = 100;
    const zoomSpan = document.getElementById('zoomValue');
    const mainDiv = document.getElementById('mainContent');
    const zoomIn = document.getElementById('zoomIn');
    const zoomOut = document.getElementById('zoomOut');
    
    function updateZoom(value) {
      zoom = Math.min(Math.max(value, 70), 150);
      zoomSpan.innerText = zoom + '%';
      mainDiv.style.zoom = zoom / 100;
    }
    
    if (zoomIn) {
      zoomIn.addEventListener('click', function() {
        updateZoom(zoom + 10);
      });
    }
    if (zoomOut) {
      zoomOut.addEventListener('click', function() {
        updateZoom(zoom - 10);
      });
    }
    
    // ============================================================
    // THEME
    // ============================================================
    document.querySelectorAll('[data-theme]').forEach(function(opt) {
      opt.addEventListener('click', function() {
        const theme = this.dataset.theme;
        if (theme === 'dark') {
          mainDiv.style.backgroundColor = '#1e293b';
          mainDiv.style.color = '#e2e8f0';
          document.querySelectorAll('.ai-tools-panel, .ai-chat-panel, .ai-history-panel, .tool-card, .chat-messages, .history-item, .keyword-tag').forEach(function(el) {
            if (el) {
              el.style.backgroundColor = '#2d3a4e';
              el.style.color = '#e2e8f0';
              el.style.borderColor = '#4a5568';
            }
          });
          document.querySelectorAll('.tool-icon').forEach(function(el) {
            if (el) el.style.backgroundColor = '#1e293b';
          });
        } else {
          mainDiv.style.backgroundColor = '#fff';
          mainDiv.style.color = '#000';
          document.querySelectorAll('.ai-tools-panel, .ai-chat-panel, .ai-history-panel, .tool-card, .chat-messages, .history-item, .keyword-tag').forEach(function(el) {
            if (el) {
              el.style.backgroundColor = 'white';
              el.style.color = '#000';
              el.style.borderColor = '#eef2f6';
            }
          });
          document.querySelectorAll('.tool-icon').forEach(function(el) {
            if (el) el.style.backgroundColor = '#fff';
          });
        }
        dropdown.classList.remove('show');
      });
    });
  });
</script>
@endpush
