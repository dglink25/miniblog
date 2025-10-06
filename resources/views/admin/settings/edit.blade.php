@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-white shadow-md rounded-lg p-6">
    <h1 class="text-xl font-bold mb-4">Réglages</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="trial_days" class="block text-sm font-medium text-gray-700">
                Durée de l’essai gratuit (en jours)
            </label>
            <input type="number" name="trial_days" id="trial_days" 
                   value="{{ old('trial_days', $trialDays) }}" 
                   class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm" min="1" max="365" required>
        </div>

        <button type="submit" 
                class="bg-blue text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Sauvegarder
        </button>
    </form>
</div>
@endsection
