@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Modifier mon commentaire</h4>

    {{-- Affichage des erreurs --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('comments.update', $comment) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="body" class="form-label">Votre commentaire</label>
            <textarea name="body" id="body" rows="4" class="form-control" required>{{ old('body', $comment->body) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Annuler</a>

        {{-- Nouveau bouton pour revenir à l'article --}}
        <a href="{{ route('articles.show', $comment->article) }}" class="btn btn-outline-info">
            Retour à la publication
        </a>
    </form>
</div>
@endsection
