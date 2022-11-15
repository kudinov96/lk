@extends("app.layout")
@section('title', 'Вход в кабинет')

@section("content")
<div class="page-line">
	<h1>Авторизация</h1>
   
	<br>
	<div class="block-lk1">
		<a target="_blank" href="https://t.me/{{ $botName }}?start=auth{{ $sessionId }}" class="block-lk1__link-bottom">
			<span>Войти через Telegram</span>
		</a>
	</div>
	<br>
	<br>

</div>
@stop

@section("javascript")
<script>
        $(document).ready(function() {
            setInterval(function() {
                $.ajax({
                    url: "{{ route("checkAuth") }}",
                    type: "POST",
                    success: function(response) {
                        if (response === "1") {
                            window.location.href = "{{ route("user.profile") }}";
                        }
                    },
                });
            }, 3000);
        });
    </script>
</body>
</html>

@stop