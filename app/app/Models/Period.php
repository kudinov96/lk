<?php

namespace App\Models;

use App\Models\Traits\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int    $id
 * @property int    $count
 * @property string $count_name
 * @property int    $order
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property string $full_count_name
 * @property string $full_count_name_human
 */
class Period extends Model
{
    use Order;

    protected $table = "period";

    protected $guarded = [
        "id",
    ];

    protected $appends = [
        "full_count_name",
        "full_count_name_human",
    ];

    protected function fullCountName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->count . "-" . $this->count_name,
        );
    }

    protected function fullCountNameHuman(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->count . " " . num_declension($this->count, __("enums." . $this->count_name)),
        );
    }

    public function fullDescription(int $subscription_id): string
    {
        $priceAfterDiscount = $this->priceAfterDiscount($subscription_id);

        if (!$priceAfterDiscount["discount"]) {
            return $this->full_count_name_human . " — " . $this->pivot->price . " руб.";
        }

        return $priceAfterDiscount["price"] . " руб. (скидка " . $priceAfterDiscount["discount"] . "%)";
    }

    public function fullPaymentDescription(int $subscription_id): string
    {
        $subscription = Subscription::findOrFail($subscription_id);
        return "Заказ подписки \"$subscription->title\": $this->full_count_name_human " . $this->fullDescription($subscription_id);
    }

    public function priceAfterDiscount(int $subscription_id): array
    {
        $user     = auth()->user();
        $discount = $user->discounts()->where([
            ["service_id", $subscription_id],
            ["service_type", Subscription::class],
        ])->first();

        if ($discount) {
            $price = $this->pivot->price - ($this->pivot->price / 100 * $discount->count);
        } else {
            $price = $this->pivot->price;
        }

        return [
            "price"    => (int) $price,
            "discount" => $discount->count ?? null,
        ];
    }

    public function subscriptions(): BelongsToMany
    {
        return $this->belongsToMany(Subscription::class, "period_subscription")->withPivot("price");
    }

    public static function findByCountName($count, $count_name)
    {
        return Period::query()
            ->where([
                ["count", $count],
                ["count_name", $count_name],
            ])
            ->first();
    }
}
