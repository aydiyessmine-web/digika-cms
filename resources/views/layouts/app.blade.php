<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Digika CMS - @yield('title', 'Dashboard')</title>
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #f5f7fc;
            overflow-x: hidden;
        }
        
        .app-wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .sidebar {
            width: 280px;
            background: white;
            border-right: 1px solid #e9eef3;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
        }
        
        .sidebar-header {
            padding: 24px;
            border-bottom: 1px solid #e9eef3;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .logo-icon {
            background: #3b82f6;
            width: 42px;
            height: 42px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        .logo-text h2 {
            font-size: 1.4rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }
        
        .logo-text p {
            font-size: 0.7rem;
            color: #3b82f6;
            font-weight: 500;
            margin: 0;
        }
        
        /* Navigation */
        .nav {
            flex: 1;
            padding: 24px;
        }
        
        .nav-section {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            padding: 16px 12px 8px 12px;
        }
        
        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 12px;
            color: #5b6e8c;
            text-decoration: none;
            transition: all 0.2s;
            margin-bottom: 4px;
        }
        
        .nav-item:hover {
            background: #f8fafc;
            color: #1e293b;
        }
        
        .nav-item.active {
            background: #eff6ff;
            color: #3b82f6;
        }
        
        .nav-item i {
            font-size: 1.2rem;
            width: 24px;
        }
        
        .nav-badge {
            background: #ef4444;
            color: white;
            font-size: 0.6rem;
            padding: 2px 8px;
            border-radius: 20px;
            margin-left: auto;
        }
        
        /* Sidebar Bottom - User Card */
        .sidebar-bottom {
            padding: 20px 24px;
            border-top: 1px solid #e9eef3;
        }
        
        .user-card {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            background: #3b82f6;
            border-radius: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
        
        .user-info {
            flex: 1;
        }
        
        .user-name {
            font-weight: 600;
            font-size: 0.85rem;
            color: #0f172a;
        }
        
        .user-role {
            font-size: 0.7rem;
            color: #5b6e8c;
        }
        
        .logout-btn {
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: 0.2s;
        }
        
        .logout-btn:hover {
            background: #fef2f2;
            color: #ef4444;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 24px 32px;
            min-height: 100vh;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
<div class="app-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <div class="logo-icon">D</div>
                <div class="logo-text">
                    <h2>digika</h2>
                    <p>CMS</p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="nav flex-column">
            <div class="nav-section">Principal</div>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>
            <a href="{{ route('media.index') }}" class="nav-item {{ request()->routeIs('media.*') ? 'active' : '' }}">
                <i class="bi bi-images"></i> Médiathèque
            </a>
            <a href="{{ route('campaigns.index') }}" class="nav-item {{ request()->routeIs('campaigns.*') ? 'active' : '' }}">
                <i class="bi bi-megaphone-fill"></i> Campagnes
            </a>

            <div class="nav-section">Contenu</div>
            <a href="{{ route('categories.index') }}" class="nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                <i class="bi bi-tag-fill"></i> Catégories
            </a>
            <a href="{{ route('screens.index') }}" class="nav-item {{ request()->routeIs('screens.*') ? 'active' : '' }}">
                <i class="bi bi-display-fill"></i> Écrans
            </a>

            <div class="nav-section">Intelligence Artificielle</div>
            <a href="{{ route('ai.index') }}" class="nav-item {{ request()->routeIs('ai.index') ? 'active' : '' }}">
                <i class="bi bi-stars"></i> Assistant IA
                <span class="nav-badge">NEW</span>
            </a>

            <!-- Section Administration - Visible uniquement si utilisateur connecté ET admin -->
            @auth
                @if(auth()->user()->isAdmin())
                    <div class="nav-section">Administration</div>
                    <a href="{{ route('settings.index') }}" class="nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                        <i class="bi bi-gear-fill"></i> Paramètres
                    </a>
                    <a href="{{ route('profiles.index') }}" class="nav-item {{ request()->routeIs('profiles.*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i> Profils
                    </a>
                @endif
            @endauth
        </nav>

        <!-- User Card - Visible uniquement si utilisateur connecté -->
        @auth
            <div class="sidebar-bottom">
                <div class="user-card">
                    <div class="user-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
                    <div class="user-info">
                        <div class="user-name">{{ auth()->user()->name }}</div>
                        <div class="user-role">
                            @if(auth()->user()->isAdmin()) Administrateur
                            @elseif(auth()->user()->isMarketing()) Marketing
                            @else Visualisateur
                            @endif
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="logout-btn" title="Déconnexion">
                            <i class="bi bi-box-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>
        @endauth
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>