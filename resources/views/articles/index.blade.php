@extends('layouts.app')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
  <h1 class="h3 mb-0">Tous les articles</h1>
  @auth
    <a href="{{ route('articles.create') }}" class="btn btn-primary">Nouvel article</a>
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
  <div class="alert alert-info">Aucun article pour le moment.</div>
@endif

<div class="row g-3">
  @foreach($articles as $article)
    @if($article->is_published) {{-- Affiche uniquement si publié --}}
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
