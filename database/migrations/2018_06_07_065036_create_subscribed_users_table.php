<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscribedUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscribed_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id');
            $table->string('pf_payment_id');
            $table->string('payment_status');
            $table->string('item_name');
            $table->string('amount_gross');
            $table->string('amount_fee');
            $table->string('amount_net');
            $table->string('token');
            $table->string('signature');
            $table->string('subscription_date');
            $table->enum('status', ['inactive', 'active'])->default('inactive');
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
        Schema::dropIfExists('subscribed_users');
    }
}
