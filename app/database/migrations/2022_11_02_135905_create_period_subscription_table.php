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
        Schema::create("period_subscription", function (Blueprint $table) {
            $table->unsignedBigInteger("period_id");
            $table->unsignedBigInteger("subscription_id");
            $table->unsignedBigInteger("price")->nullable();

            $table->foreign("period_id")->references("id")->on("period")->onDelete("cascade");
            $table->foreign("subscription_id")->references("id")->on("subscription")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("period_subscription");
    }
};
