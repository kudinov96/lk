<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property string $title
 * @property int    $price
 * @property int    $price_discount
 * @property string $content
 * @property string $preview
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Service extends Model
{
    protected $table = "service";

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
