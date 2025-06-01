<?php

return [
    'name' => 'users',
    'controller' => App\Resources\User\Controllers\UserController::class,
    'service' => App\Resources\User\Services\UserService::class,
    'repository' => App\Resources\User\Repositories\UserRepository::class,
    'model' => App\Models\User::class,
    'validation' => [
        'store' => [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ],
        'update' => [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,{id}',
            'password' => 'sometimes|min:6'
        ]
    ],
    'relationships' => ['roles', 'permissions'],
    // keywords that are get parameters
    'searchable' => ['name', 'email']
];
