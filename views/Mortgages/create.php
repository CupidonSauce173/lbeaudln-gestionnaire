<?php

use Lbeaudln\Gestionnaire\Models\User;

$customers = [];

if (session_status() != PHP_SESSION_ACTIVE) session_start();

$firstName = '';
$lastName = '';
if ($_SESSION['user'] instanceof User) {
    $firstName = $_SESSION['user']->getFirstName();
    $lastName = $_SESSION['user']->getLastName();
}

if (!isset($customerId)) {
    $_SESSION['error'] = "Problème lors de l'affichage de la fiche de création de prêt.";
    header('Location: /dashboard');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
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
                <h2>Prêts - Création</h2>
            </div>
            <div class="main-body p-4">
                <h2>Fiche nouveau prêt</h2>
                <?php include __DIR__ . '/../Components/sessionMessages.php' ?>
                <!-- The rest of the page here -->
                <div class="col-md-12">
                    <div class="form-group">
                        <p>Création d'un nouveau prêt pour le client: </p>
                        <form action="/mortgages/create" method="post">
                            <input type="hidden" name="customer_id" id="customer_id" value="<?php echo $customerId ?>">
                            <table>
                                <tbody>
                                <tr>
                                    <td class="font-weight-bolder">Montant ($CAD):</td>
                                    <td class="text-primary">
                                        <label for="Amount"></label>
                                        <input type="text" class="form-control mt-2 ml-2" name="Amount" id="Amount"
                                               required>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bolder">Taux (%):</td>
                                    <td class="text-primary">
                                        <label for="Rate"></label>
                                        <input type="text" class="form-control mt-2 ml-2" name="Rate" id="Rate"
                                               required>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bolder">Taux Fixe/Variable:</td>
                                    <td class="text-primary">
                                        <label for="MortgageType"></label>
                                        <select class="form-control mt-2 ml-2" name="MortgageType" id="MortgageType"
                                                required>
                                            <option value="Fixe">Fixe</option>
                                            <option value="Variable">Variable</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bolder">Date Échéance:</td>
                                    <td class="text-primary">
                                        <label for="Deadline"></label>
                                        <input type="date" class="form-control mt-2 ml-2" name="Deadline" id="Deadline"
                                               required>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bolder">Prêteur:</td>
                                    <td class="text-primary">
                                        <label for="Bank"></label>
                                        <select class="form-control mt-2 ml-2" name="Bank" id="Bank" required>
                                            <option value="rbc">RBC (Banque Royale du Canada)</option>
                                            <option value="td">TD (Toronto-Dominion)</option>
                                            <option value="cibc">CIBC (Canadienne Impériale de Commerce)</option>
                                            <option value="bmo">BMO (Banque de Montréal)</option>
                                            <option value="bns">BNS (Banque Scotia)</option>
                                            <option value="bnc">BNC (Banque Nationale du Canada)</option>
                                            <option value="blc">BLC (Banque Laurentienne du Canada)</option>
                                            <option value="other">Autre</option>
                                        </select>
                                        <label for="otherBank"></label>
                                        <input type="text" class="form-control mt-2 ml-2" name="otherBank"
                                               id="otherBank" placeholder="Autre institue" disabled required>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bolder">Structure</td>
                                    <td class="text-primary">
                                        <label for="Structure"></label>
                                        <select class="form-control mt-2 ml-2" name="Structure" id="Structure"
                                                required>
                                            <option value="Prêt hypothécaire">Prêt hypothécaire</option>
                                            <option value="Marge intégré">Marge intégré</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bolder">Termes:</td>
                                    <td class="text-primary">
                                        <label for="Terms"></label>
                                        <textarea class="form-control mt-2 ml-2" name="Terms" id="Terms" rows="10"
                                                  required></textarea>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-lg btn-outline-success btn-group col-12 col-md-2">
                                    Créer
                                </button>
                                <a href="/dashboard"
                                   class="btn btn-lg btn-outline-danger btn-group col-12 col-md-2 mt-2 mt-md-0">Retour</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Get references to the select and input elements
    const bankSelect = document.getElementById('Bank');
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
