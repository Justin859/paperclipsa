<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'last_login', 'active_status', 'venue_id'
    ];

    // user that owns this admin or field manager account
    public function user()
    {
        return $this->belongsTo('App\User')->withDefault(['firstname' => 'Guest']);
    }

    public function venue()
    {
        return $this->belongsTo('App\Venue');
    }
}
