@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<style>
/* ── PAGE HEADER ── */
.pg-header { margin-bottom: 26px; }
.pg-header .red-bar { width: 36px; height: 3.5px; background: #e8272a; border-radius: 2px; margin-bottom: 12px; }
.pg-header h2 { font-family:'Syne',sans-serif; font-size:22px; font-weight:800; color:#1a2332; margin:0 0 4px; }
.pg-header p  { font-size:13.5px; color:#8fa3b8; margin:0; }

/* ── STAT GRID ── */
.stats-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:24px; }

/* ── MAIN GRID ── */
.main-grid { display:grid; grid-template-columns:1fr 320px; gap:18px; }

/* ── MEDIA GRID ── */
.media-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; }
.media-thumb {
    border-radius:10px; overflow:hidden;
    background:#f3f5f8; aspect-ratio:16/9; position:relative;
    border:1px solid #e4e8ee;
}
.media-thumb img { width:100%; height:100%; object-fit:cover; display:block; }
.media-thumb .media-overlay {
    position:absolute; bottom:0; left:0; right:0;
    background:linear-gradient(transparent,rgba(15,25,35,0.85));
    padding:8px 10px;
    font-size:11px; color:#fff; font-weight:500;
}
.media-thumb .media-type-badge {
    position:absolute; top:7px; right:7px;
    background:rgba(15,25,35,0.7); color:#fff;
    font-size:10px; padding:2px 7px; border-radius:12px;
    backdrop-filter:blur(4px);
}
.media-empty {
    text-align:center; padding:40px 20px; color:#8fa3b8;
    grid-column:1/-1;
}
.media-empty i { font-size:34px; display:block; margin-bottom:10px; color:#d1d9e0; }

/* ── QUICK ACTIONS ── */
.qa-item {
    display:flex; align-items:center; gap:12px;
    padding:12px 13px; border-radius:10px;
    background:#fafbfc; border:1.5px solid #e4e8ee;
    text-decoration:none; color:#1a2332; transition:all 0.18s;
    margin-bottom:8px;
}
.qa-item:hover { border-color:#e8272a; background:#fff0f0; transform:translateX(3px); }
.qa-icon {
    width:38px; height:38px; border-radius:9px;
    display:flex; align-items:center; justify-content:center;
    font-size:17px; flex-shrink:0;
}
.qa-icon.red   { background:#fff0f0; color:#e8272a; }
.qa-icon.blue  { background:#eff6ff; color:#2563eb; }
.qa-icon.purple{ background:#faf5ff; color:#7c3aed; }
.qa-icon.green { background:#f0fdf4; color:#16a34a; }
.qa-label { font-size:13px; font-weight:600; color:#1a2332; line-height:1.2; }
.qa-sub   { font-size:11px; color:#8fa3b8; }

/* ── SCREEN PREVIEW WIDGET ── */
.screen-preview-wrap {
    background:#0f1923; border-radius:12px; padding:18px;
    border:1px solid #1e2730; text-align:center;
}
.screen-mock {
    display:inline-block; border:3px solid #e8272a;
    border-radius:8px; background:#162030;
    display:flex; align-items:center; justify-content:center;
    color:#6b7a8d; font-size:12px; gap:6px;
    margin:12px auto;
}
.screen-mock.landscape { width:160px; height:90px; }
.screen-mock.portrait  { width:90px; height:160px; }
</style>
@endpush

@section('content')

<div class="pg-header">
    <div class="red-bar"></div>
    <h2>Bonjour, {{ auth()->user()->name }} 👋</h2>
    <p>Voici un aperçu de votre activité CMS — {{ now()->format('d F Y') }}</p>
</div>

<!-- STATS -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon-wrap"><i class="bi bi-collection-play-fill"></i></div>
        <div class="stat-num">{{ $mediaCount ?? 0 }}</div>
        <div class="stat-lbl">Médias</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon-wrap"><i class="bi bi-megaphone-fill"></i></div>
        <div class="stat-num">{{ $campaignCount ?? 0 }}</div>
        <div class="stat-lbl">Campagnes</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon-wrap"><i class="bi bi-display-fill"></i></div>
        <div class="stat-num">{{ $screenCount ?? 0 }}</div>
        <div class="stat-lbl">Écrans</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon-wrap"><i class="bi bi-stars"></i></div>
        <div class="stat-num">{{ $aiCount ?? 0 }}</div>
        <div class="stat-lbl">Générations IA</div>
    </div>
</div>

<!-- CONTENT GRID -->
<div class="main-grid">

    <!-- LEFT: recent media -->
    <div class="card-digi">
        <div class="card-header-digi">
            <h3 class="card-title-digi"><i class="bi bi-collection-play-fill me-2" style="color:#e8272a;"></i>Derniers médias</h3>
            <a href="{{ route('media.index') }}" class="card-link">Voir tout <i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="media-grid">
            @forelse($recentMedia ?? [] as $m)
                <div class="media-thumb">
                    @if($m->type === 'image')
                        <img src="{{ asset('storage/'.$m->file_path) }}" alt="{{ $m->title }}">
                    @else
                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#0f1923;">
                            <i class="bi bi-play-circle-fill" style="font-size:28px;color:#e8272a;"></i>
                        </div>
                    @endif
                    <div class="media-overlay">{{ Str::limit($m->title, 22) }}</div>
                    <div class="media-type-badge">{{ strtoupper($m->type) }}</div>
                </div>
            @empty
                <div class="media-empty">
                    <i class="bi bi-images"></i>
                    Aucun média uploadé pour l'instant.
                    <div style="margin-top:14px;">
                        <a href="{{ route('media.create') }}" style="background:#e8272a;color:#fff;padding:9px 18px;border-radius:8px;text-decoration:none;font-size:13px;font-weight:600;font-family:'Syne',sans-serif;">
                            <i class="bi bi-upload"></i> Uploader un média
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- RIGHT: quick actions + screen widget -->
    <div style="display:flex;flex-direction:column;gap:18px;">

        <!-- Quick actions -->
        <div class="card-digi">
            <div class="card-header-digi" style="margin-bottom:14px;">
                <h3 class="card-title-digi">Actions rapides</h3>
            </div>
            <a href="{{ route('media.create') }}" class="qa-item">
                <div class="qa-icon red"><i class="bi bi-upload"></i></div>
                <div><div class="qa-label">Upload média</div><div class="qa-sub">Image ou vidéo</div></div>
            </a>
            <a href="{{ route('campaigns.create') }}" class="qa-item">
                <div class="qa-icon blue"><i class="bi bi-megaphone-fill"></i></div>
                <div><div class="qa-label">Nouvelle campagne</div><div class="qa-sub">Créer &amp; planifier</div></div>
            </a>
            <a href="{{ route('ai.index') }}" class="qa-item">
                <div class="qa-icon purple"><i class="bi bi-stars"></i></div>
                <div><div class="qa-label">Assistant IA</div><div class="qa-sub">Slogans &amp; descriptions</div></div>
            </a>
            <a href="{{ route('screens.create') }}" class="qa-item">
                <div class="qa-icon green"><i class="bi bi-display-fill"></i></div>
                <div><div class="qa-label">Ajouter un écran</div><div class="qa-sub">Format &amp; résolution</div></div>
            </a>
        </div>

        <!-- Screen preview -->
        <div class="screen-preview-wrap">
            <div style="font-family:'Syne',sans-serif;font-size:12px;font-weight:700;color:#fff;margin-bottom:4px;">
                <i class="bi bi-display" style="color:#e8272a;margin-right:5px;"></i>Formats d'écrans
            </div>
            <div style="font-size:11px;color:#6b7a8d;margin-bottom:14px;">Digika supporte tous les formats</div>
            <div style="display:flex;align-items:flex-end;justify-content:center;gap:16px;">
                <div>
                    <div class="screen-mock landscape">
                        <i class="bi bi-aspect-ratio" style="font-size:16px;"></i>
                    </div>
                    <div style="font-size:10px;color:#6b7a8d;text-align:center;">16:9<br>Paysage</div>
                </div>
                <div>
                    <div class="screen-mock portrait">
                        <i class="bi bi-phone" style="font-size:16px;"></i>
                    </div>
                    <div style="font-size:10px;color:#6b7a8d;text-align:center;">9:16<br>Portrait</div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection