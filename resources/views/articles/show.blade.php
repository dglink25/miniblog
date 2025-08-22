
@extends('layouts.app')

@section('content')

@auth
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
  <div class="text-muted mb-3">
    Par<strong><a href="{{ route('user.articles', $article->user->id) }}">{{ $article->user->name }}</a></strong>
    • publié {{ $article->created_at->diffForHumans() }}
    @if($article->updated_at->gt($article->created_at))
      • modifié {{ $article->updated_at->diffForHumans() }}
    @endif
  </div>

  <img src="{{ asset('storage/'.$article->image_path) }}" class="img-fluid rounded mb-3" alt="Image article {{ $article->title }}">

  <div class="fs-5 mb-3">{!! nl2br(e($article->content)) !!}</div>

    @foreach ($article->media as $m)
    @if ($m->isImage())
        <img src="{{ asset('storage/'.$m->file_path) }}" class="img-fluid mb-3">
    @elseif ($m->isVideo())
        <video controls class="img-fluid mb-3" src="{{ asset('storage/'.$m->file_path) }}"></video>
    @endif
    @endforeach

  @can('update', $article)
    <a href="{{ route('articles.edit', $article) }}" class="btn btn-outline-primary me-2">Modifier</a>
  @endcan
  @can('delete', $article)
    <form id="del-{{ $article->id }}" action="{{ route('articles.destroy', $article) }}" method="POST" class="d-inline">
      @csrf @method('DELETE')
      <button type="button" onclick="confirmDelete('del-{{ $article->id }}')" class="btn btn-outline-danger">Supprimer</button>
    </form>
  @endcan
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
      <button class="btn btn-primary">Publier le commentaire</button>
    </form>
  @else
    <div class="alert alert-warning">Vous devez être connecté pour commenter. <a href="{{ route('login') }}">Se connecter</a></div>
  @endauth

  @forelse($article->comments as $c)
    <div class="border rounded p-3 mb-3 bg-light">
      <div class="small text-muted mb-1">Par <strong>{{ $c->user->name }}</strong> • {{ $c->created_at->diffForHumans() }}</div>
      <div>{{ $c->body }}</div>
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
