<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DigiCMS Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #2a2a2a 0%, #1a1a1a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container{
            width:1000px;
            max-width:90%;
            height:580px;
            max-height:85vh;
            display:flex;
            overflow:hidden;
            background:white;
            border-radius:28px;
            box-shadow:
            0 25px 50px -12px rgba(0,0,0,.4),
            0 0 0 1px rgba(255,255,255,.1);
            transition:.3s;
        }

        .container:hover {
            transform: translateY(-3px);
            box-shadow: 0 30px 50px -15px rgba(0, 0, 0, 0.5);
        }

        .left-side {
            width: 50%;
            position: relative;
            background: #08070f;
            overflow: hidden;
            background-image: 
                linear-gradient(rgba(228, 29, 29, 0.12) 1px, transparent 1px),
                linear-gradient(90deg, rgba(228, 29, 29, 0.12) 1px, transparent 1px);
            background-size: 25px 25px;
        }

        .left-side::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(228,29,29,0.06) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
            pointer-events: none;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Logo image */
        .logo {
            position: absolute;
            top: 30px;
            left: 35px;
            z-index: 2;
            animation: fadeInDown 0.6s ease;
        }

        .logo img {
            height: 45px;
            width: auto;
        }

        .hero-text {
            position: absolute;
            left:85px;
            top:180px;
            z-index: 2;
            animation: fadeInLeft 0.6s ease 0.2s both;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .hero-text h1 {
            color: white;
            font-size: 36px;
            font-weight: 400;
            line-height: 1.15;
            letter-spacing: -0.5px;
        }

        .hero-text h1 span {
            font-style: italic;
            font-weight: 500;
            background: linear-gradient(135deg, #fff 0%, #e41d1d 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .hero-text h2 {
            color: #e41d1d;
            font-size: 32px;
            font-weight: 500;
            margin-top: 3px;
            letter-spacing: -0.5px;
        }

        .hero-text p {
            color: #a08282;
            font-style: italic;
            font-size: 17px;
            line-height: 1.35;
            margin-top: 18px;
            font-weight: 300;
        }

        .right-side {
            width: 50%;
            background: linear-gradient(135deg, #ffffff 0%, #f8f8f8 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            animation: fadeInRight 0.6s ease 0.3s both;
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .login-box {
            width: 300px;
            padding: 15px;
        }

        .login-box h1 {
            font-size: 18px;
            color: #1d2433;
            margin-bottom: 22px;
            font-weight: 700;
            position: relative;
        }

        .login-box h1::after {
            content: '';
            position: absolute;
            bottom: -6px;
            left: 0;
            width: 35px;
            height: 2.5px;
            background: #e41d1d;
            border-radius: 2px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #404b60;
            font-size: 12px;
            font-weight: 500;
        }

        input, select {
            width: 100%;
            height: 40px;
            border: 2px solid #e8eaf0;
            border-radius: 8px;
            padding: 0 12px;
            margin-bottom: 14px;
            font-size: 12px;
            background: white;
            transition: all 0.3s ease;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #e41d1d;
            box-shadow: 0 0 0 2px rgba(228, 29, 29, 0.1);
        }

        .password-box {
            position: relative;
        }

        .password-box span {
            position: absolute;
            right: 12px;
            top: 10px;
            color: #999;
            cursor: pointer;
            transition: color 0.3s ease;
            font-size: 13px;
        }

        .password-box span:hover {
            color: #e41d1d;
        }

        button {
            width: 100%;
            height: 42px;
            border: none;
            background: linear-gradient(135deg, #e41d1d 0%, #b81515 100%);
            color: white;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 8px;
            font-size: 13px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }

        button:hover::before {
            left: 100%;
        }

        button:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px -5px rgba(228, 29, 29, 0.4);
        }

        .register {
            text-align: center;
            margin-top: 14px;
            color: #9da2af;
            font-size: 11px;
        }

        .register a {
            color: #e41d1d;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .register a:hover {
            color: #b81515;
            text-decoration: underline;
        }

        .error-message {
            background: #fee2e2;
            color: #dc2626;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 11px;
            margin-bottom: 15px;
            text-align: center;
        }

        /* Responsive */
        @media (max-width: 800px) {
            .container {
                flex-direction: column;
                height: auto;
                width: 450px;
            }
            .left-side, .right-side {
                width: 100%;
                min-height: 320px;
            }
            .hero-text {
                position: relative;
                left: 0;
                top: 0;
                padding: 40px 25px;
            }
            .logo {
                position: relative;
                top: 0;
                left: 0;
                padding: 25px 25px 0 25px;
            }
            .hero-text h1 {
                font-size: 28px;
            }
            .hero-text h2 {
                font-size: 24px;
            }
            .hero-text p {
                font-size: 14px;
            }
            .login-box {
                padding: 30px 25px;
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .hero-text h1 {
                font-size: 24px;
            }
            .hero-text h2 {
                font-size: 20px;
            }
            .hero-text p {
                font-size: 13px;
            }
            .login-box h1 {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>

<div class="container">

    <!-- LEFT SIDE -->
    <div class="left-side">

        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="Digika CMS Logo" onerror="this.src='https://placehold.co/150x50/ffffff/e41d1d?text=DigikaCMS'">
        </div>

        <div class="hero-text">
            <h1>
                Gérez votre <br>
                <span>affichage</span>
            </h1>
            <h2>dynamique.</h2>
            <p>
                Plateforme intelligente<br>
                pour vos écrans<br>
                professionnels
            </p>
        </div>

    </div>

    <!-- RIGHT SIDE -->
    <div class="right-side">

        <div class="login-box">

            <h1>Accédez à votre espace DigiCMS</h1>

            @if($errors->any())
                <div class="error-message">
                    @foreach($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif

            @if(session('error'))
                <div class="error-message">{{ session('error') }}</div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <label>Adresse Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="balamia@gmail.com" required>

                <label>Mot De Passe</label>
                <div class="password-box">
                    <input type="password" name="password" id="password" placeholder="Enter your password" required>
                    <span onclick="togglePassword()">👁</span>
                </div>

                <label>Rôle</label>
                <select name="role" required>
                    <option value="">Sélectionnez votre rôle</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                    <option value="marketing" {{ old('role') == 'marketing' ? 'selected' : '' }}>Marketing</option>
                    <option value="viewer" {{ old('role') == 'viewer' ? 'selected' : '' }}>Visualisateur</option>
                </select>

                <button type="submit">
                    Se connecter
                </button>

            </form>

            <div class="register">
                Pas Encore De Compte ?
                <a href="{{ route('register') }}">Créer Un Compte</a>
            </div>

        </div>

    </div>

</div>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const icon = event.target;
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.textContent = '🙈';
        } else {
            passwordInput.type = 'password';
            icon.textContent = '👁';
        }
    }
</script>

</body>
</html>