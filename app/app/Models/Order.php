<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * @property string $id
 * @property string $description
 * @property int    $amount
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $status
 * @property int    $user_id
 * @property int    $subscription_id
 * @property int    $period_id
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */

class Order extends Model
{
    protected $table = "order";

    protected function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id");
    }

    protected function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class, "subscription_id");
    }

    public function scopeOnlyConfirmed(Builder $query): Builder
    {
        return $query->where("status", OrderStatus::CONFIRMED->value);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            $post->{$post->getKeyName()} = (string) Str::uuid();
        });
    }

    public function getIncrementing(): bool
    {
        return false;
    }

    public function getKeyType(): string
    {
        return "string";
    }
}
