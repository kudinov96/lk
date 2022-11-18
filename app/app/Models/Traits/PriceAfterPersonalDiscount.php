<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait PriceAfterPersonalDiscount
{
    protected function priceAfterPersonalDiscount(): Attribute
    {
        $user     = auth()->user();
        $discount = $user->discounts()->where([
            ["service_id", $this->id],
            ["service_type", self::class],
        ])->first();

        if ($discount) {
            $price = $this->pivot->price_discount - ($this->pivot->price_discount / 100 * $discount->count);
        } else {
            $price = $this->pivot->price_discount;
        }

        return Attribute::make(
            get: fn () => [
                "price"    => (int) $price,
                "discount" => $discount->count ?? null,
            ],
        );
    }
}
