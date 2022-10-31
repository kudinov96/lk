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
                        <div class="dd" id="dd-nestable">
                            <ol class="dd-list dd-list_categories" id="dd-output"></ol>
                            <div class="btn btn-success add_item" data-type="category"><i class="voyager-plus"></i> Добавить категорию</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-trash"></i> Это действие удалит также все вложенные элементы.</h4>
                </div>
                <div class="modal-footer">
                    <form id="delete_form" method="POST">
                        <input type="hidden" name="model_id" value="">
                        <input type="hidden" name="type" value="">
                        <input type="submit" class="btn btn-danger pull-right delete-confirm" value="Удалить">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal modal-info fade" tabindex="-1" id="add_item_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title modal-title__category hidden"><i class="voyager-plus"></i> Добавить категорию</h4>
                    <h4 class="modal-title modal-title__subcategory hidden"><i class="voyager-plus"></i> Добавить подкатегорию</h4>
                    <h4 class="modal-title modal-title__tool hidden"><i class="voyager-plus"></i> Добавить инструмент</h4>
                </div>
                <form id="add_item_form" method="POST">
                    @method("POST")
                    <input type="hidden" name="type">
                    <input type="hidden" name="parent_id">
                    <input type="hidden" name="parent_full_id">

                    <div class="modal-body">
                        <div>
                            <label for="title">Заголовок</label>
                            <input type="text" class="form-control" id="title" name="title" required><br>
                        </div>
                        <div class="modal-field modal-field__category modal-field__subcategory hidden">
                            <label for="color_title">Цвет заголовка</label>
                            <input type="color" class="form-control" id="color_title" name="color_title" required><br>
                        </div>
                        <div class="modal-field modal-field__category modal-field__subcategory hidden">
                            <label for="color_border">Цвет рамки</label>
                            <input type="color" class="form-control" id="color_border" name="color_border" required><br>
                        </div>
                        <div class="modal-field modal-field__tool hidden">
                            <input type="hidden" name="graph_category_id">
                            <div class="dd-graphs">
                                @php($i = 0)
                                @foreach($intervalCodes as $index_code)
                                    <div class="dd-graphs__item">
                                        <input type="text" name="data[{{ $i }}][interval]" class="form-control dd-graphs__item-interval">
                                        <select name="data[{{ $i }}][interval_code]" class="form-control dd-graphs__item-interval-code">
                                            @foreach($intervalCodes as $v)
                                                <option value="{{ $v }}" @if($index_code === $v) selected @endif>{{ $v }}</option>
                                            @endforeach
                                        </select>
                                        <input type="text" name="data[{{ $i }}][url]" class="form-control dd-graphs__item-url" placeholder="Url">
                                    </div>
                                    @php($i++)
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-success pull-right delete-confirm__" value="Создать">
                        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal modal-info fade" tabindex="-1" id="edit_item_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title modal-title__category hidden"><i class="voyager-plus"></i> Редактировать категорию</h4>
                    <h4 class="modal-title modal-title__subcategory hidden"><i class="voyager-plus"></i> Редактировать подкатегорию</h4>
                    <h4 class="modal-title modal-title__tool hidden"><i class="voyager-plus"></i> Редактировать инструмент</h4>
                </div>
                <form id="edit_item_form" method="POST">
                    @method("PUT")
                    <input type="hidden" name="type">
                    <input type="hidden" name="id">

                    <div class="modal-body">
                        <div>
                            <label for="title">Заголовок</label>
                            <input type="text" class="form-control" id="title" name="title" required><br>
                        </div>
                        <div class="modal-field modal-field__category modal-field__subcategory hidden">
                            <label for="color_title">Цвет заголовка</label>
                            <input type="color" class="form-control" id="color_title" name="color_title" required><br>
                        </div>
                        <div class="modal-field modal-field__category modal-field__subcategory hidden">
                            <label for="color_border">Цвет рамки</label>
                            <input type="color" class="form-control" id="color_border" name="color_border" required><br>
                        </div>
                        <div class="modal-field modal-field__tool hidden">
                            <div class="dd-graphs">
                                @php($i = 0)
                                @foreach($intervalCodes as $index_code)
                                    <div class="dd-graphs__item">
                                        <input type="text" name="data[{{ $i }}][interval]" class="form-control dd-graphs__item-interval">
                                        <select name="data[{{ $i }}][interval_code]" class="form-control dd-graphs__item-interval-code">
                                            @foreach($intervalCodes as $v)
                                                <option value="{{ $v }}" @if($index_code === $v) selected @endif>{{ $v }}</option>
                                            @endforeach
                                        </select>
                                        <input type="text" name="data[{{ $i }}][url]" class="form-control dd-graphs__item-url" placeholder="Url">
                                    </div>
                                    @php($i++)
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-success pull-right delete-confirm__" value="Редактировать">
                        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@stop

@section('javascript')

    <script>
        $(document).ready(function () {

            let graphsJson    = @json($graphsJson);
            let intervalCodes = @json($intervalCodes);
            let output        = '';

            let updateOutput = function(e) {
                let list   = e.length ? e : $(e.target);
                let output = list.data('output');
                let newGraphsJson   = window.JSON.stringify(list.nestable('serialize'));

                $.ajax({
                    url: "{{ route("voyager.graph.order") }}",
                    type: "PUT",
                    data: {
                        newGraphsJson,
                    },
                    success: function(response) {
                        console.log(response);
                    },
                });
            };

            function buildItem(item) {
                let html = '<li class="dd-item" data-id="' + item.type + '-' + item.id + '" data-model-id="' + item.id + '" data-type="' + item.type + '">';

                html += '<div class="pull-right item_actions">';
                     if (item.type === "category") {
                         html += '<div class="btn btn-success add_item" data-type="subcategory" data-parent-id="' + item.id + '" data-parent-full-id="' + item.type + '-' + item.id + '"><i class="voyager-plus"></i></div>';
                         html += '<div class="btn btn-sm btn-primary edit" ' +
                             'data-title="' + item.title + '" ' +
                             'data-color-title="' + item.color_title + '" ' +
                             'data-color-border="' + item.color_border + '" ' +
                             'data-type="category" ' +
                             'data-model-id="' + item.id + '" ><i class="voyager-edit"></i></div>';
                     }

                     if (item.type === "subcategory") {
                         html += '<div class="btn btn-success add_item" data-type="tool" data-graph-category-id="' + item.id + '" data-parent-full-id="' + item.type + '-' + item.id + '"><i class="voyager-plus"></i></div>';
                         html += '<div class="btn btn-sm btn-primary edit" ' +
                             'data-title="' + item.title + '" ' +
                             'data-color-title="' + item.color_title + '" ' +
                             'data-color-border="' + item.color_border + '" ' +
                             'data-type="subcategory" ' +
                             'data-model-id="' + item.id + '" ><i class="voyager-edit"></i></div>';
                     }

                    if (item.type === "tool") {
                        html += '<div class="btn btn-sm btn-primary edit" ' +
                            'data-title="' + item.title + '" ' +
                            'data-data=\'' + JSON.stringify(item.data) + '\' ' +
                            'data-type="tool" ' +
                            'data-model-id="' + item.id + '" ><i class="voyager-edit"></i></div>';
                    }

                     html +='<div class="btn btn-sm btn-danger pull-right delete">' +
                                '<i class="voyager-trash"></i> Удалить' +
                            '</div>' +
                        '</div>' +
                        '<div class="dd-handle">' +
                            '<span class="dd-graphs__item-title">' + item.title + '</span>';
                            if (item.type !== "tool") {
                                html += '<span class="dd-graphs__item-color">Заголовок <span class="dd-graphs__item-color-title" style="background-color: ' + item.color_title + '"></span></span>' +
                                '<span class="dd-graphs__item-color">Рамка <span class="dd-graphs__item-color-border" style="background-color: ' + item.color_border + '"></span></span>';
                            }
                html += '</div>';

                if (item.type === "tool") {
                    html += '<div class="dd-graphs">';
                    $.each(item.data, function (index, index_data) {
                        let interval      = index_data.interval ?? "";
                        let interval_code = index_data.interval_code ?? "";
                        let url           = index_data.url ?? "";

                        html += '<div class="dd-graphs__item">' +
                                    '<input readonly type="text" name="data[' + index + '][interval]" value="' + interval + '" class="form-control dd-graphs__item-interval">' +
                                    '<select disabled name="data[' + index + '][interval_code]" class="form-control dd-graphs__item-interval-code">';
                                        $.each(intervalCodes, function (k, v) {
                                            let selected = false;

                                            if (interval_code === v) {
                                                selected = " selected";
                                            }
                                            html += '<option value="' + v + '"' + selected + '>' + v + '</option>';
                                        });
                                html += '</select>' +
                                    '<input readonly type="text" name="data[' + index + '][url]" value="' + url + '" class="form-control dd-graphs__item-url" placeholder="Url">' +
                                '</div>';
                    });
                    html += '</div>';
                }

                html += "<ol class='dd-list'>";
                if (item.children) {
                    $.each(item.children, function (index, sub) {
                        html += buildItem(sub);
                    });
                }
                html += "</ol>";

                html += '</li>';

                return html;
            }

            $.each(JSON.parse(graphsJson), function (index, item) {
                output += buildItem(item);
            });

            $("#dd-output").html(output);
            $("#dd-nestable").nestable({
                expandBtnHTML: "",
                collapseBtnHTML: "",
                maxDepth: 3,
                beforeDragStop: function (l, e, p) {
                    let type        = $(e).data("type");
                    let parent_type = $(p).closest(".dd-item").data("type");

                    switch (type) {
                        case "category":
                            if (parent_type === "subcategory" || parent_type === "tool") {
                                return false;
                            }

                            if (parent_type === "category") {
                                if ($(e).find('.dd-item[data-type="subcategory"]').length === 0) {
                                    $(e).data("type", "subcategory");
                                    $(e).attr("data-type", "subcategory");
                                } else {
                                    return false;
                                }
                            }
                            break;

                        case "subcategory":
                            if (parent_type === "subcategory" || parent_type === "tool") {
                                return false;
                            }

                            if ($(p).hasClass("dd-list_categories")) {
                                $(e).data("type", "category");
                                $(e).attr("data-type", "category");
                            }
                            break;

                        case "tool":
                            if (parent_type === "tool" || $(p).hasClass("dd-list_categories")) {
                                return false;
                            }
                            break;
                        default:
                            console.error("Invalid type");
                    }
                }
            }).on("change", updateOutput);

            // Delete item
            $(document).on("click", ".delete", function (e) {
                e.preventDefault();

                let $modal = $("#delete_modal");
                let id     = $(this).closest(".dd-item").data("model-id");
                let type   = $(this).closest(".dd-item").data("type");

                $modal.find('input[name="model_id"]').val(id);
                $modal.find('input[name="type"]').val(type);
                $modal.modal("show");
            });

            // Add item
            $(document).on("click", ".add_item", function() {
                let $modal            = $("#add_item_modal");
                let $form             = $("#add_item_form");
                let type              = $(this).data("type");
                let parent_id         = $(this).data("parent-id");
                let parent_full_id    = $(this).data("parent-full-id");
                let graph_category_id = $(this).data("graph-category-id");

                $form.trigger("reset");
                $form.find('input[name="type"]').val(type);
                $form.find('input[name="parent_id"]').val(parent_id);
                $form.find('input[name="parent_full_id"]').val(parent_full_id);
                $form.find('input[name="graph_category_id"]').val(graph_category_id);

                $modal.find(".modal-title").addClass("hidden");
                $modal.find(".modal-field").addClass("hidden");
                $modal.find(".modal-title__" + type).removeClass("hidden");
                $modal.find(".modal-field__" + type).removeClass("hidden");

                $modal.modal('show');
            });

            // Edit item
            $(document).on("click", ".edit", function() {
                let $modal            = $("#edit_item_modal");
                let $form             = $("#edit_item_form");
                let type              = $(this).data("type");
                let title             = $(this).data("title");
                let modelId           = $(this).data("model-id");
                let color_title       = $(this).data("color-title");
                let color_border      = $(this).data("color-border");
                let data              = $(this).data("data");

                $form.trigger("reset");
                $form.find('input[name="type"]').val(type);
                $form.find('input[name="title"]').val(title);
                $form.find('input[name="id"]').val(modelId);
                $form.find('input[name="color_title"]').val(color_title);
                $form.find('input[name="color_border"]').val(color_border);

                $.each(data, function(index, index_data){
                    $form.find('input[name="data[' + index + '][interval]"]').val(index_data.interval);
                    $form.find('select[name="data[' + index + '][interval_code]"]').val(index_data.interval_code);
                    $form.find('input[name="data[' + index + '][url]"]').val(index_data.url);
                });

                $modal.find(".modal-title").addClass("hidden");
                $modal.find(".modal-field").addClass("hidden");
                $modal.find(".modal-title__" + type).removeClass("hidden");
                $modal.find(".modal-field__" + type).removeClass("hidden");

                $modal.modal('show');
            });

            $("#delete_form").on("submit", function (e) {
                e.preventDefault();

                let $this  = $(this);
                let $modal = $("#delete_modal");
                let id     = $this.find('input[name="model_id"]').val();
                let type   = $this.find('input[name="type"]').val();

                $.ajax({
                    url: "{{ route("voyager.graph.delete") }}",
                    type: "DELETE",
                    data: {
                        id,
                        type
                    },
                    success: function(response) {
                        console.log(response);

                        if (response.success === true) {
                            $("#dd-nestable").nestable("remove", type + "-" + id);
                        }
                        $modal.modal("hide");
                    },
                });
            });

            function appendItem(item) {
                let $dd_list  = $("#dd-output").find('.dd-item[data-id="' + item.parent_full_id + '"] > ol.dd-list');

                if ($dd_list.length > 0) {
                    $dd_list.append(buildItem(item))
                } else {
                    let ol_html = "";

                    $dd_list = $("#dd-output").find('.dd-item[data-id="' + item.parent_full_id + '"]');
                    ol_html  = '<ol class="dd-list">'
                        ol_html += buildItem(item);
                    ol_html += '</ol>';

                    $dd_list.append(ol_html)
                }
            }

            $("#add_item_form").on("submit", function (e) {
                e.preventDefault();

                let $this    = $(this);
                let $modal   = $("#add_item_modal");
                let formData = new FormData($this[0]);

                $.ajax({
                    url: "{{ route("voyager.graph.create") }}",
                    type: "POST",
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    data: formData,
                    success: function(response) {
                        console.log(response);

                        if (response.success === true) {
                            if (response.item.type === "category") {
                                $("#dd-output").append(buildItem(response.item))
                            }

                            if (response.item.type === "subcategory" || response.item.type === "tool") {
                                appendItem(response.item);
                            }

                            $this.trigger("reset");
                        }

                        $modal.modal("hide");
                    },
                });
            });

            $("#edit_item_form").on("submit", function (e) {
                e.preventDefault();

                let $this    = $(this);
                let $modal   = $("#edit_item_modal");
                let formData = new FormData($this[0]);

                $.ajax({
                    url: "{{ route("voyager.graph.update") }}",
                    type: "POST",
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    data: formData,
                    success: function(response) {
                        console.log(response);

                        let full_id  = response.item.type + '-' + response.item.id;
                        let $dd_item = $('.dd-item[data-id="' + full_id + '"]');
                        let $edit_btn = $dd_item.find("> .item_actions > .edit");

                        if (response.success === true) {
                            $dd_item.find("> .dd-handle > .dd-graphs__item-title").html(response.item.title);
                            $edit_btn.data("title", response.item.title)

                            if (response.item.type === "category" || response.item.type === "subcategory") {
                                $dd_item.find("> .dd-handle > .dd-graphs__item-color > .dd-graphs__item-color-title").css("background-color", response.item.color_title);
                                $dd_item.find("> .dd-handle > .dd-graphs__item-color > .dd-graphs__item-color-border").css("background-color", response.item.color_border);

                                $edit_btn.data("color-title", response.item.color_title)
                                $edit_btn.data("color-border", response.item.color_border)
                            }

                            if (response.item.type === "tool") {
                                $edit_btn.data("data", response.item.data)
                                $.each(response.item.data, function(index, index_data) {
                                    $dd_item.find('input[name="data[' + index + '][interval]"]').val(index_data.interval);
                                    $dd_item.find('select[name="data[' + index + '][interval_code]"]').val(index_data.interval_code);
                                    $dd_item.find('input[name="data[' + index + '][url]"]').val(index_data.url);
                                });
                            }

                            $this.trigger("reset");
                        }

                        $modal.modal("hide");
                    },
                });
            });
        });
    </script>
@stop


