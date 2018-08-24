<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SoccerSchoolsSession extends Model
{
    //

    protected $fillable = ['ss_stream_id', 'venue_id', 'date_time', 'coach_id', 'age_group_id'];
}
