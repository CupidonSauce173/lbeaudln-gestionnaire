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
                <p>Entrez le code de confirmation que vous avez reçu par SMS.</p>
                <form action="/confirm-opt" method="POST">
                    <div class="mb-3">
                        <label for="opt" class="form-label">Code :</label>
                        <input type="text" class="form-control" id="opt" name="opt" required>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success">Confirmer Code</button>
                    </div>
                    <div class="text-center mt-3">
                        <a href="/public/index.php" class="forgot-password">Retour</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
