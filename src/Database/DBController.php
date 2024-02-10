<?php

namespace Lbeaudln\Gestionnaire\Database;

use PDO;
use PDOException;

class DBController
{
    private static self $instance;
    private string $configPath = __DIR__ . '/../../resources/config.json';
    private PDO $db;

    private function __construct()
    {
        if (file_exists($this->configPath)) {
            $configContent = file_get_contents($this->configPath);
            $config = json_decode($configContent, true);
            try {
                $this->db = new PDO(
                    "mysql:host=" . $config['db_info']['address'] . ";dbname=" . $config['db_info']['database'],
                    $config['db_info']['username'],
                    $config['db_info']['password']
                );
            } catch (PDOException $exception) {
                echo $exception->getMessage();
            }
        } else {
            die("Config file not found!");
        }
    }

    public static function getInstance(): DBController
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getDB(): PDO
    {
        return $this->db;
    }
}