@extends('layouts.app')
@section('content')
<h1 class="h4 mb-3">Accorder un droit de publication</h1>

@if($errors->any())
  <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<form method="POST" action="{{ route('admin.subscriptions.grantStore') }}" class="row g-3">
  @csrf
  <div class="col-md-4">
    <label class="form-label">Utilisateur</label>
    <select name="user_id" class="form-select" required>
      @foreach($users as $u)
        <option value="{{ $u->id }}">{{ $u->name }} — {{ $u->email }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-4">
    <label class="form-label">Plan (pour durée par défaut)</label>
    <select name="plan_id" class="form-select" required>
      @foreach($plans as $p)
        <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->duration_days }} j)</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-2">
    <label class="form-label">Durée perso (jours)</label>
    <input type="number" min="1" name="days" class="form-control" placeholder="optionnel">
  </div>
  <div class="col-md-2 d-flex align-items-end">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="send_code" id="sc" value="1">
      <label class="form-check-label" for="sc">Envoyer le code par e-mail</label>
    </div>
  </div>
  <div class="col-12">
    <button class="btn btn-success">Valider</button>
  </div>
</form>
@endsection
