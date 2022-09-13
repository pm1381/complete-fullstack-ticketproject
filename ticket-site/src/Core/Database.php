<?php

namespace App\Core;

use MongoDB\Client;

class Database
{
    public static $connection;
    private $mongoConnection;

    public function connectToMySql()
    {
        if (! self::$connection) {
            // --------local
            // "mysql:host=" . 'localhost' . ";dbname=". ''
            $dsn = "mysql:host=" . 'localhost' . ";dbname=". '' . ";charset=UTF8";
            try {
                self::$connection = new \PDO($dsn, 'root', '');
            } catch (\PDOException $e) {
                echo $e->getMessage();
            }
        }
    }

    public function connectToMongo()
    {
        $client = new Client();
        $this->mongoConnection = $client;
    }

    public function getMongoConnection()
    {
        return $this->mongoConnection;
    }

    public function getConnection()
    {
        return self::$connection;
    }
}
