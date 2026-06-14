<aside class="sidebar">
  <div class="sidebar-top">
    <div class="logo">
      <img src="{{ asset('images/logo.png') }}" alt="Digika CMS" onerror="this.src='https://placehold.co/120x38/ffffff/c91818?text=DigikaCMS'">
    </div>
    <nav class="nav-menu">
      <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="9"></rect><rect x="14" y="3" width="7" height="5"></rect><rect x="14" y="12" width="7" height="9"></rect><rect x="3" y="16" width="7" height="5"></rect></svg>
        <span>Dashboard</span>
      </a>
      <a href="{{ route('media.index') }}" class="nav-item {{ request()->routeIs('media.*') ? 'active' : '' }}">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path><line x1="12" y1="11" x2="12" y2="17"></line><line x1="9" y1="14" x2="15" y2="14"></line></svg>
        <span>Médiathèque</span>
      </a>
      <a href="{{ route('campaigns.index') }}" class="nav-item {{ request()->routeIs('campaigns.*') ? 'active' : '' }}">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19l7-2V7l-7-2v14z"></path><path d="M12 5L3 9v6l9 4z"></path><line x1="23" y1="12" x2="19" y2="12"></line></svg>
        <span>Campagnes</span>
      </a>
      <div class="nav-section-title">INTELLIGENCE IA</div>
      <a href="#" class="nav-item" onclick="showToast('Assistant IA bientôt disponible', 'info'); return false;">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="10" r="3"></circle><path d="M12 2a8 8 0 0 0-8 8c0 4 3 6 8 10 5-4 8-6 8-10a8 8 0 0 0-8-8z"></path></svg>
        <span>Assistant IA</span>
      </a>
    </nav>
  </div>
  <div class="sidebar-bottom">
    <div class="user-profile">
      <div class="user-avatar">{{ auth()->user() ? substr(auth()->user()->name, 0, 1) : 'U' }}</div>
      <div class="user-info">
        <div class="user-name">{{ auth()->user() ? auth()->user()->name : 'Utilisateur' }}</div>
        <div class="user-role">
          @if(auth()->user())
            @if(auth()->user()->role == 'admin') Administrateur
            @elseif(auth()->user()->role == 'marketing') Marketing Manager
            @elseif(auth()->user()->role == 'viewer') Visualisateur
            @else {{ ucfirst(auth()->user()->role) }}
            @endif
          @else Invité
          @endif
        </div>
      </div>
    </div>
    <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="logout-btn"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg><span>Logout</span></button></form>
  </div>
</aside>