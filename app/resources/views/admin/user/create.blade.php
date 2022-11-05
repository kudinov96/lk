@extends('admin.layout')

@section('page_title', 'Добавить пользователя')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-person"></i>
        Добавить пользователя
    </h1>
@stop

@section('content')
    <div class="page-content container-fluid">
        <form class="form-edit-add" action="{{ route("voyager.users.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method("PUT")

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="name" placeholder="Имя"
                                               value="{{ old("name") }}">
                                    </div>
                                </div>

                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <input type="email" class="form-control" name="telegram_name" placeholder="Никнейм в Telegram"
                                               value="{{ old("telegram_name") }}">
                                    </div>
                                </div>

                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <select class="select2" name="role">
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-6">
                                    <div class="form-group form-group__is-ban-field">
                                        <label class="control-label">Заблокировать?</label>
                                        <input type="checkbox" class="toggleswitch">
                                    </div>
                                </div>

                                <div class="col-xs-12">
                                    <div class="form-group subscriptions-add">
                                        <div class="subscriptions-add__item">
                                            <label class="control-label">Добавить подписку</label><br>
                                            <select name="subscription[0][id]" data-number="0" class="select2 subscriptions-add__select-sub">
                                                <option value="">Выбрать подписку</option>
                                                @foreach($subscriptions as $subscription)
                                                    <option value="{{ $subscription->id }}">{{ $subscription->title }}</option>
                                                @endforeach
                                            </select>
                                            <select name="subscription[0][period]" class="select2 subscriptions-add__select-period" id="subscriptions-add__select-period-0">
                                                <option value="">Выберите период</option>
                                            </select>
                                            <div class="btn btn-success add_subscription"><i class="voyager-plus"></i> Добавить подписку</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{--<div class="col-md-4">
                    <div class="panel panel panel-bordered panel-warning">
                        <div class="panel-body">
                            <div class="form-group">
                                @if(isset($dataTypeContent->avatar))
                                    <img src="{{ filter_var($dataTypeContent->avatar, FILTER_VALIDATE_URL) ? $dataTypeContent->avatar : Voyager::image( $dataTypeContent->avatar ) }}" style="width:200px; height:auto; clear:both; display:block; padding:2px; border:1px solid #ddd; margin-bottom:10px;" />
                                @endif
                                <input type="file" data-name="avatar" name="avatar">
                            </div>
                        </div>
                    </div>
                </div>--}}
            </div>

            <button type="submit" class="btn btn-primary pull-right save">
                {{ __('voyager::generic.save') }}
            </button>
        </form>
        <div style="display:none">
            {{--<input type="hidden" id="upload_url" value="{{ route('voyager.upload') }}">
            <input type="hidden" id="upload_type_slug" value="{{ $dataType->slug }}">--}}
        </div>
    </div>
@stop

@section('javascript')
    <script>
        $(document).ready(function () {
            $('.toggleswitch').bootstrapToggle({
                on: "Да",
                off: "Нет",
            });

            $(document).on("change", ".subscriptions-add__select-sub", function(){
                let $this          = $(this);
                let number         = $this.data("number");
                let id             = $this.val();
                let $select_period = $("#subscriptions-add__select-period-" + number);

                if (id !== "") {
                    $.ajax({
                        url: "{{ route("voyager.subscription.periods") }}",
                        type: "POST",
                        data: {
                            id,
                        },
                        success: function(response) {
                            console.log(response);

                            if (response.success === true) {
                                $select_period.val("").trigger('change');
                                $select_period.html("");

                                $select_period.append(new Option("Выберите период", "", false, false)).trigger("change");
                                $.each(response.data, function(index, index_data) {
                                    $select_period.append(new Option(index_data.text, index_data.id, false, false)).trigger("change");
                                });
                            }
                        },
                    });
                }
            });
        });
    </script>
@stop
