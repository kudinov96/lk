<?php

namespace App\Models;

use App\Enums\TelegramMessageFrom;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int    $id
 * @property int    $user_id
 * @property string $text
 * @property string $from
 * @property bool   $is_read
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class TelegramMessage extends Model
{
    protected $table = "telegram_message";

    protected $guarded = [
        "id",
    ];

    protected $casts = [
        "from" => TelegramMessageFrom::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id");
    }
}
