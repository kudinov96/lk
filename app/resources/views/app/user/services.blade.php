@extends("app.layout")

@section("title", setting("site.services_title"))
@section("description", setting("site.services_description"))

@section("content")
    <div class="page-line">
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
                        <td><a href="#" class="subscription-buy__open table-subscribe1__extend">КУПИТЬ</a></td>
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
                        <td><a href="#" class="subscription-buy__open table-subscribe1__extend">КУПИТЬ</a></td>
                    </tr>
               @endforeach
            </table>
        </div>
    </div>

@stop

@section("javascript")
@stop
