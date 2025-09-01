@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Publications de {{ $user->name }}</h1>

@forelse($articles as $article)
  <div class="card mb-3">
    <div class="card-body">
      <h5 class="card-title">{{ $article->title }}</h5>
      <p class="card-text">{{ Str::limit($article->content, 150) }}</p>
      <a href="{{ route('articles.show', $article) }}" class="btn btn-primary btn-sm">Voir plus</a>
    </div>
  </div>
@empty
  <p class="text-muted">Cet utilisateur n’a encore publié aucun article.</p>
@endforelse

{{ $articles->links() }}
@endsection
