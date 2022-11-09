@extends("app.layout")

@section("css")
    <link rel="stylesheet" href="{{ asset("plugins/fancybox/fancybox/jquery.fancybox-1.3.4.css") }}" type="text/css" media="screen" />
@stop

@section("scripts")
    {{--<script type="text/javascript" src="{{ asset("plugins/fancybox/fancybox/jquery.fancybox-1.3.4.pack.js") }}"></script>--}}
@stop

@section("content")
    <div class="page-line">
        <div class="block-name1">
            <div class="block-name1__foto" style="background-image: url({{ filter_var($user->avatar, FILTER_VALIDATE_URL) ? $user->avatar : Voyager::image($user->avatar) }});">
                @if($subscription_icon)
                    <i class="pro" style="background: url({{ Voyager::image( $subscription_icon ) }}) no-repeat 0 0; background-size: 46px 54px;"></i>
                @endif
            </div>
            <div class="block-name1__text">
                <div class="block-name1__text-vertical">
                    <div class="block-name1__name">Приветствую, <span>{{ $user->name }}</span></div>
                    <div class="block-name1__contact">
                        <a href="" class="block-name1__contact-edit">Telegram: {{ "@" . $user->telegram_name }}</a>
                    </div>
                </div>
            </div>
            <a href="{{ route("user.logout") }}" class="block-name1__exit"><span>Выйти</span></a>
        </div>
        <div class="block-lk1 style1">
            <div class="block-lk1__title-top">Ваши подписки</div>
            <table class="table-subscribe1">
                @foreach($user->subscriptions as $subscription)
                    <tr>
                        <td>
                            <div class="table-subscribe1__title1"><span>{{ $subscription->title }}</span> до {{ $subscription->date_end }}</div>
                        </td>
                        <td>
                            <a href="" class="table-subscribe1__more">Подробнее</a></td>
                        <td>
                            <div class="table-subscribe1__days-left">{{ $subscription->days_left_human }}<i></i></div>
                        </td>
                        <td>
                            <a href="" class="table-subscribe1__extend">ПРОДЛИТЬ ПОДПИСКУ</a>
                        </td>
                    </tr>
                @endforeach
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
            <a href="" class="block-lk1__link-bottom">Все варианты подписок</a>
        </div>
        <div class="block-lk1 style2">
            <div class="block-lk1__title-top">Графики с аналитикой</div>
            <table class="table-subscribe1">
                @foreach($user->subscriptions as $subscription)
                    @foreach($subscription->graph_categories as $category)
                        <tr>
                            <td>
                                <div class="table-subscribe1__title1"><span>{{ $category->title }}</span> до {{ $subscription->date_end }}</div>
                            </td>
                            <td><a href="" class="table-subscribe1__more2">Подробнее от подписке</a></td>
                            <td>
                                <div class="table-subscribe1__days-left">{{ $subscription->days_left_human }}</div>
                            </td>
                            <td><a href="" class="table-subscribe1__extend">ПРОДЛИТЬ ПОДПИСКУ</a></td>
                        </tr>
                    @endforeach
                @endforeach
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
            <a href="" class="block-lk1__link-bottom">Все варианты подписок на графики</a>
        </div>
        <div class="block-lk1 style3">
            <div class="block-lk1__title-top">Обучающие семинары</div>
            <table class="training-seminars1">
                @foreach($user->courses as $course)
                    <tr>
                        <td><a href="https://www.youtube.com/watch?v=A3PDXmYoF5U" data-fancybox class="training-seminars1__video" style="background-image: url(img/vd1.jpg);"></a></td>
                        <td>
                            <div class="training-seminars1__title1"><a href="">Каналы, фракталы, жилые массивы</a></div>
                        </td>
                        <td>Приобретено 20.12.2022</td>
                        <td><a href="" class="training-seminars1__link">На страницу просмотра</a></td>
                    </tr>
                @endforeach
                @foreach($user->services as $service)
                @endforeach

                <tr>
                    <td><a href="https://www.youtube.com/watch?v=A3PDXmYoF5U" data-fancybox class="training-seminars1__video" style="background-image: url(img/vd2.jpg);"></a></td>
                    <td>
                        <div class="training-seminars1__title1"><a href="">Как управлять мировой торговлей, не привлекая внимания санитаров</a></div>
                    </td>
                    <td>Приобретено 20.12.2022</td>
                    <td><a href="" class="training-seminars1__link">На страницу просмотра</a></td>
                </tr>
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
            <a href="" class="block-lk1__link-bottom">Все обучающие семинары</a>
        </div>
    </div>
@stop

@section("javascript")
    <script>
        $(document).ready(function () {
            console.log("test");

        });
    </script>
@stop
