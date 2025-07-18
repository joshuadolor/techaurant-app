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

        static::updating(function ($restaurant) {
            if ($restaurant->isDirty('name')) { 
                $restaurant->slug = Str::slug($restaurant->name) . '-' . $restaurant->owner_id * rand(1, 100);
            }
        });

        // Create related records after restaurant is created
        static::created(function ($restaurant) {
            $restaurant->createRelatedRecords();
        });

    }

    /**
     * Create related records (contact, config, business hours)
     */
    public function createRelatedRecords()
    {
        // Create contact if provided
        $contact = array_merge([
            'phone' => '',
            'email' => '',
            'address' => '',
        ], request()->contact ?? []);

        $this->contact()->create($contact);

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

        $this->config()->create($configData);

        $businessHours = request()->business_hours;
        if (isset($businessHours) && is_array($businessHours)) {
            foreach ($businessHours as $businessHour) {
                $this->businessHours()->create($businessHour);
            }
        } else {
            $this->createDefaultBusinessHours();
        }
    }

    /**
     * Update related records (contact, config, business hours)
     */
    public function updateRelatedRecords()
    {
        // Update contact information
        if (request()->has('contact')) {
            $contactData = request()->contact;
            if ($this->contact) {
                $this->contact->update($contactData);
            } else {
                $this->contact()->create($contactData);
            }
        }

        // Update config information
        if (request()->has('config')) {
            $configData = request()->config;
            if ($this->config) {
                $this->config->update($configData);
            } else {
                $this->config()->create($configData);
            }
        }

        // Update business hours
        if (request()->has('business_hours')) {
            $businessHoursData = request()->business_hours;
            
            // Delete existing business hours
            $this->businessHours()->delete();
            
            // Create new business hours
            foreach ($businessHoursData as $businessHour) {
                $this->businessHours()->create($businessHour);
            }
        }
    }

    /**
     * Create default business hours
     */
    private function createDefaultBusinessHours()
    {
        $defaultDays = [
            'Monday' => ['09:00:00', '17:00:00'],
            'Tuesday' => ['09:00:00', '17:00:00'],
            'Wednesday' => ['09:00:00', '17:00:00'],
            'Thursday' => ['09:00:00', '17:00:00'],
            'Friday' => ['09:00:00', '17:00:00'],
            'Saturday' => ['10:00:00', '16:00:00'],
            'Sunday' => ['10:00:00', '16:00:00'],
        ];

        foreach ($defaultDays as $day => $hours) {
            $this->businessHours()->create([
                'day_of_week' => $day,
                'open_time' => $hours[0],
                'close_time' => $hours[1],
                'is_open' => true,
            ]);
        }
    }

    public function save(array $options = [])
    {
        $result = parent::save($options);
        
        // Always update related records after any save
        if ($result) {
            $this->updateRelatedRecords();
        }
        
        return $result;
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
