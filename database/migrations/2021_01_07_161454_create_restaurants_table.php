<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->string('address_line_1');
            $table->string('vicinity');
            $table->string('country');
            $table->string('postal_code');
            $table->decimal('lat', '15', '10');
            $table->decimal('lng', '15', '10');
            $table->string("stripe_acct_id")->nullable();
            $table->text("open_hours")->nullable();
            $table->string("image_location")->nullable();
            $table->timestamps();
            $table->string("table_confirmation")->default('automatic');
            $table->string("booking_timeframe")->default('0:0');
            $table->string("status")->default("pending");
            $table->integer("turnaround_time")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restaurants');
    }
}
