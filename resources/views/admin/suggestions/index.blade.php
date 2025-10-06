{{-- resources/views/admin/suggestions/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>💡 Suggestions des utilisateurs</h2>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Auteur</th>
                <th>Contenu</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($items as $s)
            <tr>
                <td>{{ $s->user->name ?? 'Utilisateur inconnu' }}</td>
                <td>{{ Str::limit($s->content,50) }}</td>
                <td>{{ ucfirst($s->status) }}</td>
                <td>{{ $s->created_at->diffForHumans() }}</td>
                <td>
                    <a href="{{ route('admin.suggestions.show',$s) }}" class="btn btn-sm btn-info">👁️ Voir</a>
                </td>
            </tr>
        @empty
            <tr><td colspan="5">Aucune suggestion</td></tr>
        @endforelse
        </tbody>
    </table>

    {{ $items->links() }}
</div>
@endsection
