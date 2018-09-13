<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VenueCreditInvoice extends Model
{
    //
    protected $fillable = ['user_id', 'venue_id', 'amount_given', 'date_time', 'status', 'user_invoiced'];
}
