<?php

use Lbeaudln\Gestionnaire\Models\Comment;
use Lbeaudln\Gestionnaire\Models\User;

$customers = [];

if (session_status() != PHP_SESSION_ACTIVE) session_start();

$firstName = '';
$lastName = '';
if ($_SESSION['user'] instanceof User) {
    $firstName = $_SESSION['user']->getFirstName();
    $lastName = $_SESSION['user']->getLastName();
}

if (!isset($comments)) {
    $_SESSION['error'] = "Problème lors de l'affichage des commentaires.";
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
                <h2>Compte - Commentaires</h2>
            </div>
            <div class="main-body p-4">
                <h2>Chercher un commentaire</h2>
                <?php include __DIR__ . '/../Components/sessionMessages.php' ?>

                <!-- The rest of the page here -->
                <div class="table-responsive">
                    <table id="commentsTable" class="table display" style="width:100%">
                        <thead class="thead-light">
                        <tr>
                            <th>Client</th>
                            <th>Création</th>
                            <th>Commentaire</th>
                            <th>Options</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($comments as $comment): ?>
                            <?php if ($comment instanceof Comment): ?>
                                <tr data-customer-id="<?= $comment->getId() ?>">
                                    <td><?= htmlspecialchars($comment->getCustomer()->getFirstName() . ' ' . $comment->getCustomer()->getLastName()) ?></td>
                                    <td><?= htmlspecialchars($comment->getRegisterDate()->format('Y-m-d')) ?></td>
                                    <td><?= htmlspecialchars($comment->getBody()) ?></td>
                                    <!-- Menu d'options -->
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-danger dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa-solid fa-bars"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <form action="/comments/delete" method="post">
                                                    <input type="hidden" id="comment_id" name="comment_id"
                                                           value="<?php echo $comment->getId() ?>">
                                                    <button type="submit" class="dropdown-item text-danger">Supprimer
                                                    </button>
                                                </form>
                                                <form action="/customer/view" method="post">
                                                    <input type="hidden" id="customer_id" name="customer_id"
                                                           value="<?php echo $comment->getCustomer()->getId() ?>">
                                                    <button type="submit" class="dropdown-item text-primary">Voir
                                                        client
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

                    <script>
                        $(document).ready(function () {
                            $('#commentsTable').DataTable();
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
