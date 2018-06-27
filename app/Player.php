<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
     /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    
    protected $fillable = [
        'user_id', 'name', 'team_ids', 'last_login', 'active_status'
    ];
    
    // user that owns this player
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
