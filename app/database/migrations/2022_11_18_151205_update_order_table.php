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
            $table->dropForeign(["subscription_id"]);

            $table->unsignedBigInteger("period_id")->nullable()->change();
            $table->renameColumn("subscription_id", "service_id");
            $table->string("service_type")->nullable();
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
            $table->foreign("subscription_id")->references("id")->on("subscription")->nullOnDelete();

            $table->unsignedBigInteger("period_id")->change();
            $table->renameColumn("service_id", "subscription_id");
            $table->dropColumn("service_type");
        });
    }
};
