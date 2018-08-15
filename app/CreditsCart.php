<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreditsCart extends Model
{
    //

    protected $fillable = ['credits', 'user_id', 'purchase_status'];
}
