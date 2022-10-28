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
                    <h4 id="m_hd_add_category" class="modal-title hidden"><i class="voyager-plus"></i> Добавить категорию</h4>
                    <h4 id="m_hd_add_subcategory" class="modal-title hidden"><i class="voyager-plus"></i> Добавить подкатегорию</h4>
                    <h4 id="m_hd_add_tool" class="modal-title hidden"><i class="voyager-plus"></i> Добавить инструмент</h4>
                </div>
                <form id="add_item_form" method="POST">
                    <input type="hidden" name="type">
                    <div class="modal-body">
                        <div>
                            <label for="title">Заголовок</label>
                            <input type="text" class="form-control" id="title" name="title" required><br>
                        </div>
                        <div>
                            <label for="color_title">Цвет заголовка</label>
                            <input type="color" class="form-control" id="color_title" name="color_title" required><br>
                        </div>
                        <div>
                            <label for="color_border">Цвет рамки</label>
                            <input type="color" class="form-control" id="color_border" name="color_border" required><br>
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
                let dataData = '';
                if (typeof item.data !== "undefined") {
                    dataData = ' data-data=\'' + JSON.stringify(item.data) + '\'';
                }
                let html = '<li class="dd-item" data-id="' + item.type + '-' + item.id + '" data-model-id="' + item.id + '" data-type="' + item.type + '" data-title="' + item.title + '"' + dataData + '>';

                html += '<div class="pull-right item_actions">' +
                            '<div class="btn btn-sm btn-danger pull-right delete">' +
                                '<i class="voyager-trash"></i> Удалить' +
                            '</div>' +
                        '</div>' +
                        '<div class="dd-handle">' +
                            '<span>' + item.title + '</span> <small class="url">' + item.type + '</small>' +
                        '</div>';

                if (item.type === "tool") {
                    html += '<div class="dd-graphs">';
                    $.each(intervalCodes, function (index_code) {
                        let interval = "";
                        let url = "";

                        if (typeof item.data[index_code] !== "undefined") {
                            interval = item.data[index_code].interval;
                            url      = item.data[index_code].url;
                        }

                        html += '<div class="dd-graphs__item">' +
                                    '<input type="text" name="interval" value="' + interval + '" class="form-control dd-graphs__item-interval">' +
                                    '<select name="interval_code" class="form-control dd-graphs__item-interval-code">';
                                        $.each(intervalCodes, function (k, v) {
                                            let selected = false;

                                            if (index_code === v) {
                                                selected = " selected";
                                            }
                                            html += '<option value="' + v + '"' + selected + '>' + v + '</option>';
                                        });
                                html += '</select>' +
                                    '<input type="text" name="url" value="' + url + '" class="form-control dd-graphs__item-url" placeholder="Url">' +
                                '</div>';
                    });
                    html += '</div>';
                }

                if (item.children) {
                    html += "<ol class='dd-list'>";
                    $.each(item.children, function (index, sub) {
                        html += buildItem(sub);
                    });
                    html += "</ol>";
                }

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
                                if ($(e).find('[data-type="subcategory"]').length === 0) {
                                    $(e).data("type", "subcategory");
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
            $(".item_actions").on("click", ".delete", function (e) {
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
            $(".add_item").click(function() {
                let $modal = $("#add_item_modal");
                let type  = $(this).data("type");

                $("#add_item_form").trigger("reset");
                $("#add_item_form").find('input[name="type"]').val(type);

                if (type === "category") {
                    $modal.find("#m_hd_add_category").removeClass("hidden");
                }

                $modal.modal('show');
            });

            $("#add_item_form").on("submit", function (e) {
                e.preventDefault();

                let $this        = $(this);
                let $modal       = $("#add_item_modal");
                let type         = $this.find('input[name="type"]').val();
                let title        = $this.find('input[name="title"]').val();
                let color_title  = $this.find('input[name="color_title"]').val();
                let color_border = $this.find('input[name="color_border"]').val();

                $.ajax({
                    url: "{{ route("voyager.graph.create") }}",
                    type: "POST",
                    data: {
                        type,
                        title,
                        color_title,
                        color_border
                    },
                    success: function(response) {
                        console.log(response);

                        if (response.success === true) {
                            $("#dd-output").append(buildItem(response.item))
                        }
                        $modal.modal("hide");
                    },
                });
            });
        });
    </script>
@stop


