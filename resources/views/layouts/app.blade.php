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
  <meta name="description" content="FlashPost est une plateforme moderne de publication et de divertissement développée par DGLINK. Partagez vos articles, découvrez du contenu et connectez-vous avec votre audience.">
  <meta name="keywords" content="FlashPost, DGLINK, blog, publication, divertissement, contenu, Afrique, numérique, Lokossa, Bénin">
  <meta property="og:title" content="FlashPost — Plateforme de publication et divertissement">
  <meta property="og:description" content="Partagez vos idées, projets et articles sur une plateforme simple et rapide.">
  <meta property="og:image" content="{{ asset('img/flashpost-og-image.jpg') }}">
  <meta name="author" content="DGLINK">
  
  <title>{{ config('app.name', $settings->site_name ?? 'FlashPost') }} - Publication & Divertissement</title>

  {{-- Bootstrap CSS --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  {{-- Icônes Bootstrap --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  
  {{-- Google Fonts --}}
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <style>
    :root {
      --primary-color: #4361ee;
      --secondary-color: #3f37c9;
      --accent-color: #ff7b00;
      --accent-light: #ff9e33;
      --dark-color: #1a1a2e;
      --light-color: #f8f9fa;
      --success-color: #4bb543;
      --warning-color: #ffd166;
      --danger-color: #ef476f;
      --text-dark: #2d3748;
      --text-light: #718096;
      --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
      --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
      --shadow-md: 0 8px 24px rgba(0, 0, 0, 0.12);
      --shadow-lg: 0 16px 40px rgba(0, 0, 0, 0.15);
    }

    /* ===== GLOBAL STYLES ===== */
    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      color: var(--text-dark);
      line-height: 1.6;
    }

    /* ===== LOADING OVERLAY STYLES ===== */
    .loading-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.98);
      backdrop-filter: blur(10px);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      z-index: 9999;
      opacity: 0;
      visibility: hidden;
      transition: var(--transition);
    }

    .loading-overlay.active {
      opacity: 1;
      visibility: visible;
    }

    .loading-spinner {
      width: 80px;
      height: 80px;
      border: 4px solid rgba(67, 97, 238, 0.1);
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
      font-size: 1.3rem;
      font-weight: 700;
      color: var(--dark-color);
      margin-bottom: 0.5rem;
      text-align: center;
      background: linear-gradient(45deg, var(--primary-color), var(--accent-color));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .loading-subtext {
      font-size: 0.95rem;
      color: var(--text-light);
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

    /* ===== NAVBAR STYLES ===== */
    .navbar {
      background: linear-gradient(135deg, var(--dark-color) 0%, #16213e 100%) !important;
      box-shadow: var(--shadow-md);
      padding: 0.8rem 0;
      transition: var(--transition);
      backdrop-filter: blur(10px);
    }

    .navbar-brand {
      font-weight: 800;
      font-size: 1.6rem;
      background: linear-gradient(45deg, var(--accent-color), var(--accent-light));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      transition: var(--transition);
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .navbar-brand img {
      height: 36px;
      width: auto;
    }

    .navbar-brand:hover {
      transform: translateY(-2px);
    }

    .nav-link {
      font-weight: 500;
      margin: 0 0.3rem;
      border-radius: 8px;
      transition: var(--transition);
      position: relative;
      overflow: hidden;
      padding: 0.5rem 0.8rem !important;
    }

    .nav-link::before {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      width: 0;
      height: 3px;
      background: var(--accent-color);
      transition: var(--transition);
      transform: translateX(-50%);
      border-radius: 3px;
    }

    .nav-link:hover::before {
      width: 80%;
    }

    .nav-link:hover {
      color: var(--accent-color) !important;
      transform: translateY(-2px);
      background: rgba(255, 123, 0, 0.05);
    }

    .dropdown-menu {
      border: none;
      box-shadow: var(--shadow-lg);
      border-radius: 12px;
      padding: 0.5rem;
      animation: fadeIn 0.3s ease;
      backdrop-filter: blur(10px);
      background: rgba(255, 255, 255, 0.95);
    }

    .dropdown-item {
      border-radius: 8px;
      padding: 0.6rem 1rem;
      transition: var(--transition);
      font-weight: 500;
    }

    .dropdown-item:hover {
      background: linear-gradient(135deg, rgba(67, 97, 238, 0.1), rgba(255, 123, 0, 0.1));
      transform: translateX(5px);
      color: var(--primary-color);
    }

    .user-avatar {
      width: 40px;
      height: 40px;
      background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      color: white;
      transition: var(--transition);
      border-radius: 50%;
      box-shadow: var(--shadow-sm);
    }

    .user-avatar:hover {
      transform: scale(1.1);
      box-shadow: 0 0 0 3px rgba(255, 123, 0, 0.3);
    }

    .badge-notif {
      font-size: 0.7rem;
      animation: pulse 2s infinite;
      position: absolute;
      top: 0;
      right: -0.35rem;
      transform: translate(50%, -30%);
    }

    @keyframes pulse {
      0% { transform: translate(50%, -30%) scale(1); box-shadow: 0 0 0 0 rgba(239, 71, 111, 0.7); }
      50% { transform: translate(50%, -30%) scale(1.1); }
      70% { box-shadow: 0 0 0 10px rgba(239, 71, 111, 0); }
      100% { transform: translate(50%, -30%) scale(1); box-shadow: 0 0 0 0 rgba(239, 71, 111, 0); }
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* ===== CARD STYLES ===== */
    .card {
      border: none;
      border-radius: 16px;
      box-shadow: var(--shadow-sm);
      transition: var(--transition);
      overflow: hidden;
      background: white;
    }

    .card:hover {
      transform: translateY(-8px);
      box-shadow: var(--shadow-lg);
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
      box-shadow: var(--shadow-sm);
    }

    .article-image:hover {
      transform: scale(1.02);
      box-shadow: var(--shadow-md);
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
    
    /* ===== BUTTON STYLES ===== */
    .btn {
      border-radius: 10px;
      font-weight: 600;
      transition: var(--transition);
      padding: 0.6rem 1.4rem;
      border: none;
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
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
      transition: 0.5s;
    }

    .btn:hover::before {
      left: 100%;
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
    }

    .btn-primary:hover {
      transform: translateY(-3px);
      box-shadow: 0 7px 20px rgba(67, 97, 238, 0.4);
    }

    .btn-accent {
      background: linear-gradient(135deg, var(--accent-color), var(--accent-light));
      color: white;
      box-shadow: 0 4px 15px rgba(255, 123, 0, 0.3);
    }

    .btn-accent:hover {
      transform: translateY(-3px);
      box-shadow: 0 7px 20px rgba(255, 123, 0, 0.4);
      color: white;
    }

    .btn-outline-light:hover {
      transform: translateY(-3px);
      box-shadow: 0 7px 14px rgba(255, 255, 255, 0.2);
    }

    /* ===== TO TOP BUTTON ===== */
    #toTop {
      position: fixed;
      bottom: 25px;
      right: 25px;
      display: none;
      z-index: 1031;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
      border: none;
      box-shadow: var(--shadow-md);
      transition: var(--transition);
      color: white;
      font-size: 1.2rem;
    }

    #toTop:hover {
      transform: translateY(-5px) scale(1.1);
      box-shadow: var(--shadow-lg);
    }

    .dropdown-menu-notifs {
      max-height: 320px;
      overflow: auto;
      width: 320px;
    }

    .alert {
      border: none;
      border-radius: 12px;
      box-shadow: var(--shadow-sm);
      border-left: 4px solid;
    }

    .alert-success {
      border-left-color: var(--success-color);
    }

    .alert-danger {
      border-left-color: var(--danger-color);
    }

    /* ===== FOOTER STYLES ===== */
    .custom-footer {
      background: linear-gradient(135deg, var(--dark-color) 0%, #16213e 100%);
      color: white;
      margin-top: auto;
      padding: 3rem 0 1.5rem;
    }

    .footer-links h5 {
      font-weight: 700;
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
      width: 44px;
      height: 44px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.1);
      color: white;
      margin-right: 10px;
      transition: var(--transition);
      font-size: 1.1rem;
    }

    .social-links a:hover {
      background: var(--accent-color);
      transform: translateY(-5px) scale(1.1);
      box-shadow: 0 5px 15px rgba(255, 123, 0, 0.4);
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

    /* ===== MODAL STYLES ===== */
    .modal-content {
      border: none;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: var(--shadow-lg);
    }

    .modal-header {
      background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
      color: white;
      border: none;
      padding: 1.2rem 1.5rem;
    }

    .btn-close {
      filter: invert(1);
      opacity: 0.8;
    }

    .btn-close:hover {
      opacity: 1;
    }

    /* ===== MOBILE OPTIMIZATIONS ===== */
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

      .navbar-brand {
        font-size: 1.4rem;
      }

      .navbar-brand img {
        height: 32px;
      }

      .nav-link {
        padding: 0.5rem 0.5rem !important;
        margin: 0.1rem;
      }

      .dropdown-menu {
        width: 100%;
      }

      .dropdown-menu-notifs {
        width: 100%;
        max-height: 300px;
      }

      .footer-links {
        margin-bottom: 1.5rem;
      }

      .custom-footer {
        padding: 2rem 0 1rem;
      }

      #toTop {
        bottom: 20px;
        right: 20px;
        width: 44px;
        height: 44px;
        font-size: 1rem;
      }
    }

    @media (max-width: 576px) {
      .container {
        padding-left: 15px;
        padding-right: 15px;
      }

      .navbar-collapse {
        padding-top: 1rem;
      }

      .nav-item {
        margin-bottom: 0.3rem;
      }

      .user-avatar {
        width: 36px;
        height: 36px;
      }

      .btn {
        padding: 0.5rem 1.2rem;
        font-size: 0.9rem;
      }

      .card {
        margin-bottom: 1.5rem;
      }
    }

    /* ===== ANIMATIONS ===== */
    .fade-in {
      animation: fadeIn 0.5s ease;
    }

    .slide-in-left {
      animation: slideInLeft 0.5s ease;
    }

    .slide-in-right {
      animation: slideInRight 0.5s ease;
    }

    .bounce-in {
      animation: bounceIn 0.6s ease;
    }

    @keyframes slideInLeft {
      from {
        opacity: 0;
        transform: translateX(-30px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    @keyframes slideInRight {
      from {
        opacity: 0;
        transform: translateX(30px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
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

    /* ===== UTILITY CLASSES ===== */
    .text-gradient {
      background: linear-gradient(45deg, var(--primary-color), var(--accent-color));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .bg-gradient-primary {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)) !important;
    }

    .bg-gradient-accent {
      background: linear-gradient(135deg, var(--accent-color), var(--accent-light)) !important;
    }

    .shadow-custom {
      box-shadow: var(--shadow-md) !important;
    }

    .border-radius-custom {
      border-radius: 16px !important;
    }

    /* ===== REDUCED MOTION SUPPORT ===== */
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

      .nav-link, .btn, .card, .dropdown-item, .social-links a, .user-avatar {
        transition: none;
      }

      .badge-notif {
        animation: none;
      }
    }
  </style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-light">

{{-- LOADING OVERLAY --}}
<div id="loadingOverlay" class="loading-overlay">
  <div class="loading-spinner"></div>
  <div class="loading-text">Chargement de FlashPost<span class="loading-dots"></span></div>
  <div class="loading-subtext">Préparation de votre expérience de divertissement</div>
</div>

{{-- NAVBAR --}}
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="{{ route('articles.index') }}">
      <img src="{{ asset('flashpost.png') }}" alt="FlashPost Logo">
      {{ config('app.name', $settings->site_name ?? 'FlashPost') }}
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
      aria-controls="mainNavbar" aria-expanded="false" aria-label="Menu" aria-controls="mainNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNavbar">
      {{-- Liens principaux gauche --}}
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">

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

          <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Tableau de bord</a></li>

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
                    <div class="d-flex align-items-center">
                      <i class="bi bi-bell-fill text-primary me-2"></i>
                      <div>{{ $n->data['message'] ?? ($n->data['title'] ?? 'Notification') }}</div>
                    </div>
                  </a>
                </li>
              @empty
                <li><span class="dropdown-item text-muted text-center">Aucune notification</span></li>
              @endforelse
              <li><hr class="dropdown-divider"></li>
            </ul>
          </li>
        @endauth

        {{-- Connexion / Profil --}}
        @guest
          <li class="nav-item"><a class="nav-link btn btn-accent text-white mx-1" href="{{ route('login') }}">Connexion</a></li>
          <li class="nav-item"><a class="nav-link btn btn-outline-light mx-1" href="{{ route('register') }}">Inscription</a></li>
        @else
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <div class="user-avatar rounded-circle">
                {{ mb_substr($user->name,0,1) }}
              </div>
              <span class="d-none d-lg-inline">{{ $user->name }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
              <li><a class="dropdown-item" href="{{ route('favorites.index') }}"><i class="bi bi-heart me-2"></i>Mes Favoris</a></li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <a class="dropdown-item text-danger" href="#"
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
      <div class="alert alert-success alert-dismissible fade show bounce-in" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
      </div>
    @endif
    @if($errors->any())
      <div class="alert alert-danger bounce-in">
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
        <h4 class="fw-bold text-gradient">{{ $settings->site_name ?? 'FlashPost' }}</h4>
        <p class="mt-3 text-light">Plateforme de publication et de divertissement. Partagez vos idées, découvrez du contenu et connectez-vous avec votre audience.</p>
        <div class="social-links mt-4">
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-linkedin-in"></i></a>
          <a href="#"><i class="fab fa-youtube"></i></a>
        </div>
      </div>
      
      <div class="col-lg-2 col-md-6 mb-4 footer-links">
        <h5>Navigation</h5>
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
            <a href="mailto:{{ $settings->contact_email ?? 'dglin25@gmail.com' }}?subject=Demande%20d'information&body=Bonjour%20FlashPost,%0AJe%20souhaite%20en%20savoir%20plus%20sur...">
              Contact
            </a>
          </li>
          <li><a href="#">Mentions légales</a></li>
          <li><a href="#">Politique de confidentialité</a></li>
        </ul>
      </div>
      
      <div class="col-lg-3 col-md-6 mb-4">
        <h5 class="mb-3 text-white">⭐ Avis global de FlashPost</h5>
        @php
          $avg = round(\App\Models\SiteRating::avg('stars') ?? 0, 2);
          $countRatings = \App\Models\SiteRating::count();
        @endphp

        <div class="d-flex align-items-center mb-2">
          <div class="rating-stars me-3">
            @for($i = 1; $i <= 5; $i++)
              <i class="bi bi-star{{ $i <= round($avg) ? '-fill' : '' }} text-warning"></i>
            @endfor
          </div>
          <span class="text-white fw-bold">{{ $avg }}/5</span>
        </div>

        <p class="text-light mb-3">
          {{ $countRatings }} {{ $countRatings > 1 ? 'utilisateurs ont' : 'utilisateur a' }} déjà noté notre plateforme
        </p>

        <center>
          <a href="{{ route('ratings.index') }}" class="text-accent fw-bold">
            Voir tous les avis
          </a>
        </center>
        <br>

        @auth
          <button class="btn btn-accent w-100" data-bs-toggle="modal" data-bs-target="#rateModal">
            <i class="bi bi-star-fill me-2"></i>Noter FlashPost
          </button>
        @else
          <a class="btn btn-accent w-100" href="{{ route('login') }}">
            <i class="bi bi-star-fill me-2"></i>Noter FlashPost
          </a>
        @endauth
      </div>
    </div>
    
    <div class="footer-bottom text-center">
      <p class="mb-0 text-light">&copy; {{ date('Y') }} {{ $settings->company_name ?? 'DGLINK' }} — {{ config('app.name', $settings->site_name ?? 'FlashPost') }}. Tous droits réservés.</p>
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
        textElement.innerHTML = 'Chargement de FlashPost<span class="loading-dots"></span>';
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
          <h5 class="modal-title" id="rateModalLabel">Noter FlashPost</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body text-center">
          <p class="mb-3">Choisissez une note (1 à 5 étoiles) :</p>
          <div class="d-flex justify-content-center gap-2 mb-3">
            @for($i=1; $i<=5; $i++)
              <input type="radio" name="stars" value="{{ $i }}" id="star{{ $i }}" class="d-none" required>
              <label for="star{{ $i }}" class="fs-1 text-warning pointer star-label" style="transition: var(--transition);">☆</label>
            @endfor
          </div>
          <p class="text-muted small">Votre avis nous aide à améliorer la plateforme</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-accent">Envoyer l'avis</button>
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
      stars.forEach((s, i) => {
        s.textContent = i <= idx ? '★' : '☆';
        s.style.transform = i <= idx ? 'scale(1.2)' : 'scale(1)';
      });
    });
    
    star.addEventListener('click', () => {
      stars.forEach((s, i) => {
        s.textContent = i < idx+1 ? '★' : '☆';
        s.style.transform = i < idx+1 ? 'scale(1.1)' : 'scale(1)';
      });
    });
  });
  
  document.querySelectorAll('input[name="stars"]').forEach(input => {
    input.addEventListener('change', () => {
      stars.forEach((s, i) => {
        s.textContent = i < input.value ? '★' : '☆';
        s.style.transform = i < input.value ? 'scale(1.1)' : 'scale(1)';
      });
    });
  });
</script>

</body>
</html>