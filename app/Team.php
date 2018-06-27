<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['venue_id', 'name', 'player_ids', 'active_status'];
    
    public function venue()
    {
        return $this->belongsTo('App\Venue');
    }
}
