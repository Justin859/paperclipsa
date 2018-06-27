<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fixture extends Model
{
        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = ['admin_id', 'venue_id', 'stream_id', 'type', 'team_a', 'team_b', 'team_a_goals', 'team_b_goals', 'date_time',];
}
