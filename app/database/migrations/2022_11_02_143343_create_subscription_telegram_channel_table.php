<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("subscription_telegram_channel", function (Blueprint $table) {
            $table->unsignedBigInteger("subscription_id");
            $table->unsignedBigInteger("telegram_channel_id");

            $table->foreign("subscription_id")->references("id")->on("subscription")->onDelete("cascade");
            $table->foreign("telegram_channel_id")->references("id")->on("telegram_channel")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("subscription_telegram_channel");
    }
};
