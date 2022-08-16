<?php

namespace App\Core;
//require_once 'Config.php';

class Database
{
    public static $connection;

    public function connect()
    {
        if (! self::$connection) {
            $dsn = "mysql:host=" . 'localhost' . ";dbname=". 'your db name' . ";charset=UTF8";
            try {
                self::$connection = new \PDO($dsn, 'username', 'password');
            } catch (\PDOException $e) {
                echo $e->getMessage();
            }
        }
    }

    public function connectionStatus()
    {
        return self::$connection;
    }
}
