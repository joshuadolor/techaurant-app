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
    [
        'name' => 'menu-items',
        'controller' => App\Resources\Menu\Controllers\MenuItemController::class,
        'model' => App\Resources\Menu\Models\MenuItem::class,
        'validation' => [
            'store' => [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric',
                'preparation_time' => 'required|integer',
                'is_active' => 'boolean',
                'is_combo' => 'boolean',
                'menu_category_id' => 'required|exists:menu_categories,id',
            ],
            'update' => [
                'name' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
                'price' => 'sometimes|numeric',
                'preparation_time' => 'sometimes|integer',
                'is_active' => 'sometimes|boolean',
                'is_combo' => 'sometimes|boolean',
                'menu_category_id' => 'sometimes|exists:menu_categories,id',
            ]
        ],
        'relationships' => ['menuCategory'],
        'filterable' => [
            'menu_category_id', 'owner_id',
        ],
        'searchable' => ['name', 'slug'],
    ],
    [
        'name' => 'menu-categories',
        'controller' => App\Resources\Menu\Controllers\MenuCategoryController::class,
        'model' => App\Resources\Menu\Models\MenuCategory::class,
        'validation' => [
            'store' => [
                'name' => 'required|string|max:255',
            ]
        ],
        'relationships' => ['owner'],
        'filterable' => [
            'owner_id',
        ],
        'searchable' => ['name'],
    ]
];
