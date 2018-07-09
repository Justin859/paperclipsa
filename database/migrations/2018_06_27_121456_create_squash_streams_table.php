<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSquashStreamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('squash_streams', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('thumbnail')->nullable();
            $table->enum('stream_type', ['none', 'live', 'vod']);
            $table->integer('venue_id');
            $table->integer('camera_port');
            $table->string('uri')->nullable();
            $table->string('http_url')->nullable();
            $table->string('storage_location')->default('VOD_STORAGE_1');
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
        Schema::dropIfExists('squash_streams');
    }
}
