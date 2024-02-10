<?php

use Lbeaudln\Gestionnaire\Models\User;

$error = '';
$customers = [];

if (session_status() != PHP_SESSION_ACTIVE) session_start();

$firstName = '';
$lastName = '';
if ($_SESSION['user'] instanceof User) {
    $firstName = $_SESSION['user']->getFirstName();
    $lastName = $_SESSION['user']->getLastName();
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

        <!-- Options du haut-->
        <div class="col-md-10">
            <div class="top-bar d-flex justify-content-between align-items-center">
                <h2>Utilisateurs - Créer</h2>
            </div>
            <div class="main-body p-4">
                <form action="/account/users/add" method="post">
                    <h2>Fiche nouveau utilisateur</h2>
                    <div class="container">
                        <p>Vous pouvez créer un nouveal utilisateur ici.</p>
                        <?php include __DIR__ . '/../Components/sessionMessages.php' ?>
                        <div class="row mb-3">
                            <div class="col-12 col-md-4">
                                <label for="firstName" class="col-form-label font-weight-bold">Prénom:</label>
                            </div>
                            <div class="col-12 col-md-8">
                                <input type="text" name="firstName" id="firstName" value="" class="form-control"
                                       required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 col-md-4">
                                <label for="lastName" class="col-form-label font-weight-bold">Nom :</label>
                            </div>
                            <div class="col-12 col-md-8">
                                <input type="text" name="lastName" id="lastName" value="" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 col-md-4">
                                <label for="username" class="col-form-label font-weight-bold">Utilisateur :</label>
                            </div>
                            <div class="col-12 col-md-8">
                                <input type="text" name="username" id="username" value="" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 col-md-4">
                                <label for="cellPhone" class="col-form-label font-weight-bold">Téléphone :</label>
                            </div>
                            <div class="col-12 col-md-8">
                                <input type="text" name="cellPhone" id="cellPhone" value="" class="form-control"
                                       required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 col-md-4">
                                <label for="email" class="col-form-label font-weight-bold">Email :</label>
                            </div>
                            <div class="col-12 col-md-8">
                                <input type="email" name="email" id="email" value="" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 col-md-4">
                                <label for="password" class="col-form-label font-weight-bold">Mot de passe par défaut
                                    :</label>
                            </div>
                            <div class="col-12 col-md-8">
                                <input type="password" name="password" id="password" value="" class="form-control"
                                       required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 col-md-4">
                                <label for="repeatedPassword" class="col-form-label font-weight-bold">Répéter mot de
                                    passe :</label>
                            </div>
                            <div class="col-12 col-md-8">
                                <input type="password" name="repeatedPassword" id="repeatedPassword" value=""
                                       class="form-control" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-outline-success col-12">Créer le nouveau compte
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