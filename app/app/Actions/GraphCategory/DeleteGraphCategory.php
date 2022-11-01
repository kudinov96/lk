<?php

namespace App\Actions\GraphCategory;

use App\Models\GraphCategory;
use Illuminate\Support\Facades\DB;

class DeleteGraphCategory
{
    public function handle(GraphCategory $item): void
    {
        DB::transaction(function () use ($item) {
            $item->subcategories()->delete();
            $item->tools()->delete();

            $item->delete();
        });
    }
}
