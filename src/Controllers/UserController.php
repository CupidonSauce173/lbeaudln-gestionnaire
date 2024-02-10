<?php

namespace Lbeaudln\Gestionnaire\Controllers;

use DateTime;
use JetBrains\PhpStorm\NoReturn;
use Lbeaudln\Gestionnaire\DAOs\CommentDAO;
use Lbeaudln\Gestionnaire\DAOs\CustomerDAO;
use Lbeaudln\Gestionnaire\DAOs\MortgageDAO;
use Lbeaudln\Gestionnaire\DAOs\UserDAO;
use Lbeaudln\Gestionnaire\Models\User;

class UserController
{

    /**
     * Handles the user account update request. This method processes form submissions
     * for updating user details. It validates the input and updates the user's information
     * in the database if valid. Redirects to the account information page upon completion.
     *
     * Expected $_POST values: 'firstName', 'lastName', 'username', 'cellPhone', 'email'
     *
     * On successful update, sets a success message in the session. On failure, sets an error
     * message and redirects back to the account information page.
     *
     * @return void
     */
    #[NoReturn] public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (session_status() != PHP_SESSION_ACTIVE) {
                session_start();
            }

            $filteredData = [];
            $filteredData['firstName'] = filter_input(INPUT_POST, 'firstName');
            $filteredData['lastName'] = filter_input(INPUT_POST, 'lastName');
            $filteredData['username'] = filter_input(INPUT_POST, 'username');
            $filteredData['cellPhone'] = filter_input(INPUT_POST, 'cellPhone');
            $filteredData['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

            if (empty($filteredData['firstName']) || empty($filteredData['lastName']) || empty($filteredData['username']) || empty($filteredData['cellPhone']) || empty($filteredData['email'])) {
                $_SESSION['error'] = "Problème lors de l'execution de la demande de mise à jour du compte utilisateur.";
                header('Location: /account/information');
                exit;
            }

            if (!filter_var($filteredData['email'], FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = "Format d'email invalide.";
                header('Location: /account/information');
                exit;
            }

            if ($_SESSION['user'] instanceof User) {
                $_SESSION['user']->setFirstName($filteredData['firstName']);
                $_SESSION['user']->setLastName($filteredData['lastName']);
                $_SESSION['user']->setUsername($filteredData['username']);
                $_SESSION['user']->setPhoneNumber($filteredData['cellPhone']);
                $_SESSION['user']->setEmail($filteredData['email']);
                $user = $_SESSION['user'];
                $result = UserDAO::getInstance()->update($user->getId(), $user->getUsername(), $user->getEmail(),
                    $user->getFirstName(), $user->getLastName(), $user->getPhoneNumber());
                if ($result) {
                    $_SESSION['message'] = 'Mise à jour de votre profil utilisateur effectué avec succès!';
                } else {
                    $_SESSION['error'] = "Problème lors de l'execution de la demande de mise à jour du compte utilisateur.";
                }
            } else {
                $_SESSION['error'] = "Problème lors de l'execution de la demande de mise à jour du compte utilisateur.";
            }
            header('Location: /account/information');
            exit;
        } else {
            http_response_code(401);
            echo json_encode(['unknown method' => 'unknown method requested']);
        }
        exit;
    }

    /**
     * Displays the list of users. This method is intended for GET requests and will
     * retrieve all users from the database and include the users listing view.
     *
     * No parameters are required.
     *
     * @return void
     */
    #[NoReturn] public function users(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            if (session_status() != PHP_SESSION_ACTIVE) session_start();
            $_SESSION['users'] = UserDAO::getInstance()->getAll();
            include __DIR__ . '/../../views/Account/users.php';
            exit;
        } else {
            http_response_code(401);
            echo json_encode(['unknown method' => 'unknown method requested']);
        }
    }

    /**
     * Handles the request to delete a user. This method processes POST requests where
     * a 'user_id' must be provided. It attempts to delete the specified user from the database.
     *
     * Required $_POST value: 'user_id' -> ID of the user to be deleted.
     *
     * Sets a success or error message in the session and redirects to the users list page.
     *
     * @return void
     */
    #[NoReturn] public function removeUser(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_POST['user_id'] ?? null;

            if ($userId === null) {
                $_SESSION['error'] = "ID d'utilisateur manquant.";
                header('Location: /dashboard');
                exit;
            }

            $user = UserDAO::getInstance()->getById($userId);
            if ($user->getRole() == 'master') {
                $success = false;
            } else {
                $success = UserDAO::getInstance()->delete($userId);
            }

            if ($success) {
                $_SESSION['message'] = "Utilisateur supprimé avec succès.";
            } else {
                $_SESSION['error'] = "Erreur lors de la suppression de l'utilisateur.";
            }

            header('Location: /account/users');
            exit;
        } else {
            http_response_code(401);
            echo json_encode(['unknown method' => 'unknown method requested']);
        }
    }

    /**
     * Handles the creation of a new user. This method supports both GET and POST requests.
     * On a GET request, it displays the user creation form. On a POST request, it processes
     * the form submission, validates input, and attempts to create a new user in the database.
     *
     * Expected $_POST values: 'username', 'email', 'firstName', 'lastName', 'cellPhone',
     * 'password', 'repeatedPassword'
     *
     * On successful creation, sets a success message and redirects to the users list.
     * On failure, sets an error message and redirects back to the creation form.
     *
     * @return void
     */
    #[NoReturn] public function addUser(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            include __DIR__ . '/../../views/Account/create.php';
        } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate input data
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $firstName = $_POST['firstName'] ?? '';
            $lastName = $_POST['lastName'] ?? '';
            $phoneNumber = $_POST['cellPhone'] ?? '';
            $password = $_POST['password'] ?? '';
            $repeatedPassword = $_POST['repeatedPassword'] ?? '';

            $errors = [];

            if (empty($username) || empty($email) || empty($firstName) || empty($lastName) || empty($phoneNumber) || empty($password) || empty($repeatedPassword)) {
                $errors[] = "Veuillez remplir tous les champs.";
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Format d'email invalide.";
            }

            if ($password !== $repeatedPassword) {
                $errors[] = "Les mots de passe ne correspondent pas.";
            }

            if (!empty($errors)) {
                $_SESSION['error'] = implode("<br>", $errors);
                header('Location: /account/users/create');
                exit;
            }

            $role = 'default';
            $registerDate = new DateTime();
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $success = UserDAO::getInstance()->create($username, $email, $firstName, $lastName, $role, $phoneNumber, $registerDate, $hashedPassword);

            if ($success) {
                $_SESSION['message'] = "Utilisateur créé avec succès.";
                header('Location: /account/users');
            } else {
                $_SESSION['error'] = "Erreur lors de la création de l'utilisateur.";
                header('Location: /account/users/create');
            }
            exit;
        } else {
            http_response_code(401);
            echo json_encode(['unknown method' => 'unknown method requested']);
        }
    }

    /**
     * Handles password change requests. This method processes POST requests for updating
     * the current user's password. It validates the current and new passwords and updates
     * the password in the database if valid.
     *
     * Expected $_POST values: 'currentPassword', 'newPassword', 'repeatedPassword'
     *
     * Sets a success or error message in the session and redirects to the security settings page.
     *
     * @return void
     */
    #[NoReturn] public function security(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            include __DIR__ . '/../../views/Account/security.php';
            exit;
        } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $currentPassword = $_POST['currentPassword'];
            $newPassword = $_POST['newPassword'];
            $repeatedPassword = $_POST['repeatedPassword'];

            if (session_status() != PHP_SESSION_ACTIVE) session_start();
            if (empty($currentPassword) || empty($newPassword) || empty($repeatedPassword)) {
                $_SESSION['error'] = "Veuillez remplir tous les champs.";
                header('Location: /account/security');
                exit;
            }

            if ($newPassword !== $repeatedPassword) {
                $_SESSION['error'] = "Les nouveaux mots de passe ne correspondent pas.";
                header('Location: /account/security');
                exit;
            }

            $user = $_SESSION['user'];

            if (!password_verify($currentPassword, $user->getPassword())) {
                $_SESSION['error'] = "Le mot de passe actuel est incorrect.";
                header('Location: /account/security');
                exit;
            }

            // Only hash the new password if it's different from the current password
            if ($newPassword !== $currentPassword) {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                if (empty($hashedPassword)) {
                    $_SESSION['error'] = "Informations non valides.";
                    header('Location: /account/security');
                    exit;
                }
                $result = UserDAO::getInstance()->updatePassword($user->getId(), $hashedPassword);
                if ($result) {
                    $_SESSION['message'] = "Le mot de passe a été mis à jour avec succès.";
                } else {
                    $_SESSION['error'] = "Erreur lors de la mise à jour du mot de passe.";
                }
            } else {
                $_SESSION['error'] = "Le nouveau mot de passe doit être différent du mot de passe actuel.";
            }
            header('Location: /account/security');
            exit;
        } else {
            http_response_code(401);
            echo json_encode(['unknown method' => 'unknown method requested']);
        }
        exit;
    }

    /**
     * Handles user login requests. This method supports both GET and POST requests.
     * On a GET request, it displays the login form. On a POST request, it processes the
     * login form, authenticates the user, and redirects to the dashboard on success.
     *
     * Expected $_POST values: 'email', 'password'
     *
     * Sets an error message in the session and re-displays the login form on authentication failure.
     *
     * @return void
     */
    #[NoReturn] public function login(): void
    {
        if (session_status() != PHP_SESSION_ACTIVE) session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // verify form values
            if (!isset($_POST['email']) || !isset($_POST['password'])) {
                $_SESSION['error'] = "Informations non valides.";
                include __DIR__ . '/../../views/User/login.php';
                exit;
            }

            // filtering
            $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            $password = $_POST['password'];
            // processing
            if ($email === false) {
                $_SESSION['error'] = "Informations non valides.";
                include __DIR__ . '/../../views/User/login.php';
                exit;
            }

            $user = UserDAO::getInstance()->getByEmail($email);

            if ($user) { // User found
                if (password_verify($password, $user->getPassword())) {
                    // Password is correct, user is authenticated
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $user->getId();
                    $_SESSION['user'] = $user;
                    // Executing dashboard action.
                    header('Location: /dashboard');
                } else {
                    $_SESSION['error'] = "Informations non valides.";
                    include __DIR__ . '/../../views/User/login.php';
                }
            } else { // User not found
                $_SESSION['error'] = "Informations non valides.";
                include __DIR__ . '/../../views/User/login.php';
            }
            exit;
        } else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            include __DIR__ . '/../../views/User/login.php';
            exit;
        } else {
            http_response_code(401);
            echo json_encode(['unknown method' => 'unknown method requested']);
        }
    }

    /**
     * Displays the user's account information page. This method is intended for GET requests
     * and will include the account information view.
     *
     * No parameters are required.
     *
     * @return void
     */
    #[NoReturn] public function information(): void
    {
        include __DIR__ . '/../../views/Account/information.php';
        exit;
    }

    /**
     * Handles user logout. This method clears the user session and redirects to the login page.
     *
     * No parameters are required.
     *
     * @return void
     */
    #[NoReturn] public function logout(): void
    {
        session_start();
        // removal of data
        if (isset($_SESSION['user_id'])) unset($_SESSION['user_id']);
        if (isset($_SESSION['user'])) unset($_SESSION['user']);
        session_destroy();
        header('Location: /login');
        exit;
    }

    /**
     * Displays the user dashboard. This method checks for user authentication and redirects
     * to the login page if the user is not authenticated. Otherwise, it regenerates the session
     * ID for security and includes the dashboard view.
     *
     * No parameters are required.
     *
     * @return void
     */
    #[NoReturn] public function dashboard(): void
    {
        if (session_status() != PHP_SESSION_ACTIVE || !isset($_SESSION['user_id'])) {
            // User is not authenticated, redirecting to login page.
            header('Location: /login');
            exit;
        }

        session_regenerate_id(true);

        // Dashboard data creation
        self::fetchAllData(true);

        include __DIR__ . '/../../views/User/dashboard.php';
        exit;
    }

    /**
     * Checks if data has been fetched and if update set to true, rebuild all the data.
     * @param bool $update
     * @return void
     */
    public static function fetchAllData(bool $update = false): void
    {
        if (session_status() != PHP_SESSION_ACTIVE) session_start();
        if (!isset($_SESSION['customers']) || $update) {
            $_SESSION['users'] = UserDAO::getInstance()->getAll();
            $_SESSION['customers'] = CustomerDAO::getInstance()->getAll();
            $_SESSION['mortgages'] = MortgageDAO::getInstance()->getAll();
            $_SESSION['comments'] = CommentDAO::getInstance()->getAll();
        }
    }
}