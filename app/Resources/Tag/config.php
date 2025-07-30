<?php

return [
    [
        'name' => 'tags',
        'controller' => App\Resources\Tag\Controllers\TagController::class,
        'model' => App\Resources\Tag\Models\Tag::class,
        'validation' => [
            'store' => [
                'name' => 'required|string|max:100',
                'owner_id' => 'required|exists:users,id',
            ],
            'update' => [
                'name' => 'sometimes|string|max:100',
                'owner_id' => 'sometimes|exists:users,id',
            ]
        ],
        'relationships' => ['owner'],
        'filterable' => [
            'owner_id',
        ],
        'searchable' => ['name'],
    ],
]; 