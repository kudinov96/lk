@extends("app.layout")

@section("title", setting("site.payment_fail_title"))
@section("description", setting("site.payment_fail_description"))

@section("content")
    <div class="page-line">
        <div class="block-lk1__title-top">К сожалению, платеж не удался. <a href="{{ route("user.profile") }}">Вернуться в личный кабинет</a>.</div>
    </div>
@stop
