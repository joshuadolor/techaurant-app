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

    protected $with = [
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
            $restaurant->slug = Str::slug($restaurant->name) . '-' . $restaurant->owner_id * rand(1, 100);
        });

        // Create related records after restaurant is created
        static::created(function ($restaurant) {
            // Create contact if provided
            $contact = array_merge([
                'phone' => '',
                'email' => '',
                'address' => '',
            ], $restaurant->contact ?? []);
            $restaurant->contact()->create($contact);

            $configData = [
                'theme' => 'default',
                'currency' => 'USD',
                'primary_color' => '#D35400',
                'secondary_color' => '#2C3E50',
                'timezone' => 'UTC',
                'language' => 'en',
            ];

            if(request()->has('logo_url')){
                $configData['logo_url'] = request()->logo_url;
            }

            $restaurant->config()->create($configData);

            $businessHours = $restaurant->business_hours;
            if (isset($businessHours) && is_array($businessHours)) {
                foreach ($businessHours as $businessHour) {
                    $restaurant->businessHours()->create($businessHour);
                }
            } else {
                $defaultDays = [
                    'monday' => ['09:00', '17:00'],
                    'tuesday' => ['09:00', '17:00'],
                    'wednesday' => ['09:00', '17:00'],
                    'thursday' => ['09:00', '17:00'],
                    'friday' => ['09:00', '17:00'],
                    'saturday' => ['10:00', '16:00'],
                    'sunday' => ['10:00', '16:00'],
                ];

                foreach ($defaultDays as $day => $hours) {
                    $restaurant->businessHours()->create([
                        'day_of_week' => $day,
                        'open_time' => $hours[0],
                        'close_time' => $hours[1],
                        'is_open' => true,
                    ]);
                }
            }
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
