<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    //
    protected $fillable = ['venue_id', 'eventName', 'cameras', 'applicationName'];
}
