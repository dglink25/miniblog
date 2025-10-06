@extends('layouts.app')
@section('content')
<h1 class="h4 mb-3">{{ $plan->exists?'Éditer':'Nouveau' }} plan</h1>

@if($errors->any())
  <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<form method="POST" action="{{ $plan->exists?route('admin.plans.update',$plan):route('admin.plans.store') }}" class="row g-3">
  @csrf
  @if($plan->exists) @method('PUT') @endif

  <div class="col-md-6">
    <label class="form-label">Nom</label>
    <input name="name" class="form-control" value="{{ old('name',$plan->name) }}" required>
  </div>
  <div class="col-md-3">
    <label class="form-label">Durée (jours)</label>
    <input type="number" min="1" name="duration_days" class="form-control" value="{{ old('duration_days',$plan->duration_days) }}" required>
  </div>
  <div class="col-md-3">
    <label class="form-label">Prix (XOF)</label>
    <input type="number" min="0" name="price" class="form-control" value="{{ old('price',$plan->price) }}" required>
  </div>

  <div class="col-md-4">
    <label class="form-label">Provider</label>
    <select name="payment_provider" class="form-select" required>
      @foreach(['kia'=>'KIApay','feda'=>'FedaPay','other'=>'Autre'] as $k=>$v)
        <option value="{{ $k }}" @selected(old('payment_provider',$plan->payment_provider)===$k)>{{ $v }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-8">
    <label class="form-label">Lien paiement (KIApay)</label>
    <input type="url" name="payment_link" class="form-control" placeholder="https://pay.kiapay.me/..." value="{{ old('payment_link',$plan->payment_link) }}">
  </div>

  <div class="col-12">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="is_active" id="c1" value="1" @checked(old('is_active',$plan->is_active))>
      <label class="form-check-label" for="c1">Actif</label>
    </div>
  </div>

  <div class="col-12">
    <button class="btn btn-success">Enregistrer</button>
  </div>
</form>
@endsection
