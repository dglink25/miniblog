
@extends('layouts.app')

@section('content')

@foreach ($article->media as $m)
  <div class="mb-2">
    @if ($m->isImage())
      <img src="{{ asset('storage/'.$m->file_path) }}" class="img-thumbnail" style="max-height:120px;">
    @else
      <video src="{{ asset('storage/'.$m->file_path) }}" controls style="max-height:160px; width:100%;"></video>
    @endif

    <form action="{{ route('media.destroy', $m) }}" method="POST" class="d-inline">
      @csrf
      @method('DELETE')
      <button class="btn btn-sm btn-danger">Supprimer</button>
    </form>
  </div>
@endforeach

<h1 class="h3 mb-3">Modifier : {{ $article->title }}</h1>

<form action="{{ route('articles.update', $article) }}" method="POST" enctype="multipart/form-data" class="row g-3">
  @csrf
  @method('PUT')

  <div class="col-12">
    <label class="form-label">Titre</label>
    <input type="text" name="title" value="{{ old('title', $article->title) }}" class="form-control" required minlength="5">
  </div>

  <div class="col-12">
    <label class="form-label">Contenu</label>
    <textarea name="content" rows="6" class="form-control" required>{{ old('content', $article->content) }}</textarea>
  </div>

  <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
  <script>
    tinymce.init({
      selector: 'textarea[name=content]',
      plugins: 'table image link lists code',
      toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist | table | link image | code',
      menubar: false,
      height: 400,
      automatic_uploads: true,
      images_upload_url: '{{ route('tinymce.upload') }}',
      file_picker_types: 'image',
      images_upload_credentials: true,
    });
  </script>

  <div class="col-12 col-md-6">
    <label class="form-label">Remplacer l'image (optionnel)</label>
    <input type="file" name="image" accept="image/*" class="form-control" onchange="previewImage(this)">
    <input type="file" name="media[]" multiple accept="image/,video/" class="form-control">
</div>
  <div class="col-12 col-md-6 d-flex align-items-end">
    <img id="preview" src="{{ asset('storage/'.$article->image_path) }}" class="img-fluid rounded border" alt="Aperçu">
  </div>

  <div class="col-12">
    <button class="btn btn-primary">Enregistrer</button>
    <a href="{{ route('articles.show', $article) }}" class="btn btn-outline-secondary">Annuler</a>
  </div>
</form>
@endsection
