<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IndoorSoccerPurchase extends Model
{
    //
    protected $fillable = ['stream_id', 'user_id', 'date_purchased', 'venue_id', 'amount_paid'];
}
