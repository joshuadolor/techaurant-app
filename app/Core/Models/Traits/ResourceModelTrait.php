<?php

namespace App\Core\Models\Traits;

use Illuminate\Support\Str;

trait ResourceModelTrait
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }
}
