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
                        </div>
                        <textarea id="nestable2-output"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                let json   = window.JSON.stringify(list.nestable('serialize'));

                if (window.JSON) {
                    output.val(json);//, null, 2));
                }


                $.ajax({
                    url: "{{ route("voyager.graph.update") }}",
                    type: "POST",
                    data: {
                        json,
                    },
                    beforeSend: function (){
                    },
                    complete: function (){
                    },
                    success: function(response) {
                        console.log(response);
                    },
                });
            };

            function buildItem(item) {
                let html = '<li class="dd-item" data-id="' + item.id + '" data-type="' + item.type + '" data-title="' + item.title + '">';

                html += '<div class="pull-right item_actions">' +
                            '<div class="btn btn-sm btn-danger pull-right delete" data-id="' + item.id + '">' +
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

            $('#dd-output').html(output);
            $('#dd-nestable').nestable({
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
                            if (parent_type === "subcategory" || parent_type === "tool"|| $(p).hasClass("dd-list_categories")) {
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
            }).on('change', updateOutput);

            updateOutput($('#dd-nestable').data('output', $('#nestable2-output')));

        });
    </script>
@stop


