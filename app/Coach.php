<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coach extends Model
{
    //

    protected $fillable = ['user_id', 'venue_id', 'active_status'];
}
