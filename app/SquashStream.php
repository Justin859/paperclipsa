<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SquashStream extends Model
{
    //
    protected $fillable = [
        'name',
        'thumbnail',
        'stream_type',
        'venue_id',
        'camera_port',
        'fixture_id',
        'uri',
        'http_url'
    ];
}
