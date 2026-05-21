<?php

require_once __DIR__ . '/../config/database.php';

/**
 * Database - PDO Singleton
 * Manages a single PostgreSQL connection to Supabase.
 */
class Database
{
    private static ?PDO $instance = null;

    private function __construct() {}
    private function __clone() {}

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $dsn = sprintf(
                'pgsql:host=%s;port=%s;dbname=%s;options=--search_path=%s',
                DB_HOST,
                DB_PORT,
                DB_NAME,
                DB_SCHEMA
            );

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                // Surface connection errors clearly during development
                die('Database connection failed: ' . $e->getMessage());
            }
        }

        return self::$instance;
    }
}
