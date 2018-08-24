<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SoccerSchoolsStream extends Model
{
    //

    protected $fillable = ['name', 'stream_type', 'venue_id', 'camera_port', 'uri', 'http_url', 'storage_location'];
}
