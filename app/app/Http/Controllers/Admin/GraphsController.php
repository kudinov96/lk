<?php

namespace App\Http\Controllers\Admin;

use App\Enums\IntervalCodeEnum;
use App\Http\Controllers\Controller;
use App\Models\GraphCategory;
use Illuminate\Http\Request;

class GraphsController extends Controller
{
    public function index()
    {
        $graphsJson = $this->getGraphJson();
        $intervalCodes       = IntervalCodeEnum::getArray();

        return response()->view("admin.graphs", compact("graphsJson", "intervalCodes"));
    }

    private function getGraphJson(): string
    {
        $graphCategories = GraphCategory::query()
            ->withoutParent()
            ->with("subcategories", "tools")
            ->get();

        $resultArray = [];
        foreach ($graphCategories as $graphCategory) {
            $children = [];

            foreach ($graphCategory->subcategories as $subcategory) {
                $tools = [];

                foreach ($subcategory->tools as $tool) {
                    $tools[]= [
                        "id"    => $tool->id,
                        "title" => $tool->title,
                        "type"  => "tool",
                        "data"  => $tool->data,
                    ];
                }

                $children[] = [
                    "id"       => $subcategory->id,
                    "title"    => $subcategory->title,
                    "type"     => "subcategory",
                    "children" => $tools,
                ];
            }

            $resultArray[] = [
                "id"       => $graphCategory->id,
                "title"    => $graphCategory->title,
                "type"     => "category",
                "children" => $children,
            ];
        }

        return json_encode($resultArray);
    }

    public function updateGraphs(Request $request)
    {
        dd($request->all());
        if (!$request->ajax()) {
            abort(404);
        }
    }
}
