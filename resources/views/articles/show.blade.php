@extends('layouts.app')

@section('content')
<div class="container-fluid px-2 px-md-3 px-lg-4 py-3">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            {{-- Floating Action Buttons --}}
            <div class="floating-actions">
                @auth
                    @include('articles.partials.favorite_button', ['article' => $article])
                @endauth
                
                <button class="btn btn-primary btn-lg shadow-lg hover-scale share-floating-btn" 
                        onclick="openShareModal()">
                    <i class="bi bi-share-fill"></i>
                </button>
                
                <button class="btn btn-accent btn-lg shadow-lg hover-scale scroll-top-btn" 
                        onclick="scrollToTop()">
                    <i class="bi bi-arrow-up"></i>
                </button>
            </div>

            {{-- Article Header --}}
            <div class="article-header mb-5 animate-fade-in">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('articles.index') }}" class="text-decoration-none hover-primary">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user.article', $article->user->id) }}" class="text-decoration-none hover-primary">{{ $article->user->name }}</a></li>
                        <li class="breadcrumb-item active text-accent" aria-current="page">Publication</li>
                    </ol>
                </nav>

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
                    <div class="flex-grow-1">
                        <h1 class="article-title fw-bold text-dark mb-3">{{ $article->title }}</h1>
                        
                        {{-- Author Info --}}
                        <div class="d-flex align-items-center flex-wrap gap-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-lg me-3">
                                    <div class="avatar-placeholder bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" 
                                         style="width: 60px; height: 60px;">
                                        {{ Str::substr($article->user->name, 0, 1) }}
                                    </div>
                                </div>
                                <div>
                                    <div class="fw-bold h5 mb-1">
                                        <a href="{{ route('user.article', $article->user->id) }}" 
                                           class="text-decoration-none text-dark hover-primary">
                                            {{ $article->user->name }}
                                        </a>
                                    </div>
                                    <div class="text-muted">
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
                                    @if(auth()->id() !== $article->user->id)
                                        @if(auth()->user()->following?->contains($article->user->id))
                                            <form method="POST" action="{{ route('users.unfollow', $article->user) }}" class="m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-outline-warning hover-lift">
                                                    <i class="bi bi-person-dash me-1"></i>Se désabonner
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('users.follow', $article->user) }}" class="m-0">
                                                @csrf
                                                <button class="btn btn-primary hover-lift">
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
                                <button class="btn btn-outline-danger hover-lift">
                                    <i class="bi bi-trash me-1"></i>Supprimer
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>

                {{-- Article Stats --}}
                <div class="article-stats d-flex flex-wrap gap-4 mb-4">
                    <div class="stat-item d-flex align-items-center">
                        <i class="bi bi-eye-fill text-primary me-2"></i>
                        <span class="fw-bold">{{ $article->views_count ?? 0 }}</span>
                        <span class="text-muted ms-1">vues</span>
                    </div>
                    <div class="stat-item d-flex align-items-center">
                        <i class="bi bi-chat-fill text-primary me-2"></i>
                        <span class="fw-bold">{{ $article->comments->count() }}</span>
                        <span class="text-muted ms-1">commentaires</span>
                    </div>
                    <div class="stat-item d-flex align-items-center">
                        <i class="bi bi-heart-fill text-danger me-2"></i>
                        <span class="fw-bold">{{ $article->reactions()->where('type','like')->count() }}</span>
                        <span class="text-muted ms-1">réactions</span>
                    </div>
                    @php
                        $avgRating = $article->ratings()->avg('stars') ?? 0;
                    @endphp
                    <div class="stat-item d-flex align-items-center">
                        <i class="bi bi-star-fill text-warning me-2"></i>
                        <span class="fw-bold">{{ number_format($avgRating, 1) }}</span>
                        <span class="text-muted ms-1">/5 ({{ $article->ratings()->count() }})</span>
                    </div>
                </div>
            </div>

            {{-- Main Article Media --}}
            @php
                $mainVideo = $article->media->where('type', 'video')->first();
                $mainImage = $article->image_path;
            @endphp

            @if($mainVideo)
                {{-- Video Player --}}
                <div class="main-media-container mb-5 rounded-4 overflow-hidden shadow-lg animate-slide-up">
                    <div class="video-player-container">
                        <video class="main-video-player" 
                               controls 
                               playsinline
                               poster="{{ $mainImage ?? asset('img/video-placeholder.jpg') }}"
                               preload="metadata">
                            <source src="{{ $mainVideo->file_path }}" type="video/mp4">
                            Votre navigateur ne supporte pas la lecture vidéo.
                        </video>
                        <div class="video-controls-overlay">
                            <div class="control-buttons">
                                <button class="btn btn-primary btn-lg control-btn play-pause-btn">
                                    <i class="bi bi-play-fill"></i>
                                </button>
                                <button class="btn btn-primary btn-lg control-btn fullscreen-btn">
                                    <i class="bi bi-arrows-fullscreen"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($mainImage)
                {{-- Main Image --}}
                <div class="main-media-container mb-5 rounded-4 overflow-hidden shadow-lg animate-slide-up">
                    <div class="image-ratio-container">
                        <img src="{{ $mainImage }}" 
                             class="article-main-image cursor-zoom" 
                             alt="Image article {{ $article->title }}"
                             loading="eager"
                             onclick="openLightbox('{{ $mainImage }}', '{{ $article->title }}')">
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
                    <div class="media-gallery mb-5">
                        <h3 class="h4 mb-4 text-gradient text-center">
                            <i class="bi bi-images me-2"></i>Médias associés
                        </h3>
                        <div class="row g-3">
                            @foreach ($article->media as $m)
                                <div class="col-6 col-sm-4 col-lg-3">
                                    <div class="media-card rounded-3 overflow-hidden shadow-sm border-0 animate-scale-in hover-scale">
                                        @if ($m->isImage())
                                            <div class="image-ratio-container">
                                                <img src="{{ $m->file_path }}"
                                                     class="media-item cursor-zoom"
                                                     alt="Média de l'article"
                                                     onclick="openLightbox('{{ $m->file_path }}', '{{ $article->title }}')"
                                                     loading="lazy">
                                            </div>
                                        @elseif ($m->isVideo())
                                            <div class="video-thumbnail-container">
                                                <div class="image-ratio-container">
                                                    <img src="{{ $article->image_path ?? asset('img/video-placeholder.jpg') }}"
                                                         class="media-item cursor-pointer"
                                                         alt="Vidéo de l'article"
                                                         onclick="playVideo('{{ $m->file_path }}')"
                                                         loading="lazy">
                                                    <div class="video-play-overlay">
                                                        <div class="play-icon-sm">
                                                            <i class="bi bi-play-fill"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif ($m->isAudio())
                                            <div class="audio-container p-3 bg-light d-flex align-items-center rounded-3">
                                                <audio controls class="w-100">
                                                    <source src="{{ $m->file_path }}" type="{{ $m->mime_type }}">
                                                    Votre navigateur ne supporte pas la lecture audio.
                                                </audio>
                                            </div>
                                        @else
                                            <div class="file-container p-3 bg-light d-flex align-items-center justify-content-center rounded-3">
                                                <a href="{{ $m->file_path }}" 
                                                   target="_blank" 
                                                   class="btn btn-outline-primary hover-lift w-100">
                                                    <i class="bi bi-download me-2"></i>Télécharger
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
                <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center">
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
                                    <i class="bi bi-heart me-2"></i>
                                    {{ $article->reactions()->where('type','like')->count() }}
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

            {{-- Share Modal --}}
            <div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header bg-gradient-primary text-white">
                            <h5 class="modal-title" id="shareModalLabel">
                                <i class="bi bi-share-fill me-2"></i>Partager cette publication
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            @php
                                $url = urlencode(request()->fullUrl());
                                $title = urlencode($article->title);
                                $image = $article->image_path ? urlencode($article->image_path) : '';
                                $description = urlencode(Str::limit(strip_tags($article->content), 150));
                                $video = $article->media->where('type', 'video')->first();
                                $hasVideo = $video ? true : false;
                                $videoUrl = $video ? urlencode($video->file_path) : '';
                            @endphp
                            
                            <div class="row g-3">
                                <div class="col-6 col-sm-4">
                                    <a class="share-platform-btn btn btn-lg w-100 text-white hover-lift" style="background:#1877F2" 
                                       href="https://www.facebook.com/sharer/sharer.php?u={{ $url }}&picture={{ $image }}&title={{ $title }}&description={{ $description }}"
                                       target="_blank">
                                        <i class="fab fa-facebook-f fa-lg"></i>
                                        <span class="d-block mt-1 small">Facebook</span>
                                    </a>
                                </div>
                                
                                <div class="col-6 col-sm-4">
                                    <a class="share-platform-btn btn btn-lg w-100 text-white hover-lift" style="background:#000000" 
                                       href="https://twitter.com/intent/tweet?url={{ $url }}&text={{ $title }}&hashtags=FlashPost"
                                       target="_blank">
                                        <i class="fab fa-x-twitter fa-lg"></i>
                                        <span class="d-block mt-1 small">Twitter</span>
                                    </a>
                                </div>
                                
                                <div class="col-6 col-sm-4">
                                    @if($hasVideo)
                                        <a class="share-platform-btn btn btn-lg w-100 text-white hover-lift" style="background:#25D366" 
                                           href="https://wa.me/?text={{ $title }}%0A%0A🎥 Vidéo disponible : {{ $url }}"
                                           target="_blank">
                                            <i class="fab fa-whatsapp fa-lg"></i>
                                            <span class="d-block mt-1 small">WhatsApp</span>
                                        </a>
                                    @else
                                        <a class="share-platform-btn btn btn-lg w-100 text-white hover-lift" style="background:#25D366" 
                                           href="https://wa.me/?text={{ $title }}%0A%0A{{ $url }}"
                                           target="_blank">
                                            <i class="fab fa-whatsapp fa-lg"></i>
                                            <span class="d-block mt-1 small">WhatsApp</span>
                                        </a>
                                    @endif
                                </div>
                                
                                <div class="col-6 col-sm-4">
                                    <a class="share-platform-btn btn btn-lg w-100 text-white hover-lift" style="background:#0088cc" 
                                       href="https://t.me/share/url?url={{ $url }}&text={{ $title }}"
                                       target="_blank">
                                        <i class="fab fa-telegram fa-lg"></i>
                                        <span class="d-block mt-1 small">Telegram</span>
                                    </a>
                                </div>
                                
                                <div class="col-6 col-sm-4">
                                    <a class="share-platform-btn btn btn-lg w-100 text-white hover-lift" style="background:#0A66C2" 
                                       href="https://www.linkedin.com/sharing/share-offsite/?url={{ $url }}"
                                       target="_blank">
                                        <i class="fab fa-linkedin-in fa-lg"></i>
                                        <span class="d-block mt-1 small">LinkedIn</span>
                                    </a>
                                </div>
                                
                                <div class="col-6 col-sm-4">
                                    <button class="share-platform-btn btn btn-lg w-100 text-white hover-lift copy-link-btn" style="background:#6c757d">
                                        <i class="fas fa-copy fa-lg"></i>
                                        <span class="d-block mt-1 small">Copier le lien</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Rating Section --}}
            @auth
                <div class="rating-section mb-5 p-4 bg-light rounded-4 animate-fade-in">
                    @php $myRating = $article->ratings()->where('user_id',auth()->id())->first(); @endphp
                    <h3 class="h4 mb-3 text-gradient">
                        <i class="bi bi-star-fill me-2"></i>Noter cette publication
                    </h3>
                    <form action="{{ route('articles.rate',$article) }}" method="POST" class="d-inline-block">
                        @csrf
                        @if(!$myRating)
                            <div class="d-flex align-items-center flex-wrap gap-3">
                                <span class="fw-bold">Votre note :</span>
                                <div class="star-rating">
                                    @for($i=1;$i<=5;$i++)
                                        <button name="stars" value="{{ $i }}" 
                                                class="btn btn-lg p-1 star-btn" 
                                                type="submit"
                                                onmouseenter="highlightStars({{ $i }})"
                                                onmouseleave="resetStars()">
                                            <i class="bi bi-star"></i>
                                        </button>
                                    @endfor
                                </div>
                            </div>
                        @else
                            <div class="d-flex align-items-center gap-3">
                                <span class="fw-bold">Votre note :</span>
                                <div class="user-rating">
                                    @for($i=1;$i<=$myRating->stars;$i++)
                                        <i class="bi bi-star-fill text-warning fs-4"></i>
                                    @endfor
                                    <span class="ms-2 fw-bold fs-5">{{ $myRating->stars }}/5</span>
                                </div>
                            </div>
                        @endif
                    </form>
                    <div class="mt-3 text-muted">
                        <i class="bi bi-graph-up me-1"></i>
                        Moyenne : <strong>{{ number_format($article->ratings()->avg('stars'),2) }}</strong> / 5 
                        ({{ $article->ratings()->count() }} avis)
                    </div>
                </div>
            @endauth

            {{-- Comments Section --}}
            <section class="comments-section mb-5">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h2 class="h3 text-gradient mb-0">
                        <i class="bi bi-chat-text-fill me-2"></i>
                        Commentaires <span class="badge bg-primary rounded-pill">{{ $article->comments->count() }}</span>
                    </h2>
                    <button class="btn btn-outline-primary hover-lift" onclick="scrollToComments()">
                        <i class="bi bi-chat-square-quote me-1"></i>Commenter
                    </button>
                </div>

                {{-- Add Comment Form --}}
                @auth
                    <div class="comment-form-card card border-0 shadow-lg mb-4 animate-slide-up">
                        <div class="card-body p-4">
                            <form action="{{ route('articles.comments.store', $article) }}" method="POST" id="commentForm">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-bold fs-5">Ajouter un commentaire</label>
                                    <textarea name="body" rows="4" 
                                              class="form-control form-control-lg focus-shadow" 
                                              minlength="10" 
                                              required 
                                              placeholder="Partagez vos pensées sur cette publication..."
                                              oninput="autoResize(this)">{{ old('body') }}</textarea>
                                </div>
                                <button class="btn btn-primary btn-lg hover-lift">
                                    <i class="bi bi-send-fill me-2"></i>Publier le commentaire
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning border-0 shadow-lg animate-fade-in">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                            <div>
                                <strong>Connexion requise</strong>
                                <p class="mb-0">Vous devez être connecté pour commenter.</p>
                            </div>
                            <div class="ms-auto">
                                <a href="{{ route('login') }}" class="btn btn-primary btn-sm hover-lift">Se connecter</a>
                                <a href="{{ route('register') }}" class="btn btn-outline-primary btn-sm hover-lift ms-2">S'inscrire</a>
                            </div>
                        </div>
                    </div>
                @endauth

                {{-- Comments List --}}
                <div class="comments-list">
                    @forelse($article->comments->whereNull('parent_id') as $comment)
                        <div class="comment-card card border-0 shadow-sm mb-4 animate-scale-in">
                            <div class="card-body p-4">
                                {{-- Comment Header --}}
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-lg me-3">
                                            <div class="avatar-placeholder bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex align-items-center justify-content-center fw-bold" 
                                                 style="width: 50px; height: 50px;">
                                                {{ Str::substr($comment->user->name, 0, 1) }}
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold h5 mb-1">
                                                <a href="{{ route('user.article', $comment->user->id) }}" 
                                                   class="text-decoration-none text-dark hover-primary">
                                                    {{ $comment->user->name }}
                                                </a>
                                            </div>
                                            <div class="text-muted">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ $comment->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    {{-- Comment Actions --}}
                                    @if($comment->canEditOrDelete())
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary border-0 hover-lift" 
                                                    type="button" 
                                                    data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                                <li>
                                                    <a href="{{ route('comments.edit', $comment) }}" 
                                                       class="dropdown-item">
                                                        <i class="bi bi-pencil me-2"></i>Modifier
                                                    </a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('comments.destroy', $comment) }}" 
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
                                    <p class="mb-0 text-break fs-5">{{ $comment->body }}</p>
                                </div>

                                {{-- Comment Reactions --}}
                                <div class="comment-reactions mb-3">
                                    @php
                                        $reactions = [
                                            'like' => ['emoji' => '👍', 'class' => 'btn-outline-primary', 'icon' => 'bi-hand-thumbs-up'],
                                            'dislike' => ['emoji' => '👎', 'class' => 'btn-outline-secondary', 'icon' => 'bi-hand-thumbs-down'],
                                            'love' => ['emoji' => '❤️', 'class' => 'btn-outline-danger', 'icon' => 'bi-heart'],
                                            'laugh' => ['emoji' => '😂', 'class' => 'btn-outline-warning', 'icon' => 'bi-emoji-laughing'],
                                            'angry' => ['emoji' => '😡', 'class' => 'btn-outline-dark', 'icon' => 'bi-emoji-angry'],
                                            'sad' => ['emoji' => '😢', 'class' => 'btn-outline-info', 'icon' => 'bi-emoji-frown'],
                                        ];
                                    @endphp

                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach ($reactions as $type => $data)
                                            <form action="{{ route('comments.react', $comment) }}" method="POST" class="m-0">
                                                @csrf
                                                <input type="hidden" name="type" value="{{ $type }}">
                                                <button class="btn btn-sm {{ $data['class'] }} hover-lift reaction-btn">
                                                    <i class="{{ $data['icon'] }} me-1"></i>
                                                    <span>{{ $comment->reactions->where('type', $type)->count() }}</span>
                                                </button>
                                            </form>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Reply Form --}}
                                @auth
                                    <div class="reply-form mt-3">
                                        <form action="{{ route('comments.reply', $comment) }}" method="POST" class="reply-form-inner">
                                            @csrf
                                            <div class="input-group">
                                                <textarea name="body" 
                                                          class="form-control focus-shadow" 
                                                          rows="2" 
                                                          placeholder="Répondre à {{ $comment->user->name }}..."></textarea>
                                                <button class="btn btn-primary hover-lift">
                                                    <i class="bi bi-reply-fill"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                @endauth

                                {{-- Replies --}}
                                @if($comment->replies->count() > 0)
                                    <div class="replies-container mt-3 ps-3 ps-md-4 border-start border-3 border-primary">
                                        @foreach ($comment->replies as $reply)
                                            <div class="reply-card card border-0 bg-light mb-2 animate-fade-in">
                                                <div class="card-body p-3">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm me-2">
                                                                <div class="avatar-placeholder bg-light text-muted rounded-circle d-flex align-items-center justify-content-center fw-bold" 
                                                                     style="width: 35px; height: 35px; font-size: 0.8rem;">
                                                                    {{ Str::substr($reply->user->name, 0, 1) }}
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <div class="fw-bold">
                                                                    {{ $reply->user->name }}
                                                                </div>
                                                                <div class="small text-muted">
                                                                    {{ $reply->created_at->diffForHumans() }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-break">{{ $reply->body }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="empty-comments text-center py-5 text-muted animate-fade-in">
                            <i class="bi bi-chat-square display-1 mb-3"></i>
                            <h4 class="mb-2">Aucun commentaire pour le moment</h4>
                            <p class="mb-0">Soyez le premier à commenter cette publication !</p>
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
/* ===== GLOBAL STYLES ===== */
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

.floating-actions .btn {
  border-radius: 50px;
  padding: 1rem;
  box-shadow: var(--shadow-lg);
  font-weight: 600;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 60px;
  height: 60px;
}

.share-floating-btn {
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.1); }
}

/* ===== TYPOGRAPHY ===== */
.article-title {
  font-size: clamp(1.75rem, 4vw, 2.5rem);
  line-height: 1.3;
  word-wrap: break-word;
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

/* ===== BREADCRUMB ===== */
.breadcrumb {
  background: transparent;
  padding: 0;
}

.breadcrumb-item a {
  color: var(--primary-color);
  text-decoration: none;
  transition: var(--transition);
}

.breadcrumb-item a:hover {
  color: var(--secondary-color);
}

/* ===== AVATARS ===== */
.avatar-lg {
  width: 60px;
  height: 60px;
}

.avatar-sm {
  width: 35px;
  height: 35px;
}

.avatar-placeholder {
  font-weight: 600;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* ===== ARTICLE STATS ===== */
.article-stats {
  padding: 1rem 0;
}

.stat-item {
  padding: 0.5rem 1rem;
  background: rgba(67, 97, 238, 0.05);
  border-radius: 10px;
  transition: var(--transition);
}

.stat-item:hover {
  background: rgba(67, 97, 238, 0.1);
  transform: translateY(-2px);
}

/* ===== MEDIA CONTAINERS ===== */
.main-media-container {
  border-radius: 20px;
  overflow: hidden;
}

.video-player-container {
  position: relative;
  width: 100%;
  padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
  background: #000;
}

.main-video-player {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: contain;
}

.video-controls-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity 0.3s ease;
  background: rgba(0, 0, 0, 0.3);
}

.video-player-container:hover .video-controls-overlay {
  opacity: 1;
}

.control-buttons {
  display: flex;
  gap: 1rem;
}

.control-btn {
  border-radius: 50%;
  width: 60px;
  height: 60px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.image-ratio-container {
  position: relative;
  width: 100%;
  padding-bottom: 66.67%; /* Ratio 3:2 */
  overflow: hidden;
  background: #f8f9fa;
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
  transition: var(--transition);
}

/* ===== VIDEO THUMBNAILS ===== */
.video-thumbnail-container {
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

.video-thumbnail-container:hover .video-play-overlay {
  opacity: 0.8;
}

.play-icon-sm {
  width: 40px;
  height: 40px;
  background: rgba(255, 123, 0, 0.9);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 1rem;
}

/* ===== CONTENT BODY ===== */
.content-body {
  font-size: clamp(1rem, 2.5vw, 1.1rem);
  line-height: 1.7;
}

.content-body img {
  max-width: 100%;
  height: auto;
  border-radius: 12px;
  box-shadow: var(--shadow-sm);
}

.content-body p {
  margin-bottom: 1.5rem;
}

/* ===== ACTION BUTTONS ===== */
.action-buttons {
  padding: 2rem 0;
  border-top: 2px solid #f8f9fa;
  border-bottom: 2px solid #f8f9fa;
}

/* ===== RATING SECTION ===== */
.rating-section {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.star-rating .btn {
  transition: var(--transition);
}

.star-rating .btn:hover {
  transform: scale(1.2);
}

/* ===== COMMENTS SECTION ===== */
.comment-form-card {
  border-radius: 15px;
}

.comment-card {
  border-radius: 15px;
  transition: var(--transition);
}

.comment-card:hover {
  transform: translateY(-2px);
  box-shadow: var(--shadow-md) !important;
}

.replies-container {
  border-left-color: var(--primary-color) !important;
}

.reply-card {
  border-radius: 10px;
  transition: var(--transition);
}

.reply-card:hover {
  transform: translateX(5px);
}

/* ===== SHARE BUTTONS ===== */
.share-platform-btn {
  border-radius: 12px;
  transition: var(--transition);
  padding: 1rem 0.5rem;
}

.share-platform-btn:hover {
  transform: translateY(-5px) scale(1.05);
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

/* ===== HOVER EFFECTS ===== */
.hover-scale {
  transition: var(--transition);
}

.hover-scale:hover {
  transform: scale(1.05);
}

.hover-lift {
  transition: var(--transition);
}

.hover-lift:hover {
  transform: translateY(-2px);
  box-shadow: var(--shadow-md);
}

.hover-primary:hover {
  color: var(--primary-color) !important;
}

.focus-shadow:focus {
  box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25) !important;
  border-color: var(--primary-color);
}

.cursor-zoom {
  cursor: zoom-in;
}

.cursor-pointer {
  cursor: pointer;
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
  transition: var(--transition);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.lightbox-close:hover {
  background: rgba(255, 255, 255, 0.2);
  transform: scale(1.1);
}

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
  transition: var(--transition);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.lightbox-nav-btn:hover {
  background: rgba(255, 255, 255, 0.2);
  transform: scale(1.1);
}

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

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 768px) {
  .container-fluid {
    padding-left: 1rem;
    padding-right: 1rem;
  }
  
  .floating-actions {
    bottom: 1.5rem;
    right: 1.5rem;
  }
  
  .floating-actions .btn {
    width: 50px;
    height: 50px;
    padding: 0.875rem;
  }
  
  .article-stats {
    gap: 0.5rem;
  }
  
  .stat-item {
    padding: 0.5rem;
    font-size: 0.875rem;
  }
  
  .avatar-lg {
    width: 50px;
    height: 50px;
  }
  
  .control-btn {
    width: 50px;
    height: 50px;
  }
  
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
}

@media (max-width: 576px) {
  .floating-actions {
    bottom: 1rem;
    right: 1rem;
  }
  
  .floating-actions .btn {
    width: 45px;
    height: 45px;
  }
  
  .article-title {
    font-size: 1.5rem;
  }
  
  .content-body {
    font-size: 1rem;
  }
  
  .action-buttons .btn {
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
  }
  
  .comment-card .card-body {
    padding: 1.5rem;
  }
  
  .avatar-lg {
    width: 45px;
    height: 45px;
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

@media (max-width: 400px) {
  .floating-actions {
    bottom: 0.5rem;
    right: 0.5rem;
  }
  
  .article-stats {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
  }
  
  .stat-item {
    width: 100%;
    justify-content: flex-start;
  }
}

/* ===== UTILITY CLASSES ===== */
.text-break {
  word-wrap: break-word;
  overflow-wrap: break-word;
}

.copy-link-btn.copied {
  background-color: #28a745 !important;
}

.empty-comments i {
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
function playVideo(videoSrc) {
  const lightbox = document.getElementById('videoLightbox');
  const lightboxVideo = document.getElementById('lightboxVideo');
  
  lightbox.classList.add('active');
  lightboxVideo.src = videoSrc;
  lightboxVideo.load();
  lightboxVideo.play();
  
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

// ===== SHARE MODAL FUNCTIONALITY =====
function openShareModal() {
  const shareModal = new bootstrap.Modal(document.getElementById('shareModal'));
  shareModal.show();
}

// ===== STAR RATING FUNCTIONALITY =====
function highlightStars(count) {
  const stars = document.querySelectorAll('.star-btn');
  stars.forEach((star, index) => {
    const icon = star.querySelector('i');
    if (index < count) {
      icon.className = 'bi bi-star-fill text-warning';
    } else {
      icon.className = 'bi bi-star';
    }
  });
}

function resetStars() {
  const stars = document.querySelectorAll('.star-btn');
  stars.forEach(star => {
    star.querySelector('i').className = 'bi bi-star';
  });
}

// ===== SCROLL FUNCTIONS =====
function scrollToTop() {
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

function scrollToComments() {
  const commentsSection = document.querySelector('.comments-section');
  commentsSection.scrollIntoView({ behavior: 'smooth' });
}

// ===== AUTO-RESIZE TEXTAREA =====
function autoResize(textarea) {
  textarea.style.height = 'auto';
  textarea.style.height = textarea.scrollHeight + 'px';
}

// ===== INITIALIZATION =====
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
  
  // Copy link functionality
  const copyLinkBtn = document.querySelector('.copy-link-btn');
  if (copyLinkBtn) {
    copyLinkBtn.addEventListener('click', function() {
      navigator.clipboard.writeText('{{ request()->fullUrl() }}').then(() => {
        const originalHTML = this.innerHTML;
        this.innerHTML = '<i class="fas fa-check fa-lg"></i><span class="d-block mt-1 small">Lien copié</span>';
        this.classList.add('copied');
        
        setTimeout(() => {
          this.innerHTML = originalHTML;
          this.classList.remove('copied');
        }, 2000);
      });
    });
  }
  
  // Video controls
  const mainVideo = document.querySelector('.main-video-player');
  const playPauseBtn = document.querySelector('.play-pause-btn');
  const fullscreenBtn = document.querySelector('.fullscreen-btn');
  
  if (mainVideo && playPauseBtn) {
    playPauseBtn.addEventListener('click', function() {
      if (mainVideo.paused) {
        mainVideo.play();
        this.innerHTML = '<i class="bi bi-pause-fill"></i>';
      } else {
        mainVideo.pause();
        this.innerHTML = '<i class="bi bi-play-fill"></i>';
      }
    });
  }
  
  if (mainVideo && fullscreenBtn) {
    fullscreenBtn.addEventListener('click', function() {
      if (mainVideo.requestFullscreen) {
        mainVideo.requestFullscreen();
      } else if (mainVideo.webkitRequestFullscreen) {
        mainVideo.webkitRequestFullscreen();
      } else if (mainVideo.msRequestFullscreen) {
        mainVideo.msRequestFullscreen();
      }
    });
  }
  
  // Auto-resize comment textareas
  const textareas = document.querySelectorAll('textarea');
  textareas.forEach(textarea => {
    textarea.addEventListener('input', function() {
      autoResize(this);
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
  document.querySelectorAll('.animate-fade-in, .animate-slide-up, .animate-scale-in').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(20px) scale(0.95)';
    el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(el);
  });
  
  // Hide floating actions on scroll
  let lastScrollTop = 0;
  const floatingActions = document.querySelector('.floating-actions');
  
  if (floatingActions) {
    window.addEventListener('scroll', function() {
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
    }, { passive: true });
  }
});

// Social sharing with video support
function shareOnPlatform(platform, hasVideo = false) {
  const url = encodeURIComponent(window.location.href);
  const title = encodeURIComponent('{{ $article->title }}');
  const description = encodeURIComponent('{{ Str::limit(strip_tags($article->content), 150) }}');
  
  let shareUrl = '';
  
  switch(platform) {
    case 'facebook':
      shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
      break;
    case 'twitter':
      shareUrl = `https://twitter.com/intent/tweet?url=${url}&text=${title}`;
      break;
    case 'whatsapp':
      if (hasVideo) {
        shareUrl = `https://wa.me/?text=${title}%0A%0A🎥 Vidéo disponible : ${url}`;
      } else {
        shareUrl = `https://wa.me/?text=${title}%0A%0A${url}`;
      }
      break;
    case 'telegram':
      shareUrl = `https://t.me/share/url?url=${url}&text=${title}`;
      break;
    case 'linkedin':
      shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${url}`;
      break;
  }
  
  if (shareUrl) {
    window.open(shareUrl, '_blank', 'width=600,height=400');
  }
}
</script>
@endsection