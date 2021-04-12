<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceExceptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_exceptions', function (Blueprint $table) {
            $table->id();
            $table->integer("restaurant_id");
            $table->date("service_date");
            $table->string("title")->nullable();
            $table->text("description")->nullable();
            $table->time("start")->nullable();
            $table->time("finish")->nullable();
            $table->time("last_booking")->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->boolean("closed")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_exceptions');
    }
}
