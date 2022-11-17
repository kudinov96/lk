@extends("app.layout")

@section("title", setting("site.payment_success_title"))
@section("description", setting("site.payment_success_description"))

@section("content")
    <div class="page-line">
        <div class="block-lk1__title-top">Платеж прошел успешно. <a href="{{ route("user.profile") }}">Вернуться в личный кабинет</a>.</div>
    </div>
@stop
