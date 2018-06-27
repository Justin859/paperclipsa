<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pricing extends Model
{
    protected $fillable = ['standard_venue', 'venue_id', 'stream_id', 'training', 'match', 'tournament', 'currency_mode',];
}
