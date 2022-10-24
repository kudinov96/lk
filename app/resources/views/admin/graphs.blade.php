@extends('voyager::master')

@section('page_title', 'Графики')

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
                                <ol class="dd-list">
                                    @foreach($graphCategories as $graphCategory)
                                        <li class="dd-item" data-id="{{ $graphCategory->id }}" data-type="type1">
                                            <div class="pull-right item_actions">
                                                <div class="btn btn-sm btn-danger pull-right delete" data-id="{{ $graphCategory->id }}">
                                                    <i class="voyager-trash"></i> Удалить
                                                </div>
                                                <div class="btn btn-sm btn-primary pull-right edit" data-id="{{ $graphCategory->id }}" data-title="{{ $graphCategory->title }}" data-url="">
                                                    <i class="voyager-edit"></i> Изменить
                                                </div>
                                            </div>
                                            <div class="dd-handle">
                                                <span>{{ $graphCategory->title }}</span> <small class="url">Category</small>
                                            </div>

                                            @if($graphCategory->subcategories->count() > 0)
                                                <ol class="dd-list">
                                                    @foreach($graphCategory->subcategories as $subcategory)
                                                        <li class="dd-item" data-id="{{ $subcategory->id }}" data-type="type2">
                                                            <div class="pull-right item_actions">
                                                                <div class="btn btn-sm btn-danger pull-right delete" data-id="{{ $subcategory->id }}">
                                                                    <i class="voyager-trash"></i> Удалить
                                                                </div>
                                                                <div class="btn btn-sm btn-primary pull-right edit" data-id="{{ $subcategory->id }}" data-title="{{ $subcategory->title }}" data-url="">
                                                                    <i class="voyager-edit"></i> Изменить
                                                                </div>
                                                            </div>
                                                            <div class="dd-handle">
                                                                <span>{{ $subcategory->title }}</span> <small class="url">Subcategory</small>
                                                            </div>

                                                            @if($subcategory->tools->count() > 0)
                                                                <ol class="dd-list">
                                                                    @foreach($subcategory->tools as $tool)
                                                                        <li class="dd-item" data-id="{{ $tool->id }}" data-type="type3">
                                                                            <div class="pull-right item_actions">
                                                                                <div class="btn btn-sm btn-danger pull-right delete" data-id="{{ $tool->id }}">
                                                                                    <i class="voyager-trash"></i> Удалить
                                                                                </div>
                                                                                <div class="btn btn-sm btn-primary pull-right edit" data-id="{{ $tool->id }}" data-title="{{ $tool->title }}" data-url="">
                                                                                    <i class="voyager-edit"></i> Изменить
                                                                                </div>
                                                                            </div>
                                                                            <div class="dd-handle">
                                                                                <span>{{ $tool->title }}</span> <small class="url">Tool</small>
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
                onDragStart: function (l, e) {
                    var type = $(e).data('type');

                    switch (type) {
                        case 'type1':

                            break;
                        case 'type2':

                            break;

                        case 'type3':
                            return false;
                        default:
                            console.error("Invalid type");
                    }
                }
            });

        });
    </script>
@stop


