<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Article rejeté</title>
</head>
<body>
    <h2>Bonjour {{ $article->user->name }},</h2>

    <p>Votre article <strong>{{ $article->title }}</strong> a été <span style="color: red;">rejeté</span>.</p>

    <p><strong>Motif :</strong> {{ $reason }}</p>

    <p>Vous pouvez vous connecter pour corriger et republier l’article.</p>

    <p>Merci,<br>L’équipe {{ config('app.name') }}</p>
</body>
</html>
