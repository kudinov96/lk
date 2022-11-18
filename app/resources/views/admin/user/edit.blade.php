@extends('admin.layout')

@section('page_title', 'Редактирование пользователя ' . $item->name)

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-person"></i>
        Редактирование пользователя {{ $item->name }} @if($item->telegram_name){!! "(<a href=\"https://t.me/{$item->telegram_name}\" target=\"_blank\">@" . $item->telegram_name . "</a>)" !!}@endif
    </h1>
@stop

@section('content')
    <div class="page-content container-fluid user-page">
        @include('voyager::alerts')

        <form class="form-edit-add" action="{{ route("voyager.users.update", ["id" => $item->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method("PUT")

            <div class="row">
                <div class="col-md-8">
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
                                               value="{{ $item->name ?? "" }}">
                                    </div>
                                </div>

                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="telegram_name" placeholder="Никнейм в Telegram"
                                               value="{{ $item->telegram_name ?? "" }}">
                                    </div>
                                </div>

                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="email" placeholder="E-mail"
                                               value="{{ $item->email ?? "" }}">
                                    </div>
                                </div>

                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <select class="select2" name="role_id">
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}" @if($role->id === $item->role_id) selected @endif>{{ $role->display_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-6">
                                    <div class="form-group form-group__is-ban-field">
                                        <label class="control-label">Заблокировать?</label>
                                        <input type="checkbox" name="is_ban" class="toggleswitch" @if($item->is_ban) checked @endif>
                                    </div>
                                </div>

                                <div class="col-xs-12">
                                    <h4>Подписки</h4>

                                    @if($item->subscriptions)
                                        <div class="form-group">
                                            <div class="user-page-update__subscriptions">
                                                @foreach($item->subscriptions as $key => $subscription)
                                                    <div class="user-page-update__subscription-item" data-number="{{ $key }}" data-subscription-id="{{ $subscription->id }}">
                                                        <input type="hidden" name="update_subscriptions[{{ $key }}][id]" value="{{ $subscription->id }}">
                                                        <input type="hidden" name="update_subscriptions[{{ $key }}][updated]" value="0">
                                                        <div class="user-page-update__item-title"><a href="{{ route("voyager.subscription.edit", ["id" => $subscription->id]) }}" target="_blank">{{ $subscription->title }}</a> до <span>{{ $subscription->date_end }}</span></div>
                                                        <div class="user-page-update__item-block">
                                                            <select name="update_subscriptions[{{ $key }}][period]" class="select2 extend-subscription-select">
                                                                <option value="">Выберите период</option>
                                                                @foreach($subscription->periods as $period)
                                                                    <option value="{{ $period->full_count_name }}">{{ $period->full_count_name_human }}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="user-page__subscriptions-bill">
                                                                <span>Выставить счет?</span>
                                                                <input type="checkbox" name="update_subscriptions[{{ $key }}][bill]" class="toggleswitch">
                                                            </div>
                                                            <div class="btn btn-primary extend_subscription">Продлить</div>
                                                            <div class="user-page__subscriptions-auto">
                                                                <span>Автопродление</span>
                                                                <input type="checkbox" name="update_subscriptions[{{ $key }}][is_auto_renewal]" class="toggleswitch" @if($subscription->pivot->is_auto_renewal) checked @endif>
                                                            </div>
                                                            <div class="btn btn-danger remove_update_subscription"><i class="voyager-trash"></i> Удалить</div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <div class="form-group subscriptions-add">
                                        <div class="user-page-add__item" data-number="0">
                                            <label class="control-label">Добавить подписку</label><br>
                                            <select name="subscriptions[0][id]" class="select2 subscriptions-add__select-sub" id="subscriptions-add__select-sub-0">
                                                <option value="" selected>Выбрать подписку</option>
                                                @foreach($subscriptions as $subscription)
                                                    <option value="{{ $subscription->id }}">{{ $subscription->title }}</option>
                                                @endforeach
                                            </select>
                                            <select name="subscriptions[0][period]" class="select2" id="subscriptions-add__select-period-0">
                                                <option value="">Выберите период</option>
                                            </select>
                                            <div class="btn btn-success add_subscription"><i class="voyager-plus"></i> Добавить подписку</div>
                                            <input type="hidden" name="subscriptions[0][added]" value="0" />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12">
                                    <h4>Доступы</h4>

                                    <h5>Услуги:</h5>

                                    @if($item->services)
                                        <div class="form-group">
                                            <div class="user-page-update__services">
                                                @foreach($item->services as $key => $service)
                                                    <div class="user-page-update__item" data-number="{{ $key }}" data-service-id="{{ $service->id }}">
                                                        <div class="user-page-update__item-title"><a href="{{ route("voyager.services.edit", ["id" => $service->id]) }}" target="_blank">{{ $service->title }}</a></div>
                                                        <div class="btn btn-danger remove_update_service"><i class="voyager-trash"></i> Удалить</div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <div class="form-group services-add">
                                        <div class="user-page-add__item" data-number="0">
                                            <label class="control-label">Добавить услугу</label><br>
                                            <select name="services[0][id]" class="select2" id="services-add__select-0">
                                                <option value="" selected>Выбрать услугу</option>
                                                @foreach($services as $service)
                                                    <option value="{{ $service->id }}">{{ $service->title }}</option>
                                                @endforeach
                                            </select>
                                            <div class="btn btn-success add_service"><i class="voyager-plus"></i> Добавить услугу</div>
                                            <input type="hidden" name="services[0][added]" value="0" />
                                        </div>
                                    </div>

                                    <h5>Курсы:</h5>

                                    @if($item->courses)
                                        <div class="form-group">
                                            <div class="user-page-update__courses">
                                                @foreach($item->courses as $key => $course)
                                                    <div class="user-page-update__item" data-number="{{ $key }}" data-course-id="{{ $course->id }}">
                                                        <div class="user-page-update__item-title"><a href="{{ route("voyager.courses.edit", ["id" => $course->id]) }}" target="_blank">{{ $course->title }}</a></div>
                                                        <div class="btn btn-danger remove_update_course"><i class="voyager-trash"></i> Удалить</div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <div class="form-group courses-add">
                                        <div class="user-page-add__item" data-number="0">
                                            <label class="control-label">Добавить курс</label><br>
                                            <select name="courses[0][id]" class="select2" id="courses-add__select-0">
                                                <option value="" selected>Выбрать курс</option>
                                                @foreach($courses as $course)
                                                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                                                @endforeach
                                            </select>
                                            <div class="btn btn-success add_course"><i class="voyager-plus"></i> Добавить курс</div>
                                            <input type="hidden" name="courses[0][added]" value="0" />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12">
                                    <h4>Скидки</h4>

                                    @if($item->discounts)
                                        <div class="form-group">
                                            <div class="user-page-update__discounts">
                                                @foreach($item->discounts as $key => $discount)
                                                    <div class="user-page-update__item" data-number="{{ $key }}" data-discount-id="{{ $discount->id }}">
                                                        <div class="user-page-update__item-title">{{ $discount->service_name }} — <span>{{ $discount->count }}%</span></div>
                                                        <div class="btn btn-danger remove_update_discount"><i class="voyager-trash"></i> Удалить</div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <div class="form-group discounts-add">
                                        <div class="user-page-add__item" data-number="0">
                                            <label class="control-label">Добавить скидку</label><br>
                                            <select name="discounts[0][id]" class="select2" id="discounts-add__select-0">
                                                <option value="" selected>Выбрать услугу</option>
                                                <option disabled>Подписки</option>
                                                @foreach($subscriptions as $subscription)
                                                    <option value="Subscription-{{ $subscription->id }}">— {{ $subscription->title }}</option>
                                                @endforeach
                                                <option disabled>Курсы</option>
                                                @foreach($courses as $course)
                                                    <option value="Course-{{ $course->id }}">— {{ $course->title }}</option>
                                                @endforeach
                                                <option disabled>Услуги</option>
                                                @foreach($services as $service)
                                                    <option value="Service-{{ $service->id }}">— {{ $service->title }}</option>
                                                @endforeach
                                            </select>
                                            <div class="user-page__discount-field">
                                                <input type="number" class="form-control" min="1" max="100" step="1" name="discounts[0][count]">
                                                <span>%</span>
                                            </div>
                                            <div class="btn btn-success add_discount"><i class="voyager-plus"></i> Добавить скидку</div>
                                            <input type="hidden" name="discounts[0][added]" value="0" />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12">
                                    <h4>Платежные даные</h4>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="fio" placeholder="ФИО"
                                               value="{{ $item->fio ?? "" }}">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="email" placeholder="E-mail"
                                               value="{{ $item->email ?? "" }}">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="phone" placeholder="Телефон"
                                               value="{{ $item->phone ?? "" }}">
                                    </div>
                                </div>

                                <div class="col-xs-12">
                                    <h4>История оплат</h4>
                                    <div class="payment-history">
                                        @if($item->orders()->exists())
                                            <div class="payment-history__items">
                                                @foreach($orders as $order)
                                                    <div class="payment-history__item">{{ $order->created_at->format("d.m.Y") }} — {{ $order->description }}</div>
                                                @endforeach
                                            </div>
                                        @else
                                            Нет истории оплат
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="panel panel panel-bordered panel-warning">
                        <div class="panel-body">
                            <h4>Аватар</h4>
                            <div class="form-group">
                                @if(isset($item->avatar))
                                    <img src="{{ filter_var($item->avatar, FILTER_VALIDATE_URL) ? $item->avatar : Voyager::image( $item->avatar ) }}" style="width:200px; height:auto; clear:both; display:block; padding:2px; border:1px solid #ddd; margin-bottom:10px;" />
                                @endif
                                <input type="file" data-name="avatar" name="avatar">
                            </div>
                        </div>
                    </div>

                    <div class="panel panel panel-bordered panel-warning" id="telegram-chat-block-wrap">
                        <div class="panel-body">
                            <h4>Чат в Telegram</h4>
                            <div class="telegram-chat">
                                <div id="telegram-chat-block" class="telegram-chat__block" data-page="2">
                                    @foreach($telegram_messages as $message)
                                        <x-telegram-message :message="$message" :user="$item"></x-telegram-message>
                                    @endforeach
                                </div>
                                <div id="telegram-chat-form" class="telegram-chat__form">
                                    <textarea class="form-control" name="message"></textarea>
                                    <button type="button" class="btn btn-success">Отправить</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary pull-right save">
                {{ __('voyager::generic.save') }}
            </button>
        </form>
    </div>
@stop

@section('javascript')
    <script>
        $(document).ready(function () {
            let subscriptionsJson = @json($subscriptions);
            let servicesJson      = @json($services);
            let coursesJson       = @json($courses);

            $('.toggleswitch').bootstrapToggle({
                on: "Да",
                off: "Нет",
            });

            $(".user-page__subscriptions-auto input").on("change", function() {
                let $item  = $(this).closest(".user-page-update__item");
                let number = $item.data("number");

                $item.find('input[name="update_subscriptions[' + number + '][updated]"]').val(1);
            });

            $(document).on("change", ".subscriptions-add__select-sub", function(){
                let $this          = $(this);
                let number         = $this.closest(".user-page-add__item").data("number");
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

            $(document).on("click", ".add_subscription", function() {
                let $this  = $(this);
                let $item  = $this.closest(".user-page-add__item");
                let number = $item.data("number");

                $item.append('<div class="btn btn-danger remove_item"><i class="voyager-trash"></i> Удалить</div>');
                $item.find('input[name="subscriptions[' + number + '][added]"]').val(1);

                number++;
                let html = '<div class="user-page-add__item" data-number="' + number + '">' +
                    '<label class="control-label">Добавить подписку</label><br>' +
                    '<select name="subscriptions[' + number + '][id]" class="select2 subscriptions-add__select-sub" id="subscriptions-add__select-sub-' + number + '">' +
                    '<option value="">Выбрать подписку</option>';
                $.each(subscriptionsJson, function(index, index_data){
                    html += '<option value="' + index_data.id + '">' + index_data.title + '</option>';
                });
                html += '</select>' +
                    '<select name="subscriptions[' + number + '][period]" class="select2" id="subscriptions-add__select-period-' + number + '">' +
                    '<option value="">Выберите период</option>' +
                    '</select>' +
                    '<div class="btn btn-success add_subscription"><i class="voyager-plus"></i> Добавить подписку</div>' +
                    '<input type="hidden" name="subscriptions[' + number + '][added]" value="0" />' +
                    '</div>';

                $(".subscriptions-add").append(html);
                $this.remove();
                $("#subscriptions-add__select-sub-" + number + ", #subscriptions-add__select-period-" + number).select2();
            });

            $(document).on("click", ".add_service", function() {
                let $this  = $(this);
                let $item  = $this.closest(".user-page-add__item");
                let number = $item.data("number");

                $item.append('<div class="btn btn-danger remove_item"><i class="voyager-trash"></i> Удалить</div>');
                $item.find('input[name="services[' + number + '][added]"]').val(1);

                number++;
                let html = '<div class="user-page-add__item" data-number="' + number + '">' +
                    '<label class="control-label">Добавить услугу</label><br>' +
                    '<select name="services[' + number + '][id]" class="select2" id="services-add__select-' + number + '">' +
                    '<option value="">Выбрать услугу</option>';
                $.each(servicesJson, function(index, index_data){
                    html += '<option value="' + index_data.id + '">' + index_data.title + '</option>';
                });
                html += '</select>' +
                    '<div class="btn btn-success add_service"><i class="voyager-plus"></i> Добавить услугу</div>' +
                    '<input type="hidden" name="services[' + number + '][added]" value="0" />' +
                    '</div>';

                $(".services-add").append(html);
                $this.remove();
                $("#services-add__select-" + number).select2();
            });

            $(document).on("click", ".add_course", function() {
                let $this  = $(this);
                let $item  = $this.closest(".user-page-add__item");
                let number = $item.data("number");

                $item.append('<div class="btn btn-danger remove_item"><i class="voyager-trash"></i> Удалить</div>');
                $item.find('input[name="courses[' + number + '][added]"]').val(1);

                number++;
                let html = '<div class="user-page-add__item" data-number="' + number + '">' +
                    '<label class="control-label">Добавить курс</label><br>' +
                    '<select name="courses[' + number + '][id]" class="select2" id="courses-add__select-' + number + '">' +
                    '<option value="">Выбрать курс</option>';
                $.each(coursesJson, function(index, index_data){
                    html += '<option value="' + index_data.id + '">' + index_data.title + '</option>';
                });
                html += '</select>' +
                    '<div class="btn btn-success add_course"><i class="voyager-plus"></i> Добавить курс</div>' +
                    '<input type="hidden" name="courses[' + number + '][added]" value="0" />' +
                    '</div>';

                $(".courses-add").append(html);
                $this.remove();
                $("#courses-add__select-" + number).select2();
            });

            $(document).on("click", ".add_discount", function() {
                let $this  = $(this);
                let $item  = $this.closest(".user-page-add__item");
                let number = $item.data("number");

                $item.append('<div class="btn btn-danger remove_item"><i class="voyager-trash"></i> Удалить</div>');
                $item.find('input[name="discounts[' + number + '][added]"]').val(1);

                number++;
                let html = '<div class="user-page-add__item" data-number="' + number + '">' +
                                '<label class="control-label">Добавить скидку</label><br>' +
                                '<select name="discounts[' + number + '][id]" class="select2" id="discounts-add__select-' + number + '">' +
                                    '<option value="">Выбрать услугу</option>' +
                                    '<option disabled>Подписки</option>';
                                    $.each(subscriptionsJson, function(index, index_data){
                                        html += '<option value="Subscription-' + index_data.id + '">' + index_data.title + '</option>';
                                    });
                                    html += '<option disabled>Курсы</option>';
                                    $.each(coursesJson, function(index, index_data){
                                        html += '<option value="Course-' + index_data.id + '">' + index_data.title + '</option>';
                                    });
                                    html += '<option disabled>Услуги</option>';
                                    $.each(servicesJson, function(index, index_data){
                                        html += '<option value="Service-' + index_data.id + '">' + index_data.title + '</option>';
                                    });
                        html += '</select>' +
                                '<div class="user-page__discount-field">' +
                                '<input type="number" class="form-control" min="1" max="100" step="1" name="discounts[' + number + '][count]">' +
                                '<span>%</span>' +
                                '</div>' +
                                '<div class="btn btn-success add_discount"><i class="voyager-plus"></i> Добавить скидку</div>' +
                                '<input type="hidden" name="discounts[' + number + '][added]" value="0" />' +
                            '</div>';

                $(".discounts-add").append(html);
                $this.remove();
                $("#discounts-add__select-" + number).select2();
            });

            $(document).on("click", ".remove_item", function() {
                $(this).closest(".user-page-add__item").remove();
            });

            $(document).on("click", ".remove_update_subscription", function() {
                let $item           = $(this).closest(".user-page-update__subscription-item");
                let number          = $item.data("number");
                let subscription_id = $item.data("subscription-id");

                $(this).closest(".user-page-update__subscriptions").append('<input type="hidden" name="delete_subscriptions[' + number + ']" value="' + subscription_id + '" />');
                $item.remove();
            });

            $(document).on("click", ".remove_update_service", function() {
                let $item      = $(this).closest(".user-page-update__item");
                let number     = $item.data("number");
                let service_id = $item.data("service-id");

                $(this).closest(".user-page-update__services").append('<input type="hidden" name="delete_services[' + number + ']" value="' + service_id + '" />');
                $item.remove();
            });

            $(document).on("click", ".remove_update_course", function() {
                let $item      = $(this).closest(".user-page-update__item");
                let number     = $item.data("number");
                let course_id = $item.data("course-id");

                $(this).closest(".user-page-update__courses").append('<input type="hidden" name="delete_courses[' + number + ']" value="' + course_id + '" />');
                $item.remove();
            });

            $(document).on("click", ".remove_update_discount", function() {
                let $item       = $(this).closest(".user-page-update__item");
                let number      = $item.data("number");
                let discount_id = $item.data("discount-id");

                $(this).closest(".user-page-update__discounts").append('<input type="hidden" name="delete_discounts[' + number + ']" value="' + discount_id + '" />');
                $item.remove();
            });

            $(document).on("click", ".extend_subscription", function() {
                let $item  = $(this).closest(".user-page-update__subscription-item");
                let number = $item.data("number");

                $(this).remove();
                $item.find('.select2').show();
                $item.find('.user-page__subscriptions-bill').show();
                $item.find('input[name="update_subscriptions[' + number + '][updated]"]').val(1);
            });

            const telegramChatblock = document.getElementById("telegram-chat-block");
            telegramChatblock.scrollTop = telegramChatblock.scrollHeight;

            $("#telegram-chat-block").scroll(function(){
                let $this   = $(this);
                let page    = $this.data("page");
                let user_id = {{ $item->id }};

                if (telegramChatblock.scrollTop === 0) {
                    $.ajax({
                        url: "{{ route("voyager.users.telegram-messages") }}",
                        type: "POST",
                        data: {
                            page,
                            user_id,
                        },
                        success: function(response) {
                            console.log(response);

                            if (response.success === true) {
                                if (response.data) {
                                    $("#telegram-chat-block").prepend(response.data);
                                    telegramChatblock.scrollTop = 50;
                                }

                                page++;
                                $this.data("page", page);
                            }
                        },
                    });
                }
            });

            $("#telegram-chat-form").on("click", "button", function(){
                let $textarea = $(this).siblings('textarea[name="message"]');
                let message   = $textarea.val();
                let user_id   = {{ $item->id }};

                if (message !== "") {
                    $.ajax({
                        url: "{{ route("voyager.users.send-telegram-message") }}",
                        type: "POST",
                        data: {
                            message,
                            user_id,
                        },
                        success: function(response) {
                            console.log(response);

                            $textarea.val("");
                            if (response.success === false) {
                                $("#telegram-chat-block").append("<p class='error'>Сообщение не может быть доставлено</p>");
                            }
                        },
                    });
                }
            });

            setInterval(function() {
                let user_id = {{ $item->id }};
                let last_message_id = $("#telegram-chat-block").find(".telegram-chat__message").last().data("id");

                $.ajax({
                    url: "{{ route("voyager.users.new-telegram-messages") }}",
                    type: "POST",
                    data: {
                        user_id,
                        last_message_id,
                    },
                    success: function(response) {
                        if (response.success === true) {
                            if (response.data) {
                                $("#telegram-chat-block").append(response.data);
                                telegramChatblock.scrollTop = telegramChatblock.scrollHeight;
                            }
                        }
                    },
                });
            }, 1000);
        });
    </script>
@stop
