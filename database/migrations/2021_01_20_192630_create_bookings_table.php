<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer("restaurant_id");
            $table->dateTime("booked_at");
            $table->dateTime("finish_at");
            $table->integer("table_id");
            $table->integer("covers");
            $table->integer("booked_by")->nullable();
            $table->string("name");
            $table->string("contact_number");
            $table->string("email")->nullable();
            $table->string("status")->default("pending");
            $table->text("comments")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
