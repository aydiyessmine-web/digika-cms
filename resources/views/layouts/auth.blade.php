<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DigiCMS — @yield('title', 'Authentification')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --red:     #e8272a;
            --red-dk:  #c01f22;
            --navy:    #0d1117;
            --navy2:   #111923;
            --navy3:   #162030;
            --card-bg: #ffffff;
            --text:    #1a2332;
            --sub:     #6b7c8d;
            --border:  #e4e8ee;
            --inp-bg:  #f7f9fb;
        }

        html, body { height: 100%; font-family: 'DM Sans', sans-serif; }

        body {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 100vh;
            background: var(--navy);
        }

        /* ── LEFT PANEL ── */
        .auth-left {
            background: var(--navy);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 44px 52px;
            position: relative;
            overflow: hidden;
        }

        /* geometric deco */
        .auth-left::before {
            content: '';
            position: absolute;
            width: 420px; height: 420px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(232,39,42,0.18) 0%, transparent 70%);
            top: -80px; left: -80px;
            pointer-events: none;
        }
        .auth-left::after {
            content: '';
            position: absolute;
            width: 260px; height: 260px;
            border: 1px solid rgba(232,39,42,0.15);
            border-radius: 50%;
            bottom: 80px; right: -60px;
            pointer-events: none;
        }

        .left-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 1;
        }
        .left-logo img {
            height: 40px;
            width: auto;
            object-fit: contain;
        }
        /* Fallback si pas d'image */
        .logo-fallback {
            display: flex;
            align-items: center;
            gap: 0;
        }
        .logo-fallback .logo-digi {
            font-family: 'Syne', sans-serif;
            font-size: 28px;
            font-weight: 800;
            color: var(--red);
            letter-spacing: -1px;
        }
        .logo-fallback .logo-cms {
            font-family: 'Syne', sans-serif;
            font-size: 14px;
            font-weight: 700;
            color: rgba(255,255,255,0.5);
            margin-left: 2px;
            margin-top: 10px;
            letter-spacing: 1px;
        }

        .left-hero { z-index: 1; }
        .left-hero .tag {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(232,39,42,0.12);
            border: 1px solid rgba(232,39,42,0.25);
            color: #ff8a8b;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            padding: 5px 13px;
            border-radius: 20px;
            margin-bottom: 20px;
        }
        .left-hero h1 {
            font-family: 'Syne', sans-serif;
            font-size: clamp(28px, 3vw, 40px);
            font-weight: 800;
            color: #fff;
            line-height: 1.2;
            margin-bottom: 16px;
        }
        .left-hero h1 span { color: var(--red); }
        .left-hero p {
            font-size: 14.5px;
            color: rgba(255,255,255,0.45);
            line-height: 1.65;
            max-width: 340px;
        }

        /* feature pills */
        .feat-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 28px;
        }
        .feat-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255,255,255,0.6);
            font-size: 13px;
        }
        .feat-item i {
            width: 28px; height: 28px;
            background: rgba(232,39,42,0.15);
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--red);
            font-size: 13px;
            flex-shrink: 0;
        }

        .left-footer {
            font-size: 11.5px;
            color: rgba(255,255,255,0.2);
            z-index: 1;
        }

        /* ── RIGHT PANEL ── */
        .auth-right {
            background: var(--card-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 48px;
        }

        .auth-box {
            width: 100%;
            max-width: 400px;
        }

        .form-title {
            font-family: 'Syne', sans-serif;
            font-size: 26px;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 6px;
        }
        .form-sub {
            font-size: 13.5px;
            color: var(--sub);
            margin-bottom: 30px;
        }

        /* form elements */
        .fg { margin-bottom: 18px; }
        .flbl {
            display: block;
            font-size: 12.5px;
            font-weight: 600;
            color: #374455;
            margin-bottom: 7px;
            letter-spacing: 0.2px;
        }
        .inp-wrap {
            position: relative;
        }
        .inp-wrap > i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9baab8;
            font-size: 15px;
            pointer-events: none;
        }
        .finp {
            width: 100%;
            padding: 11px 14px 11px 40px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            background: var(--inp-bg);
            font-family: 'DM Sans', sans-serif;
            font-size: 13.5px;
            color: var(--text);
            outline: none;
            transition: border-color 0.18s, box-shadow 0.18s;
        }
        .finp:focus {
            border-color: var(--red);
            box-shadow: 0 0 0 3px rgba(232,39,42,0.08);
            background: #fff;
        }
        .finp::placeholder { color: #b0bcc8; }

        .err-msg {
            font-size: 11.5px;
            color: var(--red);
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .alert-form-err {
            background: #fff5f5;
            border: 1px solid #fecdcd;
            border-left: 3px solid var(--red);
            color: #c0392b;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background: var(--red);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: 'Syne', sans-serif;
            font-size: 14.5px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 0.18s, transform 0.1s;
            margin-top: 6px;
        }
        .btn-submit:hover { background: var(--red-dk); transform: translateY(-1px); }
        .btn-submit:active { transform: translateY(0); }

        .auth-footer {
            text-align: center;
            margin-top: 22px;
            font-size: 13px;
            color: var(--sub);
        }
        .auth-footer a {
            color: var(--red);
            font-weight: 600;
            text-decoration: none;
        }
        .auth-footer a:hover { text-decoration: underline; }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            body { grid-template-columns: 1fr; }
            .auth-left { display: none; }
            .auth-right { padding: 32px 24px; }
        }
    </style>
</head>
<body>

<!-- LEFT PANEL -->
<div class="auth-left">
    <!-- Logo -->
    <div class="left-logo">
        @if(file_exists(public_path('images/logo.png')))
            <img src="{{ asset('images/logo.png') }}" alt="Digika Logo">
            <span style="font-family:'Syne',sans-serif;font-size:13px;font-weight:700;color:rgba(255,255,255,0.4);margin-left:4px;letter-spacing:1px;">CMS</span>
        @else
            <div class="logo-fallback">
                <span class="logo-digi">digi</span>
                <span class="logo-digi" style="color:#fff;">ka</span>
                <span class="logo-cms">CMS</span>
            </div>
        @endif
    </div>

    <!-- Hero text -->
    <div class="left-hero">
        <div class="tag"><i class="bi bi-stars"></i> Propulsé par l'IA</div>
        <h1>Gérez votre affichage<br><span>dynamique</span> avec l'IA</h1>
        <p>Créez, organisez et optimisez vos campagnes marketing grâce à l'intelligence artificielle intégrée.</p>

        <div class="feat-list">
            <div class="feat-item">
                <i class="bi bi-collection-play-fill"></i>
                Bibliothèque multimédia centralisée
            </div>
            <div class="feat-item">
                <i class="bi bi-megaphone-fill"></i>
                Gestion de campagnes simplifiée
            </div>
            <div class="feat-item">
                <i class="bi bi-cpu-fill"></i>
                Génération de contenu par IA
            </div>
            <div class="feat-item">
                <i class="bi bi-display-fill"></i>
                Multi-formats d'écrans supportés
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="left-footer">© {{ date('Y') }} Digika. Tous droits réservés.</div>
</div>

<!-- RIGHT PANEL -->
<div class="auth-right">
    <div class="auth-box">
        @yield('content')
    </div>
</div>

</body>
</html>