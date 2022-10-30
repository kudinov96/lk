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
                    <input type="hidden" name="type">
                    <input type="hidden" name="parent_id">

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
                                        <input type="text" name="data[{{ $i }}][interval]" class="form-control dd-graphs__item-interval" required>
                                        <select name="data[{{ $i }}][interval_code]" class="form-control dd-graphs__item-interval-code" required>
                                            @foreach($intervalCodes as $v)
                                                <option value="{{ $v }}" @if($index_code === $v) selected @endif>{{ $v }}</option>
                                            @endforeach
                                        </select>
                                        <input type="text" name="data[{{ $i }}][url]" class="form-control dd-graphs__item-url" placeholder="Url" required>
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
                         html += '<div class="btn btn-success add_item" data-type="subcategory" data-parent-id="' + item.id + '"><i class="voyager-plus"></i></div>';
                     }

                     if (item.type === "subcategory") {
                         html += '<div class="btn btn-success add_item" data-type="tool" data-graph-category-id="' + item.id + '"><i class="voyager-plus"></i></div>';
                     }

                     html +='<div class="btn btn-sm btn-danger pull-right delete">' +
                                '<i class="voyager-trash"></i> Удалить' +
                            '</div>' +
                        '</div>' +
                        '<div class="dd-handle">' +
                            '<span>' + item.title + '</span>';
                            if (item.type !== "tool") {
                                html += '<span class="dd-graphs__item-color">Заголовок <span style="background-color: ' + item.color_title + '"></span></span>' +
                                '<span class="dd-graphs__item-color">Рамка <span style="background-color: ' + item.color_border + '"></span></span>';
                            }
                html += '</div>';

                if (item.type === "tool") {
                    html += '<div class="dd-graphs">';
                    $.each(item.data, function (index_code, index_data) {
                        console.log(index_data);
                        html += '<div class="dd-graphs__item">' +
                                    '<input type="text" name="interval" value="' + index_data.interval + '" class="form-control dd-graphs__item-interval">' +
                                    '<select name="interval_code" class="form-control dd-graphs__item-interval-code">';
                                        $.each(intervalCodes, function (k, v) {
                                            let selected = false;

                                            if (index_code === v) {
                                                selected = " selected";
                                            }
                                            html += '<option value="' + v + '"' + selected + '>' + v + '</option>';
                                        });
                                html += '</select>' +
                                    '<input type="text" name="url" value="' + index_data.url + '" class="form-control dd-graphs__item-url" placeholder="Url">' +
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

            // Add item
            $(document).on("click", ".add_item", function() {
                let $modal            = $("#add_item_modal");
                let $form             = $("#add_item_form");
                let type              = $(this).data("type");
                let parent_id         = $(this).data("parent-id");
                let graph_category_id = $(this).data("graph-category-id");

                $form.trigger("reset");
                $form.find('input[name="type"]').val(type);
                $form.find('input[name="parent_id"]').val(parent_id);
                $form.find('input[name="graph_category_id"]').val(graph_category_id);

                $modal.find(".modal-title").addClass("hidden");
                $modal.find(".modal-field").addClass("hidden");
                $modal.find(".modal-title__" + type).removeClass("hidden");
                $modal.find(".modal-field__" + type).removeClass("hidden");

                $modal.modal('show');
            });

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

                            if (response.item.type === "subcategory") {
                                $("#dd-output").find('.dd-item[data-id="category-' + response.item.parent_id + '"] > ol.dd-list').append(buildItem(response.item))
                            }

                            if (response.item.type === "tool") {
                                $("#dd-output").find('.dd-item[data-id="category-' + response.item.graph_category_id + '"] > ol.dd-list, .dd-item[data-id="subcategory-' + response.item.graph_category_id + '"] > ol.dd-list').append(buildItem(response.item))
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


