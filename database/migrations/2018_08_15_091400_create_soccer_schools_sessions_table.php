<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoccerSchoolsSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('soccer_schools_sessions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('venue_id');
            $table->integer('ss_stream_id'); // Soccer Schools Session ID
            $table->integer('coach_id');
            $table->string('age_group_id');
            $table->dateTime('date_time');
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
        Schema::dropIfExists('soccer_schools_sessions');
    }
}
