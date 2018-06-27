<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SingleAccessUser extends Model
{
    //
    protected $fillable = ['user_id', 'venue_id', 'token'];
}
