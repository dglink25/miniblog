<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation du mot de passe - MiniBlog DGLINK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --accent-color: #4cc9f0;
            --success-color: #06d6a0;
            --warning-color: #ffd166;
            --danger-color: #ef476f;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .password-reset-container {
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
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .card-body {
            padding: 2.5rem;
        }

        .reset-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .reset-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, var(--success-color), var(--accent-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 25px rgba(6, 214, 160, 0.3);
            position: relative;
            animation: float 3s ease-in-out infinite;
        }

        .reset-icon i {
            font-size: 3rem;
            color: white;
        }

        .key-animation {
            position: absolute;
            top: -10px;
            right: -10px;
            width: 30px;
            height: 30px;
            background: var(--warning-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: keySpin 2s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes keySpin {
            0%, 100% {
                transform: rotate(0deg);
            }
            50% {
                transform: rotate(180deg);
            }
        }

        .card-title {
            color: var(--secondary-color);
            font-weight: 700;
            font-size: 1.8rem;
        }

        .security-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: rgba(6, 214, 160, 0.1);
            color: var(--success-color);
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 1rem;
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
            animation: floatElement 6s ease-in-out infinite;
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

        @keyframes floatElement {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(10deg);
            }
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

        .strength-weak { background: var(--danger-color); width: 25%; }
        .strength-fair { background: var(--warning-color); width: 50%; }
        .strength-good { background: #fd7e14; width: 75%; }
        .strength-strong { background: var(--success-color); width: 100%; }

        .strength-text {
            font-size: 0.8rem;
            margin-top: 0.25rem;
            text-align: right;
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

        /* Success animation */
        @keyframes successCheck {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            50% {
                transform: scale(1.2);
                opacity: 1;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .success-check {
            animation: successCheck 0.6s ease-in-out;
        }

        /* Password visibility toggle */
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            z-index: 5;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: var(--primary-color);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card-body {
                padding: 2rem 1.5rem;
            }

            .reset-icon {
                width: 100px;
                height: 100px;
            }

            .reset-icon i {
                font-size: 2.5rem;
            }

            .card-title {
                font-size: 1.5rem;
            }

            .btn-primary {
                width: 100%;
            }

            .d-flex.justify-content-end {
                justify-content: center !important;
            }
        }

        @media (max-width: 576px) {
            .password-reset-container {
                padding: 10px;
            }

            .card-body {
                padding: 1.5rem;
            }

            .reset-icon {
                width: 80px;
                height: 80px;
            }

            .reset-icon i {
                font-size: 2rem;
            }

            .key-animation {
                width: 25px;
                height: 25px;
                font-size: 0.8rem;
            }
        }

        /* Password match indicator */
        .password-match {
            font-size: 0.8rem;
            margin-top: 0.25rem;
            text-align: right;
        }

        .match-success { color: var(--success-color); }
        .match-error { color: var(--danger-color); }

        /* Security tips */
        .security-tips {
            background: rgba(67, 97, 238, 0.05);
            border-radius: 12px;
            padding: 1rem;
            margin-top: 1rem;
        }

        .tip-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
            color: #6c757d;
        }

        .tip-item i {
            color: var(--primary-color);
            font-size: 0.7rem;
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

    <div class="password-reset-container">
        <div class="row justify-content-center w-100">
            <div class="col-md-6 col-lg-5 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <!-- Reset Header -->
                        <div class="reset-header">
                            <div class="reset-icon">
                                <i class="fas fa-lock"></i>
                                <div class="key-animation">
                                    <i class="fas fa-key"></i>
                                </div>
                            </div>
                            <div class="security-badge">
                                <i class="fas fa-shield-alt"></i>
                                Sécurisez votre compte
                            </div>
                            <h3 class="card-title">Réinitialiser le mot de passe</h3>
                            <p class="text-center text-muted small mb-4">
                                Saisissez votre nouveau mot de passe pour sécuriser votre compte.
                            </p>
                        </div>

                        <form method="POST" action="{{ route('password.store') }}" id="passwordResetForm">
                            @csrf
                            <input type="hidden" name="token" value="{{ $request->route('token') }}">

                            <div class="mb-3">
                                <label for="email" class="form-label">Adresse e-mail</label>
                                <div class="input-group">
                                    <span class="input-icon">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" id="email" name="email" class="form-control with-icon" value="{{ old('email', $request->email) }}" required autofocus placeholder="votre@email.com">
                                </div>
                                @error('email') 
                                    <small class="text-danger mt-1 d-block">
                                        <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                    </small> 
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Nouveau mot de passe</label>
                                <div class="input-group">
                                    <span class="input-icon">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" id="password" name="password" class="form-control with-icon" required placeholder="Créez un mot de passe sécurisé">
                                    <button type="button" class="password-toggle" id="passwordToggle">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="password-strength">
                                    <div class="strength-bar" id="strengthBar"></div>
                                </div>
                                <div class="strength-text" id="strengthText"></div>
                                @error('password') 
                                    <small class="text-danger mt-1 d-block">
                                        <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                    </small> 
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                                <div class="input-group">
                                    <span class="input-icon">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control with-icon" required placeholder="Confirmez votre mot de passe">
                                    <button type="button" class="password-toggle" id="confirmPasswordToggle">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="password-match" id="passwordMatch"></div>
                                @error('password_confirmation') 
                                    <small class="text-danger mt-1 d-block">
                                        <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                    </small> 
                                @enderror
                            </div>

                            <!-- Security Tips -->
                            <div class="security-tips">
                                <small class="fw-semibold d-block mb-2">Conseils de sécurité :</small>
                                <div class="tip-item">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Minimum 8 caractères</span>
                                </div>
                                <div class="tip-item">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Lettres majuscules et minuscules</span>
                                </div>
                                <div class="tip-item">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Chiffres et caractères spéciaux</span>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-primary" id="resetBtn">
                                    <i class="fas fa-redo me-2"></i>
                                    Réinitialiser le mot de passe
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordResetForm = document.getElementById('passwordResetForm');
            const resetBtn = document.getElementById('resetBtn');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password_confirmation');
            const passwordToggle = document.getElementById('passwordToggle');
            const confirmPasswordToggle = document.getElementById('confirmPasswordToggle');
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');
            const passwordMatch = document.getElementById('passwordMatch');

            // Form submission loading state
            passwordResetForm.addEventListener('submit', function(e) {
                resetBtn.classList.add('btn-loading');
                resetBtn.disabled = true;
                
                // Simulate security processing
                setTimeout(() => {
                    resetBtn.classList.remove('btn-loading');
                    resetBtn.innerHTML = '<i class="fas fa-check me-2"></i>Mot de passe mis à jour !';
                    resetBtn.classList.add('success-check');
                }, 2000);
            });

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
            function checkPasswordMatch() {
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;
                
                if (confirmPassword.length === 0) {
                    passwordMatch.textContent = '';
                    confirmPasswordInput.style.borderColor = '#e9ecef';
                } else if (password === confirmPassword) {
                    passwordMatch.textContent = '✓ Les mots de passe correspondent';
                    passwordMatch.className = 'password-match match-success';
                    confirmPasswordInput.style.borderColor = '#198754';
                } else {
                    passwordMatch.textContent = '✗ Les mots de passe ne correspondent pas';
                    passwordMatch.className = 'password-match match-error';
                    confirmPasswordInput.style.borderColor = '#dc3545';
                }
            }

            passwordInput.addEventListener('input', checkPasswordMatch);
            confirmPasswordInput.addEventListener('input', checkPasswordMatch);

            // Password visibility toggle
            function setupPasswordToggle(toggleBtn, input) {
                toggleBtn.addEventListener('click', function() {
                    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                    input.setAttribute('type', type);
                    
                    const icon = this.querySelector('i');
                    icon.classList.toggle('fa-eye');
                    icon.classList.toggle('fa-eye-slash');
                });
            }

            setupPasswordToggle(passwordToggle, passwordInput);
            setupPasswordToggle(confirmPasswordToggle, confirmPasswordInput);

            // Input focus effects
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                    const icon = this.parentElement.querySelector('.input-icon i');
                    icon.style.color = 'var(--primary-color)';
                });

                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('focused');
                    const icon = this.parentElement.querySelector('.input-icon i');
                    icon.style.color = '#6c757d';
                });
            });

            // Auto-enable button when form is valid
            function checkFormValidity() {
                const email = document.getElementById('email').value;
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;
                
                if (email && password && confirmPassword && password === confirmPassword) {
                    resetBtn.disabled = false;
                    resetBtn.classList.add('success-check');
                    setTimeout(() => {
                        resetBtn.classList.remove('success-check');
                    }, 600);
                } else {
                    resetBtn.disabled = true;
                }
            }

            inputs.forEach(input => {
                input.addEventListener('input', checkFormValidity);
            });

            // Add security animation
            setTimeout(() => {
                const keyAnimation = document.querySelector('.key-animation');
                keyAnimation.style.animation = 'keySpin 2s ease-in-out infinite';
            }, 1000);
        });
    </script>
</body>
</html>