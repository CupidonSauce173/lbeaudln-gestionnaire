<?php

namespace Lbeaudln\Gestionnaire\Controllers;

use Lbeaudln\Gestionnaire\DAOs\MortgageDAO;

class MortgageController
{
    /**
     * Handles the creation of a new mortgage entry. This method supports both GET and POST requests.
     * On a GET request, it displays the mortgage creation form, requiring a 'customer_id' to be passed
     * via the query string. On a POST request, it processes the form submission and attempts to create
     * a new mortgage with the provided details in the database.
     *
     * Required $_GET values for GET request: 'customer_id' -> ID of the customer for whom the mortgage is being created.
     * Required $_POST values for POST request: 'customer_id', 'Amount', 'Rate', 'MortgageType', 'Deadline', 'Terms',
     * 'Bank', 'Structure', and optionally 'otherBank' if a different bank is specified.
     *
     * Redirects to the dashboard on success or failure, setting appropriate session messages.
     *
     * @return void
     */
    public function create(): void
    {
        if (session_status() != PHP_SESSION_ACTIVE) session_start();

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            if (!isset($_GET['customer_id'])) {
                $_SESSION['error'] = "Problème lors de l'affichage de la fiche de création de prêt.";
                header('Location: /dashboard');
                exit;
            }
            $customerId = $_GET['customer_id'];
            include __DIR__ . '/../../views/Mortgages/create.php';
            exit;
        } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate form data
            $customerId = $_POST['customer_id'];
            $amount = $_POST['Amount'];
            $rate = $_POST['Rate'];
            $mortgageType = $_POST['MortgageType'];
            $deadline = $_POST['Deadline'];
            $bank = $_POST['Bank'];
            $terms = $_POST['Terms'];
            $structure = $_POST['Structure'];

            if (isset($_POST['otherBank'])) {
                $bank = $_POST['otherBank'];
            }

            $result = MortgageDAO::getInstance()->create($customerId, $amount, $mortgageType, $deadline, $terms, $rate, $bank, $structure);
            if ($result) {
                $_SESSION['message'] = "Opération effectuée avec succès!";
            } else {
                $_SESSION['error'] = "Problème lors de la création du nouveau prêt.";
            }
            header('Location: /dashboard');
            exit;
        } else {
            http_response_code(401);
            echo json_encode(['unknown method' => 'unknown method requested']);
        }
    }

    /**
     * Handles the deletion of a mortgage entry. This method is intended to be called with a POST request,
     * where the 'mortgage_id' must be provided to specify which mortgage to delete.
     *
     * Required $_POST values: 'mortgage_id' -> ID of the mortgage to be deleted.
     *
     * Upon successful deletion, sets a success message in the session and redirects to the dashboard. If
     * the mortgage cannot be deleted (e.g., due to a missing or invalid 'mortgage_id'), sets an error message
     * in the session and redirects as well.
     *
     * @return void
     */
    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (session_status() != PHP_SESSION_ACTIVE) session_start();
            if (isset($_POST['mortgage_id'])) {
                $mortgageId = $_POST['mortgage_id'];
                $result = MortgageDAO::getInstance()->delete($mortgageId);
                if ($result) {
                    $_SESSION['message'] = "Opération executée avec succès!";
                } else {
                    $_SESSION['error'] = "Il y a eu un problème lors de la suppression du prêt.";
                }
            } else {
                $_SESSION['error'] = "Il y a eu un problème lors de l'obtention du ID du prêt.";
            }
            header('Location: /dashboard');
            exit;
        } else {
            http_response_code(401);
            echo json_encode(['unknown method' => 'unknown method requested']);
        }
    }
}