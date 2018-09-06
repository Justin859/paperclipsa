<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeamAdminRequest extends Model
{
    //

    protected $fillable = ['user_id', 'team_id', 'message', 'status', 'venue_id', 'checked_by'];
}
