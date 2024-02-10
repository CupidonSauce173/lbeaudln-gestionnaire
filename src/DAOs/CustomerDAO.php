<?php

namespace Lbeaudln\Gestionnaire\DAOs;

use DateTime;
use Exception;
use Lbeaudln\Gestionnaire\Database\DBController;
use Lbeaudln\Gestionnaire\Models\Customer;
use PDO;
use PDOException;

class CustomerDAO
{
    private static CustomerDAO $instance;

    public function getAll(): array
    {
        // Init variables
        $dbController = DBController::getInstance();
        $db = $dbController->getDB();

        // Prepare the SQL query
        $query = "SELECT Id, FirstName, LastName, HomePhone, CellPhone, Email, HomeAddress, RegisterDate 
            FROM customers;";

        // Executing
        try {
            $stmt = $db->prepare($query);
            $stmt->execute();

            $customers = [];

            while ($customerRow = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $customers[$customerRow['Id']] = new Customer(
                    $customerRow['Id'],
                    $customerRow['FirstName'],
                    $customerRow['LastName'],
                    $customerRow['HomePhone'],
                    $customerRow['CellPhone'],
                    $customerRow['Email'],
                    $customerRow['HomeAddress'],
                    new DateTime($customerRow['RegisterDate'])
                );
            }
            $stmt->closeCursor();
            return $customers;
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        } catch (Exception $e) {
            error_log("Error while parsing new DateTime: " . $e->getMessage());
        }

        return []; // Couldn't fetch the data
    }

    public static function getInstance(): CustomerDAO
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getById(int $CustomerId): ?Customer
    {
        $dbController = DBController::getInstance();
        $db = $dbController->getDB();

        // Prepare the SQL query
        $query = "SELECT Id, FirstName, LastName, HomePhone, CellPhone, Email, HomeAddress, RegisterDate 
            FROM Customers WHERE Id = :id";

        // Bind parameters and execute the query
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $CustomerId);

        try {
            $stmt->execute();
            $customerRow = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            if ($customerRow) {
                // Create a User object from the fetched data
                return new Customer(
                    $customerRow['Id'],
                    $customerRow['FirstName'],
                    $customerRow['LastName'],
                    $customerRow['HomePhone'],
                    $customerRow['CellPhone'],
                    $customerRow['Email'],
                    $customerRow['HomeAddress'],
                    new DateTime($customerRow['RegisterDate'])
                );
            }
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        } catch (Exception $e) {
            error_log("Error while parsing new DateTime: " . $e->getMessage());
        }
        return null; // User with the specified ID not found
    }

    public function create(string $firstName, string $lastName, string $homePhone, string $cellPhone, string $email, string $homeAddress): bool
    {
        $dbController = DBController::getInstance();
        $db = $dbController->getDB();

        // Prepare the SQL query
        $query = "INSERT INTO Customers (FirstName, LastName, HomePhone, CellPhone, Email, HomeAddress)
              VALUES (:firstName, :lastName, :homePhone, :cellPhone, :email, :homeAddress);";

        // Bind parameters and execute the query
        $stmt = $db->prepare($query);
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':homePhone', $homePhone);
        $stmt->bindParam(':cellPhone', $cellPhone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':homeAddress', $homeAddress);

        try {
            $result = $stmt->execute();
            $stmt->closeCursor();
            return $result;
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
            return false;
        }
    }

    public function get(string $firstName, string $lastName, string $cellPhone): ?Customer
    {
        $dbController = DBController::getInstance();
        $db = $dbController->getDB();

        $query = "SELECT Id, FirstName, LastName, HomePhone, CellPhone, Email, HomeAddress, RegisterDate
              FROM Customers
              WHERE FirstName = :firstName AND LastName = :lastName AND CellPhone = :cellPhone;";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':cellPhone', $cellPhone);

        try {
            $stmt->execute();
            $customerRow = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            if ($customerRow) {
                return new Customer(
                    $customerRow['Id'],
                    $customerRow['FirstName'],
                    $customerRow['LastName'],
                    $customerRow['HomePhone'],
                    $customerRow['CellPhone'],
                    $customerRow['Email'],
                    $customerRow['HomeAddress'],
                    new DateTime($customerRow['RegisterDate'])
                );
            }
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        } catch (Exception $e) {
            error_log("Error while parsing new DateTime: " . $e->getMessage());
        }
        return null; // Customer with the specified details not found
    }

    public function delete(int $customerId): bool
    {
        $dbController = DBController::getInstance();
        $db = $dbController->getDB();

        // 1. Mortgage delete
        $query1 = "DELETE FROM mortgages WHERE CustomerId = :customerId";
        $stmt1 = $db->prepare($query1);
        $stmt1->bindParam(':customerId', $customerId);
        try {
            $result1 = $stmt1->execute();
        } catch (PDOException $exception) {
            error_log("Database error in deleting from mortgages: " . $exception->getMessage());
            return false;
        }

        // 2. Customer delete
        $query2 = "DELETE FROM Customers WHERE Id = :customerId";
        $stmt2 = $db->prepare($query2);
        $stmt2->bindParam(':customerId', $customerId);
        try {
            $result2 = $stmt2->execute();
        } catch (PDOException $exception) {
            error_log("Database error in deleting customer: " . $exception->getMessage());
            return false;
        }
        return $result1 && $result2;
    }

    public function update(int $id, string $lastName, string $firstName, string $homePhone, string $cellPhone, string $email, string $homeAddress): bool
    {
        $dbController = DBController::getInstance();
        $db = $dbController->getDB();

        // Prepare the SQL query
        $query = "UPDATE Customers
              SET LastName = :lastName, FirstName = :firstName, HomePhone = :homePhone, CellPhone = :cellPhone, Email = :email, HomeAddress = :homeAddress
              WHERE Id = :customerId;";

        // Bind parameters and execute the query
        $stmt = $db->prepare($query);
        $stmt->bindParam(':customerId', $id);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':homePhone', $homePhone);
        $stmt->bindParam(':cellPhone', $cellPhone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':homeAddress', $homeAddress);

        try {
            $result = $stmt->execute();
            $stmt->closeCursor();
            return $result;
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
            return false;
        }
    }
}