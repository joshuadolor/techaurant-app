<?php

namespace App\Resources\Menu\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuComboItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'main_menu_item_id',
        'menu_item_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
    ];

    /**
     * Get the main menu item of the combo.
     */
    public function mainMenuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'main_menu_item_id');
    }

    /**
     * Get the menu item included in the combo.
     */
    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'menu_item_id');
    }
}
