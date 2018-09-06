<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeamAdmin extends Model
{
    //
    protected $fillable = ['user_id', 'team_id', 'active_status'];
}
