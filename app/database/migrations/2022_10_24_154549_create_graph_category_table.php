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
        Schema::create("graph_category", function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->unsignedBigInteger("parent_id")->nullable();
            $table->string("color_title")->nullable();
            $table->string("color_border")->nullable();
            $table->timestamps();

            $table->foreign("parent_id")->references("id")->on("graph_category")->onDelete("set null");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('graph_category');
    }
};
