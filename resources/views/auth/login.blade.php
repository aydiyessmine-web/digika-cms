<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Connexion - DigiCMS</title>
  <!-- Google Fonts: Poppins and Montserrat -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
  <style>
    /* Global Resets */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body, html {
      height: 100%;
      width: 100%;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #2e1a1f 0%, #3e161a 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    /* Container */
    .login-container {
      display: flex;
      width: 1000px;
      max-width: 90%;
      height: 580px;
      max-height: 85vh;
      background: #ffffff;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .login-container:hover {
      transform: translateY(-3px);
      box-shadow: 0 30px 50px -15px rgba(0, 0, 0, 0.5);
    }

    /* Left Section: Dark Grid Panel */
    .left-panel {
      position: relative;
      flex: 0 0 50%;
      background-color: #0b0b0d;
      overflow: hidden;
    }

    /* Red Grid Overlay */
    .grid-overlay {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-image: 
        linear-gradient(to right, rgba(179, 24, 24, 0.12) 1px, transparent 1px),
        linear-gradient(to bottom, rgba(179, 24, 24, 0.12) 1px, transparent 1px);
      background-size: 18px 18px;
      pointer-events: none;
      z-index: 1;
    }

    /* Left Content Container */
    .left-content {
      position: relative;
      height: 100%;
      padding: 40px 8% 60px 8%;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      z-index: 2;
    }

    /* Logo avec image */
    .logo {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      align-self: flex-start;
    }

    .logo img {
      height: 72px;
      width: auto;
      display: block;
    }
    

    /* Left Typography Block */
    .typography-block {
      margin-bottom: 5%;
    }

    .main-heading {
      font-size: 38px;
      font-weight: 300;
      line-height: 1.2;
      margin-bottom: 25px;
    }

    .main-heading span {
      display: block;
    }

    .main-heading .italic-text {
      font-style: italic;
      color: #ffffff;
      letter-spacing: -0.5px;
    }

    .main-heading .normal-text {
      font-style: normal;
      font-weight: 200;
      color: #939598;
      letter-spacing: -0.5px;
    }

    .sub-heading {
      font-size: 18px;
      font-weight: 300;
      line-height: 1.35;
      font-style: italic;
      color: #832a2a;
      letter-spacing: -0.2px;
    }

    .sub-heading span {
      display: block;
    }

    /* Middle Divider Stripe */
    .divider-stripe {
      flex: 0 0 5%;
      height: 100%;
      background: linear-gradient(to right, #b0b4ba 0%, #dbdee3 100%);
      box-shadow: inset 10px 0 15px -10px rgba(0, 0, 0, 0.45);
      position: relative;
      z-index: 3;
    }

    /* Right Section: Form Panel */
    .right-panel {
      flex: 0 0 45%;
      background-color: #ffffff;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 0 5%;
      z-index: 2;
    }

    .form-container {
      width: 100%;
      max-width: 350px;
    }

    .form-title {
      font-size: 20px;
      font-weight: 700;
      color: #0d121c;
      margin-bottom: 22px;
      letter-spacing: -0.3px;
    }

    /* Form Groups */
    .login-form {
      width: 100%;
    }

    .form-group {
      margin-bottom: 16px;
    }

    .form-group label {
      display: block;
      font-size: 12px;
      color: #4b5563;
      margin-bottom: 5px;
      font-weight: 500;
    }

    .form-group input, 
    .form-group select {
      width: 100%;
      height: 42px;
      padding: 0 14px;
      border: 1.5px solid #d1d5db;
      border-radius: 8px;
      font-size: 13px;
      color: #1f2937;
      font-family: 'Poppins', sans-serif;
      background-color: #ffffff;
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-group input:focus,
    .form-group select:focus {
      border-color: #c91818;
      box-shadow: 0 0 0 3px rgba(201, 24, 24, 0.1);
    }

    .form-group input::placeholder {
      color: #9ca3af;
    }

    /* Password eye positioning */
    .password-wrapper {
      position: relative;
      width: 100%;
    }

    .password-wrapper input {
      padding-right: 42px;
    }

    .toggle-password {
      position: absolute;
      right: 14px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: #9ca3af;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0;
    }

    .toggle-password:hover {
      color: #c91818;
    }

    /* Custom Dropdown Role Styling */
    .select-wrapper {
      position: relative;
      width: 100%;
    }

    .select-wrapper select {
      padding-left: 38px;
      appearance: none;
      -webkit-appearance: none;
      -moz-appearance: none;
      cursor: pointer;
    }

    .select-arrow-left {
      position: absolute;
      left: 16px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 9px;
      color: #000000;
      pointer-events: none;
    }

    /* Submit Button */
    .submit-btn {
      width: 100%;
      height: 44px;
      background-color: #c91818;
      color: #ffffff;
      border: none;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      margin-top: 10px;
      margin-bottom: 20px;
      transition: background-color 0.2s, transform 0.1s;
    }

    .submit-btn:hover {
      background-color: #b01414;
    }

    .submit-btn:active {
      transform: scale(0.985);
    }

    /* Form Footer */
    .form-footer {
      text-align: center;
      font-size: 12px;
    }

    .footer-text {
      color: #9ca3af;
    }

    .footer-link {
      color: #2563eb;
      text-decoration: none;
      font-weight: 500;
      margin-left: 2px;
    }

    .footer-link:hover {
      text-decoration: underline;
      color: #1d4ed8;
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
      .login-container {
        flex-direction: column;
        height: auto;
        width: 450px;
      }
      .left-panel, .right-panel {
        flex: 0 0 auto;
        width: 100%;
      }
      .left-panel {
        min-height: 320px;
      }
      .divider-stripe {
        flex: 0 0 10px;
        height: 10px;
        width: 100%;
        background: linear-gradient(to bottom, #b0b4ba 0%, #dbdee3 100%);
      }
      .left-content {
        padding: 30px;
      }
      .typography-block {
        margin-bottom: 0;
      }
      .main-heading {
        font-size: 28px;
      }
      .sub-heading {
        font-size: 15px;
      }
      .right-panel {
        padding: 40px 20px;
      }
    }

    @media (max-width: 480px) {
      .main-heading {
        font-size: 24px;
      }
      .form-title {
        font-size: 18px;
      }
    }
  </style>
</head>
<body>
  <div class="login-container">
    <!-- Left Section: Dark red grid with typography -->
    <div class="left-panel">
      <div class="grid-overlay"></div>
      <div class="left-content">
        <!-- Logo with image -->
        <div class="logo">
          <img src="{{ asset('images/logo.png') }}" alt="Digika CMS Logo" onerror="this.src='https://placehold.co/150x42/ffffff/c91818?text=DigikaCMS'">
        </div>
        <!-- Typography Block -->
        <div class="typography-block">
          <h1 class="main-heading">
            <span class="italic-text">Gérez votre</span>
            <span class="italic-text">affichage</span>
            <span class="normal-text">dynamique.</span>
          </h1>
          <p class="sub-heading">
            <span>Plateforme intelligente</span>
            <span>pour vos écrans</span>
            <span>professionnels</span>
          </p>
        </div>
      </div>
    </div>
    <!-- Middle Divider Stripe -->
    <div class="divider-stripe"></div>
    <!-- Right Section: Login Form -->
    <div class="right-panel">
      <div class="form-container">
        <h2 class="form-title">Accédez à votre espace DigiCMS</h2>

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

        <form class="login-form" action="{{ route('login') }}" method="POST">
          @csrf
          <!-- Email Field -->
          <div class="form-group">
            <label for="email">Adresse Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="balamia@gmail.com" required>
          </div>
          <!-- Password Field -->
          <div class="form-group">
            <label for="password">Mot De Passe</label>
            <div class="password-wrapper">
              <input type="password" id="password" name="password" placeholder="Enter your password" required>
              <button type="button" class="toggle-password" aria-label="Afficher le mot de passe">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                  <circle cx="12" cy="12" r="3"></circle>
                </svg>
              </button>
            </div>
          </div>
          <!-- Role Field -->
          <!-- Role Field -->
          <div class="form-group">
              <label for="role">Rôle</label>
              <div class="select-wrapper">
                  <span class="select-arrow-left">▼</span>
                  <select id="role" name="role" required>
                      <option value="">Sélectionnez votre rôle</option>
                      <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                      <option value="marketing" {{ old('role') == 'marketing' ? 'selected' : '' }}>Marketing</option>
                      <!-- إزالة viewer -->
                  </select>
              </div>
          </div>
          <!-- Submit Button -->
          <button type="submit" class="submit-btn">Se connecter</button>
        </form>
        <!-- Bottom Link -->
        <div class="form-footer">
          <span class="footer-text">Pas Encore De Compte ? </span>
          <a href="{{ route('register') }}" class="footer-link">Créer Un Compte</a>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Toggle password visibility
    const passwordInput = document.getElementById('password');
    const togglePasswordBtn = document.querySelector('.toggle-password');
    
    togglePasswordBtn.addEventListener('click', () => {
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
      
      if (type === 'text') {
        togglePasswordBtn.innerHTML = `
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
            <line x1="1" y1="1" x2="23" y2="23"></line>
          </svg>
        `;
      } else {
        togglePasswordBtn.innerHTML = `
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
            <circle cx="12" cy="12" r="3"></circle>
          </svg>
        `;
      }
    });
  </script>
</body>
</html>