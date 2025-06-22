<?php

namespace App\Resources\Restaurant\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Core\Models\Traits\ResourceModelTrait;

class RestaurantConfig extends Model
{
    use HasFactory, ResourceModelTrait;

    protected $table = 'restaurant_config';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'restaurant_id',
        'language',
        'primary_color',
        'secondary_color',
        'logo_url',
        'banner_url',
        'timezone',
        'currency',
    ];

    /**
     * Get the restaurant that owns the config.
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
