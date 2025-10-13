@extends('layouts.app')

@section('content')
<div class="container-fluid px-3 px-md-4 px-lg-5 py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            {{-- Article Header --}}
            <div class="article-header mb-4 animate-fade-in">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-3">
                    <div class="flex-grow-1">
                        <h1 class="display-5 fw-bold text-dark mb-2 article-title">{{ $article->title }}</h1>
                        
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
                         class="img-fluid w-100 article-main-image" 
                         alt="Image article {{ $article->title }}"
                         loading="eager">
                </div>
            @endif

            {{-- Article Content --}}
            <div class="article-content mb-5">
                <div class="content-body fs-5 lh-base text-dark mb-4 animate-fade-in">
                    {!! $article->content !!}
                </div>

                {{-- Media Gallery --}}
                @if($article->media->count() > 0)
                    <div class="media-gallery mb-4">
                        <h3 class="h5 mb-3 text-muted">Médias associés</h3>
                        <div class="row g-3">
                            @foreach ($article->media as $m)
                                <div class="col-12 col-sm-6 col-lg-4">
                                    <div class="media-card rounded-3 overflow-hidden shadow-sm border-0 h-100 animate-scale-in">
                                        @if ($m->isImage())
                                            <img src="{{ $m->file_path }}"
                                                 class="img-fluid w-100 media-item"
                                                 alt="Média de l'article"
                                                 style="height: 200px; object-fit: cover;"
                                                 loading="lazy">
                                        @elseif ($m->isVideo())
                                            <video controls class="img-fluid w-100 media-item" 
                                                   style="height: 200px; object-fit: cover;">
                                                <source src="{{ $m->file_path }}" type="{{ $m->mime_type }}">
                                                Votre navigateur ne supporte pas la lecture de vidéos.
                                            </video>
                                        @elseif ($m->isAudio())
                                            <div class="p-3 bg-light h-100 d-flex align-items-center">
                                                <audio controls class="w-100">
                                                    <source src="{{ asset('storage/'.$m->file_path) }}" type="{{ $m->mime_type }}">
                                                    Votre navigateur ne supporte pas la lecture audio.
                                                </audio>
                                            </div>
                                        @else
                                            <div class="p-3 bg-light h-100 d-flex align-items-center justify-content-center">
                                                <a href="{{ asset('storage/'.$m->file_path) }}" 
                                                   target="_blank" 
                                                   class="btn btn-outline-primary hover-lift">
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

                    {{-- WhatsApp --}}
                    <a class="btn btn-sm text-white hover-lift share-btn" style="background:#25D366" target="_blank"
                    href="https://api.whatsapp.com/send?text={{ $title }}%20{{ $url }}">
                    <i class="fab fa-whatsapp me-2"></i>WhatsApp
                    </a>

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
                    {{-- Copy with Image --}}
                    <button class="btn btn-sm text-white hover-lift share-btn copy-link-btn" style="background:#6c757d">
                        <i class="fas fa-copy me-2"></i>Copier le lien
                    </button>
                </div>
            </div>

            <style>
            .share-btn {
                position: relative;
                overflow: hidden;
                transition: all 0.3s ease;
            }

            .share-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            }

            .copy-with-image-btn.copied {
                background-color: #28a745 !important;
            }

            /* Responsive adjustments */
            @media (max-width: 768px) {
                .share-section .d-flex {
                    justify-content: center;
                }
                
                .share-btn {
                    flex: 1;
                    min-width: 140px;
                    margin-bottom: 0.5rem;
                }
            }

            @media (max-width: 576px) {
                .share-btn {
                    min-width: 120px;
                    font-size: 0.8rem;
                }
            }
            </style>

            <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Copy with image functionality
                const copyWithImageBtn = document.querySelector('.copy-with-image-btn');
                if (copyWithImageBtn) {
                    copyWithImageBtn.addEventListener('click', function() {
                        const articleData = {
                            title: '{{ $article->title }}',
                            url: '{{ request()->fullUrl() }}',
                            image: '{{ $article->image_path }}',
                            description: '{{ Str::limit(strip_tags($article->content), 150) }}'
                        };
                        
                        const shareText = ` ${articleData.title}\n\n${articleData.description}\n\n🔗 ${articleData.url}`;
                        
                        // Method 1: Try to copy rich content (works in some apps)
                        if (navigator.clipboard && navigator.clipboard.write) {
                            const htmlContent = `
                                <div style="font-family: Arial, sans-serif; max-width: 500px;">
                                    ${articleData.image ? `<img src="${articleData.image}" alt="${articleData.title}" style="max-width: 100%; border-radius: 8px; margin-bottom: 10px;">` : ''}
                                    <h3 style="color: #1e3a8a; margin: 0 0 10px 0;">${articleData.title}</h3>
                                    <p style="color: #666; margin: 0 0 10px 0;">${articleData.description}</p>
                                    <a href="${articleData.url}" style="color: #3b82f6; text-decoration: none;">🔗 Voir la publication</a>
                                </div>
                            `;
                            
                            const blobHtml = new Blob([htmlContent], { type: 'text/html' });
                            const blobText = new Blob([shareText], { type: 'text/plain' });
                            
                            const data = new ClipboardItem({
                                'text/html': blobHtml,
                                'text/plain': blobText
                            });
                            
                            navigator.clipboard.write([data]).then(() => {
                                showCopySuccess(this);
                            }).catch(() => {
                                // Fallback to plain text
                                copyPlainText(shareText, this);
                            });
                        } else {
                            // Fallback to plain text
                            copyPlainText(shareText, this);
                        }
                    });
                }
                
                function copyPlainText(text, button) {
                    navigator.clipboard.writeText(text).then(() => {
                        showCopySuccess(button);
                    }).catch(() => {
                        // Ultimate fallback
                        const textArea = document.createElement('textarea');
                        textArea.value = text;
                        document.body.appendChild(textArea);
                        textArea.select();
                        document.execCommand('copy');
                        document.body.removeChild(textArea);
                        showCopySuccess(button);
                    });
                }
                
                function showCopySuccess(button) {
                    const originalHTML = button.innerHTML;
                    button.innerHTML = '<i class="fas fa-check me-2"></i>Copié !';
                    button.classList.add('copied');
                    
                    setTimeout(() => {
                        button.innerHTML = originalHTML;
                        button.classList.remove('copied');
                    }, 2000);
                }
                
                // Enhanced sharing with image preview
                const shareButtons = document.querySelectorAll('.share-btn[target="_blank"]');
                shareButtons.forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        // Add tracking or analytics here if needed
                        console.log('Sharing via:', this.querySelector('i').className);
                    });
                });
                
                // Social media specific enhancements
                function enhanceSocialShares() {
                    // Facebook Open Graph meta (should be in your layout head)
                    const metaTags = `
                        <meta property="og:title" content="{{ $article->title }}">
                        <meta property="og:description" content="{{ Str::limit(strip_tags($article->content), 150) }}">
                        <meta property="og:url" content="{{ request()->fullUrl() }}">
                        <meta property="og:type" content="article">
                        @if($article->image_path)
                        <meta property="og:image" content="{{ $article->image_path }}">
                        <meta property="og:image:width" content="1200">
                        <meta property="og:image:height" content="630">
                        @endif
                        <meta name="twitter:card" content="summary_large_image">
                        <meta name="twitter:title" content="{{ $article->title }}">
                        <meta name="twitter:description" content="{{ Str::limit(strip_tags($article->content), 150) }}">
                        @if($article->image_path)
                        <meta name="twitter:image" content="{{ $article->image_path }}">
                        @endif
                    `;
                    
                    // Ensure meta tags are in head (for social media crawlers)
                    if (!document.querySelector('meta[property="og:title"]')) {
                        document.head.insertAdjacentHTML('beforeend', metaTags);
                    }
                }
                
                // Initialize social media enhancements
                enhanceSocialShares();
            });
            </script>

            {{-- Rating Section --}}
            @auth
                <div class="rating-section mb-5 p-4 bg-light rounded-3 animate-fade-in">
                    @php $my = $article->ratings()->where('user_id',auth()->id())->first(); @endphp
                    <h3 class="h5 mb-3">Noter cette publication</h3>
                    <form action="{{ route('articles.rate',$article) }}" method="POST" class="d-inline-block">
                        @csrf
                        @if(!$my)
                            <div class="d-flex align-items-center flex-wrap gap-3">
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
                            <div class="d-flex align-items-center gap-3">
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
                        <div class="card-body p-4">
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
                            <div class="card-body p-4">
                                {{-- Comment Header --}}
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-3">
                                            <div class="avatar-placeholder bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex align-items-center justify-content-center fw-bold" 
                                                 style="width: 40px; height: 40px;">
                                                {{ Str::substr($c->user->name, 0, 1) }}
                                            </div>
                                        </div>
                                        <div>
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
                                    <p class="mb-0">{{ $c->body }}</p>
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
                                    <div class="replies-container mt-3 ps-4 border-start border-2">
                                        @foreach ($c->replies as $reply)
                                            <div class="reply-card card border-0 bg-light mb-2 animate-fade-in">
                                                <div class="card-body p-3">
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
                                                    <div class="small">{{ $reply->body }}</div>
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

<style>
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
    
    .media-item {
        transition: transform 0.3s ease;
    }
    
    .media-item:hover {
        transform: scale(1.05);
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
    
    .comment-card:hover {
        transform: translateX(5px);
    }
    
    .avatar-placeholder {
        transition: all 0.3s ease;
    }
    
    .avatar-placeholder:hover {
        transform: scale(1.1);
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .display-5 {
            font-size: 2rem;
        }
        
        .article-main-image {
            max-height: 300px;
        }
        
        .share-section .d-flex {
            justify-content: center;
        }
        
        .share-btn {
            flex: 1;
            min-width: 120px;
            margin-bottom: 0.5rem;
        }
        
        .replies-container {
            padding-left: 1rem !important;
        }
    }
    
    @media (max-width: 576px) {
        .container-fluid {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .action-buttons .d-flex {
            justify-content: center !important;
        }
        
        .comment-reactions .d-flex {
            justify-content: center;
        }
        
        .star-rating .btn-lg {
            padding: 0.25rem !important;
            font-size: 1.2rem;
        }
    }
</style>

<script>
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