<?php

namespace App\Resources\Menu\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Models\Traits\ResourceModelTrait;

class MenuItem extends Model
{
    use HasFactory, SoftDeletes, ResourceModelTrait;

    protected $fillable = [
        'uuid',
        'slug',
        'name',
        'description',
        'price',
        'preparation_time',
        'primary_image_url',
        'is_active',
        'is_available',
        'owner_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'preparation_time' => 'integer',
        'is_active' => 'boolean',
        'is_available' => 'boolean',
    ];

    protected $hidden = [
        'id',
    ];

    /**
     * Get the owner of the menu item.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the categories associated with this menu item.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(MenuCategory::class, 'menu_category_menu_item', 'menu_item_id', 'menu_category_id')
            ->withTimestamps();
    }

    /**
     * Get the restaurants that serve this menu item.
     */
    public function restaurants(): BelongsToMany
    {
        return $this->belongsToMany(\App\Resources\Restaurant\Models\Restaurant::class, 'restaurant_menu_items', 'menu_item_id', 'restaurant_id')
            ->withPivot('override_price', 'is_active', 'is_available')
            ->withTimestamps();
    }

    /**
     * Check if the menu item is a combo item.
     */
    public function isCombo(): bool
    {
        return $this->comboItems()->exists();
    }

    /**
     * Get the combo items that include this menu item.
     */
    public function comboItems(): HasMany
    {
        return $this->hasMany(MenuComboItem::class, 'menu_item_id');
    }

    /**
     * Get the main combo items where this item is the main item.
     */
    public function mainComboItems(): HasMany
    {
        return $this->hasMany(MenuComboItem::class, 'main_menu_item_id');
    }

    /**
     * Scope to get only active menu items.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only available menu items.
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope to get only free menu items.
     */
    public function scopeFree($query)
    {
        return $query->whereNull('price');
    }

    /**
     * Scope to get only paid menu items.
     */
    public function scopePaid($query)
    {
        return $query->whereNotNull('price');
    }

    /**
     * Get the availability schedules for this menu item.
     */
    public function availabilities(): MorphMany
    {
        return $this->morphMany(MenuAvailability::class, 'available', 'available_type', 'available_id');
    }
}
