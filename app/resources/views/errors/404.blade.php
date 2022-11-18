@extends("app.layout")

@section("title", "404 - Страница не найдена")
@section("description", "404 - Страница не найдена")

@section("content")
    <div class="page-line page-line_error">
        <h1>Ошибка №404 - Страница не найдена</h1>
        <a href="{{ route("user.profile") }}">Перейти на страницу профиля</a>
    </div>
@stop
