<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    //
    protected $fillable = ['voucher_key', 'used', 'venue_id', 'points_value'];

}
