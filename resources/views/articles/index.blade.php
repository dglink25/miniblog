@extends('layouts.app')

@php
  use Illuminate\Support\Str;
  use Illuminate\Support\Arr;
@endphp

@section('content')

{{-- Annonces épinglées --}}
@foreach(($annonces ?? []) as $a)
  <div class="card border-warning mb-3">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <div class="small text-muted mb-1">
            Annonce — Administrateur DGLINK Pub • {{ $a->published_at?->diffForHumans() }}
          </div>
          <h5 class="card-title">
            {{ $a->title }} 
            @if($a->is_pinned) 
              <span class="badge bg-warning text-dark">📌 Épinglée</span>
            @endif
          </h5>
        </div>
        @auth
        <form method="POST" action="{{ route('annonces.dismiss',$a) }}">
          @csrf
          <button class="btn btn-sm btn-outline-secondary">Ne plus afficher</button>
        </form>
        @endauth
      </div>

      {{-- Gestion des médias (image / YouTube / TikTok / autres vidéos) --}}
      @if($a->media_url)

        {{-- Cas image --}}
        @if(Str::endsWith($a->media_url, ['.jpg','.jpeg','.png','.gif']))
          <img src="{{ $a->media_url }}" class="img-fluid rounded my-2" alt="Image annonce">

        {{-- Cas YouTube --}}
        @elseif(Str::contains($a->media_url, 'youtube.com/watch') || Str::contains($a->media_url, 'youtu.be'))
          @php
            $videoId = null;
            if(Str::contains($a->media_url, 'youtu.be')) {
              // Exemple : https://youtu.be/xxxx
              $videoId = basename(parse_url($a->media_url, PHP_URL_PATH));
            } else {
              // Exemple : https://www.youtube.com/watch?v=xxxx
              parse_str(parse_url($a->media_url, PHP_URL_QUERY), $ytParams);
              $videoId = $ytParams['v'] ?? null;
            }
          @endphp
          @if($videoId)
            <div class="ratio ratio-16x9 my-2">
              <iframe src="https://www.youtube.com/embed/{{ $videoId }}" allowfullscreen></iframe>
            </div>
          @endif

        {{-- Cas TikTok --}}
        @elseif(Str::contains($a->media_url, 'tiktok.com'))
          @php
            $parts = explode('/', parse_url($a->media_url, PHP_URL_PATH));
            $tiktokId = end($parts);
          @endphp
          <blockquote class="tiktok-embed my-2" cite="{{ $a->media_url }}" 
              data-video-id="{{ $tiktokId }}" 
              style="max-width: 605px;min-width: 325px;">
            <section></section>
          </blockquote>
          <script async src="https://www.tiktok.com/embed.js"></script>

        {{-- Cas autre vidéo (ex: MP4 direct) --}}
        @elseif(Str::endsWith($a->media_url, ['.mp4','.webm']))
          <video class="w-100 rounded my-2" controls>
            <source src="{{ $a->media_url }}" type="video/mp4">
            Votre navigateur ne supporte pas la lecture vidéo.
          </video>
        @endif

      @endif


      @if($a->content_html)
        <div class="mt-2">{!! $a->content_html !!}</div>
      @endif
    </div>
  </div>
@endforeach

<div class="d-flex align-items-center justify-content-between mb-3">
  <h1 class="h3 mb-0">Toutes les publications</h1>
  @auth
    <a href="{{ route('articles.create') }}" class="btn btn-primary">Nouvelle publication</a>
  @endauth
</div>

{{-- Formulaire de recherche --}}
<form class="row gy-2 gx-2 mb-3" method="GET" action="{{ route('articles.index') }}">
  <div class="col-12 col-md-6">
    <input type="text" name="q" value="{{ $q ?? '' }}" class="form-control" placeholder="Rechercher un titre ou un contenu..." oninput="filterCards(this.value)">
  </div>
  <div class="col-12 col-md-auto">
    <button class="btn btn-outline-secondary w-100" type="submit">Rechercher (serveur)</button>
  </div>
</form>

@if($articles->count() === 0)
  <div class="alert alert-info">Aucune publication de ce nom pour le moment!</div>
@endif

<div class="row g-3">
  @foreach($articles as $article)
    @if($article->status === 'validated') {{-- Affiche uniquement si publié --}}
    <div class="col-12 col-md-6 col-lg-4">
      
      <div class="card article-card h-100 shadow-sm">
        @php
          $thumb = $article->media->firstWhere('type','image') ?? null;
        @endphp
        @if ($thumb)
          <img src="{{ asset('storage/'.$thumb->file_path) }}" class="card-img-top" alt="Image article {{ $article->title }}">
        @elseif ($article->image_path)
          <img src="{{ asset('storage/'.$article->image_path) }}" class="card-img-top" alt="Image article {{ $article->title }}">
        @else
          <img src="{{ asset('images/default-article.png') }}" class="card-img-top" alt="Image par défaut">
        @endif

        <div class="card-body d-flex flex-column">
          <h5 class="card-title">{{ $article->title }}</h5>
          <p class="card-text flex-grow-1">{{ \Illuminate\Support\Str::limit(strip_tags($article->content), 180) }}</p>
          <div class="small text-muted mb-2">
            Publié par <strong><a href="{{ route('user.articles', $article->user->id) }}">{{ $article->user->name }}</a></strong>
            
            • {{ optional($article->published_at ?? $article->created_at)->diffForHumans() }}
            @if($article->updated_at->gt($article->created_at))
              • maj {{ $article->updated_at->diffForHumans() }}
            @endif
          </div>
          <a href="{{ route('articles.show', $article) }}" class="btn btn-outline-primary mt-auto">Détails</a>
        </div>
      </div>
    </div>
    @endif
  @endforeach
</div>

{{-- Pagination --}}
<div class="mt-3">
  {{ $articles->links() }}
</div>

{{-- Filtre côté client --}}
<script>
  function filterCards(q) {
    const cards = document.querySelectorAll('.article-card');
    const term = q.toLowerCase();
    cards.forEach(c => {
      const text = c.innerText.toLowerCase();
      c.style.display = text.includes(term) ? '' : 'none';
    });
  }
</script>
@endsection
