@extends("app.layout")

@section("title", "503 - Сервис недоступен")
@section("description", "503 - Сервис недоступен")

@section("content")
    <div class="page-line page-line_error">
        <h1>Ошибка №503 - Сервис недоступен</h1>
        <a href="{{ route("user.profile") }}">Перейти на страницу профиля</a>
    </div>
@stop
