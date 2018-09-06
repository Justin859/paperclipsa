<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeamPlayer extends Model
{
    //
    protected $fillable = ['user_id', 'team_id', 'active_status'];
}
