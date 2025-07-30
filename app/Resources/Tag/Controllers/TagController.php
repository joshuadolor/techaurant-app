<?php

namespace App\Resources\Tag\Controllers;

use App\Core\Controllers\ResourceController;
use Illuminate\Http\Request;

class TagController extends ResourceController
{
    public function getValidationRules(string $type, Request $request): array
    {
        $rules = $this->validationRules[$type] ?? [];
        if ($type === 'update') {
            $rules = array_merge($rules, [
                'name' => 'sometimes|string|max:255|unique:tags,name,' . $request->route('tag') . ',uuid,owner_id,' . $request->owner_id,
            ]);
        } else {
            $rules = array_merge($rules, [
                'name' => 'required|string|max:255|unique:tags,name,NULL,id,owner_id,' . $request->owner_id,
            ]); 
        }
        return $rules;
    }
} 