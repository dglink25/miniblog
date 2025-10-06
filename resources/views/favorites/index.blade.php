{{-- resources/views/favorites/index.blade.php --}}
@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Mes favoris</h1>

@if($articles->isEmpty())
  <div class="alert alert-info">Aucun favori pour l’instant.</div>
@endif

<div class="row g-3">
  @foreach($articles as $article)
    <div class="col-12 col-md-6 col-lg-4">
      <div class="card h-100 shadow-sm">
        @php $img = $article->media->firstWhere('type','image'); @endphp
        @if($img)
          <img class="card-img-top" src="{{ asset('storage/'.$img->file_path) }}" alt="">
        @elseif($article->image_path)
          <img class="card-img-top" src="{{ asset('storage/'.$article->image_path) }}" alt="">
        @endif
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">{{ $article->title }}</h5>
          <p class="card-text flex-grow-1">{{ \Illuminate\Support\Str::limit(strip_tags($article->content), 160) }}</p>
          <a href="{{ route('articles.show', $article) }}" class="btn btn-primary mt-auto">Lire</a>
        </div>
      </div>
    </div>
  @endforeach
</div>

<div class="mt-3">
  {{ $articles->links() }}
</div>
@endsection