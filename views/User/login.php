<?php
$error = '';
if (session_status() == PHP_SESSION_ACTIVE && isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}
if (session_status() == PHP_SESSION_ACTIVE && isset($_SESSION['user_id'])) {
    // user already logged in, redirecting to dashboard.
    global $router;
    $router->redirect('/dashboard', 'GET');
}
?>
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
            <div class="login-container border-box bg-white">
                <form action="/login" method="POST">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success">Connexion</button>
                    </div>
                    <div class="text-center mt-3">
                        <a href="/reset-information" class="forgot-password">Mot de passe oublié?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
