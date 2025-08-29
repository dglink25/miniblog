<x-mail::message>
# Introduction



@component('mail::message')
# Code d’activation

Bonjour {{ $sub->user->name }},

Votre paiement pour **{{ $sub->plan->name }}** a bien été reçu.

Voici votre **code d’activation** :
@component('mail::panel')
**{{ $sub->verification_code }}**
@endcomponent

Saisissez ce code ici : {{ route('subscriptions.verifyForm') }}

Merci,  
L’équipe DGLINK Pub
@endcomponent


</x-mail::message>
