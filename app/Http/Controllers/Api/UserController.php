<?php

namespace App\Http\Controllers\Api;

use App\Models\User;

class UserController extends ResourceController
{
    protected $model = User::class;
    protected $validationRules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|string|min:8',
    ];
    
    
} 