<?php

namespace App\Models;

class User extends Database
{
    public static function save(array $data)
    {
        $pdo = self::getConnection();

        $stmt = $pdo->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
        $stmt->execute([
            $data['name'],
            $data['email'],
            $data['password']
        ]);

        return $pdo->lastInsertId() > 0 ? true : false;
    }
}