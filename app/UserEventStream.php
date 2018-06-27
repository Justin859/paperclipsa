<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserEventStream extends Model
{
    //
    protected $fillable = ['user_id', 'event_stream_ids', 'vod_ids', 'live_ids'];
}
