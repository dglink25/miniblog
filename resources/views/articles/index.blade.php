@extends('layouts.app')

@php
  use Illuminate\Support\Str;
  use Illuminate\Support\Arr;
@endphp

@section('content')
<div class="container-fluid px-2 px-md-3 px-lg-4 py-3">
  {{-- Floating Create Button (Mobile First) --}}
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
      <div class="floating-create-btn">
        <a class="btn btn-primary btn-lg shadow-lg hover-scale" href="{{ route('articles.create') }}">
          <i class="bi bi-plus-lg"></i>
          <span class="btn-text">Créer</span>
        </a>
      </div>
    @endif
  @else
    <div class="floating-create-btn">
      <a class="btn btn-primary btn-lg shadow-lg hover-scale" href="{{ route('login') }}">
        <i class="bi bi-plus-lg"></i>
        <span class="btn-text">Créer</span>
      </a>
    </div>
  @endauth

  {{-- Header Section --}}
  <div class="hero-section mb-5 animate-fade-in">
    <div class="row align-items-center">
      <div class="col-12 col-lg-8">
        <h1 class="display-5 fw-bold text-gradient mb-3">
          Découvrez <span class="text-accent">FlashPost</span>
        </h1>
        <p class="lead text-muted mb-4">
          La plateforme de divertissement et de partage qui vous connecte à votre audience. 
          Partagez vos idées, découvrez du contenu passionnant et interagissez avec la communauté.
        </p>
        <div class="d-flex flex-wrap gap-3">
          <a href="#featured-content" class="btn btn-primary btn-lg hover-lift">
            <i class="bi bi-play-circle me-2"></i>Explorer
          </a>
          <a href="{{ route('articles.create') }}" class="btn btn-outline-primary btn-lg hover-lift">
            <i class="bi bi-lightning me-2"></i>Démarrer gratuitement
          </a>
        </div>
      </div>
      <div class="col-12 col-lg-4 mt-4 mt-lg-0">
        <div class="hero-stats card border-0 bg-gradient-primary text-white shadow-lg">
          <div class="card-body p-4">
            <div class="row text-center">
              <div class="col-4">
                <div class="stat-number h3 fw-bold">{{ $articles->total() }}+</div>
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
        <form method="GET" action="{{ route('articles.index') }}">
          <div class="row g-3 align-items-end">
            <div class="col-12 col-md-8 col-lg-9">
              <div class="form-floating">
                <input type="text" name="q" value="{{ $q ?? '' }}" 
                       class="form-control form-control-lg border-0 shadow-sm" 
                       id="searchInput"
                       placeholder="Rechercher une publication, un auteur..."
                       oninput="filterCards(this.value)">
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

  {{-- Featured Videos Section --}}
  @php
    $featuredVideos = $articles->filter(function($article) {
        return $article->status === 'validated' && $article->media->where('type', 'video')->count() > 0;
    })->take(3);
  @endphp

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
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 fw-bold text-gradient">
      <i class="bi bi-newspaper me-2"></i>Toutes les publications
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
  <div class="row g-4" id="articles-grid">
    @foreach($articles as $article)
      @if($article->status === 'validated')
      <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
        <div class="card article-card h-100 border-0 shadow-lg hover-scale">
          {{-- Article Media --}}
          <div class="card-media position-relative overflow-hidden">
            @php
              $hasVideo = $article->media->where('type', 'video')->count() > 0;
              $thumb = $article->media->firstWhere('type','image') ?? null;
            @endphp
            
            @if ($hasVideo)
              {{-- Video Thumbnail --}}
              @php
                $video = $article->media->where('type', 'video')->first();
              @endphp
              <div class="video-thumbnail position-relative">
                <img src="{{ $article->image_path ?? asset('img/video-placeholder.jpg') }}" 
                     class="article-image"
                     alt="Vidéo {{ $article->title }}"
                     loading="lazy">
                <div class="video-play-overlay">
                  <div class="play-icon">
                    <i class="bi bi-play-fill"></i>
                  </div>
                </div>
                <div class="video-duration-badge">
                  <span class="badge bg-dark bg-opacity-75 text-white">
                    <i class="bi bi-play-circle me-1"></i>Vidéo
                  </span>
                </div>
              </div>
            @elseif ($article->image_path)
              {{-- Image --}}
              <a href="{{ route('articles.show', $article) }}" class="article-image-link">
                <div class="image-container">
                  <img src="{{ $article->image_path }}" 
                      class="article-image" 
                      alt="Image article {{ $article->title }}"
                      loading="lazy">
                </div>
              </a>
            @else
              {{-- Placeholder --}}
              <div class="article-image-placeholder">
                <div class="placeholder-content">
                  <i class="bi bi-image text-muted"></i>
                  <span class="placeholder-text">Aucune image</span>
                </div>
              </div>
            @endif
            
            {{-- Status Badge --}}
            <div class="position-absolute top-0 end-0 m-3">
              <span class="badge bg-success bg-opacity-90 text-white shadow-sm">
                <i class="bi bi-check-circle-fill me-1"></i>Publié
              </span>
            </div>
          </div>

          <div class="card-body d-flex flex-column p-4">
            {{-- Title --}}
            <h5 class="card-title fw-bold line-clamp-2 mb-2">
              <a href="{{ route('articles.show', $article) }}" class="text-decoration-none text-dark hover-accent">
                {{ $article->title }}
              </a>
            </h5>
            
            {{-- Excerpt --}}
            <p class="card-text flex-grow-1 text-muted line-clamp-3 mb-3">
              {{ Str::limit(strip_tags($article->content), 120) }}
            </p>

            {{-- Author and Date --}}
            <div class="d-flex align-items-center mb-3">
              <div class="avatar-sm me-3">
                <div class="avatar-placeholder bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold">
                  {{ Str::substr($article->user->name, 0, 1) }}
                </div>
              </div>
              <div class="flex-grow-1">
                <div class="small fw-medium">
                  <a href="{{ route('user.article', $article->user->id) }}" class="text-decoration-none text-dark hover-primary">
                    {{ $article->user->name }}
                  </a>
                </div>
                <div class="small text-muted">
                  <i class="bi bi-clock me-1"></i>
                  {{ optional($article->published_at ?? $article->created_at)->diffForHumans() }}
                  @if($article->updated_at->gt($article->created_at))
                    <span class="ms-2" title="Modifié {{ $article->updated_at->diffForHumans() }}">
                      <i class="bi bi-pencil-square"></i>
                    </span>
                  @endif
                </div>
              </div>
            </div>

            {{-- Stats --}}
            <div class="article-stats d-flex justify-content-between text-muted small mb-3">
              <div class="d-flex align-items-center">
                <i class="bi bi-eye me-1"></i>
                <span>{{ $article->views_count ?? 0 }}</span>
              </div>
              <div class="d-flex align-items-center">
                <i class="bi bi-chat me-1"></i>
                <span>{{ $article->comments_count ?? 0 }}</span>
              </div>
              <div class="d-flex align-items-center">
                <i class="bi bi-heart me-1"></i>
                <span>{{ $article->likes_count ?? 0 }}</span>
              </div>
            </div>

            {{-- Action Button --}}
            <a href="{{ route('articles.show', $article) }}" class="btn btn-outline-primary w-100 mt-auto hover-lift">
              <i class="bi bi-eye me-2"></i>Voir les détails
            </a>
          </div>
        </div>
      </div>
      @endif
    @endforeach
  </div>

  {{-- Pagination --}}
  @if ($articles->hasPages())
    <div class="pagination-section mt-5">
      <nav aria-label="Pagination des articles">
        <ul class="pagination justify-content-center">
          {{-- Previous Page Link --}}
          @if ($articles->onFirstPage())
            <li class="page-item disabled">
              <span class="page-link">
                <i class="bi bi-chevron-left me-1"></i> Précédent
              </span>
            </li>
          @else
            <li class="page-item">
              <a class="page-link hover-lift" href="{{ $articles->previousPageUrl() }}">
                <i class="bi bi-chevron-left me-1"></i> Précédent
              </a>
            </li>
          @endif

          {{-- Pagination Elements --}}
          @foreach ($articles->links()->elements[0] ?? [] as $page => $url)
            @if ($page == $articles->currentPage())
              <li class="page-item active">
                <span class="page-link">{{ $page }}</span>
              </li>
            @else
              <li class="page-item">
                <a class="page-link hover-lift" href="{{ $url }}">{{ $page }}</a>
              </li>
            @endif
          @endforeach

          {{-- Next Page Link --}}
          @if ($articles->hasMorePages())
            <li class="page-item">
              <a class="page-link hover-lift" href="{{ $articles->nextPageUrl() }}">
                Suivant <i class="bi bi-chevron-right ms-1"></i>
              </a>
            </li>
          @else
            <li class="page-item disabled">
              <span class="page-link">
                Suivant <i class="bi bi-chevron-right ms-1"></i>
              </span>
            </li>
          @endif
        </ul>
      </nav>
    </div>
  @endif

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

.text-gradient {
  background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
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

/* ===== FLOATING CREATE BUTTON ===== */
.floating-create-btn {
  position: fixed;
  bottom: 2rem;
  right: 2rem;
  z-index: 1000;
}

.floating-create-btn .btn {
  border-radius: 50px;
  padding: 1rem 1.5rem;
  box-shadow: var(--shadow-lg);
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  animation: float 3s ease-in-out infinite;
}

@keyframes float {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-10px); }
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

/* ===== FEATURED VIDEOS ===== */
.video-feature-card {
  border-radius: 15px;
  overflow: hidden;
  transition: var(--transition);
}

.video-wrapper {
  position: relative;
  width: 100%;
  padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
  background: #000;
}

.featured-video {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 0;
}

.video-overlay {
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

.video-wrapper:hover .video-overlay {
  opacity: 0;
}

.play-button {
  width: 80px;
  height: 80px;
  background: rgba(255, 123, 0, 0.9);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 2rem;
  transition: var(--transition);
}

.video-wrapper:hover .play-button {
  transform: scale(1.1);
}

/* ===== ARTICLE CARDS ===== */
.article-card {
  border-radius: 15px;
  overflow: hidden;
  transition: var(--transition);
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

.card-media {
  height: 220px;
  background: #f8f9fa;
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

/* ===== PAGINATION ===== */
.pagination .page-link {
  border: none;
  border-radius: 10px;
  margin: 0 4px;
  color: var(--primary-color);
  font-weight: 500;
  padding: 0.75rem 1rem;
  transition: var(--transition);
}

.pagination .page-item.active .page-link {
  background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
  border: none;
  box-shadow: var(--shadow-sm);
}

.pagination .page-link:hover {
  background-color: rgba(67, 97, 238, 0.1);
  color: var(--secondary-color);
  transform: translateY(-2px);
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
  
  .floating-create-btn {
    bottom: 1.5rem;
    right: 1.5rem;
  }
  
  .floating-create-btn .btn {
    padding: 0.875rem 1.25rem;
    font-size: 0.9rem;
  }
  
  .floating-create-btn .btn-text {
    display: none;
  }
  
  .card-media {
    height: 200px;
  }
  
  .stat-number {
    font-size: 1.5rem;
  }
  
  .video-feature-card {
    margin-bottom: 1.5rem;
  }
  
  .play-button {
    width: 60px;
    height: 60px;
    font-size: 1.5rem;
  }
}

@media (max-width: 576px) {
  .hero-section h1 {
    font-size: 2rem !important;
  }
  
  .card-media {
    height: 180px;
  }
  
  .article-stats {
    font-size: 0.8rem;
  }
  
  .pagination .page-link {
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
  }
  
  .search-card .card-body {
    padding: 1.5rem;
  }
}

@media (max-width: 400px) {
  .floating-create-btn {
    bottom: 1rem;
    right: 1rem;
  }
  
  .floating-create-btn .btn {
    width: 50px;
    height: 50px;
    padding: 0;
    justify-content: center;
  }
  
  .hero-section {
    padding: 1.5rem 1rem;
  }
}

/* ===== EMPTY STATE ===== */
.empty-state {
  border-radius: 15px;
}

.empty-icon {
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
</style>

<script>
// Filter cards based on search input
function filterCards(q) {
  const cards = document.querySelectorAll('.article-card');
  const term = q.toLowerCase().trim();
  const grid = document.getElementById('articles-grid');
  
  let visibleCount = 0;
  
  cards.forEach(card => {
    const cardText = card.innerText.toLowerCase();
    const parentCol = card.closest('.col-12, .col-sm-6, .col-lg-4, .col-xl-3');
    
    if (term === '' || cardText.includes(term)) {
      parentCol.style.display = '';
      card.style.opacity = '1';
      card.style.transform = 'scale(1)';
      visibleCount++;
    } else {
      parentCol.style.display = 'none';
      card.style.opacity = '0.5';
      card.style.transform = 'scale(0.95)';
    }
  });

  // Show empty state if no results
  const emptyState = document.querySelector('.empty-state');
  if (emptyState) {
    emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
  }
}

// Video play functionality
document.addEventListener('DOMContentLoaded', function() {
  // Initialize video players
  const videos = document.querySelectorAll('.featured-video');
  videos.forEach(video => {
    video.addEventListener('click', function() {
      if (this.paused) {
        this.play();
        this.parentElement.querySelector('.video-overlay').style.opacity = '0';
      } else {
        this.pause();
        this.parentElement.querySelector('.video-overlay').style.opacity = '1';
      }
    });
  });
  
  // Video thumbnail click handlers
  const videoThumbnails = document.querySelectorAll('.video-thumbnail');
  videoThumbnails.forEach(thumbnail => {
    thumbnail.addEventListener('click', function() {
      const articleUrl = this.closest('.article-card').querySelector('.card-title a').href;
      window.location.href = articleUrl;
    });
  });
  
  // Intersection Observer for animations
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
  
  // Observe all animated elements
  document.querySelectorAll('.animate-fade-in, .animate-slide-up, .animate-scale-in, .article-card').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(20px) scale(0.95)';
    el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(el);
  });
  
  // Search input focus effect
  const searchInput = document.getElementById('searchInput');
  if (searchInput) {
    searchInput.addEventListener('focus', function() {
      this.parentElement.classList.add('focused');
    });
    
    searchInput.addEventListener('blur', function() {
      this.parentElement.classList.remove('focused');
    });
  }
  
  // Auto-hide floating button on scroll
  let lastScrollTop = 0;
  const floatingBtn = document.querySelector('.floating-create-btn');
  
  if (floatingBtn) {
    window.addEventListener('scroll', function() {
      const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
      
      if (scrollTop > lastScrollTop && scrollTop > 100) {
        // Scrolling down
        floatingBtn.style.transform = 'translateY(100px)';
        floatingBtn.style.opacity = '0';
      } else {
        // Scrolling up
        floatingBtn.style.transform = 'translateY(0)';
        floatingBtn.style.opacity = '1';
      }
      
      lastScrollTop = scrollTop;
    }, { passive: true });
  }
});

// Social sharing with video support
function shareArticle(articleId, hasVideo = false, videoUrl = null, imageUrl = null, title = '', description = '') {
  const url = encodeURIComponent(window.location.href);
  const text = encodeURIComponent(title);
  const desc = encodeURIComponent(description);
  
  if (hasVideo && videoUrl) {
    // Special handling for video content
    if (navigator.share) {
      navigator.share({
        title: title,
        text: description,
        url: window.location.href,
      })
      .catch(console.error);
    } else {
      // Fallback to WhatsApp with video mention
      window.open(`https://wa.me/?text=${text}%0A%0A🎥 ${videoUrl}%0A%0A${url}`, '_blank');
    }
  } else {
    // Standard sharing for non-video content
    if (navigator.share) {
      navigator.share({
        title: title,
        text: description,
        url: window.location.href,
      })
      .catch(console.error);
    } else {
      window.open(`https://wa.me/?text=${text}%0A%0A${url}`, '_blank');
    }
  }
}
</script>
@endsection