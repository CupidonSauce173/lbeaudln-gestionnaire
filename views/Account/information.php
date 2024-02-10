<?php

use Lbeaudln\Gestionnaire\Models\User;

if (session_status() != PHP_SESSION_ACTIVE) session_start();

$firstName = '';
$lastName = '';
$email = '';
$phone = '';
$username = '';
if ($_SESSION['user'] instanceof User) {
    $firstName = $_SESSION['user']->getFirstName();
    $lastName = $_SESSION['user']->getLastName();
    $email = $_SESSION['user']->getEmail();
    $phone = $_SESSION['user']->getPhoneNumber();
    $username = $_SESSION['user']->getUsername();
}
if (empty($firstName) || empty($lastName) || empty($email) || empty($phone) || empty($username)) {
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
                <h2>Compte - Informations</h2>
            </div>
            <div class="main-body p-4">
                <form action="/account/update" method="post">
                    <div class="container">
                        <p>Modifiez vos informations personnelles ici, appuyez sur “Sauvegarder” quand vous avez
                            terminé.</p>
                        <?php include __DIR__ . '/../Components/sessionMessages.php' ?>
                        <div class="row mb-3">
                            <div class="col-12 col-md-4">
                                <label for="firstName" class="col-form-label font-weight-bold">Prénom:</label>
                            </div>
                            <div class="col-12 col-md-6">
                                <input type="text" name="firstName" id="firstName" value="<?php echo $firstName ?>"
                                       class="form-control" required readonly>
                            </div>
                            <div class="col-12 col-md-2">
                                <button class="btn btn-lg btn-outline-info" type="button"
                                        onclick="toggleEditable('firstName')">Éditer
                                </button>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 col-md-4">
                                <label for="lastName" class="col-form-label font-weight-bold">Nom :</label>
                            </div>
                            <div class="col-12 col-md-6">
                                <input type="text" name="lastName" id="lastName" value="<?php echo $lastName ?>"
                                       class="form-control" required readonly>
                            </div>
                            <div class="col-12 col-md-2">
                                <button class="btn btn-lg btn-outline-info" type="button"
                                        onclick="toggleEditable('lastName')">Éditer
                                </button>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 col-md-4">
                                <label for="username" class="col-form-label font-weight-bold">Utilisateur :</label>
                            </div>
                            <div class="col-12 col-md-6">
                                <input type="text" name="username" id="username" value="<?php echo $username ?>"
                                       class="form-control" required readonly>
                            </div>
                            <div class="col-12 col-md-2">
                                <button class="btn btn-lg btn-outline-info" type="button"
                                        onclick="toggleEditable('username')">Éditer
                                </button>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 col-md-4">
                                <label for="cellPhone" class="col-form-label font-weight-bold">Téléphone :</label>
                            </div>
                            <div class="col-12 col-md-6">
                                <input type="text" name="cellPhone" id="cellPhone" value="<?php echo $phone ?>"
                                       class="form-control" required readonly>
                            </div>
                            <div class="col-12 col-md-2">
                                <button class="btn btn-lg btn-outline-info" type="button"
                                        onclick="toggleEditable('cellPhone')">Éditer
                                </button>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 col-md-4">
                                <label for="email" class="col-form-label font-weight-bold">Email :</label>
                            </div>
                            <div class="col-12 col-md-6">
                                <input type="email" name="email" id="email" value="<?php echo $email ?>"
                                       class="form-control" required readonly>
                            </div>
                            <div class="col-12 col-md-2">
                                <button class="btn btn-lg btn-outline-info" type="button"
                                        onclick="toggleEditable('email')">Éditer
                                </button>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-outline-success col-12">Sauvegarder</button>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <a href="/dashboard" class="btn btn-outline-danger col-12">Annuler</a>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <a href="/account/delete" class="btn btn-outline-danger col-12">Supprimer le compte</a>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<script>
    function toggleEditable(fieldName) {
        const field = document.getElementById(fieldName);
        if (field) {
            field.readOnly = !field.readOnly;
            if (!field.readOnly) {
                field.focus();
            }
        }
    }
</script>
<script>
    // Get references to the select and input elements
    const bankSelect = document.getElementById('bank');
    const otherBankInput = document.getElementById('otherBank');

    // Add an event listener to the select element
    bankSelect.addEventListener('change', function () {
        if (bankSelect.value === 'other') {
            otherBankInput.removeAttribute('disabled');
        } else {
            otherBankInput.setAttribute('disabled', 'disabled');
            otherBankInput.value = ''; // Clear the input value
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js"></script>
</body>
</html>