<?php

namespace Lbeaudln\Gestionnaire\Controllers;

use JetBrains\PhpStorm\NoReturn;
use Lbeaudln\Gestionnaire\DAOs\CommentDAO;
use Lbeaudln\Gestionnaire\DAOs\CustomerDAO;
use Lbeaudln\Gestionnaire\DAOs\MortgageDAO;
use Lbeaudln\Gestionnaire\Models\Customer;

class CustomerController
{
    /**
     * Controller method - view | POST
     *
     * Creates the necessary data to display all the information
     * related to a customer using the MortgageDAO::getByCustomerId()
     * the CommentDAO::getByCustomerId() and the CustomerDAO::getById()
     * and then includes the Customer/view.php file.
     *
     * Required $_POST values: 'customer_id' -> The customer.
     *
     * @return void
     */
    #[NoReturn] public function view(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['customer_id'])) {
            if (session_status() != PHP_SESSION_ACTIVE) session_start();

            $customerId = $_POST['customer_id'];
            if (!$customerId) {
                $_SESSION['error'] = "Erreur lors de l'obtention des données client.";
                header('Location: /dashboard');
                exit;
            }
            $customer = CustomerDAO::getInstance()->getById($customerId);
            if (!$customer) {
                $_SESSION['error'] = "Le client n'existe pas.";
                header('Location: /dashboard');
                exit;
            }

            $mortgages = MortgageDAO::getInstance()->getByCustomerId($customerId);
            $comments = CommentDAO::getInstance()->getByCustomerId($customerId);

            /** @var Customer $customer */
            /** @var array $mortgages */
            /** @var array $comments */
            header("HTTP/1.1 303 See Other");
            include __DIR__ . '/../../views/Customer/view.php';
            exit;
        } else {
            http_response_code(401);
            echo json_encode(['unknown method' => 'unknown method requested']);
        }
    }

    /**
     * Controller method - add | GET/POST
     *
     * Will register a new customer in the database using
     * the CustomerDAO::create() method and then redirects
     * to the dashboard using location: /dashboard.
     *
     * Required $_POST values: 'LastName', 'FirstName', 'HomePhone', 'CellPhone'
     * 'Email', 'HomeAddress', 'Amount', 'Rate', 'MortgageType', 'Deadline', 'Terms',
     * 'Terms', 'Bank', 'Structure' -> Data related to customer and first mortgage.
     * Required $_GET values: none
     *
     * @return void
     */
    #[NoReturn] public function add(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            include __DIR__ . '/../../views/Customer/create.php';
            exit;
        } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Initialize and filter variables from the POST data
            $filtered = $this->filter_inputs($_POST);
            $lastName = $filtered['lastName'];
            $firstName = $filtered['firstName'];
            $homePhone = $filtered['homePhone'];
            $cellPhone = $filtered['cellPhone'];
            $email = $filtered['email'];
            $homeAddress = $filtered['homeAddress'];
            $amount = filter_var($_POST['Amount'] ?? '', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $rate = filter_var($_POST['Rate'] ?? '', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $mortgageType = filter_var($_POST['MortgageType'] ?? '');
            $deadline = filter_var($_POST['Deadline'] ?? '');
            $terms = filter_var($_POST['Terms'] ?? '');
            $bank = filter_var($_POST['Bank'] ?? '');
            $structure = filter_var($_POST['Structure']);


            // Perform further validation if necessary (e.g., check email format)

            if (session_start() != PHP_SESSION_ACTIVE) session_start();
            // 1. Create customer with DAO
            if (CustomerDAO::getInstance()->create(
                $firstName,
                $lastName,
                $homePhone,
                $cellPhone,
                $email,
                $homeAddress
            )) {
                // success customer
                $_SESSION['message'] = 'Le client a été rajouté avec succès!';
            } else {
                // fail customer
                $_SESSION['error'] = "Il y a eu un problème lors de l'ajout du client, il existe déjà ?";
            }

            UserController::fetchAllData(true);

            // Get customer ID.
            foreach ($_SESSION['customers'] as $customer) {
                if ($customer instanceof Customer) {
                    if ($customer->getFirstName() == $firstName && $customer->getLastName() == $lastName &&
                        $customer->getCellPhone() == $cellPhone && $customer->getHomePhone() == $homePhone) {
                        // 2. Create mortgage with DAO
                        if (MortgageDAO::getInstance()->create(
                            $customer->getId(),
                            $amount,
                            $mortgageType,
                            $deadline,
                            $terms,
                            $rate,
                            $bank,
                            $structure
                        )) {
                            $_SESSION['message'] .= "\nLe prêt a été ajouté avec succès!";
                        } else {
                            $_SESSION['error'] = "Il y a eu un problème lors de l'ajout du prêt.";
                        }
                    }
                }
            }
            // Redirect to the dashboard
            header('Location: /dashboard');
            exit;
        } else {
            http_response_code(401);
            echo json_encode(['unknown method' => 'unknown method requested']);
        }
    }

    /**
     * Controller method - edit | GET
     *
     * Displays the form for editing a customer. This method handles the initial display
     * of the form where a user can input new values to update a customer's information.
     *
     * Required $_GET values: none
     *
     * @return void
     */
    #[NoReturn] public function edit(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            include __DIR__ . '/../../views/Customer/edit.php';
            exit;
        } else {
            http_response_code(401);
            echo json_encode(['unknown method' => 'unknown method requested']);
        }
    }

    /**
     * Controller method - editForm | POST
     *
     * Handles the submission of the edit form. If the 'customer_id' is provided and
     * 'updateCustomer' is not set, it retrieves the customer's data for pre-filling the edit form.
     * If 'updateCustomer' is set, it processes the form submission and updates the customer's
     * information in the database based on the provided 'customer_id' and form data.
     *
     * Required $_POST values for initial form: 'customer_id' -> ID of the customer to edit.
     * Required $_POST values for updating customer: 'customer_id', 'LastName', 'FirstName',
     * 'HomePhone', 'CellPhone', 'Email', 'HomeAddress' -> Fields required to update the customer.
     *
     * @return void
     */
    #[NoReturn] public function editForm(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['customer_id']) && !isset($_POST['updateCustomer'])) {
            $id = filter_input(INPUT_POST, 'customer_id', FILTER_VALIDATE_INT);
            // get customer data before including the view.
            /** @var Customer $data */
            $customer = CustomerDAO::getInstance()->getById($id);
            // include the view.
            include __DIR__ . '/../../views/Customer/edit/form.php';
            exit;
        } else if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateCustomer'])) {
            if (session_status() != PHP_SESSION_ACTIVE) session_start();
            if (!isset($_POST['customer_id'])) {
                $_SESSION['error'] = 'Erreur lors de la demande de formulaire de modification de client.';
                header('Location: /customer/edit');
                exit;
            }
            // Securing data from post.
            $id = filter_input(INPUT_POST, 'customer_id', FILTER_VALIDATE_INT);
            $filtered = $this->filter_inputs($_POST);
            $lastName = $filtered['lastName'];
            $firstName = $filtered['firstName'];
            $homePhone = $filtered['homePhone'];
            $cellPhone = $filtered['cellPhone'];
            $email = $filtered['email'];
            $homeAddress = $filtered['homeAddress'];

            if (empty($firstName) || empty($lastName) || empty($email) || empty($homeAddress)) {
                $_SESSION['error'] = "Tous les champs obligatoires doivent être remplis.";
                header('Location: /customer/edit');
                exit;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = "Le format de l'adresse e-mail est invalide.";
                header('Location: /customer/edit');
                exit;
            }

            // process modifications
            $result = CustomerDAO::getInstance()->update(
                $id,
                $lastName,
                $firstName,
                $homePhone,
                $cellPhone,
                $email,
                $homeAddress
            );
            if ($result) {
                $_SESSION['message'] = "L'utilisateur a été modifié avec succès.";
            } else {
                $_SESSION['error'] = "Il y a eu un problème lors de la modification de l'utilisateur: " . $id;
            }
            // success, updating data in in-memory + redirecting to view.
            /** @var Customer $data */
            $customer = CustomerDAO::getInstance()->getById($id);
            // include the view.
            include __DIR__ . '/../../views/Customer/edit/form.php';
            exit;
        } else {
            http_response_code(401);
            echo json_encode(['unknown method' => 'unknown method requested']);
        }
    }

    /**
     * Controller method - delete | GET/POST
     *
     * Displays the confirmation form for deleting a customer on GET request or
     * processes the deletion of a customer from the database on POST request.
     * The actual deletion is contingent on the 'customer_id' being provided via POST.
     *
     * Required $_GET values: none for GET request.
     * Required $_POST values: 'customer_id' -> ID of the customer to be deleted.
     *
     * @return void
     */
    #[NoReturn] public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            include __DIR__ . '/../../views/Customer/delete.php';
            exit;
        } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (session_status() != PHP_SESSION_ACTIVE) session_start();
            if (isset($_POST['customer_id'])) {
                $customerId = filter_input(INPUT_POST, 'customer_id', FILTER_VALIDATE_INT);
                // deleting
                if (CustomerDAO::getInstance()->delete($customerId)) {
                    $_SESSION['message'] = "Client supprimé avec succès.";
                } else {
                    $_SESSION['error'] = "Erreur lors de la suppression du client.";
                }
            } else $_SESSION['error'] = "Erreur lors de la suppression du client.";
            // redirection
            header('Location: /dashboard');
            exit;
        } else {
            http_response_code(401);
            echo json_encode(['unknown method' => 'unknown method requested']);
        }
    }

    private function filter_inputs(array $post): array
    {
        $lastName = filter_var($post['LastName'] ?? '');
        $firstName = filter_var($post['FirstName'] ?? '');
        $homePhone = filter_var($post['HomePhone'] ?? '');
        $cellPhone = filter_var($post['CellPhone'] ?? '');
        $email = filter_var($post['Email'] ?? '', FILTER_SANITIZE_EMAIL);
        $homeAddress = filter_var($post['HomeAddress'] ?? '');
        return [
            'lastName' => $lastName,
            'firstName' =>$firstName,
            'homePhone' => $homePhone,
            'cellPhone' => $cellPhone,
            'email' => $email,
            'homeAddress' => $homeAddress
        ];
    }
}