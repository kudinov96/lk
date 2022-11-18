<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait PriceAfterPersonalDiscount
{
    protected function actualPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->price_discount ?? $this->price,
        );
    }

    protected function priceAfterPersonalDiscount(): Attribute
    {
        $user     = auth()->user();
        $discount = $user->discounts()->where([
            ["service_id", $this->id],
            ["service_type", self::class],
        ])->first();

        if ($discount) {
            $price = $this->actual_price - ($this->actual_price / 100 * $discount->count);
        } else {
            $price = $this->actual_price;
        }

        return Attribute::make(
            get: fn () => [
                "price"    => (int) $price,
                "discount" => $discount->count ?? null,
            ],
        );
    }
}
