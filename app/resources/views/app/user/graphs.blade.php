@extends("app.layout")

@section("title", setting("site.graphs_title"))
@section("description", setting("site.graphs_description"))

@section("content")
    <div class="page-line">
        @if($subscriptions->exists())
            <div class="title2"><span>Графики от Романа Андреева</span></div>
            <div class="list-information1">
                <div class="list-information1__clm">
                    @foreach($subscriptions->get() as $subscription)
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
        @else
            <div class="block-lk1 style2">
                <div class="block-lk1__title-top">Графики с аналитикой</div>
                <table class="table-subscribe1">
                    <tr class="item">
                        <td>
                            <p>У вас нет купленных графиков</p>
                        </td>
                    </tr>
                </table>
                <a href="{{ route("user.subscriptions-with-categories") }}" class="block-lk1__link-bottom">Все варианты подписок на графики</a>
            </div>
        @endif
    </div>
@endsection
