<?php

use Lbeaudln\Gestionnaire\Models\Comment;
use Lbeaudln\Gestionnaire\Models\Customer;
use Lbeaudln\Gestionnaire\Models\User;

if (session_status() != PHP_SESSION_ACTIVE) session_start();

$firstName = '';
$lastName = '';
if ($_SESSION['user'] instanceof User) {
    $firstName = $_SESSION['user']->getFirstName();
    $lastName = $_SESSION['user']->getLastName();
} else {
    header('Location: /login');
    exit;
}

/** @var Customer $customer */
/** @var array $mortgages */
/** @var array $comments */

if (!$customer instanceof Customer) {
    $_SESSION['error'] = "Problème lors de l'affichage du client.";
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
                <h2>Client</h2>
                <?php include __DIR__ . '/../Components/sessionMessages.php' ?>
            </div>
            <div class="main-body p-4">
                <h2>Information</h2>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <h4>Information Client</h4>
                            <table>
                                <tbody>
                                <tr>
                                    <td class="font-weight-bolder">Nom:</td>
                                    <td class="text-primary">
                                        <label for="LastName"></label>
                                        <input type="text" class="form-control mt-2 ml-2" name="LastName" id="LastName"
                                               value="<?php echo $customer->getLastName() ?>" disabled>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bolder">Prénom:</td>
                                    <td class="text-primary">
                                        <label for="FirstName"></label>
                                        <input type="text" class="form-control mt-2 ml-2" name="FirstName"
                                               id="FirstName"
                                               value="<?php echo $customer->getFirstName() ?>" disabled>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bolder">Téléphone maison:</td>
                                    <td class="text-primary">
                                        <label for="HomePhone"></label>
                                        <input type="tel" class="form-control mt-2 ml-2" name="HomePhone" id="HomePhone"
                                               value="<?php echo $customer->getHomePhone() ?>" disabled>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bolder">Téléphone Cellulaire:</td>
                                    <td class="text-primary">
                                        <label for="CellPhone"></label>
                                        <input type="tel" class="form-control mt-2 ml-2" name="CellPhone" id="CellPhone"
                                               value="<?php echo $customer->getCellPhone() ?>" disabled>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bolder">Addresse Courriel:</td>
                                    <td class="text-primary">
                                        <label for="Email"></label>
                                        <input type="email" class="form-control mt-2 ml-2" name="Email" id="Email"
                                               value="<?php echo $customer->getEmail() ?>" disabled>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bolder">Addresse d'Habitation:</td>
                                    <td class="text-primary">
                                        <label for="HomeAddress"></label>
                                        <input type="text" class="form-control mt-2 ml-2" name="HomeAddress"
                                               id="HomeAddress"
                                               value="<?php echo $customer->getHomeAddress() ?>" disabled>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <h4 class="form-group">Commentaires</h4>
                                        <div class="border rounded p-3 mt-3 form-group"
                                             style="max-height: 300px; overflow-y: auto;">
                                            <div id="comments-section">
                                                <?php foreach ($comments as $comment): ?>
                                                    <?php if ($comment instanceof Comment): ?>
                                                        <div class="comment">
                                                            <div class="row">
                                                                <div class="col word-wrap"><?php echo $comment->getBody(); ?></div>
                                                                <div class="col-auto">
                                                                    <small><?php echo $comment->getRegisterDate()->format('Y-m-d'); ?></small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endif ?>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <form action="/comments/add" method="post">
                                            <div class="input-group justify-content-center">
                                                <!-- Hidden parameters -->
                                                <input type="hidden" id="customerId" name="customerId"
                                                       value="<?php echo $customer->getId() ?>"/>
                                                <!-- User inputs -->
                                                <label for="body"></label><input type="text" class="form-control"
                                                                                 id="body" name="body">
                                                <button type="submit" class="btn btn-outline-info ml-2">Ajouter</button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <h4>Informations Prêts</h4>
                            <table id="mortgagesTable" class="table display" style="width:100%">
                                <thead>
                                <tr>
                                    <th>Montant ($CAD)</th>
                                    <th>Taux (%)</th>
                                    <th>Taux Fixe/Variable</th>
                                    <th>Date Échéance</th>
                                    <th>Prêteur</th>
                                    <th>Termes</th>
                                    <th>Struct.</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($mortgages as $mortgage): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($mortgage->getAmount()); ?></td>
                                        <td><?php echo htmlspecialchars($mortgage->getRate()); ?></td>
                                        <td><?php echo htmlspecialchars($mortgage->getType()); ?></td>
                                        <td><?php echo $mortgage->getDeadline()->format('Y-m-d'); ?></td>
                                        <td><?php echo htmlspecialchars($mortgage->getBank()); ?></td>
                                        <td><?php echo htmlspecialchars($mortgage->getTerms()); ?></td>
                                        <td><?php echo htmlspecialchars($mortgage->getStructure()); ?>)</td>
                                        <td>
                                            <form action="/mortgages/delete" method="post">
                                                <input name="mortgage_id" id="mortgage_id" type="hidden" value="<?php echo $mortgage->getId() ?>">
                                                <button class="btn btn-outline-danger">Supprimer</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                            <script>
                                $(document).ready(function () {
                                    $('#mortgagesTable').DataTable();
                                });
                            </script>
                            <form action="/mortgages/create" method="get">
                                <input type="hidden" id="customer_id" name="customer_id"
                                       value="<?php echo $customer->getId() ?>">
                                <button class="btn btn-outline-success btn-lg btn-group col-md-12 text-center">Nouveau
                                    Prêt
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
