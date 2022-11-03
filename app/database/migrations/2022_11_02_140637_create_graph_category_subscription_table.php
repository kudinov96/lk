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
        Schema::create("graph_category_subscription", function (Blueprint $table) {
            $table->unsignedBigInteger("graph_category_id");
            $table->unsignedBigInteger("subscription_id");

            $table->foreign("graph_category_id")->references("id")->on("graph_category")->onDelete("cascade");
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
        Schema::dropIfExists("graph_category_subscription");
    }
};
