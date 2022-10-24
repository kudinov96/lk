<?php

use App\Http\Controllers\BotController;
use App\Http\Controllers\Front\HomeController;
use Illuminate\Support\Facades\Route;
use TCG\Voyager\Facades\Voyager;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get("/", [HomeController::class, "index"]);

Route::group(["prefix" => "bot"], function(){
    Route::get("setWebhook", [BotController::class, "setWebhook"])->name("bot.setWebhook");
    Route::get("getWebhookInfo", [BotController::class, "getWebhookInfo"])->name("bot.getWebhookInfo");
    Route::post("webhook", [BotController::class, "webhook"])->name("bot.webhook");
});

Route::post("ajax/checkAuth", function() {
    return auth()->user() ? 1 : 0;
})->name("checkAuth");

Route::get("profile", function() {
    return "Profile page";
})->name("profile");

Route::group(["prefix" => "admin"], function () {
    Voyager::routes();
});
