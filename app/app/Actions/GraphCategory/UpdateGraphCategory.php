<?php

namespace App\Actions\GraphCategory;

use App\Models\GraphCategory;

class UpdateGraphCategory
{
    public function handle(GraphCategory $item, array $data): GraphCategory
    {
        $item->title        = $data["title"] ?? $item->title;
        $item->parent_id    = $data["parent_id"];
        $item->color_title  = $data["color_title"] ?? $item->color_title;
        $item->color_border = $data["color_border"] ?? $item->color_border;
        $item->order        = $data["order"] ?? $item->order;

        $item->save();

        return $item;
    }
}
