@extends('layouts.app')

@section('content')

@auth
<form method="POST" action="{{ route('favorites.toggle', $article) }}" class="d-inline">
  @csrf
  <button class="btn btn-outline-danger btn-sm">
    {{ auth()->user()->favorites->contains($article->id) ? 'Retirer des favoris' : 'Ajouter aux favoris' }}
  </button>
</form>
@endauth

<h1 class="h3 mb-3">Modifier : {{ $article->title }}</h1>

{{-- === Liste des médias EXISTANTS (hors du formulaire principal) === --}}
<div class="mb-4">
  <h5>Médias actuels</h5>
  <div class="row g-2">
    @foreach ($article->media as $m)
      <div class="col-6 col-md-3">
        <div class="card">
          <div class="card-body p-2 text-center">
            @if ($m->isImage())
              <img src="{{ asset('storage/'.$m->file_path) }}" class="img-fluid mb-2" style="max-height:120px;">
            @else
              <video src="{{ asset('storage/'.$m->file_path) }}" controls style="max-height:120px; width:100%;"></video>
            @endif

            {{-- formulaire de suppression indépendant (pas imbriqué) --}}
            <form action="{{ route('media.destroy', $m) }}" method="POST" onsubmit="return confirm('Supprimer ce média ?');">
              @csrf
              @method('DELETE')
              <button class="btn btn-sm btn-danger w-100">Supprimer</button>
            </form>
          </div>
        </div>
      </div>
    @endforeach
    @if($article->media->isEmpty())
      <div class="col-12 text-muted">Aucun média</div>
    @endif
  </div>
</div>

{{-- === FORMULAIRE D'EDIT PRINCIPAL === --}}
<form id="article-edit-form" action="{{ route('articles.update', $article) }}" method="POST" enctype="multipart/form-data" class="row g-3">
  @csrf
  @method('PUT')
  
  <div class="col-12">
    <label class="form-label">Titre</label>
    <input type="text" name="title" value="{{ old('title', $article->title) }}" class="form-control" required minlength="5">
  </div>

  <div class="col-12">
    <label class="form-label">Contenu de la publication</label>
    {{-- IMPORTANT : id utilisé par TinyMCE --}}
    <textarea id="" name="content" rows="10" class="form-control" required>{{ old('content', $article->content) }}</textarea>
  </div>

  <div class="col-12 col-md-6">
    <label class="form-label">Remplacer l'image de couverture (optionnel)</label>
    <input type="file" name="image" accept="image/*" class="form-control" onchange="previewImage(this)">
  </div>

  <div class="col-12 col-md-6 d-flex align-items-end">
    <img id="preview" src="{{ $article->image_path ? asset('storage/'.$article->image_path) : 'https://placehold.co/600x340?text=Apercu' }}" class="img-fluid rounded border w-100" alt="Aperçu">
  </div>

  <div class="col-12">
    <label class="form-label">Médias supplémentaires (images/vidéos)</label>
    <input type="file"
           id="media-input"
           name="media[]"
           multiple
           accept="image/*,video/*"
           class="form-control">
    <div class="form-text">Vous pouvez sélectionner plusieurs fichiers (max 100MB chacun selon configuration serveur).</div>
  </div>

  <div class="col-12">
    <div id="media-previews" class="row g-2 mt-2"></div>
  </div>

  <div class="col-12">
    <button type="submit" id="saveBtn" class="btn btn-primary">Enregistrer</button>
    <a href="{{ route('articles.show', $article) }}" class="btn btn-outline-secondary">Annuler</a>
  </div>
</form>

{{-- TinyMCE (assure-toi que la route tinymce.upload existe si tu utilises upload) --}}
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
  tinymce.init({
    selector: '#content-editor',
    height: 500,
    menubar: true,
    plugins: 'table link image media lists code autolink paste',
    toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright | bullist numlist | table | link image media | code',
    table_class_list: [
      {title: 'Tableau pro', value: 'table table-bordered table-striped text-center align-middle'}
    ],
    image_class_list: [
      {title: 'Responsive', value: 'img-fluid rounded'}
    ],
    link_title: true,
    automatic_uploads: true,
    media_live_embeds: true,
    images_upload_url: '{{ route('tinymce.upload') }}',
    images_upload_credentials: true,
    content_style: `
      body { font-family:Arial,sans-serif; font-size:14px; line-height:1.6; }
      table { width: 100%; border-collapse: collapse; }
      th, td { padding: 0.5rem; border: 1px solid #dee2e6; }
      img { max-width: 100%; height: auto; }
    `
  });

  // Avant l'envoi du formulaire : forcer TinyMCE à enregistrer son contenu dans le textarea
  document.getElementById('article-edit-form').addEventListener('submit', function(e){
    if (typeof tinymce !== 'undefined') tinymce.triggerSave(); // met à jour le textarea
    // désactiver bouton pour éviter double click
    document.getElementById('saveBtn').disabled = true;
  });

  // Preview image de couverture
  window.previewImage = function(input){
    const file = input.files && input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e){
      document.getElementById('preview').src = e.target.result;
    };
    reader.readAsDataURL(file);
  };

  // Preview multiple médias sélectionnés
  const mediaInput = document.getElementById('media-input');
  const mediaPreviews = document.getElementById('media-previews');

  mediaInput.addEventListener('change', function(){
    mediaPreviews.innerHTML = '';
    Array.from(this.files).forEach(file => {
      const col = document.createElement('div');
      col.className = 'col-6 col-md-3';
      const card = document.createElement('div');
      card.className = 'card p-2 text-center';
      if (file.type.startsWith('image/')) {
        const img = document.createElement('img');
        img.style.maxHeight = '120px';
        img.className = 'img-fluid';
        const reader = new FileReader();
        reader.onload = e => img.src = e.target.result;
        reader.readAsDataURL(file);
        card.appendChild(img);
      } else if (file.type.startsWith('video/')) {
        const video = document.createElement('video');
        video.controls = true;
        video.style.maxHeight = '120px';
        video.style.width = '100%';
        const reader = new FileReader();
        reader.onload = e => { video.src = e.target.result; };
        reader.readAsDataURL(file);
        card.appendChild(video);
      } else {
        const span = document.createElement('div');
        span.textContent = file.name;
        card.appendChild(span);
      }
      col.appendChild(card);
      mediaPreviews.appendChild(col);
    });
  });

});
</script>

@endsection