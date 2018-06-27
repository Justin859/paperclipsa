<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuperUser extends Model
{
    //



    // user that owns this superuser
    public function user()
    {
        return $this->belongsTo('App\User')->withDefault(['firstname' => 'Guest']);
    }
}
