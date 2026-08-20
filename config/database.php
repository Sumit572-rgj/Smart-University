<?php
// config/database.php

// Database Configuration (AlwaysData)
define('DB_HOST', 'mysql-citums.alwaysdata.net'); // Remote host
define('DB_PORT', '3306');                        // Default MySQL port
define('DB_USER', 'citums');                      // Your AlwaysData username
define('DB_PASS', '9812361098abc');               // Your AlwaysData password
define('DB_NAME', 'citums_db');                   // Your database name

// Security Configuration
define('JWT_SECRET', 'super_secret_cit_key_2026_!@#$');

// PDO Connection Singleton
class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
            DB_HOST,
            DB_PORT,
            DB_NAME
        );

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            error_log('Database connection failed: ' . $e->getMessage());
            die('Database connection error. Please check configuration.');
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
}
