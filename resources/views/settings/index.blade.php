@extends('layouts.app')

@section('title', 'Paramètres - DigiCMS')

@push('styles')
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<style>
    /* ========================================
       FIX: MAIN CONTENT - CORRECTION
    ======================================== */
    .dashboard-container {
        display: flex !important;
        height: 100vh !important;
        width: 100vw !important;
        overflow: hidden !important;
    }

    .sidebar {
        flex: 0 0 240px !important;
        width: 240px !important;
        background-color: #121214 !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: space-between !important;
        border-right: 1px solid #1f1f23 !important;
        flex-shrink: 0 !important;
        height: 100vh !important;
        position: relative !important;
        z-index: 10 !important;
    }

    .main-content {
        flex: 1 !important;
        background-color: #ffffff !important;
        padding: 24px 32px !important;
        overflow-y: auto !important;
        display: flex !important;
        flex-direction: column !important;
        height: 100vh !important;
        width: calc(100% - 240px) !important;
        max-width: calc(100% - 240px) !important;
        position: relative !important;
    }

    .main-header {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        margin-bottom: 24px !important;
        padding-bottom: 16px !important;
        border-bottom: 2px solid #eef2f6 !important;
        flex-shrink: 0 !important;
        flex-wrap: wrap !important;
        gap: 12px !important;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .header-left .page-title {
        font-size: 20px;
        font-weight: 600;
        color: #0d121c;
    }

    .header-left .page-title i {
        color: #c91818;
        margin-right: 8px;
    }

    .header-actions {
        display: flex !important;
        align-items: center !important;
        gap: 16px !important;
        flex-wrap: wrap !important;
    }

    .search-wrapper {
        position: relative !important;
        width: 260px !important;
    }

    .search-wrapper input {
        width: 100% !important;
        height: 38px !important;
        padding: 0 38px 0 16px !important;
        border: 1.5px solid #e5e7eb !important;
        border-radius: 30px !important;
        font-size: 13px !important;
        outline: none !important;
        transition: all 0.2s !important;
        background: #fafbfc !important;
    }

    .search-wrapper input:focus {
        border-color: #c91818 !important;
        box-shadow: 0 0 0 3px rgba(201, 24, 24, 0.1) !important;
        background: white !important;
    }

    .search-wrapper .search-icon {
        position: absolute !important;
        right: 14px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        color: #9ca3af !important;
        pointer-events: none !important;
        font-size: 14px !important;
    }

    .settings-container-btn {
        position: relative !important;
    }

    .settings-btn {
        background: none !important;
        border: none !important;
        cursor: pointer !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 38px !important;
        height: 38px !important;
        border-radius: 50% !important;
        transition: background-color 0.2s !important;
        font-size: 18px !important;
        color: #4b5563 !important;
    }

    .settings-btn:hover {
        background-color: #f3f4f6 !important;
    }

    .settings-dropdown {
        position: absolute !important;
        top: 45px !important;
        right: 0 !important;
        background: white !important;
        border-radius: 12px !important;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1) !important;
        width: 220px !important;
        z-index: 100 !important;
        display: none !important;
        border: 1px solid #e5e7eb !important;
        overflow: hidden !important;
    }

    .settings-dropdown.show {
        display: block !important;
    }

    .dropdown-section {
        padding: 12px 16px !important;
        border-bottom: 1px solid #f0f0f0 !important;
    }

    .dropdown-section:last-child {
        border-bottom: none !important;
    }

    .dropdown-title {
        font-size: 11px !important;
        font-weight: 600 !important;
        color: #6b7280 !important;
        margin-bottom: 8px !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
    }

    .dropdown-option {
        display: flex !important;
        align-items: center !important;
        gap: 10px !important;
        padding: 6px 0 !important;
        cursor: pointer !important;
        font-size: 12px !important;
        color: #374151 !important;
        transition: all 0.2s !important;
        border-radius: 6px !important;
    }

    .dropdown-option:hover {
        color: #c91818 !important;
        padding-left: 4px !important;
    }

    .zoom-control {
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
        justify-content: space-between !important;
    }

    .zoom-btn {
        background: #f3f4f6 !important;
        border: none !important;
        width: 28px !important;
        height: 28px !important;
        border-radius: 6px !important;
        cursor: pointer !important;
        font-weight: bold !important;
        transition: all 0.2s !important;
        font-size: 12px !important;
    }

    .zoom-btn:hover {
        background: #e5e7eb !important;
    }

    .zoom-value {
        font-size: 12px !important;
        font-weight: 500 !important;
    }

    .settings-content {
        flex: 1 !important;
        overflow-y: auto !important;
        padding-right: 8px !important;
    }

    .settings-content::-webkit-scrollbar {
        width: 6px !important;
    }

    .settings-content::-webkit-scrollbar-track {
        background: #f1f1f1 !important;
        border-radius: 10px !important;
    }

    .settings-content::-webkit-scrollbar-thumb {
        background: #c91818 !important;
        border-radius: 10px !important;
    }

    .settings-content::-webkit-scrollbar-thumb:hover {
        background: #a81414 !important;
    }

    .settings-container {
        max-width: 1100px !important;
        margin: 0 auto !important;
        width: 100% !important;
    }

    .settings-header {
        margin-bottom: 28px !important;
    }

    .settings-header h1 {
        font-size: 24px !important;
        font-weight: 700 !important;
        color: #0d121c !important;
        margin-bottom: 6px !important;
    }

    .settings-header h1 i {
        color: #c91818 !important;
        margin-right: 10px !important;
    }

    .settings-header p {
        font-size: 14px !important;
        color: #6b7280 !important;
    }

    .settings-tabs {
        display: flex !important;
        gap: 4px !important;
        margin-bottom: 28px !important;
        border-bottom: 2px solid #eef2f6 !important;
        flex-wrap: wrap !important;
    }

    .settings-tab {
        padding: 10px 22px !important;
        font-size: 14px !important;
        font-weight: 500 !important;
        color: #6b7280 !important;
        cursor: pointer !important;
        border: none !important;
        background: none !important;
        border-bottom: 3px solid transparent !important;
        transition: all 0.3s ease !important;
        border-radius: 8px 8px 0 0 !important;
        margin-bottom: -2px !important;
    }

    .settings-tab:hover {
        color: #c91818 !important;
        background: #fef2f2 !important;
    }

    .settings-tab.active {
        color: #c91818 !important;
        border-bottom-color: #c91818 !important;
        background: #fef2f2 !important;
    }

    .settings-tab i {
        margin-right: 8px !important;
    }

    .tab-content {
        display: none !important;
        animation: fadeIn 0.3s ease !important;
    }

    .tab-content.active {
        display: block !important;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .settings-card {
        background: white !important;
        border-radius: 16px !important;
        padding: 24px !important;
        margin-bottom: 24px !important;
        border: 1px solid #eef2f6 !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04) !important;
    }

    .settings-card h3 {
        font-size: 18px !important;
        font-weight: 600 !important;
        color: #0d121c !important;
        margin-bottom: 6px !important;
    }

    .settings-card .subtitle {
        font-size: 13px !important;
        color: #6b7280 !important;
        margin-bottom: 20px !important;
    }

    .settings-card .divider {
        border: none !important;
        border-top: 1px solid #eef2f6 !important;
        margin: 20px 0 !important;
    }

    .form-group {
        margin-bottom: 18px !important;
    }

    .form-group label {
        display: block !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        color: #374151 !important;
        margin-bottom: 6px !important;
    }

    .form-group input,
    .form-group select {
        width: 100% !important;
        padding: 10px 14px !important;
        border: 1.5px solid #d1d5db !important;
        border-radius: 10px !important;
        font-size: 13px !important;
        transition: all 0.2s !important;
        background: #fafbfc !important;
        font-family: inherit !important;
        color: #374151 !important;
    }

    .form-group input:focus,
    .form-group select:focus {
        border-color: #c91818 !important;
        outline: none !important;
        box-shadow: 0 0 0 3px rgba(201, 24, 24, 0.1) !important;
        background: white !important;
    }

    .form-row {
        display: grid !important;
        grid-template-columns: 1fr 1fr !important;
        gap: 20px !important;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr !important;
        }
    }

    .toggle-switch {
        position: relative !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 12px !important;
        cursor: pointer !important;
    }

    .toggle-switch input {
        display: none !important;
    }

    .toggle-slider {
        width: 48px !important;
        height: 26px !important;
        background: #d1d5db !important;
        border-radius: 30px !important;
        transition: all 0.3s !important;
        position: relative !important;
        flex-shrink: 0 !important;
    }

    .toggle-slider::after {
        content: '' !important;
        position: absolute !important;
        width: 20px !important;
        height: 20px !important;
        background: white !important;
        border-radius: 50% !important;
        top: 3px !important;
        left: 3px !important;
        transition: all 0.3s !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2) !important;
    }

    .toggle-switch input:checked + .toggle-slider {
        background: #c91818 !important;
    }

    .toggle-switch input:checked + .toggle-slider::after {
        transform: translateX(22px) !important;
    }

    .toggle-label {
        font-size: 13px !important;
        color: #374151 !important;
    }

    .settings-actions {
        display: flex !important;
        gap: 12px !important;
        margin-top: 24px !important;
        flex-wrap: wrap !important;
    }

    .btn-primary {
        padding: 10px 28px !important;
        background: #c91818 !important;
        color: white !important;
        border: none !important;
        border-radius: 10px !important;
        font-size: 14px !important;
        font-weight: 600 !important;
        cursor: pointer !important;
        transition: all 0.2s !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
    }

    .btn-primary:hover {
        background: #a81414 !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 12px rgba(201, 24, 24, 0.3) !important;
    }

    .btn-secondary {
        padding: 10px 28px !important;
        background: #f3f4f6 !important;
        color: #374151 !important;
        border: 1px solid #d1d5db !important;
        border-radius: 10px !important;
        font-size: 14px !important;
        font-weight: 500 !important;
        cursor: pointer !important;
        transition: all 0.2s !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
    }

    .btn-secondary:hover {
        background: #e5e7eb !important;
        transform: translateY(-2px) !important;
    }

    .btn-danger {
        padding: 10px 28px !important;
        background: #ef4444 !important;
        color: white !important;
        border: none !important;
        border-radius: 10px !important;
        font-size: 14px !important;
        font-weight: 600 !important;
        cursor: pointer !important;
        transition: all 0.2s !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
    }

    .btn-danger:hover {
        background: #dc2626 !important;
        transform: translateY(-2px) !important;
    }

    .status-badge {
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
        padding: 4px 12px !important;
        border-radius: 20px !important;
        font-size: 12px !important;
        font-weight: 500 !important;
    }

    .status-badge.success {
        background: #dcfce7 !important;
        color: #15803d !important;
    }

    .status-badge.error {
        background: #fee2e2 !important;
        color: #dc2626 !important;
    }

    .session-item {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        padding: 14px 0 !important;
        border-bottom: 1px solid #f0f0f0 !important;
        flex-wrap: wrap !important;
        gap: 12px !important;
    }

    .session-item:last-child {
        border-bottom: none !important;
    }

    .session-info {
        display: flex !important;
        flex-direction: column !important;
        gap: 4px !important;
    }

    .session-location {
        font-weight: 600 !important;
        color: #0d121c !important;
        font-size: 14px !important;
    }

    .session-device {
        font-size: 12px !important;
        color: #6b7280 !important;
    }

    .session-time {
        font-size: 12px !important;
        color: #9ca3af !important;
    }

    .session-status {
        font-size: 12px !important;
        font-weight: 500 !important;
        color: #10b981 !important;
    }

    .session-status.inactive {
        color: #9ca3af !important;
    }

    .btn-sm-danger {
        padding: 4px 14px !important;
        font-size: 12px !important;
        border-radius: 6px !important;
        border: none !important;
        cursor: pointer !important;
        background: #fee2e2 !important;
        color: #dc2626 !important;
        transition: all 0.2s !important;
    }

    .btn-sm-danger:hover {
        background: #fecaca !important;
        transform: translateY(-1px) !important;
    }

    .logs-table {
        width: 100% !important;
        border-collapse: collapse !important;
        font-size: 13px !important;
    }

    .logs-table th {
        text-align: left !important;
        padding: 10px 12px !important;
        background: #f8fafc !important;
        color: #6b7280 !important;
        font-weight: 600 !important;
        font-size: 11px !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        border-bottom: 2px solid #eef2f6 !important;
    }

    .logs-table td {
        padding: 10px 12px !important;
        border-bottom: 1px solid #f0f0f0 !important;
        color: #374151 !important;
    }

    .logs-table tr:hover td {
        background: #fafbfc !important;
    }

    .logs-table .status-success {
        color: #10b981 !important;
        font-weight: 500 !important;
    }

    .logs-table .status-failed {
        color: #ef4444 !important;
        font-weight: 500 !important;
    }

    .checkbox-group {
        display: flex !important;
        flex-direction: column !important;
        gap: 12px !important;
    }

    .checkbox-item {
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
        cursor: pointer !important;
        padding: 6px 0 !important;
    }

    .checkbox-item input[type="checkbox"] {
        width: 18px !important;
        height: 18px !important;
        accent-color: #c91818 !important;
        cursor: pointer !important;
        flex-shrink: 0 !important;
    }

    .checkbox-item label {
        font-size: 13px !important;
        color: #374151 !important;
        cursor: pointer !important;
        margin: 0 !important;
    }

    .security-level {
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
        padding: 6px 16px !important;
        border-radius: 20px !important;
        font-size: 13px !important;
        font-weight: 600 !important;
    }

    .security-level.high {
        background: #dcfce7 !important;
        color: #15803d !important;
    }

    .security-level.medium {
        background: #fef3c7 !important;
        color: #d97706 !important;
    }

    .security-level.low {
        background: #fee2e2 !important;
        color: #dc2626 !important;
    }

    .security-level i {
        font-size: 16px !important;
    }

    .toast-notification {
        position: fixed !important;
        bottom: 30px !important;
        right: 30px !important;
        background: #1f2937 !important;
        color: white !important;
        padding: 12px 20px !important;
        border-radius: 40px !important;
        z-index: 9999 !important;
        animation: slideIn 0.3s ease !important;
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1) !important;
        font-size: 13px !important;
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
    }

    .toast-notification.success {
        background: #10b981 !important;
    }

    .toast-notification.error {
        background: #ef4444 !important;
    }

    .toast-notification.warning {
        background: #f59e0b !important;
    }

    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    @media (max-width: 768px) {
        .main-header {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 12px !important;
        }
        .header-actions {
            width: 100% !important;
            flex-wrap: wrap !important;
        }
        .search-wrapper {
            width: 100% !important;
        }
        .settings-tabs {
            gap: 2px !important;
        }
        .settings-tab {
            padding: 8px 14px !important;
            font-size: 12px !important;
        }
        .settings-card {
            padding: 16px !important;
        }
        .session-item {
            flex-direction: column !important;
            align-items: flex-start !important;
        }
        .logs-table {
            font-size: 11px !important;
        }
        .logs-table th,
        .logs-table td {
            padding: 6px 8px !important;
        }
        .main-content {
            padding: 16px !important;
            width: 100% !important;
            max-width: 100% !important;
        }
    }
</style>
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
document.addEventListener('DOMContentLoaded', function() {
    // Tabs
    const tabs = document.querySelectorAll('.settings-tab');
    const contents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const target = this.dataset.tab;
            
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            contents.forEach(c => c.classList.remove('active'));
            document.getElementById('tab-' + target).classList.add('active');
        });
    });

    // Form submission
    const forms = document.querySelectorAll('.settings-form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
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

            this.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                const name = cb.name.replace('settings[', '').replace(']', '');
                settings[name] = cb.checked ? 'true' : 'false';
            });

            saveSettings(group, settings);
        });
    });

    // Settings Dropdown
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

    // Zoom
    let zoom = 100;
    const zoomSpan = document.getElementById('zoomValue');
    const mainDiv = document.getElementById('mainContent');
    const zoomIn = document.getElementById('zoomIn');
    const zoomOut = document.getElementById('zoomOut');

    function updateZoom(value) {
        zoom = Math.min(Math.max(value, 70), 150);
        zoomSpan.innerText = zoom + '%';
        mainDiv.style.transform = 'scale(' + (zoom/100) + ')';
        mainDiv.style.transformOrigin = 'top left';
        mainDiv.style.width = (100 / (zoom/100)) + '%';
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

    // Theme
    document.querySelectorAll('[data-theme]').forEach(function(opt) {
        opt.addEventListener('click', function() {
            const theme = this.dataset.theme;
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
            dropdown.classList.remove('show');
        });
    });

    // ========================================
    // SEARCH - FONCTIONNE CORRECTEMENT
    // ========================================
    const searchInput = document.getElementById('globalSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
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
});

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

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = 'toast-notification ' + type;
    toast.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i> ${message}`;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

// ========================================
// APPLIQUER LES PARAMÈTRES CHARGÉS
// ========================================
function applySettings() {
    fetch('/settings/get')
        .then(response => response.json())
        .then(data => {
            if (data.theme === 'dark') {
                document.getElementById('mainContent').style.backgroundColor = '#1e293b';
                document.getElementById('mainContent').style.color = '#e2e8f0';
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
                document.getElementById('mainContent').style.backgroundColor = '#fff';
                document.getElementById('mainContent').style.color = '#000';
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

// Appeler applySettings au chargement
document.addEventListener('DOMContentLoaded', function() {
    applySettings();
});
</script>
@endsection