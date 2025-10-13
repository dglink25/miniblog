<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur Serveur - DGLINK</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4F46E5;
            --secondary-color: #9333EA;
            --accent-color: #06D6A0;
            --text-light: #F8FAFC;
            --text-muted: #CBD5E1;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--text-light);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Animated Background Elements */
        .bg-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.1;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 6s ease-in-out infinite;
        }

        .shape-1 {
            width: 80px;
            height: 80px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape-2 {
            width: 120px;
            height: 120px;
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }

        .shape-3 {
            width: 60px;
            height: 60px;
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }

        .shape-4 {
            width: 100px;
            height: 100px;
            top: 20%;
            right: 20%;
            animation-delay: 1s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(10deg);
            }
        }

        .container {
            max-width: 580px;
            width: 100%;
            text-align: center;
            z-index: 1;
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-container {
            margin-bottom: 2rem;
            animation: bounceIn 1s ease-out;
        }

        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }
            50% {
                opacity: 1;
                transform: scale(1.05);
            }
            70% {
                transform: scale(0.9);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        .logo {
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .logo i {
            font-size: 2.5rem;
            color: var(--text-light);
        }

        .error-code {
            font-size: 8rem;
            font-weight: 800;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #FFFFFF, #E2E8F0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            animation: pulse 2s ease-in-out infinite alternate;
        }

        @keyframes pulse {
            from {
                transform: scale(1);
                text-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            }
            to {
                transform: scale(1.05);
                text-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            }
        }

        .error-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--text-light);
        }

        .error-message {
            font-size: 1.2rem;
            line-height: 1.7;
            margin-bottom: 2.5rem;
            color: var(--text-muted);
            max-width: 480px;
            margin-left: auto;
            margin-right: auto;
        }

        .error-icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            animation: shake 2s ease-in-out infinite;
        }

        @keyframes shake {
            0%, 100% {
                transform: rotate(0deg);
            }
            25% {
                transform: rotate(-5deg);
            }
            75% {
                transform: rotate(5deg);
            }
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 2rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem 2rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: rgba(255, 255, 255, 0.95);
            color: var(--primary-color);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .btn-primary:hover {
            background: white;
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-light);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .status-indicator {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            font-size: 0.9rem;
            margin-top: 2rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .pulse-dot {
            width: 8px;
            height: 8px;
            background: var(--accent-color);
            border-radius: 50%;
            animation: pulse-dot 2s ease-in-out infinite;
        }

        @keyframes pulse-dot {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.2);
                opacity: 0.7;
            }
        }

        .tech-info {
            margin-top: 2rem;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            border-left: 4px solid var(--accent-color);
            text-align: left;
            max-width: 480px;
            margin-left: auto;
            margin-right: auto;
        }

        .tech-info h4 {
            font-size: 1rem;
            margin-bottom: 0.5rem;
            color: var(--text-light);
        }

        .tech-info p {
            font-size: 0.9rem;
            margin-bottom: 0;
            color: var(--text-muted);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .error-code {
                font-size: 6rem;
            }

            .error-title {
                font-size: 1.75rem;
            }

            .error-message {
                font-size: 1.1rem;
            }

            .action-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 100%;
                max-width: 280px;
                justify-content: center;
            }

            .logo {
                width: 80px;
                height: 80px;
            }

            .logo i {
                font-size: 2rem;
            }
        }

        @media (max-width: 480px) {
            .error-code {
                font-size: 5rem;
            }

            .error-title {
                font-size: 1.5rem;
            }

            .error-message {
                font-size: 1rem;
            }

            .error-icon {
                font-size: 3rem;
            }

            .container {
                padding: 1rem;
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .btn-primary {
                background: rgba(255, 255, 255, 0.95);
                color: var(--primary-color);
            }
        }

        /* Reduced motion support */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>
<body>
    <!-- Animated Background Shapes -->
    <div class="bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>
    </div>

    <div class="container">
        <!-- Logo -->
        <div class="logo-container">
            <div class="logo">
                <i class="fas fa-link"></i>
            </div>
        </div>

        <!-- Error Icon -->
        <div class="error-icon">
            <i class="fas fa-cogs"></i>
        </div>

        <!-- Error Code -->
        <h1 class="error-code"></h1>

        <!-- Error Title -->
        <h2 class="error-title">Erreur Serveur</h2>

        <!-- Error Message -->
        <div class="error-message">
            <p>😔 Désolé, quelque chose s'est mal passé sur notre plateforme.<br>
               Notre équipe technique a été notifiée et travaille à résoudre le problème.</p>
        </div>

        <!-- Status Indicator -->
        <div class="status-indicator">
            <div class="pulse-dot"></div>
            <span>Système de monitoring activé</span>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="{{ url('/') }}" class="btn btn-primary">
                <i class="fas fa-home"></i>
                Retour à l'accueil
            </a>
            <button onclick="window.location.reload()" class="btn btn-secondary">
                <i class="fas fa-redo"></i>
                Réessayer
            </button>
        </div>

        <!-- Contact Information -->
        <div style="margin-top: 2rem; font-size: 0.9rem; color: var(--text-muted);">
            <p>Besoin d'aide immédiate ? <a href="mailto:support@dglink.com" style="color: var(--text-light); text-decoration: underline;">Contactez notre support</a></p>
        </div>
    </div>

    <script>
        // Enhanced error page interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Add click animation to buttons
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    // Create ripple effect
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.cssText = `
                        position: absolute;
                        border-radius: 50%;
                        background: rgba(255, 255, 255, 0.6);
                        transform: scale(0);
                        animation: ripple 0.6s linear;
                        width: ${size}px;
                        height: ${size}px;
                        left: ${x}px;
                        top: ${y}px;
                        pointer-events: none;
                    `;
                    
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });

            // Add CSS for ripple animation
            const style = document.createElement('style');
            style.textContent = `
                @keyframes ripple {
                    to {
                        transform: scale(4);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);

            // Auto-refresh after 30 seconds with notification
            setTimeout(() => {
                const refreshBtn = document.querySelector('.btn-secondary');
                refreshBtn.innerHTML = '<i class="fas fa-bolt"></i> Rafraîchissement automatique...';
                refreshBtn.style.background = 'var(--accent-color)';
                refreshBtn.style.color = 'white';
                
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            }, 30000);
        });
    </script>
</body>
</html>