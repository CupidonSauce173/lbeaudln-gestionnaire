<?php

use Lbeaudln\Gestionnaire\Models\Customer;
use Lbeaudln\Gestionnaire\Models\User;

$customers = [];

if (session_status() != PHP_SESSION_ACTIVE) session_start();

$firstName = '';
$lastName = '';
if ($_SESSION['user'] instanceof User) {
    $firstName = $_SESSION['user']->getFirstName();
    $lastName = $_SESSION['user']->getLastName();
}

// check if Customer exists.
if (!isset($customer)) {
    $_SESSION['error'] = "Erreur lors de l'optention du formulaire de modification de client.";
    header('Location: /dashboard');
}

/** @var Customer $customer */
$id = $customer->getId();
$cfirstName = $customer->getFirstName();
$clastName = $customer->getLastName();
$homePhone = $customer->getHomePhone();
$cellPhone = $customer->getCellPhone();
$email = $customer->getEmail();
$homeAddress = $customer->getHomeAddress();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Gestionnaire L.Beaudin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
          integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <script src="../Components/SideBarComponent.js"></script>
    <!-- Custom stylesheets -->
    <link rel="stylesheet" type="text/css" href="/css/style.css">
</head>
<body>

<div class="container-fluid bg-light">
    <div class="row">
        <!-- Le menu sidebar -->
        <?php include __DIR__ . '/../../Components/sidebar.php' ?>

        <!-- Options du haut-->
        <div class="col-md-9 col-lg-10">
            <div class="top-bar d-flex justify-content-between align-items-center">
                <h2>Modification d'un client</h2>
            </div>
            <div class="main-body p-4">
                <h2>Modifier un client</h2>
                <?php include __DIR__ . '/../../Components/sessionMessages.php' ?>
                <form action="/customer/edit/form" method="post" class="w-100">
                    <input type="hidden" name="updateCustomer" id="updateCustomer" value="true">
                    <input type="hidden" name="customer_id" id="customer_id" value="<?= $customer->getId() ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <h4>Information Client</h4>
                                <p>Vous pouvez modifier un client ici.</p>
                                <table class="w-100 bg-light">
                                    <tbody>
                                    <tr>
                                        <td class="font-weight-bolder">Nom:</td>
                                        <td class="text-primary">
                                            <div class="input-group">
                                                <label for="LastName"></label>
                                                <input type="text" class="form-control" name="LastName" id="LastName"
                                                       value="<?= $clastName ?>" required readonly>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-info"
                                                            onclick="toggleEditable('LastName')">Éditer
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bolder">Prénom:</td>
                                        <td class="text-primary">
                                            <div class="input-group">
                                                <label for="FirstName"></label>
                                                <input type="text" class="form-control" name="FirstName" id="FirstName"
                                                       value="<?= $cfirstName ?>" required readonly>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-info"
                                                            onclick="toggleEditable('FirstName')">Éditer
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bolder">Téléphone maison:</td>
                                        <td class="text-primary">
                                            <div class="input-group">
                                                <label for="HomePhone"></label>
                                                <input type="tel" class="form-control" name="HomePhone" id="HomePhone"
                                                       value="<?= $homePhone ?>" required readonly>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-info"
                                                            onclick="toggleEditable('HomePhone')">Éditer
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bolder">Téléphone Cellulaire:</td>
                                        <td class="text-primary">
                                            <div class="input-group">
                                                <label for="CellPhone"></label>
                                                <input type="tel" class="form-control" name="CellPhone" id="CellPhone"
                                                       value="<?= $cellPhone ?>" required readonly>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-info"
                                                            onclick="toggleEditable('CellPhone')">Éditer
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bolder">Addresse Courriel:</td>
                                        <td class="text-primary">
                                            <div class="input-group">
                                                <label for="Email"></label>
                                                <input type="email" class="form-control" name="Email" id="Email"
                                                       value="<?= $email ?>" required readonly>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-info"
                                                            onclick="toggleEditable('Email')">Éditer
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bolder">Addresse d'Habitation:</td>
                                        <td class="text-primary">
                                            <div class="input-group">
                                                <label for="HomeAddress"></label>
                                                <input type="text" class="form-control" name="HomeAddress"
                                                       id="HomeAddress" value="<?= $homeAddress ?>" required readonly>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-info"
                                                            onclick="toggleEditable('HomeAddress')">Éditer
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Buttons -->
                    <div class="form-group">
                        <button type="submit" class="btn btn-info btn-lg">Modifier</button>
                        <a href="/dashboard" class="btn btn-danger btn-lg">Annuler</a>
                    </div>
                </form>
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
            </div>
        </div>
    </div>
</div>
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