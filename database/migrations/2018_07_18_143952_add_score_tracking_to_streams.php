<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddScoreTrackingToStreams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('fixtures', function($table) {
            $table->longText('score_tracking')->nullable(); // json string eg. {1: {"team_1_score": 0, "team_2_score": 1, "time_scored": "00:01:02"}, 2: {"team_1_score": 0, "team_2_score": 2, "time_scored": "00:04:31"}}
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('fixtures', function($table) {
            $table->dropColumn('score_tracking');
        });
    }
}
