@extends('layouts.app')

@php
  use Illuminate\Support\Str;
  use Illuminate\Support\Arr;
@endphp

@section('content')
<div class="container-fluid px-2 px-md-3 px-lg-4 py-3">
  {{-- Floating Action Buttons --}}
  <div class="floating-actions">
    {{-- Create Button --}}
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

      @if($canPublish)
        <div class="floating-btn-group">
          <a class="btn btn-primary btn-lg shadow-lg hover-scale create-btn" href="{{ route('articles.create') }}">
            <i class="bi bi-plus-lg"></i>
            <span class="btn-text">Créer</span>
          </a>
          <span class="btn-tooltip">Publier du contenu</span>
        </div>
      @endif
    @else
      <div class="floating-btn-group">
        <a class="btn btn-primary btn-lg shadow-lg hover-scale create-btn" href="{{ route('login') }}">
          <i class="bi bi-plus-lg"></i>
          <span class="btn-text">Créer</span>
        </a>
        <span class="btn-tooltip">Connectez-vous pour créer</span>
      </div>
    @endauth

    {{-- Suggestion Button --}}
    <div class="floating-btn-group">
      <button class="btn btn-accent btn-lg shadow-lg hover-scale suggestion-btn" data-bs-toggle="modal" data-bs-target="#suggestionModal">
        <i class="bi bi-lightbulb"></i>
        <span class="btn-text">Suggestion</span>
      </button>
      <span class="btn-tooltip">Partager une idée</span>
    </div>

    {{-- Scroll to Top --}}
    <button class="btn btn-secondary btn-lg shadow-lg hover-scale scroll-top-btn" onclick="scrollToTop()">
      <i class="bi bi-arrow-up"></i>
    </button>
  </div>

  {{-- Hero Section --}}
  <div class="hero-section mb-5 animate-fade-in">
    <div class="row align-items-center">
      <div class="col-12 col-lg-8">
        <h1 class="display-5 fw-bold mb-3">
          Bienvenue sur <span class="brand-name"><span class="text-primary">Flash</span><span class="text-accent">Post</span></span>
        </h1>
        <p class="lead text-muted mb-4">
          Découvrez, partagez et interagissez avec du contenu passionnant. 
          La plateforme qui donne vie à vos idées et connecte votre audience.
        </p>
        <div class="d-flex flex-wrap gap-3">
          <a href="#articles-grid" class="btn btn-primary btn-lg hover-lift">
            <i class="bi bi-play-circle me-2"></i>Explorer
          </a>
          <a href="{{ route('articles.create') }}" class="btn btn-outline-primary btn-lg hover-lift">
            <i class="bi bi-lightning me-2"></i>Commencer
          </a>
        </div>
      </div>
      <div class="col-12 col-lg-4 mt-4 mt-lg-0">
        <div class="hero-stats card border-0 bg-gradient-primary text-white shadow-lg">
          <div class="card-body p-4">
            <div class="row text-center">
              <div class="col-4">
                <div class="stat-number h3 fw-bold">{{ \App\Models\Article::where('status', 'validated')->count() }}+</div>
                <div class="stat-label small">Publications</div>
              </div>
              <div class="col-4">
                <div class="stat-number h3 fw-bold">{{ \App\Models\User::count() }}+</div>
                <div class="stat-label small">Membres</div>
              </div>
              <div class="col-4">
                <div class="stat-number h3 fw-bold">{{ \App\Models\Comment::count() }}+</div>
                <div class="stat-label small">Interactions</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Search Section --}}
  <div class="search-section mb-5">
    <div class="card search-card border-0 shadow-lg animate-slide-up">
      <div class="card-body p-4">
        <form method="GET" action="{{ route('articles.index') }}" id="searchForm">
          <div class="row g-3 align-items-end">
            <div class="col-12 col-md-8 col-lg-9">
              <div class="form-floating">
                <input type="text" name="q" value="{{ $q ?? '' }}" 
                       class="form-control form-control-lg border-0 shadow-sm" 
                       id="searchInput"
                       placeholder="Rechercher une publication, un auteur...">
                <label for="searchInput">
                  <i class="bi bi-search me-2"></i>Rechercher une publication, un auteur...
                </label>
              </div>
            </div>
            <div class="col-12 col-md-4 col-lg-3">
              <button class="btn btn-accent btn-lg w-100 h-100 py-3 hover-lift" type="submit">
                <i class="bi bi-sliders2 me-2"></i>Filtrer
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Pinned Announcements --}}
  @foreach(($annonces ?? []) as $a)
    <div class="announcement-card card border-warning shadow-lg mb-5 animate-scale-in">
      <div class="card-header bg-warning bg-opacity-10 border-warning d-flex align-items-center">
        <i class="bi bi-megaphone-fill text-warning me-2 fs-5"></i>
        <strong class="text-warning">Annonce importante</strong>
        @if($a->is_pinned)
          <i class="bi bi-pin-angle-fill text-warning ms-2"></i>
        @endif
        <span class="ms-auto small text-muted">
          {{ $a->published_at?->diffForHumans() }}
        </span>
      </div>
      <div class="card-body">
        <h4 class="card-title text-center mb-4 fw-bold text-dark">{{ $a->title }}</h4>

        {{-- Media Content --}}
        @if($a->media_url)
          <div class="media-container mb-4">
            {{-- Image --}}
            @if(Str::endsWith($a->media_url, ['.jpg','.jpeg','.png','.gif']))
              <div class="text-center">
                <img src="{{ $a->media_url }}" class="img-fluid rounded-3 shadow media-content" 
                     alt="Image annonce" style="max-height: 400px; width: auto;">
              </div>

            {{-- YouTube --}}
            @elseif(Str::contains($a->media_url, 'youtube.com/watch') || Str::contains($a->media_url, 'youtu.be'))
              @php
                $videoId = null;
                if(Str::contains($a->media_url, 'youtu.be')) {
                  $videoId = basename(parse_url($a->media_url, PHP_URL_PATH));
                } else {
                  parse_str(parse_url($a->media_url, PHP_URL_QUERY), $ytParams);
                  $videoId = $ytParams['v'] ?? null;
                }
              @endphp
              @if($videoId)
                <div class="ratio ratio-16x9">
                  <iframe src="https://www.youtube.com/embed/{{ $videoId }}?rel=0" 
                          class="rounded-3 shadow" 
                          allowfullscreen
                          loading="lazy"></iframe>
                </div>
              @endif

            {{-- TikTok --}}
            @elseif(Str::contains($a->media_url, 'tiktok.com'))
              @php
                $parts = explode('/', parse_url($a->media_url, PHP_URL_PATH));
                $tiktokId = end($parts);
              @endphp
              <div class="text-center">
                <blockquote class="tiktok-embed" cite="{{ $a->media_url }}" 
                    data-video-id="{{ $tiktokId }}" 
                    style="max-width: 605px;min-width: 325px; margin: 0 auto;">
                  <section></section>
                </blockquote>
              </div>

            {{-- Video --}}
            @elseif(Str::endsWith($a->media_url, ['.mp4','.webm']))
              <div class="text-center">
                <video class="rounded-3 shadow media-content" controls style="max-height: 400px;">
                  <source src="{{ $a->media_url }}" type="video/mp4">
                  Votre navigateur ne supporte pas la lecture vidéo.
                </video>
              </div>
            @endif
          </div>
        @endif

        {{-- Content --}}
        @if($a->content_html)
          <div class="announcement-content mt-4">
            {!! $a->content_html !!}
          </div>
        @endif
      </div>
    </div>
  @endforeach

  {{-- Articles Grid Header --}}
  <div class="d-flex justify-content-between align-items-center mb-4" id="articles-grid">
    <h2 class="h3 fw-bold">
      <i class="bi bi-newspaper me-2"></i>Publications récentes
    </h2>
    <div class="d-flex gap-2">
      <div class="dropdown">
        <button class="btn btn-outline-primary btn-sm hover-lift dropdown-toggle" type="button" 
                data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-funnel me-1"></i>Trier
        </button>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="{{ route('articles.index', ['sort' => 'newest']) }}">Plus récent</a></li>
          <li><a class="dropdown-item" href="{{ route('articles.index', ['sort' => 'popular']) }}">Plus populaire</a></li>
          <li><a class="dropdown-item" href="{{ route('articles.index', ['sort' => 'videos']) }}">Vidéos seulement</a></li>
        </ul>
      </div>
    </div>
  </div>

  {{-- No Articles Message --}}
  @if($articles->count() === 0)
    <div class="empty-state card border-0 shadow-lg mb-5">
      <div class="card-body text-center py-5">
        <div class="empty-icon mb-4">
          <i class="bi bi-inbox display-1 text-muted"></i>
        </div>
        <h3 class="text-muted mb-3">Aucune publication trouvée</h3>
        <p class="text-muted mb-4">Il n'y a aucune publication correspondant à votre recherche pour le moment.</p>
        @auth
          @if($canPublish ?? false)
            <a href="{{ route('articles.create') }}" class="btn btn-primary btn-lg hover-lift">
              <i class="bi bi-plus-circle me-2"></i>Créer la première publication
            </a>
          @endif
        @else
          <a href="{{ route('register') }}" class="btn btn-primary btn-lg hover-lift">
            <i class="bi bi-person-plus me-2"></i>Rejoindre pour créer
          </a>
        @endauth
      </div>
    </div>
  @endif

  {{-- Articles Grid --}}
  <div class="row g-4" id="articles-container">
    @foreach($articles as $article)
      @include('articles.article_card', ['article' => $article])
    @endforeach
  </div>

  {{-- Loading Spinner --}}
  <div id="loading-spinner" class="text-center py-5 d-none">
    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
      <span class="visually-hidden">Chargement...</span>
    </div>
    <p class="text-muted mt-3">Chargement des publications...</p>
  </div>

  {{-- End of Content Message --}}
  <div id="end-of-content" class="text-center py-5 d-none">
    <div class="end-content-icon mb-3">
      <i class="bi bi-check-circle display-4 text-success"></i>
    </div>
    <h4 class="text-muted">Vous avez tout vu !</h4>
    <p class="text-muted">Découvrez plus de contenu en créant votre propre publication.</p>
    <a href="{{ route('articles.create') }}" class="btn btn-primary hover-lift">
      <i class="bi bi-plus-circle me-2"></i>Créer une publication
    </a>
  </div>

</div>

{{-- Suggestion Modal --}}
<div class="modal fade" id="suggestionModal" tabindex="-1" aria-labelledby="suggestionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-gradient-primary text-white">
        <h5 class="modal-title" id="suggestionModalLabel">
          <i class="bi bi-lightbulb me-2"></i>Partager une suggestion
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <form method="POST" action="{{ route('suggestions.store') }}" id="suggestionForm">
          @csrf
          <div class="mb-3">
            <label class="form-label">Objet</label>
            <input class="form-control" name="subject" value="{{ old('subject') }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Message</label>
            <textarea class="form-control" name="message" placeholder="Décrivez votre idée en détail..." rows="6" required>{{ old('message') }}</textarea>
          </div>
          <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            Votre suggestion sera examinée par notre équipe. Merci pour votre contribution !
          </div>
          <button type="submit" class="btn btn-primary">Envoyer</button>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- Success Toast --}}
<div class="toast-container position-fixed top-0 end-0 p-3">
  <div id="successToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header bg-success text-white">
      <i class="bi bi-check-circle-fill me-2"></i>
      <strong class="me-auto">Succès</strong>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body bg-light">
      Votre suggestion a été envoyée avec succès !
    </div>
  </div>
</div>

<style>
/* ===== VARIABLES & GLOBAL STYLES ===== */
:root {
  --primary-color: #4361ee;
  --secondary-color: #3f37c9;
  --accent-color: #ff7b00;
  --accent-light: #ff9e33;
  --dark-color: #1a1a2e;
  --light-color: #f8f9fa;
  --text-dark: #2d3748;
  --text-light: #718096;
  --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
  --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
  --shadow-md: 0 8px 24px rgba(0, 0, 0, 0.12);
  --shadow-lg: 0 16px 40px rgba(0, 0, 0, 0.15);
}

.brand-name {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  font-weight: 800;
  letter-spacing: -0.5px;
}

.text-primary {
  color: var(--primary-color) !important;
}

.text-accent {
  color: var(--accent-color) !important;
}

.bg-gradient-primary {
  background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)) !important;
}

.bg-accent {
  background-color: var(--accent-color) !important;
}

/* ===== FLOATING ACTIONS ===== */
.floating-actions {
  position: fixed;
  bottom: 2rem;
  right: 2rem;
  z-index: 1000;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.floating-btn-group {
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.floating-actions .btn {
  border-radius: 50px;
  padding: 1rem 1.5rem;
  box-shadow: var(--shadow-lg);
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  transition: var(--transition);
  position: relative;
  z-index: 2;
}

.create-btn {
  animation: pulse 2s ease-in-out infinite;
  background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
  border: none;
}

.suggestion-btn {
  background: linear-gradient(135deg, var(--accent-color), var(--accent-light));
  border: none;
}

.btn-tooltip {
  position: absolute;
  top: -40px;
  background: rgba(0, 0, 0, 0.8);
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 8px;
  font-size: 0.8rem;
  white-space: nowrap;
  opacity: 0;
  transform: translateY(10px);
  transition: var(--transition);
  pointer-events: none;
  z-index: 1;
}

.floating-btn-group:hover .btn-tooltip {
  opacity: 1;
  transform: translateY(0);
}

@keyframes pulse {
  0%, 100% { 
    transform: scale(1) translateY(0);
    box-shadow: var(--shadow-lg);
  }
  50% { 
    transform: scale(1.05) translateY(-5px);
    box-shadow: 0 20px 50px rgba(67, 97, 238, 0.4);
  }
}

/* ===== HERO SECTION ===== */
.hero-section {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  border-radius: 20px;
  padding: 3rem 2rem;
  margin-top: 1rem;
}

.hero-stats {
  border-radius: 15px;
}

.stat-number {
  font-size: 2rem;
}

.stat-label {
  opacity: 0.9;
}

/* ===== SEARCH SECTION ===== */
.search-card {
  background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
  border-left: 4px solid var(--primary-color) !important;
  border-radius: 15px;
}

.form-floating .form-control {
  border-radius: 12px;
  height: calc(3.5rem + 2px);
  padding: 1rem 1.5rem;
}

/* ===== ARTICLE CARDS ===== */
.article-card {
  border-radius: 15px;
  overflow: hidden;
  transition: var(--transition);
  animation: slideUp 0.6s ease-out;
}

.article-card.animate-in {
  animation: slideInUp 0.6s ease-out;
}

.hover-scale {
  transition: var(--transition);
}

.hover-scale:hover {
  transform: translateY(-8px) scale(1.02);
}

.hover-lift {
  transition: var(--transition);
}

.hover-lift:hover {
  transform: translateY(-2px);
}

.hover-primary:hover {
  color: var(--primary-color) !important;
}

.hover-accent:hover {
  color: var(--accent-color) !important;
}

/* Enhanced Media Section */
.card-media {
  height: 320px;
  background: #f8f9fa;
  position: relative;
  overflow: hidden;
  cursor: pointer;
}

.media-fullscreen {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.95);
  z-index: 2000;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}

.media-fullscreen img,
.media-fullscreen video {
  max-width: 95%;
  max-height: 95%;
  object-fit: contain;
  border-radius: 10px;
}

.fullscreen-controls {
  position: absolute;
  top: 20px;
  right: 20px;
  z-index: 2001;
}

.fullscreen-controls .btn {
  background: rgba(0, 0, 0, 0.7);
  color: white;
  border: none;
  margin-left: 10px;
}

.image-container {
  width: 100%;
  height: 100%;
  position: relative;
}

.article-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.article-card:hover .article-image {
  transform: scale(1.05);
}

/* Enhanced Video Styles */
.video-container {
  position: relative;
  width: 100%;
  height: 100%;
  background: #000;
}

.auto-play-video {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 0;
}

.video-controls {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  background: linear-gradient(transparent, rgba(0, 0, 0, 0.7));
  padding: 20px 15px 10px;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.video-container:hover .video-controls {
  opacity: 1;
}

.video-progress {
  width: 100%;
  height: 4px;
  background: rgba(255, 255, 255, 0.3);
  border-radius: 2px;
  margin-bottom: 10px;
  cursor: pointer;
}

.video-progress-bar {
  height: 100%;
  background: var(--accent-color);
  border-radius: 2px;
  width: 0%;
}

.video-control-buttons {
  display: flex;
  align-items: center;
  gap: 15px;
}

.video-control-buttons .btn {
  background: none;
  border: none;
  color: white;
  padding: 5px;
  font-size: 1.2rem;
}

.volume-control {
  display: flex;
  align-items: center;
  gap: 8px;
  flex: 1;
}

.volume-slider {
  width: 80px;
  height: 4px;
  background: rgba(255, 255, 255, 0.3);
  border-radius: 2px;
  cursor: pointer;
}

.volume-level {
  height: 100%;
  background: white;
  border-radius: 2px;
  width: 100%;
}

.video-time {
  color: white;
  font-size: 0.9rem;
  margin-left: auto;
}

.play-indicator {
  position: absolute;
  bottom: 1rem;
  left: 1rem;
  background: rgba(0, 0, 0, 0.7);
  color: white;
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  font-size: 0.75rem;
  display: flex;
  align-items: center;
  gap: 0.25rem;
}

.video-thumbnail {
  width: 100%;
  height: 100%;
  position: relative;
  cursor: pointer;
}

.video-play-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.3);
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 1;
  transition: opacity 0.3s ease;
}

.video-thumbnail:hover .video-play-overlay {
  opacity: 0.8;
}

.play-icon {
  width: 60px;
  height: 60px;
  background: rgba(255, 123, 0, 0.9);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 1.5rem;
}

.video-duration-badge {
  position: absolute;
  bottom: 1rem;
  right: 1rem;
}

.article-image-placeholder {
  width: 100%;
  height: 100%;
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--text-light);
}

.placeholder-content {
  text-align: center;
}

.placeholder-content i {
  font-size: 3rem;
  margin-bottom: 0.5rem;
  display: block;
}

.placeholder-text {
  font-size: 0.9rem;
  font-weight: 500;
}

/* ===== TYPOGRAPHY UTILITIES ===== */
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* ===== AVATAR STYLES ===== */
.avatar-xs {
  width: 32px;
  height: 32px;
}

.avatar-sm {
  width: 40px;
  height: 40px;
}

.avatar-placeholder {
  font-weight: 600;
  font-size: 0.875rem;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* ===== ANIMATIONS ===== */
.animate-fade-in {
  animation: fadeIn 0.6s ease-in-out;
}

.animate-slide-up {
  animation: slideUp 0.6s ease-out;
}

.animate-scale-in {
  animation: scaleIn 0.5s ease-out;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes slideUp {
  from { 
    opacity: 0;
    transform: translateY(30px);
  }
  to { 
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes slideInUp {
  from { 
    opacity: 0;
    transform: translateY(50px) scale(0.9);
  }
  to { 
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

@keyframes scaleIn {
  from { 
    opacity: 0;
    transform: scale(0.9);
  }
  to { 
    opacity: 1;
    transform: scale(1);
  }
}

/* ===== LOADING SPINNER ===== */
.spinner-border {
  animation: spinner-border 0.75s linear infinite;
}

.end-content-icon {
  animation: bounce 2s infinite;
}

@keyframes bounce {
  0%, 20%, 50%, 80%, 100% {
    transform: translateY(0);
  }
  40% {
    transform: translateY(-10px);
  }
  60% {
    transform: translateY(-5px);
  }
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 768px) {
  .container-fluid {
    padding-left: 1rem;
    padding-right: 1rem;
  }
  
  .hero-section {
    padding: 2rem 1rem;
    border-radius: 15px;
    margin-top: 0.5rem;
  }
  
  .floating-actions {
    bottom: 1.5rem;
    right: 1.5rem;
  }
  
  .floating-actions .btn {
    padding: 0.875rem 1.25rem;
    font-size: 0.9rem;
  }
  
  .floating-actions .btn-text {
    display: none;
  }
  
  .card-media {
    height: 280px;
  }
  
  .stat-number {
    font-size: 1.5rem;
  }
  
  .play-icon {
    width: 50px;
    height: 50px;
    font-size: 1.2rem;
  }

  .video-controls {
    padding: 15px 10px 8px;
  }

  .video-control-buttons .btn {
    font-size: 1rem;
    padding: 3px;
  }

  .volume-control {
    display: none;
  }
}

@media (max-width: 576px) {
  .hero-section h1 {
    font-size: 2rem !important;
  }
  
  .card-media {
    height: 240px;
  }
  
  .article-stats {
    font-size: 0.8rem;
  }
  
  .search-card .card-body {
    padding: 1.5rem;
  }

  .floating-actions {
    bottom: 1rem;
    right: 1rem;
  }
  
  .floating-actions .btn {
    width: 50px;
    height: 50px;
    padding: 0;
    justify-content: center;
  }

  .brand-name {
    font-size: 2.5rem;
  }
}

@media (max-width: 400px) {
  .hero-section {
    padding: 1.5rem 1rem;
  }

  .floating-actions {
    bottom: 0.5rem;
    right: 0.5rem;
  }

  .card-media {
    height: 200px;
  }
}

/* ===== EMPTY STATE ===== */
.empty-state {
  border-radius: 15px;
}

.empty-icon {
  animation: bounce 2s infinite;
}

/* ===== ANNOUNCEMENT CARDS ===== */
.announcement-card {
  border-radius: 15px;
  transition: var(--transition);
}

.announcement-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 35px rgba(255, 193, 7, 0.15) !important;
}

.media-content {
  border-radius: 12px;
  box-shadow: var(--shadow-sm);
}

.announcement-content {
  font-size: 1.05rem;
  line-height: 1.6;
}

/* ===== CUSTOM SCROLLBAR ===== */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb {
  background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
  border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(135deg, var(--secondary-color), var(--accent-light));
}

/* ===== SUGGESTION MODAL ===== */
.modal-content {
  border-radius: 15px;
  overflow: hidden;
}

.modal-header {
  border-bottom: none;
  padding: 1.5rem 1.5rem 0;
}

.modal-body {
  padding: 1.5rem;
}

.modal-footer {
  border-top: none;
  padding: 0 1.5rem 1.5rem;
}

/* ===== TOAST STYLES ===== */
.toast {
  border-radius: 10px;
  box-shadow: var(--shadow-lg);
}

.toast-header {
  border-radius: 10px 10px 0 0;
}
</style>

<script>
// Infinite Scroll Variables
let isLoading = false;
let hasMore = true;
let offset = {{ $articles->count() }};
const loadThreshold = 300; // pixels from bottom to trigger load

// Video Observers
const videoObservers = new Map();
const fullscreenMedia = {
  element: null,
  type: null,
  originalParent: null,
  originalIndex: null
};

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
  initializeInfiniteScroll();
  initializeVideoPlayers();
  initializeAnimations();
  initializeFloatingButtons();
  initializeSearch();
  initializeSuggestionForm();
});

// ===== INFINITE SCROLL =====
function initializeInfiniteScroll() {
  window.addEventListener('scroll', throttle(handleScroll, 200));
}

function handleScroll() {
  if (isLoading || !hasMore) return;

  const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
  const windowHeight = window.innerHeight;
  const documentHeight = document.documentElement.scrollHeight;

  if (scrollTop + windowHeight >= documentHeight - loadThreshold) {
    loadMoreArticles();
  }
}

async function loadMoreArticles() {
  if (isLoading) return;
  
  isLoading = true;
  const loadingSpinner = document.getElementById('loading-spinner');
  const articlesContainer = document.getElementById('articles-container');
  
  // Show loading spinner
  loadingSpinner.classList.remove('d-none');
  
  try {
    const response = await fetch(`/articles/load-more?offset=${offset}`);
    const data = await response.json();
    
    if (data.articles.length > 0) {
      // Add new articles with animation
      data.articles.forEach(article => {
        const articleHtml = generateArticleCard(article);
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = articleHtml;
        const articleElement = tempDiv.firstElementChild;
        
        articlesContainer.appendChild(articleElement);
        
        // Animate the new article
        setTimeout(() => {
          articleElement.classList.add('animate-in');
          initializeVideoForArticle(articleElement, article);
          initializeMediaFullscreen(articleElement);
        }, 100);
      });
      
      offset += data.articles.length;
      hasMore = data.hasMore;
    } else {
      hasMore = false;
      showEndOfContent();
    }
  } catch (error) {
    console.error('Error loading more articles:', error);
    showError('Erreur lors du chargement des publications');
  } finally {
    isLoading = false;
    loadingSpinner.classList.add('d-none');
  }
}

function generateArticleCard(article) {
  const hasVideo = article.media && article.media.some(media => media.type === 'video');
  const video = hasVideo ? article.media.find(media => media.type === 'video') : null;
  const imageUrl = article.image_path || (article.media && article.media.find(media => media.type === 'image')?.file_path);
  
  return `
    <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
      <div class="card article-card h-100 border-0 shadow-lg hover-scale" data-article-id="${article.id}">
        <div class="card-media position-relative overflow-hidden">
          ${hasVideo ? `
            <div class="video-container" data-video-src="${video.file_path}">
              <video class="auto-play-video" muted playsinline preload="metadata" poster="${imageUrl || '/img/video-placeholder.jpg'}">
                <source src="${video.file_path}" type="video/mp4">
                Votre navigateur ne supporte pas la lecture vidéo.
              </video>
              <div class="video-controls">
                <div class="video-progress">
                  <div class="video-progress-bar"></div>
                </div>
                <div class="video-control-buttons">
                  <button class="btn play-pause-btn">
                    <i class="bi bi-play-fill"></i>
                  </button>
                  <div class="volume-control">
                    <button class="btn volume-btn">
                      <i class="bi bi-volume-up-fill"></i>
                    </button>
                    <div class="volume-slider">
                      <div class="volume-level" style="width: 100%"></div>
                    </div>
                  </div>
                  <div class="video-time">0:00 / 0:00</div>
                  <button class="btn fullscreen-btn">
                    <i class="bi bi-arrows-fullscreen"></i>
                  </button>
                </div>
              </div>
              <div class="play-indicator">
                <i class="bi bi-play-circle me-1"></i>Vidéo
              </div>
            </div>
          ` : imageUrl ? `
            <div class="image-container media-clickable">
              <img src="${imageUrl}" 
                  class="article-image" 
                  alt="Image article ${article.title}"
                  loading="lazy"
                  data-media-src="${imageUrl}">
              <div class="video-play-overlay d-none">
                <div class="play-icon">
                  <i class="bi bi-arrows-fullscreen"></i>
                </div>
              </div>
            </div>
          ` : `
            <div class="article-image-placeholder">
              <div class="placeholder-content">
                <i class="bi bi-image text-muted"></i>
                <span class="placeholder-text">Aucune image</span>
              </div>
            </div>
          `}
          
          <div class="position-absolute top-0 end-0 m-3">
            <span class="badge bg-success bg-opacity-90 text-white shadow-sm">
              <i class="bi bi-check-circle-fill me-1"></i>Publié
            </span>
          </div>
        </div>

        <div class="card-body d-flex flex-column p-4">
          <h5 class="card-title fw-bold line-clamp-2 mb-2">
            <a href="/articles/${article.id}" class="text-decoration-none text-dark hover-accent">
              ${article.title}
            </a>
          </h5>
          
          <p class="card-text flex-grow-1 text-muted line-clamp-3 mb-3">
            ${article.content ? article.content.substring(0, 120) + (article.content.length > 120 ? '...' : '') : ''}
          </p>

          <div class="d-flex align-items-center mb-3">
            <div class="avatar-sm me-3">
              <div class="avatar-placeholder bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold">
                ${article.user.name ? article.user.name.substring(0, 1).toUpperCase() : 'U'}
              </div>
            </div>
            <div class="flex-grow-1">
              <div class="small fw-medium">
                <a href="/user/${article.user.id}/articles" class="text-decoration-none text-dark hover-primary">
                  ${article.user.name}
                </a>
              </div>
              <div class="small text-muted">
                <i class="bi bi-clock me-1"></i>
                ${new Date(article.published_at || article.created_at).toLocaleDateString('fr-FR', { 
                  day: 'numeric', 
                  month: 'short',
                  year: 'numeric'
                })}
              </div>
            </div>
          </div>

          <div class="article-stats d-flex justify-content-between text-muted small mb-3">
            <div class="d-flex align-items-center">
              <i class="bi bi-eye me-1"></i>
              <span>${article.views_count || 0}</span>
            </div>
            <div class="d-flex align-items-center">
              <i class="bi bi-chat me-1"></i>
              <span>${article.comments_count || 0}</span>
            </div>
            <div class="d-flex align-items-center">
              <i class="bi bi-heart me-1"></i>
              <span>${article.likes_count || 0}</span>
            </div>
          </div>

          <a href="/articles/${article.id}" class="btn btn-outline-primary w-100 mt-auto hover-lift">
            <i class="bi bi-eye me-2"></i>Voir les détails
          </a>
        </div>
      </div>
    </div>
  `;
}

function showEndOfContent() {
  const endOfContent = document.getElementById('end-of-content');
  endOfContent.classList.remove('d-none');
}

function showError(message) {
  // Create and show error toast
  const toast = document.createElement('div');
  toast.className = 'alert alert-danger position-fixed top-0 end-0 m-3';
  toast.style.zIndex = '1060';
  toast.innerHTML = `
    <i class="bi bi-exclamation-triangle me-2"></i>
    ${message}
  `;
  document.body.appendChild(toast);
  
  setTimeout(() => {
    toast.remove();
  }, 5000);
}

// ===== ENHANCED VIDEO PLAYERS =====
function initializeVideoPlayers() {
  document.querySelectorAll('.video-container').forEach(container => {
    initializeVideoPlayer(container);
  });
  
  initializeMediaFullscreen();
}

function initializeVideoPlayer(container) {
  const video = container.querySelector('video');
  const playPauseBtn = container.querySelector('.play-pause-btn');
  const volumeBtn = container.querySelector('.volume-btn');
  const volumeSlider = container.querySelector('.volume-slider');
  const volumeLevel = container.querySelector('.volume-level');
  const progressBar = container.querySelector('.video-progress-bar');
  const videoTime = container.querySelector('.video-time');
  const fullscreenBtn = container.querySelector('.fullscreen-btn');
  
  if (!video) return;

  // Play/Pause
  playPauseBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    togglePlayPause(video, playPauseBtn);
  });

  // Volume control
  volumeBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    toggleMute(video, volumeBtn, volumeLevel);
  });

  volumeSlider.addEventListener('click', (e) => {
    e.stopPropagation();
    const rect = volumeSlider.getBoundingClientRect();
    const percent = (e.clientX - rect.left) / rect.width;
    setVolume(video, percent, volumeBtn, volumeLevel);
  });

  // Progress bar
  container.querySelector('.video-progress').addEventListener('click', (e) => {
    e.stopPropagation();
    const rect = e.currentTarget.getBoundingClientRect();
    const percent = (e.clientX - rect.left) / rect.width;
    video.currentTime = percent * video.duration;
  });

  // Fullscreen
  fullscreenBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    enterFullscreen(container, video);
  });

  // Video events
  video.addEventListener('loadedmetadata', () => {
    updateTimeDisplay(video, videoTime);
  });

  video.addEventListener('timeupdate', () => {
    const percent = (video.currentTime / video.duration) * 100;
    progressBar.style.width = percent + '%';
    updateTimeDisplay(video, videoTime);
  });

  video.addEventListener('ended', () => {
    playPauseBtn.innerHTML = '<i class="bi bi-play-fill"></i>';
  });

  // Auto-play when visible
  initializeVideoObserver(container);
}

function togglePlayPause(video, button) {
  if (video.paused) {
    video.play().then(() => {
      button.innerHTML = '<i class="bi bi-pause-fill"></i>';
    }).catch(e => {
      console.log('Play prevented:', e);
    });
  } else {
    video.pause();
    button.innerHTML = '<i class="bi bi-play-fill"></i>';
  }
}

function toggleMute(video, button, volumeLevel) {
  video.muted = !video.muted;
  button.innerHTML = video.muted ? 
    '<i class="bi bi-volume-mute-fill"></i>' : 
    '<i class="bi bi-volume-up-fill"></i>';
  volumeLevel.style.width = video.muted ? '0%' : (video.volume * 100) + '%';
}

function setVolume(video, percent, button, volumeLevel) {
  video.volume = percent;
  video.muted = percent === 0;
  button.innerHTML = percent === 0 ? 
    '<i class="bi bi-volume-mute-fill"></i>' : 
    '<i class="bi bi-volume-up-fill"></i>';
  volumeLevel.style.width = (percent * 100) + '%';
}

function updateTimeDisplay(video, timeElement) {
  const currentTime = formatTime(video.currentTime);
  const duration = formatTime(video.duration);
  timeElement.textContent = `${currentTime} / ${duration}`;
}

function formatTime(seconds) {
  const mins = Math.floor(seconds / 60);
  const secs = Math.floor(seconds % 60);
  return `${mins}:${secs.toString().padStart(2, '0')}`;
}

function enterFullscreen(container, video) {
  if (!document.fullscreenElement) {
    if (container.requestFullscreen) {
      container.requestFullscreen();
    } else if (container.webkitRequestFullscreen) {
      container.webkitRequestFullscreen();
    } else if (container.msRequestFullscreen) {
      container.msRequestFullscreen();
    }
  } else {
    if (document.exitFullscreen) {
      document.exitFullscreen();
    }
  }
}

function initializeVideoObserver(container) {
  const video = container.querySelector('video');
  if (!video) return;

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          // Video is in viewport - play muted
          if (video.muted) {
            video.play().catch(e => {
              console.log('Autoplay prevented:', e);
            });
          }
          container.classList.add('playing');
        } else {
          // Video is out of viewport
          video.pause();
          container.classList.remove('playing');
        }
      });
    },
    {
      threshold: 0.5,
      rootMargin: '0px 0px -100px 0px'
    }
  );

  observer.observe(container);
  videoObservers.set(container, observer);
}

// ===== MEDIA FULLSCREEN =====
function initializeMediaFullscreen(articleElement = null) {
  const elements = articleElement ? 
    articleElement.querySelectorAll('.media-clickable, .video-container') : 
    document.querySelectorAll('.media-clickable, .video-container');
  
  elements.forEach(element => {
    element.addEventListener('click', function(e) {
      if (e.target.closest('.video-controls') || e.target.closest('.video-control-buttons')) {
        return; // Don't trigger fullscreen if clicking controls
      }
      
      if (this.classList.contains('video-container')) {
        // Video fullscreen
        const video = this.querySelector('video');
        openMediaFullscreen(video, 'video');
      } else {
        // Image fullscreen
        const img = this.querySelector('img');
        openMediaFullscreen(img, 'image');
      }
    });
  });
}

function openMediaFullscreen(mediaElement, type) {
  // Create fullscreen overlay
  const overlay = document.createElement('div');
  overlay.className = 'media-fullscreen';
  overlay.innerHTML = `
    <div class="fullscreen-controls">
      <button class="btn btn-sm close-fullscreen">
        <i class="bi bi-x-lg"></i>
      </button>
    </div>
  `;
  
  // Clone the media element
  const mediaClone = mediaElement.cloneNode(true);
  mediaClone.style.maxWidth = '95%';
  mediaClone.style.maxHeight = '95%';
  mediaClone.style.objectFit = 'contain';
  
  if (type === 'video') {
    mediaClone.controls = true;
    mediaClone.autoplay = true;
    mediaClone.currentTime = mediaElement.currentTime;
  }
  
  overlay.appendChild(mediaClone);
  document.body.appendChild(overlay);
  
  // Close fullscreen
  const closeBtn = overlay.querySelector('.close-fullscreen');
  closeBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    closeMediaFullscreen(overlay, mediaElement, mediaClone, type);
  });
  
  overlay.addEventListener('click', (e) => {
    if (e.target === overlay) {
      closeMediaFullscreen(overlay, mediaElement, mediaClone, type);
    }
  });
  
  // Escape key to close
  const escapeHandler = (e) => {
    if (e.key === 'Escape') {
      closeMediaFullscreen(overlay, mediaElement, mediaClone, type);
      document.removeEventListener('keydown', escapeHandler);
    }
  };
  document.addEventListener('keydown', escapeHandler);
}

function closeMediaFullscreen(overlay, originalMedia, clonedMedia, type) {
  if (type === 'video') {
    originalMedia.currentTime = clonedMedia.currentTime;
  }
  overlay.remove();
}

// ===== VIDEO INITIALIZATION FOR NEW ARTICLES =====
function initializeVideoForArticle(articleElement, article) {
  const videoContainer = articleElement.querySelector('.video-container');
  if (videoContainer) {
    initializeVideoPlayer(videoContainer);
  }
  initializeMediaFullscreen(articleElement);
}

// ===== ANIMATIONS =====
function initializeAnimations() {
  const observerOptions = {
    threshold: 0.1,
    rootMargin: '50px'
  };

  const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = '1';
        entry.target.style.transform = 'translateY(0) scale(1)';
      }
    });
  }, observerOptions);

  document.querySelectorAll('.animate-fade-in, .animate-slide-up, .animate-scale-in, .article-card').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(20px) scale(0.95)';
    el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(el);
  });
}

// ===== FLOATING BUTTONS =====
function initializeFloatingButtons() {
  let lastScrollTop = 0;
  const floatingActions = document.querySelector('.floating-actions');
  
  if (floatingActions) {
    window.addEventListener('scroll', throttle(function() {
      const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
      
      if (scrollTop > lastScrollTop && scrollTop > 100) {
        // Scrolling down
        floatingActions.style.transform = 'translateY(100px)';
        floatingActions.style.opacity = '0';
      } else {
        // Scrolling up
        floatingActions.style.transform = 'translateY(0)';
        floatingActions.style.opacity = '1';
      }
      
      lastScrollTop = scrollTop;
    }, 100));
  }
}

// ===== SEARCH FUNCTIONALITY =====
function initializeSearch() {
  const searchForm = document.getElementById('searchForm');
  const searchInput = document.getElementById('searchInput');
  
  if (searchInput) {
    searchInput.addEventListener('input', throttle(function() {
      // You can add real-time search here if needed
    }, 300));
  }
}

// ===== SUGGESTION FORM =====
function initializeSuggestionForm() {
  const suggestionForm = document.getElementById('suggestionForm');
  if (suggestionForm) {
    suggestionForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      
      fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Show success message
          const modal = bootstrap.Modal.getInstance(document.getElementById('suggestionModal'));
          modal.hide();
          
          // Show success toast
          const toast = new bootstrap.Toast(document.getElementById('successToast'));
          toast.show();
          
          this.reset();
        } else {
          showError('Erreur lors de l\'envoi de la suggestion');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showError('Erreur lors de l\'envoi de la suggestion');
      });
    });
  }
}

// ===== UTILITY FUNCTIONS =====
function throttle(func, limit) {
  let inThrottle;
  return function() {
    const args = arguments;
    const context = this;
    if (!inThrottle) {
      func.apply(context, args);
      inThrottle = true;
      setTimeout(() => inThrottle = false, limit);
    }
  }
}

function scrollToTop() {
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Initialize tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
});
</script>
@endsection