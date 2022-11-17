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
        Schema::table("order", function (Blueprint $table) {
            $table->dropForeign(["user_id"]);
            $table->dropForeign(["subscription_id"]);
            $table->dropForeign(["period_id"]);

            $table->unsignedBigInteger("user_id")->nullable()->change();
            $table->unsignedBigInteger("subscription_id")->nullable()->change();
            $table->unsignedBigInteger("period_id")->nullable()->change();

            $table->foreign("user_id")->references("id")->on("users")->nullOnDelete();
            $table->foreign("subscription_id")->references("id")->on("subscription")->nullOnDelete();
            $table->foreign("period_id")->references("id")->on("period")->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("order", function (Blueprint $table) {
            $table->dropForeign(["user_id"]);
            $table->dropForeign(["subscription_id"]);
            $table->dropForeign(["period_id"]);

            $table->unsignedBigInteger("user_id")->change();
            $table->unsignedBigInteger("subscription_id")->change();
            $table->unsignedBigInteger("period_id")->change();

            $table->foreign("user_id")->references("id")->on("users");
            $table->foreign("subscription_id")->references("id")->on("subscription");
            $table->foreign("period_id")->references("id")->on("period");
        });
    }
};
