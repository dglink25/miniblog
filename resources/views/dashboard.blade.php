@extends('layouts.app')

@section('content')
<div class="container-xxl">

  {{-- HERO / BIENVENUE --}}
    <div class="p-5 mb-4 bg-light rounded-3 shadow-sm position-relative overflow-hidden hero">
    <div class="row align-items-center">
        <div class="col-lg-8">
        <h1 class="display-5 fw-bold mb-2 text-dark">Bienvenue, {{ Auth::user()->name }} !</h1>
        <p class="lead mb-3 justify text-dark">
            Heureux de vous voir sur <strong>Miniblog de DGLINK</strong> — la plateforme pour 
            <strong>publier</strong>, <strong>partager</strong> et <strong>découvrir</strong> des contenus (articles,
            annonces, médias). Tout est pensé pour être <em>rapide, clair et sécurisé</em>.
        </p>
        </div>

        <div class="col-lg-4 d-none d-lg-flex justify-content-center">
        <div class="coin-container reveal">
            <img src="{{ asset('logo.png') }}" alt="Logo DGLINK" class="coin">
        </div>
        </div>
    </div>
    </div>

  {{-- CARTES FONCTIONNALITÉS --}}
  <div class="row g-4 mt-4">
    @php
      $features = [
        ['','Publier des articles','Rédigez avec un éditeur riche (titres, images, tableaux…).','bg-light'],
        ['','Notifications en temps réel','Mail + sur le site (son de cloche, badge).','bg-primary text-white'],
        ['','Annonces avec médias','Image, lien YouTube, TikTok, vidéo MP4 intégrée.','bg-success text-white'],
        ['','Abonnements','Suivez des auteurs et soyez alerté de leurs publications.','bg-warning'],
        ['','Recherche avancée','Trouvez rapidement un contenu par titre/texte.','bg-info text-dark'],
        ['','Notes & favoris','Note unique par article + liste de favoris.','bg-danger text-white'],
      ];
    @endphp

    @foreach($features as [$icon, $title, $desc, $color])
      <div class="col-12 col-md-6 col-xl-4 reveal slide-up">
        <div class="card h-100 shadow-sm border-0 feature-card {{ $color }}">
          <div class="card-body">
            <div class="fs-2 mb-2">{{ $icon }}</div>
            <h5 class="card-title mb-1">{{ $title }}</h5>
            <p class="card-text justify">{{ $desc }}</p>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  {{-- POURQUOI UTILISER --}}
  <div class="row mt-5 g-4">
    <div class="col-lg-6 reveal slide-left">
      <div class="card shadow-sm border-0 h-100 bg-light">
        <div class="card-body">
          <h3 class="h5 fw-bold mb-3">Pourquoi utiliser Miniblog de DGLINK ?</h3>
          <p class="justify">
            Plateforme <strong>rapide</strong>, <strong>intuitive</strong> et <strong>sécurisée</strong>.
          </p>
          <ul class="list-unstyled mb-0">
            <li class="mb-2">✔️ Restez informé via notifications</li>
            <li class="mb-2">✔️ Publiez en quelques clics</li>
            <li class="mb-2">✔️ Contrôlez vos contenus (éditer/supprimer)</li>
          </ul>
        </div>
      </div>
    </div>

    <div class="col-lg-6 reveal slide-right">
      <div class="card shadow-sm border-0 h-100 bg-secondary text-white">
        <div class="card-body">
          <h3 class="h5 fw-bold mb-3">Pourquoi nous choisir ?</h3>
          <ul class="list-unstyled mb-0">
            <li class="mb-2">Interface claire & 100% responsive</li>
            <li class="mb-2">Données protégées & rôles admin</li>
            <li class="mb-2">Support réactif & mises à jour régulières</li>
            <li class="mb-2">Communauté en croissance</li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  {{-- PERSPECTIVES --}}
  <div class="card mt-5 shadow-sm border-0 reveal fade-in bg-gradient-info text-dark">
    <div class="card-body">
      <h3 class="h5 fw-bold mb-3">📈 Perspectives & évolutions</h3>
      <div class="row g-4">
        <div class="col-md-6">
          <ul class="list-group list-group-flush">
            <li class="list-group-item">Application mobile Android & iOS</li>
            <li class="list-group-item">Plus d’interactions (likes, partages)</li>
            <li class="list-group-item">Paiements (abonnements) intégrés</li>
          </ul>
        </div>
        <div class="col-md-6">
          <ul class="list-group list-group-flush">
            <li class="list-group-item">📊 Statistiques détaillées des articles</li>
            <li class="list-group-item">Intégrations médias améliorées</li>
            <li class="list-group-item">Outils de modération avancés</li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  {{-- ASTUCES --}}
  <div class="row mt-5 g-4">
    <div class="col-lg-8 reveal slide-left">
      <div class="alert alert-info shadow-sm m-0">
        Astuce : Ajoutez des <strong>images</strong>, <strong>tableaux</strong> et <strong>vidéos</strong> dans l’éditeur.
      </div>
    </div>
    <div class="col-lg-4 reveal slide-right">
      <div class="d-grid gap-2">
        @if(Route::has('articles.create'))
          <a href="{{ route('articles.create') }}" class="btn btn-success">Créer une publication</a>
        @endif
        @if(Route::has('articles.mine'))
          <a href="{{ route('articles.mine') }}" class="btn btn-outline-secondary">Mes publications</a>
        @endif
        @if(Route::has('suggestions.create'))
          <a href="{{ route('suggestions.create') }}" class="btn btn-outline-primary">Vos suggestions</a>
        @endif
      </div>
    </div>
  </div>

</div>

{{-- STYLES --}}
<style>
  .justify { text-align: justify; }

  /* Animations */
  .reveal{ opacity: 0; transform: translateY(20px); transition: all .8s ease; }
  .reveal.show{ opacity: 1; transform: none; }
  .fade-in.show{ animation: fadeIn 1s ease forwards; }
  .slide-up.show{ animation: slideUp 1s ease forwards; }
  .slide-left.show{ animation: slideLeft 1s ease forwards; }
  .slide-right.show{ animation: slideRight 1s ease forwards; }
  .zoom-in.show{ animation: zoomIn 1s ease forwards; }

  @keyframes fadeIn{ from{opacity:0} to{opacity:1} }
  @keyframes slideUp{ from{transform:translateY(40px);opacity:0} to{transform:translateY(0);opacity:1} }
  @keyframes slideLeft{ from{transform:translateX(-40px);opacity:0} to{transform:translateX(0);opacity:1} }
  @keyframes slideRight{ from{transform:translateX(40px);opacity:0} to{transform:translateX(0);opacity:1} }
  @keyframes zoomIn{ from{transform:scale(0.8);opacity:0} to{transform:scale(1);opacity:1} }
</style>

{{-- JS Animation au scroll --}}
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const els = document.querySelectorAll('.reveal');
    const io = new IntersectionObserver(entries => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          e.target.classList.add('show');
          io.unobserve(e.target);
        }
      });
    }, {threshold:.15});
    els.forEach(el => io.observe(el));
  });
</script>

{{-- STYLES HERO --}}
<style>
  .hero {
    background: linear-gradient(135deg, #ffffff, #f8fafc);
  }

  /* Animation pièce */
  .coin-container {
    perspective: 1000px;
    opacity: 0;
    transform: scale(0.7);
    transition: all 0.8s ease-out;
  }

  .coin-container.show {
    opacity: 1;
    transform: scale(1);
  }

  .coin {
    width: 200px;
    height: 200px;
    border-radius: 50%;
    object-fit: contain;
    animation: spin-coin 6s linear infinite;
  }

  @keyframes spin-coin {
    0%   { transform: rotateY(0deg); }
    50%  { transform: rotateY(180deg); }
    100% { transform: rotateY(360deg); }
  }

  /* Texte plus lisible */
  .hero h1, .hero p {
    color: #1e293b; /* gris foncé */
  }
</style>

{{-- RÉVÉLATION AU SCROLL --}}
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const els = document.querySelectorAll('.reveal');
    const io = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('show');
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15 });
    els.forEach(el => io.observe(el));
  });
</script>
@endsection
