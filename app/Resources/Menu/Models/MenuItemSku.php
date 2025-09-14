<?php

namespace App\Resources\Menu\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Core\Models\Traits\ResourceModelTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class MenuItemSku extends Model
{
    use HasFactory, ResourceModelTrait;

    protected $fillable = [
        'uuid',
        'menu_item_id',
        'name',
        'value',
        'description',
        'sku',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    protected $hidden = [
        'id',
    ];

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function availabilities(): MorphMany
    {
        return $this->morphMany(MenuAvailability::class, 'available', 'available_type', 'available_id');
    }
}