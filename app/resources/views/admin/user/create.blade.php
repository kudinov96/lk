@extends('admin.layout')

@section('page_title', 'Добавить пользователя')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-person"></i>
        Добавить пользователя
    </h1>
@stop

@section('content')
    <div class="page-content container-fluid user-page">
        <form class="form-edit-add" action="{{ route("voyager.users.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method("POST")

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
                                               value="{{ old("name") }}">
                                    </div>
                                </div>

                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="telegram_name" placeholder="Никнейм в Telegram"
                                               value="{{ old("telegram_name") }}">
                                    </div>
                                </div>

                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="email" placeholder="E-mail"
                                               value="{{ old("email") }}">
                                    </div>
                                </div>

                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="password" placeholder=Пароль
                                               value="{{ old("password") }}">
                                    </div>
                                </div>

                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <select class="select2" name="role_id">
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
                                    <h4>Подписки</h4>

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
                                                <input type="number" class="form-control" name="discounts[0][count]">
                                                <span>%</span>
                                            </div>
                                            <div class="btn btn-success add_discount"><i class="voyager-plus"></i> Добавить скидку</div>
                                            <input type="hidden" name="discounts[0][added]" value="0" />
                                        </div>
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
                                <input type="file" data-name="avatar" name="avatar">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary pull-right save">
                {{ __('voyager::generic.add') }}
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

            console.log(subscriptionsJson);

            $('.toggleswitch').bootstrapToggle({
                on: "Да",
                off: "Нет",
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
                                    '<input type="number" class="form-control" name="discounts[' + number + '][count]">' +
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

        });
    </script>
@stop
