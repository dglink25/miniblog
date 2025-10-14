@extends('layouts.app')

@section('content')
<div class="container-fluid px-2 px-md-3 px-lg-5 py-3">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            {{-- Article Header --}}
            <div class="article-header mb-4 animate-fade-in">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-3">
                    <div class="flex-grow-1">
                        <h1 class="article-title fw-bold text-dark mb-2">{{ $article->title }}</h1>
                        
                        {{-- Author Info --}}
                        <div class="d-flex align-items-center flex-wrap gap-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-2">
                                    <div class="avatar-placeholder bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" 
                                         style="width: 40px; height: 40px;">
                                        {{ Str::substr($article->user->name, 0, 1) }}
                                    </div>
                                </div>
                                <div>
                                    <div class="fw-medium">
                                        <a href="{{ route('user.article', $article->user->id) }}" 
                                           class="text-decoration-none text-dark hover-primary">
                                            {{ $article->user->name }}
                                        </a>
                                    </div>
                                    <div class="small text-muted">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ $article->created_at->diffForHumans() }}
                                        @if($article->updated_at->gt($article->created_at))
                                            <span class="ms-2" title="Modifié {{ $article->updated_at->diffForHumans() }}">
                                                <i class="bi bi-pencil-square"></i>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            {{-- User Actions --}}
                            <div class="d-flex align-items-center gap-2">
                                @auth
                                    @include('articles.partials.favorite_button', ['article' => $article])
                                    
                                    @if(auth()->id() !== $article->user->id)
                                        @if(auth()->user()->following?->contains($article->user->id))
                                            <form method="POST" action="{{ route('users.unfollow', $article->user) }}" class="m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-outline-warning btn-sm hover-lift">
                                                    <i class="bi bi-person-dash me-1"></i>Se désabonner
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('users.follow', $article->user) }}" class="m-0">
                                                @csrf
                                                <button class="btn btn-primary btn-sm hover-lift">
                                                    <i class="bi bi-person-plus me-1"></i>S'abonner
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                    
                    {{-- Admin Actions --}}
                    @auth
                        @if(auth()->user()->isAdmin())
                            <form method="POST" action="{{ route('admin.articles.destroy',$article) }}" 
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette publication ?');"
                                  class="m-0">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm hover-lift">
                                    <i class="bi bi-trash me-1"></i>Supprimer
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>

            {{-- Main Article Image --}}
            @if($article->image_path)
                <div class="main-image-container mb-4 rounded-3 overflow-hidden shadow-lg animate-slide-up">
                    <div class="image-ratio-container">
                        <img src="{{ $article->image_path }}" 
                             class="article-main-image cursor-zoom" 
                             alt="Image article {{ $article->title }}"
                             loading="eager"
                             onclick="openLightbox('{{ $article->image_path }}', '{{ $article->title }}')">
                    </div>
                </div>
            @endif

            {{-- Article Content --}}
            <div class="article-content mb-5">
                <div class="content-body text-dark mb-4 animate-fade-in">
                    {!! $article->content !!}
                </div>

                {{-- Media Gallery --}}
                @if($article->media->count() > 0)
                    <div class="media-gallery mb-4">
                        <h3 class="h5 mb-3 text-muted">Médias associés</h3>
                        <div class="row g-2 g-sm-3">
                            @foreach ($article->media as $m)
                                <div class="col-6 col-sm-4 col-lg-3">
                                    <div class="media-card rounded-3 overflow-hidden shadow-sm border-0 animate-scale-in">
                                        @if ($m->isImage())
                                            <div class="image-ratio-container">
                                                <img src="{{ $m->file_path }}"
                                                     class="media-item cursor-zoom"
                                                     alt="Média de l'article"
                                                     onclick="openLightbox('{{ $m->file_path }}', '{{ $article->title }}')"
                                                     loading="lazy">
                                            </div>
                                        @elseif ($m->isVideo())
                                            <div class="video-ratio-container">
                                                <video controls class="media-item cursor-zoom" 
                                                       onclick="openVideoLightbox('{{ $m->file_path }}')">
                                                    <source src="{{ $m->file_path }}" type="{{ $m->mime_type }}">
                                                    Votre navigateur ne supporte pas la lecture de vidéos.
                                                </video>
                                                <div class="video-overlay">
                                                    <i class="bi bi-play-circle-fill"></i>
                                                </div>
                                            </div>
                                        @elseif ($m->isAudio())
                                            <div class="audio-container p-2 p-sm-3 bg-light d-flex align-items-center">
                                                <audio controls class="w-100">
                                                    <source src="{{ asset('storage/'.$m->file_path) }}" type="{{ $m->mime_type }}">
                                                    Votre navigateur ne supporte pas la lecture audio.
                                                </audio>
                                            </div>
                                        @else
                                            <div class="file-container p-2 p-sm-3 bg-light d-flex align-items-center justify-content-center">
                                                <a href="{{ asset('storage/'.$m->file_path) }}" 
                                                   target="_blank" 
                                                   class="btn btn-outline-primary btn-sm hover-lift">
                                                    <i class="bi bi-download me-1 me-sm-2"></i>Télécharger
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Action Buttons --}}
            <div class="action-buttons mb-5">
                <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
                    {{-- Left Side Actions --}}
                    <div class="d-flex flex-wrap gap-2">
                        @can('update', $article)
                            <a href="{{ route('articles.edit', $article) }}" 
                               class="btn btn-outline-primary hover-lift">
                                <i class="bi bi-pencil me-2"></i>Modifier
                            </a>
                        @endcan
                        
                        @can('delete', $article)
                            <form id="del-{{ $article->id }}" 
                                  action="{{ route('articles.destroy', $article) }}" 
                                  method="POST" 
                                  class="d-inline"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette publication ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger hover-lift">
                                    <i class="bi bi-trash me-2"></i>Supprimer
                                </button>
                            </form>
                        @endcan
                    </div>

                    {{-- Right Side Actions --}}
                    <div class="d-flex flex-wrap gap-2">
                        {{-- Reaction Button --}}
                        @auth
                            <form action="{{ route('articles.react', $article) }}" method="POST" class="m-0">
                                @csrf
                                <button name="type" value="like" 
                                        class="btn btn-outline-primary hover-lift reaction-btn">
                                    ❤️ {{ $article->reactions()->where('type','like')->count() }}
                                </button>
                            </form>
                        @endauth

                        {{-- Boost Button --}}
                        @auth
                            @if(auth()->check())
                                @if(auth()->user()->has_subscription)
                                    <form action="{{ route('articles.boost', $article) }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="btn btn-success hover-lift">
                                            <i class="bi bi-rocket-takeoff me-2"></i>Booster
                                        </button>
                                    </form>
                                @endif
                            @endif
                        @endauth
                    </div>
                </div>
            </div>

            {{-- Share Section --}}
            <div class="share-section mb-5">
                <h3 class="h5 mb-3 text-muted">Partager cette publication</h3>
                <div class="d-flex flex-wrap gap-2">
                    @php
                        $url = urlencode(request()->fullUrl());
                        $title = urlencode($article->title);
                        $image = $article->image_path ? urlencode($article->image_path) : '';
                        $description = urlencode(Str::limit(strip_tags($article->content), 150));
                    @endphp
                    
                    {{-- Facebook --}}
                    <a class="btn btn-sm text-white hover-lift share-btn" style="background:#1877F2" target="_blank"
                    href="https://www.facebook.com/sharer/sharer.php?u={{ $url }}&picture={{ $image }}&title={{ $title }}&description={{ $description }}">
                    <i class="fab fa-facebook-f me-2"></i>Facebook
                    </a>

                    {{-- X (Twitter) --}}
                    <a class="btn btn-sm text-white hover-lift share-btn" style="background:#000000" target="_blank"
                    href="https://twitter.com/intent/tweet?url={{ $url }}&text={{ $title }}&hashtags=E-SOURCE">
                    <i class="fab fa-x-twitter me-2"></i>X
                    </a>

                    {{-- WhatsApp avec image --}}
                    @if($article->image_path)
                        <a class="btn btn-sm text-white hover-lift share-btn" style="background:#25D366" target="_blank"
                        href="https://wa.me/?text={{ $title }}%0A%0A{{ $url }}%0A%0A🖼️ {{ $article->image_path }}">
                        <i class="fab fa-whatsapp me-2"></i>WhatsApp
                        </a>
                    @else
                        <a class="btn btn-sm text-white hover-lift share-btn" style="background:#25D366" target="_blank"
                        href="https://wa.me/?text={{ $title }}%0A%0A{{ $url }}">
                        <i class="fab fa-whatsapp me-2"></i>WhatsApp
                        </a>
                    @endif

                    {{-- Telegram --}}
                    <a class="btn btn-sm text-white hover-lift share-btn" style="background:#0088cc" target="_blank"
                    href="https://t.me/share/url?url={{ $url }}&text={{ $title }}">
                    <i class="fab fa-telegram me-2"></i>Telegram
                    </a>

                    {{-- LinkedIn --}}
                    <a class="btn btn-sm text-white hover-lift share-btn" style="background:#0A66C2" target="_blank"
                    href="https://www.linkedin.com/sharing/share-offsite/?url={{ $url }}">
                    <i class="fab fa-linkedin-in me-2"></i>LinkedIn
                    </a>

                    {{-- Pinterest --}}
                    @if($article->image_path)
                        <a class="btn btn-sm text-white hover-lift share-btn" style="background:#BD081C" target="_blank"
                        href="https://pinterest.com/pin/create/button/?url={{ $url }}&media={{ $image }}&description={{ $title }}">
                        <i class="fab fa-pinterest me-2"></i>Pinterest
                        </a>
                    @endif

                    {{-- Email --}}
                    <a class="btn btn-sm text-white hover-lift share-btn" style="background:#EA4335" target="_blank"
                    href="mailto:?subject={{ $title }}&body=Découvrez cette publication : {{ $url }}">
                    <i class="fas fa-envelope me-2"></i>Email
                    </a>

                    {{-- Copy Link --}}
                    <button class="btn btn-sm text-white hover-lift share-btn copy-link-btn" style="background:#6c757d">
                        <i class="fas fa-copy me-2"></i>Copier le lien
                    </button>
                </div>
            </div>

            {{-- Rating Section --}}
            @auth
                <div class="rating-section mb-5 p-3 p-sm-4 bg-light rounded-3 animate-fade-in">
                    @php $my = $article->ratings()->where('user_id',auth()->id())->first(); @endphp
                    <h3 class="h5 mb-3">Noter cette publication</h3>
                    <form action="{{ route('articles.rate',$article) }}" method="POST" class="d-inline-block">
                        @csrf
                        @if(!$my)
                            <div class="d-flex align-items-center flex-wrap gap-2 gap-sm-3">
                                <span class="fw-medium">Votre note :</span>
                                <div class="star-rating">
                                    @for($i=1;$i<=5;$i++)
                                        <button name="stars" value="{{ $i }}" 
                                                class="btn btn-lg p-1 star-btn" 
                                                type="submit">
                                            <i class="bi bi-star"></i>
                                        </button>
                                    @endfor
                                </div>
                            </div>
                        @else
                            <div class="d-flex align-items-center gap-2 gap-sm-3">
                                <span class="fw-medium">Votre note :</span>
                                <div class="user-rating">
                                    @for($i=1;$i<=$my->stars;$i++)
                                        <i class="bi bi-star-fill text-warning"></i>
                                    @endfor
                                    <span class="ms-2">{{ $my->stars }}/5</span>
                                </div>
                            </div>
                        @endif
                    </form>
                    <div class="mt-2 text-muted">
                        Moyenne : <strong>{{ number_format($article->ratings()->avg('stars'),2) }}</strong> / 5 
                        ({{ $article->ratings()->count() }} avis)
                    </div>
                </div>
            @endauth

            {{-- Comments Section --}}
            <section class="comments-section">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h2 class="h4 mb-0">
                        <i class="bi bi-chat-text me-2"></i>
                        Commentaires ({{ $article->comments->count() }})
                    </h2>
                </div>

                {{-- Add Comment Form --}}
                @auth
                    <div class="comment-form-card card border-0 shadow-sm mb-4 animate-slide-up">
                        <div class="card-body p-3 p-sm-4">
                            <form action="{{ route('articles.comments.store', $article) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Ajouter un commentaire</label>
                                    <textarea name="body" rows="4" 
                                              class="form-control focus-shadow" 
                                              minlength="10" 
                                              required 
                                              placeholder="Partagez vos pensées...">{{ old('body') }}</textarea>
                                </div>
                                <button class="btn btn-primary hover-lift">
                                    <i class="bi bi-send me-2"></i>Commenter
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning border-0 shadow-sm animate-fade-in">
                        <i class="bi bi-info-circle me-2"></i>
                        Vous devez être connecté pour commenter. 
                        <a href="{{ route('login') }}" class="alert-link">Se connecter</a>
                    </div>
                @endauth

                {{-- Comments List --}}
                <div class="comments-list">
                    @forelse($article->comments->whereNull('parent_id') as $c)
                        <div class="comment-card card border-0 shadow-sm mb-3 animate-scale-in">
                            <div class="card-body p-3 p-sm-4">
                                {{-- Comment Header --}}
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2 me-sm-3">
                                            <div class="avatar-placeholder bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex align-items-center justify-content-center fw-bold" 
                                                 style="width: 40px; height: 40px;">
                                                {{ Str::substr($c->user->name, 0, 1) }}
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-medium">
                                                <a href="{{ route('user.article', $c->user->id) }}" 
                                                   class="text-decoration-none text-dark hover-primary">
                                                    {{ $c->user->name }}
                                                </a>
                                            </div>
                                            <div class="small text-muted">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ $c->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    {{-- Comment Actions --}}
                                    @if($c->canEditOrDelete())
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary border-0" 
                                                    type="button" 
                                                    data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a href="{{ route('comments.edit', $c) }}" 
                                                       class="dropdown-item">
                                                        <i class="bi bi-pencil me-2"></i>Modifier
                                                    </a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('comments.destroy', $c) }}" 
                                                          method="POST" 
                                                          class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="dropdown-item text-danger"
                                                                onclick="return confirm('Voulez-vous vraiment supprimer ce commentaire ?')">
                                                            <i class="bi bi-trash me-2"></i>Supprimer
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif
                                </div>

                                {{-- Comment Body --}}
                                <div class="comment-body mb-3">
                                    <p class="mb-0 text-break">{{ $c->body }}</p>
                                </div>

                                {{-- Comment Reactions --}}
                                <div class="comment-reactions mb-3">
                                    @php
                                        $reactions = [
                                            'like' => ['emoji' => '👍', 'class' => 'btn-outline-primary'],
                                            'dislike' => ['emoji' => '👎', 'class' => 'btn-outline-secondary'],
                                            'love' => ['emoji' => '❤️', 'class' => 'btn-outline-danger'],
                                            'laugh' => ['emoji' => '😂', 'class' => 'btn-outline-warning'],
                                            'angry' => ['emoji' => '😡', 'class' => 'btn-outline-dark'],
                                            'sad' => ['emoji' => '😢', 'class' => 'btn-outline-info'],
                                        ];
                                    @endphp

                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach ($reactions as $type => $data)
                                            <form action="{{ route('comments.react', $c) }}" method="POST" class="m-0">
                                                @csrf
                                                <input type="hidden" name="type" value="{{ $type }}">
                                                <button class="btn btn-sm {{ $data['class'] }} hover-lift reaction-btn">
                                                    {{ $data['emoji'] }} 
                                                    <span class="ms-1">{{ $c->reactions->where('type', $type)->count() }}</span>
                                                </button>
                                            </form>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Reply Form --}}
                                @auth
                                    <div class="reply-form mt-3">
                                        <form action="{{ route('comments.reply', $c) }}" method="POST">
                                            @csrf
                                            <div class="input-group">
                                                <textarea name="body" 
                                                          class="form-control form-control-sm focus-shadow" 
                                                          rows="2" 
                                                          placeholder="Répondre à ce commentaire..."></textarea>
                                                <button class="btn btn-primary btn-sm hover-lift">
                                                    <i class="bi bi-reply"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                @endauth

                                {{-- Replies --}}
                                @if($c->replies->count() > 0)
                                    <div class="replies-container mt-3 ps-3 ps-sm-4 border-start border-2">
                                        @foreach ($c->replies as $reply)
                                            <div class="reply-card card border-0 bg-light mb-2 animate-fade-in">
                                                <div class="card-body p-2 p-sm-3">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-xs me-2">
                                                                <div class="avatar-placeholder bg-light text-muted rounded-circle d-flex align-items-center justify-content-center fw-bold" 
                                                                     style="width: 30px; height: 30px; font-size: 0.8rem;">
                                                                    {{ Str::substr($reply->user->name, 0, 1) }}
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <div class="small fw-medium">
                                                                    {{ $reply->user->name }}
                                                                </div>
                                                                <div class="small text-muted">
                                                                    {{ $reply->created_at->diffForHumans() }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="small text-break">{{ $reply->body }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted animate-fade-in">
                            <i class="bi bi-chat-square display-4 mb-3"></i>
                            <p class="mb-0">Aucun commentaire pour le moment.</p>
                            <small>Soyez le premier à commenter cette publication !</small>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</div>

<!-- Lightbox Modal -->
<div id="lightbox" class="lightbox">
    <div class="lightbox-content">
        <span class="lightbox-close" onclick="closeLightbox()">
            <i class="bi bi-x-lg"></i>
        </span>
        <div class="lightbox-image-container">
            <img class="lightbox-image" id="lightboxImage">
            <div class="lightbox-caption" id="lightboxCaption"></div>
        </div>
        <div class="lightbox-nav">
            <button class="lightbox-nav-btn lightbox-prev" onclick="navigateLightbox(-1)">
                <i class="bi bi-chevron-left"></i>
            </button>
            <button class="lightbox-nav-btn lightbox-next" onclick="navigateLightbox(1)">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
        <div class="lightbox-counter">
            <span id="lightboxCounter">1/1</span>
        </div>
    </div>
</div>

<!-- Video Lightbox Modal -->
<div id="videoLightbox" class="lightbox">
    <div class="lightbox-content video-lightbox-content">
        <span class="lightbox-close" onclick="closeVideoLightbox()">
            <i class="bi bi-x-lg"></i>
        </span>
        <div class="video-container">
            <video controls class="lightbox-video" id="lightboxVideo">
                Votre navigateur ne supporte pas la lecture de vidéos.
            </video>
        </div>
    </div>
</div>

<style>
/* ===== RESPONSIVE TYPOGRAPHY ===== */
.article-title {
    font-size: clamp(1.5rem, 4vw, 2.5rem);
    line-height: 1.3;
    word-wrap: break-word;
}

.content-body {
    font-size: clamp(1rem, 2.5vw, 1.1rem);
    line-height: 1.6;
}

/* ===== IMAGE RATIO CONTAINERS ===== */
.image-ratio-container {
    position: relative;
    width: 100%;
    padding-bottom: 66.67%; /* Ratio 3:2 */
    overflow: hidden;
    background: #f8f9fa;
}

.video-ratio-container {
    position: relative;
    width: 100%;
    padding-bottom: 56.25%; /* Ratio 16:9 */
    overflow: hidden;
    background: #000;
}

.article-main-image,
.media-item {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

/* ===== CURSOR & HOVER EFFECTS ===== */
.cursor-zoom {
    cursor: zoom-in;
}

.media-card:hover .media-item {
    transform: scale(1.05);
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
    opacity: 0;
    transition: opacity 0.3s ease;
}

.video-ratio-container:hover .video-overlay {
    opacity: 1;
}

.video-overlay i {
    font-size: 3rem;
    color: white;
    filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.5));
}

/* ===== LIGHTBOX STYLES ===== */
.lightbox {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.95);
    backdrop-filter: blur(10px);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.lightbox.active {
    display: flex;
    align-items: center;
    justify-content: center;
    animation: lightboxFadeIn 0.3s ease forwards;
}

@keyframes lightboxFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.lightbox-content {
    position: relative;
    width: 95%;
    max-width: 1200px;
    max-height: 95vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.video-lightbox-content {
    max-width: 900px;
}

/* Lightbox Close Button */
.lightbox-close {
    position: absolute;
    top: -50px;
    right: 0;
    color: white;
    font-size: 2rem;
    cursor: pointer;
    background: rgba(255, 255, 255, 0.1);
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.lightbox-close:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: scale(1.1);
}

/* Lightbox Image Container */
.lightbox-image-container {
    position: relative;
    width: 100%;
    max-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.lightbox-image {
    max-width: 100%;
    max-height: 80vh;
    object-fit: contain;
    border-radius: 12px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
    animation: imageZoomIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes imageZoomIn {
    from {
        opacity: 0;
        transform: scale(0.8);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* Video Container */
.video-container {
    width: 100%;
    max-height: 80vh;
}

.lightbox-video {
    width: 100%;
    height: auto;
    max-height: 80vh;
    border-radius: 12px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
}

/* Lightbox Caption */
.lightbox-caption {
    position: absolute;
    bottom: -60px;
    left: 0;
    width: 100%;
    text-align: center;
    color: white;
    font-size: 1.1rem;
    padding: 1rem;
    background: rgba(0, 0, 0, 0.7);
    border-radius: 8px;
    backdrop-filter: blur(10px);
}

/* Lightbox Navigation */
.lightbox-nav {
    position: absolute;
    top: 50%;
    width: 100%;
    display: flex;
    justify-content: space-between;
    transform: translateY(-50%);
    padding: 0 2rem;
}

.lightbox-nav-btn {
    background: rgba(255, 255, 255, 0.1);
    color: white;
    border: none;
    font-size: 1.5rem;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.lightbox-nav-btn:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: scale(1.1);
}

/* Lightbox Counter */
.lightbox-counter {
    position: absolute;
    top: -50px;
    left: 0;
    color: white;
    font-size: 1rem;
    background: rgba(255, 255, 255, 0.1);
    padding: 0.5rem 1rem;
    border-radius: 20px;
    backdrop-filter: blur(10px);
}

/* ===== MOBILE OPTIMIZATIONS ===== */
@media (max-width: 768px) {
    .container-fluid {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .image-ratio-container {
        padding-bottom: 75%; /* Ratio 4:3 sur mobile */
    }
    
    .video-ratio-container {
        padding-bottom: 75%;
    }
    
    /* Lightbox Mobile */
    .lightbox-content {
        width: 98%;
        max-height: 90vh;
    }
    
    .lightbox-close {
        top: 10px;
        right: 10px;
        width: 45px;
        height: 45px;
        font-size: 1.5rem;
    }
    
    .lightbox-nav {
        padding: 0 1rem;
    }
    
    .lightbox-nav-btn {
        width: 50px;
        height: 50px;
        font-size: 1.2rem;
    }
    
    .lightbox-counter {
        top: 10px;
        left: 10px;
    }
    
    .lightbox-caption {
        bottom: -80px;
        font-size: 1rem;
    }
    
    .video-overlay i {
        font-size: 2.5rem;
    }
}

@media (max-width: 576px) {
    .media-gallery .col-6 {
        flex: 0 0 50%;
        max-width: 50%;
    }
    
    .image-ratio-container {
        padding-bottom: 100%; /* Ratio carré sur très petit mobile */
    }
    
    .lightbox-nav-btn {
        width: 45px;
        height: 45px;
        font-size: 1.1rem;
    }
    
    .lightbox-close {
        width: 40px;
        height: 40px;
        font-size: 1.2rem;
    }
}

/* ===== TOUCH DEVICE OPTIMIZATIONS ===== */
@media (hover: none) and (pointer: coarse) {
    .lightbox-nav-btn,
    .lightbox-close {
        min-width: 44px;
        min-height: 44px;
    }
    
    .media-card:hover .media-item {
        transform: none;
    }
    
    .video-ratio-container:hover .video-overlay {
        opacity: 0.7;
    }
}

/* ===== EXISTING ANIMATIONS ===== */
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

/* ===== EXISTING UTILITIES ===== */
.hover-lift {
    transition: all 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.hover-primary:hover {
    color: #4361ee !important;
}

.focus-shadow:focus {
    box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25) !important;
    border-color: #4361ee;
}

.text-break {
    word-wrap: break-word;
    overflow-wrap: break-word;
}

.copy-link-btn.copied {
    background-color: #28a745 !important;
}
</style>

<script>
// ===== LIGHTBOX FUNCTIONALITY =====
let currentLightboxIndex = 0;
let lightboxImages = [];

function openLightbox(imageSrc, caption) {
    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightboxImage');
    const captionText = document.getElementById('lightboxCaption');
    const counter = document.getElementById('lightboxCounter');
    
    // Collect all clickable images
    lightboxImages = Array.from(document.querySelectorAll('.cursor-zoom[src]'));
    currentLightboxIndex = lightboxImages.findIndex(img => img.src.includes(imageSrc));
    
    // Show lightbox with animation
    lightbox.classList.add('active');
    lightboxImg.src = imageSrc;
    captionText.innerHTML = caption || '';
    updateLightboxCounter();
    
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    const lightbox = document.getElementById('lightbox');
    lightbox.classList.remove('active');
    
    setTimeout(() => {
        lightbox.style.display = 'none';
    }, 300);
    
    document.body.style.overflow = 'auto';
}

function navigateLightbox(direction) {
    if (lightboxImages.length === 0) return;
    
    currentLightboxIndex += direction;
    
    if (currentLightboxIndex >= lightboxImages.length) {
        currentLightboxIndex = 0;
    } else if (currentLightboxIndex < 0) {
        currentLightboxIndex = lightboxImages.length - 1;
    }
    
    const lightboxImg = document.getElementById('lightboxImage');
    const captionText = document.getElementById('lightboxCaption');
    
    // Add fade transition
    lightboxImg.style.opacity = '0';
    
    setTimeout(() => {
        lightboxImg.src = lightboxImages[currentLightboxIndex].src;
        captionText.innerHTML = lightboxImages[currentLightboxIndex].alt || '';
        lightboxImg.style.opacity = '1';
        updateLightboxCounter();
    }, 200);
}

function updateLightboxCounter() {
    const counter = document.getElementById('lightboxCounter');
    counter.textContent = `${currentLightboxIndex + 1}/${lightboxImages.length}`;
}

// ===== VIDEO LIGHTBOX FUNCTIONALITY =====
function openVideoLightbox(videoSrc) {
    const lightbox = document.getElementById('videoLightbox');
    const lightboxVideo = document.getElementById('lightboxVideo');
    
    lightbox.classList.add('active');
    lightboxVideo.src = videoSrc;
    lightboxVideo.load();
    
    document.body.style.overflow = 'hidden';
}

function closeVideoLightbox() {
    const lightbox = document.getElementById('videoLightbox');
    const lightboxVideo = document.getElementById('lightboxVideo');
    
    lightbox.classList.remove('active');
    lightboxVideo.pause();
    
    setTimeout(() => {
        lightbox.style.display = 'none';
        lightboxVideo.src = '';
    }, 300);
    
    document.body.style.overflow = 'auto';
}

// ===== EVENT LISTENERS =====
document.addEventListener('DOMContentLoaded', function() {
    // Close lightboxes on outside click
    document.addEventListener('click', function(event) {
        const lightbox = document.getElementById('lightbox');
        const videoLightbox = document.getElementById('videoLightbox');
        
        if (event.target === lightbox) {
            closeLightbox();
        }
        
        if (event.target === videoLightbox) {
            closeVideoLightbox();
        }
    });
    
    // Keyboard navigation
    document.addEventListener('keydown', function(event) {
        const lightbox = document.getElementById('lightbox');
        const videoLightbox = document.getElementById('videoLightbox');
        
        if (lightbox.classList.contains('active')) {
            if (event.key === 'Escape') {
                closeLightbox();
            } else if (event.key === 'ArrowLeft') {
                navigateLightbox(-1);
            } else if (event.key === 'ArrowRight') {
                navigateLightbox(1);
            }
        }
        
        if (videoLightbox.classList.contains('active') && event.key === 'Escape') {
            closeVideoLightbox();
        }
    });
    
    // Touch gestures for mobile
    let touchStartX = 0;
    let touchEndX = 0;
    
    document.addEventListener('touchstart', function(event) {
        touchStartX = event.changedTouches[0].screenX;
    });
    
    document.addEventListener('touchend', function(event) {
        touchEndX = event.changedTouches[0].screenX;
        handleSwipe();
    });
    
    function handleSwipe() {
        const lightbox = document.getElementById('lightbox');
        
        if (lightbox.classList.contains('active')) {
            const swipeThreshold = 50;
            
            if (touchEndX < touchStartX - swipeThreshold) {
                navigateLightbox(1); // Swipe left - next image
            }
            
            if (touchEndX > touchStartX + swipeThreshold) {
                navigateLightbox(-1); // Swipe right - previous image
            }
        }
    }
    
    // Existing functionality
    const copyLinkBtn = document.querySelector('.copy-link-btn');
    if (copyLinkBtn) {
        copyLinkBtn.addEventListener('click', function() {
            navigator.clipboard.writeText('{{ request()->fullUrl() }}').then(() => {
                const originalHTML = this.innerHTML;
                this.innerHTML = '<i class="fas fa-check me-2"></i>Lien copié';
                this.classList.add('copied');
                
                setTimeout(() => {
                    this.innerHTML = originalHTML;
                    this.classList.remove('copied');
                }, 2000);
            });
        });
    }
    
    // Star rating hover effect
    const starBtns = document.querySelectorAll('.star-btn');
    starBtns.forEach((btn, index) => {
        btn.addEventListener('mouseenter', () => {
            starBtns.forEach((star, starIndex) => {
                if (starIndex <= index) {
                    star.querySelector('i').className = 'bi bi-star-fill text-warning';
                }
            });
        });
        
        btn.addEventListener('mouseleave', () => {
            starBtns.forEach(star => {
                star.querySelector('i').className = 'bi bi-star';
            });
        });
    });
    
    // Scroll animations
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
    
    document.querySelectorAll('.animate-fade-in, .animate-slide-up, .animate-scale-in').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
});
</script>

<!-- Rest of your existing content for action buttons, share section, rating, comments -->
@endsection