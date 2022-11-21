<?php

namespace App\Models;

use App\Models\Traits\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int    $id
 * @property string $title
 * @property string $content
 * @property bool   $is_test
 * @property string $color
 * @property string $icon
 * @property int    $order
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Subscription extends Model
{
    use Order;

    protected $table = "subscription";

    protected $guarded = [
        "id",
    ];

    protected function dateEnd(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse($this->pivot->date_end)->format("d.m.Y") ?? null,
        );
    }

    protected function daysLeftHuman(): Attribute
    {
        $daysLeft = $this->pivot ? Carbon::parse($this->pivot->date_end)->diffInDays(now()) : null;

        return Attribute::make(
            get: fn () => $daysLeft ? num_declension($daysLeft, ["остался", "осталось", "осталось"]) . " " . $daysLeft . " " . num_declension($daysLeft, ["день", "дня", "дней"]): null,
        );
    }

    public function periods(): BelongsToMany
    {
        return $this->belongsToMany(Period::class, "period_subscription")->withPivot("price", "is_default");
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
        return $this->belongsToMany(User::class, "subscription_users")->withPivot("date_start", "date_end", "is_auto_renewal", "auto_renewal_try");
    }

    public function scopeWithCategories(Builder $query): Builder
    {
        return $query->whereHas("graph_categories");
    }

    public function scopeWithoutCategories(Builder $query): Builder
    {
        return $query->whereDoesntHave("graph_categories");
    }
}
