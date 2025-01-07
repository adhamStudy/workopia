<?php

namespace Framework; // Corrected namespace

use PDO;
use PDOException; // Import PDOException
use Exception; // Import Exception

class Database
{
    public $conn;

    /**
     * Constructor for Database class
     * @param array $config
     */
    public function __construct($config)
    {
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
        ];

        try {
            $this->conn = new PDO($dsn, $config['username'], $config['password'], $options);
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: {$e->getMessage()}"); // Use $e->getMessage() for the error message
        }
    }

    /**
     * Execute a query
     * @param string $query
     * @param array $params
     * @return \PDOStatement
     * @throws Exception
     */
    public function query($query, $params = [])
    {
        try {
            $sth = $this->conn->prepare($query);
            // Bind named params
            foreach ($params as $param => $value) {
                $sth->bindValue(':' . $param, $value);
            }
            $sth->execute();
            return $sth;
        } catch (PDOException $e) {
            throw new Exception("Query failed to execute: {$e->getMessage()}"); // Use $e->getMessage() for the error message
        }
    }
}
