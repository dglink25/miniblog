{{-- resources/views/admin/annonces/index.blade.php --}}
@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h4 mb-0">Annonces</h1>
  <a href="{{ route('annonces.create') }}" class="btn btn-primary btn-sm">Nouvelle annonce</a>
</div>
<table class="table table-hover align-middle">
  <thead><tr>
    <th>Titre</th><th>Publiée</th><th>Épinglée</th><th>Créée</th><th></th>
  </tr></thead>
  <tbody>
    @foreach($annonces as $a)
    <tr>
      <td>{{ $a->title }}</td>
      <td>{!! $a->is_published ? '✅' : '⏸️' !!}</td>
      <td>{!! $a->is_pinned ? '📌' : '—' !!}</td>
      <td>{{ $a->created_at->diffForHumans() }}</td>
      <td class="text-end">
        <form class="d-inline" method="POST" action="{{ route('admin.annonces.toggle',$a) }}">
          @csrf <button class="btn btn-sm btn-outline-secondary">Publier ⟷</button>
        </form>
        <a href="{{ route('annonces.edit',$a) }}" class="btn btn-sm btn-outline-primary">Éditer</a>
        <form class="d-inline" method="POST" action="{{ route('annonces.destroy',$a) }}"
              onsubmit="return confirm('Supprimer ?')">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-outline-danger">Supprimer</button>
        </form>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
{{ $annonces->links() }}
@endsection