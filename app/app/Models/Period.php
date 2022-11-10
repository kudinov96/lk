<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property int    $count
 * @property string $count_name
 * @property int    $price
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property string $full_count_name
 * @property string $full_count_name_human
 */
class Period extends Model
{
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
