<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property string $title
 * @property int    $price
 * @property int    $price_discount
 * @property string $link
 * @property string $preview
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Course extends Model
{
    protected $table = "course";

    protected $guarded = [
        "id",
    ];
}
