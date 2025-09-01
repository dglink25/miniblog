
@extends('layouts.app')

@section('content')

@auth
    @include('articles.partials.favorite_button', ['article' => $article])
    @if(auth()->id() !== $article->user->id)
        @if(auth()->user()->following?->contains($article->user->id))
            <form method="POST" action="{{ route('users.unfollow', $article->user) }}">
                @csrf
                @method('DELETE')
                <button class="btn btn-warning">Se désabonner</button>
            </form>
        @else
            <form method="POST" action="{{ route('users.follow', $article->user) }}">
                @csrf
                <button class="btn btn-primary">S’abonner</button>
            </form>
        @endif
    @endif
@endauth


<article class="mb-4">
  <h1 class="mb-2">{{ $article->title }}</h1>
  {{-- resources/views/articles/show.blade.php --}}

  @auth
      @if(auth()->user()->isAdmin())
          <form method="POST" action="{{ route('admin.articles.destroy',$article) }}" onsubmit="return confirm('Supprimer ?');">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-outline-danger">Supprimer en tant que administrateur</button>
          </form>
      @endif
  @endauth

  <div class="text-muted mb-3">
    Publiée par <strong><a href="{{ route('user.articles', $article->user->id) }}">{{ $article->user->name }}</a></strong>
     {{ $article->created_at->diffForHumans() }}
    @if($article->updated_at->gt($article->created_at))
       modifié {{ $article->updated_at->diffForHumans() }}
    @endif
  </div>

  <img src="{{ asset('storage/'.$article->image_path) }}" class="img-fluid rounded mb-3" alt="Image article {{ $article->title }}">

  <div class="fs-5 mb-3">{!! (($article->content)) !!}</div>

    @foreach ($article->media as $m)
    @if ($m->isImage())
        <img src="{{ asset('storage/'.$m->file_path) }}"
             class="img-fluid mb-3 rounded border"
             style="max-height: 400px; object-fit: cover; width:100%;"
             alt="Média de l'article">
    @elseif ($m->isVideo())
        <video controls class="img-fluid mb-3 rounded border" style="max-height: 400px; width:100%;">
            <source src="{{ asset('storage/'.$m->file_path) }}" type="{{ $m->mime_type }}">
            Votre navigateur ne supporte pas la lecture de vidéos.
        </video>
    @elseif ($m->isAudio())
        <audio controls class="w-100 mb-3">
            <source src="{{ asset('storage/'.$m->file_path) }}" type="{{ $m->mime_type }}">
            Votre navigateur ne supporte pas la lecture audio.
        </audio>
    @else
        <a href="{{ asset('storage/'.$m->file_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary mb-2">
            Télécharger le fichier
        </a>
    @endif
@endforeach


  @can('update', $article)
    <a href="{{ route('articles.edit', $article) }}" class="btn btn-outline-primary me-2">Modifier</a>
  @endcan
  @can('delete', $article)
  <form id="del-{{ $article->id }}" action="{{ route('articles.destroy', $article) }}" method="POST" class="d-inline">
      @csrf
      @method('DELETE')
  </form>
  @endcan

  @php
  $url = urlencode(request()->fullUrl());
  $title = urlencode($article->title);
@endphp

<div class="d-flex align-items-center gap-2 flex-wrap my-3">
  <a class="btn btn-sm text-white" style="background:#1877F2" target="_blank"
   href="https://www.facebook.com/sharer/sharer.php?u={{ $url }}">
   <i class="fab fa-facebook-f"></i> Facebook
</a>

<a class="btn btn-sm text-white" style="background:#000000" target="_blank"
   href="https://twitter.com/intent/tweet?url={{ $url }}&text={{ $title }}">
   <i class="fab fa-x-twitter"></i> X
</a>

<a class="btn btn-sm text-white" style="background:#25D366" target="_blank"
   href="https://api.whatsapp.com/send?text={{ $title }}%20{{ $url }}">
   <i class="fab fa-whatsapp"></i> WhatsApp
</a>

<a class="btn btn-sm text-white" style="background:#0088cc" target="_blank"
   href="https://t.me/share/url?url={{ $url }}&text={{ $title }}">
   <i class="fab fa-telegram"></i> Telegram
</a>

<a class="btn btn-sm text-white" style="background:#0A66C2" target="_blank"
   href="https://www.linkedin.com/sharing/share-offsite/?url={{ $url }}">
   <i class="fab fa-linkedin-in"></i> LinkedIn
</a>

<button class="btn btn-sm text-white" style="background:#6c757d"
        onclick="navigator.clipboard.writeText('{{ request()->fullUrl() }}'); this.innerHTML='<i class=\'fas fa-check\'></i> Lien copié';">
    <i class="fas fa-copy"></i> Copier le lien
</button>


  @auth

        @if(auth()->check())
        @if(auth()->user()->has_subscription)
            <form action="{{ route('articles.boost', $article) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success">Booster ma publication</button>
            </form>
        @else
            <a href="{{ route('subscriptions.plans') }}" class="btn btn-primary">
              Acheter un boost
            </a>
        @endif
    @endif
  @endauth
</div>

</article>

<hr>

<section class="mt-4">
  <h2 class="h4">Commentaires ({{ $article->comments->count() }})</h2>

  @auth
    <form action="{{ route('articles.comments.store', $article) }}" method="POST" class="mb-4">
      @csrf
      <div class="mb-3">
        <label class="form-label">Votre commentaire</label>
        <textarea name="body" rows="4" class="form-control" minlength="10" required>{{ old('body') }}</textarea>
      </div>
      <button class="btn btn-primary">commenter</button>
    </form>
  @else
    <div class="alert alert-warning">Vous devez être connecté pour commenter. <a href="{{ route('login') }}">Se connecter</a></div>
  @endauth

  @forelse($article->comments->whereNull('parent_id') as $c)
  <div class="border rounded p-3 mb-3 bg-light">
    <div class="small text-muted mb-1">
      <strong><a href="{{ route('user.article', $article->user->id) }}">{{ $c->user->name }}</a></strong>
      {{ $c->created_at->diffForHumans() }}
    </div>
    <div>{{ $c->body }}</div>
    {{-- Boutons d’action --}}
    @if($c->canEditOrDelete())
      <div class="mt-2">
        <a href="{{ route('comments.edit', $c) }}" class="btn btn-sm btn-outline-primary">Modifier</a>
        
        <form action="{{ route('comments.destroy', $c) }}" method="POST" class="d-inline">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-sm btn-outline-danger"
                  onclick="return confirm('Voulez-vous vraiment supprimer ce commentaire ?')">
            Supprimer
          </button>
        </form>
      </div>
    @endif
    <div class="mt-2 d-flex flex-wrap gap-2">
  @php
    $reactions = [
      'like' => '👍',
      'dislike' => '👎',
      'love' => '❤️',
      'laugh' => '😂',
      'angry' => '😡',
      'sad' => '😢',
    ];
  @endphp

  @foreach ($reactions as $type => $emoji)
    <form action="{{ route('comments.react', $c) }}" method="POST" class="d-inline">
      @csrf
      <input type="hidden" name="type" value="{{ $type }}">
      <button class="btn btn-sm btn-outline-secondary">
        {{ $emoji }} {{ $c->reactions->where('type', $type)->count() }}
      </button>
    </form>
  @endforeach
</div>

    <!-- Bouton Répondre -->
    @auth
      <form action="{{ route('comments.reply', $c) }}" method="POST" class="mt-2">
        @csrf
        <textarea name="body" class="form-control form-control-sm" rows="2" placeholder="Répondre..."></textarea>
        <button class="btn btn-sm btn-primary mt-1">Répondre</button>
      </form>
    @endauth

    <!-- Réponses -->
    @foreach ($c->replies as $reply)
      <div class="border rounded p-2 ms-4 mt-2 bg-white">
        <div class="small text-muted mb-1">
          <strong><a href="{{ route('user.article', $article->user->id) }}">{{ $reply->user->name }}</a></strong>
          {{ $reply->created_at->diffForHumans() }}
        </div>
        <div>{{ $reply->body }}</div>
      </div>
    @endforeach
    
  </div>
@empty
  <p class="text-muted">Pas encore de commentaires.</p>
@endforelse



  @auth
    @php $my = $article->ratings()->where('user_id',auth()->id())->first(); @endphp
    <div class="mt-3">
      <form action="{{ route('articles.rate',$article) }}" method="POST" class="d-inline"> @csrf
        @if(!$my)
          <span class="me-2">Noter :</span>
          @for($i=1;$i<=5;$i++)
            <button name="stars" value="{{ $i }}" class="btn btn-sm btn-outline-warning">★</button>
          @endfor
        @else
          <span>Votre note : {{ $my->stars }}★</span>
        @endif
      </form>
      <div class="small text-muted">Moyenne : {{ number_format($article->ratings()->avg('stars'),2) }} / 5</div>
    </div>
  @endauth
</section>
@endsection
