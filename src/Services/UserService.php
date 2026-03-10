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

    public static function fetch(mixed $authorization)
    {
        try {
            if(isset($authorization['error'])) {
                return ['error' => $authorization['error']];
            }
            
            $userFromJWT = JWT::verify($authorization);

            if (!$userFromJWT) return ['unauthorized'=> "Please, login to access this resource."];

            $user = User::find($userFromJWT['id']);

            if (!$user) return ['error'=> 'Sorry, we could not find your account.'];

            return $user;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public static function update(mixed $authorization, array $data)
    {
        try {
            if (isset($authorization['error'])) {
                return ['unauthorized'=> $authorization['error']];
            }

            $userFromJWT = JWT::verify($authorization);

            if (!$userFromJWT) return ['unauthorized'=> "Please, login to access this resource."];

            $fields = Validator::validate([
                'name' => $data['name'] ?? ''
            ]);

            $user = User::update($userFromJWT['id'], $fields);

            if (!$user) return ['error'=> 'Sorry, we could not update your account.'];

            return "User updated successfully!";
        } 
        catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public static function delete(mixed $authorization, int|string $id)
    {
        try {
            if (isset($authorization['error'])) {
                return ['unauthorized'=> $authorization['error']];
            }

            $userFromJWT = JWT::verify($authorization);

            if (!$userFromJWT) return ['unauthorized'=> "Please, login to access this resource."];

            $user = User::delete($id);

            if (!$user) return ['error'=> 'Sorry, we could not delete your account.'];

            return "User deleted successfully!";
        } 
        catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}