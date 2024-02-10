<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionnaire L.Beaudin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    <style>
        .login-container {
            max-width: 400px;
            margin-top: 50px;
        }

        .border-box {
            border: 1px solid rgba(0, 0, 0, .125);
            padding: 20px;
            border-radius: 0.25rem;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7 col-sm-9">
            <div class="text-center my-5">
                <h2>Gestionnaire L.Beaudin</h2>
            </div>
            <h3>Réinitialisation du mot de passe</h3>
            <div class="login-container border-box bg-white">
                <p>Entrez votre email et numéro de téléphone pour recevoir un SMS avec le code de réinitialisation.</p>
                <form action="/reset-password" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Téléphone:</label>
                        <input type="tel" class="form-control" id="phone" name="phone" required>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success">Envoyer SMS</button>
                    </div>
                    <div class="text-center mt-3">
                        <a href="/login" class="forgot-password">Retour</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
