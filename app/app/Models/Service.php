<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property string $title
 * @property int    $price
 * @property int    $price_discount
 * @property string $content
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Service extends Model
{
    protected $table = "services";

    protected $guarded = [
        "id",
    ];
}
