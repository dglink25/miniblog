{{-- resources/views/admin/plans/form.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ $plan->exists ? '✏️ Modifier le plan' : '➕ Nouveau plan' }}</h2>

    <form method="POST" action="{{ $plan->exists ? route('plans.update', $plan) : route('plans.store') }}">
        @csrf
        @if($plan->exists) @method('PUT') @endif

        <div class="mb-3">
            <label>Nom du plan</label>
            <input type="text" name="name" value="{{ old('name',$plan->name) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Durée (jours)</label>
            <input type="number" name="duration_days" value="{{ old('duration_days',$plan->duration_days) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Prix (FCFA)</label>
            <input type="number" name="price" value="{{ old('price',$plan->price) }}" class="form-control" required>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active',$plan->is_active) ? 'checked' : '' }}>
            <label class="form-check-label">Activer ce plan</label>
        </div>

        <button type="submit" class="btn btn-success">💾 Enregistrer</button>
        <a href="{{ route('plans.index') }}" class="btn btn-secondary">⬅️ Retour</a>
    </form>
</div>
@endsection
