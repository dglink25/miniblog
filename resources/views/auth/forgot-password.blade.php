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
            --success-color: #4bb543;
            --warning-color: #ffd166;
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

        .alert-success {
            border-radius: 12px;
            border: none;
            background: rgba(75, 181, 67, 0.1);
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
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

        /* Email sent animation */
        .email-sent-animation {
            animation: emailSent 0.6s ease-in-out;
        }

        @keyframes emailSent {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
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
            .password-reset-container {
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

            .btn-primary {
                width: 100%;
            }

            .d-flex.justify-content-end {
                justify-content: center !important;
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

        /* Success message animation */
        .success-message {
            animation: slideDown 0.5s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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
                        <!-- Logo with Coin Flip Animation -->
                        <div class="logo-container">
                            <div class="logo-coin">
                                <div class="logo-coin-inner">
                                    <div class="logo-front">
                                        <i class="fas fa-key"></i>
                                    </div>
                                    <div class="logo-back">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                </div>
                            </div>
                            <h3 class="card-title">Mot de passe oublié ?</h3>
                            <p class="text-center text-muted small mb-4">
                                Pas de problème. Indiquez votre adresse e-mail et nous vous enverrons un lien pour réinitialiser votre mot de passe.
                            </p>
                        </div>

                        @if(session('status'))
                            <div class="alert alert-success alert-dismissible fade show success-message" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}" id="passwordResetForm">
                            @csrf
                            <div class="mb-4">
                                <label for="email" class="form-label">Adresse e-mail</label>
                                <div class="input-group">
                                    <span class="input-icon">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" id="email" name="email" class="form-control with-icon" value="{{ old('email') }}" required autofocus placeholder="votre@email.com">
                                </div>
                                @error('email')<small class="text-danger mt-1 d-block">{{ $message }}</small>@enderror
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary" id="resetBtn">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Envoyer le lien de réinitialisation
                                </button>
                            </div>
                        </form>

                        <hr class="my-4">
                        <p class="text-center small mb-0">
                            Vous vous souvenez du mot de passe ? <a href="{{ route('login') }}" class="text-decoration-none fw-bold">Se connecter</a>
                        </p>
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
            const emailInput = document.getElementById('email');

            // Form submission loading state
            passwordResetForm.addEventListener('submit', function() {
                resetBtn.classList.add('btn-loading');
                resetBtn.disabled = true;
                
                // Add email sent animation
                setTimeout(() => {
                    resetBtn.classList.remove('btn-loading');
                    resetBtn.innerHTML = '<i class="fas fa-check me-2"></i>Lien envoyé !';
                    resetBtn.classList.add('email-sent-animation');
                }, 1500);
            });

            // Auto-flip logo every 5 seconds
            setInterval(() => {
                const logoCoin = document.querySelector('.logo-coin-inner');
                logoCoin.style.transform = logoCoin.style.transform === 'rotateY(180deg)' ? 'rotateY(0deg)' : 'rotateY(180deg)';
            }, 5000);

            // Add focus effects to form inputs
            emailInput.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });

            emailInput.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused');
            });

            // Email validation animation
            emailInput.addEventListener('input', function() {
                const email = this.value;
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                
                if (emailRegex.test(email)) {
                    this.style.borderColor = '#198754';
                    this.parentElement.querySelector('.input-icon i').style.color = '#198754';
                } else if (email.length > 0) {
                    this.style.borderColor = '#dc3545';
                    this.parentElement.querySelector('.input-icon i').style.color = '#dc3545';
                } else {
                    this.style.borderColor = '#e9ecef';
                    this.parentElement.querySelector('.input-icon i').style.color = '#6c757d';
                }
            });

            // Success message auto-dismiss
            const successAlert = document.querySelector('.alert-success');
            if (successAlert) {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(successAlert);
                    bsAlert.close();
                }, 5000);
            }

            // Add pulsing animation to button when form is valid
            emailInput.addEventListener('input', function() {
                const email = this.value;
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                
                if (emailRegex.test(email)) {
                    resetBtn.classList.add('email-sent-animation');
                    setTimeout(() => {
                        resetBtn.classList.remove('email-sent-animation');
                    }, 600);
                }
            });
        });
    </script>
</body>
</html>