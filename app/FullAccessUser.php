<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FullAccessUser extends Model
{
    //
    protected $fillable = ['user_id', 'token'];
}
