<?php

namespace App\Providers;

use App\Services\Payment\TinkoffPaymentService;
use App\Services\TelegramBotService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(TinkoffPaymentService::class, function () {
            return new TinkoffPaymentService(
                acquiring_url: "https://securepay.tinkoff.ru/v2/",
                terminal_id: config("payment.tinkoff_terminal_key"),
                secret_key: config("payment.tinkoff_secret_key"),
            );
        });

        $this->app->singleton(TelegramBotService::class, function () {
            return new TelegramBotService(
                api_token: config("bot.bot_api_token"),
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
    }
}
