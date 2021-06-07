<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBookingPreAuthColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("bookings", function (Blueprint $table){
            $table->string("payment_method")->nullable();
            $table->string("payment_intent")->nullable();
            $table->decimal("no_show_fee")->nullable();
            $table->dateTime("no_show_charged_at")->nullable();
            $table->text("reject_reason")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method',
                'payment_intent',
                'no_show_charged',
                'no_show_charged_at',
                'reject_reason',
            ]);
        });
    }
}
