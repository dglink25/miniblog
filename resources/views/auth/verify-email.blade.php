<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification de l'e-mail - MiniBlog DGLINK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title text-center mb-3">Vérifiez votre e-mail</h3>
                    <p class="text-center text-muted small mb-4">
                        Merci de vous être inscrit ! Avant de commencer, veuillez vérifier votre adresse e-mail en cliquant sur le lien que nous venons de vous envoyer. Si vous ne l’avez pas reçu, nous vous en enverrons un nouveau.
                    </p>

                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success text-center">
                            Un nouveau lien de vérification a été envoyé à votre adresse e-mail.
                        </div>
                    @endif

                    <div class="d-flex justify-content-between mt-4">
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button class="btn btn-primary">Renvoyer l'e-mail de vérification</button>
                        </form>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-link text-decoration-none text-muted">Se déconnecter</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
