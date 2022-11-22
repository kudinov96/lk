@extends("app.layout")

@section("title", setting("site.services_title"))
@section("description", setting("site.services_description"))

@section("content")
    <div class="page-line">
        <div class="block-name1 block-name1_lk block-name1_lk_without-title profile-back-btn">
            <a href="{{ route("user.profile") }}" class="block-name1__exit"><span>Личный кабинет</span></a>
        </div>
        <div class="block-lk1 style1">
            <div class="block-lk1__title-top">Все обучающие семинары</div>
            <table class="training-seminars1">
                @foreach($courses as $course)
                    <tr>
                        <td><a class="training-seminars1__video without_icon" style="background-image: url({{ Voyager::image($course->preview) }});"></a></td>
                        <td>
                            <div class="training-seminars1__title1">
                                {{ $course->title }}
                            </div>
                        </td>
                        @if(!$user->courses()->where("id", $course->id)->exists())
                            <td><a href="#" data-fancybox data-src="#modal-service-payment" data-service-id="{{ $course->id }}" data-service-type="{{ \App\Models\Course::class }}" class="item__buy subscription-buy__open table-subscribe1__extend">КУПИТЬ</a></td>
                        @endif
                    </tr>
                @endforeach
                @foreach($services as $key => $service)
                    <tr>
                        <td>
                            <a class="training-seminars1__video without_icon" style="background-image: url({{ Voyager::image($service->preview) }});"></a>
                        </td>
                        <td>
                            <div class="training-seminars1__title1">
                                {{ $service->title }}
                            </div>
                        </td>
                        @if(!$user->services()->where("id", $service->id)->exists())
                            <td><a href="#" data-fancybox data-src="#modal-service-payment" data-service-id="{{ $service->id }}" data-service-type="{{ \App\Models\Service::class }}" class="item__buy subscription-buy__open table-subscribe1__extend">КУПИТЬ</a></td>
                        @endif
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
