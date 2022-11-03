@extends('voyager::master')

@section('page_title', 'Пользователи')

@section('css')
    @vite('resources/scss/admin/admin.scss')
@stop

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
                        <form method="get" class="form-search">
                            <div id="search-input">
                                <div class="input-group col-md-12">
                                    <input type="text" class="form-control" placeholder="{{ __('voyager::generic.search') }}" name="s">
                                    <span class="input-group-btn">
                                        <button class="btn btn-info btn-lg" type="submit">
                                            <i class="voyager-search"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                            @if (Request::has('sort_order') && Request::has('order_by'))
                                <input type="hidden" name="sort_order" value="{{ Request::get('sort_order') }}">
                                <input type="hidden" name="order_by" value="{{ Request::get('order_by') }}">
                            @endif
                        </form>
                        <div class="table-responsive">
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
                                                <input type="checkbox" name="row_id" id="checkbox_{{ $user->id }}" value="">
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

                        </div>
                        <div class="pull-right">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bulk delete modal --}}
    <div class="modal modal-danger fade" tabindex="-1" id="bulk_delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">
                        <i class="voyager-trash"></i> {{ __('voyager::generic.are_you_sure_delete') }} <span id="bulk_delete_count"></span> <span id="bulk_delete_display_name"></span>?
                    </h4>
                </div>
                <div class="modal-body" id="bulk_delete_modal_body">
                </div>
                <div class="modal-footer">
                    <form action="" id="bulk_delete_form" method="POST">
                        {{ method_field("DELETE") }}
                        {{ csrf_field() }}
                        <input type="hidden" name="ids" id="bulk_delete_input" value="">
                        <input type="submit" class="btn btn-danger pull-right delete-confirm">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">
                        {{ __('voyager::generic.cancel') }}
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@stop

@section('javascript')
    <script>
        $(document).ready(function () {

        });
    </script>
@stop
