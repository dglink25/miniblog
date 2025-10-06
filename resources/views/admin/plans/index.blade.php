@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h4">Plans</h1>
  <a href="{{ route('admin.plans.create') }}" class="btn btn-primary">Nouveau plan</a>
</div>

<table class="table table-sm align-middle">
  <thead><tr>
    <th>Nom</th><th>Prix</th><th>Durée</th><th>Provider</th><th>Actif</th><th></th>
  </tr></thead>
  <tbody>
  @foreach($plans as $p)
    <tr>
      <td>{{ $p->name }}</td>
      <td>{{ number_format($p->price,0,' ',' ') }} XOF</td>
      <td>{{ $p->duration_days }} j</td>
      <td>{{ strtoupper($p->payment_provider) }}</td>
      <td>{!! $p->is_active?'<span class="badge bg-success">oui</span>':'<span class="badge bg-secondary">non</span>' !!}</td>
      <td class="text-end">
        <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.plans.edit',$p) }}">Éditer</a>
        <form action="{{ route('admin.plans.destroy',$p) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ?')">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-outline-danger">Supprimer</button>
        </form>
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
@endsection
