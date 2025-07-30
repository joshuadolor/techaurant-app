<?php

namespace App\Resources\Tag\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Core\Models\Traits\ResourceModelTrait;
use App\Models\User;
use App\Resources\Tag\Database\Factories\TagFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Tag extends Model
{
    use HasFactory, ResourceModelTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'owner_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];

    /**
     * Get the owner of the tag.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get all menu items that are tagged with this tag.
     */
    public function menuItems()
    {
        return [];
        // return $this->morphedByMany(
        //     'App\Resources\Menu\Models\MenuItem',
        //     'taggable',
        //     'taggables',
        //     'tag_id',
        //     'taggable_id'
        // );
    }

    /**
     * Get all menu categories that are tagged with this tag.
     */
    public function menuCategories()
    {
        return $this->morphedByMany(
            'App\Resources\Menu\Models\MenuCategory',
            'taggable',
            'taggables',
            'tag_id',
            'taggable_id'
        );
    }

    /**
     * Get all menu combos that are tagged with this tag.
     */
    public function menuCombos()
    {
        return $this->morphedByMany(
            'App\Resources\Menu\Models\MenuCombo',
            'taggable',
            'taggables',
            'tag_id',
            'taggable_id'
        );
    }

    protected static function newFactory()
    {
        return TagFactory::new();
    }
} 