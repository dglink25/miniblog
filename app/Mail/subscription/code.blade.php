@component('mail::message')
# Code d’activation

Bonjour {{ $sub->user->name }},

Merci pour votre paiement de **{{ number_format($sub->plan->price,0,' ',' ') }} XOF**
pour le plan **{{ $sub->plan->name }}**.

Voici votre **code d’activation** :

@component('mail::panel')
**{{ $sub->verification_code }}**
@endcomponent

Rendez-vous ici pour l’activer :
@component('mail::button', ['url' => route('subscriptions.verifyForm')])
Activer mon abonnement
@endcomponent

Ce code expirera lorsque vous l’aurez utilisé.

Merci,  
{{ config('app.name') }}
@endcomponent
