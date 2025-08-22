@extends('layouts.app')
@section('content')
<h1 class="h4 mb-3">Articles de {{ $user->name }}</h1>
<div class="row g-3">
@forelse($articles as $article)
  <div class="col-md-6 col-lg-4">
    <div class="card h-100">
      @php $thumb=$article->media->firstWhere('type','image'); @endphp
      @if($thumb)
        <img class="card-img-top" src="{{ asset('storage/'.$thumb->file_path) }}" alt="">
      @endif
      <div class="card-body">
        <h5>{{ $article->title }}</h5>
        <a href="{{ route('articles.show',$article) }}" class="btn btn-outline-primary btn-sm">Lire</a>
      </div>
    </div>
  </div>
@empty
  <p class="text-muted">Aucun article publié.</p>
@endforelse
</div>
@endsection