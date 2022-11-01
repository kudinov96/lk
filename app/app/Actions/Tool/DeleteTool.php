<?php

namespace App\Actions\Tool;

use App\Models\Tool;

class DeleteTool
{
    public function handle(Tool $item): void
    {
        $item->delete();
    }
}
