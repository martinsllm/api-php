<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Services\UserService;

class UserController
{
    public function store(Request $request, Response $response)
    {
        $body = $request::body();

        $userData = UserService::create($body);

        if (isset($userData['error'])) {
            return $response::json([
                'error' => $userData['error']
            ], 400);
        }

        $response::json([
            'data' => $userData
        ], 201);
    }

    public function login(Request $request, Response $response)
    {
        $body = $request::body();

        $userData = UserService::auth($body);

        if (isset($userData['error'])) {
            return $response::json([
                'error' => $userData['error']
            ], 400);
        }

        $response::json([
            'jwt' => $userData
        ]);
    }

    public function fetch(Request $request, Response $response)
    {
        $authorization = Request::authorization();

        $userData = UserService::fetch($authorization);

        if (isset($userData['error'])) {
            return $response::json([
                'error' => $userData['error']
            ], 400);
        }

        $response::json([
            'jwt' => $userData
        ]);
    }

    public function update(Request $request, Response $response)
    {
        $authorization = Request::authorization();

        $body = $request::body();

        $userData = UserService::update($authorization, $body);

        if (isset($userData['error'])) {
            return $response::json([
                'error' => $userData['error']
            ], 400);
        }

        $response::json([
            'data' => $userData
        ]);
    }

    public function remove(Request $request, Response $response, array $id)
    {
        $authorization = $request::authorization();

        $userService = UserService::delete($authorization, $id[0]);

        if (isset($userService['unauthorized'])) {
            return $response::json([
                'message' => $userService['unauthorized']
            ], 401);
        }

        if (isset($userService['error'])) {
            return $response::json([
                'message' => $userService['error']
            ], 400);
        }

        $response::json([
            'message' => $userService
        ], 200);
    }
}