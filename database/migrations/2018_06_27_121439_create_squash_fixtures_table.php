<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSquashFixturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('squash_fixtures', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('squash_stream_id');
            $table->integer('venue_id');
            $table->string('player_1');
            $table->string('player_2');
            $table->longText('rounds'); // who won the round eg. {"round_1": "player_1", "round_2": "player_2"} as php array in eloquent  eg. ["round_1' => "player_1", "round_2" => "player_2"]
            $table->longText('round_points'); // points scored in round eg. {"round_1": {"player_1": 5, "player_2": 4}, "round_2": {"player_1": 3, "player_2": 5}}
            $table->longText('points'); // points with time scored eg. {"round_1": {"player_1": { "1": "00:56" , "2": "2:02" }, "player_2": { "1": "00:32" } }, "2": {"player_1": {"point_1": "00:54"}, "player_2": {"point_1": "1:22"} }}
            $table->dateTime('date_time'); 
            $table->enum('rally_running', ['running', 'not_running'])->default('not_running');    
            $table->enum('round_running', ['running', 'not_running'])->default('not_running');       
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
        Schema::dropIfExists('squash_fixtures');
    }
}
