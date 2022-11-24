@extends('admin.layout')

@section('page_title', 'Редактировать подписку')

@section('page_header')
    <h1 class="page-title">Редактировать подписку</h1>
@stop

@section('content')
    <div class="page-content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <form class="form-edit-add" action="{{ route("voyager.subscription.update", ["id" => $item->id]) }}" method="POST" enctype="multipart/form-data">
                        @method("PUT")
                        @csrf

                        <input type="hidden" name="order" value="{{ $item->order }}">
                        <div class="panel-body">
                            <div class="form-group col-md-6">
                                <label class="control-label" for="name">Название</label>
                                <input  type="text" class="form-control" name="title" placeholder="Название" value="{{ $item->title }}" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="control-label" for="color">Цвет подписки</label>
                                <input type="color" class="form-control" name="color" value="{{ $item->color }}"><br>
                            </div>

                            <div class="form-group col-md-12">
                                <label class="control-label" for="icon">Иконка</label>
                                @if($item->icon)
                                    <div data-field-name="preview">
                                        <a href="#" class="voyager-x remove-single-image" style="position:absolute;"></a>
                                        <img src="@if( !filter_var($item->icon, FILTER_VALIDATE_URL)){{ Voyager::image( $item->icon ) }}@else{{ $item->icon }}@endif"
                                             data-file-name="{{ $item->icon }}"
                                             style="max-width:200px; height:auto; clear:both; display:block; padding:2px; border:1px solid #ddd; margin-bottom:10px;">
                                    </div>
                                @endif
                                <input type="file" name="icon" accept="image/*">
                            </div>

                            <div class="form-group col-md-12 ">
                                <label class="control-label" for="name">Тестовая?</label><br>
                                <input type="checkbox" name="is_test" @if($item->is_test) checked @endif class="toggleswitch is-test">
                            </div>

                            <div @class([
                                "is-not-test",
                                "hidden" => $item->is_test,
                            ])>
                                <div class="form-group col-md-12">
                                    <div class="periods">
                                        <div class="row">
                                            <div class="col-xs-3 mb-0">
                                                <label class="control-label">Период подписки</label>
                                            </div>
                                            <div class="col-xs-3 mb-0">
                                                <label class="control-label">Цена за период</label><br>
                                            </div>
                                            <div class="col-xs-3 mb-0">
                                                <label class="control-label">Период по умолчанию</label><br>
                                            </div>
                                        </div>
                                        <div class="periods__items" id="periods-items">
                                            @foreach($item->periods as $key => $item_period)
                                                <div class="periods__item">
                                                    <div class="row">
                                                        <div class="col-xs-3 mb-10">
                                                            <select class="form-control select2 select2-hidden-accessible" name="periods[{{ $key }}][count_name]">
                                                                @foreach($periods as $period_item)
                                                                    <option value="{{ $period_item->full_count_name }}" @if($item_period->full_count_name === $period_item->full_count_name) selected @endif>{{ $period_item->full_count_name_human }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-xs-3 mb-10">
                                                            <input class="form-control" type="number" min="0" name="periods[{{ $key }}][price]" value="@if($item_period){{ $item_period->pivot->price }}@endif">
                                                        </div>
                                                        <div class="col-xs-3 mb-10">
                                                            <div class="period-default-toggle">
                                                                <input type="checkbox" id="period-is-default-0" name="periods[{{ $key }}][is_default]" class="toggleswitch" @if($item_period->pivot->is_default) checked @endif>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-3 mb-10">
                                                            <div class="btn btn-danger remove_period"><i class="voyager-trash"></i> Удалить</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="btn btn-success add_period" data-number="{{ $item->periods()->count() }}"><i class="voyager-plus"></i> Добавить период</div>
                                    </div>
                                </div>

                                <div class="form-group col-md-12">
                                    <label class="control-label">Графики</label>
                                    <select class="form-control select2 select2-hidden-accessible" name="graph_categories[]" multiple>
                                        @foreach($graphCategories as $category)
                                            <option value="{{ $category->id }}" @if($item->graph_categories()->where("id", $category->id)->exists()) selected @endif>{{ $category->title }}</option>
                                            @foreach($category->subcategories as $subcategory)
                                                <option value="{{ $subcategory->id }}" @if($item->graph_categories()->where("id", $subcategory->id)->exists()) selected @endif>— {{ $subcategory->title }}</option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-12">
                                    <label class="control-label">Telegram-каналы</label>
                                    <select class="form-control select2 select2-hidden-accessible" name="telegram_channels[]" multiple>
                                        @foreach($telegramChannels as $channel)
                                            <option value="{{ $channel->id }}" @if($item->telegram_channels()->where("id", $channel->id)->exists()) selected @endif>{{ $channel->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div @class([
                                "is-test",
                                "hidden" => !$item->is_test,
                            ])>
                                <div class="form-group col-md-12">
                                    <label class="control-label">Период подписки</label>
                                    <select class="form-control select2 select2-hidden-accessible" name="period_count_name">
                                        @foreach($periods as $period_item)
                                            <option value="{{ $period_item->full_count_name }}" @if($item->is_test && $item->periods->first() && $item->periods->first()->full_count_name === $period_item->full_count_name) selected @endif> {{ $period_item->full_count_name_human }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-md-12 ">
                                <label class="control-label" for="content">Контент</label><br>
                                <textarea name="content" class="form-control richTextBox" id="richtextcontent">
                                    {{ $item->content }}
                                </textarea>
                            </div>
                        </div><!-- panel-body -->

                        <div class="panel-footer">
                            <button type="submit" class="btn btn-primary save">Сохранить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script>
        $(document).ready(function () {
            let periods = @json($periods);

            $('.toggleswitch').bootstrapToggle({
                on: "Да",
                off: "Нет",
            });

            $(".is-test").on("change", function(){
                if ($(this).prop('checked')) {
                    $('.is-test').removeClass('hidden');
                    $('.is-not-test').addClass('hidden');
                } else {
                    $('.is-not-test').removeClass('hidden');
                    $('.is-test').addClass('hidden');
                }
            });

            let additionalConfig = {
                selector: 'textarea.richTextBox[name="content"]',
            }
            $.extend(additionalConfig, {})
            tinymce.init(window.voyagerTinyMCE.getConfig(additionalConfig));

            $(document).on("click", ".add_period", function(){
                let number = $(this).data("number");
                number++;

                html = '<div class="periods__item">' +
                            '<div class="row">' +
                                '<div class="col-xs-3 mb-10">' +
                                    '<select class="form-control select2 select2-hidden-accessible" id="period-count-name-' + number + '" name="periods[' + number + '][count_name]">';
                                        $.each(periods, function(index, index_data) {
                                            html += '<option value="' + index_data.full_count_name + '">' + index_data.full_count_name_human + '</option>';
                                        });
                            html += '</select>' +
                                '</div>' +
                                '<div class="col-xs-3 mb-10">' +
                                    '<input class="form-control" type="number" min="0" name="periods[' + number + '][price]">' +
                                '</div>' +
                                '<div class="col-xs-3 mb-10">' +
                                    '<div class="period-default-toggle">' +
                                        '<input type="checkbox" id="period-is-default-' + number + '" name="periods[' + number + '][is_default]" class="toggleswitch">' +
                                    '</div>' +
                                '</div>' +
                                '<div class="col-xs-3 mb-10">' +
                                    '<div class="btn btn-danger remove_period"><i class="voyager-trash"></i> Удалить</div>' +
                                '</div>' +
                            '</div>' +
                        '</div>';

                $("#periods-items").append(html);
                $('#period-count-name-' + number).select2();
                $('#period-count-name-' + number).select2();
                $('#period-is-default-' + number).bootstrapToggle({
                    on: "Да",
                    off: "Нет",
                });
                $(this).data("number", number);
            });

            $(document).on("click", ".remove_period", function() {
                let $item = $(this).closest(".periods__item");

                $item.remove();
            });

        });
    </script>
@stop
