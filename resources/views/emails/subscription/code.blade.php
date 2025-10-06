<!-- resources/views/emails/subscription/code.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Code d'activation de votre abonnement</title>
</head>
<body>
    <p>Bonjour {{ $subscription->user->name }},</p>

    <p>Votre abonnement a été validé. Voici votre code de vérification :</p>

    <h2>{{ $subscription->verification_code }}</h2>

    <p>Merci de votre confiance !</p>
</body>
</html>
