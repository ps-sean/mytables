<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OpenHoursExceptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('open_hours_exceptions', function (Blueprint $table) {
            $table->id();
            $table->integer("restaurant_id");
            $table->date("open_date");
            $table->time("open")->nullable();
            $table->time("close")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('open_hours_exceptions');
    }
}
