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
                    <img src="{{ $article->image_path }}" 
                         class="img-fluid w-100 article-main-image cursor-zoom" 
                         alt="Image article {{ $article->title }}"
                         loading="eager"
                         onclick="openImageModal('{{ $article->image_path }}', '{{ $article->title }}')">
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
                                    <div class="media-card rounded-3 overflow-hidden shadow-sm border-0 h-100 animate-scale-in">
                                        @if ($m->isImage())
                                            <img src="{{ $m->file_path }}"
                                                 class="img-fluid w-100 media-item cursor-zoom"
                                                 alt="Média de l'article"
                                                 onclick="openImageModal('{{ $m->file_path }}', '{{ $article->title }}')"
                                                 loading="lazy">
                                        @elseif ($m->isVideo())
                                            <video controls class="img-fluid w-100 media-item cursor-zoom" 
                                                   onclick="openVideoModal('{{ $m->file_path }}')">
                                                <source src="{{ $m->file_path }}" type="{{ $m->mime_type }}">
                                                Votre navigateur ne supporte pas la lecture de vidéos.
                                            </video>
                                        @elseif ($m->isAudio())
                                            <div class="p-2 p-sm-3 bg-light h-100 d-flex align-items-center">
                                                <audio controls class="w-100">
                                                    <source src="{{ asset('storage/'.$m->file_path) }}" type="{{ $m->mime_type }}">
                                                    Votre navigateur ne supporte pas la lecture audio.
                                                </audio>
                                            </div>
                                        @else
                                            <div class="p-2 p-sm-3 bg-light h-100 d-flex align-items-center justify-content-center">
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

<!-- Modal for Image Zoom -->
<div id="imageModal" class="modal-image-overlay">
    <div class="modal-image-content">
        <span class="modal-image-close" onclick="closeImageModal()">&times;</span>
        <img class="modal-image" id="modalImage">
        <div class="modal-image-caption" id="modalCaption"></div>
        <div class="modal-image-nav">
            <button class="nav-btn prev-btn" onclick="navigateImage(-1)">&#10094;</button>
            <button class="nav-btn next-btn" onclick="navigateImage(1)">&#10095;</button>
        </div>
    </div>
</div>

<!-- Modal for Video -->
<div id="videoModal" class="modal-video-overlay">
    <div class="modal-video-content">
        <span class="modal-video-close" onclick="closeVideoModal()">&times;</span>
        <video controls class="modal-video" id="modalVideo">
            Votre navigateur ne supporte pas la lecture de vidéos.
        </video>
    </div>
</div>

<style>
/* Responsive Typography */
.article-title {
    font-size: clamp(1.5rem, 4vw, 2.5rem);
    line-height: 1.3;
    word-wrap: break-word;
}

.content-body {
    font-size: clamp(1rem, 2.5vw, 1.1rem);
    line-height: 1.6;
}

/* Mobile First Media Gallery */
.media-gallery .row {
    margin: 0 -0.25rem;
}

.media-gallery .col-6 {
    padding: 0.25rem;
}

.media-item {
    height: 120px;
    object-fit: cover;
    transition: transform 0.3s ease;
    cursor: pointer;
}

@media (min-width: 576px) {
    .media-item {
        height: 150px;
    }
}

@media (min-width: 768px) {
    .media-item {
        height: 180px;
    }
}

.cursor-zoom {
    cursor: zoom-in;
}

.media-item:hover {
    transform: scale(1.02);
}

/* Image Modal Styles */
.modal-image-overlay {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.9);
    backdrop-filter: blur(5px);
}

.modal-image-content {
    position: relative;
    margin: auto;
    padding: 20px;
    width: 95%;
    max-width: 1200px;
    height: 95vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.modal-image {
    max-width: 100%;
    max-height: 80vh;
    object-fit: contain;
    border-radius: 8px;
}

.modal-image-close {
    position: absolute;
    top: 15px;
    right: 35px;
    color: #fff;
    font-size: 40px;
    font-weight: bold;
    cursor: pointer;
    z-index: 10000;
    background: rgba(0,0,0,0.5);
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-image-caption {
    color: #fff;
    text-align: center;
    margin-top: 15px;
    font-size: 1.1rem;
    padding: 0 20px;
}

.modal-image-nav {
    position: absolute;
    top: 50%;
    width: 100%;
    display: flex;
    justify-content: space-between;
    transform: translateY(-50%);
    padding: 0 20px;
}

.nav-btn {
    background: rgba(0,0,0,0.5);
    color: white;
    border: none;
    font-size: 24px;
    padding: 15px;
    cursor: pointer;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.3s ease;
}

.nav-btn:hover {
    background: rgba(0,0,0,0.8);
}

/* Video Modal Styles */
.modal-video-overlay {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.9);
}

.modal-video-content {
    position: relative;
    margin: auto;
    padding: 20px;
    width: 95%;
    max-width: 800px;
    height: 80vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.modal-video {
    width: 100%;
    height: 100%;
    max-height: 70vh;
    border-radius: 8px;
}

.modal-video-close {
    position: absolute;
    top: 15px;
    right: 35px;
    color: #fff;
    font-size: 40px;
    font-weight: bold;
    cursor: pointer;
    z-index: 10000;
    background: rgba(0,0,0,0.5);
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Mobile Optimizations */
@media (max-width: 768px) {
    .container-fluid {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .article-main-image {
        max-height: 300px;
    }
    
    .modal-image-content {
        padding: 10px;
        width: 98%;
        height: 98vh;
    }
    
    .modal-image-close {
        top: 10px;
        right: 15px;
        font-size: 30px;
        width: 40px;
        height: 40px;
    }
    
    .nav-btn {
        width: 40px;
        height: 40px;
        font-size: 20px;
        padding: 10px;
    }
    
    .modal-video-content {
        padding: 10px;
        width: 98%;
        height: 70vh;
    }
    
    .modal-video-close {
        top: 10px;
        right: 15px;
        font-size: 30px;
        width: 40px;
        height: 40px;
    }
    
    /* Share buttons mobile optimization */
    .share-section .d-flex {
        justify-content: center !important;
        gap: 0.5rem !important;
    }
    
    .share-btn {
        flex: 0 0 calc(50% - 0.5rem) !important;
        min-width: auto !important;
        margin-bottom: 0.5rem;
        font-size: 0.8rem;
        padding: 0.5rem 0.75rem;
    }

    /* Action buttons mobile */
    .action-buttons .d-flex {
        justify-content: center !important;
        gap: 0.5rem !important;
    }
    
    .action-buttons .btn {
        flex: 1;
        min-width: 120px;
        font-size: 0.85rem;
        padding: 0.5rem 0.75rem;
    }
}

@media (max-width: 576px) {
    .media-gallery .col-6 {
        flex: 0 0 50%;
        max-width: 50%;
    }
    
    .media-item {
        height: 100px;
    }
    
    .comment-reactions .d-flex {
        justify-content: center;
        gap: 0.25rem !important;
    }
    
    .comment-reactions .btn {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    
    .star-rating .btn-lg {
        padding: 0.2rem !important;
        font-size: 1rem;
    }

    /* Rating section mobile */
    .rating-section {
        padding: 1.5rem !important;
    }

    /* Comments mobile */
    .comment-card .card-body {
        padding: 1rem !important;
    }

    .replies-container {
        padding-left: 1rem !important;
    }
}

/* Touch device optimizations */
@media (hover: none) and (pointer: coarse) {
    .hover-lift:hover {
        transform: none;
    }
    
    .media-item:hover {
        transform: none;
    }
    
    .nav-btn, .modal-image-close, .modal-video-close {
        min-width: 44px;
        min-height: 44px;
    }
}

/* Text break for long words */
.text-break {
    word-wrap: break-word;
    overflow-wrap: break-word;
}

/* Keep your existing animations and styles */
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

.article-main-image {
    max-height: 500px;
    object-fit: cover;
}

.share-btn, .reaction-btn {
    transition: all 0.3s ease;
}

.star-btn {
    color: #dee2e6;
    transition: all 0.2s ease;
}

.star-btn:hover {
    color: #ffc107;
    transform: scale(1.2);
}

.copy-link-btn.copied {
    background-color: #28a745 !important;
}

.comment-card {
    transition: all 0.3s ease;
}

.avatar-placeholder {
    transition: all 0.3s ease;
}
</style>

<script>
// Image Modal Functionality
let currentImageIndex = 0;
let imageElements = [];

function openImageModal(imageSrc, caption) {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    const captionText = document.getElementById('modalCaption');
    
    // Collect all clickable images
    imageElements = Array.from(document.querySelectorAll('.cursor-zoom[src]'));
    currentImageIndex = imageElements.findIndex(img => img.src.includes(imageSrc));
    
    modal.style.display = 'block';
    modalImg.src = imageSrc;
    captionText.innerHTML = caption || '';
    
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

function navigateImage(direction) {
    if (imageElements.length === 0) return;
    
    currentImageIndex += direction;
    
    if (currentImageIndex >= imageElements.length) {
        currentImageIndex = 0;
    } else if (currentImageIndex < 0) {
        currentImageIndex = imageElements.length - 1;
    }
    
    const modalImg = document.getElementById('modalImage');
    const captionText = document.getElementById('modalCaption');
    
    modalImg.src = imageElements[currentImageIndex].src;
    captionText.innerHTML = imageElements[currentImageIndex].alt || '';
}

// Video Modal Functionality
function openVideoModal(videoSrc) {
    const modal = document.getElementById('videoModal');
    const modalVideo = document.getElementById('modalVideo');
    
    modal.style.display = 'block';
    modalVideo.src = videoSrc;
    modalVideo.load();
    
    document.body.style.overflow = 'hidden';
}

function closeVideoModal() {
    const modal = document.getElementById('videoModal');
    const modalVideo = document.getElementById('modalVideo');
    
    modal.style.display = 'none';
    modalVideo.pause();
    modalVideo.src = '';
    
    document.body.style.overflow = 'auto';
}

// Close modals on outside click
document.addEventListener('click', function(event) {
    const imageModal = document.getElementById('imageModal');
    const videoModal = document.getElementById('videoModal');
    
    if (event.target === imageModal) {
        closeImageModal();
    }
    
    if (event.target === videoModal) {
        closeVideoModal();
    }
});

// Keyboard navigation
document.addEventListener('keydown', function(event) {
    const imageModal = document.getElementById('imageModal');
    const videoModal = document.getElementById('videoModal');
    
    if (imageModal.style.display === 'block') {
        if (event.key === 'Escape') {
            closeImageModal();
        } else if (event.key === 'ArrowLeft') {
            navigateImage(-1);
        } else if (event.key === 'ArrowRight') {
            navigateImage(1);
        }
    }
    
    if (videoModal.style.display === 'block' && event.key === 'Escape') {
        closeVideoModal();
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
    const imageModal = document.getElementById('imageModal');
    
    if (imageModal.style.display === 'block') {
        const swipeThreshold = 50;
        
        if (touchEndX < touchStartX - swipeThreshold) {
            navigateImage(1); // Swipe left - next image
        }
        
        if (touchEndX > touchStartX + swipeThreshold) {
            navigateImage(-1); // Swipe right - previous image
        }
    }
}

// Existing functionality
document.addEventListener('DOMContentLoaded', function() {
    // Copy link functionality
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
    
    // Add animation to elements on scroll
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
    
    // Observe all animated elements
    document.querySelectorAll('.animate-fade-in, .animate-slide-up, .animate-scale-in').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
});
</script>
@endsection