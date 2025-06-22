<?php

namespace App\Resources\Restaurant\Controllers;

use App\Core\Controllers\ResourceController;
use Illuminate\Http\Request;

class RestaurantController extends ResourceController
{
    public function getValidationRules(string $type, Request $request): array
    {
        $rules = $this->validationRules[$type] ?? [];
        $rules = array_merge($rules, [
            'name' => 'required|string|max:255|unique:restaurants,name,null,id,owner_id,' . $request->owner_id,
        ]);
        return $rules;
    }
}