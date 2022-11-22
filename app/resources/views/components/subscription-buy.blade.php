@if($subscription->periods()->exists())
    <div class="block-lk1__button">
        <div class="block-lk1__button-select">
            <div class="select-price1">
                @php
                    $firstPeriod            = $subscription->periods()->wherePivot("is_default", true)->first();
                    $priceAfterDiscount     = $firstPeriod->priceAfterDiscount($subscription->id);
                    $fullPaymentDescription = $firstPeriod->full_count_name_human . " — <span class='discount-price'>" . $firstPeriod->pivot->price . "</span> → " . $firstPeriod->fullDescription($subscription->id);
                @endphp
                @if ($priceAfterDiscount["discount"])
                    <div class="item__current-period select-price1__current" data-period-id="{{ $firstPeriod->id }}">
                        <div>
                            {!! $fullPaymentDescription !!}
                        </div>
                    </div>
                @else
                    <div class="item__current-period select-price1__current" data-period-id="{{ $firstPeriod->id }}">
                        <div>
                            {{ $firstPeriod->full_count_name_human }} — {{ $firstPeriod->pivot->price }} руб.
                        </div>
                    </div>
                @endif
                <div class="select-price1__drop">
                    @foreach($subscription->periods as $period)
                        @php
                            $priceAfterDiscount = $period->priceAfterDiscount($subscription->id);
                            $fullPaymentDescription = $period->full_count_name_human . " — <span class='discount-price'>" . $period->pivot->price . "</span> → " . $period->fullDescription($subscription->id);
                        @endphp
                        @if ($priceAfterDiscount["discount"])
                            <div class="select-price1__drop-item" data-period-id="{{ $period->id }}">
                                {!! $fullPaymentDescription !!}
                            </div>
                        @else
                            <div class="select-price1__drop-item" data-period-id="{{ $period->id }}">
                                {{ $period->full_count_name_human }} — {{ $period->pivot->price }} руб.
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <a href="#" data-fancybox data-src="#modal-service-payment" data-service-id="{{ $subscription->id }}" data-service-type="{{ \App\Models\Subscription::class }}" class="item__buy block-lk1__button-buy">@if($user->subscriptions()->where("id", $subscription->id)->exists())ПРОДЛИТЬ ПОДПИСКУ@elseКУПИТЬ ПОДПИСКУ@endif</a>
    </div>
@endif
