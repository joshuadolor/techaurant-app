<?php

namespace App\Resources\Menu\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JsonMenu extends Model
{
    use HasFactory;

    protected $table = 'json_menus';

    protected $fillable = [
        'restaurant_id',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];
}


