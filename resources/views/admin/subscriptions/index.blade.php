@extends('layouts.app')
@section('content')
<h1 class="h4 mb-3">Abonnements</h1>

<form method="GET" class="row g-2 mb-3">
  <div class="col-md-2">
    <select name="status" class="form-select">
      <option value="">-- statut --</option>
      @foreach(['pending','active','expired','cancelled'] as $s)
        <option value="{{ $s }}" @selected(($filters['status']??'')===$s)>{{ $s }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-2">
    <select name="source" class="form-select">
      <option value="">-- source --</option>
      @foreach(['kia','feda','admin'] as $s)
        <option value="{{ $s }}" @selected(($filters['source']??'')===$s)>{{ $s }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-2">
    <select name="plan_id" class="form-select">
      <option value="">-- plan --</option>
      @foreach($plans as $p)
        <option value="{{ $p->id }}" @selected(($filters['plan_id']??'')==$p->id)>{{ $p->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-2"><input class="form-control" type="date" name="from" value="{{ $filters['from']??'' }}"></div>
  <div class="col-md-2"><input class="form-control" type="date" name="to"   value="{{ $filters['to']??'' }}"></div>
  <div class="col-md-2"><input class="form-control" type="text" name="q" placeholder="Nom/email" value="{{ $filters['q']??'' }}"></div>
  <div class="col-12"><button class="btn btn-outline-secondary">Filtrer</button>
    <a class="btn btn-outline-primary ms-2" href="{{ route('admin.subscriptions.grantForm') }}">Accorder un accès</a>
  </div>
</form>

<table class="table table-sm align-middle">
  <thead>
    <tr>
      <th>N°</th><th>Utilisateur</th><th>Plan</th><th>Statut</th><th>Source</th>
      <th>Début</th><th>Fin</th><th>Créée</th><th></th>
    </tr>
  </thead>
  <tbody>
    @foreach($subs as $s)
    <tr>
      <td>{{ $s->id }}</td>
      <td>{{ $s->user->name }} <small class="text-muted d-block">{{ $s->user->email }}</small></td>
      <td>{{ $s->plan->name }}</td>
      <td><span class="badge bg-{{ $s->status==='active'?'success':($s->status==='pending'?'warning text-dark':'secondary') }}">{{ $s->status }}</span></td>
      <td>{{ $s->source }}</td>
      <td>{{ $s->starts_at?->format('d/m/Y H:i') ?? '-' }}</td>
      <td>{{ $s->ends_at?->format('d/m/Y H:i') ?? '-' }}</td>
      <td>{{ $s->created_at->format('d/m/Y H:i') }}</td>
      <td class="text-end">
        @if($s->status==='pending')
          <form class="d-inline" method="POST" action="{{ route('admin.subscriptions.markPaid',$s) }}">
            @csrf <button class="btn btn-sm btn-outline-primary">Marquer payé & envoyer code</button>
          </form>
        @endif
        @if($s->status!=='active')
          <form class="d-inline" method="POST" action="{{ route('admin.subscriptions.activate',$s) }}">
            @csrf <button class="btn btn-sm btn-outline-success">Activer</button>
          </form>
        @endif
      </td>
    </tr>
    @endforeach
  </tbody>
</table>

{{ $subs->withQueryString()->links() }}
@endsection
