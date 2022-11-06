<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property int    $count
 * @property int    $user_id
 * @property string $service_type
 * @property int    $service_id
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Discount extends Model
{
    protected $table = "discount";

    protected $guarded = [
        "id",
    ];

    public function user()
    {
        $this->belongsTo(User::class);
    }
}
