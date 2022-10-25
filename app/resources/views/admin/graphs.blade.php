@extends('voyager::master')

@section('page_title', 'Графики')

@section('css')
    @vite('resources/scss/admin/admin.scss')
@stop

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-pie-graph"></i>Графики
    </h1>
@stop

@section('content')
    <div class="page-content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding:30px;">
                        @if($graphCategories)
                            <div class="dd">
                                <ol class="dd-list dd-list_categories">
                                    @foreach($graphCategories as $graphCategory)
                                        <li class="dd-item" data-id="category-{{ $graphCategory->id }}" data-type="category">
                                            <div class="pull-right item_actions">
                                                <div class="btn btn-sm btn-danger pull-right delete" data-id="{{ $graphCategory->id }}">
                                                    <i class="voyager-trash"></i> Удалить
                                                </div>
                                            </div>
                                            <div class="dd-handle">
                                                <span>{{ $graphCategory->title }}</span> <small class="url">Category</small>
                                            </div>

                                            @if($graphCategory->subcategories->count() > 0)
                                                <ol class="dd-list">
                                                    @foreach($graphCategory->subcategories as $subcategory)
                                                        <li class="dd-item" data-id="subcategory-{{ $subcategory->id }}" data-type="subcategory">
                                                            <div class="pull-right item_actions">
                                                                <div class="btn btn-sm btn-danger pull-right delete" data-id="{{ $subcategory->id }}">
                                                                    <i class="voyager-trash"></i> Удалить
                                                                </div>
                                                            </div>
                                                            <div class="dd-handle">
                                                                <span>{{ $subcategory->title }}</span> <small class="url">Subcategory</small>
                                                            </div>

                                                            @if($subcategory->tools->count() > 0)
                                                                <ol class="dd-list">
                                                                    @foreach($subcategory->tools as $tool)
                                                                        <li class="dd-item" data-id="tool-{{ $tool->id }}" data-type="tool">
                                                                            <div class="pull-right item_actions">
                                                                                <div class="btn btn-sm btn-danger pull-right delete" data-id="{{ $tool->id }}">
                                                                                    <i class="voyager-trash"></i> Удалить
                                                                                </div>
                                                                            </div>
                                                                            <div class="dd-handle">
                                                                                <span>{{ $tool->title }}</span> <small class="url">Tool</small>
                                                                            </div>
                                                                            <div class="dd-tools">
                                                                                @foreach($intervalCodes as $key => $code)
                                                                                    <div class="dd-tools__item">
                                                                                        <input type="text" name="interval" value="{{ $tool->data[\App\Enums\IntervalCodeEnum::from($key)->value]["interval"] ?? "" }}" class="form-control dd-tools__item-interval">
                                                                                        <select name="interval_code" class="form-control dd-tools__item-interval-code">
                                                                                            @foreach($intervalCodes as $k => $v)
                                                                                                <option value="<?php echo $v; ?>" @if($k === \App\Enums\IntervalCodeEnum::from($key)->value) selected @endif><?php echo $v; ?></option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                        <input type="text" name="url" value="{{ $tool->data[\App\Enums\IntervalCodeEnum::from($key)->value]["url"] ?? "" }}" class="form-control dd-tools__item-url" placeholder="Url">
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        </li>
                                                                    @endforeach
                                                                </ol>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ol>
                                            @endif
                                        </li>
                                    @endforeach
                                </ol>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('javascript')

    <script>
        $(document).ready(function () {

            $('.dd').nestable({
                expandBtnHTML: '',
                collapseBtnHTML: '',
                maxDepth: 3,
                beforeDragStop: function (l, e, p) {
                    let type        = $(e).data('type');
                    let parent_type = $(p).closest(".dd-item").data('type');

                    switch (type) {
                        case 'category':
                            if (parent_type === "category" || parent_type === "subcategory" || parent_type === "tool") {
                                return false;
                            }
                            break;

                        case 'subcategory':
                            if (parent_type === "category" || parent_type === "subcategory" || parent_type === "tool" || $(p).hasClass("dd-list_categories")) {
                                return false;
                            }
                            break;

                        case 'tool':
                            if (parent_type === "tool" || $(p).hasClass("dd-list_categories")) {
                                return false;
                            }
                            break;
                        default:
                            console.error("Invalid type");
                    }
                }
            });

        });
    </script>
@stop


