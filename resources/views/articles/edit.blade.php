
@extends('layouts.app')

@section('content')

@auth
<form method="POST" action="{{ route('favorites.toggle', $article) }}" class="d-inline">
  @csrf
  <button class="btn btn-outline-danger btn-sm">
    {{ auth()->user()->favorites->contains($article->id) ? 'Retirer des favoris ❤️' : 'Ajouter aux favoris 🤍' }}
  </button>
</form>
@endauth

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
    <label class="form-label">Contenu de la publication</label>
    <textarea id="content-editor" name="content" rows="10" class="form-control" required>{{ old('content', $article->content) }}</textarea>
  </div>

<script src="https://cdn.tiny.cloud/1/bxfoodqbzho4gtk7u8sg750h7hmavqi784ztlsnkg2m52eex/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
  <script>
tinymce.init({
    selector: '#content-editor',
    height: 500,
    menubar: true,
    plugins: 'table link image media lists code autolink paste',
    toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright | bullist numlist | table | link image media | code',
    
    // FORCER les classes Bootstrap sur les tableaux automatiquement
    table_class_list: [
        {title: 'Tableau pro', value: 'table table-bordered table-striped text-center align-middle'}
    ],

    // Images / vidéos responsives
    image_class_list: [
        {title: 'Responsive', value: 'img-fluid rounded'}
    ],

    // Auto convertit les liens vers des liens cliquables ou embeds
    link_title: true,
    automatic_uploads: true,
    media_live_embeds: true,

    // Uploads (nécessite route Laravel pour gérer)
    images_upload_url: '{{ route('tinymce.upload') }}',
    images_upload_credentials: true,

    // Prévention des styles inline pour garder propre
    content_style: `
        body { font-family:Arial,sans-serif; font-size:14px; line-height:1.6; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 0.5rem; border: 1px solid #dee2e6; }
        img { max-width: 100%; height: auto; }
    `
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
