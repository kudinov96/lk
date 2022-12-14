@extends("app.layout")

@section("title", setting("site.profile_title"))
@section("description", setting("site.profile_description"))

@section("content")
    <div class="page-line">
        <div class="block-name1">
            <div class="block-name1__foto" style="background-image: url({{ filter_var($user->avatar, FILTER_VALIDATE_URL) ? $user->avatar : Voyager::image($user->avatar) }});">
                @if($subscriptionIcon)
                    <i class="pro" style="background: url({{ Voyager::image( $subscriptionIcon ) }}) no-repeat 0 0; background-size: 46px 54px;"></i>
                @endif
            </div>
            <div class="block-name1__text">
                <div class="block-name1__text-vertical">
                    <div class="block-name1__name">Приветствую, <span>{{ $user->name }}</span></div>
                    <div class="block-name1__contact">
                        <a href="#" data-fancybox data-src="#modal-edit-user" class="block-name1__contact-edit">Telegram: {{ "@" . $user->telegram_name }}</a>
                    </div>
                </div>
            </div>
            <a href="{{ route("user.logout") }}" class="block-name1__exit"><span>Выйти</span></a>
        </div>
        @if(setting("site.has_subsctiptions"))
            <div class="block-lk1 style1">
                @if($userSubscriptionsWithoutCategories->isNotEmpty())
                    <div class="block-lk1__title-top">Ваши подписки</div>
                @else
                    <div class="block-lk1__title-top">У вас нет подписок</div>
                @endif
                <table class="table-subscribe1">
                    @if($userSubscriptionsWithoutCategories->isNotEmpty())
                        @foreach($userSubscriptionsWithoutCategories as $key => $subscription)
                            <tr class="item">
                                <td>
                                    <div class="item__title table-subscribe1__title1"><span>{{ $subscription->title }}</span> до {{ $subscription->date_end }}</div>
                                </td>
                                <td>
                                    <a href="#" data-fancybox data-src="#modal-subscription-without-{{ $key }}" class="table-subscribe1__more">Подробнее</a></td>
                                <td>
                                    <div class="table-subscribe1__days-left">{{ $subscription->days_left_human }}<i></i></div>
                                </td>
                                <td>
                                    <a href="#" class="subscription-buy__open table-subscribe1__extend">ПРОДЛИТЬ ПОДПИСКУ</a>
                                    <div class="subscription-buy__block hidden">
                                        <x-subscription-buy :subscription="$subscription" :user="$user"></x-subscription-buy>
                                    </div>
                                </td>
                                <div class="hidden">
                                    <div id="modal-subscription-without-{{ $key }}">
                                        <h2>{{ $subscription->title }}</h2>
                                        {!! $subscription->content !!}
                                    </div>
                                </div>
                            </tr>
                        @endforeach
                    @else
                        @if($subscriptionsWithoutCategories->isNotEmpty())
                            @foreach($subscriptionsWithoutCategories as $key => $subscription)
                                <tr class="item">
                                    <td>
                                        <div class="item__title table-subscribe1__title1"><span>{{ $subscription->title }}</span></div>
                                    </td>
                                    <td>
                                        <a href="#" data-fancybox data-src="#modal-subscription-{{ $key }}" class="table-subscribe1__more">Подробнее</a></td>
                                    <td>
                                        <x-subscription-buy :subscription="$subscription" :user="$user"></x-subscription-buy>
                                    </td>
                                    <div class="hidden">
                                        <div id="modal-subscription-{{ $key }}">
                                            <h2>{{ $subscription->title }}</h2>
                                            {!! $subscription->content !!}
                                        </div>
                                    </div>
                                </tr>
                            @endforeach
                        @endif
                    @endif
                    {{--<tr>
                            <td class="no-padding-mobile">
                                <div class="table-subscribe1__title2">
                                    <span>персональное предложение со скидкой!</span>
                                    <p>подписка ULTRA VIP Luxury 3000</p>
                                </div>
                            </td>
                            <td><a href="" class="table-subscribe1__more">Подробнее</a></td>
                            <td colspan="2">
                                <div class="block-lk1__button">
                                    <div class="block-lk1__button-select">
                                        <div class="select-price1">
                                            <div class="select-price1__current"><div><span>8 200 ₽</span> → 6 666 ₽ (скидка 20%)</div></div>
                                            <div class="select-price1__drop">
                                                <div class="select-price1__drop-item"><span>8 200 ₽</span> → 6 666 ₽ (скидка 20%)</div>
                                                <div class="select-price1__drop-item"><span>12 200 ₽</span> → 10 666 ₽ (скидка 200%)</div>
                                                <div class="select-price1__drop-item"><span>15 200 ₽</span> → 99 666 ₽ (скидка 2000%)</div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="" class="block-lk1__button-buy">КУПИТЬ ПОДПИСКУ</a>
                                    <div class="block-lk1__button-close"></div>
                                </div>
                            </td>
                        </tr>--}}
                </table>
                <a href="{{ route("user.subscriptions-without-categories") }}" class="block-lk1__link-bottom">Все варианты подписок</a>
            </div>
        @endif
        <div class="block-lk1 style2">
            @if($userSubscriptionsWithCategories->isNotEmpty())
                <div class="block-lk1__title-top">Графики с аналитикой</div>
            @else
                <div class="block-lk1__title-top">У вас нет подписок на графики</div>
            @endif
            <table class="table-subscribe1">
                @if($userSubscriptionsWithCategories->isNotEmpty())
                    @foreach($userSubscriptionsWithCategories as $key => $subscription)
                        <tr class="item">
                            <td>
                                <a href="{{ route("user.graphs") }}" class="item__title table-subscribe1__title1"><span>{{ $subscription->title }}</span></a>
                            </td>
                            <td><a href="#" data-fancybox data-src="#modal-subscription-with-{{ $key }}" class="table-subscribe1__more2">Подробнее от подписке</a></td>
                            <td>
                                <div class="table-subscribe1__days-left">{{ $subscription->days_left_human }}</div>
                            </td>
                            <td>
                                <a href="#" class="subscription-buy__open table-subscribe1__extend">ПРОДЛИТЬ ПОДПИСКУ</a>
                                <div class="subscription-buy__block hidden">
                                    <x-subscription-buy :subscription="$subscription" :user="$user"></x-subscription-buy>
                                </div>
                            </td>
                            <div class="hidden">
                                <div id="modal-subscription-with-{{ $key }}">
                                    <h2>{{ $subscription->title }}</h2>
                                    {!! $subscription->content !!}
                                </div>
                            </div>
                        </tr>
                    @endforeach
                @else
                    @if($subscriptionsWithCategories->isNotEmpty())
                        @foreach($subscriptionsWithCategories as $key => $subscription)
                            <tr class="item">
                                <td>
                                    <a href="{{ route("user.graphs") }}" class="item__title table-subscribe1__title1"><span>{{ $subscription->title }}</span></a>
                                </td>
                                <td>
                                    <a href="#" data-fancybox data-src="#modal-subscription-{{ $key }}" class="table-subscribe1__more">Подробнее</a></td>
                                <td>
                                    <x-subscription-buy :subscription="$subscription" :user="$user"></x-subscription-buy>
                                </td>
                                <div class="hidden">
                                    <div id="modal-subscription-{{ $key }}">
                                        <h2>{{ $subscription->title }}</h2>
                                        {!! $subscription->content !!}
                                    </div>
                                </div>
                            </tr>
                        @endforeach
                    @endif
                @endif
                {{--<tr>
                    <td class="no-padding-mobile">
                        <div class="table-subscribe1__title3">Графики акций (неоплачено)</div>
                    </td>
                    <td><a href="" class="table-subscribe1__more2">Подробнее от подписке</a></td>
                    <td colspan="2">
                        <div class="block-lk1__button">
                            <div class="block-lk1__button-select">
                                <div class="select-price1">
                                    <div class="select-price1__current"><div>3 месяца 6 666 Р (скидка 10%)</div></div>
                                    <div class="select-price1__drop">
                                        <div class="select-price1__drop-item">3 месяца 6 666 Р (скидка 10%)</div>
                                        <div class="select-price1__drop-item">6 месяца 6 666 Р (скидка 20%)</div>
                                        <div class="select-price1__drop-item">9 месяца 6 666 Р (скидка 30%)</div>
                                    </div>
                                </div>
                            </div>
                            <a href="" class="block-lk1__button-buy">ПРОДЛИТЬ</a>
                            <div class="block-lk1__button-close"></div>
                        </div>
                    </td>
                </tr>--}}
            </table>
            <a href="{{ route("user.subscriptions-with-categories") }}" class="block-lk1__link-bottom">Все варианты подписок на графики</a>
        </div>
		@if(setting('site.has_courses'))
            <div class="block-lk1 style3">
                <div class="block-lk1__title-top">Обучающие семинары</div>
                @if($user->courses->isNotEmpty() || $user->services->isNotEmpty())
                    <table class="training-seminars1">
                        @foreach($user->courses as $course)
                            <tr>
                                <td><a href="{{ $course->link }}" target="_blank" class="training-seminars1__video" style="background-image: url({{ Voyager::image($course->preview) }});"></a></td>
                                <td>
                                    <div class="training-seminars1__title1"><a href="{{ $course->link }}" target="_blank">{{ $course->title }}</a></div>
                                </td>
                                <td>Приобретено {{ $course->date_start }}</td>
                                <td><a href="{{ $course->link }}" target="_blank" class="training-seminars1__link">На страницу просмотра</a></td>
                            </tr>
                        @endforeach
                        @foreach($user->services as $key => $service)
                            <tr>
                                <td>
                                    <a href="#" data-fancybox data-src="#modal-service-{{ $key }}" class="training-seminars1__video without_icon" style="background-image: url({{ Voyager::image($service->preview) }});"></a>
                                </td>
                                <td>
                                    <div class="training-seminars1__title1"><a href="#" data-fancybox data-src="#modal-service-{{ $key }}">{{ $service->title }}</a></div>
                                </td>
                                <td>Приобретено {{ $service->date_start }}</td>
                                <td><a href="#" data-fancybox data-src="#modal-service-{{ $key }}" class="training-seminars1__link without_icon">Просмотр</a></td>
                                <div class="hidden">
                                    <div id="modal-service-{{ $key }}">
                                        <h2>{{ $service->title }}</h2>
                                        {!! $service->content !!}
                                    </div>
                                </div>
                            </tr>
                        @endforeach
                        {{--                <tr>
                            <td class="mod1">
                                <div class="show-mobile1">
                                    <div class="training-seminars1__title3">рекомендуем:</div>
                                </div>
                                <a href="https://www.youtube.com/watch?v=A3PDXmYoF5U" data-fancybox class="training-seminars1__video" style="background-image: url(img/vd3.jpg);"></a>
                                <div class="show-mobile1">
                                    <div class="training-seminars1__title4"><a href="">Фрактальные уравнения получения бабла</a></div>
                                </div>
                            </td>
                            <td>
                                <div class="hide-mobile1">
                                    <div class="training-seminars1__title2">
                                        <span>рекомендуем:</span>
                                        <p><a href="">Фрактальные уравнения получения бабла</a></p>
                                    </div>
                                </div>
                            </td>
                            <td class="clear-clean"><a href="" class="training-seminars1__more">Подробнее</a></td>
                            <td class="last">
                                <div class="block-lk1__button">
                                    <div class="block-lk1__button-select">
                                        <div class="block-lk1__button-small">6 666 Р (скидка 10%)</div>
                                    </div>
                                    <a href="" class="block-lk1__button-buy mod1">КУПИТЬ ДОСТУП</a>
                                    <div class="block-lk1__button-close"></div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="mod1">
                                <div class="show-mobile1">
                                    <div class="training-seminars1__title3">ранее вы интересовались:</div>
                                </div>
                                <a href="https://www.youtube.com/watch?v=A3PDXmYoF5U" data-fancybox class="training-seminars1__video" style="background-image: url(img/vd4.jpg);"></a>
                                <div class="show-mobile1">
                                    <div class="training-seminars1__title4"><a href="">Быки и медведи: как управлять зоопарком</a></div>
                                </div>
                            </td>
                            <td>
                                <div class="hide-mobile1">
                                    <div class="training-seminars1__title2">
                                        <span>ранее вы интересовались:</span>
                                        <p><a href="">Быки и медведи: как управлять зоопарком</a></p>
                                    </div>
                                </div>
                            </td>
                            <td class="clear-clean"><a href="" class="training-seminars1__more">Подробнее</a></td>
                            <td class="last">
                                <div class="block-lk1__button">
                                    <div class="block-lk1__button-select">
                                        <div class="block-lk1__button-small">6 666 Р (скидка 10%)</div>
                                    </div>
                                    <a href="" class="block-lk1__button-buy mod1">КУПИТЬ ДОСТУП</a>
                                    <div class="block-lk1__button-close"></div>
                                </div>
                            </td>
                        </tr>--}}
                    </table>
                @else
                    <table class="training-seminars1">
                        <tr>
                            <td>
                                <p>У вас нет купленных семинаров</p>
                            </td>
                        </tr>
                    </table>
                @endif
                <a href="{{ route("user.services") }}" class="block-lk1__link-bottom">Все обучающие семинары</a>
            </div>
		@endif
    </div>

    <x-service-buy-modal></x-service-buy-modal>

    <div class="hidden">
        <div class="modal-default" id="modal-edit-user">
            <h2>Редактировать</h2>

            <form action="{{ route("user.update", ["id" => $user->id]) }}" method="POST" id="edit-user">
                @csrf
                @method("PUT")

                <label>
                    <input type="text" placeholder="Имя" name="name" value="{{ $user->name }}">
                </label>
                <label>
                    <input type="text" placeholder="Telegram никнейм" name="telegram_name" value="{{ $user->telegram_name }}">
                </label>

                <input type="submit" value="Сохранить">
            </form>
        </div>
    </div>

@stop

@section("javascript")
    <x-service-buy-js></x-service-buy-js>

    <script>
        $(document).ready(function () {

            $(document).on("click", ".subscription-buy__open", function(e){
                e.preventDefault();

                $(this).hide();
                $(this).siblings(".subscription-buy__block").show();
            });

        });
    </script>
@stop
