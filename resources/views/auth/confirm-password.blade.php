<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmer le mot de passe - MiniBlog DGLINK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title text-center mb-3">Confirmation du mot de passe</h3>
                    <p class="text-center text-muted small mb-4">
                        Vous êtes sur une zone sécurisée de l'application. Veuillez confirmer votre mot de passe avant de continuer.
                    </p>

                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" id="password" name="password" class="form-control" required autocomplete="current-password">
                            @error('password')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary">Confirmer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
