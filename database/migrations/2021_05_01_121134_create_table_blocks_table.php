<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_blocks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId("restaurant_id");
            $table->dateTime("start_date");
            $table->dateTime("end_date");
            $table->json("tables");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_blocks');
    }
}
