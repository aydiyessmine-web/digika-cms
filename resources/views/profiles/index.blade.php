@extends('layouts.app')

@section('title', 'Profils - DigiCMS')

@push('styles')
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<link href="{{ asset('css/profile.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="main-content" id="mainContent">
    <!-- ========================================
    HEADER
    ======================================== -->
    <div class="main-header">
        <div class="header-left">
            <span class="page-title"><i class="fas fa-users"></i> Profils</span>
        </div>
        <div class="header-actions">
            <div class="search-wrapper">
                <input type="text" id="globalSearch" placeholder="Rechercher un utilisateur...">
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
    PROFILES CONTENT
    ======================================== -->
    <div class="profiles-content">
        <div class="profiles-container">
            <!-- Header -->
            <div class="profiles-header">
                <h1><i class="fas fa-users-cog"></i> Gestion des profils</h1>
                <p>Gérez les utilisateurs, rôles et permissions de la plateforme Digika CMS</p>
            </div>

            <!-- Summary Cards -->
            <div class="summary-grid">
                <div class="summary-card">
                    <div class="summary-icon blue"><i class="fas fa-users"></i></div>
                    <div class="summary-info">
                        <span class="summary-value">{{ $totalUsers ?? 0 }}</span>
                        <span class="summary-label">Utilisateurs totaux</span>
                    </div>
                </div>
                <div class="summary-card">
                    <div class="summary-icon green"><i class="fas fa-user-check"></i></div>
                    <div class="summary-info">
                        <span class="summary-value">{{ $activeUsers ?? 0 }}</span>
                        <span class="summary-label">Comptes actifs</span>
                    </div>
                </div>
                <div class="summary-card">
                    <div class="summary-icon pink"><i class="fas fa-user-shield"></i></div>
                    <div class="summary-info">
                        <span class="summary-value">{{ $adminUsers ?? 0 }}</span>
                        <span class="summary-label">Administrateurs</span>
                    </div>
                </div>
                <div class="summary-card">
                    <div class="summary-icon purple"><i class="fas fa-envelope"></i></div>
                    <div class="summary-info">
                        <span class="summary-value">{{ $pendingInvitations ?? 0 }}</span>
                        <span class="summary-label">Invitations en attente</span>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="filters-toolbar">
                <div class="filter-search-wrapper">
                    <input type="text" id="userSearchInput" placeholder="Rechercher un utilisateur...">
                </div>
                <div class="filter-select-wrapper">
                    <select id="roleFilter">
                        <option value="all">Tous les rôles</option>
                        <option value="admin">Administrateurs</option>
                        <option value="marketing">Marketing</option>
                    </select>
                </div>
                <div class="filter-select-wrapper">
                    <select id="statusFilter">
                        <option value="all">Tous les statuts</option>
                        <option value="active">Actifs</option>
                        <option value="inactive">Inactifs</option>
                    </select>
                </div>
                <div class="toolbar-actions">
                    <button class="btn-export" onclick="exportUsers()">
                        <i class="fas fa-download"></i> Exporter
                    </button>
                    <button class="btn-add" onclick="openAddModal()">
                        <i class="fas fa-plus"></i> Nouvel utilisateur
                    </button>
                </div>
            </div>

            <!-- Users Table -->
            <div class="table-wrapper">
                <table class="profiles-table" id="usersTable">
                    <thead>
                        <tr>
                            <th>Utilisateur</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Statut</th>
                            <th>Dernière activité</th>
                            <th style="text-align:center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                        @forelse($users ?? [] as $user)
                        <tr data-user-id="{{ $user->id }}" data-name="{{ strtolower($user->name) }}" data-email="{{ strtolower($user->email) }}" data-role="{{ $user->role }}" data-status="{{ $user->is_active ? 'active' : 'inactive' }}">
                            <td>
                                <div class="user-name">
                                    <div class="user-avatar-sm {{ $user->role == 'admin' ? 'pink' : ($user->is_active ? 'green' : 'orange') }}">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    {{ $user->name }}
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="role-badge {{ $user->role }}">
                                    <i class="fas {{ $user->role == 'admin' ? 'fa-user-shield' : 'fa-user-tag' }}"></i>
                                    {{ $user->role == 'admin' ? 'Administrateur' : 'Marketing' }}
                                </span>
                            </td>
                            <td>
                                @if($user->is_active)
                                    <span class="status-badge active">
                                        <i class="fas fa-circle"></i>
                                        Actif
                                    </span>
                                @else
                                    <span class="status-badge inactive">
                                        <i class="fas fa-circle"></i>
                                        Inactif
                                    </span>
                                @endif
                            </td>
                            <td class="last-activity">
                                @if($user->last_login_at)
                                    {{ $user->last_login_at->format('d/m/Y, H:i') }}
                                @else
                                    Jamais
                                @endif
                            </td>
                            <td style="text-align:center;">
                                <div class="table-actions">
                                    <button class="action-btn edit" onclick="openEditModal({{ $user->id }})" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="action-btn toggle {{ $user->is_active ? 'active' : 'inactive' }}" onclick="toggleUser({{ $user->id }})" title="{{ $user->is_active ? 'Désactiver' : 'Activer' }}">
                                        <i class="fas {{ $user->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                                    </button>
                                    @if($user->id != auth()->id())
                                    <button class="action-btn delete" onclick="deleteUser({{ $user->id }})" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align:center;padding:40px;color:#9ca3af;">
                                <i class="fas fa-users" style="font-size:32px;display:block;margin-bottom:10px;"></i>
                                Aucun utilisateur trouvé
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer -->
            <div style="text-align:center;padding:20px 0;color:#9ca3af;font-size:12px;border-top:1px solid #eef2f6;margin-top:20px;">
                Digika CMS – Gestion des profils et permissions | Contrôle total administrateur
            </div>
        </div>
    </div>
</div>

<!-- ========================================
MODAL ADD/EDIT USER
======================================== -->
<div id="userModal" class="modal">
    <div class="modal-content">
        <h3 id="modalTitle"><i class="fas fa-user-plus"></i> Nouvel utilisateur</h3>
        <form id="userForm">
            @csrf
            <input type="hidden" id="userId" name="id">
            
            <div class="form-group">
                <label for="userName">Nom complet</label>
                <input type="text" id="userName" name="name" placeholder="Jean Dupont" required>
            </div>
            <div class="form-group">
                <label for="userEmail">Adresse Email</label>
                <input type="email" id="userEmail" name="email" placeholder="jean@digika.com" required>
            </div>
            <div class="form-group">
                <label for="userPassword">Mot de passe</label>
                <input type="password" id="userPassword" name="password" placeholder="Laisser vide pour ne pas modifier">
            </div>
            <div class="form-group">
                <label for="userRole">Rôle</label>
                <select id="userRole" name="role" required>
                    <option value="admin">Administrateur</option>
                    <option value="marketing">Marketing</option>
                </select>
            </div>
            <div class="form-group">
                <div class="checkbox-inline">
                    <input type="checkbox" id="userActive" name="is_active" value="1" checked>
                    <label for="userActive">Compte actif</label>
                </div>
            </div>
            <div class="modal-buttons">
                <button type="button" class="cancel" onclick="closeModal()">Annuler</button>
                <button type="submit" class="submit" id="modalSubmitBtn">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// ========================================
// VARIABLES
// ========================================
let currentUserId = null;
let isEditMode = false;

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
// MODAL
// ========================================
function openAddModal() {
    isEditMode = false;
    currentUserId = null;
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-user-plus"></i> Nouvel utilisateur';
    document.getElementById('userForm').reset();
    document.getElementById('userId').value = '';
    document.getElementById('userPassword').placeholder = 'Entrer un mot de passe';
    document.getElementById('userPassword').required = true;
    document.getElementById('userActive').checked = true;
    document.getElementById('modalSubmitBtn').innerHTML = '<i class="fas fa-save"></i> Créer';
    document.getElementById('userModal').classList.add('show');
}

function openEditModal(id) {
    isEditMode = true;
    currentUserId = id;
    
    const row = document.querySelector(`tr[data-user-id="${id}"]`);
    if (!row) return;
    
    // Récupérer les données du tableau
    const name = row.querySelector('.user-name').textContent.trim();
    const email = row.querySelector('td:nth-child(2)').textContent.trim();
    const role = row.getAttribute('data-role');
    const status = row.getAttribute('data-status');
    
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-user-edit"></i> Modifier l\'utilisateur';
    document.getElementById('userId').value = id;
    document.getElementById('userName').value = name;
    document.getElementById('userEmail').value = email;
    document.getElementById('userRole').value = role;
    document.getElementById('userPassword').placeholder = 'Laisser vide pour ne pas modifier';
    document.getElementById('userPassword').required = false;
    document.getElementById('userActive').checked = status === 'active';
    document.getElementById('modalSubmitBtn').innerHTML = '<i class="fas fa-save"></i> Modifier';
    document.getElementById('userModal').classList.add('show');
}

function closeModal() {
    document.getElementById('userModal').classList.remove('show');
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('userModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

// ========================================
// FORM SUBMISSION
// ========================================
document.getElementById('userForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const id = document.getElementById('userId').value;
    const name = document.getElementById('userName').value;
    const email = document.getElementById('userEmail').value;
    const password = document.getElementById('userPassword').value;
    const role = document.getElementById('userRole').value;
    const is_active = document.getElementById('userActive').checked ? 1 : 0;
    
    const url = id ? `/profiles/${id}` : '/profiles';
    const method = id ? 'PUT' : 'POST';
    
    const data = { name, email, role, is_active };
    if (password) data.password = password;
    
    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast(result.message, 'success');
            closeModal();
            setTimeout(() => location.reload(), 1000);
        } else {
            let errorMsg = result.message || 'Erreur';
            if (result.errors) {
                errorMsg = Object.values(result.errors).flat().join(', ');
            }
            showToast('Erreur: ' + errorMsg, 'error');
        }
    } catch (error) {
        showToast('Erreur de connexion', 'error');
    }
});

// ========================================
// TOGGLE USER
// ========================================
function toggleUser(id) {
    if (!confirm('Voulez-vous changer le statut de cet utilisateur ?')) return;
    
    fetch(`/profiles/${id}/toggle-active`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message || 'Erreur', 'error');
        }
    })
    .catch(error => {
        showToast('Erreur de connexion', 'error');
    });
}

// ========================================
// DELETE USER
// ========================================
function deleteUser(id) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.')) return;
    
    fetch(`/profiles/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message || 'Erreur', 'error');
        }
    })
    .catch(error => {
        showToast('Erreur de connexion', 'error');
    });
}

// ========================================
// EXPORT USERS
// ========================================
function exportUsers() {
    window.location.href = '/profiles/export';
}

// ========================================
// SEARCH & FILTER
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('userSearchInput');
    const roleFilter = document.getElementById('roleFilter');
    const statusFilter = document.getElementById('statusFilter');
    
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const roleValue = roleFilter.value;
        const statusValue = statusFilter.value;
        
        const rows = document.querySelectorAll('#usersTableBody tr');
        let visibleCount = 0;
        
        rows.forEach(row => {
            const name = row.getAttribute('data-name') || '';
            const email = row.getAttribute('data-email') || '';
            const role = row.getAttribute('data-role') || '';
            const status = row.getAttribute('data-status') || '';
            
            const matchSearch = searchTerm === '' || name.includes(searchTerm) || email.includes(searchTerm);
            const matchRole = roleValue === 'all' || role === roleValue;
            const matchStatus = statusValue === 'all' || status === statusValue;
            
            if (matchSearch && matchRole && matchStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Afficher un message si aucun résultat
        const emptyMessage = document.getElementById('emptyMessage');
        if (visibleCount === 0) {
            if (!document.getElementById('emptyMessage')) {
                const tbody = document.getElementById('usersTableBody');
                const msg = document.createElement('tr');
                msg.id = 'emptyMessage';
                msg.innerHTML = `<td colspan="6" style="text-align:center;padding:40px;color:#9ca3af;">
                    <i class="fas fa-users" style="font-size:32px;display:block;margin-bottom:10px;"></i>
                    Aucun utilisateur trouvé
                </td>`;
                tbody.appendChild(msg);
            }
        } else {
            const msg = document.getElementById('emptyMessage');
            if (msg) msg.remove();
        }
    }
    
    if (searchInput) searchInput.addEventListener('input', filterTable);
    if (roleFilter) roleFilter.addEventListener('change', filterTable);
    if (statusFilter) statusFilter.addEventListener('change', filterTable);
});

// ========================================
// SETTINGS DROPDOWN
// ========================================
const dropdownBtn = document.getElementById('settingsDropdownBtn');
const dropdown = document.getElementById('settingsDropdown');
if (dropdownBtn) {
    dropdownBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        dropdown.classList.toggle('show');
    });
    document.addEventListener('click', function() {
        dropdown.classList.remove('show');
    });
}

// ========================================
// ZOOM - CORRECTION
// ========================================
let zoom = 100;
const zoomSpan = document.getElementById('zoomValue');
const mainDiv = document.getElementById('mainContent');
const zoomIn = document.getElementById('zoomIn');
const zoomOut = document.getElementById('zoomOut');

function updateZoom(value) {
    zoom = Math.min(Math.max(value, 70), 150);
    zoomSpan.innerText = zoom + '%';
    // Utiliser zoom CSS au lieu de transform pour éviter les problèmes de hauteur
    mainDiv.style.zoom = zoom / 100;
    mainDiv.style.transform = 'none';
}

if (zoomIn) {
    zoomIn.addEventListener('click', function(e) {
        e.stopPropagation();
        updateZoom(zoom + 10);
    });
}
if (zoomOut) {
    zoomOut.addEventListener('click', function(e) {
        e.stopPropagation();
        updateZoom(zoom - 10);
    });
}

// ========================================
// THEME
// ========================================
document.querySelectorAll('[data-theme]').forEach(function(opt) {
    opt.addEventListener('click', function() {
        const theme = this.dataset.theme;
        if (theme === 'dark') {
            mainDiv.style.backgroundColor = '#1e293b';
            mainDiv.style.color = '#e2e8f0';
            document.querySelectorAll('.summary-card, .settings-dropdown, .modal-content, .table-wrapper').forEach(function(el) {
                if (el) {
                    el.style.backgroundColor = '#2d3a4e';
                    el.style.color = '#e2e8f0';
                    el.style.borderColor = '#4a5568';
                }
            });
            document.querySelectorAll('.form-group input, .form-group select, .filter-search-wrapper input, .filter-select-wrapper select').forEach(function(el) {
                if (el) {
                    el.style.backgroundColor = '#1e293b';
                    el.style.color = '#e2e8f0';
                    el.style.borderColor = '#4a5568';
                }
            });
            document.querySelectorAll('.profiles-table th').forEach(function(el) {
                if (el) el.style.backgroundColor = '#1e293b';
            });
            document.querySelectorAll('.profiles-table td').forEach(function(el) {
                if (el) el.style.borderBottomColor = '#4a5568';
            });
        } else {
            mainDiv.style.backgroundColor = '#fff';
            mainDiv.style.color = '#000';
            document.querySelectorAll('.summary-card, .settings-dropdown, .modal-content, .table-wrapper').forEach(function(el) {
                if (el) {
                    el.style.backgroundColor = 'white';
                    el.style.color = '#000';
                    el.style.borderColor = '#eef2f6';
                }
            });
            document.querySelectorAll('.form-group input, .form-group select, .filter-search-wrapper input, .filter-select-wrapper select').forEach(function(el) {
                if (el) {
                    el.style.backgroundColor = '#fafbfc';
                    el.style.color = '#374151';
                    el.style.borderColor = '#d1d5db';
                }
            });
            document.querySelectorAll('.profiles-table th').forEach(function(el) {
                if (el) el.style.backgroundColor = '#f8fafc';
            });
            document.querySelectorAll('.profiles-table td').forEach(function(el) {
                if (el) el.style.borderBottomColor = '#f0f0f0';
            });
        }
        dropdown.classList.remove('show');
    });
});

// ========================================
// GLOBAL SEARCH (header)
// ========================================
const globalSearch = document.getElementById('globalSearch');
if (globalSearch) {
    globalSearch.addEventListener('input', function() {
        const term = this.value.toLowerCase().trim();
        const searchInput = document.getElementById('userSearchInput');
        if (searchInput) {
            searchInput.value = term;
            searchInput.dispatchEvent(new Event('input'));
        }
    });
}
</script>
@endsection