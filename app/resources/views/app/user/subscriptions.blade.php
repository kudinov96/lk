@extends("app.layout")

@section("content")
    <div class="page-line">
        <div class="block-lk1 style1">
            <div class="block-lk1__title-top">Все подписки</div>
            <table class="table-subscribe1">
                @foreach($subscriptions as $key => $subscription)
                    <tr>
                        <td>
                            <div class="table-subscribe1__title1"><span>{{ $subscription->title }}</span></div>
                        </td>
                        <td>
                            <a href="#" data-fancybox data-src="#modal-subscription-{{ $key }}" class="table-subscribe1__more">Подробнее</a></td>
                        <td>
                            @if($subscription->periods()->exists())
                                <div class="block-lk1__button">
                                    <div class="block-lk1__button-select">
                                        <div class="select-price1">
                                            @php
                                                $firstPeriod        = $subscription->periods()->first();
                                                $priceAfterDiscount = $firstPeriod->priceAfterDiscount($subscription->id);
                                            @endphp
                                            @if ($priceAfterDiscount["discount"])
                                                <div class="select-price1__current"><div>{{ $firstPeriod->full_count_name_human }} — <span>{{ $firstPeriod->pivot->price }} ₽</span> → {{ $priceAfterDiscount["price"] }} ₽ (скидка {{ $priceAfterDiscount["discount"] }}%)</div></div>
                                            @else
                                                <div class="select-price1__current"><div>{{ $firstPeriod->full_count_name_human }} — {{ $firstPeriod->pivot->price }} ₽</div></div>
                                            @endif
                                            <div class="select-price1__drop">
                                                @foreach($subscription->periods as $period)
                                                    @php $priceAfterDiscount = $period->priceAfterDiscount($subscription->id); @endphp
                                                    @if ($priceAfterDiscount["discount"])
                                                        <div class="select-price1__drop-item">{{{ $period->full_count_name_human }}} — <span>{{ $period->pivot->price }} ₽</span> → {{ $priceAfterDiscount["price"] }} ₽ (скидка {{ $priceAfterDiscount["discount"] }}%)</div>
                                                    @else
                                                        <div class="select-price1__drop-item">{{{ $period->full_count_name_human }}} — {{ $period->pivot->price }} ₽</div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <a href="" class="block-lk1__button-buy">@if($user->subscriptions()->where("id", $subscription->id)->exists())ПРОДЛИТЬ ПОДПИСКУ@elseКУПИТЬ ПОДПИСКУ@endif</a>
                                </div>
                            @endif
                        </td>
                        <div class="hidden">
                            <div id="modal-subscription-{{ $key }}">
                                <h2>{{ $subscription->title }}</h2>
                                {!! $subscription->content !!}
                            </div>
                        </div>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@stop
