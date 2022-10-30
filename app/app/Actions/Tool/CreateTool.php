<?php

namespace App\Actions\Tool;

use App\Models\Tool;

class CreateTool
{
    public function handle(array $data): Tool
    {
        $item                    = new Tool();
        $item->title             = $data["title"];
        $item->graph_category_id = $data["graph_category_id"];
        $item->data              = $data["data"];
        $item->order             = $data["order"] ?? Tool::query()->max("order") + 1;

        $item->save();

        return $item;
    }
}
