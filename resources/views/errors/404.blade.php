@extends('layouts.app')

@section('title', 'Page non trouvée - E-SOURCE')

@section('content')
<div class="error-container">
    <!-- Animation Background -->
    <div class="background-animation">
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>
        <div class="floating-shape shape-3"></div>
        <div class="floating-shape shape-4"></div>
    </div>

    <!-- Main Content -->
    <div class="error-content animate-fadeInUp">
        <!-- Main Message -->
        <div class="error-message">
            <h1>Page introuvable</h1>
            <p class="lead-text">
                Oups ! Il semble que la ressource que vous cherchez ait disparu ou a été supprimée par son auteur.
            </p>
            <p class="sub-text">
                Ne vous inquiétez pas, même les meilleurs explorateurs se perdent parfois. 
                Revenez sur la terre ferme et continuez votre aventure !
            </p>
        </div>

        <!-- Animated Illustration -->
        <div class="error-illustration">
            <div class="astronaut">
                <div class="astronaut-helmet"></div>
                <div class="astronaut-body"></div>
                <div class="astronaut-arms"></div>
                <div class="floating-rocks">
                    <div class="rock rock-1"></div>
                    <div class="rock rock-2"></div>
                    <div class="rock rock-3"></div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="{{ url('/') }}" class="btn-primary">
                <i class="bi bi-house-fill"></i>
                Retour à l'accueil
            </a>
            <button onclick="history.back()" class="btn-secondary">
                <i class="bi bi-arrow-left"></i>
                Page précédente
            </button>
            <a href="{{ route('articles.index') }}" class="btn-tertiary">
                <i class="bi bi-newspaper"></i>
                Voir les publications
            </a>
        </div>
    </div>

    <!-- Floating Help -->
    <div class="floating-help">
        <div class="help-bubble">
            <i class="bi bi-question-circle"></i>
            <span>Besoin d'aide ?</span>
        </div>
    </div>
</div>

<style>
/* ===== VARIABLES & BASE STYLES ===== */
:root {
    --primary-color: #4361ee;
    --secondary-color: #3a0ca3;
    --accent-color: #7209b7;
    --text-dark: #2d3748;
    --text-light: #718096;
    --bg-light: #f7fafc;
    --bg-white: #ffffff;
    --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 20px 60px rgba(0, 0, 0, 0.15);
}

.error-container {
    min-height: 100vh;
    
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
    padding: 2rem 1rem;
}

/* ===== BACKGROUND ANIMATION ===== */
.background-animation {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}

.floating-shape {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    animation: float 6s ease-in-out infinite;
}

.shape-1 {
    width: 100px;
    height: 100px;
    top: 10%;
    left: 10%;
    animation-delay: 0s;
}

.shape-2 {
    width: 150px;
    height: 150px;
    top: 60%;
    right: 10%;
    animation-delay: 2s;
}

.shape-3 {
    width: 80px;
    height: 80px;
    bottom: 20%;
    left: 20%;
    animation-delay: 4s;
}

.shape-4 {
    width: 120px;
    height: 120px;
    top: 30%;
    right: 20%;
    animation-delay: 1s;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(10deg); }
}

/* ===== MAIN CONTENT ===== */
.error-content {
    background: var(--bg-white);
    border-radius: 24px;
    padding: 3rem 2rem;
    box-shadow: var(--shadow-lg);
    text-align: center;
    max-width: 600px;
    width: 100%;
    position: relative;
    z-index: 2;
    animation: slideUp 0.8s ease-out;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* ===== ERROR NUMBER ===== */
.error-number {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
    position: relative;
}

.digit {
    font-size: 6rem;
    font-weight: 900;
    color: var(--primary-color);
    text-shadow: 3px 3px 0px rgba(67, 97, 238, 0.2);
    animation: bounceIn 1s ease-out;
}

.digit-4:nth-child(1) { animation-delay: 0.1s; }
.digit-4:nth-child(3) { animation-delay: 0.3s; }

@keyframes bounceIn {
    0% {
        opacity: 0;
        transform: scale(0.3) translateY(-100px);
    }
    50% {
        transform: scale(1.05) translateY(10px);
    }
    100% {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.floating-icon {
    width: 80px;
    height: 80px;
    background: var(--accent-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: float 3s ease-in-out infinite, rotate 4s linear infinite;
}

.floating-icon i {
    font-size: 2.5rem;
    color: white;
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* ===== ERROR MESSAGE ===== */
.error-message h1 {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--text-dark);
    margin-bottom: 1rem;
    animation: fadeInUp 0.8s ease-out 0.4s both;
}

.lead-text {
    font-size: 1.3rem;
    color: var(--primary-color);
    font-weight: 600;
    margin-bottom: 1rem;
    animation: fadeInUp 0.8s ease-out 0.6s both;
}

.sub-text {
    font-size: 1.1rem;
    color: var(--text-light);
    line-height: 1.6;
    margin-bottom: 2rem;
    animation: fadeInUp 0.8s ease-out 0.8s both;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* ===== ASTRONAUT ILLUSTRATION ===== */
.error-illustration {
    margin: 2rem 0;
    height: 120px;
    position: relative;
    animation: fadeInUp 0.8s ease-out 1s both;
}

.astronaut {
    position: relative;
    width: 80px;
    height: 100px;
    margin: 0 auto;
}

.astronaut-helmet {
    width: 60px;
    height: 60px;
    background: #e2e8f0;
    border-radius: 50%;
    margin: 0 auto;
    position: relative;
    animation: float 4s ease-in-out infinite;
}

.astronaut-helmet::before {
    content: '';
    position: absolute;
    top: 15px;
    left: 15px;
    width: 30px;
    height: 20px;
    background: #cbd5e0;
    border-radius: 10px;
}

.astronaut-body {
    width: 40px;
    height: 40px;
    background: #e2e8f0;
    border-radius: 10px;
    margin: -10px auto 0;
    animation: float 4s ease-in-out infinite 0.2s;
}

.astronaut-arms {
    position: absolute;
    top: 30px;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 20px;
    animation: wave 3s ease-in-out infinite;
}

@keyframes wave {
    0%, 100% { transform: translateX(-50%) rotate(0deg); }
    50% { transform: translateX(-50%) rotate(10deg); }
}

.floating-rocks {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.rock {
    position: absolute;
    background: #cbd5e0;
    border-radius: 50%;
    animation: float 5s ease-in-out infinite;
}

.rock-1 {
    width: 20px;
    height: 20px;
    top: 20px;
    left: 20px;
    animation-delay: 0s;
}

.rock-2 {
    width: 15px;
    height: 15px;
    top: 60px;
    right: 30px;
    animation-delay: 1.5s;
}

.rock-3 {
    width: 25px;
    height: 25px;
    bottom: 10px;
    left: 40px;
    animation-delay: 3s;
}

/* ===== ACTION BUTTONS ===== */
.action-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
    margin: 2rem 0;
    animation: fadeInUp 0.8s ease-out 1.2s both;
}

.btn-primary, .btn-secondary, .btn-tertiary {
    padding: 1rem 1.5rem;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    cursor: pointer;
    font-size: 1rem;
}

.btn-primary {
    background: var(--primary-color);
    color: white;
    box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
}

.btn-primary:hover {
    background: var(--secondary-color);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
}

.btn-secondary {
    background: transparent;
    color: var(--primary-color);
    border: 2px solid var(--primary-color);
}

.btn-secondary:hover {
    background: var(--primary-color);
    color: white;
    transform: translateY(-2px);
}

.btn-tertiary {
    background: var(--bg-light);
    color: var(--text-dark);
}

.btn-tertiary:hover {
    background: #e2e8f0;
    transform: translateY(-2px);
}

/* ===== QUICK STATS ===== */
.quick-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    margin-top: 2rem;
    animation: fadeInUp 0.8s ease-out 1.4s both;
}

.stat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem;
    background: var(--bg-light);
    border-radius: 12px;
    transition: transform 0.3s ease;
}

.stat-item:hover {
    transform: translateY(-5px);
}

.stat-item i {
    font-size: 1.5rem;
    color: var(--primary-color);
}

.stat-item span {
    font-size: 0.9rem;
    color: var(--text-light);
    font-weight: 500;
}

/* ===== FLOATING HELP ===== */
.floating-help {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    z-index: 3;
    animation: bounce 2s ease-in-out infinite;
}

.help-bubble {
    background: var(--primary-color);
    color: white;
    padding: 1rem 1.5rem;
    border-radius: 50px;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow: var(--shadow);
    cursor: pointer;
    transition: all 0.3s ease;
}

.help-bubble:hover {
    background: var(--secondary-color);
    transform: scale(1.05);
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-10px); }
    60% { transform: translateY(-5px); }
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 768px) {
    .error-content {
        padding: 2rem 1.5rem;
        margin: 1rem;
    }

    .digit {
        font-size: 4rem;
    }

    .floating-icon {
        width: 60px;
        height: 60px;
    }

    .floating-icon i {
        font-size: 2rem;
    }

    .error-message h1 {
        font-size: 2rem;
    }

    .lead-text {
        font-size: 1.1rem;
    }

    .sub-text {
        font-size: 1rem;
    }

    .action-buttons {
        flex-direction: column;
        align-items: center;
    }

    .btn-primary, .btn-secondary, .btn-tertiary {
        width: 100%;
        max-width: 250px;
        justify-content: center;
    }

    .quick-stats {
        grid-template-columns: 1fr;
    }

    .floating-help {
        bottom: 1rem;
        right: 1rem;
    }

    .help-bubble {
        padding: 0.8rem 1.2rem;
        font-size: 0.9rem;
    }
}

@media (max-width: 480px) {
    .error-number {
        gap: 0.5rem;
    }

    .digit {
        font-size: 3rem;
    }

    .floating-icon {
        width: 50px;
        height: 50px;
    }

    .floating-icon i {
        font-size: 1.5rem;
    }

    .error-content {
        padding: 1.5rem 1rem;
    }
}

/* ===== REDUCED MOTION SUPPORT ===== */
@media (prefers-reduced-motion: reduce) {
    .floating-shape,
    .digit,
    .floating-icon,
    .astronaut-helmet,
    .astronaut-body,
    .astronaut-arms,
    .rock,
    .floating-help,
    .stat-item {
        animation: none;
    }

    .btn-primary:hover,
    .btn-secondary:hover,
    .btn-tertiary:hover,
    .stat-item:hover,
    .help-bubble:hover {
        transform: none;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add interactive elements
    const helpBubble = document.querySelector('.help-bubble');
    
    if (helpBubble) {
        helpBubble.addEventListener('click', function() {
            // Create a simple help modal
            const helpText = [
                "💡 Conseil : Vérifiez l'URL dans la barre d'adresse",
                "🔍 Essayez notre moteur de recherche",
                "📞 Contactez notre support si le problème persiste"
            ];
            
            const randomTip = helpText[Math.floor(Math.random() * helpText.length)];
            
            // Show temporary tooltip
            const tooltip = document.createElement('div');
            tooltip.className = 'help-tooltip';
            tooltip.textContent = randomTip;
            tooltip.style.cssText = `
                position: fixed;
                bottom: 100px;
                right: 2rem;
                background: var(--primary-color);
                color: white;
                padding: 1rem;
                border-radius: 12px;
                box-shadow: var(--shadow-lg);
                z-index: 1000;
                max-width: 250px;
                animation: slideInRight 0.3s ease-out;
            `;
            
            document.body.appendChild(tooltip);
            
            // Remove tooltip after 3 seconds
            setTimeout(() => {
                tooltip.style.animation = 'slideOutRight 0.3s ease-in forwards';
                setTimeout(() => tooltip.remove(), 300);
            }, 3000);
        });
    }

    // Add CSS for tooltip animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes slideOutRight {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(100%);
            }
        }
    `;
    document.head.appendChild(style);
});
</script>
@endsection