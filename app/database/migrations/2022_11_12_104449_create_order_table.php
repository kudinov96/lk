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
        Schema::create("order", function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("description");
            $table->unsignedInteger("amount");
            $table->string("name");
            $table->string("email");
            $table->string("phone");
            $table->string("status");
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("subscription_id");
            $table->unsignedBigInteger("period_id");
            $table->timestamps();

            $table->foreign("user_id")->references("id")->on("users");
            $table->foreign("subscription_id")->references("id")->on("subscription");
            $table->foreign("period_id")->references("id")->on("period");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("order");
    }
};
