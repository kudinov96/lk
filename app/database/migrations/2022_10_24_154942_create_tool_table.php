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
        Schema::create("tool", function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->jsonb("data");
            $table->unsignedBigInteger("graph_category_id");
            $table->timestamps();

            $table->foreign("graph_category_id")->references("id")->on("graph_category")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("tool");
    }
};
