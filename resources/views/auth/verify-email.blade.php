<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification de l'e-mail - MiniBlog DGLINK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --accent-color: #4cc9f0;
            --success-color: #06d6a0;
            --warning-color: #ffd166;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .verification-container {
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

        .verification-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .email-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.3);
            position: relative;
            animation: float 3s ease-in-out infinite;
        }

        .email-icon i {
            font-size: 3rem;
            color: white;
        }

        .envelope-flap {
            position: absolute;
            top: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 30px;
            background: var(--accent-color);
            border-radius: 10px 10px 0 0;
            animation: flap 2s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes flap {
            0%, 100% {
                transform: translateX(-50%) rotate(0deg);
            }
            50% {
                transform: translateX(-50%) rotate(-10deg);
            }
        }

        .card-title {
            color: var(--secondary-color);
            font-weight: 700;
            font-size: 1.8rem;
        }

        .verification-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: rgba(67, 97, 238, 0.1);
            color: var(--primary-color);
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 1rem;
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

        .btn-link {
            color: var(--primary-color) !important;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-link:hover {
            color: var(--secondary-color) !important;
            transform: translateY(-1px);
        }

        .alert-success {
            border-radius: 12px;
            border: none;
            background: rgba(6, 214, 160, 0.1);
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
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
        @keyframes successPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .success-pulse {
            animation: successPulse 0.6s ease-in-out;
        }

        /* Email sent animation */
        @keyframes emailSent {
            0% {
                transform: translateX(-100%);
                opacity: 0;
            }
            50% {
                transform: translateX(0);
                opacity: 1;
            }
            100% {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        .email-sent {
            position: relative;
            overflow: hidden;
        }

        .email-sent::after {
            content: '✉️';
            position: absolute;
            top: 50%;
            left: -50px;
            transform: translateY(-50%);
            font-size: 1.5rem;
            animation: emailSent 2s ease-in-out;
        }

        /* Progress bar animation */
        .progress-container {
            height: 4px;
            background: #e9ecef;
            border-radius: 2px;
            overflow: hidden;
            margin: 1rem 0;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            border-radius: 2px;
            animation: progress 2s ease-in-out infinite;
        }

        @keyframes progress {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card-body {
                padding: 2rem 1.5rem;
            }

            .email-icon {
                width: 100px;
                height: 100px;
            }

            .email-icon i {
                font-size: 2.5rem;
            }

            .card-title {
                font-size: 1.5rem;
            }

            .d-flex.justify-content-between {
                flex-direction: column;
                gap: 1rem;
            }

            .btn-primary, .btn-link {
                width: 100%;
                text-align: center;
            }
        }

        @media (max-width: 576px) {
            .verification-container {
                padding: 10px;
            }

            .card-body {
                padding: 1.5rem;
            }

            .email-icon {
                width: 80px;
                height: 80px;
            }

            .email-icon i {
                font-size: 2rem;
            }

            .envelope-flap {
                width: 50px;
                height: 25px;
            }
        }

        /* Countdown animation */
        .countdown {
            font-size: 0.9rem;
            color: var(--primary-color);
            font-weight: 600;
            text-align: center;
            margin-top: 1rem;
        }

        /* Checkmark animation */
        @keyframes checkmark {
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

        .checkmark {
            animation: checkmark 0.6s ease-in-out;
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

    <div class="verification-container">
        <div class="row justify-content-center w-100">
            <div class="col-md-6 col-lg-5 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <!-- Verification Header -->
                        <div class="verification-header">
                            <div class="email-icon">
                                <div class="envelope-flap"></div>
                                <i class="fas fa-envelope-open-text"></i>
                            </div>
                            <div class="verification-badge">
                                <i class="fas fa-shield-check"></i>
                                Vérification requise
                            </div>
                            <h3 class="card-title">Vérifiez votre e-mail</h3>
                            <p class="text-center text-muted small mb-4">
                                Merci de vous être inscrit ! Avant de commencer, veuillez vérifier votre adresse e-mail en cliquant sur le lien que nous venons de vous envoyer. Si vous ne l'avez pas reçu, nous vous en enverrons un nouveau.
                            </p>
                        </div>

                        <!-- Progress Bar -->
                        <div class="progress-container">
                            <div class="progress-bar"></div>
                        </div>

                        @if (session('status') == 'verification-link-sent')
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2 checkmark"></i>
                                Un nouveau lien de vérification a été envoyé à votre adresse e-mail.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between mt-4 flex-wrap gap-3">
                            <form method="POST" action="{{ route('verification.send') }}" id="resendForm">
                                @csrf
                                <button type="submit" class="btn btn-primary" id="resendBtn">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Renvoyer l'e-mail de vérification
                                </button>
                            </form>

                            <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                                @csrf
                                <button type="submit" class="btn btn-link">
                                    <i class="fas fa-sign-out-alt me-2"></i>
                                    Se déconnecter
                                </button>
                            </form>
                        </div>

                        <!-- Countdown Timer -->
                        <div class="countdown" id="countdown">
                            Vous pourrez renvoyer l'e-mail dans <span id="timer">60</span> secondes
                        </div>

                        <!-- Help Text -->
                        <div class="mt-4 p-3 bg-light rounded-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-question-circle text-primary me-2"></i>
                                <small class="fw-semibold">Besoin d'aide ?</small>
                            </div>
                            <small class="text-muted">
                                Vérifiez votre dossier spam ou contactez notre support si vous ne recevez pas l'e-mail de vérification.
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
            const resendForm = document.getElementById('resendForm');
            const resendBtn = document.getElementById('resendBtn');
            const logoutForm = document.getElementById('logoutForm');
            const countdownElement = document.getElementById('countdown');
            const timerElement = document.getElementById('timer');
            let countdown = 60;
            let canResend = false;

            // Initialize countdown
            function startCountdown() {
                const countdownInterval = setInterval(() => {
                    countdown--;
                    timerElement.textContent = countdown;
                    
                    if (countdown <= 0) {
                        clearInterval(countdownInterval);
                        countdownElement.style.display = 'none';
                        canResend = true;
                        resendBtn.disabled = false;
                    }
                }, 1000);
            }

            startCountdown();

            // Form submission handling
            resendForm.addEventListener('submit', function(e) {
                if (!canResend) {
                    e.preventDefault();
                    return;
                }

                resendBtn.classList.add('btn-loading');
                resendBtn.disabled = true;
                
                // Reset countdown
                setTimeout(() => {
                    countdown = 60;
                    canResend = false;
                    countdownElement.style.display = 'block';
                    timerElement.textContent = countdown;
                    resendBtn.classList.remove('btn-loading');
                    resendBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Renvoyer l\'e-mail de vérification';
                    
                    // Add email sent animation
                    resendBtn.classList.add('email-sent');
                    setTimeout(() => {
                        resendBtn.classList.remove('email-sent');
                    }, 2000);
                    
                    startCountdown();
                }, 2000);
            });

            // Logout form confirmation
            logoutForm.addEventListener('submit', function(e) {
                if (!confirm('Êtes-vous sûr de vouloir vous déconnecter ? Vous devrez vous reconnecter après vérification de votre e-mail.')) {
                    e.preventDefault();
                }
            });

            // Add success animation when verification link is sent
            const successAlert = document.querySelector('.alert-success');
            if (successAlert) {
                successAlert.classList.add('success-pulse');
                
                // Auto-dismiss after 5 seconds
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(successAlert);
                    bsAlert.close();
                }, 5000);
            }

            // Add hover effects to buttons
            const buttons = document.querySelectorAll('button');
            buttons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Add click animation to resend button
            resendBtn.addEventListener('click', function() {
                if (canResend) {
                    this.classList.add('success-pulse');
                    setTimeout(() => {
                        this.classList.remove('success-pulse');
                    }, 600);
                }
            });

            // Auto-refresh page every 30 seconds to check for verification
            setTimeout(() => {
                window.location.reload();
            }, 30000);
        });
    </script>
</body>
</html>