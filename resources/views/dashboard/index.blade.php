@extends('layouts.app')

@section('title', 'Tableau de bord - DigiCMS')

@push('styles')
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- STATISTICS PANEL -->
<section class="stats-panel">
  <div class="stats-header"><span class="stats-subtitle">Tableau de bord</span><h1 class="stats-title">Statistiques</h1></div>
  <div class="stats-cards">
    <div class="stat-card">
      <div class="card-header"><div class="indicator blue"><i class="fas fa-images"></i></div><span class="badge blue">+{{ $newMediaThisMonth ?? 0 }} ce mois</span></div>
      <div class="card-number">{{ $totalMedia ?? 0 }}</div>
      <div class="card-label">Médias en bibliothèque</div>
      <div class="card-trend green"><i class="fas fa-arrow-up"></i> {{ $mediaGrowth ?? 0 }}% vs mois dernier</div>
    </div>
    <div class="stat-card">
      <div class="card-header"><div class="indicator red"><i class="fas fa-bullhorn"></i></div><span class="badge red">{{ $activeCampaigns ?? 0 }} actives</span></div>
      <div class="card-number">{{ $totalCampaigns ?? 0 }}</div>
      <div class="card-label">Campagnes créées</div>
      <div class="card-trend green"><i class="fas fa-plus"></i> {{ $newCampaignsThisWeek ?? 0 }} nouvelles cette semaine</div>
    </div>
    <div class="stat-card">
      <div class="card-header"><div class="indicator purple"><i class="fas fa-robot"></i></div><span class="badge purple">Aujourd'hui</span></div>
      <div class="card-number">{{ $iaRequestsToday ?? 0 }}</div>
      <div class="card-label">Requêtes IA utilisées</div>
      <div class="card-trend red"><i class="fas fa-chart-line"></i> {{ $iaGrowth ?? 0 }}% vs hier</div>
    </div>
  </div>
</section>

<!-- MAIN CONTENT -->
<main class="main-content" id="mainContent">
  <div class="main-header">
    <div class="search-wrapper"><input type="text" id="globalSearch" placeholder="Rechercher (médias, campagnes)..."><i class="fas fa-search search-icon"></i></div>
    <div class="settings-container">
      <button class="settings-btn" id="settingsDropdownBtn"><i class="fas fa-sliders-h"></i></button>
      <div class="settings-dropdown" id="settingsDropdown">
        <div class="dropdown-section"><div class="dropdown-title"><i class="fas fa-palette"></i> Thème</div><div class="dropdown-option" data-theme="light"><i class="fas fa-sun"></i> Clair</div><div class="dropdown-option" data-theme="dark"><i class="fas fa-moon"></i> Sombre</div></div>
        <div class="dropdown-section"><div class="dropdown-title"><i class="fas fa-search-plus"></i> Zoom</div><div class="zoom-control"><button class="zoom-btn" id="zoomOut">A-</button><span class="zoom-value" id="zoomValue">100%</span><button class="zoom-btn" id="zoomIn">A+</button></div></div>
      </div>
    </div>
  </div>

  <!-- Médiathèque récente -->
  <div class="media-widget">
    <div class="section-header"><h2 class="section-title">Médiathèque récente</h2><a href="{{ route('media.index') }}" class="section-link">Voir tout <i class="fas fa-arrow-right"></i></a></div>
    <div class="media-grid" id="mediaGrid">
      @forelse(($recentMedia ?? []) as $media)
      <div class="media-card" data-media-id="{{ $media->id }}" data-media-type="{{ $media->type }}" data-media-url="{{ asset('storage/' . $media->file_path) }}" data-media-title="{{ $media->title }}" data-search="{{ strtolower($media->title) }} {{ strtolower($media->type) }}">
        <div class="media-thumb" style="background-image: url('{{ $media->type === 'image' ? asset('storage/' . $media->file_path) : '' }}'); background-size: cover;">
          @if($media->type !== 'image')
          <div style="display:flex;align-items:center;justify-content:center;height:100%;background:#f3f4f6;"><i class="fas {{ $media->type === 'video' ? 'fa-video' : ($media->type === 'pdf' ? 'fa-file-pdf' : 'fa-file-alt') }}" style="font-size:32px;color:#9ca3af;"></i></div>
          @endif
          <span class="type-badge {{ $media->type }}">{{ strtoupper(substr($media->type, 0, 4)) }}</span>
          <div class="media-zoom-btn" onclick="event.stopPropagation(); previewMedia('{{ asset('storage/' . $media->file_path) }}', '{{ $media->type }}')"><i class="fas fa-search-plus"></i></div>
        </div>
        <div class="media-info">
          <div class="media-title">{{ $media->title }}</div>
          <div class="media-meta"><span>{{ $media->type }}</span><span>{{ round(($media->size ?? 0) / 1024 / 1024, 1) }} MB</span></div>
        </div>
      </div>
      @empty
      <div class="media-card"><div class="media-info"><div class="media-title">Aucun média</div></div></div>
      @endforelse
    </div>
  </div>

  <!-- Campagnes actives -->
  <div class="campaigns-widget">
    <div class="section-header"><h2 class="section-title">Campagnes actives</h2><a href="{{ route('campaigns.index') }}" class="section-link">Voir tout <i class="fas fa-arrow-right"></i></a></div>
    <div class="campaign-list" id="campaignList">
      @forelse(($activeCampaignsList ?? []) as $campaign)
      <div class="campaign-item" data-campaign-id="{{ $campaign->id }}" data-search="{{ strtolower($campaign->title) }} {{ strtolower($campaign->description ?? '') }}">
        <div class="campaign-info">
          <div class="campaign-name">{{ $campaign->title }}</div>
          <div class="campaign-detail">
            <span><i class="fas fa-images"></i> {{ $campaign->media_count ?? 0 }} médias</span>
            <span><i class="fas fa-calendar-alt"></i> Du {{ \Carbon\Carbon::parse($campaign->start_date)->translatedFormat('d M Y') ?? '—' }}</span>
            <span><i class="fas fa-calendar-check"></i> Au {{ \Carbon\Carbon::parse($campaign->end_date)->translatedFormat('d M Y') ?? '—' }}</span>
          </div>
        </div>
        <div class="campaign-progress">
          <div class="progress-bar-container"><div class="progress-bar {{ $loop->index % 3 == 0 ? 'red' : ($loop->index % 3 == 1 ? 'blue' : 'green') }}" style="width: {{ $campaign->progress ?? 0 }}%;"></div></div>
          <span class="progress-percent">{{ $campaign->progress ?? 0 }}%</span>
        </div>
      </div>
      @empty
      <div class="campaign-item"><div class="campaign-info"><div class="campaign-name">Aucune campagne active</div></div></div>
      @endforelse
    </div>
  </div>

  <!-- Assistant IA -->
  <div class="ai-assistant-widget">
    <div class="ai-header"><h2 class="ai-title">Assistant IA - DigiCMS</h2><p class="ai-subtitle">Générez, optimisez et évaluez vos contenus marketing en quelques secondes</p></div>
    <div class="ai-pills">
      <button class="ai-pill" onclick="document.getElementById('aiPrompt').value='Génère un slogan percutant pour notre nouvelle campagne'"><i class="fas fa-magic"></i> Générer un slogan</button>
      <button class="ai-pill" onclick="document.getElementById('aiPrompt').value='Propose 10 mots-clés stratégiques pour un affichage dynamique'"><i class="fas fa-key"></i> Proposer des mots-clés</button>
      <button class="ai-pill" onclick="document.getElementById('aiPrompt').value='Évalue la qualité de ce message marketing'"><i class="fas fa-star"></i> Évaluer mon message</button>
      <button class="ai-pill" onclick="document.getElementById('aiPrompt').value='Donne-moi une idée créative de visuel pour'"><i class="fas fa-lightbulb"></i> Idée de visuel</button>
      <button class="ai-pill" onclick="document.getElementById('aiPrompt').value='Rédige une description attrayante pour'"><i class="fas fa-pen-fancy"></i> Description d'affichage</button>
    </div>
    <div class="ai-input-wrapper">
      <input type="text" id="aiPrompt" placeholder="Ex: Génère un slogan pour une promo été sur écran extérieur...">
      <button class="ai-generate-btn" onclick="showToast('Demande envoyée à l\'IA', 'success')">Générer <i class="fas fa-paper-plane"></i></button>
    </div>
  </div>
</main>

<!-- Modal Preview -->
<div id="previewModal" class="modal">
  <div class="modal-content">
    <div id="previewContent"></div>
    <button class="modal-close" onclick="closePreviewModal()">Fermer</button>
  </div>
</div>
@endsection