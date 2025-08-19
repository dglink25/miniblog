
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'MiniBlog') }}</title>

    {{-- Bootstrap CSS via CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Vite (si laravel/ui a généré des assets) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .article-card img { object-fit: cover; height: 220px; }
        .pointer { cursor:pointer; }
        #toTop { position: fixed; bottom: 20px; right: 20px; display:none; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="{{ route('articles.index') }}">MiniBlog</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbars" aria-controls="navbars" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbars">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="{{ route('articles.index') }}">Articles</a></li>
        @auth
          <li class="nav-item"><a class="nav-link" href="{{ route('articles.create') }}">Écrire</a></li>
        @endauth
      </ul>
      <ul class="navbar-nav ms-auto">
        @guest
          <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Connexion</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Inscription</a></li>
        @else
          <li class="nav-item"><span class="navbar-text me-2">Bonjour, {{ Auth::user()->name }}</span></li>
          <li class="nav-item">
            <a class="nav-link" href="#"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Déconnexion</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
          </li>
        @endguest
      </ul>
    </div>
  </div>
</nav>

<main class="py-4">
  <div class="container">
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif
    @if($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    @yield('content')
  </div>
</main>

<button id="toTop" class="btn btn-primary rounded-circle">↑</button>

{{-- Bootstrap JS via CDN --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Bouton retour en haut
const toTop = document.getElementById('toTop');
window.addEventListener('scroll', () => {
  toTop.style.display = window.scrollY > 300 ? 'block' : 'none';
});
toTop.addEventListener('click', () => window.scrollTo({top:0, behavior:'smooth'}));

// Aperçu d'image pour les formulaires
function previewImage(input, targetId='preview') {
  const file = input.files?.[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = e => {
    const img = document.getElementById(targetId);
    if (img) img.src = e.target.result;
  };
  reader.readAsDataURL(file);
}

// Filtre rapide côté client (index)
function filterCards(q) {
  const cards = document.querySelectorAll('.article-card');
  const term = q.toLowerCase();
  cards.forEach(c => {
    const text = c.innerText.toLowerCase();
    c.style.display = text.includes(term) ? '' : 'none';
  });
}

// Confirmation suppression
function confirmDelete(formId) {
  if (confirm('Confirmer la suppression de cet article ?')) {
    document.getElementById(formId).submit();
  }
}
</script>
</body>

{{-- Footer --}}
<footer class="bg-dark text-white text-center py-3 mt-auto">
  <small>© {{ date('Y') }} MiniBlog — Tous droits réservés.</small>
</footer>

</html>
