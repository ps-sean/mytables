<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId("booking_id");
            $table->foreignId("user_id");
            $table->tinyInteger("price")->nullable();
            $table->tinyInteger("service")->nullable();
            $table->tinyInteger("product")->nullable();
            $table->tinyInteger("cleanliness")->nullable();
            $table->tinyInteger("overall");
            $table->string("title");
            $table->text("review")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
