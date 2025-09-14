<?php

namespace App\Resources\Menu\Models;

use App\Models\User;
use Illuminate\Support\Str;
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

    protected $with = [
        'skus',
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

    public function skus(): HasMany
    {
        return $this->hasMany(MenuItemSku::class);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($menuItem) {
            if (!$menuItem->slug) {
                $menuItem->slug = str()->slug($menuItem->name);
            }
        });

        // Update SKUs when menu item is updated (diff by uuid)
        static::updating(function ($menuItem) {
            if (!request()->has('skus')) {
                return;
            }

            $incomingGroups = array_merge([], request()->input('skus', []));

            $existingByUuid = $menuItem->skus()->get()->keyBy('uuid');
            $keptUuids = [];

            foreach ($incomingGroups as $group) {
                $groupName = $group['name'] ?? null;
                $items = $group['items'] ?? [];
                foreach ($items as $opt) {
                    $uuid = $opt['uuid'] ?? null;
                    $attributes = [
                        'name' => $groupName,
                        'value' => $opt['value'] ?? null,
                        'description' => $opt['description'] ?? null,
                        'sku' => $opt['sku'] ?? null,
                        'price' => $opt['price'] ?? null,
                    ];

                    if ($uuid && $existingByUuid->has($uuid)) {
                        // Update existing SKU
                        $existingByUuid->get($uuid)->update($attributes);
                        $keptUuids[] = $uuid;
                    } else {
                        // Create new SKU
                        $menuItem->skus()->create(array_merge($attributes, [
                            'uuid' => (string) Str::uuid(),
                        ]));
                    }
                }
            }

            // Delete SKUs that are not present anymore
            $uuidsToDelete = $existingByUuid->keys()->diff($keptUuids);
            if ($uuidsToDelete->isNotEmpty()) {
                $menuItem->skus()->whereIn('uuid', $uuidsToDelete->all())->delete();
            }
        });

        // Create related records after restaurant is created
        static::created(function ($menuItem) {
            $menuItem->createRelatedRecords($menuItem);
        });
    }

    /**
     * Create related records (contact, config, business hours)
     */
    public function createRelatedRecords($menuItem)
    {
        // Create skus if provided
        $groupedSkus = array_merge([], request()->skus ?? []);
        foreach ($groupedSkus as $groupedSku) {
            $groupName = $groupedSku['name'];
            foreach ($groupedSku['items'] as $sku) {
                $sku = array_merge($sku, [
                    'name' => $groupName,
                ]);
                $this->skus()->create($sku);
            }
        }
    }
}
