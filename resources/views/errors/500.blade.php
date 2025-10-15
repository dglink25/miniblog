@extends('layouts.app')

@section('title', 'Oups ! Une erreur est survenue | DGLINK')

@section('content')
<div class="relative flex flex-col items-center justify-center min-h-[80vh] px-6 py-12 text-center overflow-hidden bg-gradient-to-br from-indigo-600 via-purple-600 to-blue-600 text-white">

    {{-- Effet d’arrière-plan avec formes animées --}}
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-10 left-10 w-20 h-20 bg-white rounded-full animate-pulse"></div>
        <div class="absolute bottom-16 right-10 w-32 h-32 bg-white rounded-full animate-bounce"></div>
        <div class="absolute top-1/2 left-1/3 w-16 h-16 bg-white rounded-full animate-ping"></div>
    </div>

    <div class="relative z-10 max-w-lg bg-white/10 backdrop-blur-lg rounded-2xl shadow-2xl p-10 animate-fadeInUp">
        <div class="mb-6 flex justify-center">
            <div class="w-20 h-20 rounded-2xl bg-white/20 flex items-center justify-center border border-white/30 shadow-lg">
                <i class="fas fa-link text-3xl text-white"></i>
            </div>
        </div>

        <h2 class="text-2xl md:text-3xl font-semibold mb-4">Quelque chose s’est mal passé</h2>

        <p class="text-base md:text-lg text-gray-100 leading-relaxed mb-6">
            Une erreur inattendue est survenue sur la plateforme.  
            Notre équipe technique a été notifiée et travaille activement à la corriger.  
            Vous pouvez retourner à l’accueil ou réessayer dans quelques instants.
        </p>

        <div class="flex flex-wrap justify-center gap-4 mt-6">
            <a href="{{ url('/') }}"
               class="inline-flex items-center gap-2 bg-white text-indigo-700 hover:bg-gray-100 px-5 py-3 rounded-lg font-medium shadow-md transition duration-300 ease-in-out">
                <i class="fas fa-home"></i> Retour à l’accueil
            </a>

            <button onclick="window.location.reload()"
                    class="inline-flex items-center gap-2 bg-indigo-500 hover:bg-indigo-600 text-white px-5 py-3 rounded-lg font-medium shadow-md transition duration-300 ease-in-out">
                <i class="fas fa-redo"></i> Réessayer
            </button>
        </div>

        <p class="text-sm text-gray-200 mt-8">
            Besoin d’aide ? <a href="{{ route('suggestions.create') }}" class="underline hover:text-white">Contactez notre support</a>
        </p>
    </div>
</div>

{{-- Petite animation CSS si Tailwind n’est pas chargé --}}
<style>
@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-fadeInUp {
  animation: fadeInUp 0.8s ease-out;
}
</style>
@endsection
