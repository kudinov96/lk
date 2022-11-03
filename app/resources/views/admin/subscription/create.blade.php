@extends('voyager::master')

@section('page_title', 'Подписки')

@section('css')
    @vite('resources/scss/admin/admin.scss')
@stop

@section('page_header')
    <h1 class="page-title">Добавить подписку</h1>
@stop

@section('content')
    <div class="page-content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <form class="form-edit-add" action="{{ route("voyager.subscription.store") }}" method="POST">
                        @method("POST")
                        @csrf

                        <div class="panel-body">
                            <div class="form-group col-md-6">
                                <label class="control-label" for="name">Название</label>
                                <input  type="text" class="form-control" name="title" placeholder="Название" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="control-label" for="color">Цвет подписки</label>
                                <input type="color" class="form-control" name="color"><br>
                            </div>

                            <div class="form-group col-md-12">
                                <label class="control-label" for="name">Тестовая?</label><br>
                                <input type="checkbox" name="is_test" class="toggleswitch">
                            </div>

                            <div class="is-not-test">
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
                                            @foreach($periods as $key => $period)
                                                <div @class([
                                                    "periods__item",
                                                    "item-" . ++$key,
                                                    "hidden" => $key > 1
                                                ])>
                                                    <div class="row">
                                                        <div class="col-xs-6">
                                                            <select class="form-control select2 select2-hidden-accessible" name="periods[{{ $key }}][count_name]">
                                                                @foreach($periods as $period_item)
                                                                    <option value="{{ $period_item->full_count_name }}"> {{ $period_item->full_count_name_human }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-xs-6">
                                                            <input class="form-control" type="number" min="0" name="periods[{{ $key }}][price]">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="btn btn-success add_period" data-count="2" data-total-count="{{ $periods->count() }}"><i class="voyager-plus"></i> Добавить период</div>
                                    </div>
                                </div>

                                <div class="form-group col-md-12">
                                    <label class="control-label">Графики</label>
                                    <select class="form-control select2 select2-hidden-accessible" name="graph_categories[]" multiple>
                                        @foreach($graphCategories as $category)
                                            <option value="{{ $category->id }}">{{ $category->title }}</option>
                                            @foreach($category->subcategories as $subcategory)
                                                <option value="{{ $subcategory->id }}">— {{ $subcategory->title }}</option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-12">
                                    <label class="control-label">Telegram-каналы</label>
                                    <select class="form-control select2 select2-hidden-accessible" name="telegram_channels[]" multiple>
                                        @foreach($telegramChannels as $channel)
                                            <option value="{{ $channel->id }}">{{ $channel->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="is-test hidden">
                                <div class="form-group col-md-12">
                                    <label class="control-label">Период подписки</label>
                                    <select class="form-control select2 select2-hidden-accessible" name="period_count_name">
                                        @foreach($periods as $period_item)
                                            <option value="{{ $period_item->full_count_name }}"> {{ $period_item->full_count_name_human }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-md-12 ">
                                <label class="control-label" for="content">Контент</label><br>
                                <textarea name="content" class="form-control richTextBox" id="richtextcontent"></textarea>
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
