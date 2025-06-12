<?php

namespace App\Core\Models\Traits;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

trait ResourceModelTrait 
{
    use HasUuids;

    protected function initializeResourceModelTrait()
    {
        $this->hidden = array_merge($this->hidden ?? [], ['id']);
    }

    protected static function bootResourceModelTrait()
    {
        static::creating(function ($model) {
            $model->initializeResourceModelTrait();
        });
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = null;
            $model->uuid = (string) Str::uuid();
        });
    }
}