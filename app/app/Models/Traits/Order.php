<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Order
{
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope("order", function (Builder $builder) {
            $builder->orderBy("order", "ASC");
        });
    }
}
