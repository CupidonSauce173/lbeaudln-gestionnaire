<?php

namespace Lbeaudln\Gestionnaire\Controllers;

use JetBrains\PhpStorm\NoReturn;
use Lbeaudln\Gestionnaire\DAOs\CommentDAO;
use Lbeaudln\Gestionnaire\Models\User;

class CommentController
{
    /**
     * Controller method - add | POST
     *
     * Will register a new comment in the database using the CommentDAO and then
     * create a temporary CustomerController() to call the view() method to resend
     * the user directly to the customer's view with the newly created comment.
     *
     * Required $_POST values: 'customerId' -> target customer that receives the comment.
     *
     * @return void
     */
    #[NoReturn] public function add(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (session_status() != PHP_SESSION_ACTIVE) session_start();
            $customerId = $_POST['customerId'];

            $authorId = $_SESSION['user']->getId();

            $body = $_POST['body'];
            if ($_SESSION['user'] instanceof User) {
                $result = CommentDAO::getInstance()->create($customerId, $authorId, $body);
                if (!$result) {
                    $_SESSION['error'] = "Problème lors de l'insertion du commentaire.";
                } else {
                    $_SESSION['message'] = "Opération effectuée avec succès!";
                }
            } else {
                $_SESSION['error'] = "Problème lors de la création du commentaire.";
            }
            $_POST['customer_id'] = $customerId;
            $controller = new CustomerController();
            $controller->view();
        } else {
            http_response_code(401);
            echo json_encode(['unknown method' => 'unknown method requested']);
        }
    }


    /**
     * Controller method - delete | POST
     *
     * Will delete a comment from a customer and then call the
     * /account/comments controller.
     *
     * Required $_POST values: 'comment_id' -> Comment ID to be removed
     * from the db.
     *
     * @return void
     */
    #[NoReturn] public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (session_status() != PHP_SESSION_ACTIVE) session_start();
            $commentId = $_POST['comment_id'];
            $result = CommentDAO::getInstance()->delete($commentId);
            if ($result) {
                $_SESSION['message'] = 'Opération effecté avec succès!';
            } else {
                $_SESSION['error'] = "Problème lors de l'effecement du commentaire avec ID: " . $commentId;
            }
            header('Location: /account/comments');
            exit;
        } else {
            http_response_code(401);
            echo json_encode(['unknown method' => 'unknown method requested']);
        }
    }

    /** Controller method - comments | GET
     *
     * Will retrieve every comment from the db and
     * include the comments.php file with the array of comments.
     *
     * Required $_GET values: none
     *
     * @return void
     */
    #[NoReturn] public function comments(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            if (session_status() != PHP_SESSION_ACTIVE) session_start();
            $comments = CommentDAO::getInstance()->getAll();
            include __DIR__ . '/../../views/Account/comments.php';
            exit;
        } else {
            http_response_code(401);
            echo json_encode(['unknown method' => 'unknown method requested']);
        }
    }
}