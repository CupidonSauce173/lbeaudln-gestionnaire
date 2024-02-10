<?php

use Lbeaudln\Gestionnaire\Models\Customer;
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
                <h2>Clients - Modifier</h2>
            </div>
            <div class="row">
            </div>
            <div class="main-body p-4">
                <h2>Liste des clients actuels</h2>
                <?php include __DIR__ . '/../Components/sessionMessages.php' ?>
                <div class="table-responsive">
                    <table id="customerTable" class="display" style="width:100%">
                        <thead class="thead-light">
                        <tr>
                            <th>Nom</th>
                            <th>Modifier</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($_SESSION['customers'] as $customer): ?>
                            <?php if ($customer instanceof Customer): ?>
                                <tr>
                                    <td class="customer-cell-data"><?= htmlspecialchars($customer->getLastName()) . ' ' . htmlspecialchars($customer->getFirstName()) ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <form action="/customer/edit/form" method="POST">
                                                <input type="hidden" name="customer_id" id="customer_id"
                                                       value="<?= $customer->getId() ?>">
                                                <button type="submit" class="btn btn-info">
                                                    Modifier
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <script>
                        $(document).ready(function () {
                            $('#customerTable').DataTable();
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js"></script>
</body>
</html>