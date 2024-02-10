<?php

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
$mortgagesData = json_encode($_SESSION['mortgages']);
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
                <h2>Gestionnaire LBeaudln</h2>
            </div>
            <div class="row">
            </div>
            <div class="main-body p-4">
                <h2>Liste des clients actuels</h2>
                <?php include __DIR__ . '/../Components/sessionMessages.php' ?>
                <div class="table-responsive">
                    <p class="font-weight-bold">Note: L'échéance et le montant font partie du prêt le plus proche à
                        échéance.</p>
                    <table id="customerTable" class="table display" style="width:100%">
                        <thead class="thead-light">
                        <tr>
                            <th>Nom</th>
                            <th># Téléphone</th>
                            <th>Échéance</th>
                            <th>Montant</th>
                            <th>Menu</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($_SESSION['customers'] as $customer): ?>
                            <?php if ($customer instanceof Customer): ?>
                                <?php
                                $mortgages = $_SESSION['mortgages'][$customer->getId()] ?? [];
                                usort($mortgages, function ($a, $b) {
                                    return $a->getDeadline() <=> $b->getDeadline();
                                });
                                $firstMortgage = !empty($mortgages) ? $mortgages[0] : null;
                                $deadlineDate = $firstMortgage ? $firstMortgage->getDeadline() : null;
                                $today = new DateTime();
                                $oneMonthLater = new DateTime('+1 month');
                                $isWithinOneMonth = $deadlineDate && $deadlineDate >= $today && $deadlineDate <= $oneMonthLater;
                                ?>
                                <tr data-customer-id="<?= $customer->getId() ?>">
                                    <td>
                                        <button class="btn btn-group details-control"><?= htmlspecialchars($customer->getLastName()) . ' ' . htmlspecialchars($customer->getFirstName()) ?></button>
                                    </td>
                                    <td><?= htmlspecialchars($customer->getCellPhone()) ?></td>
                                    <td <?= $isWithinOneMonth ? 'style="background-color: #ffcccc;"' : '' ?>><?= $firstMortgage ? $firstMortgage->getDeadline()->format('Y-m-d') : '-' ?></td>
                                    <td><?= $firstMortgage ? '$' . number_format($firstMortgage->getAmount(), 2) : '-' ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-danger dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa-solid fa-bars"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <form action="/customer/edit/form" method="post">
                                                    <input type="hidden" id="customer_id" name="customer_id"
                                                           value="<?php echo $customer->getId() ?>">
                                                    <button type="submit" class="dropdown-item">Modifier</button>
                                                </form>
                                                <form action="/customer/view" method="post">
                                                    <input type="hidden" id="customer_id" name="customer_id"
                                                           value="<?php echo $customer->getId() ?>">
                                                    <button type="submit" class="dropdown-item">Voir</button>
                                                </form>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-info" href="#">Archiver</a>
                                                <form action="/customer/delete" method="post">
                                                    <input type="hidden" id="customer_id" name="customer_id"
                                                           value="<?php echo $customer->getId() ?>">
                                                    <button type="submit" class="dropdown-item text-danger">Supprimer
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <script>
                    $(document).ready(function () {
                        const table = $('#customerTable').DataTable({
                            "order": [[2, "asc"]]
                        });

                        const mortgagesData = JSON.parse('<?php echo json_encode($_SESSION['mortgages']); ?>');

                        function formatDetails(customerId) {
                            const customerMortgages = mortgagesData[customerId];

                            if (!customerMortgages || customerMortgages.length === 0) {
                                return '<div class="text-center">Aucun prêt à afficher.</div>';
                            }

                            let html = '<table class="table table-sm">' +
                                '<thead><tr><th>Type</th><th>Taux</th><th>Montant</th><th>Échéance</th></tr></thead><tbody>';

                            customerMortgages.forEach(function (mortgage) {
                                html += '<tr>' +
                                    '<td>' + mortgage.type + '</td>' +
                                    '<td>' + mortgage.rate + '</td>' +
                                    '<td>' + mortgage.amount + '</td>' +
                                    '<td>' + mortgage.deadline + '</td>' +
                                    '</tr>';
                            });

                            html += '</tbody></table>';
                            return html;
                        }

                        $('#customerTable tbody').on('click', 'button.details-control', function () {
                            const tr = $(this).closest('tr');
                            const row = table.row(tr);
                            const customerId = tr.data('customer-id');

                            if (row.child.isShown()) {
                                row.child.hide();
                                tr.removeClass('shown');
                            } else {
                                row.child(formatDetails(customerId)).show();
                                tr.addClass('shown');
                            }
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js"></script>
</body>
</html>