<h2>Bonjour {{ $article->user->name }}</h2>

<p>Votre article <strong>{{ $article->title }}</strong> a été {{ $status }}.</p>

@if($status === 'rejeté' && $reason)
    <p>Motif : {{ $reason }}</p>
@endif

<p>Merci de votre contribution !</p>