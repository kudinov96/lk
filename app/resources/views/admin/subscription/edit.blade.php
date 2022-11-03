@extends('voyager::master')

@section('page_title', 'Редактировать подписку')

@section('css')
    @vite('resources/scss/admin/admin.scss')
@stop

@section('page_header')
    <h1 class="page-title">Редактировать подписку</h1>
@stop

@section('content')
    <div class="page-content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <form class="form-edit-add" action="{{ route("voyager.subscription.update", ["id" => $item->id]) }}" method="POST">
                        @method("PUT")
                        @csrf

                        <div class="panel-body">
                            <div class="form-group col-md-12 ">
                                <label class="control-label" for="name">Название</label>
                                <input  type="text" class="form-control" name="title" placeholder="Название" value="{{ $item->title }}" required>
                            </div>

                            <div class="form-group col-md-12 ">
                                <label class="control-label" for="name">Тестовая?</label><br>
                                <input type="checkbox" name="is_test" @if($item->is_test) checked @endif class="toggleswitch">
                            </div>

                            <div @class([
                                "is-not-test",
                                "hidden" => $item->is_test,
                            ])>
                                <div class="form-group col-md-12">
                                    <div class="periods">
                                        <div class="row">
                                            <div class="col-xs-6">
                                                <label class="control-label">Период подписки</label>
                                            </div>
                                            <div class="col-xs-6">
                                                <label class="control-label">Цена за период</label><br>
                                            </div>
                                        </div>
                                        <div class="periods__items">
                                            @foreach($item->periods as $key => $item_period)
                                                <div @class([
                                                    "periods__item",
                                                    "item-" . ++$key,
                                                ])>
                                                    <div class="row">
                                                        <div class="col-xs-6">
                                                            <select class="form-control select2 select2-hidden-accessible" name="periods[{{ $key }}][count_name]">
                                                                @foreach($periods as $period_item)
                                                                    <option value="{{ $period_item->full_count_name }}" @if($item_period->full_count_name === $period_item->full_count_name) selected @endif>{{ $period_item->full_count_name_human }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-xs-6">
                                                            <input class="form-control" type="number" min="0" name="periods[{{ $key }}][price]" value="@if($item_period){{ $item_period->pivot->price }}@endif">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                            @php
                                                $count = $periods->count();
                                                $key   = isset($key) ? ++$key : 0;
                                            @endphp
                                            @for($key; $key <= $count; $key++)
                                                <div @class([
                                                    "periods__item",
                                                    "item-" . $key,
                                                    "hidden" => $key > 0
                                                ])>
                                                    <div class="row">
                                                        <div class="col-xs-6">
                                                            <select class="form-control select2 select2-hidden-accessible" name="periods[{{ $key }}][count_name]">
                                                                @foreach($periods as $period_item)
                                                                    <option value="{{ $period_item->full_count_name }}">{{ $period_item->full_count_name_human }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-xs-6">
                                                            <input class="form-control" type="number" min="0" name="periods[{{ $key }}][price]">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endfor
                                        </div>
                                        <div class="btn btn-success add_period" data-count="{{ $item->periods()->count() + 1 }}" data-total-count="{{ $periods->count() }}"><i class="voyager-plus"></i> Добавить период</div>
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
                                            <option value="{{ $period_item->full_count_name }}" @if($item->is_test && $item->periods->first()->full_count_name === $period_item->full_count_name) selected @endif> {{ $period_item->full_count_name_human }}</option>
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

            $('.toggleswitch').bootstrapToggle({
                on: "Да",
                off: "Нет",
            }).change(function() {
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
                let count       = $(this).data("count");
                let total_count = parseInt($(this).data("total-count"));

                $(".periods__item.item-" + count).removeClass("hidden");

                count++;
                if (count > total_count) {
                    $(this).addClass("hidden");
                }

                $(this).data("count", count);
            });

        });
    </script>
@stop
