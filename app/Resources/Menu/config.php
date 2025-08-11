<?php

return [
    [
        'name' => 'menus',
        'controller' => App\Resources\Menu\Controllers\MenuController::class,
        'model' => App\Resources\Menu\Models\Menu::class,
        'validation' => [
            'store' => [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'owner_id' => 'required|exists:users,id',
                'slug' => 'required|string|max:255|unique:menus,slug',
                'is_active' => 'boolean',
                'primary_image_url' => 'nullable|string|max:255',
            ],
            'update' => [
                'name' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
                'owner_id' => 'sometimes|exists:users,id',
                'slug' => 'sometimes|string|max:255|unique:menus,slug,{id}',
                'primary_image_url' => 'nullable|string|max:255',
                'is_active' => 'sometimes|boolean',
            ]
        ],
        'relationships' => ['owner', 'categories', 'items', 'availabilities'],
        'filterable' => [
            'owner_id',
        ],
        'searchable' => ['name', 'slug'],
    ],
];
