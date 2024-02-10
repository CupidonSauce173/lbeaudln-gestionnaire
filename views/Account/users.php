<?php

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
                <h2>Liste des utilisateurs</h2>
                <p>Vous pouvez visualiser les utilisateurs qui peuvent rajouter, voir, modifier et supprimer des
                    clients. Vous ne pouvez pas supprimer votre propre compte.</p>
                <?php include __DIR__ . '/../Components/sessionMessages.php' ?>
                <div class="table-responsive">
                    <table id="customerTable" class="table display" style="width:100%">
                        <thead class="thead-light">
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Création</th>
                            <th>Option</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($_SESSION['users'] as $dbUser): ?>
                            <?php if ($dbUser instanceof User): ?>
                                <tr data-customer-id="<?= $dbUser->getId() ?>">
                                    <td>
                                        <button class="btn btn-group details-control"> <?= htmlspecialchars($dbUser->getFirstName()) ?></button>
                                    </td>
                                    <td><?= htmlspecialchars($dbUser->getLastName()) ?></td>
                                    <td><?= htmlspecialchars($dbUser->getRegisterDate()->format("Y/m/d")) ?></td>
                                    <td>
                                        <?php if ($dbUser->getId() != $_SESSION['user']->getId() && $dbUser->getRole() != 'master'): ?>
                                            <div class="btn-group">
                                                <form action="/account/users/delete" method="post">
                                                    <input type="hidden" id="user_id" name="user_id"
                                                           value="<?php echo $dbUser->getId() ?>">
                                                    <button type="submit" class="btn btn-danger">Supprimer</button>
                                                </form>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <a href="/account/users/add" class="btn btn-lg btn-outline-success col-12">Ajouter un
                        utilisateur</a>
                    <script>
                        $(document).ready(function () {
                            $('#customerTable').DataTable();
                        });
                    </script>
                    <script>
                        var mortgages = JSON.parse('<?= json_encode(array_map(function ($mortgage) {
                            return [
                                'type' => $mortgage->getType(),
                                'rate' => $mortgage->getRate(),
                                'amount' => $mortgage->getAmount(),
                                'deadline' => $mortgage->getDeadline()->format('Y-m-d')
                            ];
                        }, $_SESSION['mortgages'])) ?>');
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function formatDetails(customerId) {
        console.log('CustomerId:', customerId);
        if (!mortgages[customerId]) {
            console.log('No mortgages found for this customer.');
            return '<div class="text-center">Aucun prêt à afficher.</div>';
        }

        var html = '<table class="table table-sm">' +
            '<thead><tr><th>Type</th><th>Taux</th><th>Montant</th><th>Échéance</th></tr></thead><tbody>';

        mortgages[customerId].forEach(function (mortgage) {
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

    $(document).ready(function () {
        var table = $('#customerTable').DataTable();

        // Event listener for opening and closing details
        $('#customerTable tbody').on('click', 'button.details-control', function () {
            var tr = $(this).closest('tr');
            var row = table.row(tr);
            var customerId = tr.data('customer-id');

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


<script>
    // Function to toggle the visibility of the additional information row
    function toggleHiddenRow(event) {
        const customerNameCell = event.currentTarget;
        const hiddenRow = customerNameCell.querySelector('.hidden-row');
        hiddenRow.classList.toggle('show-row');
    }

    // Add a click event listener to each customer name cell
    const customerNameCells = document.querySelectorAll('.customer-cell-data');
    customerNameCells.forEach(cell => {
        cell.addEventListener('click', toggleHiddenRow);
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js"></script>
</body>
</html>