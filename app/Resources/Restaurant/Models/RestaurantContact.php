<?php

namespace App\Resources\Restaurant\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Core\Models\Traits\ResourceModelTrait;
use App\Models\Country;
class RestaurantContact extends Model
{
    use HasFactory, ResourceModelTrait;

    protected $fillable = [
        'restaurant_id',
        'phone',
        'email',
        'address',
        'city',
        'state',
        'zip',
        'country_id',
    ];

    protected $with = [
        'country',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
