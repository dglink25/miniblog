@php
  use Illuminate\Support\Facades\Auth;
  $user = Auth::user();
  $settings = \App\Models\SiteSetting::current();
  $unreadCount = $user ? $user->unreadNotifications()->count() : 0;
@endphp

<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name', $settings->site_name ?? 'DGLink_Pub') }}</title>

  {{-- Bootstrap CSS --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  {{-- (Optionnel) Icônes Bootstrap --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    .pointer { cursor:pointer; }
    #toTop { position: fixed; bottom: 20px; right: 20px; display: none; z-index: 1031; }
    .nav-link .badge-notif {
      position:absolute; top:0; right:-.35rem; transform: translate(50%, -30%);
    }
    .dropdown-menu-notifs { max-height: 320px; overflow:auto; width: 320px; }
  </style>
</head>
<body class="d-flex flex-column min-vh-100 bg-light">

{{-- NAVBAR --}}
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
      {{ $settings->site_name ?? 'DGLink_Pub' }}
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
      aria-controls="mainNavbar" aria-expanded="false" aria-label="Menu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNavbar">
      {{-- Liens principaux gauche --}}
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="{{ route('articles.index') }}">Publications</a></li>

        @auth
          {{-- Nouvelle publication (protégée par le middleware publish.access côté routes) --}}
          <li class="nav-item"><a class="nav-link" href="{{ route('articles.create') }}">Nouvelle publication</a></li>

          {{-- Mes publications --}}
          <li class="nav-item"><a class="nav-link" href="{{ route('articles.mine') }}">Mes publications</a></li>

          {{-- Favoris --}}
          <li class="nav-item"><a class="nav-link" href="{{ route('favorites.index') }}">Mes favoris</a></li>

          {{-- Suggestions (utilisateur → admin) --}}
          <li class="nav-item"><a class="nav-link" href="{{ route('suggestions.create') }}">Suggestion</a></li>

          {{-- Abonnements / Plans --}}
          <li class="nav-item"><a class="nav-link" href="{{ route('subscriptions.plans') }}">Abonnements</a></li>

          {{-- Espace Admin (si admin) --}}
          @if($user->is_admin ?? false)
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="adminDrop" data-bs-toggle="dropdown" aria-expanded="false">
                Espace Admin
              </a>
              <ul class="dropdown-menu" aria-labelledby="adminDrop">
                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
                <li><a class="dropdown-item" href="{{ route('annonces.index') }}">Annonces</a></li>
                <li><a class="dropdown-item" href="{{ route('plans.index') }}">Plans d’abonnement</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.suggestions.index') }}">Suggestions</a></li>
                {{-- Ajoute ici la page d’articles en attente/validés si tu as un controller dédié --}}
              </ul>
            </li>
          @endif
        @endauth
      </ul>

      {{-- Zone droite (notifs + user) --}}
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">

        @auth
          {{-- Cloche notifications --}}
          <li class="nav-item dropdown me-lg-2">
            <a class="nav-link position-relative" href="#" id="notifDrop" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-bell"></i>
              @if($unreadCount > 0)
                <span id="notifBadge" class="badge bg-danger rounded-pill badge-notif">{{ $unreadCount }}</span>
              @endif
            </a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-notifs" aria-labelledby="notifDrop">
              @php $items = $user->unreadNotifications()->latest()->take(10)->get(); @endphp
              @forelse($items as $n)
                <li>
                  <a class="dropdown-item small" href="{{ route('notifications.show',$n->id) }}">
                    {{ $n->data['message'] ?? ($n->data['title'] ?? 'Notification') }}
                  </a>
                </li>
              @empty
                <li><span class="dropdown-item text-muted">Aucune notification</span></li>
              @endforelse
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item small text-muted" href="{{ route('articles.index') }}">Voir tout</a></li>
            </ul>
          </li>
        @endauth

        {{-- Connexion / Profil --}}
        @guest
          <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Connexion</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Inscription</a></li>
        @else
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <span class="rounded-circle bg-secondary text-white d-inline-flex justify-content-center align-items-center" style="width:28px;height:28px;">
                {{ mb_substr($user->name,0,1) }}
              </span>
              <span class="d-none d-lg-inline">{{ $user->name }}</span>
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
{{-- /NAVBAR --}}

<main class="py-4 flex-grow-1">
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
  @endphp
  <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between text-center text-md-start">
    <small>© {{ date('Y') }} {{ $settings->company_name ?? 'Votre société' }} — {{ $settings->site_name ?? config('app.name','DGLink_Pub') }}</small>
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

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

{{-- Bell sound --}}
<audio id="notifSound" preload="auto">
  <source src="{{ asset('sounds/bell.mp3') }}" type="audio/mpeg">
</audio>

{{-- Polling notifications (15s) --}}
@auth
<script>
(function(){
  let last = {{ $unreadCount }};
  async function tick(){
    try{
      const r = await fetch('{{ route('notifications.unreadCount') }}', {headers:{'X-Requested-With':'XMLHttpRequest'}});
      const {count} = await r.json();
      const badge = document.getElementById('notifBadge');
      if(count !== last){
        if(count > last){
          document.getElementById('notifSound')?.play().catch(()=>{});
        }
        last = count;
        if(count>0){
          if(!badge){
            const a=document.querySelector('#notifDrop');
            const span=document.createElement('span');
            span.id='notifBadge';
            span.className='badge bg-danger rounded-pill badge-notif';
            span.textContent=count;
            a.appendChild(span);
          } else {
            badge.textContent=count;
          }
        } else {
          badge?.remove();
        }
      }
    }catch(e){}
    setTimeout(tick, 15000);
  }
  tick();
})();
</script>
@endauth

{{-- Bouton remonter en haut --}}
<button id="toTop" class="btn btn-primary rounded-circle shadow">↑</button>
<script>
const toTop = document.getElementById('toTop');
window.addEventListener('scroll', () => {
  toTop.style.display = window.scrollY > 300 ? 'block' : 'none';
});
toTop?.addEventListener('click', () => window.scrollTo({top:0, behavior:'smooth'}));
</script>

{{-- MODAL Rating site --}}
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
// Effet étoiles
const stars = document.querySelectorAll('label[for^="star"]');
stars.forEach((star, idx) => {
  star.addEventListener('mouseenter', () => {
    stars.forEach((s, i) => s.textContent = i <= idx ? '★' : '☆');
  });
});
document.querySelectorAll('input[name="stars"]').forEach(input => {
  input.addEventListener('change', () => {
    stars.forEach((s, i) => s.textContent = i < input.value ? '★' : '☆');
  });
});
</script>

</body>
</html>
