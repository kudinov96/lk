@extends('admin.layout')

@section('page_title', 'Пользователи')

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="icon voyager-person"></i> Пользователи
        </h1>
        <a href="{{ route('voyager.users.create') }}" class="btn btn-success btn-add-new">
            <i class="voyager-plus"></i> <span>{{ __('voyager::generic.add_new') }}</span>
        </a>
    </div>
@stop

@section('content')
    <div class="page-content browse container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="sorting">
                                    <select id="sorting" class="select2">
                                        <option selected value="">Сортировать по</option>
                                        @foreach($subscriptions as $subscription)
                                            <option value="subscription-{{ $subscription->id }}" @if(request()->sort_by === "subscription-$subscription->id") selected @endif>Подписки: {{ $subscription->title }}</option>
                                        @endforeach
                                        @foreach($graphCategories as $category)
                                            <option value="graphCategory-{{ $category->id }}" @if(request()->sort_by === "graphCategory-$category->id") selected @endif>Графики: {{ $category->title }}</option>
                                            @foreach($category->subcategories as $subcategory)
                                                <option value="graphCategory-{{ $subcategory->id }}" @if(request()->sort_by === "graphCategory-$subcategory->id") selected @endif>— {{ $subcategory->title }}</option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <form method="get" class="form-search">
                                    <div id="search-input">
                                        <div class="input-group col-md-12">
                                            <input type="text" class="form-control" placeholder="{{ __('voyager::generic.search') }}" name="s" value="{{ request()->s }}">
                                            <span class="input-group-btn">
                                        <button class="btn btn-info btn-lg" type="submit">
                                            <i class="voyager-search"></i>
                                        </button>
                                    </span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="table-responsive table-users">
                            <table id="dataTable" class="table table-hover">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>
                                        ID
                                    </th>
                                    <th>
                                        Имя
                                    </th>
                                    <th>Подписки</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="ids" class="checked-ids" value="{{ $user->id }}">
                                            </td>
                                            <td>
                                                ID: {{ $user->id }}
                                            </td>
                                            <td>
                                                {{ $user->name }} @if($user->telegram_name){!! "(<a href=\"https://t.me/{$user->telegram_name}\" target=\"_blank\">@" . $user->telegram_name . "</a>)" !!}@endif
                                            </td>
                                            <td class="user-subscriptions">
                                                @foreach($user->subscriptions as $subscription)
                                                    <a href="{{ route("voyager.subscription.edit", ["id" => $subscription->id]) }}" class="user-subscriptions__item" style="background-color: {{ $subscription->color }}">{{ $subscription->title }}</a>
                                                @endforeach
                                                @if ($user->courses()->exists())
                                                    <span class="user-subscriptions__item" style="background-color: #ec8ff5">{{ num_declension($user->courses()->count(), ["курс", "курса", "курсов"]) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a title="Удалить" class="btn btn-sm btn-danger pull-right delete" data-id="{{ $user->id }}" id="delete-{{ $user->id }}">
                                                    <i class="voyager-trash"></i>
                                                </a>
                                                <a href="{{ route("voyager.users.edit", ["id" => $user->id]) }}" title="Изменить" class="btn btn-sm btn-primary pull-right edit">
                                                    <i class="voyager-edit"></i>
                                                </a>
                                                <a href="{{ route("voyager.users.edit", ["id" => $user->id]) }}" class="btn btn-sm btn-tg pull-right tg-chat">
                                                    Написать в Telegram
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="pull-left">
                            <div class="actions">
                                <form id="actions_form">
                                    <select name="actions" class="select2" required>
                                        @foreach($subscriptions as $subscription)
                                            <option value="add-subscription-{{ $subscription->id }}">Добавить подписку: {{ $subscription->title }}</option>
                                        @endforeach
                                        <option selected value="">C отмеченными</option>
                                        <option value="clear-subscriptions">Очистить подписки</option>
                                        <option value="clear-courses">Очистить курсы</option>
                                        <option value="ban">Заблокировать</option>
                                        <option value="delete">Удалить</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary">Применить</button>
                                </form>
                            </div>
                        </div>
                        <div class="pull-right">
                            {{ $users->appends([
                                's' => request()->s,
                            ])->links() }}

                            <div сlass="show-res">
                                {{ trans_choice(
                                    'voyager::generic.showing_entries', $users->total(), [
                                        'from' => $users->firstItem(),
                                        'to' => $users->lastItem(),
                                        'all' => $users->total()
                                ]) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Single delete modal --}}
    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-trash"></i> Вы действительно хотите удалить этого пользователя?</h4>
                </div>
                <div class="modal-footer">
                    <form id="delete_form" method="POST">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right delete-confirm" value="{{ __('voyager::generic.delete_confirm') }}">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    {{-- Confirm modal --}}
    <div class="modal modal-danger fade" tabindex="-1" id="confirm_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Вы действительно хотите применить это действие?</h4>
                </div>
                <div class="modal-footer">
                    <form id="confirm_form" method="POST">
                        <input type="submit" class="btn btn-danger pull-right delete-confirm" value="Да">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@stop

@section('javascript')
    <script>
        $(document).ready(function () {
            var deleteFormAction;
            $('td').on('click', '.delete', function (e) {
                $('#delete_form')[0].action = '{{ route('voyager.users.destroy', '__id') }}'.replace('__id', $(this).data('id'));
                $('#delete_modal').modal('show');
            });

            $("#sorting").on("change", function(){
                let value  = $(this).val();

                updateQueryStringParam("sort_by", value);
                location.reload();
            });

            $("#actions_form").on("submit", function(e){
                e.preventDefault();

                $("#confirm_modal").modal("show");
            });

            $("#confirm_form").on("submit", function(e){
                e.preventDefault();

                const formData = new FormData();

                let $modal     = $("#confirm_modal");
                let action     = $(document).find('select[name="actions"]').val();
                let checkboxes = document.querySelectorAll('input.checked-ids:checked');

                formData.append("action", action);

                for(let i = 0; i < checkboxes.length; i++){
                    formData.append("ids[]", checkboxes[i].value);
                }

                $.ajax({
                    url: "{{ route("voyager.users.actions") }}",
                    type: "POST",
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    data: formData,
                    success: function(response) {
                        console.log(response);

                        if (response.success === true) {
                            $(this).trigger("reset");
                            location.reload();
                        }

                        $modal.modal("hide");
                    },
                });
            });

            var updateQueryStringParam = function (key, value) {
                var baseUrl = [location.protocol, '//', location.host, location.pathname].join(''),
                    urlQueryString = document.location.search,
                    newParam = key + '=' + value,
                    params = '?' + newParam;

                // If the "search" string exists, then build params from it
                if (urlQueryString) {
                    keyRegex = new RegExp('([\?&])' + key + '[^&]*');

                    // If param exists already, update it
                    if (urlQueryString.match(keyRegex) !== null) {
                        params = urlQueryString.replace(keyRegex, "$1" + newParam);
                    } else { // Otherwise, add it to end of query string
                        params = urlQueryString + '&' + newParam;
                    }
                }
                window.history.replaceState({}, "", baseUrl + params);
            };
        });
    </script>
@stop
