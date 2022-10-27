<?php

namespace App\Http\Controllers\Admin;

use App\Actions\GraphCategory\UpdateGraphCategory;
use App\Actions\Tool\UpdateTool;
use App\Enums\IntervalCodeEnum;
use App\Http\Controllers\Controller;
use App\Models\GraphCategory;
use App\Models\Tool;
use Illuminate\Http\Request;

class GraphsController extends Controller
{
    public function index()
    {
        $graphsJson    = $this->getGraphsJson();
        $intervalCodes = IntervalCodeEnum::getArray();

        return response()->view("admin.graphs", compact("graphsJson", "intervalCodes"));
    }

    public function updateGraphs(Request $request, UpdateGraphCategory $updateGraphCategory, UpdateTool $updateTool)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        $newGraphsJson = json_decode($request->input("newGraphsJson")) ?? [];
        $graphsJson    = json_decode($this->getGraphsJson()) ?? [];

        if ($newGraphsJson == $graphsJson) {
            return [
                "success" => true,
                "error"   => "Nothing to change",
            ];
        }

        $order = 0;
        foreach ($newGraphsJson as $category) {
            $categoryModel = GraphCategory::find($category->id);

            $updateGraphCategory->handle($categoryModel, [
                "title"     => $category->title,
                "parent_id" => null,
                "order"     => $order,
            ]);


            if (isset($category->children)) {
                foreach ($category->children as $category_children) {
                    $order++;

                    if ($category_children->type === "subcategory") {
                        $subcategoryModel = GraphCategory::find($category_children->id);

                        $updateGraphCategory->handle($subcategoryModel, [
                            "title"     => $category_children->title,
                            "parent_id" => $category->id,
                            "order"     => $order,
                        ]);

                        if (isset($category_children->children)) {
                            foreach ($category_children->children as $tool) {
                                $order++;
                                $toolModel = Tool::find($tool->id);

                                $updateTool->handle($toolModel, [
                                    "title"             => $tool->title,
                                    "data"              => $tool->data,
                                    "graph_category_id" => $category_children->id,
                                    "order"             => $order,
                                ]);
                            }
                        }
                    } elseif ($category_children->type === "tool") {
                        $toolModel = Tool::find($category_children->id);

                        $updateTool->handle($toolModel, [
                            "title"             => $category_children->title,
                            "data"              => $category_children->data,
                            "graph_category_id" => $category->id,
                            "order"             => $order,
                        ]);
                    }
                }
            }
            $order++;
        }

        return [
            "success" => true,
        ];
    }

    private function getGraphsJson(): string
    {
        $graphCategories = GraphCategory::query()
            ->withoutParent()
            ->with("subcategories", "tools")
            ->get();

        $resultArray = [];
        foreach ($graphCategories as $category) {
            $childrenModels = $category->subcategories->merge($category->tools)->sortBy("order");
            $children       = [];

            foreach ($childrenModels as $children_item) {
                if ($children_item->getMorphClass() === GraphCategory::class) {
                    $tools = [];

                    foreach ($children_item->tools as $tool) {
                        $tools[] = [
                            "id"    => $tool->id,
                            "title" => $tool->title,
                            "type"  => "tool",
                            "data"  => $tool->data,
                        ];
                    }

                    $children[] = [
                        "id"       => $children_item->id,
                        "title"    => $children_item->title,
                        "type"     => "subcategory",
                        "children" => $tools,
                    ];
                } elseif ($children_item->getMorphClass() === Tool::class) {
                    $children[] = [
                        "id"    => $children_item->id,
                        "title" => $children_item->title,
                        "type"  => "tool",
                        "data"  => $children_item->data,
                    ];
                }
            }

            $resultArray[] = [
                "id"       => $category->id,
                "title"    => $category->title,
                "type"     => "category",
                "children" => $children,
            ];
        }

        return json_encode($resultArray);
    }
}
