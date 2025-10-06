<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmer le mot de passe - MiniBlog DGLINK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --accent-color: #4cc9f0;
            --warning-color: #ffd166;
            --danger-color: #ef476f;
            --success-color: #06d6a0;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .password-confirm-container {
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

        .security-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .security-icon {
            width: 100px;
            height: 100px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, var(--warning-color), var(--danger-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 25px rgba(255, 209, 102, 0.3);
            animation: pulse 2s ease-in-out infinite;
        }

        .security-icon i {
            font-size: 2.5rem;
            color: white;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 8px 25px rgba(255, 209, 102, 0.3);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 12px 35px rgba(255, 209, 102, 0.4);
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
            background: rgba(239, 71, 111, 0.1);
            color: var(--danger-color);
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

        .text-decoration-none {
            color: var(--primary-color);
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .text-decoration-none:hover {
            color: var(--secondary-color);
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

        /* Security shield animation */
        @keyframes shieldGlow {
            0%, 100% {
                filter: drop-shadow(0 0 5px rgba(255, 209, 102, 0.5));
            }
            50% {
                filter: drop-shadow(0 0 15px rgba(255, 209, 102, 0.8));
            }
        }

        .security-shield {
            animation: shieldGlow 2s ease-in-out infinite;
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

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card-body {
                padding: 2rem 1.5rem;
            }

            .security-icon {
                width: 80px;
                height: 80px;
            }

            .security-icon i {
                font-size: 2rem;
            }

            .card-title {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .password-confirm-container {
                padding: 10px;
            }

            .card-body {
                padding: 1.5rem;
            }

            .security-icon {
                width: 70px;
                height: 70px;
            }

            .security-icon i {
                font-size: 1.8rem;
            }

            .btn-primary {
                width: 100%;
            }

            .d-flex.justify-content-end {
                justify-content: center !important;
            }

            .security-badge {
                font-size: 0.8rem;
                padding: 0.4rem 0.8rem;
            }
        }

        /* Error animation */
        @keyframes shakeError {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .error-shake {
            animation: shakeError 0.5s ease-in-out;
        }

        /* Focus animation */
        @keyframes inputFocus {
            from {
                box-shadow: 0 0 0 0 rgba(67, 97, 238, 0.25);
            }
            to {
                box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
            }
        }

        .form-control:focus {
            animation: inputFocus 0.3s ease-out;
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

    <div class="password-confirm-container">
        <div class="row justify-content-center w-100">
            <div class="col-md-6 col-lg-5 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <!-- Security Header -->
                        <div class="security-header">
                            <div class="security-icon security-shield">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="security-badge">
                                <i class="fas fa-lock"></i>
                                Zone sécurisée
                            </div>
                            <h3 class="card-title">Confirmation du mot de passe</h3>
                            <p class="text-center text-muted small mb-4">
                                Vous êtes sur une zone sécurisée de l'application. Veuillez confirmer votre mot de passe avant de continuer.
                            </p>
                        </div>

                        <form method="POST" action="{{ route('password.confirm') }}" id="passwordConfirmForm">
                            @csrf
                            <div class="mb-4">
                                <label for="password" class="form-label">Mot de passe</label>
                                <div class="input-group">
                                    <span class="input-icon">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" id="password" name="password" class="form-control with-icon" required autocomplete="current-password" placeholder="Entrez votre mot de passe actuel">
                                    <button type="button" class="password-toggle" id="passwordToggle">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <small class="text-danger mt-1 d-block error-shake">
                                        <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary" id="confirmBtn">
                                    <i class="fas fa-check-circle me-2"></i>
                                    Confirmer
                                </button>
                            </div>
                        </form>

                        <!-- Security Tips -->
                        <div class="mt-4 p-3 bg-light rounded-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-info-circle text-primary me-2"></i>
                                <small class="fw-semibold">Conseil de sécurité</small>
                            </div>
                            <small class="text-muted">
                                Cette étape supplémentaire protège vos données sensibles. Votre mot de passe ne sera pas stocké.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordConfirmForm = document.getElementById('passwordConfirmForm');
            const confirmBtn = document.getElementById('confirmBtn');
            const passwordInput = document.getElementById('password');
            const passwordToggle = document.getElementById('passwordToggle');
            const passwordIcon = document.querySelector('.fa-lock');

            // Form submission loading state
            passwordConfirmForm.addEventListener('submit', function(e) {
                confirmBtn.classList.add('btn-loading');
                confirmBtn.disabled = true;
                
                // Simulate security check delay
                setTimeout(() => {
                    confirmBtn.classList.remove('btn-loading');
                    confirmBtn.innerHTML = '<i class="fas fa-check me-2"></i>Accès autorisé';
                    confirmBtn.classList.add('success-check');
                }, 1500);
            });

            // Password visibility toggle
            passwordToggle.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                const icon = this.querySelector('i');
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            });

            // Password input focus effects
            passwordInput.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
                passwordIcon.style.color = 'var(--primary-color)';
            });

            passwordInput.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused');
                passwordIcon.style.color = '#6c757d';
            });

            // Real-time password validation feedback
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                
                if (password.length > 0) {
                    // Add some visual feedback when typing
                    this.style.borderColor = password.length >= 8 ? '#198754' : '#fd7e14';
                    
                    // Pulse the security icon when password is being entered
                    const securityIcon = document.querySelector('.security-icon');
                    securityIcon.style.animation = 'pulse 1s ease-in-out';
                    
                    setTimeout(() => {
                        securityIcon.style.animation = 'pulse 2s ease-in-out infinite';
                    }, 1000);
                } else {
                    this.style.borderColor = '#e9ecef';
                }
            });

            // Add security animation when page loads
            setTimeout(() => {
                const securityShield = document.querySelector('.security-shield');
                securityShield.style.animation = 'shieldGlow 2s ease-in-out infinite';
            }, 500);

            // Auto-focus on password input with delay for better UX
            setTimeout(() => {
                passwordInput.focus();
            }, 800);

            // Add enter key submission
            passwordInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    confirmBtn.click();
                }
            });

            // Error handling animation
            const errorElement = document.querySelector('.text-danger');
            if (errorElement) {
                errorElement.classList.add('error-shake');
                setTimeout(() => {
                    errorElement.classList.remove('error-shake');
                }, 500);
            }
        });
    </script>
</body>
</html>