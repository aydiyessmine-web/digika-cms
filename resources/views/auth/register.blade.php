<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>DigiCMS - Créer un compte</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{
    background:linear-gradient(135deg,#2a2a2a 0%,#1a1a1a 100%);
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:20px;
}

/* CONTAINER */
.container{
    width:1000px;
    max-width:90%;
    height:780px;
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

.container:hover{
    transform:translateY(-3px);
    box-shadow:
    0 30px 50px -15px rgba(0,0,0,.5);
}

/* LEFT SIDE */
.left{
    width:50%;
    position:relative;
    overflow:hidden;
    background:#08070f;

    background-image:
    linear-gradient(rgba(228,29,29,.12) 1px,transparent 1px),
    linear-gradient(90deg,rgba(228,29,29,.12) 1px,transparent 1px);

    background-size:25px 25px;
}

.left::before{
    content:'';
    position:absolute;
    top:-50%;
    left:-50%;
    width:200%;
    height:200%;

    background:
    radial-gradient(circle,
    rgba(228,29,29,.06) 0%,
    transparent 70%);

    animation:rotate 20s linear infinite;
}

@keyframes rotate{
    from{transform:rotate(0deg);}
    to{transform:rotate(360deg);}
}

/* Logo image */
.logo-image{
    position:absolute;
    top:30px;
    left:35px;
    z-index:2;
}

.logo-image img{
    height:45px;
    width:auto;
}

.hero{
    position:absolute;
    left:85px;
    top:180px;
    z-index:2;
}

.hero h1{
    color:white;
    font-size:36px;
    font-weight:400;
    line-height:1.15;
    letter-spacing:-0.5px;
}

.hero h1 span{
    font-style:italic;
    font-weight:500;

    background:
    linear-gradient(
    135deg,
    #ffffff 0%,
    #e41d1d 100%);

    -webkit-background-clip:text;
    background-clip:text;
    color:transparent;
}
.hero h2{
    color:#e41d1d;
    font-size:32px;
    font-weight:500;
    margin-top:3px;
}

.hero p{
    color:#a08282;
    font-style:italic;
    font-size:17px;
    line-height:1.35;
    margin-top:18px;
    font-weight:300;
}

/* RIGHT */
.right{
    width:50%;
    background:linear-gradient(
    135deg,
    #ffffff 0%,
    #f8f8f8 100%);

    display:flex;
    justify-content:center;
    align-items:center;
    position:relative;
    padding:0;
}

.form-box{
    width:300px;
    padding:15px;
}

.form-box h1{
    font-size:18px;
    color:#1d2433;
    margin-bottom:22px;
    font-weight:700;
    position:relative;
    text-align:left;
}

.form-box h1::after{
    content:'';
    position:absolute;
    left:0;
    bottom:-6px;
    width:35px;
    height:2.5px;
    background:#e41d1d;
    border-radius:2px;
}

label{
    display:block;
    margin-bottom:6px;
    color:#4b5563;
    font-size:13px;
}

input,
select{
    width:100%;
    height:40px;
    border:2px solid #e8eaf0;
    border-radius:8px;
    padding:0 12px;
    margin-bottom:14px;
    font-size:12px;
    background:white;
    transition:.3s;
}

input:focus,
select:focus{
    outline:none;
    border-color:#e41d1d;
    box-shadow:0 0 0 2px rgba(228,29,29,.1);
}



.password{
    position:relative;
}

.password span{
    position:absolute;
    right:12px;
    top:7px;
    color:#9ca3af;
    font-size:14px;
    cursor:pointer;
}

.password span:hover{
    color:#e21a1a;
}

button{
    width:100%;
    height:42px;
    border:none;

    background:
    linear-gradient(
    135deg,
    #e41d1d 0%,
    #b81515 100%);

    color:white;
    border-radius:8px;
    font-weight:600;
    cursor:pointer;
    margin-top:8px;
    font-size:13px;
    transition:.3s;
}

button:hover{
    transform:translateY(-1px);
    box-shadow:
    0 8px 20px -5px
    rgba(228,29,29,.4);
}

.bottom{
    text-align:center;
    margin-top:14px;
    color:#9da2af;
    font-size:11px;
}

.bottom a{
    color:#e41d1d;
    font-weight:600;
    text-decoration:none;
}

.bottom a:hover{
    text-decoration:underline;
}

.error-message{
    background:#fee2e2;
    color:#dc2626;
    padding:6px 10px;
    border-radius:6px;
    font-size:11px;
    margin-bottom:12px;
    text-align:center;
}

.success-message{
    background:#dcfce7;
    color:#166534;
    padding:6px 10px;
    border-radius:6px;
    font-size:11px;
    margin-bottom:12px;
    text-align:center;
}

/* Scrollbar */
.right::-webkit-scrollbar{
    width:4px;
}
.right::-webkit-scrollbar-track{
    background:#f1f1f1;
}
.right::-webkit-scrollbar-thumb{
    background:#e21a1a;
    border-radius:4px;
}

@media (max-width:800px){
    .container{
        flex-direction:column;
        width:450px;
        height:auto;
    }
    .left,.right{
        width:100%;
    }
    .hero{
        position:relative;
        left:0;
        top:0;
        padding:40px;
    }
    .logo-image{
        position:relative;
        top:0;
        left:0;
        padding:30px 30px 0 30px;
    }
    .hero h1{
        font-size:28px;
    }
    .hero h2{
        font-size:22px;
    }
    .hero p{
        font-size:18px;
    }
    .form-box{
        padding:30px;
    }
}
</style>
</head>
<body>

<div class="container">

    <div class="left">

        <!-- Logo image ajouté ici -->
        <div class="logo-image">
            <img src="{{ asset('images/logo.png') }}" alt="Digika CMS Logo" onerror="this.src='https://placehold.co/150x45/ffffff/e41d1d?text=DigikaCMS'">
        </div>

        <div class="hero">

            <h1>
                Gérez votre<br>
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

    <div class="right">

        <div class="form-box">

            <h1>Créer un compte</h1>

            @if($errors->any())
                <div class="error-message">
                    @foreach($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif

            @if(session('success'))
                <div class="success-message">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="error-message">{{ session('error') }}</div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf

                <label>Nom Complet</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="prenom nom" required>

                <label>Adresse Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="balamia@gmail.com" required>

                <label>Rôle</label>
                <select name="role" required>
                    <option value="">Sélectionnez votre rôle</option>
                    <option value="marketing" {{ old('role') == 'marketing' ? 'selected' : '' }}>Marketing</option>
                    <option value="viewer" {{ old('role') == 'viewer' ? 'selected' : '' }}>Visualisateur</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                </select>

                <label>Mot De Passe</label>
                <div class="password">
                    <input type="password" name="password" id="password" placeholder="Entrer votre mot de passe" required>
                    <span onclick="togglePassword('password')">👁</span>
                </div>

                <label>Confirmer Mot De Passe</label>
                <div class="password">
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirmer votre mot de passe" required>
                    <span onclick="togglePassword('password_confirmation')">👁</span>
                </div>

                <button type="submit">Créer un compte</button>

                <div class="bottom">
                    Déjà Un Compte ?
                    <a href="{{ route('login') }}">Se Connecter</a>
                </div>

            </form>

        </div>

    </div>

</div>

<script>
    function togglePassword(fieldId) {
        const input = document.getElementById(fieldId);
        const icon = event.target;
        if (input.type === 'password') {
            input.type = 'text';
            icon.textContent = '🙈';
        } else {
            input.type = 'password';
            icon.textContent = '👁';
        }
    }
</script>

</body>
</html>