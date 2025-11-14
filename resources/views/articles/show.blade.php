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
                $hasVideo = $mainVideo ? true : false;
                $hasImage = $mainImage ? true : false;
                $shareImage = $mainImage ?: ($article->media->where('type', 'image')->first()->file_path ?? null);
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

                {{-- Enhanced Media Gallery --}}
                @if($article->media->count() > 0)
                    <div class="media-gallery mb-5">
                        <h3 class="h4 mb-4 text-gradient text-center">
                            <i class="bi bi-images me-2"></i>Médias associés
                        </h3>
                        <div class="row g-4">
                            @foreach ($article->media as $index => $m)
                                <div class="col-12 col-sm-6 col-lg-4">
                                    <div class="media-card rounded-4 overflow-hidden shadow-lg border-0 animate-scale-in hover-scale" 
                                         data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                                        @if ($m->isImage())
                                            <div class="enhanced-image-container">
                                                <img src="{{ $m->file_path }}"
                                                     class="enhanced-media-item cursor-zoom"
                                                     alt="Média de l'article {{ $article->title }}"
                                                     onclick="openLightbox('{{ $m->file_path }}', '{{ $article->title }}')"
                                                     loading="lazy">
                                                <div class="media-overlay">
                                                    <div class="media-actions">
                                                        <button class="btn btn-light btn-sm rounded-pill hover-lift" 
                                                                onclick="openLightbox('{{ $m->file_path }}', '{{ $article->title }}')">
                                                            <i class="bi bi-zoom-in me-1"></i>Agrandir
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif ($m->isVideo())
                                            <div class="enhanced-video-container">
                                                <div class="enhanced-image-container">
                                                    <img src="{{ $article->image_path ?? asset('img/video-placeholder.jpg') }}"
                                                         class="enhanced-media-item cursor-pointer"
                                                         alt="Vidéo de l'article {{ $article->title }}"
                                                         onclick="playVideo('{{ $m->file_path }}')"
                                                         loading="lazy">
                                                    <div class="video-play-overlay">
                                                        <div class="play-icon-lg">
                                                            <i class="bi bi-play-fill"></i>
                                                        </div>
                                                    </div>
                                                    <div class="media-overlay">
                                                        <div class="media-actions">
                                                            <button class="btn btn-light btn-sm rounded-pill hover-lift" 
                                                                    onclick="playVideo('{{ $m->file_path }}')">
                                                                <i class="bi bi-play-fill me-1"></i>Lire
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif ($m->isAudio())
                                            <div class="audio-container p-4 bg-gradient-primary text-white rounded-4">
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-music-note-beamed display-6 me-3"></i>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-2">Fichier audio</h6>
                                                        <audio controls class="w-100 audio-player">
                                                            <source src="{{ $m->file_path }}" type="{{ $m->mime_type }}">
                                                            Votre navigateur ne supporte pas la lecture audio.
                                                        </audio>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="file-container p-4 bg-light rounded-4 text-center">
                                                <i class="bi bi-file-earmark-text display-4 text-primary mb-3"></i>
                                                <h6 class="mb-2">Document</h6>
                                                <a href="{{ $m->file_path }}" 
                                                   target="_blank" 
                                                   class="btn btn-primary hover-lift w-100">
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

            {{-- Enhanced Share Modal --}}
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
                                $content = $article->content ? Str::limit(strip_tags($article->content), 150) : '';
                                $description = urlencode($content);
                                $image = $shareImage ? urlencode($shareImage) : '';
                                
                                $video = null;
                                if ($article->media) {
                                    $video = $article->media->where('type', 'video')->first();
                                }
                                $hasVideo = (bool) $video;
                                $videoUrl = '';
                                if ($video) {
                                    $videoUrl = urlencode($video->file_path);
                                }
                                
                                // WhatsApp message with enhanced formatting
                                $whatsappContent = $article->content ? Str::limit(strip_tags($article->content), 200) : 'Découvrez cette publication intéressante';
                                $whatsappMessage = urlencode("📢 *{$article->title}*

{$whatsappContent}

🔗 " . request()->fullUrl() . "

📱 Partagé via FlashPost");
                            @endphp
                            
                            <div class="row g-3">
                                {{-- WhatsApp --}}
                                <div class="col-6 col-sm-4">
                                    <a class="share-platform-btn btn btn-lg w-100 text-white hover-lift" 
                                       style="background:#25D366" 
                                       href="https://wa.me/?text={{ $whatsappMessage }}"
                                       target="_blank"
                                       onclick="trackShare('whatsapp')">
                                        <i class="fab fa-whatsapp fa-2x mb-2"></i>
                                        <span class="d-block fw-bold">WhatsApp</span>
                                        <small class="opacity-75">Partager avec contacts</small>
                                    </a>
                                </div>
                                
                                {{-- Facebook --}}
                                <div class="col-6 col-sm-4">
                                    <a class="share-platform-btn btn btn-lg w-100 text-white hover-lift" 
                                       style="background:#1877F2" 
                                       href="https://www.facebook.com/sharer/sharer.php?u={{ $url }}&quote={{ $title }}"
                                       target="_blank"
                                       onclick="trackShare('facebook')">
                                        <i class="fab fa-facebook-f fa-2x mb-2"></i>
                                        <span class="d-block fw-bold">Facebook</span>
                                        <small class="opacity-75">Partager sur Facebook</small>
                                    </a>
                                </div>
                                
                                {{-- Twitter --}}
                                <div class="col-6 col-sm-4">
                                    <a class="share-platform-btn btn btn-lg w-100 text-white hover-lift" 
                                       style="background:#000000" 
                                       href="https://twitter.com/intent/tweet?url={{ $url }}&text={{ $title }}&hashtags=FlashPost"
                                       target="_blank"
                                       onclick="trackShare('twitter')">
                                        <i class="fab fa-x-twitter fa-2x mb-2"></i>
                                        <span class="d-block fw-bold">Twitter</span>
                                        <small class="opacity-75">Tweet cette publication</small>
                                    </a>
                                </div>
                                
                                {{-- Telegram --}}
                                <div class="col-6 col-sm-4">
                                    <a class="share-platform-btn btn btn-lg w-100 text-white hover-lift" 
                                       style="background:#0088cc" 
                                       href="https://t.me/share/url?url={{ $url }}&text={{ $title }}"
                                       target="_blank"
                                       onclick="trackShare('telegram')">
                                        <i class="fab fa-telegram fa-2x mb-2"></i>
                                        <span class="d-block fw-bold">Telegram</span>
                                        <small class="opacity-75">Partager sur Telegram</small>
                                    </a>
                                </div>
                                
                                {{-- LinkedIn --}}
                                <div class="col-6 col-sm-4">
                                    <a class="share-platform-btn btn btn-lg w-100 text-white hover-lift" 
                                       style="background:#0A66C2" 
                                       href="https://www.linkedin.com/sharing/share-offsite/?url={{ $url }}"
                                       target="_blank"
                                       onclick="trackShare('linkedin')">
                                        <i class="fab fa-linkedin-in fa-2x mb-2"></i>
                                        <span class="d-block fw-bold">LinkedIn</span>
                                        <small class="opacity-75">Partager professionnellement</small>
                                    </a>
                                </div>
                                
                                {{-- Copy Link --}}
                                <div class="col-6 col-sm-4">
                                    <button class="share-platform-btn btn btn-lg w-100 text-white hover-lift copy-link-btn" 
                                            style="background:#6c757d"
                                            onclick="copyToClipboard()">
                                        <i class="fas fa-copy fa-2x mb-2"></i>
                                        <span class="d-block fw-bold">Copier le lien</span>
                                        <small class="opacity-75">Coller où vous voulez</small>
                                    </button>
                                </div>
                            </div>

                            {{-- Share Preview --}}
                            <div class="share-preview mt-4 p-3 bg-light rounded-3">
                                <div class="d-flex align-items-center">
                                    @if($shareImage)
                                        <img src="{{ $shareImage }}" 
                                             class="share-preview-image rounded-2 me-3"
                                             alt="Preview"
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="share-preview-placeholder bg-primary bg-opacity-10 rounded-2 d-flex align-items-center justify-content-center me-3"
                                             style="width: 60px; height: 60px;">
                                            <i class="bi bi-file-text text-primary"></i>
                                        </div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold">{{ Str::limit($article->title, 50) }}</h6>
                                        <p class="mb-0 text-muted small">{{ Str::limit(strip_tags($article->content), 80) }}</p>
                                        <small class="text-primary">{{ request()->getHost() }}</small>
                                    </div>
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

<!-- Enhanced Lightbox Modal -->
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
        <div class="lightbox-actions">
            <button class="lightbox-action-btn" onclick="downloadMedia()">
                <i class="bi bi-download"></i>
            </button>
            <button class="lightbox-action-btn" onclick="shareMedia()">
                <i class="bi bi-share"></i>
            </button>
        </div>
    </div>
</div>

<!-- Enhanced Video Lightbox Modal -->
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
        <div class="video-lightbox-actions">
            <button class="btn btn-primary hover-lift" onclick="shareVideo()">
                <i class="bi bi-share me-2"></i>Partager cette vidéo
            </button>
        </div>
    </div>
</div>

<!-- Success Toast -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="successToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-success text-white">
            <i class="bi bi-check-circle-fill me-2"></i>
            <strong class="me-auto">Succès</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body bg-light">
            Lien copié dans le presse-papiers !
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
  --shadow-xl: 0 20px 60px rgba(0, 0, 0, 0.2);
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
  box-shadow: var(--shadow-xl);
  font-weight: 600;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 60px;
  height: 60px;
  transition: var(--transition);
}

.share-floating-btn {
  animation: pulse 2s infinite;
  background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
  border: none;
}

@keyframes pulse {
  0%, 100% { 
    transform: scale(1);
    box-shadow: var(--shadow-xl);
  }
  50% { 
    transform: scale(1.1);
    box-shadow: 0 0 30px rgba(67, 97, 238, 0.6);
  }
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

/* ===== ENHANCED MEDIA GALLERY ===== */
.media-gallery .row {
  animation: fadeInUp 0.8s ease-out;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.enhanced-image-container {
  position: relative;
  width: 100%;
  padding-bottom: 75%; /* 4:3 Aspect Ratio for better mobile display */
  overflow: hidden;
  background: #f8f9fa;
  border-radius: 16px;
}

.enhanced-media-item {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  border-radius: 16px;
}

.media-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(to bottom, transparent 60%, rgba(0,0,0,0.7) 100%);
  opacity: 0;
  transition: var(--transition);
  border-radius: 16px;
  display: flex;
  align-items: flex-end;
  justify-content: center;
  padding: 1rem;
}

.media-card:hover .media-overlay {
  opacity: 1;
}

.media-card:hover .enhanced-media-item {
  transform: scale(1.05);
}

.media-actions {
  transform: translateY(20px);
  transition: var(--transition);
}

.media-card:hover .media-actions {
  transform: translateY(0);
}

/* Enhanced Video Play Button */
.play-icon-lg {
  width: 80px;
  height: 80px;
  background: rgba(255, 123, 0, 0.95);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 2rem;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  transition: var(--transition);
  backdrop-filter: blur(10px);
  border: 3px solid rgba(255, 255, 255, 0.3);
}

.enhanced-video-container:hover .play-icon-lg {
  background: rgba(255, 123, 0, 1);
  transform: translate(-50%, -50%) scale(1.1);
}

/* Audio Player Enhancement */
.audio-container {
  background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)) !important;
  transition: var(--transition);
}

.audio-container:hover {
  transform: translateY(-5px);
  box-shadow: var(--shadow-lg);
}

.audio-player {
  border-radius: 25px;
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
}

.audio-player::-webkit-media-controls-panel {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
}

/* Enhanced Share Modal */
.share-platform-btn {
  border-radius: 16px;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  padding: 1.5rem 0.5rem;
  border: none;
  position: relative;
  overflow: hidden;
}

.share-platform-btn::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
  transition: left 0.5s;
}

.share-platform-btn:hover::before {
  left: 100%;
}

.share-platform-btn:hover {
  transform: translateY(-8px) scale(1.05);
  box-shadow: var(--shadow-xl);
}

.share-preview {
  border: 2px solid #e9ecef;
  transition: var(--transition);
}

.share-preview:hover {
  border-color: var(--primary-color);
  transform: translateY(-2px);
}

/* Enhanced Lightbox */
.lightbox {
  display: none;
  position: fixed;
  z-index: 9999;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.98);
  backdrop-filter: blur(20px);
  opacity: 0;
  transition: opacity 0.4s ease;
}

.lightbox.active {
  display: flex;
  align-items: center;
  justify-content: center;
  animation: lightboxFadeIn 0.4s ease forwards;
}

@keyframes lightboxFadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

.lightbox-content {
  position: relative;
  width: 95%;
  max-width: 1400px;
  max-height: 95vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.video-lightbox-content {
  max-width: 1000px;
}

.lightbox-close {
  position: absolute;
  top: 20px;
  right: 20px;
  color: white;
  font-size: 2rem;
  cursor: pointer;
  background: rgba(255, 255, 255, 0.15);
  width: 60px;
  height: 60px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: var(--transition);
  backdrop-filter: blur(20px);
  border: 2px solid rgba(255, 255, 255, 0.3);
  z-index: 10001;
}

.lightbox-close:hover {
  background: rgba(255, 255, 255, 0.25);
  transform: scale(1.1) rotate(90deg);
}

.lightbox-image-container {
  position: relative;
  width: 100%;
  max-height: 85vh;
  display: flex;
  align-items: center;
  justify-content: center;
}

.lightbox-image {
  max-width: 100%;
  max-height: 85vh;
  object-fit: contain;
  border-radius: 20px;
  box-shadow: 0 25px 80px rgba(0, 0, 0, 0.6);
  animation: imageZoomIn 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes imageZoomIn {
  from {
    opacity: 0;
    transform: scale(0.8) translateY(20px);
  }
  to {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}

.video-container {
  width: 100%;
  max-height: 85vh;
  border-radius: 20px;
  overflow: hidden;
}

.lightbox-video {
  width: 100%;
  height: auto;
  max-height: 85vh;
  border-radius: 20px;
  box-shadow: 0 25px 80px rgba(0, 0, 0, 0.6);
}

.lightbox-caption {
  position: absolute;
  bottom: -70px;
  left: 0;
  width: 100%;
  text-align: center;
  color: white;
  font-size: 1.2rem;
  padding: 1.5rem;
  background: rgba(0, 0, 0, 0.8);
  border-radius: 12px;
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.lightbox-nav {
  position: absolute;
  top: 50%;
  width: 100%;
  display: flex;
  justify-content: space-between;
  transform: translateY(-50%);
  padding: 0 3rem;
  z-index: 10000;
}

.lightbox-nav-btn {
  background: rgba(255, 255, 255, 0.15);
  color: white;
  border: none;
  font-size: 1.8rem;
  width: 70px;
  height: 70px;
  border-radius: 50%;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: var(--transition);
  backdrop-filter: blur(20px);
  border: 2px solid rgba(255, 255, 255, 0.3);
}

.lightbox-nav-btn:hover {
  background: rgba(255, 255, 255, 0.25);
  transform: scale(1.1);
}

.lightbox-counter {
  position: absolute;
  top: 20px;
  left: 20px;
  color: white;
  font-size: 1.1rem;
  background: rgba(255, 255, 255, 0.15);
  padding: 0.75rem 1.5rem;
  border-radius: 25px;
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  z-index: 10000;
}

.lightbox-actions {
  position: absolute;
  bottom: 20px;
  right: 20px;
  display: flex;
  gap: 1rem;
  z-index: 10000;
}

.lightbox-action-btn {
  background: rgba(255, 255, 255, 0.15);
  color: white;
  border: none;
  width: 50px;
  height: 50px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: var(--transition);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.lightbox-action-btn:hover {
  background: rgba(255, 255, 255, 0.25);
  transform: scale(1.1);
}

.video-lightbox-actions {
  position: absolute;
  bottom: 20px;
  left: 50%;
  transform: translateX(-50%);
  z-index: 10000;
}

/* ===== RESPONSIVE DESIGN ENHANCEMENTS ===== */
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
    width: 55px;
    height: 55px;
  }
  
  .enhanced-image-container {
    padding-bottom: 100%; /* Square aspect ratio for mobile */
  }
  
  .media-gallery .row {
    gap: 1rem !important;
  }
  
  .media-card {
    margin-bottom: 1rem;
  }
  
  .play-icon-lg {
    width: 60px;
    height: 60px;
    font-size: 1.5rem;
  }
  
  .share-platform-btn {
    padding: 1rem 0.5rem;
  }
  
  .share-platform-btn i {
    font-size: 1.5rem !important;
  }
  
  .lightbox-content {
    width: 98%;
    max-height: 90vh;
  }
  
  .lightbox-close {
    top: 10px;
    right: 10px;
    width: 50px;
    height: 50px;
    font-size: 1.5rem;
  }
  
  .lightbox-nav {
    padding: 0 1rem;
  }
  
  .lightbox-nav-btn {
    width: 55px;
    height: 55px;
    font-size: 1.5rem;
  }
  
  .lightbox-counter {
    top: 10px;
    left: 10px;
    font-size: 1rem;
    padding: 0.5rem 1rem;
  }
  
  .lightbox-caption {
    bottom: -60px;
    font-size: 1rem;
    padding: 1rem;
  }
  
  .lightbox-actions {
    bottom: 10px;
    right: 10px;
  }
  
  .lightbox-action-btn {
    width: 45px;
    height: 45px;
  }
}

@media (max-width: 576px) {
  .floating-actions {
    bottom: 1rem;
    right: 1rem;
  }
  
  .floating-actions .btn {
    width: 50px;
    height: 50px;
  }
  
  .article-title {
    font-size: 1.5rem;
  }
  
  .media-gallery .row {
    gap: 0.5rem !important;
  }
  
  .col-12.col-sm-6.col-lg-4 {
    padding: 0.25rem;
  }
  
  .enhanced-image-container {
    padding-bottom: 120%; /* Taller aspect ratio for better mobile viewing */
  }
  
  .share-platform-btn {
    padding: 0.75rem 0.25rem;
  }
  
  .share-platform-btn span {
    font-size: 0.875rem;
  }
  
  .share-platform-btn small {
    font-size: 0.75rem;
  }
  
  .lightbox-nav-btn {
    width: 45px;
    height: 45px;
    font-size: 1.2rem;
  }
  
  .lightbox-close {
    width: 45px;
    height: 45px;
    font-size: 1.2rem;
  }
}

@media (max-width: 400px) {
  .floating-actions {
    bottom: 0.5rem;
    right: 0.5rem;
  }
  
  .media-gallery .row {
    gap: 0.25rem !important;
  }
  
  .col-12.col-sm-6.col-lg-4 {
    padding: 0.125rem;
  }
  
  .share-platform-btn {
    padding: 0.5rem 0.125rem;
  }
  
  .share-platform-btn i {
    font-size: 1.25rem !important;
    margin-bottom: 0.25rem !important;
  }
  
  .share-platform-btn span {
    font-size: 0.8rem;
  }
  
  .share-platform-btn small {
    display: none;
  }
}

/* ===== ANIMATION ENHANCEMENTS ===== */
.animate-fade-in {
  animation: fadeIn 0.8s ease-in-out;
}

.animate-slide-up {
  animation: slideUp 0.8s ease-out;
}

.animate-scale-in {
  animation: scaleIn 0.6s ease-out;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes slideUp {
  from { 
    opacity: 0;
    transform: translateY(40px);
  }
  to { 
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes scaleIn {
  from { 
    opacity: 0;
    transform: scale(0.8);
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
  transform: translateY(-5px);
  box-shadow: var(--shadow-lg);
}

.hover-primary:hover {
  color: var(--primary-color) !important;
}

.focus-shadow:focus {
  box-shadow: 0 0 0 0.3rem rgba(67, 97, 238, 0.25) !important;
  border-color: var(--primary-color);
}

.cursor-zoom {
  cursor: zoom-in;
}

.cursor-pointer {
  cursor: pointer;
}

/* ===== UTILITY CLASSES ===== */
.text-break {
  word-wrap: break-word;
  overflow-wrap: break-word;
}

.copy-link-btn.copied {
  background-color: #28a745 !important;
  animation: bounce 0.5s;
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

.empty-comments i {
  animation: bounce 2s infinite;
}

/* Smooth scrolling */
html {
  scroll-behavior: smooth;
}

/* Loading animation for images */
.enhanced-media-item {
  background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
  background-size: 200% 100%;
  animation: loading 1.5s infinite;
}

@keyframes loading {
  0% {
    background-position: 200% 0;
  }
  100% {
    background-position: -200% 0;
  }
}

.enhanced-media-item[src] {
  animation: none;
}
</style>

<script>
// ===== ENHANCED LIGHTBOX FUNCTIONALITY =====
let currentLightboxIndex = 0;
let lightboxImages = [];

function openLightbox(imageSrc, caption) {
  const lightbox = document.getElementById('lightbox');
  const lightboxImg = document.getElementById('lightboxImage');
  const captionText = document.getElementById('lightboxCaption');
  const counter = document.getElementById('lightboxCounter');
  
  // Collect all clickable images from enhanced media gallery
  lightboxImages = Array.from(document.querySelectorAll('.enhanced-media-item[src]'));
  currentLightboxIndex = lightboxImages.findIndex(img => img.src.includes(imageSrc));
  
  if (currentLightboxIndex === -1) {
    currentLightboxIndex = 0;
  }
  
  // Show lightbox with enhanced animation
  lightbox.style.display = 'flex';
  setTimeout(() => {
    lightbox.classList.add('active');
  }, 10);
  
  lightboxImg.src = imageSrc;
  captionText.innerHTML = caption || '';
  updateLightboxCounter();
  
  document.body.style.overflow = 'hidden';
  
  // Add keyboard event listeners
  document.addEventListener('keydown', handleLightboxKeydown);
}

function closeLightbox() {
  const lightbox = document.getElementById('lightbox');
  lightbox.classList.remove('active');
  
  setTimeout(() => {
    lightbox.style.display = 'none';
  }, 400);
  
  document.body.style.overflow = 'auto';
  document.removeEventListener('keydown', handleLightboxKeydown);
}

function handleLightboxKeydown(event) {
  if (event.key === 'Escape') {
    closeLightbox();
  } else if (event.key === 'ArrowLeft') {
    navigateLightbox(-1);
  } else if (event.key === 'ArrowRight') {
    navigateLightbox(1);
  }
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
  
  // Add smooth transition
  lightboxImg.style.opacity = '0';
  lightboxImg.style.transform = 'scale(0.95)';
  
  setTimeout(() => {
    lightboxImg.src = lightboxImages[currentLightboxIndex].src;
    captionText.innerHTML = lightboxImages[currentLightboxIndex].alt || '';
    lightboxImg.style.opacity = '1';
    lightboxImg.style.transform = 'scale(1)';
    updateLightboxCounter();
  }, 300);
}

function updateLightboxCounter() {
  const counter = document.getElementById('lightboxCounter');
  counter.textContent = `${currentLightboxIndex + 1}/${lightboxImages.length}`;
}

function downloadMedia() {
  const lightboxImg = document.getElementById('lightboxImage');
  const link = document.createElement('a');
  link.href = lightboxImg.src;
  link.download = 'flashpost-image.jpg';
  link.click();
}

function shareMedia() {
  const lightboxImg = document.getElementById('lightboxImage');
  if (navigator.share) {
    navigator.share({
      title: '{{ $article->title }}',
      text: '{{ Str::limit(strip_tags($article->content), 100) }}',
      url: window.location.href,
    })
    .catch(console.error);
  } else {
    openShareModal();
  }
}

// ===== ENHANCED VIDEO LIGHTBOX FUNCTIONALITY =====
function playVideo(videoSrc) {
  const lightbox = document.getElementById('videoLightbox');
  const lightboxVideo = document.getElementById('lightboxVideo');
  
  lightbox.style.display = 'flex';
  setTimeout(() => {
    lightbox.classList.add('active');
  }, 10);
  
  lightboxVideo.src = videoSrc;
  lightboxVideo.load();
  lightboxVideo.play().catch(e => {
    console.log('Autoplay prevented:', e);
  });
  
  document.body.style.overflow = 'hidden';
  document.addEventListener('keydown', handleVideoLightboxKeydown);
}

function closeVideoLightbox() {
  const lightbox = document.getElementById('videoLightbox');
  const lightboxVideo = document.getElementById('lightboxVideo');
  
  lightbox.classList.remove('active');
  lightboxVideo.pause();
  
  setTimeout(() => {
    lightbox.style.display = 'none';
    lightboxVideo.src = '';
  }, 400);
  
  document.body.style.overflow = 'auto';
  document.removeEventListener('keydown', handleVideoLightboxKeydown);
}

function handleVideoLightboxKeydown(event) {
  if (event.key === 'Escape') {
    closeVideoLightbox();
  }
}

function shareVideo() {
  if (navigator.share) {
    navigator.share({
      title: '{{ $article->title }} - Vidéo',
      text: '🎥 Regardez cette vidéo: {{ Str::limit(strip_tags($article->content), 100) }}',
      url: window.location.href,
    })
    .catch(console.error);
  } else {
    openShareModal();
  }
}

// ===== ENHANCED SHARE FUNCTIONALITY =====
function openShareModal() {
  const shareModal = new bootstrap.Modal(document.getElementById('shareModal'));
  shareModal.show();
}

function trackShare(platform) {
  // Analytics tracking would go here
  console.log(`Shared on ${platform}`);
}

function copyToClipboard() {
  const url = '{{ request()->fullUrl() }}';
  navigator.clipboard.writeText(url).then(() => {
    const copyBtn = document.querySelector('.copy-link-btn');
    const originalHTML = copyBtn.innerHTML;
    
    copyBtn.innerHTML = '<i class="fas fa-check fa-2x mb-2"></i><span class="d-block fw-bold">Lien copié!</span><small class="opacity-75">Coller où vous voulez</small>';
    copyBtn.classList.add('copied');
    
    // Show success toast
    const toast = new bootstrap.Toast(document.getElementById('successToast'));
    toast.show();
    
    setTimeout(() => {
      copyBtn.innerHTML = originalHTML;
      copyBtn.classList.remove('copied');
    }, 3000);
  }).catch(err => {
    console.error('Failed to copy: ', err);
  });
}

// ===== STAR RATING FUNCTIONALITY =====
function highlightStars(count) {
  const stars = document.querySelectorAll('.star-btn');
  stars.forEach((star, index) => {
    const icon = star.querySelector('i');
    if (index < count) {
      icon.className = 'bi bi-star-fill text-warning';
      star.style.transform = 'scale(1.1)';
    } else {
      icon.className = 'bi bi-star';
      star.style.transform = 'scale(1)';
    }
  });
}

function resetStars() {
  const stars = document.querySelectorAll('.star-btn');
  stars.forEach(star => {
    star.querySelector('i').className = 'bi bi-star';
    star.style.transform = 'scale(1)';
  });
}

// ===== SCROLL FUNCTIONS =====
function scrollToTop() {
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

function scrollToComments() {
  const commentsSection = document.querySelector('.comments-section');
  commentsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// ===== AUTO-RESIZE TEXTAREA =====
function autoResize(textarea) {
  textarea.style.height = 'auto';
  textarea.style.height = Math.min(textarea.scrollHeight, 200) + 'px';
}

// ===== ENHANCED INITIALIZATION =====
document.addEventListener('DOMContentLoaded', function() {
  // Initialize all enhanced functionality
  initializeEnhancedMedia();
  initializeEventListeners();
  initializeAnimations();
});

function initializeEnhancedMedia() {
  // Lazy loading for enhanced media items
  const lazyImages = document.querySelectorAll('.enhanced-media-item');
  
  const imageObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const img = entry.target;
        img.src = img.dataset.src || img.src;
        img.classList.remove('lazy');
        imageObserver.unobserve(img);
      }
    });
  });

  lazyImages.forEach(img => imageObserver.observe(img));
}

function initializeEventListeners() {
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
  
  // Video controls for main video
  const mainVideo = document.querySelector('.main-video-player');
  const playPauseBtn = document.querySelector('.play-pause-btn');
  const fullscreenBtn = document.querySelector('.fullscreen-btn');
  
  if (mainVideo && playPauseBtn) {
    playPauseBtn.addEventListener('click', function(e) {
      e.stopPropagation();
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
    fullscreenBtn.addEventListener('click', function(e) {
      e.stopPropagation();
      if (mainVideo.requestFullscreen) {
        mainVideo.requestFullscreen();
      } else if (mainVideo.webkitRequestFullscreen) {
        mainVideo.webkitRequestFullscreen();
      } else if (mainVideo.msRequestFullscreen) {
        mainVideo.msRequestFullscreen();
      }
    });
  }
  
  // Auto-resize all textareas
  const textareas = document.querySelectorAll('textarea');
  textareas.forEach(textarea => {
    textarea.addEventListener('input', function() {
      autoResize(this);
    });
    // Initial resize
    autoResize(textarea);
  });
}

function initializeAnimations() {
  // Enhanced intersection observer for animations
  const observerOptions = {
    threshold: 0.1,
    rootMargin: '50px'
  };
  
  const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = '1';
        entry.target.style.transform = 'translateY(0) scale(1)';
        
        // Add sequential animation for media cards
        if (entry.target.classList.contains('media-card')) {
          const delay = Array.from(entry.target.parentNode.children).indexOf(entry.target) * 100;
          entry.target.style.animationDelay = `${delay}ms`;
        }
      }
    });
  }, observerOptions);
  
  // Observe all animated elements
  document.querySelectorAll('.animate-fade-in, .animate-slide-up, .animate-scale-in, .media-card').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(30px) scale(0.95)';
    el.style.transition = 'opacity 0.8s ease, transform 0.8s ease, box-shadow 0.3s ease';
    observer.observe(el);
  });
  
  // Hide floating actions on scroll with enhanced behavior
  let lastScrollTop = 0;
  const floatingActions = document.querySelector('.floating-actions');
  const scrollThreshold = 100;
  
  if (floatingActions) {
    window.addEventListener('scroll', function() {
      const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
      const scrollDelta = scrollTop - lastScrollTop;
      
      if (Math.abs(scrollDelta) > 5) { // Only trigger on significant scroll
        if (scrollTop > lastScrollTop && scrollTop > scrollThreshold) {
          // Scrolling down
          floatingActions.style.transform = 'translateY(120px)';
          floatingActions.style.opacity = '0';
        } else {
          // Scrolling up
          floatingActions.style.transform = 'translateY(0)';
          floatingActions.style.opacity = '1';
        }
      }
      
      lastScrollTop = scrollTop;
    }, { passive: true });
  }
}

// Touch gesture support for mobile
let touchStartX = 0;
let touchEndX = 0;

document.addEventListener('touchstart', e => {
  touchStartX = e.changedTouches[0].screenX;
});

document.addEventListener('touchend', e => {
  touchEndX = e.changedTouches[0].screenX;
  handleSwipe();
});

function handleSwipe() {
  const lightbox = document.getElementById('lightbox');
  if (!lightbox.classList.contains('active')) return;
  
  const swipeThreshold = 50;
  const swipeDistance = touchEndX - touchStartX;
  
  if (Math.abs(swipeDistance) > swipeThreshold) {
    if (swipeDistance > 0) {
      navigateLightbox(-1); // Swipe right - previous
    } else {
      navigateLightbox(1); // Swipe left - next
    }
  }
}

// Enhanced Social Sharing with Image Support
function shareOnPlatform(platform) {
  const url = encodeURIComponent(window.location.href);
  const title = encodeURIComponent('{{ $article->title }}');
  const description = encodeURIComponent('{{ Str::limit(strip_tags($article->content), 150) }}');
  const image = '{{ $shareImage ? urlencode($shareImage) : "" }}';
  const hasVideo = {{ $hasVideo ? 'true' : 'false' }};
  
  let shareUrl = '';
  
  switch(platform) {
    case 'whatsapp':
      shareUrl = `https://wa.me/?text=${encodeURIComponent(`📢 *${'{{ $article->title }}'}*

${'{{ Str::limit(strip_tags($article->content), 200) }}'}

${hasVideo ? '🎥 Vidéo incluse - ' : '📷 '}🔗 ${window.location.href}

📱 Partagé via FlashPost`)}`;
      break;
      
    case 'facebook':
      shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}&quote=${title}`;
      break;
      
    case 'twitter':
      shareUrl = `https://twitter.com/intent/tweet?url=${url}&text=${encodeURIComponent(`${title} ${hasVideo ? '🎥' : '📷'} via @FlashPost`)}`;
      break;
      
    case 'telegram':
      shareUrl = `https://t.me/share/url?url=${url}&text=${title}`;
      break;
      
    case 'linkedin':
      shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${url}`;
      break;
  }
  
  if (shareUrl) {
    window.open(shareUrl, '_blank', 'width=600,height=500,menubar=no,toolbar=no,resizable=yes,scrollbars=yes');
  }
}

// Enhanced Web Share API for native sharing
function shareNative() {
  if (navigator.share) {
    navigator.share({
      title: '{{ $article->title }}',
      text: '{{ Str::limit(strip_tags($article->content), 150) }}',
      url: window.location.href,
    })
    .then(() => console.log('Successful share'))
    .catch((error) => console.log('Error sharing:', error));
  } else {
    openShareModal();
  }
}

// Update the share floating button to use native sharing when available
document.addEventListener('DOMContentLoaded', function() {
  const shareFloatingBtn = document.querySelector('.share-floating-btn');
  if (navigator.share && shareFloatingBtn) {
    shareFloatingBtn.onclick = shareNative;
  }
});
</script>

<!-- Open Graph Meta Tags for Social Media Sharing -->
<meta property="og:title" content="{{ $article->title }}">
<meta property="og:description" content="{{ Str::limit(strip_tags($article->content), 150) }}">
<meta property="og:image" content="{{ $shareImage ? url($shareImage) : asset('img/default-share-image.jpg') }}">
<meta property="og:url" content="{{ request()->fullUrl() }}">
<meta property="og:type" content="article">
<meta property="og:site_name" content="FlashPost">

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $article->title }}">
<meta name="twitter:description" content="{{ Str::limit(strip_tags($article->content), 150) }}">
<meta name="twitter:image" content="{{ $shareImage ? url($shareImage) : asset('img/default-share-image.jpg') }}">
@endsection