<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - MiniBlog DGLINK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title text-center mb-3">Créer un compte</h3>
                    <p class="text-center text-muted small">
                        Rejoignez MiniBlog de DGLINK pour publier et suivre des publications intéressantes!
                    </p>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom complet</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required autofocus>
                            @error('name')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse e-mail</label>
                            <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                            @error('email')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                            @error('password')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                            @error('password_confirmation')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <button class="btn btn-primary w-100">S’inscrire</button>
                    </form>

                    <hr>
                    <p class="text-center small mb-0">
                        Déjà inscrit ? <a href="{{ route('login') }}" class="text-decoration-none">Se connecter</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
