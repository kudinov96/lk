<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property string $title
 * @property int    $count
 * @property int    $user_id
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class PaymentHistory extends Model
{
    protected $table = "payment_history";

    protected $guarded = [
        "id",
    ];
}
