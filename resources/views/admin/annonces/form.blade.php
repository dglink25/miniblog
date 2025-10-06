{{-- resources/views/admin/annonces/form.blade.php --}}
@extends('layouts.app')
@section('content')
<h1 class="h4 mb-3">{{ $annonce->exists ? 'Modifier' : 'Créer' }} une annonce</h1>
<form method="POST" action="{{ $annonce->exists ? route('admin.annonces.update', $annonce) : route('admin.annonces.store') }}">
  @csrf @if($annonce->exists) @method('PUT') @endif
  <div class="mb-3">
    <label class="form-label">Titre</label>
    <input class="form-control" name="title" value="{{ old('title',$annonce->title) }}" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Contenu (HTML)</label>
    <textarea class="form-control" name="content_html" rows="6">{{ old('content_html',$annonce->content_html) }}</textarea>
    <small class="text-muted">Utilise le même éditeur riche que pour les articles si tu veux (voir §6).</small>
  </div>
  <div class="row">
    <div class="col-md-3 mb-3">
      <label class="form-label">Type média</label>
      <select class="form-select" name="media_type">
        @foreach(['none'=>'Aucun','image'=>'Image URL','video'=>'Vidéo URL'] as $k=>$v)
          <option value="{{ $k }}" @selected(old('media_type',$annonce->media_type)===$k)>{{ $v }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-9 mb-3">
      <label class="form-label">URL média (facultatif)</label>
      <input class="form-control" name="media_url" value="{{ old('media_url',$annonce->media_url) }}">
    </div>
  </div>
  <div class="form-check form-switch mb-2">
    <input class="form-check-input" type="checkbox" name="is_published" value="1" @checked(old('is_published',$annonce->is_published))>
    <label class="form-check-label">Publié</label>
  </div>
  <div class="form-check form-switch mb-3">
    <input class="form-check-input" type="checkbox" name="is_pinned" value="1" @checked(old('is_pinned',$annonce->is_pinned))>
    <label class="form-check-label">Épinglé (toujours en haut)</label>
  </div>
  <button class="btn btn-primary">Enregistrer</button>
</form>
@endsection