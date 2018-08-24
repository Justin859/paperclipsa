<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OnDemandNotification extends Model
{
    //
    protected $fillable = ['name', 'fixture_id', 'sent'];
}
