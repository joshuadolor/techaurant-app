<?php

return [
    [
        'name' => 'restaurants',
        'controller' => App\Resources\Restaurant\Controllers\RestaurantController::class,
        'model' => App\Resources\Restaurant\Models\Restaurant::class,
        'validation' => [
            'store' => [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'owner_id' => 'required|exists:users,id',
                'subdomain' => 'nullable|string|max:255|unique:restaurants,subdomain',
                'is_active' => 'boolean',
                'tagline' => 'nullable|string|max:255',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'contact' => 'sometimes|array',
                'contact.phone' => 'sometimes|string|max:255',
                'contact.email' => 'sometimes|email|max:255',
                'contact.address' => 'sometimes|string|max:255',
                'contact.country_id' => 'sometimes|exists:countries,id',
            ],
            'update' => [
                'name' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
                'owner_id' => 'sometimes|exists:users,id',
                'subdomain' => 'sometimes|string|max:255|unique:restaurants,subdomain,{id}',
                'is_active' => 'sometimes|boolean',
                'tagline' => 'sometimes|string|max:255',
                'contact' => 'sometimes|array',
                'contact.phone' => 'sometimes|string|max:255',
                'contact.email' => 'sometimes|email|max:255',
                'contact.address' => 'sometimes|string|max:255',
                'contact.country_id' => 'sometimes|exists:countries,id',
            ]
        ],
        'relationships' => ['owner'],
        'filterable' => [
            'owner_id',
        ],
        'searchable' => ['name', 'slug', 'subdomain'],
    ],
];
