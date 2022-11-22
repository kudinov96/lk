@extends("app.layout")

@section("title", setting("site.subscriptions_title"))
@section("description", setting("site.subscriptions_description"))

@section("content")
    <div class="page-line">
        <div class="block-name1 block-name1_lk block-name1_lk_without-title profile-back-btn">
            <a href="{{ route("user.profile") }}" class="block-name1__exit"><span>Личный кабинет</span></a>
        </div>
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

    <x-service-buy-modal></x-service-buy-modal>
@stop

@section("javascript")
    <x-service-buy-js></x-service-buy-js>
@stop
