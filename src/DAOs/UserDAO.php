<?php

namespace Lbeaudln\Gestionnaire\DAOs;

use DateTime;
use Exception;
use Lbeaudln\Gestionnaire\Database\DBController;
use Lbeaudln\Gestionnaire\Models\User;
use PDO;
use PDOException;

class UserDAO
{

    private static UserDAO $instance;

    public static function getInstance(): UserDAO
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function create(string $username, string $email, string $firstName, string $lastName, string $role, string $phoneNumber, DateTime $registerDate, string $hashedPassword): bool
    {
        $dbController = DBController::getInstance();
        $db = $dbController->getDB();

        $query = "INSERT INTO Users (Username, HashedPassword, Email, FirstName, LastName, UserRole, PhoneNumber, RegisterDate)
              VALUES (:username, :hashedPassword, :email, :firstName, :lastName, :role, :phoneNumber, :registerDate)";

        try {
            $stmt = $db->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':hashedPassword', $hashedPassword);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':firstName', $firstName);
            $stmt->bindParam(':lastName', $lastName);
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':phoneNumber', $phoneNumber);
            $format = $registerDate->format('Y-m-d H:i:s');
            $stmt->bindParam(':registerDate', $format);

            $result = $stmt->execute();
            $stmt->closeCursor();
            return $result;
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
            return false;
        }
    }

    public function update(int $userId, string $username, string $email, string $firstName, string $lastName, string $phoneNumber): bool
    {
        $dbController = DBController::getInstance();
        $db = $dbController->getDB();

        $query = "UPDATE Users SET Username = :username, Email = :email, FirstName = :firstName, LastName = :lastName, PhoneNumber = :phoneNumber WHERE Id = :userId";

        try {
            $stmt = $db->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':firstName', $firstName);
            $stmt->bindParam(':lastName', $lastName);
            $stmt->bindParam(':phoneNumber', $phoneNumber);
            $stmt->bindParam(':userId', $userId);

            $stmt->execute();
            $stmt->closeCursor();
            return true;
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
            return false;
        }
    }


    public function getById(int $id): ?User
    {
        $dbController = DBController::getInstance();
        $db = $dbController->getDB();

        $query = "SELECT * FROM Users WHERE Id = :id";

        try {
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $id);

            $stmt->execute();
            $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            if ($userRow) {
                return new User(
                    $userRow['Id'],
                    $userRow['Username'],
                    $userRow['Email'],
                    $userRow['FirstName'],
                    $userRow['LastName'],
                    $userRow['UserRole'],
                    $userRow['PhoneNumber'],
                    new DateTime($userRow['RegisterDate']),
                    $userRow['HashedPassword']
                );
            } else {
                return null;
            }
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        } catch (Exception $e) {
            error_log("Error while parsing new DateTime: " . $e->getMessage());
        }
        return null;
    }

    public function getByEmail(string $email): ?User
    {
        $dbController = DBController::getInstance();
        $db = $dbController->getDB();

        $query = "SELECT * FROM Users WHERE Email = :email";

        try {
            $stmt = $db->prepare($query);
            $stmt->bindParam(':email', $email);

            $stmt->execute();
            $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            if ($userRow) {
                return new User(
                    $userRow['Id'],
                    $userRow['Username'],
                    $userRow['Email'],
                    $userRow['FirstName'],
                    $userRow['LastName'],
                    $userRow['UserRole'],
                    $userRow['PhoneNumber'],
                    new DateTime($userRow['RegisterDate']),
                    $userRow['HashedPassword']
                );
            } else {
                return null;
            }
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        } catch (Exception $e) {
            error_log("Error while parsing new DateTime: " . $e->getMessage());
        }
        return null;
    }

    public function getByUsername(string $username): ?User
    {
        $dbController = DBController::getInstance();
        $db = $dbController->getDB();

        $query = "SELECT * FROM Users WHERE Username = :username";

        try {
            $stmt = $db->prepare($query);
            $stmt->bindParam(':username', $username);

            $stmt->execute();
            $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            if ($userRow) {
                // Create a User object from the fetched data
                return new User(
                    $userRow['Id'],
                    $userRow['Username'],
                    $userRow['Email'],
                    $userRow['FirstName'],
                    $userRow['LastName'],
                    $userRow['UserRole'],
                    $userRow['PhoneNumber'],
                    new DateTime($userRow['RegisterDate']),
                    $userRow['HashedPassword']
                );
            } else {
                return null;
            }
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        } catch (Exception $e) {
            error_log("Error while parsing new DateTime: " . $e->getMessage());
        }
        return null;
    }

    public function updatePassword(int $id, string $password_hash): bool|array
    {
        $dbController = DBController::getInstance();
        $db = $dbController->getDB();

        $query = "UPDATE Users SET HashedPassword = :password WHERE Id = :userId";

        try {
            $stmt = $db->prepare($query);
            $stmt->bindParam(':password', $password_hash);
            $stmt->bindParam(':userId', $id);

            $result = $stmt->execute();
            $stmt->closeCursor();
            if (!$result) {
                return $stmt->errorInfo();
            }
            return true;
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
            return false;
        }
    }

    public function getAll(): array
    {
        $dbController = DBController::getInstance();
        $db = $dbController->getDB();

        // Prepare the SQL query
        $query = "SELECT Id, Username, Email, FirstName, LastName, UserRole, PhoneNumber, RegisterDate
              FROM Users";

        try {
            $stmt = $db->prepare($query);
            $stmt->execute();

            $users = [];

            while ($userRow = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $users[$userRow['Id']] = new User(
                    $userRow['Id'],
                    $userRow['Username'],
                    $userRow['Email'],
                    $userRow['FirstName'],
                    $userRow['LastName'],
                    $userRow['UserRole'],
                    $userRow['PhoneNumber'],
                    new DateTime($userRow['RegisterDate']),
                    ''
                );
            }

            $stmt->closeCursor();
            return $users;
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        } catch (Exception $e) {
            error_log("Error while parsing new DateTime: " . $e->getMessage());
        }

        return [];
    }

    public function delete(int $userId): bool
    {
        $dbController = DBController::getInstance();
        $db = $dbController->getDB();

        $query = "DELETE FROM Users WHERE Id = :userId";

        try {
            $stmt = $db->prepare($query);
            $stmt->bindParam(':userId', $userId);

            $stmt->execute();
            $stmt->closeCursor();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
            return false;
        }
    }


}