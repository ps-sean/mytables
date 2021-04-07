<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId("booking_id");
            $table->timestamps();
            $table->foreignId("created_by");
            $table->text("message");
            $table->timestamp("read_at")->nullable();
            $table->timestamp("emailed_at")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_messages');
    }
}
