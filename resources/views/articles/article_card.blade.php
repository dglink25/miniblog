@php
  use Illuminate\Support\Str;
  $hasVideo = $article->media->where('type', 'video')->count() > 0;
  $video = $hasVideo ? $article->media->where('type', 'video')->first() : null;
  $imageUrl = $article->image_path ?? $article->media->where('type', 'image')->first()?->file_path;
@endphp

<div class="col-12 col-sm-6 col-lg-4 col-xl-3">
  <div class="card article-card h-100 border-0 shadow-lg hover-scale" data-article-id="{{ $article->id }}">
    <div class="card-media position-relative overflow-hidden">
      @if ($hasVideo && $video)
        {{-- Video avec lecture automatique --}}
        <div class="video-container" data-video-src="{{ $video->file_path }}">
          <video class="auto-play-video" muted playsinline preload="metadata" poster="{{ $imageUrl ?? asset('img/video-placeholder.jpg') }}">
            <source src="{{ $video->file_path }}" type="video/mp4">
            Votre navigateur ne supporte pas la lecture vidéo.
          </video>
          <div class="video-overlay">
            <div class="play-icon">
              <i class="bi bi-play-fill"></i>
            </div>
          </div>
          <div class="play-indicator">
            <i class="bi bi-play-circle me-1"></i>Vidéo
          </div>
        </div>
      @elseif ($imageUrl)
        {{-- Image --}}
        <a href="{{ route('articles.show', $article) }}" class="article-image-link">
          <div class="image-container">
            <img src="{{ $imageUrl }}" 
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