<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Referee extends Model
{
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    
    protected $fillable = [
        'user_id', 'venue_id', 'last_login', 'active_status'
    ];


    // user that owns this referee
    public function user()
    {
        return $this->belongsTo('App\User')->withDefault(['firstname' => 'Guest']);
    }

    // venue that owns this referee
    public function venue()
    {
        return $this->belongsTo('App\Venue');
    }
}
