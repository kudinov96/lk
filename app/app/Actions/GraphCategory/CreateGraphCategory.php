<?php

namespace App\Actions\GraphCategory;

use App\Models\GraphCategory;

class CreateGraphCategory
{
    public function handle(array $data): GraphCategory
    {
        $item               = new GraphCategory();
        $item->title        = $data["title"];
        $item->parent_id    = $data["parent_id"] ?? null;
        $item->color_title  = $data["color_title"] ?? null;
        $item->color_border = $data["color_border"] ?? null;
        $item->order        = $data["order"] ?? GraphCategory::query()->max("order") + 1;

        $item->save();

        return $item;
    }
}
