<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    protected $appends = [
        "service_name",
    ];

    protected function serviceName(): Attribute
    {
        return Attribute::make(
            get: fn () => app($this->service_type)::find($this->service_id)->title ?? null,
        );
    }

    public function user()
    {
        $this->belongsTo(User::class);
    }
}
