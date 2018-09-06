<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeamProfile extends Model
{
    //
    protected $fillable = ['team_id', 'description', 'logo'];
}
