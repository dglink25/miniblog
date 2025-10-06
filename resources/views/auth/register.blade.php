<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - MiniBlog DGLINK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --accent-color: #4cc9f0;
            --success-color: #4bb543;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .register-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            overflow: hidden;
        }

        .card-body {
            padding: 2.5rem;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-coin {
            width: 120px;
            height: 120px;
            margin: 0 auto 1.5rem;
            position: relative;
            perspective: 1000px;
            cursor: pointer;
        }

        .logo-coin-inner {
            position: relative;
            width: 100%;
            height: 100%;
            text-align: center;
            transition: transform 0.8s;
            transform-style: preserve-3d;
        }

        .logo-coin:hover .logo-coin-inner {
            transform: rotateY(180deg);
        }

        .logo-front, .logo-back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .logo-front {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        .logo-back {
            background: linear-gradient(135deg, var(--accent-color), var(--primary-color));
            color: white;
            transform: rotateY(180deg);
        }

        .logo-front i, .logo-back i {
            font-size: 3rem;
        }

        .card-title {
            color: var(--secondary-color);
            font-weight: 700;
            font-size: 1.8rem;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
            transform: translateY(-2px);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .text-decoration-none {
            color: var(--primary-color);
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .text-decoration-none:hover {
            color: var(--secondary-color);
        }

        hr {
            border-top: 2px solid #e9ecef;
            opacity: 0.5;
        }

        /* Floating animation for background elements */
        .floating-elements {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .floating-element {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .element-1 {
            width: 80px;
            height: 80px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .element-2 {
            width: 120px;
            height: 120px;
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }

        .element-3 {
            width: 60px;
            height: 60px;
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(10deg);
            }
        }

        /* Password strength indicator */
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

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card-body {
                padding: 2rem 1.5rem;
            }

            .logo-coin {
                width: 100px;
                height: 100px;
            }

            .logo-front i, .logo-back i {
                font-size: 2.5rem;
            }

            .card-title {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .register-container {
                padding: 10px;
            }

            .card-body {
                padding: 1.5rem;
            }

            .logo-coin {
                width: 80px;
                height: 80px;
            }

            .logo-front i, .logo-back i {
                font-size: 2rem;
            }
        }

        /* Loading animation for form submission */
        .btn-loading {
            position: relative;
            color: transparent;
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

        /* Input icons */
        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            z-index: 4;
        }

        .form-control.with-icon {
            padding-left: 45px;
        }

        /* Success animation */
        @keyframes successPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .success-animation {
            animation: successPulse 0.6s ease-in-out;
        }
    </style>
</head>
<body>
    <!-- Floating Background Elements -->
    <div class="floating-elements">
        <div class="floating-element element-1"></div>
        <div class="floating-element element-2"></div>
        <div class="floating-element element-3"></div>
    </div>

    <div class="register-container">
        <div class="row justify-content-center w-100">
            <div class="col-md-6 col-lg-5 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <!-- Logo with Coin Flip Animation -->
                        <div class="logo-container">
                            <div class="logo-coin">
                                <div class="logo-coin-inner">
                                    <div class="logo-front">
                                        <i class="fas fa-user-plus"></i>
                                    </div>
                                    <div class="logo-back">
                                        <i class="fas fa-blog"></i>
                                    </div>
                                </div>
                            </div>
                            <h3 class="card-title">Créer un compte</h3>
                            <p class="text-center text-muted small">
                                Rejoignez MiniBlog de DGLINK pour publier et suivre des publications intéressantes!
                            </p>
                        </div>

                        <form method="POST" action="{{ route('register') }}" id="registerForm">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Nom complet</label>
                                <div class="input-group">
                                    <span class="input-icon">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" id="name" name="name" class="form-control with-icon" value="{{ old('name') }}" required autofocus placeholder="Votre nom complet">
                                </div>
                                @error('name')<small class="text-danger mt-1 d-block">{{ $message }}</small>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Adresse e-mail</label>
                                <div class="input-group">
                                    <span class="input-icon">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" id="email" name="email" class="form-control with-icon" value="{{ old('email') }}" required placeholder="votre@email.com">
                                </div>
                                @error('email')<small class="text-danger mt-1 d-block">{{ $message }}</small>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <div class="input-group">
                                    <span class="input-icon">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" id="password" name="password" class="form-control with-icon" required placeholder="Créez un mot de passe">
                                </div>
                                <div class="password-strength">
                                    <div class="strength-bar" id="strengthBar"></div>
                                </div>
                                <div class="strength-text" id="strengthText"></div>
                                @error('password')<small class="text-danger mt-1 d-block">{{ $message }}</small>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                                <div class="input-group">
                                    <span class="input-icon">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control with-icon" required placeholder="Confirmez votre mot de passe">
                                </div>
                                @error('password_confirmation')<small class="text-danger mt-1 d-block">{{ $message }}</small>@enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2" id="registerBtn">
                                S'inscrire
                            </button>
                        </form>

                        <hr class="my-4">
                        <p class="text-center small mb-0">
                            Déjà inscrit ? <a href="{{ route('login') }}" class="text-decoration-none fw-bold">Se connecter</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const registerForm = document.getElementById('registerForm');
            const registerBtn = document.getElementById('registerBtn');
            const passwordInput = document.getElementById('password');
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');
            const confirmPasswordInput = document.getElementById('password_confirmation');

            // Form submission loading state
            registerForm.addEventListener('submit', function() {
                registerBtn.classList.add('btn-loading');
                registerBtn.disabled = true;
            });

            // Auto-flip logo every 5 seconds
            setInterval(() => {
                const logoCoin = document.querySelector('.logo-coin-inner');
                logoCoin.style.transform = logoCoin.style.transform === 'rotateY(180deg)' ? 'rotateY(0deg)' : 'rotateY(180deg)';
            }, 5000);

            // Password strength indicator
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

            // Password confirmation validation
            confirmPasswordInput.addEventListener('input', function() {
                if (this.value !== passwordInput.value && this.value.length > 0) {
                    this.style.borderColor = '#dc3545';
                } else {
                    this.style.borderColor = '#198754';
                }
            });

            // Add focus effects to form inputs
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });

                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('focused');
                });
            });

            // Password visibility toggle
            const passwordIcons = document.querySelectorAll('.fa-lock');
            passwordIcons.forEach(icon => {
                icon.style.cursor = 'pointer';
                icon.addEventListener('click', function() {
                    const input = this.closest('.input-group').querySelector('input');
                    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                    input.setAttribute('type', type);
                    this.classList.toggle('fa-eye');
                    this.classList.toggle('fa-lock');
                });
            });

            // Success animation on successful validation
            registerForm.addEventListener('input', function() {
                const allFilled = Array.from(inputs).every(input => input.value.length > 0);
                if (allFilled && passwordInput.value === confirmPasswordInput.value) {
                    registerBtn.classList.add('success-animation');
                    setTimeout(() => {
                        registerBtn.classList.remove('success-animation');
                    }, 600);
                }
            });
        });
    </script>
</body>
</html>