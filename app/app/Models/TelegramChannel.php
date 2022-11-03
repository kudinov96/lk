<?php

namespace App\Models;

use App\Models\Traits\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property string $title
 * @property int    $telegram_id
 * @property int    $order
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class TelegramChannel extends Model
{
    use Order;

    protected $table = "telegram_channel";

    protected $guarded = [
        "id",
    ];
}
