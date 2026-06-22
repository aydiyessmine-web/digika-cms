@extends('layouts.app')

@section('title', 'Profils - DigiCMS')

@push('styles')
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<style>
    /* ========================================
       PROFILS PAGE STYLES
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

    /* ========================================
       PROFILES CONTENT
    ======================================== */
    .profiles-content {
        flex: 1 !important;
        overflow-y: auto !important;
        padding-right: 8px !important;
    }

    .profiles-content::-webkit-scrollbar {
        width: 6px !important;
    }

    .profiles-content::-webkit-scrollbar-track {
        background: #f1f1f1 !important;
        border-radius: 10px !important;
    }

    .profiles-content::-webkit-scrollbar-thumb {
        background: #c91818 !important;
        border-radius: 10px !important;
    }

    .profiles-content::-webkit-scrollbar-thumb:hover {
        background: #a81414 !important;
    }

    .profiles-container {
        max-width: 1200px !important;
        margin: 0 auto !important;
        width: 100% !important;
    }

    .profiles-header {
        margin-bottom: 28px !important;
    }

    .profiles-header h1 {
        font-size: 24px !important;
        font-weight: 700 !important;
        color: #0d121c !important;
        margin-bottom: 6px !important;
    }

    .profiles-header h1 i {
        color: #c91818 !important;
        margin-right: 10px !important;
    }

    .profiles-header p {
        font-size: 14px !important;
        color: #6b7280 !important;
    }

    /* Summary Grid */
    .summary-grid {
        display: grid !important;
        grid-template-columns: repeat(4, 1fr) !important;
        gap: 16px !important;
        margin-bottom: 24px !important;
    }

    .summary-card {
        background: white !important;
        border-radius: 12px !important;
        padding: 16px 18px !important;
        border: 1px solid #eef2f6 !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04) !important;
        display: flex !important;
        align-items: center !important;
        gap: 14px !important;
        transition: all 0.2s !important;
    }

    .summary-card:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 20px -8px rgba(0,0,0,0.1) !important;
    }

    .summary-icon {
        width: 42px !important;
        height: 42px !important;
        border-radius: 10px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 18px !important;
        flex-shrink: 0 !important;
    }

    .summary-icon.blue {
        background: #eff6ff !important;
        color: #2563eb !important;
    }

    .summary-icon.green {
        background: #dcfce7 !important;
        color: #16a34a !important;
    }

    .summary-icon.pink {
        background: #fdf2f2 !important;
        color: #ef4444 !important;
    }

    .summary-icon.purple {
        background: #faf5ff !important;
        color: #a855f7 !important;
    }

    .summary-info {
        display: flex !important;
        flex-direction: column !important;
    }

    .summary-value {
        font-size: 22px !important;
        font-weight: 700 !important;
        color: #0d121c !important;
        line-height: 1.2 !important;
    }

    .summary-label {
        font-size: 11px !important;
        color: #6b7280 !important;
        margin-top: 2px !important;
    }

    /* Filters */
    .filters-toolbar {
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
        margin-bottom: 20px !important;
        flex-wrap: wrap !important;
    }

    .filter-search-wrapper {
        flex: 1 !important;
        min-width: 200px !important;
    }

    .filter-search-wrapper input {
        width: 100% !important;
        height: 38px !important;
        padding: 0 16px !important;
        border: 1.5px solid #e5e7eb !important;
        border-radius: 10px !important;
        font-size: 13px !important;
        outline: none !important;
        transition: all 0.2s !important;
        background: #fafbfc !important;
    }

    .filter-search-wrapper input:focus {
        border-color: #c91818 !important;
        box-shadow: 0 0 0 3px rgba(201, 24, 24, 0.1) !important;
    }

    .filter-select-wrapper select {
        height: 38px !important;
        padding: 0 36px 0 14px !important;
        border: 1.5px solid #e5e7eb !important;
        border-radius: 10px !important;
        font-size: 13px !important;
        appearance: none !important;
        background: #fafbfc url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 8L1 3h10z'/%3E%3C/svg%3E") no-repeat right 12px center !important;
        cursor: pointer !important;
        outline: none !important;
        transition: all 0.2s !important;
        min-width: 150px !important;
    }

    .filter-select-wrapper select:focus {
        border-color: #c91818 !important;
        box-shadow: 0 0 0 3px rgba(201, 24, 24, 0.1) !important;
    }

    /* Table */
    .table-wrapper {
        overflow-x: auto !important;
        border-radius: 12px !important;
        border: 1px solid #eef2f6 !important;
        background: white !important;
    }

    .profiles-table {
        width: 100% !important;
        border-collapse: collapse !important;
        font-size: 13px !important;
        min-width: 700px !important;
    }

    .profiles-table th {
        text-align: left !important;
        padding: 14px 16px !important;
        background: #f8fafc !important;
        color: #6b7280 !important;
        font-weight: 600 !important;
        font-size: 11px !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        border-bottom: 2px solid #eef2f6 !important;
    }

    .profiles-table td {
        padding: 12px 16px !important;
        border-bottom: 1px solid #f0f0f0 !important;
        color: #374151 !important;
        vertical-align: middle !important;
    }

    .profiles-table tr:hover td {
        background: #fafbfc !important;
    }

    .profiles-table tr:last-child td {
        border-bottom: none !important;
    }

    .user-name {
        font-weight: 600 !important;
        color: #0d121c !important;
        display: flex !important;
        align-items: center !important;
        gap: 10px !important;
    }

    .user-avatar-sm {
        width: 32px !important;
        height: 32px !important;
        border-radius: 50% !important;
        background: #c91818 !important;
        color: white !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        flex-shrink: 0 !important;
    }

    .user-avatar-sm.green {
        background: #16a34a !important;
    }

    .user-avatar-sm.blue {
        background: #2563eb !important;
    }

    .user-avatar-sm.purple {
        background: #a855f7 !important;
    }

    .user-avatar-sm.orange {
        background: #f59e0b !important;
    }

    .role-badge {
        display: inline-flex !important;
        align-items: center !important;
        gap: 5px !important;
        padding: 3px 10px !important;
        border-radius: 20px !important;
        font-size: 11px !important;
        font-weight: 500 !important;
    }

    .role-badge.admin {
        background: #fef2f2 !important;
        color: #dc2626 !important;
    }

    .role-badge.marketing {
        background: #eff6ff !important;
        color: #2563eb !important;
    }

    .status-badge {
        display: inline-flex !important;
        align-items: center !important;
        gap: 5px !important;
        padding: 3px 10px !important;
        border-radius: 20px !important;
        font-size: 11px !important;
        font-weight: 500 !important;
    }

    .status-badge.active {
        background: #dcfce7 !important;
        color: #16a34a !important;
    }

    .status-badge.inactive {
        background: #fef2f2 !important;
        color: #dc2626 !important;
    }

    .status-badge i {
        font-size: 8px !important;
    }

    .last-activity {
        font-size: 12px !important;
        color: #9ca3af !important;
    }

    .table-actions {
        display: flex !important;
        gap: 6px !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .table-actions .action-btn {
        width: 30px !important;
        height: 30px !important;
        border-radius: 8px !important;
        border: none !important;
        cursor: pointer !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        transition: all 0.2s !important;
        font-size: 13px !important;
    }

    .table-actions .action-btn:hover {
        transform: scale(1.05) !important;
    }

    .table-actions .action-btn.edit {
        background: #f3f4f6 !important;
        color: #374151 !important;
    }

    .table-actions .action-btn.edit:hover {
        background: #e5e7eb !important;
    }

    .table-actions .action-btn.toggle {
        background: #f3f4f6 !important;
        color: #374151 !important;
    }

    .table-actions .action-btn.toggle:hover {
        background: #e5e7eb !important;
    }

    .table-actions .action-btn.toggle.active {
        background: #dcfce7 !important;
        color: #16a34a !important;
    }

    .table-actions .action-btn.toggle.inactive {
        background: #fee2e2 !important;
        color: #dc2626 !important;
    }

    .table-actions .action-btn.delete {
        background: #fee2e2 !important;
        color: #dc2626 !important;
    }

    .table-actions .action-btn.delete:hover {
        background: #fecaca !important;
    }

    /* Toolbar actions */
    .toolbar-actions {
        display: flex !important;
        gap: 10px !important;
        flex-wrap: wrap !important;
    }

    .toolbar-actions .btn-export {
        padding: 8px 18px !important;
        background: #f3f4f6 !important;
        color: #374151 !important;
        border: 1px solid #d1d5db !important;
        border-radius: 10px !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        cursor: pointer !important;
        transition: all 0.2s !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
    }

    .toolbar-actions .btn-export:hover {
        background: #e5e7eb !important;
        transform: translateY(-1px) !important;
    }

    .toolbar-actions .btn-add {
        padding: 8px 20px !important;
        background: #c91818 !important;
        color: white !important;
        border: none !important;
        border-radius: 10px !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        cursor: pointer !important;
        transition: all 0.2s !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
    }

    .toolbar-actions .btn-add:hover {
        background: #a81414 !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 4px 12px rgba(201, 24, 24, 0.3) !important;
    }

    /* Modal */
    .modal {
        display: none !important;
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        height: 100% !important;
        background: rgba(0,0,0,0.5) !important;
        backdrop-filter: blur(4px) !important;
        align-items: center !important;
        justify-content: center !important;
        z-index: 9999 !important;
    }

    .modal.show {
        display: flex !important;
    }

    .modal-content {
        background: white !important;
        border-radius: 20px !important;
        padding: 32px !important;
        max-width: 500px !important;
        width: 90% !important;
        max-height: 90vh !important;
        overflow-y: auto !important;
        box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25) !important;
        animation: modalFadeIn 0.3s ease !important;
    }

    @keyframes modalFadeIn {
        from { opacity: 0; transform: scale(0.95) translateY(20px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }

    .modal-content h3 {
        font-size: 20px !important;
        font-weight: 700 !important;
        color: #0d121c !important;
        margin-bottom: 20px !important;
        display: flex !important;
        align-items: center !important;
        gap: 10px !important;
    }

    .modal-content h3 i {
        color: #c91818 !important;
    }

    .modal-content .form-group {
        margin-bottom: 14px !important;
    }

    .modal-content .form-group label {
        display: block !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        color: #374151 !important;
        margin-bottom: 5px !important;
    }

    .modal-content .form-group input,
    .modal-content .form-group select {
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

    .modal-content .form-group input:focus,
    .modal-content .form-group select:focus {
        border-color: #c91818 !important;
        outline: none !important;
        box-shadow: 0 0 0 3px rgba(201, 24, 24, 0.1) !important;
        background: white !important;
    }

    .modal-content .form-group .checkbox-inline {
        display: flex !important;
        align-items: center !important;
        gap: 10px !important;
    }

    .modal-content .form-group .checkbox-inline input[type="checkbox"] {
        width: 18px !important;
        height: 18px !important;
        accent-color: #c91818 !important;
        cursor: pointer !important;
        flex-shrink: 0 !important;
        margin: 0 !important;
    }

    .modal-content .form-group .checkbox-inline label {
        margin: 0 !important;
        cursor: pointer !important;
        font-size: 13px !important;
        color: #374151 !important;
    }

    .modal-buttons {
        display: flex !important;
        gap: 12px !important;
        justify-content: flex-end !important;
        margin-top: 24px !important;
    }

    .modal-buttons .cancel {
        padding: 10px 24px !important;
        background: #f3f4f6 !important;
        color: #374151 !important;
        border: none !important;
        border-radius: 10px !important;
        font-size: 14px !important;
        font-weight: 500 !important;
        cursor: pointer !important;
        transition: all 0.2s !important;
    }

    .modal-buttons .cancel:hover {
        background: #e5e7eb !important;
    }

    .modal-buttons .submit {
        padding: 10px 24px !important;
        background: #c91818 !important;
        color: white !important;
        border: none !important;
        border-radius: 10px !important;
        font-size: 14px !important;
        font-weight: 600 !important;
        cursor: pointer !important;
        transition: all 0.2s !important;
    }

    .modal-buttons .submit:hover {
        background: #a81414 !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 4px 12px rgba(201, 24, 24, 0.3) !important;
    }

    /* Toast */
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

    /* Responsive */
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
        .summary-grid {
            grid-template-columns: repeat(2, 1fr) !important;
        }
        .main-content {
            padding: 16px !important;
            width: 100% !important;
            max-width: 100% !important;
        }
        .filters-toolbar {
            flex-direction: column !important;
            align-items: stretch !important;
        }
        .filter-search-wrapper {
            min-width: auto !important;
        }
        .filter-select-wrapper select {
            width: 100% !important;
        }
        .toolbar-actions {
            width: 100% !important;
            justify-content: stretch !important;
        }
        .toolbar-actions .btn-export,
        .toolbar-actions .btn-add {
            flex: 1 !important;
            justify-content: center !important;
        }
        .modal-content {
            padding: 24px !important;
            width: 95% !important;
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
                                <span class="status-badge {{ $user->is_active ? 'active' : 'inactive' }}">
                                    <i class="fas fa-circle"></i>
                                    {{ $user->is_active ? 'Actif' : 'Inactif' }}
                                </span>
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