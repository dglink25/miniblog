@extends('layouts.app')

@php
  use Illuminate\Support\Str;
  use Illuminate\Support\Arr;
@endphp

@section('content')
<div class="container-fluid px-3 px-md-4 px-lg-5">
  {{-- Header Section --}}
  <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between mb-4">
    <div class="mb-3 mb-md-0">
      <h1 class="h2 fw-bold text-gradient-primary mb-2">Toutes les publications</h1>
      <p class="text-muted mb-0">Découvrez les dernières publications de notre communauté</p>
    </div>
    
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
        <a class="btn btn-primary btn-lg shadow-sm hover-lift" href="{{ route('articles.create') }}">
          <i class="bi bi-plus-circle me-2"></i>Nouvelle publication
        </a>
      @endif
    @endauth
  </div>

  {{-- Search Section --}}
  <div class="card search-card border-0 shadow-sm mb-4">
    <div class="card-body p-3 p-md-4">
      <form method="GET" action="{{ route('articles.index') }}">
        <div class="row g-2 align-items-center">
          <div class="col-12 col-md-8 col-lg-9">
            <div class="input-group input-group-lg">
              <span class="input-group-text bg-transparent border-end-0">
                <i class="bi bi-search text-muted"></i>
              </span>
              <input type="text" name="q" value="{{ $q ?? '' }}" 
                     class="form-control border-start-0 ps-0" 
                     placeholder="Rechercher une publication, un auteur..." 
                     oninput="filterCards(this.value)">
            </div>
          </div>
          <div class="col-12 col-md-4 col-lg-3">
            <button class="btn btn-outline-primary w-100 h-100 py-2" type="submit">
              <i class="bi bi-sliders me-2"></i>Filtrer
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>

  {{-- Pinned Announcements --}}
  @foreach(($annonces ?? []) as $a)
    <div class="card announcement-card border-warning shadow-hover mb-4 animate-fade-in">
      <div class="card-header bg-warning bg-opacity-10 border-warning">
        <div class="d-flex align-items-center">
          <i class="bi bi-megaphone-fill text-warning me-2 fs-5"></i>
          <strong class="text-warning">Annonce importante</strong>
          @if($a->is_pinned) 
            <span class="badge bg-warning text-dark ms-2">
              <i class="bi bi-pin-angle-fill me-1"></i>Épinglée
            </span>
          @endif
          <span class="ms-auto small text-muted">
            {{ $a->published_at?->diffForHumans() }}
          </span>
        </div>
      </div>
      <div class="card-body">
        <h4 class="card-title text-center mb-3 fw-bold">{{ $a->title }}</h4>

        {{-- Media Content --}}
        @if($a->media_url)
          <div class="media-container mb-3">
            {{-- Image --}}
            @if(Str::endsWith($a->media_url, ['.jpg','.jpeg','.png','.gif']))
              <div class="text-center">
                <img src="{{ $a->media_url }}" class="img-fluid rounded-3 shadow-sm media-content" alt="Image annonce" style="max-height: 400px;">
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
                  <iframe src="https://www.youtube.com/embed/{{ $videoId }}" 
                          class="rounded-3 shadow-sm" 
                          allowfullscreen></iframe>
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
              <script async src="https://www.tiktok.com/embed.js"></script>

            {{-- Video --}}
            @elseif(Str::endsWith($a->media_url, ['.mp4','.webm']))
              <div class="text-center">
                <video class="rounded-3 shadow-sm media-content" controls style="max-height: 400px;">
                  <source src="{{ $a->media_url }}" type="video/mp4">
                  Votre navigateur ne supporte pas la lecture vidéo.
                </video>
              </div>
            @endif
          </div>
        @endif

        {{-- Content --}}
        @if($a->content_html)
          <div class="announcement-content mt-3">
            {!! $a->content_html !!}
          </div>
        @endif
      </div>
    </div>
  @endforeach

  {{-- No Articles Message --}}
  @if($articles->count() === 0)
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-body text-center py-5">
        <i class="bi bi-inbox display-1 text-muted mb-3"></i>
        <h3 class="text-muted mb-3">Aucune publication trouvée</h3>
        <p class="text-muted mb-4">Il n'y a aucune publication correspondant à votre recherche pour le moment.</p>
        @auth
          @if($canPublish ?? false)
            <a href="{{ route('articles.create') }}" class="btn btn-primary">
              <i class="bi bi-plus-circle me-2"></i>Créer la première publication
            </a>
          @endif
        @endauth
      </div>
    </div>
  @endif

  {{-- Articles Grid --}}
  <div class="row g-4" id="articles-grid">
    @foreach($articles as $article)
      @if($article->status === 'validated')
      <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
        <div class="card article-card h-100 border-0 shadow-sm hover-lift">
          {{-- Article Image --}}
          <div class="card-img-container position-relative overflow-hidden">
            @php
              $thumb = $article->media->firstWhere('type','image') ?? null;
            @endphp
            
            @if ($thumb)
              <img src="{{ asset('storage/'.$thumb->file_path) }}" 
                   class="card-img-top article-image" 
                   alt="Image article {{ $article->title }}"
                   loading="lazy">
            @elseif ($article->image_path)
              <img src="{{ asset('storage/'.$article->image_path) }}" 
                   class="card-img-top article-image" 
                   alt="Image article {{ $article->title }}"
                   loading="lazy">
            @else
              <div class="card-img-top article-image-placeholder d-flex align-items-center justify-content-center bg-light">
                <i class="bi bi-image text-muted display-4"></i>
              </div>
            @endif
            
            {{-- Status Badge --}}
            <div class="position-absolute top-0 end-0 m-3">
              <span class="badge bg-success bg-opacity-90 text-white">
                <i class="bi bi-check-circle-fill me-1"></i>Validé
              </span>
            </div>
          </div>

          <div class="card-body d-flex flex-column p-3">
            {{-- Title --}}
            <h5 class="card-title fw-bold line-clamp-2 mb-2">{{ $article->title }}</h5>
            
            {{-- Excerpt --}}
            <p class="card-text flex-grow-1 text-muted line-clamp-3 mb-3">
              {{ \Illuminate\Support\Str::limit(strip_tags($article->content), 120) }}
            </p>

            {{-- Author and Date --}}
            <div class="d-flex align-items-center mb-3">
              <div class="avatar-sm me-2">
                <div class="avatar-placeholder bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                  {{ Str::substr($article->user->name, 0, 1) }}
                </div>
              </div>
              <div class="flex-grow-1">
                <div class="small fw-medium">
                  <a href="{{ route('user.article', $article->user->id) }}" class="text-decoration-none text-dark">
                    {{ $article->user->name }}
                  </a>
                </div>
                <div class="small text-muted">
                  <i class="bi bi-clock me-1"></i>
                  {{ optional($article->published_at ?? $article->created_at)->diffForHumans() }}
                  @if($article->updated_at->gt($article->created_at))
                    <span class="ms-1" title="Modifié {{ $article->updated_at->diffForHumans() }}">
                      <i class="bi bi-pencil-square"></i>
                    </span>
                  @endif
                </div>
              </div>
            </div>

            {{-- Action Button --}}
            <a href="{{ route('articles.show', $article) }}" class="btn btn-outline-primary w-100 mt-auto">
              <i class="bi bi-eye me-2"></i>Voir les détails
            </a>
          </div>
        </div>
      </div>
      @endif
    @endforeach
  </div>

  {{-- Pagination --}}
  @if($articles->hasPages())
    <div class="mt-5">
      <nav aria-label="Pagination des articles">
        {{ $articles->links() }}
      </nav>
    </div>
  @endif
</div>

<style>
  .text-gradient-primary {
    background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .search-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-left: 4px solid #4361ee !important;
  }

  .announcement-card {
    transition: all 0.3s ease;
    border-left: 4px solid #ffc107 !important;
  }

  .announcement-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 193, 7, 0.15) !important;
  }

  .article-card {
    transition: all 0.3s ease;
    border-radius: 12px;
    overflow: hidden;
  }

  .article-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15) !important;
  }

  .hover-lift {
    transition: all 0.3s ease;
  }

  .hover-lift:hover {
    transform: translateY(-2px);
  }

  .shadow-hover {
    transition: box-shadow 0.3s ease;
  }

  .shadow-hover:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
  }

  .card-img-container {
    height: 200px;
    overflow: hidden;
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

  .article-image-placeholder {
    height: 200px;
    border-radius: 12px 12px 0 0;
  }

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

  .avatar-placeholder {
    font-weight: 600;
    font-size: 0.875rem;
  }

  .media-content {
    max-width: 100%;
    border-radius: 12px;
  }

  .announcement-content {
    font-size: 1.05rem;
    line-height: 1.6;
  }

  .animate-fade-in {
    animation: fadeIn 0.6s ease-in-out;
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* Responsive adjustments */
  @media (max-width: 576px) {
    .card-img-container {
      height: 180px;
    }
    
    .article-image-placeholder {
      height: 180px;
    }
    
    .container-fluid {
      padding-left: 1rem;
      padding-right: 1rem;
    }
  }

  @media (min-width: 577px) and (max-width: 768px) {
    .card-img-container {
      height: 220px;
    }
    
    .article-image-placeholder {
      height: 220px;
    }
  }

  @media (min-width: 769px) and (max-width: 992px) {
    .card-img-container {
      height: 200px;
    }
  }

  @media (min-width: 993px) {
    .card-img-container {
      height: 220px;
    }
  }

  /* Pagination styling */
  .pagination {
    justify-content: center;
  }

  .page-link {
    border: none;
    border-radius: 8px;
    margin: 0 4px;
    color: #4361ee;
    font-weight: 500;
  }

  .page-item.active .page-link {
    background: linear-gradient(135deg, #4361ee, #3a0ca3);
    border: none;
  }

  .page-link:hover {
    background-color: #e9ecef;
    color: #3a0ca3;
  }
</style>

<script>
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
        visibleCount++;
      } else {
        parentCol.style.display = 'none';
        card.style.opacity = '0.5';
      }
    });

    // Add smooth animation
    grid.style.transition = 'all 0.3s ease';
  }

  // Add intersection observer for lazy loading animations
  document.addEventListener('DOMContentLoaded', function() {
    const observerOptions = {
      threshold: 0.1,
      rootMargin: '50px'
    };

    const observer = new IntersectionObserver(function(entries) {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0)';
        }
      });
    }, observerOptions);

    // Observe all article cards for animation
    document.querySelectorAll('.article-card').forEach(card => {
      card.style.opacity = '0';
      card.style.transform = 'translateY(20px)';
      card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
      observer.observe(card);
    });
  });
</script>
@endsection