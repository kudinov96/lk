@extends("app.layout")

@section("title", setting("site.graphs_title"))
@section("description", setting("site.graphs_description"))

@section("content")
    <div class="page-line">
        <div class="block-name1 block-name1_lk profile-back-btn">
            <a href="{{ route("user.profile") }}" class="block-name1__exit"><span>Личный кабинет</span></a>
        </div>
        <div class="title2 title2_lk"><span>Графики от Романа Андреева</span></div>
        @if($userSubscriptionsWithCategories->isNotEmpty())
            <div class="list-information1">
                <div class="list-information1__clm">
                    @foreach($userSubscriptionsWithCategories as $subscription)
                        @php $graph_categories = $subscription->graph_categories()->withoutParent()->with("subcategories", "tools")->get(); @endphp

                        @foreach($graph_categories as $category)
                            <div class="list-information1__item" style="border-color: {{ $category->color_border }};">
                                <div class="list-information1__title1" style="color: {{ $category->color_title }};">{{ $category->title }}</div>
                                @if($category->tools()->exists())
                                    <table class="list-information1__table">
                                        <tbody>
                                        @foreach($category->tools as $tool)
                                            <tr>
                                                <td>{{ $tool->title }}</td>
                                                @foreach($tool->data as $data_item)
                                                    @if(!$data_item["url"] || !$data_item["interval"])
                                                        <td><span></span></td>
                                                    @else
                                                        @php
                                                            $has_subscribes=true;
                                                        @endphp
                                                        <td><a href="{{ $data_item["url"] }}" target="_blank">{{ $data_item["interval"] . $data_item["interval_code"] }}</a></td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @endif
                                @if($category->subcategories()->exists())
                                    @foreach ($category->subcategories as $subcategory)
                                        <div class="list-information1__title2" style="color: {{ $subcategory->color_title }};">{{ $subcategory->title }}</div>
                                        @if($subcategory->tools()->exists())
                                            <table class="list-information1__table">
                                                <tbody>
                                                @foreach($subcategory->tools as $tool)
                                                    <tr>
                                                        <td>{{ $tool->title }}</td>
                                                        @foreach($tool->data as $data_item)
                                                            @if(!$data_item["url"] || !$data_item["interval"])
                                                                <td><span></span></td>
                                                            @else
                                                                @php
                                                                    $has_subscribes=true;
                                                                @endphp
                                                                <td><a href="{{ $data_item["url"] }}" target="_blank">{{ $data_item["interval"] . $data_item["interval_code"] }}</a></td>
                                                            @endif
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        @elseif($subscriptionsWithCategories->isNotEmpty())
            <div class="block-lk1 style2">
                <div class="block-lk1__title-top">У вас нет подписок на графики</div>
                <table class="table-subscribe1">
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
                </table>
                <a href="{{ route("user.subscriptions-with-categories") }}" class="block-lk1__link-bottom">Все варианты подписок на графики</a>
            </div>
        @endif
    </div>

    <x-service-buy-modal></x-service-buy-modal>
@endsection

@section("javascript")
    <x-service-buy-js></x-service-buy-js>
@stop
