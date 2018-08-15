<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VerifiedUser extends Model
{
    //
    protected $fillable = ['user_id', 'verified', 'email'];
    
}
