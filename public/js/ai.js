// Données simulées pour les réponses IA
const aiResponses = {
  slogan: "Voici 3 slogans percutants pour votre campagne :\n\n1. ✨ **L'été se porte, les prix s'envolent pas** — Profitez de nos soldes exclusifs !\n\n2. 🎯 **Nouveau look, nouvelle saison** — Découvrez nos collections été à prix réduits.\n\n3. 🔥 **Chaque jour est une raison de briller** — Mode été, prix d'été !",
  keywords: "Voici 10 mots-clés stratégiques pour votre campagne :\n\n#PromoÉté #Soldes2025 #AffichageDynamique #MarketingDigital #CampagnePublicitaire #ÉcranLcd #DigiCMS #Branding #CommunicationVisuelle #TendanceÉté",
  evaluate: "📊 **Évaluation du message :**\n\n• Impact visuel : 7/10\n• Clarté du message : 8/10\n• Appel à l'action : 6/10\n• Originalité : 7/10\n\n**Score global : 7.4/10**\n\n**Conseils d'amélioration :**\n• Ajoutez un call-to-action plus visible\n• Réduisez la longueur du texte pour l'affichage dynamique\n• Utilisez des couleurs plus contrastées",
  visual: "🎨 **Idée de visuel pour votre affichage :**\n\n**Concept :** « Vague de fraîcheur »\n\n• **Couleurs dominantes :** Bleu turquoise, blanc, orange corail\n• **Typographie :** Titre en gras (Poppins Bold), sous-titre en léger (Poppins Light)\n• **Éléments visuels :** Une vague stylisée qui traverse l'écran de gauche à droite\n• **Message principal :** « Plongez dans l'été »\n• **Call-to-action :** « Découvrez nos offres »\n• **Format recommandé :** 1920x1080 (16:9)",
  description: "📝 **Description d'affichage dynamique :**\n\n**Titre :** L'été s'invite chez vous ☀️\n\n**Sous-titre :** Profitez de -30% sur toute la collection\n\n**Message :** Découvrez nos nouveautés et profitez des prix les plus bas de la saison. Offre valable jusqu'au 31 août.\n\n**Call-to-action :** Rendez-vous en magasin ou sur digika.com\n\n**Durée d'affichage suggérée :** 8 secondes"
};

// Historique des conversations
let chatHistory = [];

// Ajouter un message au chat
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
  
  // Sauvegarder dans l'historique
  if (isUser) {
    chatHistory.push({ role: 'user', content: text });
  } else {
    chatHistory.push({ role: 'assistant', content: text });
  }
  saveHistory();
}

// Formater le message (convertir les retours à la ligne en HTML)
function formatMessage(text) {
  return text.replace(/\n/g, '<br>').replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
}

// Ajouter un indicateur de chargement
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

// Supprimer l'indicateur de chargement
function removeLoadingIndicator() {
  const loadingDiv = document.getElementById('loadingIndicator');
  if (loadingDiv) loadingDiv.remove();
}

// Générer une réponse IA (simulée)
async function generateAIResponse(prompt, toolType = null) {
  return new Promise((resolve) => {
    setTimeout(() => {
      if (toolType && aiResponses[toolType]) {
        resolve(aiResponses[toolType]);
      } else {
        // Réponse générique
        const lowerPrompt = prompt.toLowerCase();
        if (lowerPrompt.includes('slogan') || lowerPrompt.includes('accroche')) {
          resolve(aiResponses.slogan);
        } else if (lowerPrompt.includes('mot-clé') || lowerPrompt.includes('hashtag')) {
          resolve(aiResponses.keywords);
        } else if (lowerPrompt.includes('évalue') || lowerPrompt.includes('qualité')) {
          resolve(aiResponses.evaluate);
        } else if (lowerPrompt.includes('visuel') || lowerPrompt.includes('affiche')) {
          resolve(aiResponses.visual);
        } else if (lowerPrompt.includes('description') || lowerPrompt.includes('affichage')) {
          resolve(aiResponses.description);
        } else {
          resolve("Je n'ai pas bien compris votre demande. Voici ce que je peux faire :\n\n• Générer des slogans\n• Proposer des mots-clés\n• Évaluer des messages marketing\n• Suggérer des idées de visuels\n• Rédiger des descriptions d'affichage\n\nEssayez de reformuler votre demande !");
        }
      }
    }, 1500);
  });
}

// Envoyer une requête
async function sendRequest(prompt, toolType = null) {
  if (!prompt.trim()) return;
  
  // Ajouter le message utilisateur
  addMessage(prompt, true);
  
  // Vider le champ
  document.getElementById('userPrompt').value = '';
  
  // Ajouter indicateur de chargement
  addLoadingIndicator();
  
  // Générer la réponse
  const response = await generateAIResponse(prompt, toolType);
  
  // Supprimer l'indicateur
  removeLoadingIndicator();
  
  // Ajouter la réponse
  addMessage(response, false);
}

// Sauvegarder l'historique dans localStorage
function saveHistory() {
  localStorage.setItem('aiChatHistory', JSON.stringify(chatHistory.slice(-20)));
}

// Charger l'historique
function loadHistory() {
  const saved = localStorage.getItem('aiChatHistory');
  if (saved) {
    try {
      const history = JSON.parse(saved);
      history.forEach(msg => {
        addMessage(msg.content, msg.role === 'user');
      });
    } catch(e) {}
  }
}

// Effacer la conversation
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
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
  // Charger l'historique
  loadHistory();
  
  // Si pas d'historique, afficher le message par défaut
  if (chatHistory.length === 0) {
    clearConversation();
  }
  
  // Boutons des outils
  document.querySelectorAll('.tool-card').forEach(btn => {
    btn.addEventListener('click', async () => {
      const tool = btn.dataset.tool;
      let prompt = '';
      switch(tool) {
        case 'slogan':
          prompt = 'Génère un slogan percutant pour notre nouvelle campagne marketing';
          break;
        case 'keywords':
          prompt = 'Propose 10 mots-clés stratégiques pour une campagne d\'affichage dynamique';
          break;
        case 'evaluate':
          prompt = 'Évalue la qualité de ce message marketing : "Profitez de nos promotions exceptionnelles ! Ne manquez pas cette opportunité unique !"';
          break;
        case 'visual':
          prompt = 'Donne-moi une idée créative de visuel pour une campagne d\'été sur écran extérieur';
          break;
        case 'description':
          prompt = 'Rédige une description attrayante pour une campagne d\'affichage dynamique';
          break;
      }
      await sendRequest(prompt, tool);
    });
  });
  
  // Bouton d'envoi
  document.getElementById('sendPromptBtn')?.addEventListener('click', () => {
    const prompt = document.getElementById('userPrompt').value;
    sendRequest(prompt);
  });
  
  // Envoyer avec Entrée (Ctrl+Entrée pour nouvelle ligne)
  document.getElementById('userPrompt')?.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      document.getElementById('sendPromptBtn').click();
    }
  });
  
  // Bouton clear
  document.getElementById('clearChatBtn')?.addEventListener('click', clearConversation);
  
  // Mots-clés cliquables
  document.querySelectorAll('.keyword-tag').forEach(tag => {
    tag.addEventListener('click', () => {
      const keyword = tag.textContent;
      document.getElementById('userPrompt').value = `Propose des mots-clés similaires à "${keyword}" pour ma campagne`;
      document.getElementById('sendPromptBtn').click();
    });
  });
  
  // Historique cliquable
  document.querySelectorAll('.history-item').forEach(item => {
    item.addEventListener('click', () => {
      const title = item.querySelector('.history-title')?.textContent || '';
      document.getElementById('userPrompt').value = title;
      document.getElementById('sendPromptBtn').click();
    });
  });
  
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