<?php

use App\Http\Controllers\Admin\GraphsController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BotController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\OrderController;
use App\Http\Controllers\Front\PaymentTinkoffController;
use App\Http\Controllers\Front\ProfileController;
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

Route::get("/", [HomeController::class, "index"])->name("home");

Route::group(["prefix" => "bot"], function(){
    Route::get("set-webhook", [BotController::class, "setWebhook"])->name("bot.setWebhook");
    Route::get("get-webhook-info", [BotController::class, "getWebhookInfo"])->name("bot.getWebhookInfo");
    Route::post("webhook", [BotController::class, "webhook"])->name("bot.webhook");
});

Route::post("ajax/check-auth", function() {
    return auth()->user() ? 1 : 0;
})->name("checkAuth");

Route::group(["prefix" => "profile", "middleware" => "auth"], function(){
    Route::get("/", [ProfileController::class, "profile"])->name("user.profile");
    Route::get("logout", [ProfileController::class, "logout"])->name("user.logout");
    Route::get("graphs", [ProfileController::class, "graphs"])->name("user.graphs");
    Route::get("subscriptions/with-categories", [ProfileController::class, "subscriptionsWithCategories"])->name("user.subscriptions-with-categories");
    Route::get("subscriptions/without-categories", [ProfileController::class, "subscriptionsWithoutCategories"])->name("user.subscriptions-without-categories");

    Route::put("user/{id}", [ProfileController::class, "update"])->name("user.update");
});

Route::group(["prefix" => "payment", "middleware" => "auth"], function(){
    Route::post("tinkoff/callback ", [PaymentTinkoffController::class, "callback"])->name("payment.tinkoff.callback");
    Route::get("tinkoff/success", [PaymentTinkoffController::class, "success"])->name("payment.tinkoff.success");
    Route::get("tinkoff/fail", [PaymentTinkoffController::class, "fail"])->name("payment.tinkoff.fail");

    Route::post("order", [OrderController::class, "store"])->name("order.create");
});

Route::group(["prefix" => "order", "middleware" => "auth"], function(){
    Route::post("/", [OrderController::class, "store"])->name("order.create");
});

Route::group(["prefix" => "admin"], function () {
    Voyager::routes();

    Route::get("graphs", [GraphsController::class, "index"])->name("voyager.graph.index");
    Route::get("subscription/create", [SubscriptionController::class, "create"])->name("voyager.subscription.create");
    Route::get("subscription/{id}/edit", [SubscriptionController::class, "edit"])->name("voyager.subscription.edit");
    Route::post("subscription", [SubscriptionController::class, "store"])->name("voyager.subscription.store");
    Route::put("subscription/{id}", [SubscriptionController::class, "update"])->name("voyager.subscription.update");

    Route::get("users/create", [UserController::class, "create"])->name("voyager.users.create");
    Route::post("users", [UserController::class, "store"])->name("voyager.users.store");
    Route::put("users/{id}", [UserController::class, "update"])->name("voyager.users.update");
    Route::post("users/actions", [UserController::class, "actions"])->name("voyager.users.actions");
    Route::post("users/send-telegram-message", [UserController::class, "sendTelegramMessage"])->name("voyager.users.send-telegram-message");
    Route::post("users/telegram-messages", [UserController::class, "telegramMessages"])->name("voyager.users.telegram-messages");
    Route::post("users/new-telegram-messages", [UserController::class, "newTelegramMessages"])->name("voyager.users.new-telegram-messages");

    Route::post("ajax/subscription/periods", [SubscriptionController::class, "periods"])->name("voyager.subscription.periods");

    Route::post("ajax/subscription/full-description", [SubscriptionController::class, "fullDescription"])->name("voyager.subscription.period.full-description");

    Route::put("ajax/graphs/order", [GraphsController::class, "orderGraphs"])->name("voyager.graph.order");
    Route::delete("ajax/graphs", [GraphsController::class, "deleteGraphs"])->name("voyager.graph.delete");
    Route::post("ajax/graphs", [GraphsController::class, "storeGraphs"])->name("voyager.graph.store");
    Route::put("ajax/graphs", [GraphsController::class, "updateGraphs"])->name("voyager.graph.update");
});
