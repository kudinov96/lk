<?php

namespace App\Models;

use App\Models\Traits\Order;
use App\Models\Traits\PriceAfterPersonalDiscount;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property string $title
 * @property int    $price
 * @property int    $price_discount
 * @property string $link
 * @property string $preview
 * @property int    $order
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Course extends Model
{
    use Order;
    use PriceAfterPersonalDiscount;

    protected $table = "course";

    protected $guarded = [
        "id",
    ];

    protected function dateStart(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse($this->pivot->date_start)->format("d.m.Y") ?? null,
        );
    }
}
