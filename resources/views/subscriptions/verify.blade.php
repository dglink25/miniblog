@extends('layouts.app')

@section('content')
<h1 class="h4 mb-3">Activer mon abonnement</h1>

@if($errors->any())
  <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

@if($lastPending)
  <div class="alert alert-info">
    Abonnement en attente : <strong>{{ $lastPending->plan->name }}</strong>.
    Entrez le code reçu par e-mail.
  </div>
@endif

<form method="POST" class="row gy-2 gx-2" action="{{ route('subscriptions.verifyCode') }}">
  @csrf
  <div class="col-12 col-md-6">
    <input type="text" name="code" class="form-control" placeholder="CODE" required>
  </div>
  <div class="col-12 col-md-auto">
    <button class="btn btn-success">Activer</button>
  </div>
</form>
@endsection
