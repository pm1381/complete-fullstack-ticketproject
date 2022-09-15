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
            $dsn = "mysql:host=" . 'localhost' . ";dbname=". DB_NAME . ";charset=UTF8";
            try {
                self::$connection = new \PDO($dsn, USERNAME, PASSWORD);
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
