<?php

namespace App\Resources\Menu\Models;

use App\Resources\Restaurant\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RestaurantMenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'menu_item_id',
        'override_price',
        'is_active',
        'is_available',
    ];

    protected $casts = [
        'override_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_available' => 'boolean',
    ];

    /**
     * Get the restaurant that serves this menu item.
     */
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * Get the menu item being served.
     */
    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }

    /**
     * Get the effective price (override price if set, otherwise menu item price).
     */
    public function getEffectivePriceAttribute()
    {
        return $this->override_price ?? $this->menuItem->price;
    }

    /**
     * Scope to get only active restaurant menu items.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only available restaurant menu items.
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope to get items with price overrides.
     */
    public function scopeWithPriceOverride($query)
    {
        return $query->whereNotNull('override_price');
    }
}
