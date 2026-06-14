<div class="main-header">
  <div class="header-page-title">@yield('header-title')</div>
  <div class="header-actions">
    <div class="search-wrapper">
      <input type="text" id="globalSearch" placeholder="Rechercher...">
      <i class="fas fa-search search-icon"></i>
    </div>
    <div class="settings-container">
      <button class="settings-btn" id="settingsDropdownBtn"><i class="fas fa-sliders-h"></i></button>
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