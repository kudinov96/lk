<?php

namespace App\Providers;

use App\Services\Payment\TinkoffPaymentService;
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
        $this->app->singleton(TinkoffPaymentService::class, function () {
            return new TinkoffPaymentService(
                acquiring_url: "https://securepay.tinkoff.ru/v2/",
                terminal_id: config("payment.tinkoff_terminal_key"),
                secret_key: config("payment.tinkoff_secret_key"),
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
