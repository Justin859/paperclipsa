<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //if(!Schema::hasTable('users')){
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('superuser_id')->nullable()->comment('ID of super user account that belongs to this user');
            $table->integer('admin_id')->nullable()->comment('ID of admin or field manager account that belongs to this user');   
            $table->integer('referee_id')->nullable()->comment('ID of referee account that belongs to this user');             
            $table->string('firstname')->nullable();
            $table->string('surname')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('country')->nullable();
            $table->integer('location_id')->nullable()->comment('id of location on a specific country db table based on country');
            $table->string('verifyToken')->nullable();
            $table->enum('status', ['n','y']);
            $table->enum('active_status', ['active','suspended','banned']);
            $table->enum('onboarding', ['a','b','c','d']);
            $table->enum('gender', ['none','m','f']);            
            $table->enum('remember_me', ['true','false']); 
            $table->integer('last_login');           
            $table->rememberToken();
            $table->timestamps();
        });
       //}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
