<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int    $id
 * @property string $title
 * @property string $content
 * @property bool   $is_test
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Subscription extends Model
{
    protected $table = "subscription";

    protected $guarded = [
        "id",
    ];

    public function periods(): BelongsToMany
    {
        return $this->belongsToMany(Period::class, "period_subscription")->withPivot("price");
    }

    public function graph_categories(): BelongsToMany
    {
        return $this->belongsToMany(GraphCategory::class, "graph_category_subscription");
    }

    public function telegram_channels(): BelongsToMany
    {
        return $this->belongsToMany(TelegramChannel::class, "subscription_telegram_channel");
    }
}
