<?php

namespace App\Resources\Menu\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MenuAvailability extends Model
{
    use HasFactory;

    protected $table = 'availabilities';

    protected $fillable = [
        'uuid',
        'available_type',
        'available_id',
        'day_of_week',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    /**
     * Get the parent available model (menu, menu category, or menu item).
     */
    public function available(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope to get availabilities for a specific day.
     */
    public function scopeForDay($query, $day)
    {
        return $query->where('day_of_week', strtolower($day));
    }

    /**
     * Scope to get availabilities for a specific type.
     */
    public function scopeForType($query, $type)
    {
        return $query->where('available_type', $type);
    }

    /**
     * Scope to get availabilities for a specific time.
     */
    public function scopeForTime($query, $time)
    {
        return $query->where('start_time', '<=', $time)
                    ->where('end_time', '>=', $time);
    }

    /**
     * Get the day names as constants.
     */
    public const DAYS = [
        'monday' => 'Monday',
        'tuesday' => 'Tuesday',
        'wednesday' => 'Wednesday',
        'thursday' => 'Thursday',
        'friday' => 'Friday',
        'saturday' => 'Saturday',
        'sunday' => 'Sunday',
    ];

    /**
     * Get the available types as constants.
     */
    public const TYPES = [
        'menu' => 'Menu',
        'menu_category' => 'Menu Category',
        'menu_item' => 'Menu Item',
    ];
}
