<?php

namespace App\Http\Controllers\Admin;

use App\Actions\GraphCategory\CreateGraphCategory;
use App\Actions\GraphCategory\DeleteGraphCategory;
use App\Actions\GraphCategory\UpdateGraphCategory;
use App\Actions\Tool\DeleteTool;
use App\Actions\Tool\UpdateTool;
use App\Enums\GraphTypeEnum;
use App\Enums\IntervalCodeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\GraphCategoryRequest;
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

    public function createGraphs(GraphCategoryRequest $request, CreateGraphCategory $createGraphCategory): array
    {
        if (!$request->ajax()) {
            abort(404);
        }

        $type = $request->input("type") ?? "";

        if ($type === GraphTypeEnum::CATEGORY->value || $type === GraphTypeEnum::SUBCATEGORY->value) {
            $item = $createGraphCategory->handle($request->all());
        }

        return [
            "success" => true,
            "item"    => [
                "id"           => $item->id,
                "parent_id"    => $item->parent_id,
                "type"         => $type,
                "title"        => $item->title,
                "color_title"  => $item->color_title,
                "color_border" => $item->color_border,
            ],
        ];
    }

    public function deleteGraphs(Request $request, DeleteTool $deleteTool, DeleteGraphCategory $deleteGraphCategory): array
    {
        if (!$request->ajax()) {
            abort(404);
        }

        $id   = (int) $request->input("id") ?? "";
        $type = $request->input("type") ?? "";

        if ($type === GraphTypeEnum::CATEGORY->value || $type === GraphTypeEnum::SUBCATEGORY->value) {
            $item = GraphCategory::findOrFail($id);

            $deleteGraphCategory->handle($item);
        }

        if ($type === GraphTypeEnum::TOOL->value) {
            $item = Tool::findOrFail($id);

            $deleteTool->handle($item);
        }

        return [
            "success" => true,
        ];
    }

    public function orderGraphs(Request $request, UpdateGraphCategory $updateGraphCategory, UpdateTool $updateTool): array
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
            $categoryModel = GraphCategory::find($category->modelId);

            $updateGraphCategory->handle($categoryModel, [
                "parent_id" => null,
                "order"     => $order,
            ]);

            if (isset($category->children)) {
                foreach ($category->children as $category_children) {
                    $order++;

                    if ($category_children->type === GraphTypeEnum::SUBCATEGORY->value) {
                        $subcategoryModel = GraphCategory::find($category_children->modelId);

                        $updateGraphCategory->handle($subcategoryModel, [
                            "parent_id" => $category->modelId,
                            "order"     => $order,
                        ]);

                        if (isset($category_children->children)) {
                            foreach ($category_children->children as $tool) {
                                $order++;
                                $toolModel = Tool::find($tool->modelId);

                                $updateTool->handle($toolModel, [
                                    "graph_category_id" => $category_children->modelId,
                                    "order"             => $order,
                                ]);
                            }
                        }
                    } elseif ($category_children->type === GraphTypeEnum::TOOL->value) {
                        $toolModel = Tool::find($category_children->modelId);

                        $updateTool->handle($toolModel, [
                            "graph_category_id" => $category->modelId,
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
                            "type"  => GraphTypeEnum::TOOL->value,
                            "data"  => $tool->data,
                        ];
                    }

                    $children[] = [
                        "id"           => $children_item->id,
                        "title"        => $children_item->title,
                        "color_title"  => $children_item->color_title,
                        "color_border" => $children_item->color_border,
                        "type"         => GraphTypeEnum::SUBCATEGORY->value,
                        "children"     => $tools,
                    ];
                } elseif ($children_item->getMorphClass() === Tool::class) {
                    $children[] = [
                        "id"    => $children_item->id,
                        "title" => $children_item->title,
                        "type"  => GraphTypeEnum::TOOL->value,
                        "data"  => $children_item->data,
                    ];
                }
            }

            $resultArray[] = [
                "id"           => $category->id,
                "title"        => $category->title,
                "color_title"  => $category->color_title,
                "color_border" => $category->color_border,
                "type"         => GraphTypeEnum::CATEGORY->value,
                "children"     => $children,
            ];
        }

        return json_encode($resultArray);
    }
}
