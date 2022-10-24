<?php

namespace App\Http\Controllers\Admin;

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

        return response()->view("admin.graphs", compact("graphCategories"));
    }
}
