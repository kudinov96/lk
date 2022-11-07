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
 * @property string $color
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

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, "subscription_users")->withPivot("date_start", "date_end", "is_auto_renewal");
    }
}
