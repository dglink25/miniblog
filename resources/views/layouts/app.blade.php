@php
  use Illuminate\Support\Facades\Auth;
  $user = Auth::user();
  $settings = \App\Models\SiteSetting::current();
  $unreadCount = $user ? $user->unreadNotifications()->count() : 0;
@endphp

<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Miniblog est une plateforme moderne de publication d’articles développée par DGLINK. Elle facilite la création, la visibilité et la diffusion du contenu web en Afrique.">
  <meta name="keywords" content="Miniblog, DGLINK, blog, publication, Afrique, numérique, technologie, Lokossa, Bénin">
  <meta property="og:title" content="Miniblog — La plateforme de publication numérique par DGLINK">
  <meta property="og:description" content="Partagez vos idées, projets et articles sur une plateforme simple et rapide.">
  <meta property="og:image" content="https://miniblog-myrd.onrender.com/uploads/affiche-miniblog.jpg">
  <meta name="author" content="DGLINK">

  <title>{{ config('app.name', $settings->site_name ?? 'DGLink_Pub') }}</title>

  {{-- Bootstrap CSS --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  {{-- Icônes Bootstrap --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  
  {{-- Google Fonts --}}
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    :root {
      --primary-color: #4361ee;
      --secondary-color: #3f37c9;
      --accent-color: #4cc9f0;
      --dark-color: #1a1a2e;
      --light-color: #f8f9fa;
      --success-color: #4bb543;
      --warning-color: #ffd166;
      --danger-color: #ef476f;
      --transition: all 0.3s ease;
    }

    /* ===== LOADING OVERLAY STYLES ===== */
    .loading-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      z-index: 9999;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
    }

    .loading-overlay.active {
      opacity: 1;
      visibility: visible;
    }

    .loading-spinner {
      width: 80px;
      height: 80px;
      border: 4px solid #f3f3f3;
      border-top: 4px solid var(--primary-color);
      border-radius: 50%;
      animation: spin 1s linear infinite;
      margin-bottom: 1.5rem;
      position: relative;
    }

    .loading-spinner::after {
      content: '';
      position: absolute;
      top: -4px;
      left: -4px;
      right: -4px;
      bottom: -4px;
      border: 4px solid transparent;
      border-top: 4px solid var(--accent-color);
      border-radius: 50%;
      animation: spin 1.5s linear infinite reverse;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    .loading-text {
      font-size: 1.2rem;
      font-weight: 600;
      color: var(--dark-color);
      margin-bottom: 0.5rem;
      text-align: center;
    }

    .loading-subtext {
      font-size: 0.9rem;
      color: var(--text-muted);
      text-align: center;
      max-width: 300px;
      line-height: 1.4;
    }

    .loading-dots {
      display: inline-block;
      position: relative;
    }

    .loading-dots::after {
      content: '...';
      position: absolute;
      animation: dots 1.5s steps(4, end) infinite;
    }

    @keyframes dots {
      0%, 20% { content: '.'; }
      40% { content: '..'; }
      60%, 100% { content: '...'; }
    }

    /* Mobile optimization for loading */
    @media (max-width: 768px) {
      .loading-spinner {
        width: 60px;
        height: 60px;
        border-width: 3px;
      }

      .loading-text {
        font-size: 1.1rem;
      }

      .loading-subtext {
        font-size: 0.85rem;
        max-width: 250px;
      }
    }

    /* Reduced motion support */
    @media (prefers-reduced-motion: reduce) {
      .loading-spinner {
        animation: none;
        border-top-color: var(--primary-color);
      }

      .loading-spinner::after {
        animation: none;
        display: none;
      }

      .loading-dots::after {
        animation: none;
        content: '...';
      }
    }

    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .navbar {
      background: linear-gradient(135deg, var(--dark-color) 0%, #16213e 100%) !important;
      box-shadow: 0 4px 18px rgba(0, 0, 0, 0.1);
      padding: 0.8rem 0;
      transition: var(--transition);
    }

    .navbar-brand {
      font-weight: 700;
      font-size: 1.5rem;
      background: linear-gradient(45deg, var(--accent-color), #fff);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      transition: var(--transition);
    }

    .navbar-brand:hover {
      transform: translateY(-2px);
    }

    .nav-link {
      font-weight: 500;
      margin: 0 0.2rem;
      border-radius: 6px;
      transition: var(--transition);
      position: relative;
      overflow: hidden;
    }

    .nav-link::before {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      width: 0;
      height: 2px;
      background: var(--accent-color);
      transition: var(--transition);
      transform: translateX(-50%);
    }

    .nav-link:hover::before {
      width: 80%;
    }

    .nav-link:hover {
      color: var(--accent-color) !important;
      transform: translateY(-2px);
    }

    .dropdown-menu {
      border: none;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      border-radius: 12px;
      padding: 0.5rem;
      animation: fadeIn 0.3s ease;
    }

    .dropdown-item {
      border-radius: 8px;
      padding: 0.6rem 1rem;
      transition: var(--transition);
    }

    .dropdown-item:hover {
      background-color: rgba(67, 97, 238, 0.1);
      transform: translateX(5px);
    }

    .user-avatar {
      width: 36px;
      height: 36px;
      background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
      transition: var(--transition);
    }

    .user-avatar:hover {
      transform: scale(1.1);
      box-shadow: 0 0 0 3px rgba(76, 201, 240, 0.3);
    }

    .badge-notif {
      font-size: 0.7rem;
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0% { transform: translate(50%, -30%) scale(1); }
      50% { transform: translate(50%, -30%) scale(1.1); }
      100% { transform: translate(50%, -30%) scale(1); }
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .card {
      border: none;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
      transition: var(--transition);
      overflow: hidden;
    }

    .card:hover {
      transform: translateY(-8px);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    .card-img-fixed {
      width: 100%;
      height: 200px;
      object-fit: cover;
      transition: var(--transition);
    }

    .card:hover .card-img-fixed {
      transform: scale(1.05);
    }

    .article-image {
      object-fit: cover;
      width: 100%;
      border-radius: 12px;
      transition: var(--transition);
    }

    .article-image:hover {
      transform: scale(1.02);
    }

    /* Mobile */
    @media (max-width: 576px) {
      .article-image {
        height: 180px;
      }
    }

    /* Tablette */
    @media (min-width: 577px) and (max-width: 992px) {
      .article-image {
        height: 300px;
      }
    }

    /* Desktop */
    @media (min-width: 993px) {
      .article-image {
        height: 450px;
      }
    }

    .pointer { cursor: pointer; }
    
    #toTop {
      position: fixed;
      bottom: 25px;
      right: 25px;
      display: none;
      z-index: 1031;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border: none;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
      transition: var(--transition);
    }

    #toTop:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
    }

    .nav-link .badge-notif {
      position: absolute;
      top: 0;
      right: -0.35rem;
      transform: translate(50%, -30%);
    }

    .dropdown-menu-notifs {
      max-height: 320px;
      overflow: auto;
      width: 320px;
    }

    .alert {
      border: none;
      border-radius: 12px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .btn {
      border-radius: 8px;
      font-weight: 500;
      transition: var(--transition);
      padding: 0.5rem 1.2rem;
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border: none;
    }

    .btn-primary:hover {
      transform: translateY(-3px);
      box-shadow: 0 7px 14px rgba(67, 97, 238, 0.3);
    }

    .btn-outline-light:hover {
      transform: translateY(-3px);
      box-shadow: 0 7px 14px rgba(255, 255, 255, 0.2);
    }

    /* Footer Styles */
    .custom-footer {
      background: linear-gradient(135deg, var(--dark-color) 0%, #16213e 100%);
      color: white;
      margin-top: auto;
      padding: 3rem 0 1.5rem;
    }

    .footer-links h5 {
      font-weight: 600;
      margin-bottom: 1.2rem;
      position: relative;
      display: inline-block;
    }

    .footer-links h5::after {
      content: '';
      position: absolute;
      bottom: -8px;
      left: 0;
      width: 40px;
      height: 3px;
      background: var(--accent-color);
      border-radius: 2px;
    }

    .footer-links ul {
      list-style: none;
      padding: 0;
    }

    .footer-links li {
      margin-bottom: 0.7rem;
    }

    .footer-links a {
      color: rgba(255, 255, 255, 0.7);
      text-decoration: none;
      transition: var(--transition);
      display: inline-block;
    }

    .footer-links a:hover {
      color: var(--accent-color);
      transform: translateX(5px);
    }

    .social-links a {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.1);
      color: white;
      margin-right: 10px;
      transition: var(--transition);
    }

    .social-links a:hover {
      background: var(--accent-color);
      transform: translateY(-5px);
    }

    .footer-bottom {
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      padding-top: 1.5rem;
      margin-top: 2rem;
    }

    .rating-stars {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 5px;
    }

    .rating-stars .stars {
      color: var(--warning-color);
    }

    .rating-value {
      font-weight: 600;
      margin-left: 8px;
    }

    .modal-content {
      border: none;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .modal-header {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      border: none;
    }

    .btn-close {
      filter: invert(1);
    }
  </style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-light">

{{-- LOADING OVERLAY --}}
<div id="loadingOverlay" class="loading-overlay">
  <div class="loading-spinner"></div>
  <div class="loading-text">Veuillez patienter<span class="loading-dots"></span></div>
  <div class="loading-subtext">Chargement de votre contenu en cours</div>
</div>

{{-- NAVBAR --}}
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
      {{ $settings->site_name ?? 'DGLink_Pub' }}
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
      aria-controls="mainNavbar" aria-expanded="false" aria-label="Menu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNavbar">
      {{-- Liens principaux gauche --}}
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="{{ route('articles.index') }}">Publications</a></li>

        @auth
          @php
              $user = auth()->user();
              $canPublish = false;

              if ($user->activeSubscription()) {
                  $canPublish = true;
              } elseif ($user->trial_ends_at && now()->lessThanOrEqualTo($user->trial_ends_at)) {
                  $canPublish = true;
              }
          @endphp

          <li class="nav-item">
            <a class="nav-link" href="{{ route('articles.create') }}">Créer</a>
          </li>

          <li class="nav-item"><a class="nav-link" href="{{ route('articles.mine') }}">Pour moi</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('favorites.index') }}">Favoris</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('suggestions.create') }}">Suggestion</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('subscriptions.plans') }}">Abonnements</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('payments.history') }}">Historique</a></li>

          {{-- Espace Admin (si admin) --}}
          @if(auth()->user()->is_admin ?? false)
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="adminDrop" data-bs-toggle="dropdown" aria-expanded="false">
                    Espace Admin
                </a>
                <ul class="dropdown-menu" aria-labelledby="adminDrop">
                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.annonces.index') }}">Annonces</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.plans.index') }}">Plans d'abonnement</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.suggestions.index') }}">Suggestions</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.users.index') }}">Utilisateurs</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.settings.edit') }}">Réglages</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.subscriptions.index') }}">Abonnements</a></li>
                </ul>
            </li>
          @endif
        @endauth
      </ul>

      {{-- Zone droite (notifs + user) --}}
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
        @auth
          {{-- Cloche notifications --}}
          <li class="nav-item dropdown me-lg-2">
            <a class="nav-link position-relative" href="#" id="notifDrop" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-bell fs-5"></i>
              @if($unreadCount > 0)
                <span id="notifBadge" class="badge bg-danger rounded-pill badge-notif">{{ $unreadCount }}</span>
              @endif
            </a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-notifs" aria-labelledby="notifDrop">
              @php $items = $user->unreadNotifications()->latest()->take(10)->get(); @endphp
              @forelse($items as $n)
                <li>
                  <a class="dropdown-item small" href="{{ route('notifications.show',$n->id) }}">
                    {{ $n->data['message'] ?? ($n->data['title'] ?? 'Notification') }}
                  </a>
                </li>
              @empty
                <li><span class="dropdown-item text-muted">Aucune notification</span></li>
              @endforelse
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item small text-muted" href="{{ route('articles.index') }}">Voir tout</a></li>
            </ul>
          </li>
        @endauth

        {{-- Connexion / Profil --}}
        @guest
          <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Connexion</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Inscription</a></li>
        @else
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <div class="user-avatar rounded-circle">
                {{ mb_substr($user->name,0,1) }}
              </div>
              <span class="d-none d-lg-inline">{{ $user->name }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
              <li>
                <a class="dropdown-item" href="#"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                   <i class="bi bi-box-arrow-right me-2"></i>Déconnexion
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
              </li>
            </ul>
          </li>
        @endguest
      </ul>
    </div>
  </div>
</nav>
{{-- /NAVBAR --}}

<main class="py-4 flex-grow-1">
  <div class="container">
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
      </div>
    @endif
    @if($errors->any())
      <div class="alert alert-danger">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <ul class="mb-0">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    @yield('content')
  </div>
</main>

{{-- FOOTER --}}
<footer class="custom-footer">
  <div class="container">
    <div class="row">
      <div class="col-lg-4 col-md-6 mb-4">
        <h4 class="fw-bold">{{ $settings->site_name ?? 'DGLink_Pub' }}</h4>
        <p class="mt-3 text-light">Plateforme de publication et de partage de contenu. Connectez-vous avec votre audience et partagez vos idées.</p>
        <div class="social-links mt-4">
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-linkedin-in"></i></a>
        </div>
      </div>
      
      <div class="col-lg-2 col-md-6 mb-4 footer-links">
        <h5>Liens Rapides</h5>
        <ul>
          <li><a href="{{ route('articles.index') }}">Publications</a></li>
          @auth
          <li><a href="{{ route('articles.create') }}">Créer</a></li>
          <li><a href="{{ route('favorites.index') }}">Favoris</a></li>
          @endauth
          <li><a href="{{ route('subscriptions.plans') }}">Abonnements</a></li>
        </ul>
      </div>
      
      <div class="col-lg-3 col-md-6 mb-4 footer-links">
        <h5>Support</h5>
        <ul>
          <li><a href="#">Centre d'aide</a></li>
          <li><a href="{{ route('suggestions.create') }}">Suggestions</a></li>
          <li>
            <a href="mailto:dglin25@gmail.com?subject=Demande%20d'information&body=Bonjour%20DGLINK,%0AJe%20souhaite%20en%20savoir%20plus%20sur...">
              Contact
            </a>
          </li>
          <li><a href="#">Mentions légales</a></li>
          <li><a href="#">Politique de confidentialité</a></li>
        </ul>
      </div>
      
      <div class="col-lg-3 col-md-6 mb-4">
        <h5 class="mb-3">Notez notre plateforme</h5>
        @php
          $avg = round(\App\Models\Rating::avg('stars') ?? 0, 2);
          $countRatings = \App\Models\Rating::count();
        @endphp
        <div class="rating-stars mb-3">
          <div class="stars">
            @for($i = 1; $i <= 5; $i++)
              @if($i <= floor($avg))
                <i class="fas fa-star"></i>
              @elseif($i - 0.5 <= $avg)
                <i class="fas fa-star-half-alt"></i>
              @else
                <i class="far fa-star"></i>
              @endif
            @endfor
          </div>
          <span class="rating-value">{{ $avg }}/5</span>
        </div>
        <p class="text-light small mb-3">{{ $countRatings }} avis</p>
        @auth
          <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#rateModal">
            <i class="bi bi-star-fill me-2"></i>Noter la plateforme
          </button>
        @else
          <a class="btn btn-primary w-100" href="{{ route('login') }}">
            <i class="bi bi-star-fill me-2"></i>Noter la plateforme
          </a>
        @endauth
      </div>
    </div>
    
    <div class="footer-bottom text-center">
      <p class="mb-0 text-light">&copy; {{ date('Y') }} {{ $settings->company_name ?? 'Votre société' }} — {{ $settings->site_name ?? config('app.name','DGLink_Pub') }}. Tous droits réservés.</p>
    </div>
  </div>
</footer>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

{{-- Bell sound --}}
<audio id="notifSound" preload="auto">
  <source src="{{ asset('sounds/bell.mp3') }}" type="audio/mpeg">
</audio>

{{-- LOADING SYSTEM SCRIPT --}}
<script>
class LoadingSystem {
  constructor() {
    this.overlay = document.getElementById('loadingOverlay');
    this.isLoading = false;
    this.init();
  }

  init() {
    // Intercept all navigation links
    document.addEventListener('click', (e) => {
      const link = e.target.closest('a');
      if (link && this.shouldShowLoading(link)) {
        e.preventDefault();
        this.show();
        
        // Navigate after showing loading
        setTimeout(() => {
          window.location.href = link.href;
        }, 100);
      }
    });

    // Intercept form submissions
    document.addEventListener('submit', (e) => {
      if (this.shouldShowLoading(e.target)) {
        this.show();
      }
    });

    // Hide loading when page is fully loaded
    window.addEventListener('load', () => {
      this.hide();
    });

    // Hide loading when going back/forward
    window.addEventListener('pageshow', (e) => {
      if (e.persisted) {
        this.hide();
      }
    });
  }

  shouldShowLoading(element) {
    // Don't show loading for external links, mailto, tel, etc.
    if (element.target === '_blank') return false;
    if (element.href && (
      element.href.startsWith('mailto:') ||
      element.href.startsWith('tel:') ||
      element.href.startsWith('javascript:') ||
      element.href.includes('#')
    )) return false;

    // Don't show loading for same page anchors
    if (element.hash && element.pathname === window.location.pathname) return false;

    // Show loading for internal navigation and forms
    return element.href && element.href.startsWith(window.location.origin) ||
           element.tagName === 'FORM';
  }

  show(message = null) {
    if (this.isLoading) return;
    
    this.isLoading = true;
    
    // Update message if provided
    if (message) {
      const textElement = this.overlay.querySelector('.loading-text');
      if (textElement) {
        textElement.innerHTML = message + '<span class="loading-dots"></span>';
      }
    }
    
    this.overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
  }

  hide() {
    this.isLoading = false;
    this.overlay.classList.remove('active');
    document.body.style.overflow = '';
    
    // Reset to default message
    const textElement = this.overlay.querySelector('.loading-text');
    if (textElement) {
      textElement.innerHTML = 'Veuillez patienter<span class="loading-dots"></span>';
    }
  }
}

// Initialize loading system
const loadingSystem = new LoadingSystem();

// Manual control for AJAX requests or other async operations
window.showLoading = (message = null) => loadingSystem.show(message);
window.hideLoading = () => loadingSystem.hide();
</script>

{{-- Polling notifications (15s) --}}
@auth
<script>
(function(){
  let last = {{ $unreadCount }};
  async function tick(){
    try{
      const r = await fetch('{{ route('notifications.unreadCount') }}', {headers:{'X-Requested-With':'XMLHttpRequest'}});
      const {count} = await r.json();
      const badge = document.getElementById('notifBadge');
      if(count !== last){
        if(count > last){
          document.getElementById('notifSound')?.play().catch(()=>{});
        }
        last = count;
        if(count>0){
          if(!badge){
            const a=document.querySelector('#notifDrop');
            const span=document.createElement('span');
            span.id='notifBadge';
            span.className='badge bg-danger rounded-pill badge-notif';
            span.textContent=count;
            a.appendChild(span);
          } else {
            badge.textContent=count;
          }
        } else {
          badge?.remove();
        }
      }
    }catch(e){}
    setTimeout(tick, 15000);
  }
  tick();
})();
</script>
@endauth

{{-- Bouton remonter en haut --}}
<button id="toTop" class="btn btn-primary shadow">
  <i class="bi bi-chevron-up"></i>
</button>
<script>
const toTop = document.getElementById('toTop');
window.addEventListener('scroll', () => {
  toTop.style.display = window.scrollY > 300 ? 'block' : 'none';
});
toTop?.addEventListener('click', () => window.scrollTo({top:0, behavior:'smooth'}));
</script>

{{-- MODAL Rating site --}}
<div class="modal fade" id="rateModal" tabindex="-1" aria-labelledby="rateModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="{{ route('ratings.store') }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="rateModalLabel">Noter la plateforme</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body text-center">
          <p>Choisissez une note (1 à 5 étoiles) :</p>
          <div class="d-flex justify-content-center gap-2 mb-3">
            @for($i=1; $i<=5; $i++)
              <input type="radio" name="stars" value="{{ $i }}" id="star{{ $i }}" class="d-none" required>
              <label for="star{{ $i }}" class="fs-1 text-warning pointer star-label">☆</label>
            @endfor
          </div>
          <p class="text-muted small">Votre avis nous aide à améliorer la plateforme</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-primary">Envoyer l'avis</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
// Effet étoiles
const stars = document.querySelectorAll('.star-label');
stars.forEach((star, idx) => {
  star.addEventListener('mouseenter', () => {
    stars.forEach((s, i) => s.textContent = i <= idx ? '★' : '☆');
  });
});
document.querySelectorAll('input[name="stars"]').forEach(input => {
  input.addEventListener('change', () => {
    stars.forEach((s, i) => s.textContent = i < input.value ? '★' : '☆');
  });
});
</script>

</body>
</html>