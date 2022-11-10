@extends("app.layout")

@section("content")
    <div class="page-line">
        <div class="title2"><span>Графики от Романа Андреева</span></div>

        @if($user->subscriptions()->exists())
            <div class="list-information1">
                <div class="list-information1__clm">
                    @foreach($user->subscriptions as $subscription)
                        @foreach($subscription->graph_categories as $category)
                            <div class="list-information1__item" style="border-color: {{ $category->color_border }};">
                                <div class="list-information1__title1" style="color: {{ $category->color_title }};">{{ $category->title }}</div>
                                <table class="list-information1__table">
                                    <tbody>
                                        @foreach($category->tools as $tool)
                                            <tr>
                                                <td>{{ $tool->title }}</td>
                                                @foreach($tool->data as $data_item)
                                                    @if(!$data_item["url"] || !$data_item["interval"])
                                                        <td><span></span></td>
                                                    @else
                                                        <td><a href="{{ $data_item["url"] }}" target="_blank">{{ $data_item["interval"] . $data_item["interval_code"] }}</a></td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    @endforeach
                    {{--<div class="list-information1__item style2">
                        <div class="list-information1__title1">Валюты</div>
                        <table class="list-information1__table">
                            <tbody><tr>
                                <td>BITCOIN</td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/jFcsNVaa/" target="_blank">4H<i>OUR</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>CNY/RUB</td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/JiTSsmrI/" target="_blank">1H<i>OUR</i></a></td>
                                <td><a href="https://ru.tradingview.com/chart/lyUmG4t4/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>EUR/RUB</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/D6N2e9av/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>EUR/USD</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://www.tradingview.com/chart/Y8ej1lK0/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>USD/RUB</td>
                                <td><a href="https://ru.tradingview.com/chart/d4LDEpKR/" target="_blank">15min</a></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/2EPTrMe4/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>USD/CNY</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/l0P1lSZu/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            </tbody></table>
                    </div>
                    <div class="list-information1__item style3">
                        <div class="list-information1__title1">ФЬЮЧЕРСЫ</div>
                        <table class="list-information1__table">
                            <tbody><tr>
                                <td>BRENT</td>
                                <td><a href="https://www.tradingview.com/chart/5vouXDgy/" target="_blank">15min</a></td>
                                <td><a href="https://ru.tradingview.com/chart/cH1ulqmP/" target="_blank">1H<i>OUR</i></a></td>
                                <td><a href="https://ru.tradingview.com/chart/X9vtdjjI/" target="_blank">1D<i>AY</i></a></td>
                                <td><a href="https://ru.tradingview.com/chart/c5ZE3HRM/" target="_blank">1W<i>EEK</i></a></td>
                                <td><a href="https://ru.tradingview.com/chart/U1gTBkka/" target="_blank">1M<i>ONTH</i></a></td>
                            </tr>
                            <tr>
                                <td>GOLD</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/MXwFoxwA/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>MICEX</td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/4Ab3S1nQ/" target="_blank">1H<i>OUR</i></a></td>
                                <td><a href="https://ru.tradingview.com/chart/owBAeVVC/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>RI</td>
                                <td><a href="https://ru.tradingview.com/chart/di4YpLvY/" target="_blank">15min</a></td>
                                <td><a href="https://ru.tradingview.com/chart/obLEvdUN/" target="_blank">1H<i>OUR</i></a></td>
                                <td><a href="https://ru.tradingview.com/chart/mHchJzu0/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/dLWc3pGa/" target="_blank">1M<i>ONTH</i></a></td>
                            </tr>
                            <tr>
                                <td>PLATINUM</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://www.tradingview.com/chart/jypcogWl/" target="_blank">1M<i>ONTH</i></a></td>
                            </tr>
                            <tr>
                                <td>SI</td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/AqC4ePzt/" target="_blank">1H<i>OUR</i></a></td>
                                <td><a href="https://ru.tradingview.com/chart/o6SbxwjV/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>SILVER</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/VfXPlAOd/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>Нат. газ</td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/rTKBjLAc/" target="_blank">1H<i>OUR</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            </tbody></table>
                    </div>
                    <div class="list-information1__item style4">
                        <div class="list-information1__title1">Акции</div>
                        <div class="list-information1__title2">Банки</div>
                        <table class="list-information1__table">
                            <tbody><tr>
                                <td>Сбербанк</td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/c6scd5kf/" target="_blank">1H<i>OUR</i></a></td>
                                <td><a href="https://ru.tradingview.com/chart/DPxYn4Ws/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/V1NdS8sk/" target="_blank">1M<i>ONTH</i></a></td>
                            </tr>
                            <tr>
                                <td>ВТБ</td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/Ny5cVMqa/" target="_blank">1H<i>OUR</i></a></td>
                                <td><a href="https://www.tradingview.com/chart/3yR0i668/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>Мосбиржа</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/lxwxpzuz/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>Тинькофф</td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/RwaYHXXB/" target="_blank">4H<i>OUR</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>Qiwi</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/JM3kYi4a/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            </tbody></table>
                        <div class="list-information1__title2">Нефть и газ</div>
                        <table class="list-information1__table">
                            <tbody><tr>
                                <td>Башнефть АП</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://www.tradingview.com/chart/zkqVLCCe/" target="_blank">1W<i>EEK</i></a></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>Газпром</td>
                                <td><span></span></td>
                                <td><a href="https://www.tradingview.com/chart/zan5S0ty/" target="_blank">1H<i>OUR</i></a></td>
                                <td><a href="https://www.tradingview.com/chart/a71ahpa9/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/YId93WLu/" target="_blank">1M<i>ONTH</i></a></td>
                            </tr>
                            <tr>
                                <td>Газпром Нефть</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://www.tradingview.com/chart/PEmwazIZ/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>Лукойл</td>
                                <td><span></span></td>
                                <td><a href="https://www.tradingview.com/chart/McENqnk3/" target="_blank">1H<i>OUR</i></a></td>
                                <td><a href="https://ru.tradingview.com/chart/ujeBNQli/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/Q5gmd4tk/" target="_blank">1M<i>ONTH</i></a></td>
                            </tr>
                            <tr>
                                <td>Новатэк</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/HFdFPAqb/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>Роснефть</td>
                                <td><span></span></td>
                                <td><a href="https://www.tradingview.com/chart/oFmVLl6l/" target="_blank">1H<i>OUR</i></a></td>
                                <td><a href="https://www.tradingview.com/chart/07VcVPyL/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>Сургут НГ</td>
                                <td><span></span></td>
                                <td><a href="https://www.tradingview.com/chart/TuPPS0zv/" target="_blank">1H<i>OUR</i></a></td>
                                <td><a href="https://www.tradingview.com/chart/ygvawg0R/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>Сургут НГ АП</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/6cZo2m0f/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>Татнефть</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://www.tradingview.com/chart/0DvaILCU/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>Татнефть АП</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/fSzQYGKS/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/r0tFB7j7/" target="_blank">1M<i>ONTH</i></a></td>
                            </tr>
                            <tr>
                                <td>Транснефть АП</td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/o1lKfCwf/" target="_blank">1H<i>OUR</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            </tbody></table>
                        <div class="list-information1__title2">металлы</div>
                        <table class="list-information1__table">
                            <tbody><tr>
                                <td>Ашинский Метзавод</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/OO12WfN9/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>ВСМПО Ависма</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/t7yN2d2d/" target="_blank">1W<i>EEK</i></a></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>ГМК Норникель</td>
                                <td><span></span></td>
                                <td><a href="https://www.tradingview.com/chart/yqfwPKSv/" target="_blank">1H<i>OUR</i></a></td>
                                <td><a href="https://ru.tradingview.com/chart/b7AIFHA4/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/lFQsuPHm/" target="_blank">1M<i>ONTH</i></a></td>
                            </tr>
                            <tr>
                                <td>ММК</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://www.tradingview.com/chart/hCYntP6z/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/WKfkfHQh/" target="_blank">1M<i>ONTH</i></a></td>
                            </tr>
                            <tr>
                                <td>НЛМК</td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/PM8p9Zh4/" target="_blank">4H<i>OUR</i></a></td>
                                <td><a href="https://www.tradingview.com/chart/ET2mB08m/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/i9lGQ7sy/" target="_blank">1M<i>ONTH</i></a></td>
                            </tr>
                            <tr>
                                <td>Полюс золото</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/dFn3D1Is/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>Полиметалл</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/2uz3newx/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>РУСАЛ</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/6KfT1z7K/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>Северсталь</td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/WAl0iuPB/" target="_blank">1H<i>OUR</i></a></td>
                                <td><a href="https://ru.tradingview.com/chart/j3D3B6XR/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/28dwd6vL/" target="_blank">1M<i>ONTH</i></a></td>
                            </tr>
                            <tr>
                                <td>ТМК</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/5s4Sz9P7/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            </tbody></table>
                        <div class="list-information1__title2">горнодобывающие</div>
                        <table class="list-information1__table">
                            <tbody><tr>
                                <td>Алроса</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://www.tradingview.com/chart/MfNhHfMM/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/4XBc9tS0/" target="_blank">1M<i>ONTH</i></a></td>
                            </tr>
                            <tr>
                                <td>МЕЧЕЛ АО</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/dbivMrBH/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>МЕЧЕЛ АП</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/sO10S6Xk/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>Распадская</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://www.tradingview.com/chart/UshaXMUD/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>

                            </tbody></table>
                        <div class="list-information1__title2">ритейл</div>
                        <table class="list-information1__table">
                            <tbody><tr>
                                <td>Детский Мир</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/29vTJ2L8/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>Лента</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://www.tradingview.com/chart/yqvbDssR/" target="_blank">1W<i>EEK</i></a></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>Магнит</td>
                                <td><span></span></td>
                                <td><a href="https://www.tradingview.com/chart/Vhcw5Erk/" target="_blank">1H<i>OUR</i></a></td>
                                <td><a href="https://ru.tradingview.com/chart/eUFVQxj6/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/CNhiBYXz/" target="_blank">1M<i>ONTH</i></a></td>
                            </tr>
                            <tr>
                                <td>М.Видео</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://www.tradingview.com/chart/23wtLwQ7/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>ОЗОН</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/ABfkWFTD/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>X5 RETAIL</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://www.tradingview.com/chart/g9ZInw9n/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            </tbody></table>
                        <div class="list-information1__title2">Энергетика</div>
                        <table class="list-information1__table">
                            <tbody><tr>
                                <td>Интер РАО</td>
                                <td><span></span></td>
                                <td><a href="https://www.tradingview.com/chart/U18vC348/" target="_blank">1H<i>OUR</i></a></td>
                                <td><a href="https://www.tradingview.com/chart/PSysIG94/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>ОГК-2</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/W962Z37l/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/Yh7JtNDK/" target="_blank">1M<i>ONTH</i></a></td>
                            </tr>
                            <tr>
                                <td>Русгидро</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/6bpLEOim/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>Россети АП</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://www.tradingview.com/chart/ce9PhHFL/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>Россети АО</td>
                                <td><span></span></td>
                                <td><a href="https://www.tradingview.com/chart/ipIPO62c/" target="_blank">1H<i>OUR</i></a></td>
                                <td><a href="https://www.tradingview.com/chart/YzlTqRJH/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>ТГК-1</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://www.tradingview.com/chart/vaKQkvi8/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>ЭНЕЛРОС</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/tdx29ajJ/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>Юнипро</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://www.tradingview.com/chart/dDXn5hfg/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            </tbody></table>
                        <div class="list-information1__title2">it/телеком</div>
                        <table class="list-information1__table">
                            <tbody><tr>
                                <td>ВК (Mail.ru) гдр</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/TkL88guY/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>МТС</td>
                                <td><span></span></td>
                                <td><a href="https://www.tradingview.com/chart/GNC0ycfl/" target="_blank">1H<i>OUR</i></a></td>
                                <td><a href="https://www.tradingview.com/chart/6GydMxky/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/8bKu3njK/" target="_blank">1M<i>ONTH</i></a></td>
                            </tr>
                            <tr>
                                <td>МГТС</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/eWCEE7Q0/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>РОСТЕЛЕКОМ</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/w5CUVJdj/" target="_blank">1M<i>ONTH</i></a></td>
                            </tr>
                            <tr>
                                <td>Яндекс</td>
                                <td><span></span></td>
                                <td><a href="https://www.tradingview.com/chart/BsJNFIZz/" target="_blank">1H<i>OUR</i></a></td>
                                <td><a href="https://ru.tradingview.com/chart/nJq3738u/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            </tbody></table>
                        <div class="list-information1__title2">Другое</div>
                        <table class="list-information1__table">
                            <tbody><tr>
                                <td>АФК «СИСТЕМА»</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/4YS4XY2J/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/tS6hSX4h/" target="_blank">1M<i>ONTH</i></a></td>
                            </tr>
                            <tr>
                                <td>АЭРОФЛОТ</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/IgWrGoP9/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/3n19ohXy/" target="_blank">1M<i>ONTH</i></a></td>
                            </tr>
                            <tr>
                                <td>КАМАЗ</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/uyx1q0m3/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>ПАО "ИСКЧ"</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/QvmcnaRz/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>СЕГЕЖА АО</td>
                                <td><span></span></td>
                                <td><a href="https://ru.tradingview.com/chart/TIjNJwpX/" target="_blank">4H<i>OUR</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>ФосАгро</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://www.tradingview.com/chart/NEfitaLE/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            <tr>
                                <td>ФСК</td>
                                <td><span></span></td>
                                <td><span></span></td>
                                <td><a href="https://www.tradingview.com/chart/afCvXq5j/" target="_blank">1D<i>AY</i></a></td>
                                <td><span></span></td>
                                <td><span></span></td>
                            </tr>
                            </tbody></table>
                    </div>--}}
                </div>
                <div class="list-information1__clm">

                </div>
            </div>
        @endif
    </div>
@endsection
