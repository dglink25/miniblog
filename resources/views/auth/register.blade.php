<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - FlashPost</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --accent-color: #ff7b00;
            --accent-light: #ff9e33;
            --success-color: #4bb543;
            --dark-color: #1a1a2e;
            --light-color: #f8f9fa;
            --flash-blue: #4361ee;
            --post-orange: #ff7b00;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #1a2a6c, #b21f1f, #fdbb2d);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .register-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            position: relative;
        }

        /* Carte principale */
        .card {
            border: none;
            border-radius: 25px;
            box-shadow: 
                0 25px 50px rgba(0, 0, 0, 0.25),
                0 10px 30px rgba(67, 97, 238, 0.3);
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.95);
            overflow: hidden;
            width: 100%;
            max-width: 500px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            z-index: 10;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 
                0 35px 60px rgba(0, 0, 0, 0.3),
                0 15px 40px rgba(67, 97, 238, 0.4);
        }

        .card-body {
            padding: 2.5rem;
        }

        /* Logo animé */
        .logo-container {
            text-align: center;
            margin-bottom: 2.5rem;
            perspective: 1000px;
        }

        .logo-wrapper {
            width: 180px;
            height: 180px;
            margin: 0 auto 2rem;
            position: relative;
            transform-style: preserve-3d;
        }

        .logo-animation {
            width: 100%;
            height: 100%;
            position: relative;
            transform-style: preserve-3d;
            animation: logoFloat 6s ease-in-out infinite;
        }

        .logo-base {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--flash-blue), var(--post-orange));
            position: relative;
            overflow: hidden;
            box-shadow: 
                inset 0 0 50px rgba(255, 255, 255, 0.3),
                0 0 60px rgba(67, 97, 238, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-image {
            width: 70%;
            height: 70%;
            object-fit: contain;
            border-radius: 50%;
            filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.7));
            animation: logoPulse 3s ease-in-out infinite;
        }

        .logo-ring {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 3px solid transparent;
            border-top: 3px solid var(--flash-blue);
            border-right: 3px solid var(--post-orange);
            animation: logoRotate 4s linear infinite;
        }

        .logo-ring:nth-child(2) {
            width: 80%;
            height: 80%;
            top: 10%;
            left: 10%;
            animation: logoRotateReverse 3s linear infinite;
        }

        .logo-ring:nth-child(3) {
            width: 60%;
            height: 60%;
            top: 20%;
            left: 20%;
            animation: logoRotate 2s linear infinite;
        }

        .logo-sparkle {
            position: absolute;
            width: 10px;
            height: 10px;
            background: white;
            border-radius: 50%;
            filter: blur(1px);
            animation: sparkle 2s ease-in-out infinite;
        }

        .logo-sparkle:nth-child(4) {
            top: 20%;
            left: 20%;
            animation-delay: 0s;
        }

        .logo-sparkle:nth-child(5) {
            top: 70%;
            left: 80%;
            animation-delay: 0.5s;
        }

        .logo-sparkle:nth-child(6) {
            top: 80%;
            left: 30%;
            animation-delay: 1s;
        }

        .logo-sparkle:nth-child(7) {
            top: 40%;
            left: 70%;
            animation-delay: 1.5s;
        }

        /* Animations du logo */
        @keyframes logoFloat {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-15px) rotate(5deg);
            }
        }

        @keyframes logoPulse {
            0%, 100% {
                transform: scale(1);
                filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.7));
            }
            50% {
                transform: scale(1.05);
                filter: drop-shadow(0 0 15px rgba(255, 255, 255, 0.9));
            }
        }

        @keyframes logoRotate {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes logoRotateReverse {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(-360deg);
            }
        }

        @keyframes sparkle {
            0%, 100% {
                opacity: 0;
                transform: scale(0);
            }
            50% {
                opacity: 1;
                transform: scale(1);
            }
        }

        .app-name {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            letter-spacing: -1px;
            line-height: 1;
        }

        .flash-text {
            color: var(--flash-blue);
            text-shadow: 2px 2px 4px rgba(67, 97, 238, 0.2);
        }

        .post-text {
            color: var(--post-orange);
            text-shadow: 2px 2px 4px rgba(255, 123, 0, 0.2);
        }

        .card-subtitle {
            color: #6c757d;
            font-size: 1rem;
            line-height: 1.5;
        }

        /* Formulaire amélioré */
        .form-label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
        }

        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
            z-index: 3;
            transition: all 0.3s ease;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 0.875rem 1rem 0.875rem 3rem;
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(248, 249, 250, 0.8);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 
                0 0 0 0.25rem rgba(67, 97, 238, 0.15),
                0 8px 25px rgba(67, 97, 238, 0.1);
            transform: translateY(-2px);
            background: white;
        }

        .form-control:focus + .input-icon {
            color: var(--accent-color);
            transform: translateY(-50%) scale(1.1);
        }

        /* Checkbox personnalisée */
        .form-check-input {
            width: 1.2em;
            height: 1.2em;
            margin-top: 0.15em;
            border: 2px solid #dee2e6;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            box-shadow: 0 2px 10px rgba(67, 97, 238, 0.3);
        }

        .form-check-label {
            color: #495057;
            font-weight: 500;
            cursor: pointer;
        }

        /* Bouton d'inscription amélioré */
        .btn-register {
            background: linear-gradient(135deg, var(--flash-blue), var(--post-orange));
            border: none;
            border-radius: 15px;
            padding: 1rem 2rem;
            font-weight: 700;
            font-size: 1.1rem;
            color: white;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.3);
            z-index: 1;
        }

        .btn-register::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
            z-index: -1;
        }

        .btn-register:hover {
            transform: translateY(-3px);
            box-shadow: 
                0 15px 35px rgba(67, 97, 238, 0.4),
                0 5px 15px rgba(67, 97, 238, 0.2);
        }

        .btn-register:hover::before {
            left: 100%;
        }

        .btn-register:active {
            transform: translateY(-1px);
        }

        /* Alertes */
        .alert {
            border-radius: 15px;
            border: none;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: rgba(75, 181, 67, 0.1);
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border-left: 4px solid #dc3545;
        }

        /* Liens */
        .link-primary {
            color: var(--primary-color);
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
        }

        .link-primary::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--accent-color);
            transition: width 0.3s ease;
        }

        .link-primary:hover {
            color: var(--accent-color);
        }

        .link-primary:hover::after {
            width: 100%;
        }

        /* Ligne de séparation */
        .divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, #e9ecef, transparent);
            margin: 2rem 0;
            position: relative;
        }

        .divider::before {
            content: 'ou';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 0 1rem;
            color: #6c757d;
            font-size: 0.9rem;
        }

        /* Éléments flottants en arrière-plan */
        .floating-elements {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            overflow: hidden;
        }

        .floating-element {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
            animation: float 8s ease-in-out infinite;
        }

        .element-1 {
            width: 100px;
            height: 100px;
            top: 10%;
            left: 5%;
            animation-delay: 0s;
        }

        .element-2 {
            width: 150px;
            height: 150px;
            top: 60%;
            right: 5%;
            animation-delay: 2s;
        }

        .element-3 {
            width: 80px;
            height: 80px;
            bottom: 20%;
            left: 10%;
            animation-delay: 4s;
        }

        .element-4 {
            width: 120px;
            height: 120px;
            top: 20%;
            right: 15%;
            animation-delay: 6s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotate(0deg) scale(1);
            }
            33% {
                transform: translateY(-20px) rotate(120deg) scale(1.05);
            }
            66% {
                transform: translateY(10px) rotate(240deg) scale(0.95);
            }
        }

        /* États de chargement */
        .btn-loading {
            position: relative;
            color: transparent !important;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            border: 2px solid transparent;
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Nouveaux éléments d'arrière-plan */
        .background-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            overflow: hidden;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            animation: floatShape 20s linear infinite;
        }

        .shape-1 {
            width: 300px;
            height: 300px;
            top: -150px;
            left: -150px;
            animation-delay: 0s;
        }

        .shape-2 {
            width: 200px;
            height: 200px;
            bottom: -100px;
            right: -100px;
            animation-delay: 5s;
        }

        .shape-3 {
            width: 150px;
            height: 150px;
            top: 50%;
            right: -75px;
            animation-delay: 10s;
        }

        @keyframes floatShape {
            0% {
                transform: translate(0, 0) rotate(0deg);
            }
            25% {
                transform: translate(50px, 50px) rotate(90deg);
            }
            50% {
                transform: translate(100px, 0) rotate(180deg);
            }
            75% {
                transform: translate(50px, -50px) rotate(270deg);
            }
            100% {
                transform: translate(0, 0) rotate(360deg);
            }
        }

        /* Indicateur de force du mot de passe */
        .password-strength {
            height: 4px;
            border-radius: 2px;
            margin-top: 0.5rem;
            background: #e9ecef;
            overflow: hidden;
        }

        .strength-bar {
            height: 100%;
            width: 0%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .strength-weak { background: #dc3545; width: 25%; }
        .strength-fair { background: #fd7e14; width: 50%; }
        .strength-good { background: #ffc107; width: 75%; }
        .strength-strong { background: #198754; width: 100%; }

        .strength-text {
            font-size: 0.8rem;
            margin-top: 0.25rem;
            text-align: right;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .card-body {
                padding: 2rem 1.5rem;
            }

            .logo-wrapper {
                width: 150px;
                height: 150px;
                margin-bottom: 1.5rem;
            }

            .app-name {
                font-size: 2.5rem;
            }

            .card-subtitle {
                font-size: 0.95rem;
            }

            .btn-register {
                padding: 0.875rem 1.5rem;
                font-size: 1rem;
            }
        }

        @media (max-width: 576px) {
            .register-container {
                padding: 0.5rem;
            }

            .card-body {
                padding: 1.5rem 1.25rem;
            }

            .logo-wrapper {
                width: 120px;
                height: 120px;
            }

            .app-name {
                font-size: 2.2rem;
            }

            .card-subtitle {
                font-size: 0.9rem;
            }

            .form-control {
                padding: 0.75rem 1rem 0.75rem 2.75rem;
                font-size: 0.95rem;
            }

            .input-icon {
                left: 0.875rem;
            }

            .d-flex.justify-content-between {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .btn-register {
                width: 100%;
                order: -1;
            }
        }

        @media (max-width: 400px) {
            .card-body {
                padding: 1.25rem 1rem;
            }

            .logo-wrapper {
                width: 100px;
                height: 100px;
            }

            .app-name {
                font-size: 1.8rem;
            }
        }

        /* Support pour le mode sombre */
        @media (prefers-color-scheme: dark) {
            .card {
                background: rgba(40, 40, 60, 0.95);
            }

            .card-subtitle,
            .form-label,
            .form-check-label {
                color: var(--light-color);
            }

            .form-control {
                background: rgba(60, 60, 80, 0.8);
                border-color: #495057;
                color: white;
            }

            .form-control:focus {
                background: rgba(70, 70, 90, 0.9);
                color: white;
            }

            .divider::before {
                background: rgba(40, 40, 60, 0.95);
                color: #adb5bd;
            }
        }

        /* Animation d'entrée */
        @keyframes slideUpFade {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            animation: slideUpFade 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        /* Effet de particules */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 2;
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            animation: particleFloat 6s ease-in-out infinite;
        }

        @keyframes particleFloat {
            0%, 100% {
                transform: translateY(0) translateX(0);
            }
            25% {
                transform: translateY(-20px) translateX(10px);
            }
            50% {
                transform: translateY(-40px) translateX(-10px);
            }
            75% {
                transform: translateY(-20px) translateX(10px);
            }
        }
    </style>
</head>
<body>
    <!-- Éléments flottants en arrière-plan -->
    <div class="floating-elements">
        <div class="floating-element element-1"></div>
        <div class="floating-element element-2"></div>
        <div class="floating-element element-3"></div>
        <div class="floating-element element-4"></div>
    </div>

    <!-- Formes d'arrière-plan animées -->
    <div class="background-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <!-- Particules animées -->
    <div class="particles" id="particles"></div>

    <div class="register-container">
        <div class="card">
            <div class="card-body">
                <div class="logo-container">
                    <div class="logo-wrapper">
                        <div class="logo-animation">
                            <div class="logo-base">
                                <img src="flashpost.png" alt="FlashPost Logo" class="logo-image">
                                <div class="logo-ring"></div>
                                <div class="logo-ring"></div>
                                <div class="logo-ring"></div>
                                <div class="logo-sparkle"></div>
                                <div class="logo-sparkle"></div>
                                <div class="logo-sparkle"></div>
                                <div class="logo-sparkle"></div>
                            </div>
                        </div>
                    </div>
                    <h1 class="app-name">
                        <span class="flash-text">Flash</span><span class="post-text">Post</span>
                    </h1>
                    <p class="card-subtitle">
                        Créez votre compte pour découvrir et partager du contenu passionnant
                    </p>
                </div>

                <!-- Messages d'alerte -->
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;" id="success-alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <span id="success-message"></span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="display: none;" id="error-alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <span id="error-message"></span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                <form method="POST" action="{{ route('register') }}" id="registerForm">
                    @csrf <!-- Token CSRF pour Laravel -->
                    
                    <!-- Champ Nom complet -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Nom complet</label>
                        <div class="input-group">
                            <span class="input-icon">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" id="name" name="name" class="form-control" 
                                   value="{{ old('name') }}" required autofocus 
                                   placeholder="Votre nom complet">
                        </div>
                        <small class="text-danger mt-1 d-block error-message" id="name-error" style="display: none;">
                            <i class="fas fa-exclamation-circle me-1"></i>
                        </small>
                    </div>

                    <!-- Champ Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse e-mail</label>
                        <div class="input-group">
                            <span class="input-icon">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" id="email" name="email" class="form-control" 
                                   value="{{ old('email') }}" required 
                                   placeholder="votre@email.com">
                        </div>
                        <small class="text-danger mt-1 d-block error-message" id="email-error" style="display: none;">
                            <i class="fas fa-exclamation-circle me-1"></i>
                        </small>
                    </div>

                    <!-- Champ Mot de passe -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <div class="input-group">
                            <span class="input-icon">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" id="password" name="password" class="form-control" 
                                   required placeholder="Créez un mot de passe">
                            <span class="input-icon password-toggle" style="left: auto; right: 1rem; cursor: pointer;">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                        <div class="password-strength">
                            <div class="strength-bar" id="strengthBar"></div>
                        </div>
                        <div class="strength-text" id="strengthText"></div>
                        <small class="text-danger mt-1 d-block error-message" id="password-error" style="display: none;">
                            <i class="fas fa-exclamation-circle me-1"></i>
                        </small>
                    </div>

                    <!-- Champ Confirmation du mot de passe -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                        <div class="input-group">
                            <span class="input-icon">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" 
                                   required placeholder="Confirmez votre mot de passe">
                        </div>
                        <small class="text-danger mt-1 d-block error-message" id="password-confirmation-error" style="display: none;">
                            <i class="fas fa-exclamation-circle me-1"></i>
                        </small>
                    </div>

                    <!-- Case à cocher "Conditions d'utilisation" -->
                    <div class="mb-4">
                        <div class="form-check">
                            <input type="checkbox" id="terms" name="terms" class="form-check-input" required>
                            <label class="form-check-label" for="terms">
                                J'accepte les <a href="#" class="link-primary">conditions d'utilisation</a>
                            </label>
                        </div>
                        <small class="text-danger mt-1 d-block error-message" id="terms-error" style="display: none;">
                            <i class="fas fa-exclamation-circle me-1"></i>
                        </small>
                    </div>

                    <!-- Bouton d'inscription et liens -->
                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
                        <a href="{{ route('login') }}" class="link-primary small">
                            Déjà un compte ?
                        </a>
                        <button type="submit" class="btn btn-register" id="registerBtn">
                            <i class="fas fa-user-plus me-2"></i>S'inscrire
                        </button>
                    </div>
                </form>

                <!-- Séparateur -->
                <div class="divider"></div>

                <!-- Lien de connexion -->
                <p class="text-center mb-0">
                    <span class="text-muted">Déjà inscrit ?</span>
                    <a href="{{ route('login') }}" class="link-primary fw-bold ms-2">
                        Se connecter
                    </a>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const registerForm = document.getElementById('registerForm');
            const registerBtn = document.getElementById('registerBtn');
            const passwordToggle = document.querySelector('.password-toggle');
            const passwordInput = document.getElementById('password');
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');
            const confirmPasswordInput = document.getElementById('password_confirmation');
            const successAlert = document.getElementById('success-alert');
            const errorAlert = document.getElementById('error-alert');

            let formSubmitted = false;

            // Gestion de la soumission du formulaire
            registerForm.addEventListener('submit', function(e) {
                // Si le formulaire a déjà été validé, on laisse passer
                if (formSubmitted) {
                    return true;
                }
                
                e.preventDefault();
                
                // Reset errors
                hideAllErrors();
                hideAlerts();
                
                // Validate form
                const name = document.getElementById('name').value;
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;
                const passwordConfirmation = document.getElementById('password_confirmation').value;
                const terms = document.getElementById('terms').checked;
                let isValid = true;
                
                if (!name) {
                    showError('name-error', 'Le nom complet est requis');
                    isValid = false;
                } else if (name.length < 2) {
                    showError('name-error', 'Le nom doit contenir au moins 2 caractères');
                    isValid = false;
                }
                
                if (!email) {
                    showError('email-error', 'L\'adresse email est requise');
                    isValid = false;
                } else if (!isValidEmail(email)) {
                    showError('email-error', 'Veuillez entrer une adresse email valide');
                    isValid = false;
                }
                
                if (!password) {
                    showError('password-error', 'Le mot de passe est requis');
                    isValid = false;
                } else if (password.length < 8) {
                    showError('password-error', 'Le mot de passe doit contenir au moins 8 caractères');
                    isValid = false;
                }
                
                if (!passwordConfirmation) {
                    showError('password-confirmation-error', 'Veuillez confirmer votre mot de passe');
                    isValid = false;
                } else if (password !== passwordConfirmation) {
                    showError('password-confirmation-error', 'Les mots de passe ne correspondent pas');
                    isValid = false;
                }
                
                if (!terms) {
                    showError('terms-error', 'Vous devez accepter les conditions d\'utilisation');
                    isValid = false;
                }
                
                if (isValid) {
                    registerBtn.classList.add('btn-loading');
                    registerBtn.disabled = true;
                    
                    // Marquer comme validé et soumettre le formulaire normalement
                    formSubmitted = true;
                    registerForm.submit();
                }
            });

            // Fonction de validation d'email
            function isValidEmail(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }

            // Fonctions d'affichage des erreurs
            function showError(elementId, message) {
                const errorElement = document.getElementById(elementId);
                errorElement.textContent = message;
                errorElement.style.display = 'block';
                
                // Animation de secousse
                const inputElement = errorElement.closest('.mb-3, .mb-4').querySelector('.form-control, .form-check-input');
                if (inputElement) {
                    inputElement.style.animation = 'shake 0.5s ease-in-out';
                    setTimeout(() => {
                        inputElement.style.animation = '';
                    }, 500);
                }
            }

            function hideAllErrors() {
                document.querySelectorAll('.error-message').forEach(el => {
                    el.style.display = 'none';
                });
            }

            function hideAlerts() {
                successAlert.style.display = 'none';
                errorAlert.style.display = 'none';
            }

            // Basculer la visibilité du mot de passe
            passwordToggle.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                const icon = this.querySelector('i');
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            });

            // Indicateur de force du mot de passe
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;
                let text = '';
                let barClass = '';

                if (password.length >= 8) strength++;
                if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
                if (password.match(/\d/)) strength++;
                if (password.match(/[^a-zA-Z\d]/)) strength++;

                switch(strength) {
                    case 0:
                    case 1:
                        text = 'Faible';
                        barClass = 'strength-weak';
                        break;
                    case 2:
                        text = 'Moyen';
                        barClass = 'strength-fair';
                        break;
                    case 3:
                        text = 'Bon';
                        barClass = 'strength-good';
                        break;
                    case 4:
                        text = 'Fort';
                        barClass = 'strength-strong';
                        break;
                }

                strengthBar.className = 'strength-bar ' + barClass;
                strengthText.textContent = text;
                strengthText.style.color = getComputedStyle(strengthBar).backgroundColor;
            });

            // Validation de confirmation du mot de passe
            confirmPasswordInput.addEventListener('input', function() {
                if (this.value !== passwordInput.value && this.value.length > 0) {
                    this.style.borderColor = '#dc3545';
                } else {
                    this.style.borderColor = '#198754';
                }
            });

            // Créer des particules animées
            function createParticles() {
                const particlesContainer = document.getElementById('particles');
                const particleCount = 20;
                
                for (let i = 0; i < particleCount; i++) {
                    const particle = document.createElement('div');
                    particle.className = 'particle';
                    
                    // Taille aléatoire
                    const size = Math.random() * 6 + 2;
                    particle.style.width = `${size}px`;
                    particle.style.height = `${size}px`;
                    
                    // Position aléatoire
                    particle.style.left = `${Math.random() * 100}%`;
                    particle.style.top = `${Math.random() * 100}%`;
                    
                    // Délai d'animation aléatoire
                    particle.style.animationDelay = `${Math.random() * 6}s`;
                    
                    // Couleur aléatoire
                    const colors = [
                        'rgba(67, 97, 238, 0.5)',
                        'rgba(255, 123, 0, 0.5)',
                        'rgba(255, 255, 255, 0.5)',
                        'rgba(58, 12, 163, 0.5)'
                    ];
                    particle.style.background = colors[Math.floor(Math.random() * colors.length)];
                    
                    particlesContainer.appendChild(particle);
                }
            }

            // Effets d'entrée pour les champs de formulaire
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                });

                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });
            });

            // Initialiser les particules
            createParticles();

            // Afficher les erreurs existantes du serveur (Laravel)
            @if($errors->any())
                @foreach($errors->all() as $error)
                    showErrorAlert('{{ $error }}');
                @endforeach
            @endif

            // Afficher les messages de statut
            @if(session('status'))
                showSuccess('{{ session('status') }}');
            @endif
        });

        // Animation de secousse pour les erreurs
        const style = document.createElement('style');
        style.textContent = `
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                25% { transform: translateX(-5px); }
                75% { transform: translateX(5px); }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>