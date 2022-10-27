<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int    $id
 * @property string $title
 * @property int    $parent_id
 * @property string $color_title
 * @property string $color_border
 * @property int    $order
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class GraphCategory extends Model
{
    use HasFactory;

    protected $table = "graph_category";

    protected $guarded = [
        "id",
    ];

    protected $with = [
        "tools",
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, "parent_id");
    }

    public function subcategories(): HasMany
    {
        return $this->hasMany(self::class, "parent_id");
    }

    public function tools(): HasMany
    {
        return $this->hasMany(Tool::class, "graph_category_id");
    }

    public function scopeWithoutParent(Builder $query): Builder
    {
        return $query->where("parent_id", null);
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope("order", function (Builder $builder) {
            $builder->orderBy("order", "ASC");
        });
    }
}
