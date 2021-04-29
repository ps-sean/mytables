<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId("restaurant_id");
            $table->timestamp("start")->nullable();
            $table->timestamp("end")->nullable();
            $table->integer("starting_tables")->default(0);
            $table->decimal("expected")->default(0);
            $table->json("notes")->nullable();
            $table->decimal("actual")->default(0);
            $table->timestamp("charged_at")->nullable();
            $table->string("payment_reference")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
