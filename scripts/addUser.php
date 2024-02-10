<?php

class DBController
{
    private PDO $db;

    public function __construct()
    {
        // Directly using database credentials
        $host = 'localhost';
        $username = 'lbeaudin-u';
        $password = 'GM$-t)(V4kPLOWASEMG)$%I*GHJ#($$W$()_B*JM#W$ofk';
        $database = 'lbeaudin';
        $port = 3306;

        try {
            $this->db = new PDO("mysql:host=$host;port=$port;dbname=$database", $username, $password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function getDB(): PDO
    {
        return $this->db;
    }
}

class userDAO
{
    private DBController $dbController;

    public function __construct($dbController)
    {
        $this->dbController = $dbController;
    }

    public function createUser($username, $email, $firstName, $lastName, $role, $phoneNumber, $registerDate, $hashedPassword)
    {
        $db = $this->dbController->getDB();

        $query = "INSERT INTO Users (Username, Email, FirstName, LastName, UserRole, PhoneNumber, RegisterDate, HashedPassword)
              VALUES (:username, :email, :firstName, :lastName, :role, :phoneNumber, :registerDate, :hashedPassword)";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':phoneNumber', $phoneNumber);
        $format = $registerDate->format('Y-m-d H:i:s');
        $stmt->bindParam(':registerDate', $format);
        $stmt->bindParam(':hashedPassword', $hashedPassword);

        try {
            $stmt->execute();
            return true;
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
            return false;
        }
    }
}

// Creating DBController instance
$dbController = new DBController();

// Creating userDAO instance
$userDAO = new userDAO($dbController);

// User data
$username = 'admin';
$email = 'admin@email.com';
$firstName = 'admin';
$lastName = 'admin';
$role = 'master';
$phoneNumber = '111-222-3333';
$registerDate = new DateTime();
$hashedPassword = password_hash('adminroot', PASSWORD_DEFAULT);

// Create user
$success = $userDAO->createUser($username, $email, $firstName, $lastName, $role, $phoneNumber, $registerDate, $hashedPassword);

if ($success) {
    echo "User successfully created.";
} else {
    echo "Failed to create user.";
}


