<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion à MiniBlog DGLINK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title text-center mb-3">Connexion</h3>
                    <p class="text-center text-muted small">
                        Bienvenue sur MiniBlog de DGLINK. Connectez-vous pour partager et découvrir des publications intéressantes.
                    </p>

                    @if(session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse e-mail</label>
                            <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                            @error('email')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                            @error('password')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" id="remember_me" name="remember" class="form-check-input">
                            <label class="form-check-label" for="remember_me">Se souvenir de moi</label>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('password.request') }}" class="small text-decoration-none">Mot de passe oublié ?</a>
                            <button class="btn btn-primary">Se connecter</button>
                        </div>
                    </form>

                    <hr>
                    <p class="text-center small mb-0">
                        Pas encore inscrit ? <a href="{{ route('register') }}" class="text-decoration-none">Créer un compte</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
