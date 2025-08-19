@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Nouvel article</h1>

<form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
  @csrf
  <div class="col-12">
    <label class="form-label">Titre</label>
    <input type="text" name="title" value="{{ old('title') }}" class="form-control" required minlength="5">
  </div>

  <div class="col-12">
    <label class="form-label">Contenu</label>
    <textarea name="content" rows="6" class="form-control" required>{{ old('content') }}</textarea>
  </div>

  <div class="col-12 col-md-6">
    <label class="form-label">Image</label>
    <input type="file" name="image" accept="image/*" class="form-control" onchange="previewImage(this)" required>
    <input type="file" name="media[]" multiple accept="image/,video/" class="form-control">
  </div>
  <div class="col-12 col-md-6 d-flex align-items-end">
    <img id="preview" src="https://placehold.co/400x220?text=Apercu" class="img-fluid rounded border" alt="Aperçu">
  </div>

  <div class="col-12">
    <button class="btn btn-primary">Publier</button>
    <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary">Annuler</a>
  </div>
</form>
@endsection
