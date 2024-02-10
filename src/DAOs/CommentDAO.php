<?php

namespace Lbeaudln\Gestionnaire\DAOs;

use DateTime;
use Exception;
use Lbeaudln\Gestionnaire\Database\DBController;
use Lbeaudln\Gestionnaire\Models\Comment;
use PDO;
use PDOException;

class CommentDAO
{
    private static CommentDAO $instance;

    public function getByCustomerId(int $customerId): array
    {
        $comments = [];
        $dbController = DBController::getInstance();
        $db = $dbController->getDB();

        $query = "SELECT Id, CustomerId, AuthorId, Body, RegisterDate 
                  FROM Comments 
                  WHERE CustomerId = :customerId";

        try {
            $stmt = $db->prepare($query);
            $stmt->bindParam(':customerId', $customerId);
            $stmt->execute();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $comments[] = new Comment(
                    $row['Id'],
                    CustomerDAO::getInstance()->getById($customerId),
                    UserDAO::getInstance()->getById($row['AuthorId']),
                    $row['Body'],
                    new DateTime($row['RegisterDate'])
                );
            }
        } catch (PDOException $exception) {
            error_log("Database error in CommentDAO->getByCustomerId: " . $exception->getMessage());
            // Handle this exception in a way appropriate for your application
        } catch (Exception $e) {
            error_log("Database error while creating DataTime: " . $e->getMessage());
        }
        return $comments;
    }

    public static function getInstance(): CommentDAO
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getAll(): array
    {
        // Init variables
        $dbController = DBController::getInstance();
        $db = $dbController->getDB();

        // Prepare the SQL query
        $query = "SELECT Id, CustomerId, AuthorId, Body, RegisterDate
            FROM Comments;";

        if (session_status() != PHP_SESSION_ACTIVE) session_start();

        $comments = [];
        try {
            $stmt = $db->prepare($query);
            $stmt->execute();

            while ($commentRow = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Get Customer.
                if (!isset($_SESSION['customers'][$commentRow['CustomerId']])) {
                    // TODO: What if the customer is inexistant?
                    $_SESSION['customers'][$commentRow['CustomerId']] = CustomerDAO::getInstance()->getById($commentRow['CustomerId']);
                }
                // Get User.
                if (!isset($_SESSION['users'][$commentRow['AuthorId']])) {
                    // TODO: What if the user is inexistant?
                    $_SESSION['customers'][$commentRow['CustomerId']] = UserDAO::getInstance()->getById($commentRow['AuthorId']);
                }
                // Adding Comment to the array.
                $comments[$commentRow['Id']] = new Comment(
                    $commentRow['Id'],
                    $_SESSION['customers'][$commentRow['CustomerId']],
                    $_SESSION['users'][$commentRow['AuthorId']],
                    $commentRow['Body'],
                    new DateTime($commentRow['RegisterDate'])
                );
            }
            return $comments;
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        } catch (Exception $e) {
            error_log("Error while parsing new DateTime: " . $e->getMessage());
        }

        return $comments;
    }

    public function create(int $customerId, int $authorId, string $body): false|string
    {
        $dbController = DBController::getInstance();
        $db = $dbController->getDB();

        $query = "INSERT INTO Comments (CustomerId, AuthorId, Body, RegisterDate) 
              VALUES (:customerId, :authorId, :body, :registerDate)";

        try {
            $stmt = $db->prepare($query);
            $stmt->bindParam(':customerId', $customerId);
            $stmt->bindParam(':authorId', $authorId);
            $stmt->bindParam(':body', $body);
            $registerDate = date('Y-m-d H:i:s');
            $stmt->bindParam(':registerDate', $registerDate);
            $stmt->execute();
            $stmt->closeCursor();

            return $db->lastInsertId();
        } catch (PDOException $exception) {
            error_log("Database error in CommentDAO->create: " . $exception->getMessage());
        }
        return false;
    }

    public function delete(int $commentId): bool
    {
        $dbController = DBController::getInstance();
        $db = $dbController->getDB();

        $query = "DELETE FROM Comments WHERE id = :id";

        try {
            $stmt = $db->prepare($query);
            $stmt->bindParam('id', $commentId);
            $result = $stmt->execute();
            $stmt->closeCursor();
            return $result;
        } catch (PDOException $exception) {
            error_log("Database error in CommentDAO->delete: " . $exception->getMessage());
        }
        return false;
    }

}