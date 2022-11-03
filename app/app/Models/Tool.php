<?php

namespace App\Models;

use App\Models\Traits\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int    $id
 * @property string $title
 * @property int    $graph_category_id
 * @property string $data
 * @property int    $order
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Tool extends Model
{
    use Order;

    protected $table = "tool";

    protected $guarded = [
        "id",
    ];

    protected $casts = [
        "data" => "array",
    ];

    public function graph_category(): BelongsTo
    {
        return $this->belongsTo(GraphCategory::class, "graph_category_id");
    }
}
