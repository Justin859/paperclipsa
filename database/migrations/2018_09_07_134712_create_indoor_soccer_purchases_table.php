<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndoorSoccerPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indoor_soccer_purchases', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('stream_id');
            $table->integer('user_id');
            $table->dateTime('date_purchased');
            $table->integer('venue_id');
            $table->integer('amount_paid');
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
        Schema::dropIfExists('indoor_soccer_purchases');
    }
}
