<?php

return [
    [
        'name' => 'restaurants',
        'controller' => 'App\Resources\Restaurant\Controllers\RestaurantController',
        'model' => App\Resources\Restaurant\Models\Restaurant::class,
        'validation' => [
            'store' => [
                'name' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:restaurants,slug',
                'description' => 'nullable|string',
                'owner_id' => 'required|exists:users,id',
                'subdomain' => 'nullable|string|max:255|unique:restaurants,subdomain',
                'is_active' => 'boolean',
                'tagline' => 'nullable|string|max:255',
            ],
            'update' => [
                'name' => 'sometimes|string|max:255',
                'slug' => 'sometimes|string|max:255|unique:restaurants,slug,{id}',
                'description' => 'sometimes|string',
                'owner_id' => 'sometimes|exists:users,id',
                'subdomain' => 'sometimes|string|max:255|unique:restaurants,subdomain,{id}',
                'is_active' => 'sometimes|boolean',
                'tagline' => 'sometimes|string|max:255',
            ]
        ],
        'relationships' => ['owner'],
        'searchable' => ['name', 'slug', 'subdomain'],
    ],
];
