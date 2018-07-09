<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SquashFixture extends Model
{
    //
    protected $fillable = [
        'squash_stream_id',
        'venue_id',
        'player_1',
        'player_2',
        'rounds',
        'round_points',
        'points',
        'date_time'
    ];
}
