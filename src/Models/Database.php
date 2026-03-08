<?php

namespace App\Models;

class Database 
{
    public static function getConnection()
    {
        $pdo = new \PDO('mysql:host=localhost;dbname=api', 'root', '12345678');

        return $pdo;
    }
}