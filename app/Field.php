<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    protected $fillable = ['venue_id', 'name', 'port_names', 'ports',];

    public function venue()
    {
        return $this->belongsTo('App\Venue');
    }
}
