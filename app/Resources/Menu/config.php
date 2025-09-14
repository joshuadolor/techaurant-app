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
                'name' => 'required|string|unique:menu_items,name|max:255',
                'description' => 'nullable|string',
                'price' => 'sometimes|nullable|numeric',
                'preparation_time' => 'sometimes|nullable|integer',
                'primary_image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'skus' => 'nullable|sometimes|array',
                'is_active' => 'boolean',
                'is_combo' => 'boolean',
                'owner_id' => 'required|exists:users,id',
            ],
            'update' => [
                'name' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
                'price' => 'sometimes|numeric',
                'preparation_time' => 'sometimes|integer',
                'primary_image_url' => 'nullable|sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
                'skus' => 'nullable|sometimes|array',
                'is_active' => 'sometimes|boolean',
                'is_combo' => 'sometimes|boolean',
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
