<?php

namespace App\Resources\Menu\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'name',
        'description',
        'primary_image_url',
        'owner_id',
        'is_active',
        'is_available',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_available' => 'boolean',
    ];

    protected $hidden = [
        'id',
    ];

    /**
     * Get the owner of the menu category.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the menus that include this category.
     */
    public function menus(): BelongsToMany
    {
        return $this->belongsToMany(Menu::class, 'menu_menu_category', 'menu_category_id', 'menu_id')
            ->withTimestamps();
    }

    /**
     * Get the menu items in this category.
     */
    public function menuItems(): BelongsToMany
    {
        return $this->belongsToMany(MenuItem::class, 'menu_category_menu_item', 'menu_category_id', 'menu_item_id')
            ->withTimestamps();
    }

    /**
     * Scope to get only active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only available categories.
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Get the availability schedules for this menu category.
     */
    public function availabilities(): MorphMany
    {
        return $this->morphMany(MenuAvailability::class, 'available', 'available_type', 'available_id');
    }
}
