@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Nouvelle publication</h1>

<form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
  @csrf

  <div class="col-12">
    <label class="form-label">Titre</label>
    <input type="text" name="title" value="{{ old('title') }}" class="form-control" required minlength="5">
  </div>

  <div class="col-12">
  <label class="form-label">Contenu de la publication</label>
  {{-- Le textarea sera enrichi par TinyMCE --}}
  <textarea id="" name="content" rows="10" class="form-control" required>
    {{ old('content', $article->content ?? '') }}
  </textarea>
  @error('content') 
    <div class="text-danger small">{{ $message }}</div> 
  @enderror
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
    <label class="form-label">Image principale</label>
    <input type="file"
           name="image"
           accept="image/*"
           class="form-control"
           onchange="previewImage(this)"
           required>
    <div class="form-text">Formats supportés : JPG, PNG, WEBP…</div>

    <hr class="my-3">

    <label class="form-label">Médias supplémentaires (images/vidéos)</label>
    <input type="file"
           name="media[]"
           multiple
           accept="image/*,video/*"
           class="form-control"
           id="media-input">
    <div style="color:red" class="form-text">Vous pouvez sélectionner plusieurs fichiers. Attention, la taille maximale autorisée 100Mo !</div>
  </div>

  <div class="col-12 col-md-6 d-flex align-items-start">
    <div class="w-100">
      <div class="mb-3">
        <img id="preview" src="https://placehold.co/600x340?text=Apercu" class="img-fluid rounded border w-100" alt="Aperçu">
      </div>

      <div id="media-previews" class="row g-2"></div>
    </div>
  </div>

  <div class="col-12">
    <button class="btn btn-primary">Publier</button>
    <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary">Annuler</a>
  </div>
</form>
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
  <script>
    function previewImage(input) {
      const file = input.files && input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e){
      document.getElementById('preview').src = e.target.result;
    };
    reader.readAsDataURL(file);
    }

    tinymce.init({
      selector: '#content-editor',
      menubar: false,
      branding: false,
      height: 420,
      plugins: 'lists link image table code codesample media autoresize',
      toolbar: 'undo redo | blocks | bold italic underline forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table | removeformat | code',
      // Empêche TinyMCE d’injecter des tailles fixes : meilleur rendu responsive
      content_style: 'img,video{max-width:100%;height:auto} table{width:100%;border-collapse:collapse} td,th{border:1px solid #ddd;padding:6px;}',
      // Insertion d’images/vidéos par URL (upload serveur possible, mais à configurer côté backend)
      image_title: true,
      automatic_uploads: false
    });

    // Aperçu image principale
    
    // Aperçu médias multiples (images + vidéos)
    const mediaInput = document.getElementById('media-input');
    const mediaPreviews = document.getElementById('media-previews');

    mediaInput?.addEventListener('change', function () {
      mediaPreviews.innerHTML = '';
      const files = Array.from(this.files || []);
      files.forEach(file => {
        const col = document.createElement('div');
        col.className = 'col-6';

        const wrapper = document.createElement('div');
        wrapper.className = 'ratio ratio-16x9 border rounded overflow-hidden';

        const url = URL.createObjectURL(file);

        if (file.type.startsWith('image/')) {
          const img = document.createElement('img');
          img.src = url;
          img.className = 'w-100 h-100 object-fit-cover';
          img.onload = () => URL.revokeObjectURL(url);
          wrapper.appendChild(img);
        } else if (file.type.startsWith('video/')) {
          const video = document.createElement('video');
          video.src = url;
          video.controls = true;
          video.className = 'w-100 h-100';
          video.onloadeddata = () => URL.revokeObjectURL(url);
          wrapper.appendChild(video);
        } else {
          const p = document.createElement('div');
          p.className = 'p-2 small';
          p.textContent = `Type non prévisualisable : ${file.name}`;
          wrapper.appendChild(p);
        }

        col.appendChild(wrapper);
        mediaPreviews.appendChild(col);
      });
    });
  </script>
 
@endsection

