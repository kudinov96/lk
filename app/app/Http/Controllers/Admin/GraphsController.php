<?php

namespace App\Http\Controllers\Admin;

use App\Enums\IntervalCodeEnum;
use App\Http\Controllers\Controller;
use App\Models\GraphCategory;

class GraphsController extends Controller
{
    public function index()
    {
        $graphCategories = GraphCategory::query()
            ->withoutParent()
            ->with("subcategories", "tools")
            ->get();

        $intervalCodes = IntervalCodeEnum::getArray();

        return response()->view("admin.graphs", compact("graphCategories", "intervalCodes"));
    }
}
