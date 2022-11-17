<!DOCTYPE html>
<html lang="ru" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <!--[if IE]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <title>@yield('title')</title>
    <!--[if lt IE 9]> <link href= "css/ie8.css" rel= "stylesheet" media= "all" /> <![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="{{ asset("css/style.css") }}" type="text/css" />
    @vite('resources/scss/app/custom.scss')
</head>

<body>
<div class="page">
    <div class="page-wrapper">
        <div class="mobile-menu1">
            <div class="mobile-menu1__black"></div>
            <ul class="mobile-menu1__list">
               <li class="mobile-menu1__item"><a href="{{ route("user.subscriptions-with-categories") }}">Профиль</a></li>
                <li class="mobile-menu1__item"><a href="{{ route("user.subscriptions-with-categories") }}">Графики</a></li>
            </ul>
        </div>
        <div class="background-top1">
            <div class="page-line">
                <div class="header mod1">
                    <div class="hide-mobile1">
                        <a href="" class="header__logo"><img src="{{ asset("img/logo.svg") }}" alt=""></a>
                    </div>
                    <div class="show-mobile1">
                        <a href="" class="header__logo"><img src="{{ asset("img/logo2.svg") }}" alt=""></a>
                    </div>
                    <div class="hide-mobile1">
                        <div class="menu-button1"></div>
                    </div>
                    <div class="show-mobile1">
                        <div class="menu-button2"></div>
                    </div>
                    <div class="header__right">
                        <a href="tel:{{ setting('site.manager_phone') }}" class="header__phone mod1">{{ setting('site.manager_phone') }}</a>
                        <div class="header__callback2"><a href="#contacts"><span>Все контакты</span> →</a></div>
						@can('browse_admin')
							<div class="header__callback2"><a target="_blank" href="/admin"><span>Админ-панель</span></a></div>
						@endcan
                    </div>
					<div class="drop-menu1">
                        <div class="drop-menu1__close"><i></i></div>
                        <div class="drop-menu1__over">
                            <ul class="drop-menu1__menu">
								@widget('MainMenu', [ "menu" => "header_menu", "menu_class" => "drop-menu1__menu-item" ])
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="padding4">
            @yield("content")
        </div>
        <div class="background-bottom1">
            <div class="hide-mobile1">
                <div class="background-bottom1__background" style="background-image: url({{ asset("img/bg4.jpg") }});"></div>
            </div>
            <div class="show-mobile1">
                <div class="background-bottom1__background" style="background-image: url({{ asset("img/bg7.jpg") }});"></div>
            </div>
            <div class="page-line" id="contacts">
                <div class="have-questions1">
                    <div class="have-questions1__left">
                        <div class="have-questions1__title1">Есть <br>вопросы?</div>
                    </div>
                    <div class="have-questions1__right">
                        <div class="have-questions1__title2">Задайте их моему личному менеджеру:</div>
                        <div class="have-questions1__list">
                            <a href="https://t.me/{{ setting('site.manager_tg') }}" class="have-questions1__link icon1">{{'@'}}{{ setting('site.manager_tg') }}</a>
                            <a href="tel:{{ setting('site.manager_phone') }}" class="have-questions1__link icon2">{{ setting('site.manager_phone') }}</a>
                        </div>
                    </div>
                </div>
                <div class="footer1">
                    <div class="footer1__over">
                        <div class="footer1__left">
                            <a href="" class="footer1__logo"><img src="{{ asset("img/logo.svg") }}" alt=""></a>
                        </div>
                        <div class="footer1__right">
                            <ul class="menu1">
								@widget('MainMenu', [ "menu" => "footer_menu_1", "menu_class" => "menu1__item" ])
                            </ul>
                            <ul class="menu2">
								@widget('MainMenu', [ "menu" => "footer_menu_2", "menu_class" => "menu2__item" ])
							</ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script src="{{ asset("js/app.js") }}"></script>
<script src="{{ asset("js/common.js") }}"></script>
@yield("scripts")
@yield("javascript")
</body>
</html>
