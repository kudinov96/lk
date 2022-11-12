@extends("app.layout")

@section("content")
    <div class="page-line">
        <div class="block-lk1 style1">
            <div class="block-lk1__title-top">Все подписки</div>
            <table class="table-subscribe1">
                @foreach($subscriptions as $key => $subscription)
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
            </table>
        </div>
    </div>

    <x-subscription-buy-modal></x-subscription-buy-modal>
@stop

@section("javascript")
    <x-subscription-buy-js></x-subscription-buy-js>
@stop
