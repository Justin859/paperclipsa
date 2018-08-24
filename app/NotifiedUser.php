<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotifiedUser extends Model
{
    //

    protected $fillable = ['user_id', 'notifications', 'enabled'];
}
