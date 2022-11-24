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
        Schema::table("subscription_users", function (Blueprint $table) {
            $table->dateTime("auto_renewal_try_date")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("subscription_users", function (Blueprint $table) {
            $table->dropColumn("auto_renewal_try_date");
        });
    }
};
