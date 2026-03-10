<?php

namespace App\Services;

use App\Http\Jwt;
use App\Utils\Validator;
use App\Models\User;

class UserService
{
    public static function create(array $data)
    {
        try {
            $validatedFields = Validator::validate([
                'name' => $data['name'] ?? '',
                'email' => $data['email'] ?? '',
                'password' => $data['password'] ?? '',
            ]);

            $validatedFields['password'] = password_hash($validatedFields['password'], PASSWORD_DEFAULT);

            $user = User::save($validatedFields);

            if(!$user) {
                return ['error' => 'User not created'];
            }

            return 'User created successfully';
        } catch (\PDOException $e) {
           return ['error' => $e->getMessage()];
        }catch (\Exception $e) {
           return ['error' => $e->getMessage()];
        }
    }

    public static function auth(array $data)
    {
        try {
            $validatedFields = Validator::validate([
                'email' => $data['email'] ?? '',
                'password' => $data['password'] ?? '',
            ]);

            $user = User::authentication($validatedFields);

            if(!$user) {
                return ['error' => 'E-mail or password incorrect'];
            }

            return Jwt::generate($user);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}