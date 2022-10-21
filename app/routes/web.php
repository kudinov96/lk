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

Route::get('/', [HomeController::class, 'index']);

Route::get('bot/setWebhook', [BotController::class, 'setWebhook'])->name('bot.setWebhook');
Route::post('webhook', [BotController::class, 'webhook'])->name('bot.webhook');

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
