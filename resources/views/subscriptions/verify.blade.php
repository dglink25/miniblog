@extends('layouts.app')
@section('content')
<h1 class="h4 mb-3">Activer l’abonnement</h1>
@if(!$lastPending)
  <div class="alert alert-info">Aucune souscription à valider.</div>
@else
  <div class="card">
    <div class="card-body">
      <p>Plan : <strong>{{ $lastPending->plan->name }}</strong></p>
      <form method="POST" action="{{ route('subscriptions.verifyCode') }}">
        @csrf
        <div class="mb-3">
          <label class="form-label">Code reçu par e-mail</label>
          <input class="form-control" name="code" required>
        </div>
        <button class="btn btn-primary">Valider</button>
      </form>
    </div>
  </div>
@endif
@endsection