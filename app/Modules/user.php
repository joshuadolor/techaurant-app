<?php

return [
    'model' => \App\Models\User::class,
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
    'searchable' => ['name', 'email']
];