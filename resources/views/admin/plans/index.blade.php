{{-- resources/views/admin/plans/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>📦 Liste des Plans d'abonnement</h2>
    <a href="{{ route('plans.create') }}" class="btn btn-primary mb-3">➕ Nouveau plan</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Durée (jours)</th>
                <th>Prix (FCFA)</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($plan as $p)
            <tr>
                <td>{{ $p->name }}</td>
                <td>{{ $p->duration_days }}</td>
                <td>{{ number_format($p->price, 0, ',', ' ') }}</td>
                <td>{{ $p->is_active ? '✅ Actif' : '❌ Inactif' }}</td>
                <td>
                    <a href="{{ route('plans.edit', $p) }}" class="btn btn-sm btn-warning">✏️ Modifier</a>
                    <form action="{{ route('plans.destroy', $p) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ?')">🗑️ Supprimer</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5">Aucun plan défini</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
