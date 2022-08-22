<?php

namespace App\Core;

use MongoDB\Client;

//require_once 'Config.php';

class Database
{
    public static $connection;
    private $mongoConnection;

    public function connectToMySql()
    {
        if (! self::$connection) {
            // rava 
            // username : ravathea_parham // password : 8v84&q1owa}h
            // "mysql:host=" . 'localhost' . ";dbname=". 'ravathea_ghadir'
            // --------local
            // "mysql:host=" . 'localhost' . ";dbname=". 'ghadir'
            $dsn = "mysql:host=" . 'localhost' . ";dbname=". 'ghadir' . ";charset=UTF8";
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
