@extends('layouts.app')

@section('content')
<div class="container-xxl">

  {{-- HERO / BIENVENUE --}}
    <div class="p-5 mb-4 bg-light rounded-3 shadow-sm position-relative overflow-hidden hero">
    <div class="row align-items-center">
        <div class="col-lg-8">
        <h1 class="display-5 fw-bold mb-2 text-dark">Bienvenue, {{ Auth::user()->name }} !</h1>
        <p class="lead mb-3 justify text-dark">
            Heureux de vous voir sur <strong>FlashPost de DGLINK</strong>la plateforme pour 
            <strong>publier</strong>, <strong>partager</strong> et <strong>découvrir</strong> des contenus (articles,
            annonces, médias). Tout est pensé pour être <em>rapide, clair et sécurisé</em>.
        </p>
        </div>

        <div class="col-lg-4 d-none d-lg-flex justify-content-center">
        <div class="coin-container reveal">
            <img src="{{ asset('flashpost.png') }}" alt="Logo DGLINK" class="coin">
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
          <h3 class="h5 fw-bold mb-3">Pourquoi utiliser FlashPost de DGLINK ?</h3>
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
            <li class="list-group-item">Plus d'interactions (likes, partages)</li>
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
        Astuce : Ajoutez des <strong>images</strong>, <strong>tableaux</strong> et <strong>vidéos</strong> dans l'éditeur.
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

{{-- STYLES AMÉLIORÉS --}}
<style>
  :root {
    --primary-color: #4361ee;
    --secondary-color: #3a0ca3;
    --accent-color: #ff7b00;
    --success-color: #4bb543;
  }

  .justify { text-align: justify; }

  /* HERO SECTION AMÉLIORÉE */
  .hero {
    background: linear-gradient(135deg, #4361ee, #3a0ca3, #ff7b00);
    background-size: 400% 400%;
    animation: gradientShift 8s ease infinite;
    position: relative;
    overflow: hidden;
  }

  .hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at 30% 70%, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
    animation: float 15s ease-in-out infinite;
  }

  .hero h1, .hero p {
    color: white !important;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }

  .hero h1 {
    background: linear-gradient(135deg, #ffffff, #e2e8f0);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  /* ANIMATIONS PROFESSIONNELLES */
  .reveal {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
  }

  .reveal.show {
    opacity: 1;
    transform: translateY(0);
  }

  .slide-up { transform: translateY(40px); }
  .slide-left { transform: translateX(-40px); }
  .slide-right { transform: translateX(40px); }
  .fade-in { opacity: 0; }
  .zoom-in { transform: scale(0.9); opacity: 0; }

  .slide-up.show { animation: slideUp 0.8s ease-out forwards; }
  .slide-left.show { animation: slideLeft 0.8s ease-out forwards; }
  .slide-right.show { animation: slideRight 0.8s ease-out forwards; }
  .fade-in.show { animation: fadeIn 1s ease-out forwards; }
  .zoom-in.show { animation: zoomIn 0.8s ease-out forwards; }

  @keyframes slideUp {
    from { transform: translateY(40px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
  }

  @keyframes slideLeft {
    from { transform: translateX(-40px); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
  }

  @keyframes slideRight {
    from { transform: translateX(40px); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
  }

  @keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }

  @keyframes zoomIn {
    from { transform: scale(0.9); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
  }

  @keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
  }

  @keyframes float {
    0%, 100% { transform: translate(0, 0) rotate(0deg); }
    33% { transform: translate(30px, -20px) rotate(5deg); }
    66% { transform: translate(-20px, 10px) rotate(-3deg); }
  }

  /* CARTES AVEC EFFETS PROFESSIONNELS */
  .feature-card {
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    border: none !important;
    position: relative;
    overflow: hidden;
  }

  .feature-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
  }

  .feature-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
  }

  .feature-card:hover::before {
    left: 100%;
  }

  /* COIN ANIMATION AMÉLIORÉE */
  .coin-container {
    perspective: 1000px;
    opacity: 0;
    transform: scale(0.7) rotate(10deg);
    transition: all 1s cubic-bezier(0.34, 1.56, 0.64, 1);
  }

  .coin-container.show {
    opacity: 1;
    transform: scale(1) rotate(0deg);
  }

  .coin {
    width: 200px;
    height: 200px;
    border-radius: 50%;
    object-fit: contain;
    animation: spin-coin 8s ease-in-out infinite;
    filter: drop-shadow(0 10px 20px rgba(0,0,0,0.2));
    transition: all 0.3s ease;
  }

  .coin:hover {
    animation-duration: 4s;
    filter: drop-shadow(0 15px 30px rgba(0,0,0,0.3)) brightness(1.1);
  }

  @keyframes spin-coin {
    0% { transform: rotateY(0deg) scale(1); }
    25% { transform: rotateY(90deg) scale(1.05); }
    50% { transform: rotateY(180deg) scale(1); }
    75% { transform: rotateY(270deg) scale(1.05); }
    100% { transform: rotateY(360deg) scale(1); }
  }

  /* BOUTONS AMÉLIORÉS */
  .btn {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    font-weight: 600;
    border: none;
  }

  .btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left 0.5s;
  }

  .btn:hover::before {
    left: 100%;
  }

  .btn-success {
    background: linear-gradient(135deg, var(--success-color), #3a9d5d);
    box-shadow: 0 4px 15px rgba(75, 181, 67, 0.3);
  }

  .btn-success:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(75, 181, 67, 0.4);
  }

  .btn-outline-secondary, .btn-outline-primary {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  }

  .btn-outline-secondary:hover, .btn-outline-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
  }

  /* ALERTE AMÉLIORÉE */
  .alert {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
  }

  .alert:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
  }

  /* CARTES INFORMATIVES */
  .card {
    transition: all 0.3s ease;
    border: none !important;
  }

  .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
  }

  /* RESPONSIVE AMÉLIORÉ */
  @media (max-width: 768px) {
    .hero {
      padding: 2rem !important;
      text-align: center;
    }
    
    .hero h1 {
      font-size: 2.2rem;
    }
    
    .coin {
      width: 150px;
      height: 150px;
      margin-top: 1rem;
    }
    
    .feature-card:hover {
      transform: translateY(-5px) scale(1.01);
    }
    
    .btn {
      width: 100%;
      margin-bottom: 0.5rem;
    }
  }

  @media (max-width: 576px) {
    .hero {
      padding: 1.5rem !important;
    }
    
    .hero h1 {
      font-size: 1.8rem;
    }
    
    .coin {
      width: 120px;
      height: 120px;
    }
    
    .container-xxl {
      padding-left: 0.75rem;
      padding-right: 0.75rem;
    }
  }

  /* AMÉLIORATIONS VISUELLES */
  .bg-primary { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)) !important; }
  .bg-success { background: linear-gradient(135deg, var(--success-color), #3a9d5d) !important; }
  .bg-warning { background: linear-gradient(135deg, #ffd166, #ffb347) !important; }
  .bg-info { background: linear-gradient(135deg, #4cc9f0, #4361ee) !important; color: white !important; }
  .bg-danger { background: linear-gradient(135deg, #f72585, #b5179e) !important; }
  .bg-secondary { background: linear-gradient(135deg, #7209b7, #3a0ca3) !important; }
  .bg-gradient-info { background: linear-gradient(135deg, #e9ecef, #f8f9fa) !important; }

  .list-group-item {
    border: none;
    padding: 0.75rem 0;
    background: transparent;
    position: relative;
    padding-left: 1.5rem;
  }

  .list-group-item::before {
    content: '▸';
    position: absolute;
    left: 0;
    color: var(--primary-color);
    font-weight: bold;
  }
</style>

{{-- JS ANIMATION AU SCROLL AMÉLIORÉ --}}
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const els = document.querySelectorAll('.reveal');
    const io = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          // Délai progressif pour un effet cascade
          const delay = Array.from(els).indexOf(entry.target) * 100;
          setTimeout(() => {
            entry.target.classList.add('show');
          }, delay);
          observer.unobserve(entry.target);
        }
      });
    }, { 
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    });
    
    els.forEach(el => io.observe(el));

    // Animation au survol des cartes
    const cards = document.querySelectorAll('.feature-card');
    cards.forEach(card => {
      card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-8px) scale(1.02)';
      });
      
      card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) scale(1)';
      });
    });

    // Animation du logo au chargement
    const coinContainer = document.querySelector('.coin-container');
    if (coinContainer) {
      setTimeout(() => {
        coinContainer.classList.add('show');
      }, 500);
    }
  });
</script>
@endsection