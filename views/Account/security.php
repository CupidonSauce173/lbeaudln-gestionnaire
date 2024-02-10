<?php

use Lbeaudln\Gestionnaire\Models\User;

if (session_status() != PHP_SESSION_ACTIVE) session_start();

$firstName = '';
$lastName = '';
if ($_SESSION['user'] instanceof User) {
    $firstName = $_SESSION['user']->getFirstName();
    $lastName = $_SESSION['user']->getLastName();
}
if (empty($firstName) || empty($lastName)) {
    $_SESSION['error'] = "Problème lors du rendu de la page.";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Gestionnaire L.Beaudin</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
          integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <!-- Font Awesome CSS (for icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.css"
          integrity="sha512-tx5+1LWHez1QiaXlAyDwzdBTfDjX07GMapQoFTS74wkcPMsI3So0KYmFe6EHZjI8+eSG0ljBlAQc3PQ5BTaZtQ=="
          crossorigin="anonymous"/>

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>

    <!-- Popper.js (Make sure it's version 2.x or higher) -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"></script>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js"></script>


    <!-- DataTables JavaScript -->
    <script type="text/javascript" charset="utf8"
            src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <!-- Custom stylesheets -->
    <link rel="stylesheet" type="text/css" href="/css/style.css">
</head>
<body>

<div class="container-fluid bg-light">
    <div class="row">
        <!-- Le menu sidebar -->
        <?php include __DIR__ . '/../Components/sidebar.php' ?>

        <!-- Body -->

        <div class="col-md-10">
            <div class="top-bar d-flex justify-content-between align-items-center">
                <h2>Compte - Sécurité</h2>
            </div>
            <div class="main-body p-4">
                <form action="/account/security" method="post">
                    <div class="container">
                        <?php include __DIR__ . '/../Components/sessionMessages.php' ?>
                        <p>Vous pouvez modifier votre mot de passe ici.</p>
                        <div class="row mb-3">
                            <div class="col-12 col-md-4">
                                <label for="currentPassword" class="font-weight-bold">Mot de passe actuel:</label>
                            </div>
                            <div class="col-12 col-md-8">
                                <input type="password" name="currentPassword" id="currentPassword" value="************"
                                       class="form-control" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 col-md-4">
                                <label for="newPassword" class="font-weight-bold">Nouveau mot de passe :</label>
                            </div>
                            <div class="col-12 col-md-8">
                                <input type="password" name="newPassword" id="newPassword" value=""
                                       class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 col-md-4">
                                <label for="repeatedPassword" class="font-weight-bold">Répéter mot de passe :</label>
                            </div>
                            <div class="col-12 col-md-8">
                                <input type="password" name="repeatedPassword" id="repeatedPassword" value=""
                                       class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-outline-success col-12">Modifier mot de passe
                                </button>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <a href="/dashboard" class="btn btn-outline-danger col-12">Annuler</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js"></script>
</body>
</html>