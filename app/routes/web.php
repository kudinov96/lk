<?php

use App\Http\Controllers\Admin\GraphsController;
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
    Route::get("set-webhook", [BotController::class, "setWebhook"])->name("bot.setWebhook");
    Route::get("get-webhook-info", [BotController::class, "getWebhookInfo"])->name("bot.getWebhookInfo");
    Route::post("webhook", [BotController::class, "webhook"])->name("bot.webhook");
});

Route::post("ajax/check-auth", function() {
    return auth()->user() ? 1 : 0;
})->name("checkAuth");

Route::get("profile", function() {
    return "Profile page";
})->name("profile");

Route::group(["prefix" => "admin"], function () {
    Voyager::routes();

    Route::get("graphs", [GraphsController::class, "index"])->name("voyager.graph.index");

    Route::put("ajax/graphs/order", [GraphsController::class, "orderGraphs"])->name("voyager.graph.order");
    Route::delete("ajax/graphs", [GraphsController::class, "deleteGraphs"])->name("voyager.graph.delete");
    Route::post("ajax/graphs", [GraphsController::class, "createGraphs"])->name("voyager.graph.create");
    Route::put("ajax/graphs", [GraphsController::class, "updateGraphs"])->name("voyager.graph.update");
});
