@extends("app.layout")

@section("title", setting("site.title"))
@section("description", setting("site.description"))

@section("content")
    <div class="page-line">
        <h1>Авторизация</h1>

        <br>
        <div class="block-lk1">
			
            <a target="_blank" href="https://telegram.im/{{ $botName }}?start=auth{{ $sessionId }}" id="auth_link" class="block-lk1__link-bottom">
                <span>Войти через Telegram</span>
            </a>
			<div class="qr_code "><h2>Или отсканировать QR-Код</h2><a target="_blank" href="https://t.me/{{ $botName }}?start=auth{{ $sessionId }}"><img id="qr_code"></a></div>
        </div>
        <br>
        <br>
    </div>
@stop

@section("javascript")
<style>
	.block-lk1 {
		display: flex;
		align-items: flex-start;
		justify-content: flex-start;
		padding-left: 0px;
	}
	.block-lk1 > * {
		max-width: 400px;
		position: relative;
		margin-right: 150px;
		bottom:auto;
	}
	.qr_code h2 {
		position: absolute;
		margin-top: -70px;
		width: 400px;
	}
	@media (max-width: 768px){
		.qr_code { display: none !important; }
	}
</style>
<script src="/js/qrious.js"></script>
<script>
        $(document).ready(function() {
            setInterval(function() {
                $.ajax({
                    url: "{{ route("checkAuth") }}",
                    type: "POST",
                    success: function(response) {
                        if (response === "1") {
                            window.location.href = "{{ route("user.graphs") }}";
                        }
                    },
                });
            }, 3000);
			if(typeof QRious != 'undefined') {
				var qrcode = new QRious({
					  element: document.getElementById('qr_code'),
					  value: document.getElementById('auth_link').href.replace('telegram.im','t.me'),
					  level: 'H',
					  padding: 0,
					  size: 300,
				});
			}
        });
    </script>
	
@stop
