@extends('layouts.app')
@section('content')
<h1 class="h4 mb-3">Abonnements</h1>

@if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

@auth
  @if($active)
    <div class="alert alert-success">
      Abonnement actif : <strong>{{ $active->plan->name }}</strong> — expire le {{ $active->ends_at->format('d/m/Y') }}
    </div>
  @else
    <div class="alert alert-warning">
      @if($trialEndsAt && $trialEndsAt->isFuture())
        Période d’essai jusqu’au <strong>{{ $trialEndsAt->format('d/m/Y') }}</strong>.
      @else
        Votre essai est terminé. Abonnez-vous pour publier.
      @endif
    </div>
  @endif
@endauth

<div class="row g-3">
  @foreach($plans as $plan)
  <div class="col-12 col-md-6 col-lg-4">
    <div class="card h-100 shadow-sm">
      <div class="card-body d-flex flex-column">
        <h5 class="card-title">{{ $plan->name }}</h5>
        <p class="card-text mb-1"><strong>{{ number_format($plan->price,0,' ',' ') }} XOF</strong></p>
        <p class="text-muted mb-3">{{ $plan->duration_days }} jours</p>
        <form method="POST" action="{{ route('subscriptions.checkout',$plan) }}" class="mt-auto">
          @csrf
          @auth
            <button class="btn btn-primary w-100">Payer</button>
          @else
            <a href="{{ route('login') }}" class="btn btn-outline-primary w-100">Se connecter</a>
          @endauth
        </form>
      </div>
    </div>
  </div>
  @endforeach
</div>
@endsection