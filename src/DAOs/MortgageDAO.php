<?php

namespace Lbeaudln\Gestionnaire\DAOs;

use DateTime;
use Exception;
use Lbeaudln\Gestionnaire\Database\DBController;
use Lbeaudln\Gestionnaire\Models\Mortgage;
use PDO;
use PDOException;

class MortgageDAO
{
    private static MortgageDAO $instance;

    /**
     * @return MortgageDAO
     */
    public static function getInstance(): MortgageDAO
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    /**
     * @param int $customerId
     * @return array
     */
    public function getByCustomerId(int $customerId): array
    {
        $mortgages = [];

        $dbController = DBController::getInstance();
        $db = $dbController->getDB();

        $query = "SELECT Id, CustomerId, Amount, MortgageType, Deadline, Terms, Rate, Bank, Structure 
                  FROM Mortgages 
                  WHERE CustomerId = :customerId";

        try {
            $stmt = $db->prepare($query);
            $stmt->bindParam(':customerId', $customerId);
            $stmt->execute();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $mortgages[] = new Mortgage(
                    $row['Id'],
                    CustomerDAO::getInstance()->getById($row['CustomerId']),
                    $row['Amount'],
                    $row['Rate'],
                    $row['MortgageType'],
                    $row['Terms'],
                    new DateTime($row['Deadline']),
                    $row['Bank'],
                    $row['Structure']
                );
            }
            $stmt->closeCursor();
        } catch (PDOException $exception) {
            error_log("Database error in MortgageDAO->getByCustomerId: " . $exception->getMessage());
        } catch (Exception $e) {
            error_log("Database error while creating DataTime: " . $e->getMessage());
        }
        return $mortgages;
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        $dbController = DBController::getInstance();
        $db = $dbController->getDB();

        $query = "SELECT Id, CustomerId, Amount, MortgageType, Deadline, Terms, Rate, Bank, Structure FROM mortgages";

        try {
            $stmt = $db->prepare($query);
            $stmt->execute();

            $mortgages = [];

            while ($mortgageRow = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if (!isset($_SESSION['customers'][$mortgageRow['CustomerId']])) {
                    $_SESSION['customers'][$mortgageRow['CustomerId']] = CustomerDAO::getInstance()->getById($mortgageRow['CustomerId']);
                }
                $mortgages[$mortgageRow['CustomerId']][] = new Mortgage(
                    $mortgageRow['Id'],
                    $_SESSION['customers'][$mortgageRow['CustomerId']],
                    $mortgageRow['Amount'],
                    $mortgageRow['Rate'],
                    $mortgageRow['MortgageType'],
                    $mortgageRow['Terms'],
                    new DateTime($mortgageRow['Deadline']),
                    $mortgageRow['Bank'],
                    $mortgageRow['Structure']
                );
            }
            $stmt->closeCursor();
            return $mortgages;
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        } catch (Exception $e) {
            error_log("Error while parsing new DateTime: " . $e->getMessage());
        }

        return [];
    }

    /**
     * @param int $customerId
     * @param string $amount
     * @param string $mortgageType
     * @param string $deadline
     * @param string $terms
     * @param string $rate
     * @param string $bank
     * @param string $structure
     * @return bool
     */
    public function create(int $customerId, string $amount, string $mortgageType, string $deadline, string $terms, string $rate, string $bank, string $structure): bool
    {
        $dbController = DBController::getInstance();
        $db = $dbController->getDB();

        $query = "INSERT INTO Mortgages (CustomerId, Amount, MortgageType, Deadline, Terms, Rate, Bank, Structure)
              VALUES (:customerId, :amount, :mortgageType, :deadline, :terms, :rate, :bank, :structure)";

        try {
            $stmt = $db->prepare($query);
            $stmt->bindParam(':customerId', $customerId);
            $stmt->bindParam(':amount', $amount);
            $stmt->bindParam(':mortgageType', $mortgageType);
            $stmt->bindParam(':deadline', $deadline);
            $stmt->bindParam(':terms', $terms);
            $stmt->bindParam(':rate', $rate);
            $stmt->bindParam(':bank', $bank);
            $stmt->bindParam(':structure', $structure);

            $result = $stmt->execute();
            $stmt->closeCursor();
            return $result;
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        }
        return false;
    }

    /**
     * @param int $mortgageId
     * @return bool
     */
    public function delete(int $mortgageId): bool
    {
        $dbController = DBController::getInstance();
        $db = $dbController->getDB();

        $query = "DELETE FROM mortgages WHERE Id = :id";

        try {
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $mortgageId);

            $result = $stmt->execute();
            $stmt->closeCursor();
            return $result;
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        }
        return false;
    }

}