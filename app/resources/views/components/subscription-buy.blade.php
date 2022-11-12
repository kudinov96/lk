@if($subscription->periods()->exists())
    <div class="block-lk1__button">
        <div class="block-lk1__button-select">
            <div class="select-price1">
                @php
                    $firstPeriod        = $subscription->periods()->first();
                    $priceAfterDiscount = $firstPeriod->priceAfterDiscount($subscription->id);
                @endphp
                @if ($priceAfterDiscount["discount"])
                    <div class="item__current-period select-price1__current" data-period-id="{{ $firstPeriod->id }}"><div>{{ $firstPeriod->full_count_name_human }} — <span>{{ $firstPeriod->pivot->price }}</span> → {{ $priceAfterDiscount["price"] }} руб. (скидка {{ $priceAfterDiscount["discount"] }}%)</div></div>
                @else
                    <div class="item__current-period select-price1__current" data-period-id="{{ $firstPeriod->id }}"><div>{{ $firstPeriod->full_count_name_human }} — {{ $firstPeriod->pivot->price }} руб.</div></div>
                @endif
                <div class="select-price1__drop">
                    @foreach($subscription->periods as $period)
                        @php $priceAfterDiscount = $period->priceAfterDiscount($subscription->id); @endphp
                        @if ($priceAfterDiscount["discount"])
                            <div class="select-price1__drop-item" data-period-id="{{ $period->id }}">{{ $period->full_count_name_human }} — <span>{{ $period->pivot->price }}</span> → {{ $priceAfterDiscount["price"] }} руб. (скидка {{ $priceAfterDiscount["discount"] }}%)</div>
                        @else
                            <div class="select-price1__drop-item" data-period-id="{{ $period->id }}">{{ $period->full_count_name_human }} — {{ $period->pivot->price }} руб.</div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <a href="#" data-fancybox data-src="#modal-subscription-payment" data-subscription-id="{{ $subscription->id }}" class="item__buy block-lk1__button-buy">@if($user->subscriptions()->where("id", $subscription->id)->exists())ПРОДЛИТЬ ПОДПИСКУ@elseКУПИТЬ ПОДПИСКУ@endif</a>
    </div>
@endif
