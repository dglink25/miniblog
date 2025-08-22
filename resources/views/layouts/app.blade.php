<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'MiniBlog') }}</title>

    {{-- Bootstrap CSS via CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- ⚠️ Optionnel si tu utilises Vite --}}
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

    <style>
        .article-card img { object-fit: cover; height: 220px; }
        .pointer { cursor: pointer; }
        #toTop { position: fixed; bottom: 20px; right: 20px; display: none; }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

{{-- NAVBAR --}}
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="{{ route('articles.index') }}">
      {{ $settings->site_name ?? 'DGLink_Pub' }}
  </a>


    {{-- Bouton hamburger mobile --}}
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
      aria-controls="mainNavbar" aria-expanded="false" aria-label="Menu">
      <span class="navbar-toggler-icon"></span>
    </button>

    {{-- Liens --}}
    <div class="collapse navbar-collapse" id="mainNavbar">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="{{ route('articles.index') }}">Articles</a></li>
        @auth
          <li class="nav-item"><a class="nav-link" href="{{ route('articles.create') }}">Écrire</a></li>
          
          @if(Auth::user()->is_admin)
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Admin</a></li>
          @endif
        @endauth
      </ul>

      {{-- Droite --}}
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        @guest
          <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Connexion</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Inscription</a></li>
        @else
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
               data-bs-toggle="dropdown" aria-expanded="false">
              👤 {{ Auth::user()->name }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
              <li>
                <a class="dropdown-item" href="#"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                   Déconnexion
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
              </li>
            </ul>
          </li>
        @endguest
      </ul>
    </div>
  </div>
</nav>
{{-- FIN NAVBAR --}}

<main class="py-4 flex-grow-1">

<ul class="navbar-nav ms-auto">
  @auth
    <li class="nav-item me-3"><a class="nav-link" href="{{ route('articles.mine') }}">Mes publications</a></li>
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle position-relative" href="#" data-bs-toggle="dropdown">
        🔔 Notifications <span id="notif-badge" class="badge bg-danger">{{ auth()->user()->unreadNotifications()->count() }}</span>
      </a>
      <ul class="dropdown-menu dropdown-menu-end" style="max-height:300px;overflow:auto">
        @forelse(auth()->user()->unreadNotifications as $n)
          <li class="dropdown-item small">{{ $n->data['title'] ?? 'Notification' }}</li>
        @empty
          <li class="dropdown-item text-muted">Aucune notification</li>
        @endforelse
      </ul>
    </li>
  @endauth
</ul>


  <div class="container">
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
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

{{-- FOOTER --}}
<footer class="bg-dark text-white py-3 mt-auto">
  @php
    $avg = round(\App\Models\Rating::avg('stars') ?? 0, 2);
    $countRatings = \App\Models\Rating::count();
    $settings = \App\Models\SiteSetting::current();
  @endphp
  <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between text-center text-md-start">
    <small>© {{ date('Y') }} {{ $settings->company_name }} — {{ $settings->site_name }}</small>
    <div class="mt-2 mt-md-0">
      <span class="me-2">Note : <strong>{{ $avg }}/5</strong> ({{ $countRatings }})</span>
      @auth
        <button class="btn btn-sm btn-outline-light" data-bs-toggle="modal" data-bs-target="#rateModal">Noter</button>
      @else
        <a class="btn btn-sm btn-outline-light" href="{{ route('login') }}">Noter</a>
      @endauth
    </div>
  </div>
</footer>

{{-- JS Bootstrap --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

{{-- Scripts utilitaires --}}
<script>
const toTop = document.getElementById('toTop');
window.addEventListener('scroll', () => {
  toTop.style.display = window.scrollY > 300 ? 'block' : 'none';
});
toTop?.addEventListener('click', () => window.scrollTo({top:0, behavior:'smooth'}));
</script>

<button id="toTop" class="btn btn-primary rounded-circle shadow">↑</button>



<script src="https://js.pusher.com/8.2/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1/dist/echo.iife.js"></script>
<script>
  if ({{ auth()->check() ? 'true' : 'false' }}) {
    window.Pusher = Pusher;
    window.Echo = new Echo({
      broadcaster: 'pusher',
      key: '{{ config('broadcasting.connections.pusher.key') }}',
      cluster: '{{ config('broadcasting.connections.pusher.options.cluster') ?? 'mt1' }}',
      wsHost: '{{ request()->getHost() }}',
      wsPort: 6001,
      forceTLS: false,
      enabledTransports: ['ws','wss'],
      authEndpoint: '{{ url('/broadcasting/auth') }}',
      auth: { headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }
    });
    window.Echo.private('App.Models.User.{{ auth()->id() }}')
      .notification((notification) => {
        const el = document.getElementById('notif-badge');
        if (el) el.textContent = parseInt(el.textContent||'0') + 1;
      });
  }
</script>


</body>

<!-- Modal pour noter le site -->
<div class="modal fade" id="rateModal" tabindex="-1" aria-labelledby="rateModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="{{ route('ratings.store') }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="rateModalLabel">Noter le site</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body text-center">
          <p>Choisissez une note (1 à 5 étoiles) :</p>
          <div class="d-flex justify-content-center gap-2">
            @for($i=1; $i<=5; $i++)
              <input type="radio" name="stars" value="{{ $i }}" id="star{{ $i }}" class="d-none" required>
              <label for="star{{ $i }}" class="fs-2 text-warning pointer">★</label>
            @endfor
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-primary">Envoyer</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
// Permet de sélectionner les étoiles avec survol
const stars = document.querySelectorAll('label[for^="star"]');
stars.forEach((star, idx) => {
  star.addEventListener('mouseenter', () => {
    stars.forEach((s, i) => {
      s.textContent = i <= idx ? '★' : '☆';
    });
  });
});
document.querySelectorAll('input[name="stars"]').forEach(input => {
  input.addEventListener('change', () => {
    stars.forEach((s, i) => {
      s.textContent = i < input.value ? '★' : '☆';
    });
  });
});
</script>
</html>
