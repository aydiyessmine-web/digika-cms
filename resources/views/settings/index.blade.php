@extends('layouts.app')

@section('title', 'Paramètres - DigiCMS')

@push('styles')
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<link href="{{ asset('css/setting.css') }}" rel="stylesheet">


@endpush

@section('content')
<div class="main-content" id="mainContent">
    <!-- ========================================
    HEADER
    ======================================== -->
    <div class="main-header">
        <div class="header-left">
            <span class="page-title"><i class="fas fa-cog"></i> Paramètres</span>
        </div>
        <div class="header-actions">
            <div class="search-wrapper">
                <input type="text" id="globalSearch" placeholder="Rechercher dans les paramètres...">
                <i class="fas fa-search search-icon"></i>
            </div>
            <div class="settings-container-btn">
                <button class="settings-btn" id="settingsDropdownBtn">
                    <i class="fas fa-sliders-h"></i>
                </button>
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

    <!-- ========================================
    SETTINGS CONTENT
    ======================================== -->
    <div class="settings-content">
        <div class="settings-container">
            <!-- Header -->
            <div class="settings-header">
                <h1><i class="fas fa-sliders-h"></i> Paramètres & Administration</h1>
                <p>Configuration de l'application, API IA, gestion des utilisateurs et permissions</p>
            </div>

            <!-- Tabs -->
            <div class="settings-tabs">
                <button class="settings-tab active" data-tab="display">
                    <i class="fas fa-desktop"></i> Affichage
                </button>
                <button class="settings-tab" data-tab="security">
                    <i class="fas fa-shield-alt"></i> Sécurité
                </button>
            </div>

            <!-- ========================================
            TAB: AFFICHAGE
            ======================================== -->
            <div id="tab-display" class="tab-content active">
                <form class="settings-form" data-group="display">
                    <div class="settings-card">
                        <h3><i class="fas fa-desktop" style="color:#c91818;"></i> Affichage</h3>
                        <p class="subtitle">Configuration du thème et du nombre d'éléments par page</p>
                        <hr class="divider">

                        <div class="form-row">
                            <div class="form-group">
                                <label>Thème par défaut</label>
                                <select name="settings[default_theme]">
                                    <option value="light" {{ ($displaySettings['default_theme'] ?? '') == 'light' ? 'selected' : '' }}>Clair</option>
                                    <option value="dark" {{ ($displaySettings['default_theme'] ?? '') == 'dark' ? 'selected' : '' }}>Sombre</option>
                                    <option value="auto" {{ ($displaySettings['default_theme'] ?? '') == 'auto' ? 'selected' : '' }}>Automatique</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Médias par page</label>
                                <select name="settings[media_per_page]">
                                    <option value="10" {{ ($displaySettings['media_per_page'] ?? '') == '10' ? 'selected' : '' }}>10</option>
                                    <option value="15" {{ ($displaySettings['media_per_page'] ?? '') == '15' ? 'selected' : '' }}>15</option>
                                    <option value="20" {{ ($displaySettings['media_per_page'] ?? '') == '20' ? 'selected' : '' }}>20</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Campagnes par page</label>
                                <select name="settings[campaigns_per_page]">
                                    <option value="6" {{ ($displaySettings['campaigns_per_page'] ?? '') == '6' ? 'selected' : '' }}>6</option>
                                    <option value="9" {{ ($displaySettings['campaigns_per_page'] ?? '') == '9' ? 'selected' : '' }}>9</option>
                                    <option value="12" {{ ($displaySettings['campaigns_per_page'] ?? '') == '12' ? 'selected' : '' }}>12</option>
                                </select>
                            </div>
                        </div>

                        <div class="settings-actions">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save"></i> Enregistrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- ========================================
            TAB: SÉCURITÉ
            ======================================== -->
            <div id="tab-security" class="tab-content">
                <form class="settings-form" data-group="security">
                    <div class="settings-card">
                        <h3><i class="fas fa-shield-alt" style="color:#c91818;"></i> Sécurité & Authentification</h3>
                        <p class="subtitle">Gestion des sessions, 2FA, politique des mots de passe et logs de connexion</p>
                        <hr class="divider">

                        <div class="form-row">
                            <div class="form-group">
                                <label>Expiration de session (minutes)</label>
                                <input type="number" name="settings[session_expiry]" value="{{ $securitySettings['session_expiry'] ?? '60' }}" min="5">
                            </div>
                            <div class="form-group">
                                <label>Durée de blocage (minutes)</label>
                                <input type="number" name="settings[block_duration]" value="{{ $securitySettings['block_duration'] ?? '15' }}" min="1" max="1440">
                                <span style="font-size:11px;color:#9ca3af;display:block;margin-top:4px;">Temps après lequel le compte se débloque automatiquement</span>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Politique mot de passe</label>
                                <select name="settings[password_policy]">
                                    <option value="basic" {{ ($securitySettings['password_policy'] ?? '') == 'basic' ? 'selected' : '' }}>Basique (8 caractères)</option>
                                    <option value="advanced" {{ ($securitySettings['password_policy'] ?? '') == 'advanced' ? 'selected' : '' }}>Avancé (12 caractères + chiffres + symboles)</option>
                                    <option value="strict" {{ ($securitySettings['password_policy'] ?? '') == 'strict' ? 'selected' : '' }}>Strict (16 caractères + chiffres + symboles + majuscules)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Tentatives de connexion</label>
                                <input type="number" name="settings[login_attempts]" value="{{ $securitySettings['login_attempts'] ?? '5' }}" min="1">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Conservation logs connexion</label>
                                <input type="number" name="settings[log_retention_days]" value="{{ $securitySettings['log_retention_days'] ?? '90' }}" min="1">
                                <span style="font-size:11px;color:#9ca3af;display:block;margin-top:4px;">jours</span>
                            </div>
                            <div class="form-group">
                                <label>Délai d'inactivité (minutes)</label>
                                <input type="number" name="settings[inactivity_timeout]" value="{{ $securitySettings['inactivity_timeout'] ?? '15' }}" min="1">
                            </div>
                        </div>

                        <hr class="divider">

                        <div class="checkbox-group">
                            <div class="checkbox-item">
                                <input type="checkbox" name="settings[auto_block_enabled]" value="true" {{ ($securitySettings['auto_block_enabled'] ?? 'true') == 'true' ? 'checked' : '' }}>
                                <label>Blocage automatique 15 min après dépassement</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="settings[log_all_attempts]" value="true" {{ ($securitySettings['log_all_attempts'] ?? 'true') == 'true' ? 'checked' : '' }}>
                                <label>Enregistrer toutes les tentatives de connexion (succès/échec)</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="settings[alert_unknown_device]" value="true" {{ ($securitySettings['alert_unknown_device'] ?? 'true') == 'true' ? 'checked' : '' }}>
                                <label>Alerte email pour nouvelle connexion depuis un appareil inconnu</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="settings[unique_session]" value="true" {{ ($securitySettings['unique_session'] ?? 'true') == 'true' ? 'checked' : '' }}>
                                <label>Session unique (déconnexion automatique sur autre appareil)</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="settings[auto_logout_inactive]" value="true" {{ ($securitySettings['auto_logout_inactive'] ?? 'true') == 'true' ? 'checked' : '' }}>
                                <label>Déconnexion automatique après inactivité</label>
                            </div>
                        </div>

                        <!-- Niveau de sécurité -->
                        <hr class="divider">

                        <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
                            <span class="security-level {{ $securityLevel['class'] ?? 'high' }}" style="background: {{ $securityLevel['color'] ?? '#10b981' }}20; color: {{ $securityLevel['color'] ?? '#10b981' }};">
                                <i class="fas {{ $securityLevel['icon'] ?? 'fa-shield-alt' }}"></i> 
                                Niveau de sécurité : {{ $securityLevel['level'] ?? 'Élevé' }}
                            </span>
                            <span style="font-size:12px;color:#6b7280;">
                                <i class="fas fa-info-circle"></i> 
                                Basé sur la configuration actuelle
                            </span>
                        </div>

                        <!-- ========================================
                        BOUTONS SÉCURITÉ - RESTAURÉS
                        ======================================== -->
                        <div class="settings-actions">
                            <button type="button" class="btn-secondary" onclick="exportLogs()">
                                <i class="fas fa-download"></i> Exporter les logs
                            </button>
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Sessions actives -->
                <div class="settings-card">
                    <h3><i class="fas fa-users" style="color:#c91818;"></i> Sessions actives</h3>
                    <p class="subtitle">Appareils connectés actuellement à votre compte administrateur</p>
                    <hr class="divider">

                    @forelse($activeSessions as $session)
                    <div class="session-item">
                        <div class="session-info">
                            <div class="session-location">
                                <i class="fas fa-map-marker-alt" style="color:#c91818;"></i>
                                {{ $session->ip_address ?? 'Inconnu' }}
                            </div>
                            <div class="session-device">
                                <i class="fas fa-{{ $session->device == 'Mobile' ? 'mobile-alt' : ($session->device == 'Tablet' ? 'tablet' : 'desktop') }}"></i>
                                {{ $session->user_agent ?? 'Appareil inconnu' }}
                            </div>
                            <div class="session-time">
                                <i class="far fa-clock"></i>
                                {{ $session->last_activity ? $session->last_activity->diffForHumans() : 'Récemment' }}
                            </div>
                        </div>
                        <div class="session-status {{ $session->is_current ? '' : 'inactive' }}">
                            <i class="fas fa-circle" style="{{ $session->is_current ? 'color:#10b981;' : 'color:#9ca3af;' }}"></i>
                            {{ $session->is_current ? 'En cours (actuel)' : 'Terminé' }}
                        </div>
                        <div class="session-actions">
                            @if(!$session->is_current)
                            <button class="btn-sm-danger" onclick="revokeSession('{{ $session->id }}')">
                                <i class="fas fa-times"></i> Révoquer
                            </button>
                            @endif
                        </div>
                    </div>
                    @empty
                    <p style="color:#9ca3af;text-align:center;padding:20px;">Aucune session active</p>
                    @endforelse
                </div>

                <!-- Historique des connexions -->
                <div class="settings-card">
                    <h3><i class="fas fa-history" style="color:#c91818;"></i> Historique des connexions récentes</h3>
                    <p class="subtitle">Dernières activités sur votre compte</p>
                    <hr class="divider">

                    <div style="overflow-x:auto;">
                        <table class="logs-table">
                            <thead>
                                <tr>
                                    <th>Date & Heure</th>
                                    <th>Adresse IP</th>
                                    <th>Appareil</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($loginHistory as $log)
                                <tr>
                                    <td>{{ $log->last_activity ? $log->last_activity->format('d/m/Y, H:i') : 'N/A' }}</td>
                                    <td>{{ $log->ip_address ?? 'N/A' }}</td>
                                    <td>{{ $log->user_agent ?? 'N/A' }}</td>
                                    <td>
                                        <span class="{{ $log->is_current ? 'status-success' : 'status-failed' }}">
                                            <i class="fas fa-{{ $log->is_current ? 'check-circle' : 'times-circle' }}"></i>
                                            {{ $log->is_current ? 'Succès' : 'Terminé' }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" style="text-align:center;color:#9ca3af;">Aucun historique</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="settings-actions">
                        <button type="button" class="btn-danger" onclick="clearHistory()">
                            <i class="fas fa-trash"></i> Effacer l'historique
                        </button>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div style="text-align:center;padding:20px 0;color:#9ca3af;font-size:12px;border-top:1px solid #eef2f6;margin-top:20px;">
                Digika CMS – Configuration avancée | Affichage dynamique intelligent | Sécurité
            </div>
        </div>
    </div>
</div>

<script>
// ========================================
// VARIABLES GLOBALES
// ========================================
let zoom = 100;

// ========================================
// TOAST
// ========================================
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = 'toast-notification ' + type;
    toast.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i> ${message}`;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

// ========================================
// SETTINGS DROPDOWN - FONCTIONNE
// ========================================
function initSettingsDropdown() {
    const dropdownBtn = document.getElementById('settingsDropdownBtn');
    const dropdown = document.getElementById('settingsDropdown');
    
    if (dropdownBtn && dropdown) {
        // Supprimer les anciens écouteurs pour éviter les doublons
        const newBtn = dropdownBtn.cloneNode(true);
        dropdownBtn.parentNode.replaceChild(newBtn, dropdownBtn);
        
        newBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdown.classList.toggle('show');
        });
        
        document.addEventListener('click', function() {
            dropdown.classList.remove('show');
        });
    }
}

// ========================================
// ZOOM - FONCTIONNE
// ========================================
function initZoom() {
    const zoomSpan = document.getElementById('zoomValue');
    const mainDiv = document.getElementById('mainContent');
    const zoomIn = document.getElementById('zoomIn');
    const zoomOut = document.getElementById('zoomOut');

    function updateZoom(value) {
        zoom = Math.min(Math.max(value, 70), 150);
        if (zoomSpan) zoomSpan.innerText = zoom + '%';
        if (mainDiv) {
            // Utiliser zoom au lieu de transform
            mainDiv.style.zoom = zoom / 100;
            mainDiv.style.transform = 'none';
        }
    }

    if (zoomIn) {
        // Supprimer les anciens écouteurs
        const newZoomIn = zoomIn.cloneNode(true);
        zoomIn.parentNode.replaceChild(newZoomIn, zoomIn);
        newZoomIn.addEventListener('click', function(e) {
            e.stopPropagation();
            updateZoom(zoom + 10);
        });
    }
    
    if (zoomOut) {
        const newZoomOut = zoomOut.cloneNode(true);
        zoomOut.parentNode.replaceChild(newZoomOut, zoomOut);
        newZoomOut.addEventListener('click', function(e) {
            e.stopPropagation();
            updateZoom(zoom - 10);
        });
    }
}

// ========================================
// THEME - FONCTIONNE
// ========================================
function initTheme() {
    document.querySelectorAll('[data-theme]').forEach(function(opt) {
        const newOpt = opt.cloneNode(true);
        opt.parentNode.replaceChild(newOpt, opt);
        
        newOpt.addEventListener('click', function() {
            const theme = this.getAttribute('data-theme');
            const mainDiv = document.getElementById('mainContent');
            const dropdown = document.getElementById('settingsDropdown');
            
            if (theme === 'dark') {
                mainDiv.style.backgroundColor = '#1e293b';
                mainDiv.style.color = '#e2e8f0';
                document.querySelectorAll('.settings-card, .settings-dropdown').forEach(function(el) {
                    if (el) {
                        el.style.backgroundColor = '#2d3a4e';
                        el.style.color = '#e2e8f0';
                        el.style.borderColor = '#4a5568';
                    }
                });
                document.querySelectorAll('.form-group input, .form-group select').forEach(function(el) {
                    if (el) {
                        el.style.backgroundColor = '#1e293b';
                        el.style.color = '#e2e8f0';
                        el.style.borderColor = '#4a5568';
                    }
                });
            } else {
                mainDiv.style.backgroundColor = '#fff';
                mainDiv.style.color = '#000';
                document.querySelectorAll('.settings-card, .settings-dropdown').forEach(function(el) {
                    if (el) {
                        el.style.backgroundColor = 'white';
                        el.style.color = '#000';
                        el.style.borderColor = '#eef2f6';
                    }
                });
                document.querySelectorAll('.form-group input, .form-group select').forEach(function(el) {
                    if (el) {
                        el.style.backgroundColor = '#fafbfc';
                        el.style.color = '#374151';
                        el.style.borderColor = '#d1d5db';
                    }
                });
            }
            if (dropdown) dropdown.classList.remove('show');
        });
    });
}

// ========================================
// SEARCH - FONCTIONNE
// ========================================
function initSearch() {
    const searchInput = document.getElementById('globalSearch');
    if (searchInput) {
        const newSearch = searchInput.cloneNode(true);
        searchInput.parentNode.replaceChild(newSearch, searchInput);
        
        newSearch.addEventListener('input', function() {
            const term = this.value.toLowerCase().trim();
            const cards = document.querySelectorAll('.settings-card');
            
            if (term === '') {
                cards.forEach(card => {
                    card.style.display = 'block';
                });
                return;
            }
            
            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                if (text.includes(term)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
}

// ========================================
// TABS
// ========================================
function initTabs() {
    const tabs = document.querySelectorAll('.settings-tab');
    const contents = document.querySelectorAll('.tab-content');

    tabs.forEach(function(tab) {
        const newTab = tab.cloneNode(true);
        tab.parentNode.replaceChild(newTab, tab);
        
        newTab.addEventListener('click', function() {
            const target = this.dataset.tab;
            
            document.querySelectorAll('.settings-tab').forEach(function(t) {
                t.classList.remove('active');
            });
            this.classList.add('active');
            
            document.querySelectorAll('.tab-content').forEach(function(c) {
                c.classList.remove('active');
            });
            document.getElementById('tab-' + target).classList.add('active');
        });
    });
}

// ========================================
// APPLY SETTINGS
// ========================================
function applySettings() {
    fetch('/settings/get')
        .then(response => response.json())
        .then(data => {
            const mainDiv = document.getElementById('mainContent');
            if (data.theme === 'dark') {
                mainDiv.style.backgroundColor = '#1e293b';
                mainDiv.style.color = '#e2e8f0';
                document.querySelectorAll('.settings-card, .settings-dropdown, .settings-tab.active').forEach(function(el) {
                    if (el) {
                        el.style.backgroundColor = '#2d3a4e';
                        el.style.color = '#e2e8f0';
                        el.style.borderColor = '#4a5568';
                    }
                });
                document.querySelectorAll('.form-group input, .form-group select').forEach(function(el) {
                    if (el) {
                        el.style.backgroundColor = '#1e293b';
                        el.style.color = '#e2e8f0';
                        el.style.borderColor = '#4a5568';
                    }
                });
                document.querySelectorAll('.form-group label').forEach(function(el) {
                    if (el) el.style.color = '#94a3b8';
                });
            } else {
                mainDiv.style.backgroundColor = '#fff';
                mainDiv.style.color = '#000';
                document.querySelectorAll('.settings-card, .settings-dropdown, .settings-tab.active').forEach(function(el) {
                    if (el) {
                        el.style.backgroundColor = 'white';
                        el.style.color = '#000';
                        el.style.borderColor = '#eef2f6';
                    }
                });
                document.querySelectorAll('.form-group input, .form-group select').forEach(function(el) {
                    if (el) {
                        el.style.backgroundColor = '#fafbfc';
                        el.style.color = '#374151';
                        el.style.borderColor = '#d1d5db';
                    }
                });
                document.querySelectorAll('.form-group label').forEach(function(el) {
                    if (el) el.style.color = '#374151';
                });
            }
        })
        .catch(error => console.error('Erreur chargement paramètres:', error));
}

// ========================================
// FORM SUBMISSION
// ========================================
function initForms() {
    const forms = document.querySelectorAll('.settings-form');
    forms.forEach(function(form) {
        const newForm = form.cloneNode(true);
        form.parentNode.replaceChild(newForm, form);
        
        newForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const group = this.dataset.group;
            const formData = new FormData(this);
            const settings = {};

            for (let [key, value] of formData.entries()) {
                if (key.startsWith('settings[')) {
                    const realKey = key.replace('settings[', '').replace(']', '');
                    settings[realKey] = value;
                }
            }

            this.querySelectorAll('input[type="checkbox"]').forEach(function(cb) {
                const name = cb.name.replace('settings[', '').replace(']', '');
                settings[name] = cb.checked ? 'true' : 'false';
            });

            saveSettings(group, settings);
        });
    });
}

function saveSettings(group, settings) {
    fetch('/settings/group/' + group, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ settings: settings })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Paramètres enregistrés avec succès', 'success');
        } else {
            showToast('Erreur lors de l\'enregistrement', 'error');
        }
    })
    .catch(error => {
        showToast('Erreur de connexion', 'error');
    });
}

// ========================================
// AUTRES FONCTIONS
// ========================================
function revokeSession(sessionId) {
    if (!confirm('Voulez-vous révoquer cette session ?')) return;
    
    fetch('/settings/session/' + sessionId + '/revoke', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Session révoquée avec succès', 'success');
            location.reload();
        } else {
            showToast('Erreur lors de la révocation', 'error');
        }
    });
}

function clearHistory() {
    if (!confirm('Voulez-vous effacer tout l\'historique des connexions ?')) return;
    
    fetch('/settings/history/clear', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message || 'Historique effacé avec succès', 'success');
            setTimeout(function() {
                location.reload();
            }, 1000);
        } else {
            showToast(data.message || 'Erreur lors de l\'effacement', 'error');
        }
    })
    .catch(error => {
        showToast('Erreur de connexion', 'error');
    });
}

function exportLogs() {
    window.location.href = '/settings/logs/export';
}

// ========================================
// INITIALISATION
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    // Appliquer les paramètres
    applySettings();
    
    // Initialiser les composants
    initTabs();
    initSettingsDropdown();
    initZoom();
    initTheme();
    initSearch();
    initForms();
});
</script>
@endsection
