<?php

namespace App\Http\Controllers\Admin;

use App\Actions\GraphCategory\CreateGraphCategory;
use App\Actions\GraphCategory\DeleteGraphCategory;
use App\Actions\GraphCategory\UpdateGraphCategory;
use App\Actions\Tool\CreateTool;
use App\Actions\Tool\DeleteTool;
use App\Actions\Tool\UpdateTool;
use App\Enums\GraphTypeEnum;
use App\Enums\IntervalCodeEnum;
use App\Http\Controllers\Controller;
use App\Models\GraphCategory;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class GraphsController extends Controller
{
    public function index()
    {
        $graphsJson    = $this->getGraphsJson();
        $intervalCodes = IntervalCodeEnum::getArray();

        return response()->view("admin.graphs", compact("graphsJson", "intervalCodes"));
    }

    public function createGraphs(Request $request, CreateGraphCategory $createGraphCategory, CreateTool $createTool): array
    {
        if (!$request->ajax()) {
            abort(404);
        }

        $type = $request->input("type") ?? "";

        if ($type === GraphTypeEnum::TOOL->value) {
            $request->validate([
                "title"                => "required",
                "graph_category_id"    => "nullable|integer|exists:App\Models\GraphCategory,id",
                "data"                 => "required|array",
                "data.*.interval"      => "required|integer",
                "data.*.interval_code" => ["required", new Enum(IntervalCodeEnum::class)],
                "data.*.url"           => "required|url",
            ]);

            /*$jsonData = [];
            foreach ($request->input("data") as $data_item) {
               $jsonData[$data_item["interval_code"]] = [
                   "interval" => $data_item["interval"],
                   "url"      => $data_item["url"],
               ];
            }*/

            /*$request->merge([
                "data" => $jsonData,
            ]);*/

            $item = $createTool->handle($request->only([
                "title",
                "graph_category_id",
                "data",
            ]));

            return [
                "success" => true,
                "item"    => [
                    "id"                => $item->id,
                    "title"             => $item->title,
                    "graph_category_id" => $item->graph_category_id,
                    "data"              => $item->data,
                    "type"              => $type,
                ],
            ];
        }

        if ($type === GraphTypeEnum::CATEGORY->value || $type === GraphTypeEnum::SUBCATEGORY->value) {
            $request->validate([
                "title"        => "required",
                "parent_id"    => "nullable|integer|exists:App\Models\GraphCategory,id",
                "color_title"  => "required",
                "color_border" => "required",
            ]);

            $item = $createGraphCategory->handle($request->only([
                "title",
                "parent_id",
                "color_title",
                "color_border",
            ]));
        }

        return [
            "success" => true,
            "item"    => [
                "id"           => $item->id,
                "title"        => $item->title,
                "parent_id"    => $item->parent_id,
                "color_title"  => $item->color_title,
                "color_border" => $item->color_border,
                "type"         => $type,
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
