<?php

namespace App\Resources\Tag\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Core\Models\Traits\ResourceModelTrait;

class Taggable extends Model
{
    use HasFactory, ResourceModelTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'taggables';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'tag_id',
        'taggable_id',
        'taggable_type',
    ];

    /**
     * Get the tag that owns the taggable.
     */
    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }

    /**
     * Get the parent taggable model.
     */
    public function taggable()
    {
        return $this->morphTo();
    }
} 