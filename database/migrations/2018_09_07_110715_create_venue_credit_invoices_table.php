<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVenueCreditInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venue_credit_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('invoice_id')->default(uniqid());
            $table->integer('venue_id');
            $table->integer('user_id');
            $table->integer('user_invoiced');
            $table->integer('amount_given');
            $table->dateTime('date_time');
            $table->enum('status', ['paid', 'owed'])->default('owed');
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
        Schema::dropIfExists('venue_credit_invoices');
    }
}
