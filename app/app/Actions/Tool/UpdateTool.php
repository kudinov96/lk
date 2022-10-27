<?php

namespace App\Actions\Tool;

use App\Models\Tool;

class UpdateTool
{
    public function handle(Tool $item, array $data): Tool
    {
        $item->title             = $data["title"] ?? $item->title;
        $item->graph_category_id = $data["graph_category_id"];
        $item->data              = $data["data"] ?? $item->data;
        $item->order             = $data["order"] ?? $item->order;

        $item->save();

        return $item;
    }
}
