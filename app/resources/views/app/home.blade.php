<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
</head>
<body>
    <h1>Авторизация</h1>
    <p><a target="_blank" href="https://t.me/{{ $botName }}?start=auth{{ $sessionId }}">Войти через Telegram</a></p>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
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
