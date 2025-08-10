<?php

namespace App\Resources\Menu\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'name',
        'slug',
        'description',
        'primary_image_url',
        'is_active',
        'is_available',
        'owner_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_available' => 'boolean',
    ];

    protected $hidden = [
        'id',
    ];

    /**
     * Get the owner of the menu.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the categories associated with this menu.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(MenuCategory::class, 'menu_menu_category', 'menu_id', 'menu_category_id')
            ->withTimestamps();
    }

    /**
     * Get the menu items associated with this menu through categories.
     */
    public function menuItems()
    {
        return $this->hasManyThrough(
            MenuItem::class,
            MenuCategory::class,
            'id', // Foreign key on menu_categories table
            'id', // Foreign key on menu_items table
            'id', // Local key on menus table
            'id'  // Local key on menu_categories table
        );
    }

    /**
     * Scope to get only active menus.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only available menus.
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Get the availability schedules for this menu.
     */
    public function availabilities(): MorphMany
    {
        return $this->morphMany(MenuAvailability::class, 'available', 'available_type', 'available_id');
    }
}
