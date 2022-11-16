<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\TelegramMessageFrom;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property string $name
 * @property string $email
 * @property string $email_verified_at
 * @property string $password
 * @property string $telegram_name
 * @property int    $telegram_id
 * @property bool   $is_ban
 */
class User extends \TCG\Voyager\Models\User
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'telegram_id',
        'telegram_name',
        'is_ban',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    public function subscriptions(): BelongsToMany
    {
        return $this->belongsToMany(Subscription::class, "subscription_users")->withPivot("date_start", "date_end", "is_auto_renewal");
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, "course_users")->withPivot("date_start");
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, "service_users")->withPivot("date_start");
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(Discount::class, "user_id");
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, "user_id");
    }

    public function telegram_messages(): HasMany
    {
        return $this->hasMany(TelegramMessage::class, "user_id");
    }

    public function hasNotReadTelegramMessages(): bool
    {
        return $this->telegram_messages()
            ->where([
                ["is_read", false],
                ["from", TelegramMessageFrom::USER->value],
            ])
            ->exists();
    }
}
