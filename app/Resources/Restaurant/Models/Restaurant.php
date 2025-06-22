<?php

namespace App\Resources\Restaurant\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Core\Models\Traits\ResourceModelTrait;
use App\Models\User;
use App\Resources\Restaurant\Models\RestaurantConfig;
use App\Resources\Restaurant\Models\RestaurantContact;
use App\Resources\Restaurant\Models\RestaurantBusinessHour;
use App\Resources\Restaurant\Database\Factories\RestaurantFactory;
use Illuminate\Support\Str;

class Restaurant extends Model
{
    use HasFactory, ResourceModelTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'tagline', 
        'description',
        'owner_id',
        'subdomain',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $withExists = [
        'config',
        'businessHours',
        'contact', 
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($restaurant) {
            $restaurant->slug = Str::slug($restaurant->name) . '-' . $restaurant->owner_id;
        });
    }

    /**
     * Get the owner of the restaurant.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the config for the restaurant.
     */
    public function config()
    {
        return $this->hasOne(RestaurantConfig::class);
    }

    /**
     * Get the business hours for the restaurant.
     */
    public function businessHours()
    {
        return $this->hasMany(RestaurantBusinessHour::class);
    }

    /**
     * Get the contact information for the restaurant.
     */
    public function contact()
    {
        return $this->hasOne(RestaurantContact::class);
    }

    protected static function newFactory()
    {
        return RestaurantFactory::new();
    }
}
