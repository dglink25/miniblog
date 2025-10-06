{{-- resources/views/admin/suggestions/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>👁️ Détail de la suggestion</h2>

    <p><strong>Auteur :</strong> {{ $suggestion->user->name ?? 'Utilisateur inconnu' }}</p>
    <p><strong>Objet :</strong> {{ $suggestion->subject }}</p>
    <p><strong>Contenu :</strong> {{ $suggestion->message }}</p>
    <p><strong>Status :</strong> {{ ucfirst($suggestion->status) }}</p>
    <p><strong>Date :</strong> {{ $suggestion->created_at->format('d/m/Y H:i') }}</p>

    <form method="POST" action="{{ route('admin.suggestions.updateStatus', $suggestion) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Changer le statut</label>
            <select name="status" class="form-select">
                <option value="new" {{ $suggestion->status=='new'?'selected':'' }}>Nouveau</option>
                <option value="seen" {{ $suggestion->status=='seen'?'selected':'' }}>Vu</option>
                <option value="closed" {{ $suggestion->status=='closed'?'selected':'' }}>Fermé</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">✅ Mettre à jour</button>
        <a href="{{ route('admin.suggestions.index') }}" class="btn btn-secondary">⬅️ Retour</a>
    </form>
</div>
@endsection
